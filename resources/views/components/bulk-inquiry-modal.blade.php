@props([
    'products' => [],
    'currentProductId' => null,
])

<div x-data="bulkInquiryModal({
        initialProductId: {{ $currentProductId ? (int) $currentProductId : 'null' }},
        userName: '{{ Auth::user() ? addslashes(Auth::user()->name) : '' }}',
        userEmail: '{{ Auth::user() ? addslashes(Auth::user()->email) : '' }}',
        userPhone: '{{ Auth::user() ? addslashes(Auth::user()->phone ?? '') : '' }}'
    })" @open-bulk-inquiry.window="openModal($event.detail)" @keydown.escape.window="if(isOpen) closeModal()" x-cloak
    class="relative z-50">

    <!-- Backdrop -->
    <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="closeModal()"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Modal Dialog Positioner -->
    <div x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto p-3 sm:p-4 flex items-center justify-center">
        <div x-show="isOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2" @click.away="closeModal()"
            class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-amber-200/80 w-full max-w-xl overflow-hidden my-auto transform transition-all text-slate-800">

            <!-- Modal Header -->
            <div
                class="px-5 sm:px-6 py-4 bg-gradient-to-r from-[#FFFDF7] via-amber-50/50 to-[#FFFDF7] border-b border-amber-200/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span
                        class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-700 flex items-center justify-center shrink-0 border border-amber-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-serif font-bold text-slate-900 text-base sm:text-lg leading-tight">Bulk Product
                            Inquiry</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Wholesale & temple pricing for large sacred orders
                        </p>
                    </div>
                </div>
                <button type="button" @click="closeModal()"
                    class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition"
                    aria-label="Close dialog">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 sm:p-6 max-h-[calc(88vh-80px)] overflow-y-auto">
                <!-- Success State -->
                <div x-show="submitted" class="text-center py-6 sm:py-8 space-y-4">
                    <div
                        class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xl font-bold font-serif text-slate-900">Inquiry Received with Reverence!</h4>
                        <p class="text-xs sm:text-sm text-slate-600 max-w-sm mx-auto leading-relaxed">
                            Thank you, <strong class="text-slate-900" x-text="form.name"></strong>. Your bulk inquiry
                            for
                            <strong class="text-amber-800" x-text="form.quantity + ' units'"></strong> of
                            <strong class="text-slate-900" x-text="selectedProductName()"></strong> has been registered.
                        </p>
                    </div>

                    <div
                        class="bg-amber-50/70 border border-amber-200/70 rounded-xl p-3.5 text-xs text-amber-900 text-left max-w-md mx-auto space-y-1.5">
                        <div class="flex items-center gap-2 font-semibold">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            <span>WhatsApp / Phone Confirmation</span>
                        </div>
                        <p class="text-[11px] text-amber-800">
                            Our wholesale desk will connect directly on <span class="font-mono font-semibold"
                                x-text="form.phone"></span> with wholesale tier pricing, dispatch timelines, and GST
                            invoice details.
                        </p>
                    </div>

                    <div class="pt-3 flex items-center justify-center gap-3">
                        <button type="button" @click="closeModal()"
                            class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs transition">
                            Done
                        </button>
                        <button type="button" @click="resetForm()"
                            class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs sm:text-sm font-medium transition">
                            Submit Another
                        </button>
                    </div>
                </div>

                <!-- Form State -->
                <form x-show="!submitted" @submit.prevent="submitForm()" class="space-y-4">
                    <!-- Global Error Alert -->
                    <div x-show="errorMessage" x-transition
                        class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-start gap-2">
                        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div x-text="errorMessage"></div>
                    </div>

                    <!-- Client Name & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name* -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Full Name <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" x-model="form.name" required placeholder="Enter your name"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs" />
                            <template x-if="errors.name">
                                <p class="text-[11px] text-rose-600 mt-1" x-text="errors.name[0]"></p>
                            </template>
                        </div>

                        <!-- Phone / WhatsApp Number* -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Phone / WhatsApp Number <span class="text-rose-600">*</span>
                            </label>
                            <input type="tel" x-model="form.phone" required placeholder="10-digit Mobile / WhatsApp"
                                class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs font-mono" />
                            <template x-if="errors.phone">
                                <p class="text-[11px] text-rose-600 mt-1" x-text="errors.phone[0]"></p>
                            </template>
                        </div>
                    </div>

                    <!-- Email Address (Optional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Email Address <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <input type="email" x-model="form.email" placeholder="your.email@example.com"
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs" />
                        <template x-if="errors.email">
                            <p class="text-[11px] text-rose-600 mt-1" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    <!-- Product Dropdown* -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Select Product <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="form.productId" required
                                class="w-full px-3.5 py-2.5 pr-8 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs appearance-none">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ (isset($currentProductId) && $currentProductId == $p->id) ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 text-slate-400 absolute right-3 top-3 pointer-events-none" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <template x-if="errors.product_id">
                            <p class="text-[11px] text-rose-600 mt-1" x-text="errors.product_id[0]"></p>
                        </template>
                    </div>

                    <!-- Quantity* -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-slate-700">
                                Required Quantity <span class="text-rose-600">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Min: 1 unit</span>
                        </div>
                        <input type="number" min="1" max="1000000" x-model.number="form.quantity" required
                            placeholder="e.g. 50, 100, 500"
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs font-semibold" />

                        <!-- Quick Quantity Selectors -->
                        <div class="flex items-center gap-1.5 mt-2 overflow-x-auto scrollbar-none pb-0.5">
                            <span class="text-[10px] text-slate-400 uppercase font-bold shrink-0">Quick:</span>
                            <template x-for="qty in [25, 50, 100, 250, 500, 1000]" :key="qty">
                                <button type="button" @click="form.quantity = qty"
                                    :class="form.quantity === qty ? 'bg-amber-600 text-white border-amber-600' : 'bg-amber-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                    class="px-2.5 py-1 text-[11px] font-semibold border rounded-lg transition shrink-0"
                                    x-text="qty + ' units'">
                                </button>
                            </template>
                        </div>
                        <template x-if="errors.quantity">
                            <p class="text-[11px] text-rose-600 mt-1" x-text="errors.quantity[0]"></p>
                        </template>
                    </div>

                    <!-- Delivery Address & Destination Details (Address, City, State, Pincode) -->
                    <div class="pt-2 border-t border-slate-100 space-y-3">
                        <!-- Full Address -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Delivery Address <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <textarea x-model="form.address" rows="2"
                                placeholder="Street address, building, landmark, temple/ashram or organization name..."
                                class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs"></textarea>
                            <template x-if="errors.address">
                                <p class="text-[11px] text-rose-600 mt-1" x-text="errors.address[0]"></p>
                            </template>
                        </div>

                        <!-- City, State, Pincode Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- City -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    City <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>
                                <input type="text" x-model="form.city" placeholder="e.g. Haridwar"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs" />
                                <template x-if="errors.city">
                                    <p class="text-[11px] text-rose-600 mt-1" x-text="errors.city[0]"></p>
                                </template>
                            </div>

                            <!-- State -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    State <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>
                                <input type="text" x-model="form.state" placeholder="e.g. Uttarakhand"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs" />
                                <template x-if="errors.state">
                                    <p class="text-[11px] text-rose-600 mt-1" x-text="errors.state[0]"></p>
                                </template>
                            </div>

                            <!-- Pincode -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Pincode <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>
                                <input type="text" x-model="form.pincode" maxlength="10" placeholder="e.g. 249401"
                                    class="w-full px-3 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs font-mono" />
                                <template x-if="errors.pincode">
                                    <p class="text-[11px] text-rose-600 mt-1" x-text="errors.pincode[0]"></p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Message / Requirements (Optional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Message / Specific Requirements <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <textarea x-model="form.message" rows="2"
                            placeholder="Mention target date, custom branding/labels, GST requirements, or temple instructions..."
                            class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-2xs"></textarea>
                        <template x-if="errors.message">
                            <p class="text-[11px] text-rose-600 mt-1" x-text="errors.message[0]"></p>
                        </template>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-2">
                        <button type="submit" :disabled="submitting"
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 bg-amber-700 hover:from-amber-700 hover:to-amber-900 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition transform active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed touch-manipulation">
                            <template x-if="!submitting">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>Submit Bulk Product Inquiry</span>
                                </span>
                            </template>
                            <template x-if="submitting">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Submitting Inquiry...</span>
                                </span>
                            </template>
                        </button>
                    </div>

                    <!-- Trust Footer -->
                    <div class="pt-2 flex items-center justify-center gap-4 text-[10px] text-slate-400 text-center">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Direct Haridwar Origin
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            GST Billing Available
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Pan-India Dispatch
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function bulkInquiryModal(config) {
        return {
            isOpen: false,
            submitting: false,
            submitted: false,
            errorMessage: null,
            errors: {},
            productsMap: @js($products->pluck('name', 'id')->all()),
            form: {
                name: config.userName || '',
                email: config.userEmail || '',
                phone: config.userPhone || '',
                productId: config.initialProductId || '',
                quantity: 50,
                address: '',
                city: '',
                state: '',
                pincode: '',
                message: ''
            },

            openModal(detail = {}) {
                if (detail && detail.productId) {
                    this.form.productId = detail.productId;
                }
                this.isOpen = true;
                this.errorMessage = null;
                this.errors = {};
                document.body.classList.add('overflow-hidden');
            },

            closeModal() {
                this.isOpen = false;
                document.body.classList.remove('overflow-hidden');
            },

            resetForm() {
                this.submitted = false;
                this.submitting = false;
                this.errorMessage = null;
                this.errors = {};
                this.form.address = '';
                this.form.city = '';
                this.form.state = '';
                this.form.pincode = '';
                this.form.message = '';
                this.form.quantity = 50;
            },

            selectedProductName() {
                return this.productsMap[this.form.productId] || 'Selected Sacred Item';
            },

            async submitForm() {
                this.submitting = true;
                this.errorMessage = null;
                this.errors = {};

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch('{{ route('bulk-inquiry.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({
                            name: this.form.name,
                            email: this.form.email,
                            phone: this.form.phone,
                            product_id: this.form.productId,
                            quantity: this.form.quantity,
                            address: this.form.address,
                            city: this.form.city,
                            state: this.form.state,
                            pincode: this.form.pincode,
                            message: this.form.message
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            this.errors = data.errors;
                            this.errorMessage = data.message || 'Please correct the highlighted fields.';
                        } else {
                            this.errorMessage = data.message || 'Something went wrong while submitting. Please try again.';
                        }
                        this.submitting = false;
                        return;
                    }

                    this.submitted = true;
                    this.submitting = false;
                } catch (err) {
                    console.error('Bulk inquiry error:', err);
                    this.errorMessage = 'Network error. Please check your connection and try again.';
                    this.submitting = false;
                }
            }
        };
    }
</script>