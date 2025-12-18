<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[var(--color-primary-dark)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[var(--color-primary)] focus:bg-[var(--color-primary-dark)] active:bg-[var(--color-primary-dark)] focus:outline-none transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
