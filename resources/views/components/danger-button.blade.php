<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#991B1B] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#881337] active:bg-[#881337] focus:outline-none focus:ring-2 focus:ring-[#991B1B] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
