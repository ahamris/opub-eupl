<x-layouts.admin title="Accordions - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <div class="space-y-20">
                <div>
                    <h3 class="mb-4 text-xl font-semibold">Accordion Examples</h3>
                    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                        <div class="p-10">
                            <x-accordion>
                                <x-accordion.item heading="Basic Usage">
                                    Basic accordion with default chevron icons. Use the heading prop for the button text and slot for content.
                                </x-accordion.item>
                                <x-accordion.item heading="Custom Icons" icon="plus">
                                    Use icon prop to set custom Font Awesome icons. Icon rotates 45 degrees when opened.
                                </x-accordion.item>
                                <x-accordion.item heading="Custom Icon with Color" icon="star" color="purple">
                                    This item uses a custom star icon with purple color for both heading and icon.
                                </x-accordion.item>
                                <x-accordion.item heading="Disabled Item" disabled>
                                    Use disabled prop to prevent an item from being opened. The item becomes non-interactive.
                                </x-accordion.item>
                                <x-accordion.item heading="Expanded by Default" expanded>
                                    Use expanded prop to make an item start in an open state when the page loads.
                                </x-accordion.item>
                                <x-accordion.item heading="Purple Color" icon="gem" color="purple">
                                    This item uses purple color for both heading and icon.
                                </x-accordion.item>
                                <x-accordion.item heading="Orange Color" icon="sun" color="orange">
                                    This item uses orange color for both heading and icon.
                                </x-accordion.item>
                                <x-accordion.item heading="Cyan Color" icon="droplet" color="cyan">
                                    This item uses cyan color for both heading and icon.
                                </x-accordion.item>
                            </x-accordion>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 px-4 py-2 flex justify-end">
                            <button 
                                type="button"
                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                data-code="&lt;x-accordion&gt;&#10;    &lt;x-accordion.item heading=&quot;Basic Usage&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Custom Icons&quot; icon=&quot;plus&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Custom Icon with Color&quot; icon=&quot;star&quot; color=&quot;purple&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Disabled Item&quot; disabled&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Expanded by Default&quot; expanded&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Purple Color&quot; icon=&quot;gem&quot; color=&quot;purple&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Orange Color&quot; icon=&quot;sun&quot; color=&quot;orange&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Cyan Color&quot; icon=&quot;droplet&quot; color=&quot;cyan&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;&lt;/x-accordion&gt;"
                                x-data="{ copied: false }"
                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                            >
                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 text-xl font-semibold">Exclusive Mode</h3>
                    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                        <div class="p-10">
                            <x-accordion exclusive>
                                <x-accordion.item heading="Exclusive Mode">
                                    Use exclusive prop to allow only one item open at a time. Opening one item automatically closes others.
                                </x-accordion.item>
                                <x-accordion.item heading="Second Item">
                                    When you open this item, the first item will automatically close because exclusive mode is enabled.
                                </x-accordion.item>
                                <x-accordion.item heading="Third Item">
                                    Only one item can be open at a time in exclusive mode. Try opening different items to see the effect.
                                </x-accordion.item>
                            </x-accordion>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 px-4 py-2 flex justify-end">
                            <button 
                                type="button"
                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                data-code="&lt;x-accordion exclusive&gt;&#10;    &lt;x-accordion.item heading=&quot;Exclusive Mode&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Second Item&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Third Item&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;&lt;/x-accordion&gt;"
                                x-data="{ copied: false }"
                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                            >
                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 text-xl font-semibold">Reverse Layout</h3>
                    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                        <div class="p-10">
                            <x-accordion reverse>
                                <x-accordion.item heading="Reverse Layout">
                                    Use reverse prop to display icons before the heading instead of after.
                                </x-accordion.item>
                                <x-accordion.item heading="Icon on Left" icon="star" color="purple">
                                    Icons appear on the left side when reverse mode is enabled.
                                </x-accordion.item>
                                <x-accordion.item heading="Custom Icon" icon="heart" color="pink">
                                    Custom icons also appear on the left in reverse mode.
                                </x-accordion.item>
                            </x-accordion>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900 px-4 py-2 flex justify-end">
                            <button 
                                type="button"
                                class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                data-code="&lt;x-accordion reverse&gt;&#10;    &lt;x-accordion.item heading=&quot;Reverse Layout&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Icon on Left&quot; icon=&quot;star&quot; color=&quot;purple&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;    &lt;x-accordion.item heading=&quot;Custom Icon&quot; icon=&quot;heart&quot; color=&quot;pink&quot;&gt;&#10;        Content here.&#10;    &lt;/x-accordion.item&gt;&#10;&lt;/x-accordion&gt;"
                                x-data="{ copied: false }"
                                x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                            >
                                <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documentation -->
        <div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Documentation</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-4">
                <div>
                    <h4 class="text-lg font-semibold mb-3">Accordion Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">exclusive</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Only one item can be open at a time</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">reverse</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Display icon before heading instead of after</p>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-3">Accordion Item Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">heading</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">The text displayed in the accordion button</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">expanded</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Item starts in an open state</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">disabled</code>
                            <span class="text-gray-600 dark:text-gray-400"> - bool</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Disable the item</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">icon</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Custom Font Awesome icon name. Rotates 45° when opened.</p>
                        </li>
                        <li>
                            <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">color</code>
                            <span class="text-gray-600 dark:text-gray-400"> - string</span>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tailwind CSS color name for heading and icon. Default: theme color.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
