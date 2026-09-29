<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#D97706] hover:bg-[#F97316] active:bg-[#881337] border border-transparent rounded-lg font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-[#D97706] focus:ring-offset-2 shadow-sm transition ease-in-out duration-150 disabled:opacity-50 cursor-pointer']) }}>
    {{ $slot }}
</button>
