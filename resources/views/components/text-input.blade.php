@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 focus:border-[#D97706] focus:ring-[#D97706] rounded-lg shadow-sm text-sm text-slate-900 placeholder:text-slate-400 transition duration-150 ease-in-out']) }}>
