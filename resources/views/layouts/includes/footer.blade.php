<!-- Modern Footer -->
<footer class="bg-[var(--color-primary)] text-white mt-auto" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mx-auto max-w-7xl px-6 pb-12 pt-16 sm:pt-20 lg:px-8 lg:pt-24">
        <div class="xl:grid xl:grid-cols-3 xl:gap-12">
            <!-- Links Section -->
            <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold leading-6 text-white mb-4">Over deze website</h3>
                        <ul role="list" class="space-y-3">
                            <li>
                                <a href="{{ route('over') }}" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                    Over open.overheid.nl
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('verwijzingen') }}" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                    Verwijzingen
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-8 md:mt-0">
                        <h3 class="text-sm font-semibold leading-6 text-white mb-4">Recht & Privacy</h3>
                        <ul role="list" class="space-y-3">
                            <li>
                                <a href="#" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                    Privacy & Cookies
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm">
                                    Toegankelijkheid
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-sm font-semibold leading-6 text-white mb-4">Externe links</h3>
                        <ul role="list" class="space-y-3">
                            <li>
                                <a href="https://www.overheid.nl" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                    Overheid.nl
                                    <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.woo-index.nl" target="_blank" rel="noopener noreferrer" class="text-sm leading-6 text-neutral-300 hover:text-white transition-colors duration-200 focus:outline-2 focus:outline-white focus:outline-offset-2 rounded-sm inline-flex items-center gap-1.5">
                                    Woo-index
                                    <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Mission Statement Column -->
            <div class="mt-12 xl:mt-0">
                <h3 class="text-sm font-semibold leading-6 text-white mb-4">Onze missie</h3>
                <p class="text-sm leading-6 text-neutral-300">
                    Open.overheid.nl bundelt actief openbaar gemaakte overheidsdocumenten op één centrale plek, 
                    zodat burgers en professionals deze eenvoudig kunnen vinden en raadplegen. Wij werken op basis 
                    van de Wet open overheid (Woo) om transparantie en toegankelijkheid van overheidsinformatie te bevorderen.
                </p>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs leading-5 text-neutral-400">
                    &copy; {{ date('Y') }} Open Overheid. Alle rechten voorbehouden.
                </p>
                <div class="flex items-center gap-6">
                    <span class="text-xs text-neutral-400">Digitaliseringspartner voor slimme ICT-oplossingen</span>
                </div>
            </div>
        </div>
    </div>
</footer>
