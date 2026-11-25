@php
    $code = isset($statusCode)
        ? (int) $statusCode
        : ((isset($exception) && method_exists($exception, 'getStatusCode')) ? (int) $exception->getStatusCode() : 500);
    $code = ($code >= 500 && $code < 600) ? $code : 500;
    $ref = $referenceId ?? null;

    if ($code === 503) {
        $headline = 'Sacred Portal Under Brief Maintenance';
        $hindiSubtitle = 'सेवा अस्थायी रूप से अनुरक्षण में है';
        $description = 'We are performing a gentle update to keep your sacred Haridwar Gangajal & Vedic experience smooth. Please check back in a few moments — your account, orders, and active memberships remain completely safe.';
    } elseif (in_array($code, [502, 504], true)) {
        $headline = 'Temporary Connection Pause';
        $hindiSubtitle = 'सर्वर संपर्क में अस्थायी विलंब हुआ है';
        $description = 'Our servers took a little longer than expected to respond. Please refresh in a few seconds — if you already completed a Razorpay payment or booking, do not repeat the payment as verified confirmations remain safe.';
    } else {
        $headline = 'A Gentle Pause on Our End';
        $hindiSubtitle = 'कुछ तकनीकी बाधा आई है &bull; हम इसे शीघ्र ठीक कर रहे हैं';
        $description = 'Something unexpected happened while loading this page. Our team has been notified automatically, and your account details, existing orders, and verified transactions remain safe.';
    }

    $homeHref = url('/');
    $contactHref = url('/contact');
    $faqsHref = url('/faqs');
    $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} - {{ $headline }} | Blu Bliss</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @if ($hasViteAssets)
        @vite(['resources/css/app.css'])
    @endif
    <style>
        body {
            margin: 0;
            min-height: 100%;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(180deg, #FFFDF7 0%, #FEF3C7 55%, #FFFDF7 100%);
            color: #292524;
            display: flex;
            flex-direction: column;
        }
        .fallback-shell {
            max-width: 780px;
            margin: 0 auto;
            width: 100%;
            padding: 36px 20px;
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fallback-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid #FDE68A;
            border-radius: 24px;
            padding: 40px 28px;
            text-align: center;
            box-shadow: 0 18px 40px rgba(136, 19, 55, 0.08);
        }
        .fallback-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 9999px;
            background-color: #D97706;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .fallback-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 9999px;
            background-color: #FFFFFF;
            color: #78350F;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: 2px solid #F59E0B;
            cursor: pointer;
        }
        .fallback-btn-soft {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 9999px;
            background-color: #FEF3C7;
            color: #78350F;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
        }
    </style>
</head>
<body class="min-h-full antialiased selection:bg-[#881337] selection:text-white">
    <!-- Minimal Safe Brand Header (No DB / Auth queries) -->
    <header class="w-full bg-white/95 border-b border-amber-200/80 shadow-xs" style="background:#FFFFFF;border-bottom:1px solid #FDE68A;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between" style="max-width:72rem;margin:0 auto;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;">
            <a href="{{ $homeHref }}" class="flex items-center gap-2.5" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <span class="text-xl font-serif font-extrabold italic text-[#881337]" style="font-family:Georgia,serif;font-weight:800;font-style:italic;font-size:20px;color:#881337;">
                    Blu Bliss
                </span>
                <span class="hidden sm:inline text-xs font-medium text-amber-800" style="font-size:12px;color:#92400E;">
                    &bull; Sacred Haridwar Gangajal
                </span>
            </a>
            <a href="tel:+917042482555" class="text-xs font-bold text-[#881337] hover:text-amber-700 transition" style="font-size:12px;font-weight:700;color:#881337;text-decoration:none;">
                Support: +91 7042482555
            </a>
        </div>
    </header>

    <!-- Soft Error Body -->
    <main class="fallback-shell">
        <div class="fallback-card space-y-6">
            <!-- Vedic Pill Badge -->
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 border border-amber-300/80 text-amber-900 text-xs sm:text-sm font-medium"
                      style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:9999px;background:#FEF3C7;border:1px solid #FCD34D;color:#78350F;font-size:13px;font-weight:600;">
                    <span style="font-family:Georgia,serif;color:#D97706;">ॐ</span>
                    <span>Haridwar Bliss &bull; Devotee Care Notice</span>
                    <span style="font-family:Georgia,serif;color:#D97706;">ॐ</span>
                </span>
            </div>

            <!-- 5xx Status Emblem -->
            <div class="py-2">
                <div class="font-serif italic text-7xl sm:text-8xl font-black text-amber-700/90 tracking-tight select-none"
                     style="font-family:Georgia,serif;font-style:italic;font-size:72px;font-weight:900;color:#B45309;line-height:1;">
                    {{ $code }}
                </div>
                <div class="mt-3 flex justify-center items-center gap-3" style="margin-top:12px;display:flex;justify-content:center;align-items:center;gap:12px;">
                    <div style="height:2px;width:56px;background:linear-gradient(to right, transparent, #F59E0B);"></div>
                    <div style="width:44px;height:44px;border-radius:9999px;background:#F59E0B;color:#FFFFFF;display:flex;align-items:center;justify-content:center;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 4.97-6 8.5-6 11.5a6 6 0 0012 0c0-3-1.03-6.53-6-11.5z" />
                        </svg>
                    </div>
                    <div style="height:2px;width:56px;background:linear-gradient(to left, transparent, #F59E0B);"></div>
                </div>
            </div>

            <!-- Calm Heading & Bilingual Copy -->
            <div class="space-y-2.5">
                <h1 class="text-2xl sm:text-4xl font-serif font-bold text-stone-900 tracking-tight"
                    style="font-family:Georgia,serif;font-size:28px;font-weight:700;color:#1C1917;margin:0;">
                    {{ $headline }}
                </h1>
                <p class="text-base sm:text-lg font-serif text-amber-800 italic"
                   style="font-family:Georgia,serif;font-size:17px;color:#92400E;font-style:italic;margin:6px 0;">
                    {!! $hindiSubtitle !!}
                </p>
                <p class="max-w-xl mx-auto text-sm text-stone-600 leading-relaxed"
                   style="max-width:36rem;margin:10px auto 0;font-size:14px;color:#57534E;line-height:1.65;">
                    {{ $description }}
                </p>
            </div>

            @if ($ref)
                <div>
                    <span class="inline-block px-3 py-1 rounded-lg bg-stone-100 border border-stone-200 text-[11px] font-mono text-stone-600"
                          style="display:inline-block;padding:5px 12px;border-radius:8px;background:#F5F5F4;border:1px solid #E7E5E4;font-family:monospace;font-size:11px;color:#57534E;">
                        Reference ID: <strong>{{ $ref }}</strong>
                    </span>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:12px;padding-top:8px;">
                <button type="button" onclick="window.location.reload()" class="fallback-btn-primary hover:bg-amber-700 transition">
                    <span>Try Again (पुनः प्रयास करें)</span>
                </button>

                <a href="{{ $homeHref }}" class="fallback-btn-secondary hover:bg-amber-50 transition">
                    <span>Return to Home (मुख्य पृष्ठ)</span>
                </a>

                <button type="button" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ $homeHref }}'; }" class="fallback-btn-soft hover:bg-amber-200 transition">
                    <span>Go Back</span>
                </button>
            </div>

            <!-- Safe Links -->
            <div class="pt-5 border-t border-amber-200/80 text-xs text-stone-600 space-y-2"
                 style="padding-top:20px;border-top:1px solid #FDE68A;font-size:12px;color:#57534E;">
                <div class="flex flex-wrap items-center justify-center gap-4" style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin-bottom:8px;">
                    <a href="{{ $contactHref }}" style="color:#B45309;font-weight:600;text-decoration:underline;">Devotee Support (सहायता)</a>
                    <a href="{{ $faqsHref }}" style="color:#B45309;font-weight:600;text-decoration:underline;">Devotee FAQs</a>
                </div>
                <p style="margin:0;">
                    Need immediate assistance? Call
                    <a href="tel:+917042482555" style="color:#881337;font-weight:700;text-decoration:underline;">+91 7042482555</a>
                    or email
                    <a href="mailto:sales@haridwarbliss.com" style="color:#881337;font-weight:700;text-decoration:underline;">sales@haridwarbliss.com</a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>
