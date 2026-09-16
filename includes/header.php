<header class="py-12 relative">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex items-center justify-between md:justify-start relative px-2 md:px-0">
            <a href="/" title="Retour à la page d'accueil" class="block">
                <img src="images/logo.svg" alt="Liège Hackerspace - Learn \ Make \ Share" class="h-14 md:h-32 w-auto">
            </a>

            <button type="button" id="nav-toggle" aria-expanded="false" aria-controls="main-nav-mobile"
                class="md:hidden inline-flex items-center justify-center w-9 h-9 border border-black hover:bg-black hover:text-white transition-colors">
                <span class="sr-only">Menu</span>
                <svg id="nav-icon-open" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M3 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M3 18H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
                <svg id="nav-icon-close" class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 5L19 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M19 5L5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </button>

            <nav id="main-nav-mobile" class="hidden md:hidden absolute right-0 top-full mt-2 z-30 w-56 bg-white border-2 border-black shadow-[4px_4px_0_0_#000]">
                <ul class="flex flex-col divide-y divide-gray-200">
                    <li><a href="/" title="Accueil" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">Home</a></li>
                    <li><a href="/agenda" title="Agenda" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">Agenda</a></li>
                    <li><a href="/faq" title="FAQ" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="https://wiki.liegehacker.space/" title="Notre wiki" target="_blank" rel="noopener" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">Wiki</a></li>
                    <li><a href="/soutenir" title="Soutenir le Liège Hackerspace" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">Soutenir</a></li>
                    <li><a href="/contact" title="Contactez-nous" class="block px-4 py-3 uppercase font-bold hover:bg-black hover:text-white transition-colors">Contact</a></li>
                </ul>
            </nav>
        </div>

        <nav class="hidden md:flex items-center justify-end mt-8 md:mt-0">
            <ul class="flex flex-wrap justify-end gap-2">
                <li>
                    <a href="/" title="Accueil"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <a href="/agenda" title="Agenda"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Agenda
                    </a>
                    <a href="/faq" title="FAQ"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        FAQ
                    </a>
                </li>
                <!-- <li>
                    <a href="https://wiki.liegehacker.space/shelves/projets" title="Nos projets" target="_blank" rel="noopener"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Projets
                    </a>
                </li>-->
                <li>
                    <a href="https://wiki.liegehacker.space/" title="Notre wiki" target="_blank" rel="noopener"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Wiki
                    </a>
                </li>
                <li>
                    <a href="/soutenir" title="Soutenir le Liège Hackerspace"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Soutenir
                    </a>
                </li>
                <li>
                    <a href="/contact" title="Contactez-nous"
                        class="inline-block uppercase font-bold px-2 py-2 hover:bg-black hover:text-white transition-colors">
                        Contact
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<script>
(function() {
    const toggle = document.getElementById('nav-toggle');
    const menu = document.getElementById('main-nav-mobile');
    const iconOpen = document.getElementById('nav-icon-open');
    const iconClose = document.getElementById('nav-icon-close');

    function closeMenu() {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
    }

    toggle.addEventListener('click', function(event) {
        event.stopPropagation();
        const isHidden = menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        if (!menu.classList.contains('hidden') && !menu.contains(event.target)) {
            closeMenu();
        }
    });
})();
</script>
