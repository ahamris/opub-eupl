<div 
    class="w-full relative p-px"
    data-accordion
    data-reverse="{{ $reverse ? 'true' : 'false' }}"
    x-data="{
        exclusive: @js($exclusive),
        reverse: @js($reverse),
        init() {
            // Exclusive mode için sadece bir item'ın açık kalmasını sağla
            if (this.exclusive) {
                const items = Array.from(this.$el.querySelectorAll('details'));
                items.forEach((item) => {
                    item.addEventListener('toggle', () => {
                        if (item.open) {
                            items.forEach((other) => {
                                if (other !== item && other.open) {
                                    other.removeAttribute('open');
                                }
                            });
                        }
                    });
                });
            }
        }
    }"
    {{ $attributes->except(['class']) }}
>
    {{ $slot }}
</div>
