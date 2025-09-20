<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 hover:bg-[#730e78] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-[#b437ba] focus:bg-[#730e78] active:bg-[#5b045f] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
