<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Driving School Management')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': {
                            50: '#e3f2fd',
                            100: '#bbdefb',
                            200: '#90caf9',
                            300: '#64b5f6',
                            400: '#42a5f5',
                            500: '#2196f3',
                            600: '#1e88e5',
                            700: '#1976d2',
                            800: '#1565c0',
                            900: '#0d47a1',
                        },
                        'secondary': {
                            50: '#fce4ec',
                            100: '#f8bbd9',
                            200: '#f48fb1',
                            300: '#f06292',
                            400: '#ec407a',
                            500: '#e91e63',
                            600: '#d81b60',
                            700: '#c2185b',
                            800: '#ad1457',
                            900: '#880e4f',
                        },
                        'surface': {
                            0: '#ffffff',
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#eeeeee',
                            300: '#e0e0e0',
                            400: '#bdbdbd',
                            500: '#9e9e9e',
                            600: '#757575',
                            700: '#616161',
                            800: '#424242',
                            900: '#212121',
                        }
                    },
                    fontFamily: {
                        'roboto': ['Roboto', 'sans-serif'],
                    },
                    boxShadow: {
                        'material-2': '0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)',
                        'material-3': '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)',
                        'material-4': '0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)',
                        'material-5': '0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .material-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .material-card:hover {
            box-shadow: 0 16px 48px rgba(0,0,0,0.15);
            transform: translateY(-4px) scale(1.02);
        }
        
        .material-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .material-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .material-button:hover::before {
            left: 100%;
        }
        
        .material-button:hover {
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            transform: translateY(-2px);
        }
        
        .material-button:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.8) 100%);
            backdrop-filter: blur(20px);
        }
        
        .nav-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            margin: 4px 0;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-circle:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .floating-circle:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .icon-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        /* Fixed Sidebar Styles */
        @media (min-width: 768px) {
            .fixed-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 40;
            }
            
            .main-content-with-sidebar {
                margin-left: 16rem; /* 256px = w-64 */
            }
            
            .sidebar-scroll {
                height: 100vh;
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar {
                width: 6px;
            }
            
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background-color: rgba(156, 163, 175, 0.5);
                border-radius: 3px;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background-color: rgba(156, 163, 175, 0.7);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 md:z-50">
            <div class="flex flex-col flex-grow pt-5 sidebar-gradient overflow-y-auto border-r border-gray-200 h-full sidebar-scroll">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <div class="icon-container w-12 h-12">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <span class="ml-3 text-xl font-bold gradient-text">SIAQA</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 pb-4 space-y-1">
                    <!-- TABLEAU DE BORD Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">TABLEAU DE BORD</p>
                        <a href="{{ route('dashboard') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-th-large mr-3 text-lg"></i>
                            Aperçu
                        </a>
                    </div>

                    <!-- ÉTUDIANTS & APPRENTISSAGE Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">ÉTUDIANTS & APPRENTISSAGE</p>
                        <a href="{{ route('students.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('students.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-user-graduate mr-3 text-lg"></i>
                            Étudiants
                            <span class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $stats['total_students'] ?? 0 }}+</span>
                        </a>
                        <a href="{{ route('lessons.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('lessons.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-calendar-alt mr-3 text-lg"></i>
                            Leçons
                        </a>
                        <a href="{{ route('exams.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('exams.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-clipboard-check mr-3 text-lg"></i>
                            Examens
                        </a>
                        <a href="{{ route('schedule.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('schedule.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-calendar-week mr-3 text-lg"></i>
                            Planning
                        </a>
                    </div>

                    <!-- PERSONNEL & VÉHICULES Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">PERSONNEL & VÉHICULES</p>
                        <a href="{{ route('instructors.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('instructors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chalkboard-teacher mr-3 text-lg"></i>
                            Instructeurs
                        </a>
                        <a href="{{ route('vehicules.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('vehicules.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-car mr-3 text-lg"></i>
                            Véhicules
                        </a>
                    </div>

                    <!-- FINANCIER Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">FINANCIER</p>
                        <a href="{{ route('charges.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('charges.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-receipt mr-3 text-lg"></i>
                            Frais
                        </a>
                    </div>


                    <!-- RAPPORTS & ANALYSES Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">RAPPORTS & ANALYSES</p>
                        <a href="{{ route('reports.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-bar mr-3 text-lg"></i>
                            Rapports
                        </a>
                        <a href="{{ route('clients.statistics') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('clients.statistics') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-line mr-3 text-lg"></i>
                            Statistiques
                        </a>
                    </div>


                </nav>

            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
            <!-- Top Header -->
            <header class="glass-effect shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" id="mobile-menu-button">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-lg mx-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" placeholder="Rechercher des tâches" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <kbd class="inline-flex items-center px-2 py-1 border border-gray-200 rounded text-xs font-mono text-gray-500">⌘F</kbd>
                            </div>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">3</span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                    <p class="text-sm text-gray-500">Vous avez 3 notifications non lues</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- Notification Items -->
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">Nouvel étudiant inscrit</p>
                                                <p class="text-xs text-gray-500">John Doe vient de terminer l'inscription</p>
                                                <p class="text-xs text-gray-400 mt-1">il y a 2 minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">Leçon terminée</p>
                                                <p class="text-xs text-gray-500">Sarah a terminé sa leçon de conduite</p>
                                                <p class="text-xs text-gray-400 mt-1">il y a 1 heure</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 hover:bg-gray-50">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">Examen programmé</p>
                                                <p class="text-xs text-gray-500">L'examen théorique de Mike est demain</p>
                                                <p class="text-xs text-gray-400 mt-1">il y a 3 heures</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-100">
                                    <button class="w-full text-center text-sm text-blue-600 hover:text-blue-700 font-medium">Voir toutes les notifications</button>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </button>
                            
                            <!-- Profile Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                                
                                <!-- User Info Header -->
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                                            <p class="text-sm text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Notifications Section -->
                                <div class="p-2">
                                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">NOTIFICATIONS</div>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        <i class="fas fa-bell mr-3 text-gray-400"></i>
                                        <span>Notifications</span>
                                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                                    </a>
                                </div>
                                
                                <!-- General Section -->
                                <div class="p-2 border-t border-gray-100">
                                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">GÉNÉRAL</div>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                                        <span>Paramètres</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        <i class="fas fa-question-circle mr-3 text-gray-400"></i>
                                        <span>Aide</span>
                                    </a>
                                    <a href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        <span>Déconnexion</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Se connecter
                            </a>
                        </div>
                        @endauth

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 bg-opacity-50">
                <div class="py-6">
                    @if (session('success'))
                        <div class="mb-4 mx-4 sm:mx-6 lg:mx-8">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 mx-4 sm:mx-6 lg:mx-8">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="mb-4 mx-4 sm:mx-6 lg:mx-8">
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-4 mx-4 sm:mx-6 lg:mx-8">
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg relative" role="alert">
                                <span class="block sm:inline">{{ session('info') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 z-40 md:hidden hidden" id="mobile-sidebar">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" id="mobile-sidebar-overlay"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" id="mobile-sidebar-close">
                    <i class="fas fa-times text-white text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile sidebar content (same as desktop) -->
            <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-car text-white text-xl"></i>
                    </div>
                    <span class="ml-3 text-xl font-bold text-gray-900">Siaqa</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 pb-4 space-y-1">
                    <!-- TABLEAU DE BORD Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">TABLEAU DE BORD</p>
                        <a href="{{ route('dashboard') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-th-large mr-3 text-lg"></i>
                            Aperçu
                        </a>
                    </div>

                    <!-- ÉTUDIANTS & APPRENTISSAGE Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">ÉTUDIANTS & APPRENTISSAGE</p>
                        <a href="{{ route('students.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('students.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-user-graduate mr-3 text-lg"></i>
                            Étudiants
                            <span class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $stats['total_students'] ?? 0 }}+</span>
                        </a>
                        <a href="{{ route('schedule.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('schedule.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-calendar-week mr-3 text-lg"></i>
                            Planning
                        </a>
                    </div>

                    <!-- PERSONNEL & VÉHICULES Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">PERSONNEL & VÉHICULES</p>
                        <a href="{{ route('instructors.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('instructors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chalkboard-teacher mr-3 text-lg"></i>
                            Instructeurs
                        </a>
                        <a href="{{ route('vehicules.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('vehicules.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-car mr-3 text-lg"></i>
                            Véhicules
                        </a>
                    </div>

                    <!-- FINANCIER Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">FINANCIER</p>
                        <a href="{{ route('charges.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('charges.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-receipt mr-3 text-lg"></i>
                            Frais
                        </a>
                    </div>


                    <!-- RAPPORTS & ANALYSES Section -->
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">RAPPORTS & ANALYSES</p>
                        <a href="{{ route('reports.index') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-bar mr-3 text-lg"></i>
                            Rapports
                        </a>
                        <a href="{{ route('clients.statistics') }}" class="nav-item group flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('clients.statistics') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <i class="fas fa-chart-line mr-3 text-lg"></i>
                            Statistiques
                        </a>
                    </div>


                </nav>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
            const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');
            
            if (mobileMenuButton && mobileSidebar) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                });
            }
            
            if (mobileSidebarClose && mobileSidebar) {
                mobileSidebarClose.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                });
            }
            
            if (mobileSidebarOverlay && mobileSidebar) {
                mobileSidebarOverlay.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                });
            }
        });
    </script>

    @stack('scripts')
    
    <!-- Déconnexion Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</body>
</html>