<nav x-data="{ open: false }" class="h-16 bg-purple-900 shadow-lg">
    <div class="h-full max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-full">
            <a href="<?= base_url() ?>"><img src="<?= LOGO_IMG ?>" alt="Logo" width="50px" /></a>
            <div class="flex">
                <div x-data="{ dropdown: false }" class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="relative inline-block text-left">
                            <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100 text-md uppercase cursor-pointer">
                                <div class="">Home</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <!-- Dropdown panel -->
                            <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                <div class="rounded-md bg-red-900 shadow-xs">
                                    <div class="py-1">
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('about') ?>">Mengapa <?= COMPANY_NAME ?>?</a>
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('team') ?>">Temui Team</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="text-md uppercase text-gray-100">
                            <a href="<?= base_url('alphaking') ?>">AlphaKing</a>
                        </div>
                    </div>
                </div>
                <div x-data="{ dropdown: false }" class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="relative inline-block text-left">
                            <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100 text-md uppercase cursor-pointer">
                                <div class="">Produk</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <!-- Dropdown panel -->
                            <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                <div class="rounded-md bg-purple-900 shadow-xs">
                                    <div class="py-1">
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('produk/alphaking') ?>">AlphaKing</a>
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="#">AlphaPropolis</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ dropdown: false }" class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="relative inline-block text-left">
                            <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100 text-md uppercase cursor-pointer">
                                <div class="">Peluang</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <!-- Dropdown panel -->
                            <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                                <div class="rounded-md bg-purple-900 shadow-xs">
                                    <div class="py-1">
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('peluang') ?>">Peluang</a>
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('testimoni') ?>">Testimoni</a>
                                        <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('tool-promosi') ?>">Tool Promosi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="text-md uppercase text-gray-100">
                            <a href="//rekomen.info">Bergabung</a>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:block md:ml-16">
                    <div class="flex items-center">
                        <div class="text-md uppercase text-gray-100">
                            <a href="//rekomen.store"><b>Shop</b></a>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex sm:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-100 hover:bg-purple-600 focus:outline-none focus:bg-purple-600 focus:text-gray-100 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6 text-gray-100" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div :class="{'block': open, 'hidden': !open}" class="bg-purple-900 hidden sm:hidden">
        <div class="px-2 pt-2 pb-3 border-b border-purple-500">
            <div x-data="{ dropdown: false }" class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100">
                            <div>Home</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        <!-- Dropdown panel -->
                        <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg z-10">
                            <div class="rounded-md bg-purple-600 shadow-xs">
                                <div class="py-1">
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('about') ?>">Mengapa <?= COMPANY_NAME ?>?</a>
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('team') ?>">Temui Team</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out text-gray-100" href="<?= base_url('alphaking') ?>">AlphaKing</a>
            <div x-data="{ dropdown: false }" class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100">
                            <div>Produk</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        <!-- Dropdown panel -->
                        <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg z-10">
                            <div class="rounded-md bg-purple-600 shadow-xs">
                                <div class="py-1">
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('produk/alphaking') ?>">AlphaKing</a>
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="#">AlphaPropolis</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="{ dropdown: false }" class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <a @click="dropdown = true; $refs.dropdown.classList.remove('hidden')" class="flex items-center text-gray-100">
                            <div>Peluang</div> <svg class="-mr-1 ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        <!-- Dropdown panel -->
                        <div x-ref="dropdown" @click.away="dropdown = false" x-show="dropdown" x-transition:enter="ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg z-10">
                            <div class="rounded-md bg-purple-600 shadow-xs">
                                <div class="py-1">
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('peluang') ?>">Peluang</a>
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('testimoni') ?>">Testimoni</a>
                                    <a class="block px-4 py-2 text-sm leading-5 hover:bg-purple-600 hover:text-gray-100 focus:outline-none focus:bg-purple-600 focus:text-gray-100 text-gray-100" href="<?= base_url('tool-promosi') ?>">Tool Promosi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out text-gray-100" href="//rekomen.info">Bergabung</a>
            <a class="block px-3 py-2 rounded-md text-base font-medium focus:outline-none focus:text-gray-100 focus:bg-purple-600 transition duration-150 ease-in-out text-gray-100" href="//rekomen.store">Shop</a>
        </div>
    </div>
</nav>