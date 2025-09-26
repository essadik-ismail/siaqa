<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Siaqa - Driving School Management')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .material-button {
            @apply bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl;
        }
        
        .icon-container {
            @apply bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center;
        }
        
        .card-hover {
            @apply transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-car text-white text-xl"></i>
                        </div>
                        <span class="ml-3 text-2xl font-bold gradient-text">SIAQA</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors">Fonctionnalités</a>
                    <a href="#pricing" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors">Tarifs</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors">Témoignages</a>
                    <a href="#contact" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors">
                        Se connecter
                    </a>
                    <a href="{{ route('dev.login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-code mr-1"></i>Dev
                    </a>
                    <a href="#demo" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Commencer
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
                <a href="#features" class="text-gray-600 hover:text-gray-900 block px-3 py-2 text-base font-medium">Fonctionnalités</a>
                <a href="#pricing" class="text-gray-600 hover:text-gray-900 block px-3 py-2 text-base font-medium">Tarifs</a>
                <a href="#testimonials" class="text-gray-600 hover:text-gray-900 block px-3 py-2 text-base font-medium">Témoignages</a>
                <a href="#contact" class="text-gray-600 hover:text-gray-900 block px-3 py-2 text-base font-medium">Contact</a>
                <div class="border-t border-gray-200 pt-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 block px-3 py-2 text-base font-medium">Se connecter</a>
                    <a href="{{ route('dev.login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white block px-3 py-2 rounded-lg text-base font-medium mt-2">
                        <i class="fas fa-code mr-2"></i>Dev Login
                    </a>
                    <a href="#demo" class="bg-blue-600 hover:bg-blue-700 text-white block px-3 py-2 rounded-lg text-base font-medium mt-2">Commencer</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div id="app">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-car text-white text-xl"></i>
                        </div>
                        <span class="ml-3 text-2xl font-bold">SIAQA</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        La solution complète pour gérer votre auto-école avec l'intelligence artificielle. 
                        Optimisez vos opérations, améliorez l'expérience de vos étudiants et développez votre business.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Produit</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Tarifs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">API</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Intégrations</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Mises à jour</a></li>
                    </ul>
                </div>

                <!-- Support Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Centre d'aide</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Statut</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Communauté</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} SIAQA. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Mentions légales</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">CGU</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
