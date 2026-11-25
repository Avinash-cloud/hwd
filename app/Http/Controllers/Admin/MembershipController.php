<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfflineMembershipRequest;
use App\Models\Membership;
use App\Models\MembershipSubscription;
use App\Models\SiteSetting;
use App\Services\OfflineMembershipOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembershipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Membership::with(['user', 'activeSubscription'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->get('search', ''))) {
            $query->where(function ($q) use ($search): void {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $memberships = $query->paginate(20)->withQueryString();
        $monthlyBilling = MembershipSubscription::getMonthlyBillingDetails();

        return view('admin.memberships.index', [
            'memberships' => $memberships,
            'monthlyAutoDebit' => $monthlyBilling['total_monthly_charge'],
        ]);
    }

    public function createOffline(): View
    {
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $monthlyBilling = MembershipSubscription::getMonthlyBillingDetails();

        return view('admin.memberships.create-offline', compact(
            'membershipFee',
            'validityYears',
            'monthlyBilling'
        ));
    }

    public function storeOffline(
        StoreOfflineMembershipRequest $request,
        OfflineMembershipOnboardingService $onboardingService
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            $result = $onboardingService->enroll([
                ...$validated,
                'dispatch_current_month' => $request->boolean('dispatch_current_month'),
                'send_welcome_email' => $request->boolean('send_welcome_email', true),
            ]);
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $msg = "Offline membership for {$result['user']->name} ({$result['membership']->resolved_invoice_number}) added successfully.";
        if ($result['order']) {
            $msg .= " Initial monthly dispatch Order #{$result['order']->order_number} created.";
        }

        return redirect()->route('admin.memberships.index')->with('success', $msg);
    }

    public function downloadCsvTemplate(): StreamedResponse
    {
        $headers = [
            'name',
            'email',
            'phone',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'postal_code',
            'starts_at',
            'fee_paid',
            'validity_years',
            'payment_method',
            'payment_id',
            'invoice_number',
            'prepaid_months',
        ];

        $sampleRow = [
            'Rameshwar Tiwari',
            'rameshwar.sample@example.com',
            '9876543210',
            'House 42, Shivlok Colony',
            'Near Ganga Barrage',
            'Haridwar',
            'Uttarakhand',
            '249401',
            now()->subMonths(2)->format('Y-m-d'),
            SiteSetting::get('membership_fee', '500'),
            SiteSetting::get('membership_validity_years', '5'),
            'cash',
            'RCPT-OFF-101',
            '',
            '0',
        ];

        return response()->streamDownload(function () use ($headers, $sampleRow): void {
            $handle = fopen('php://output', 'w');
            if ($handle !== false) {
                fputcsv($handle, $headers);
                fputcsv($handle, $sampleRow);
                fclose($handle);
            }
        }, 'offline_memberships_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importCsv(
        Request $request,
        OfflineMembershipOnboardingService $onboardingService
    ): RedirectResponse {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
            'dispatch_current_month' => ['nullable', 'boolean'],
            'send_welcome_email' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return back()->with('error', 'Unable to open the uploaded CSV file.');
        }

        $headerRow = fgetcsv($handle);
        if (! is_array($headerRow) || empty($headerRow)) {
            fclose($handle);

            return back()->with('error', 'CSV file is empty or missing a header row.');
        }

        // Normalize UTF-8 BOM and header names
        $normalizedHeaders = array_map(function (?string $col): string {
            $cleaned = preg_replace('/^\xEF\xBB\xBF/', '', (string) $col);

            return strtolower(trim((string) $cleaned));
        }, $headerRow);

        $imported = 0;
        $skipped = 0;
        $rowErrors = [];
        $lineNumber = 1;
        $dispatchCurrent = $request->boolean('dispatch_current_month');
        $sendWelcome = $request->boolean('send_welcome_email', true);

        while (($rawRow = fgetcsv($handle)) !== false) {
            $lineNumber++;
            if ($rawRow === [null] || count(array_filter($rawRow, fn (?string $v): bool => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $mapped = [];
            foreach ($normalizedHeaders as $idx => $key) {
                if ($key !== '') {
                    $mapped[$key] = isset($rawRow[$idx]) ? trim((string) $rawRow[$idx]) : null;
                }
            }

            $validator = Validator::make($mapped, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:25'],
                'address_line1' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'state' => ['required', 'string', 'max:100'],
                'postal_code' => ['required', 'string', 'max:20'],
                'starts_at' => ['nullable', 'date'],
                'fee_paid' => ['nullable', 'numeric', 'min:0'],
                'validity_years' => ['nullable', 'integer', 'min:1', 'max:20'],
                'payment_method' => ['nullable', 'string', 'in:cash,upi,bank_transfer,cheque,offline'],
                'prepaid_months' => ['nullable', 'integer', 'min:0', 'max:60'],
                'prepaid_until' => ['nullable', 'date'],
            ]);

            if ($validator->fails()) {
                $rowErrors[] = "Row {$lineNumber}: ".implode(' ', $validator->errors()->all());

                continue;
            }

            try {
                $onboardingService->enroll([
                    'name' => (string) $mapped['name'],
                    'email' => (string) $mapped['email'],
                    'phone' => (string) $mapped['phone'],
                    'address_line1' => (string) $mapped['address_line1'],
                    'address_line2' => $mapped['address_line2'] ?? null,
                    'city' => (string) $mapped['city'],
                    'state' => (string) $mapped['state'],
                    'postal_code' => (string) $mapped['postal_code'],
                    'starts_at' => $mapped['starts_at'] ?? null,
                    'fee_paid' => $mapped['fee_paid'] ?? null,
                    'validity_years' => $mapped['validity_years'] ?? null,
                    'payment_method' => $mapped['payment_method'] ?: 'offline',
                    'payment_id' => $mapped['payment_id'] ?? null,
                    'invoice_number' => $mapped['invoice_number'] ?? null,
                    'prepaid_months' => $mapped['prepaid_months'] ?? 0,
                    'prepaid_until' => $mapped['prepaid_until'] ?? null,
                    'dispatch_current_month' => $dispatchCurrent,
                    'send_welcome_email' => $sendWelcome,
                ]);
                $imported++;
            } catch (InvalidArgumentException $e) {
                if (str_contains($e->getMessage(), 'already has an active membership')) {
                    $skipped++;
                } else {
                    $rowErrors[] = "Row {$lineNumber}: {$e->getMessage()}";
                }
            } catch (\Throwable $e) {
                $rowErrors[] = "Row {$lineNumber}: {$e->getMessage()}";
            }
        }

        fclose($handle);

        $redirect = redirect()->route('admin.memberships.index')
            ->with('success', "CSV import completed: {$imported} imported, {$skipped} skipped (already active).");

        if (! empty($rowErrors)) {
            $redirect->with('import_errors', $rowErrors);
        }

        return $redirect;
    }

    public function dispatchMonthly(
        Membership $membership,
        OfflineMembershipOnboardingService $onboardingService
    ): RedirectResponse {
        try {
            $order = $onboardingService->dispatchMonthlyOrderForMembership($membership);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        if (! $order) {
            return back()->with('info', "Monthly dispatch for {$membership->user->name} has already been created for the current billing cycle.");
        }

        return back()->with('success', "Monthly Order #{$order->order_number} created and booked with NimbusPost for {$membership->user->name}.");
    }

    public function toggleStatus(Membership $membership): RedirectResponse
    {
        $newStatus = $membership->status === 'active' ? 'cancelled' : 'active';
        $membership->update(['status' => $newStatus]);

        return back()->with('success', "Membership for {$membership->user->name} marked as {$newStatus}.");
    }
}
