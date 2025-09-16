<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Odys SaaS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- RTL Support for Arabic -->
    
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Dynamic Validation CSS -->
    <link rel="stylesheet" href="{{ asset('css/dynamic-validation.css') }}">
    
    <!-- Custom Styles -->
    <style>
        /* Material Design inspired styles */
        :root {
            --md-primary: #1976d2;
            --md-primary-dark: #1565c0;
            --md-primary-light: #42a5f5;
            --md-secondary: #ff4081;
            --md-surface: #ffffff;
            --md-surface-variant: #f5f5f5;
            --md-on-surface: #212121;
            --md-on-surface-variant: #757575;
            --md-elevation-1: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --md-elevation-2: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
            --md-elevation-3: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
            --md-elevation-4: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        }
        
        /* Additional custom styles if needed */
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* Enhanced sidebar styles with Modern Design */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 18rem; /* Increased to 288px for better comfort */
            overflow-y: auto;
            z-index: 40;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
            border-right: 1px solid rgba(0,0,0,0.08);
            box-shadow: 8px 0 32px rgba(0,0,0,0.12), 0 0 0 1px rgba(255,255,255,0.05);
            backdrop-filter: blur(24px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Sidebar inner shadow for depth */
        .sidebar-fixed::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.03), transparent);
        }
        
        /* Enhanced sidebar content container */
        .sidebar-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        /* Enhanced sidebar header area */
        .sidebar-header {
            position: relative;
            z-index: 2;
        }
        
        /* Enhanced sidebar navigation area */
        .sidebar-navigation {
            flex: 1;
            position: relative;
            z-index: 1;
        }
        
        /* Navigation subsections for SaaS management */
        .nav-subsection {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,0.06);
        }
        
        .nav-subheader {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            padding-left: 1rem;
        }
        
        /* Button styles for SaaS overview */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            border: none;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
        }
        
        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #d97706;
        }
        
        .btn-success {
            background-color: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Enhanced sidebar footer area */
        .sidebar-footer {
            position: relative;
            z-index: 2;
        }
        
        .content-with-fixed-sidebar {
            margin-left: 18rem; /* Always 288px */
        }
        
        /* Ensure content area is flexible and scrollable */
        .main-content {
            min-height: 100vh;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 2rem;
        }
        
        /* Enhanced scrollbar for sidebar */
        .sidebar-fixed::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 2px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.2);
        }
        
        /* Enhanced navigation item styles */
        .sidebar-nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            border-radius: 12px;
            margin: 0.25rem 1rem;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            letter-spacing: 0.025em;
            cursor: pointer;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: currentColor;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 16px;
        }
        
        .sidebar-nav-item:hover::before {
            opacity: 0.06;
        }
        
        .sidebar-nav-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(147, 51, 234, 0.05));
            color: #1e40af;
            border-left: 3px solid #3b82f6;
        }
        
        .sidebar-nav-item:hover i {
            transform: scale(1.15) rotate(5deg);
            color: var(--md-primary);
        }
        
        .sidebar-nav-item i {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Ripple effect for navigation items */
        .sidebar-nav-item::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            pointer-events: none;
        }
        
        .sidebar-nav-item:active::after {
            width: 300px;
            height: 300px;
        }
        
        /* Active navigation item with Modern Design */
        .sidebar-nav-item.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(147, 51, 234, 0.08));
            color: #1e40af;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
            transform: translateX(4px);
            position: relative;
            overflow: hidden;
            font-weight: 600;
            border-left: 4px solid #3b82f6;
        }
        
        .sidebar-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #1e3a8a;
            border-radius: 0;
        }
        
        .sidebar-nav-item.active:hover {
            transform: none;
            box-shadow: none;
            background: rgba(30, 58, 138, 0.05);
        }
        
        .sidebar-nav-item.active i {
            transform: none;
            filter: none;
            color: var(--md-primary);
        }
        
        /* Shimmer animation for active items */
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Pulse animation for active indicator */
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: translateY(-50%) scale(1); }
            50% { opacity: 0.8; transform: translateY(-50%) scale(1.1); }
        }
        
        /* Enhanced logo section */
        .logo-section {
            color: --md-secondary;
            text-transform: uppercase;
            border-radius: 20px;
            text-align: center;
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(147, 51, 234, 0.03));
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        /* .logo-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: logo-shine 6s infinite;
        } */
        
        @keyframes logo-shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        /* Enhanced user profile section */
        .user-profile {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,250,252,0.9));
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(24px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,255,255,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .user-profile:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(59, 130, 246, 0.1);
        }
        
        /* Enhanced navigation group headers */
        .nav-group-header {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #64748b;
            margin: 2rem 1rem 0.75rem;
            padding: 0.5rem 1rem;
            position: relative;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(147, 51, 234, 0.03));
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.08);
        }
        
        .nav-group-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 12px;
            background: linear-gradient(135deg, var(--md-primary), var(--md-secondary));
            border-radius: 2px;
        }
        
        /* Enhanced spacing between navigation sections */
        .nav-section {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .sidebar-fixed {
                width: 16rem;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar-fixed.mobile-open {
                transform: translateX(0);
            }
            
            .content-with-fixed-sidebar {
                margin-left: 0 !important;
            }
        }
        
        /* Enhanced scrollbar for sidebar */
        .sidebar-fixed::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 3px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 3px;
        }
        
        .sidebar-fixed::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }
        
        .nav-section:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: -0.75rem;
            left: 0.75rem;
            right: 0.75rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.06), transparent);
        }
        
        /* Enhanced mobile responsive styles */
        @media (max-width: 768px) {
            .sidebar-fixed {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar-fixed.mobile-open {
                transform: translateX(0);
            }
            
            .content-with-fixed-sidebar {
                margin-left: 0;
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.6);
                z-index: 30;
                backdrop-filter: blur(8px);
                transition: opacity 0.3s ease;
            }
            
            .mobile-overlay.active {
                display: block;
                opacity: 1;
            }
        }
        
        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }
        
        /* Enhanced content cards */
        .content-card {
            background: var(--md-surface);
            border-radius: 16px;
            box-shadow: var(--md-elevation-1);
            border: 1px solid rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .content-card:hover {
            box-shadow: var(--md-elevation-2);
            transform: translateY(-2px);
        }
        
        /* Enhanced buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--md-primary) 0%, var(--md-primary-dark) 100%);
            color: white;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--md-elevation-1);
        }
        
        .btn-primary:hover {
            box-shadow: var(--md-elevation-3);
            transform: translateY(-2px);
        }
        
        /* Enhanced form inputs */
        .form-input {
            border: 2px solid rgba(0,0,0,0.08);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }
        
        .form-input:focus {
            border-color: var(--md-primary);
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
            background: white;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Enhanced Fixed Sidebar - Always Expanded -->
        <div class="sidebar-fixed">
            <div class="sidebar-content">
                <!-- Sidebar Header -->
                <div class="sidebar-header p-6">
                    <!-- Mobile Close Button -->
                    <button class="mobile-menu-btn absolute top-4 right-4 p-2.5 bg-white/80 rounded-xl hover:bg-white transition-all duration-200 md:hidden backdrop-blur-sm" onclick="closeMobileMenu()">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                    
                    <!-- Enhanced Logo Section -->
                    <div class="logo-section mb-6">
                        <div class="flex items-center justify-center relative z-10">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 hover:opacity-90 transition-opacity duration-200">
                                <img src="{{ asset('assets/images/odys-logo-modern.svg') }}" alt="Odys Rental Management" class="h-12 w-auto">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <div class="sidebar-navigation px-6">
                    <!-- Enhanced Navigation -->
                    <nav class="space-y-1">
                        <!-- Main Navigation -->
                        <div class="nav-section">
                            <a href="{{ route('dashboard') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="font-medium">Tableau de bord</span>
                            </a>
                        </div>

                        <!-- Business Management -->
                        <div class="nav-section">
                            <div class="nav-group-header">Entreprise</div>
                            
                            <a href="{{ route('clients.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span class="font-medium">Clients</span>
                            </a>

                            <a href="{{ route('vehicules.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('vehicules.*') ? 'active' : '' }}">
                                <i class="fas fa-car"></i>
                                <span class="font-medium">Véhicules</span>
                            </a>

                            <a href="{{ route('charges.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('charges.*') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span class="font-medium">Charges</span>
                            </a>
                        </div>

                        <!-- Operations -->
                        <div class="nav-section">
                            <div class="nav-group-header">Opérations</div>
                            
                            <a href="{{ route('reservations.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span class="font-medium">Réservations</span>
                            </a>

                            <a href="{{ route('contrats.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('contrats.*') ? 'active' : '' }}">
                                <i class="fas fa-file-contract"></i>
                                <span class="font-medium">Contrats</span>
                            </a>


                        </div>

                        <!-- System -->
                        <div class="nav-section">
                            <div class="nav-group-header">Système</div>
                            
                            <a href="{{ route('settings.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="fas fa-cog"></i>
                                <span class="font-medium">Paramètres</span>
                            </a>
                            
                            <!-- SaaS Management - Super Admin Only -->
                            @if(auth()->user()->isSuperAdmin())
                            <div class="nav-subsection">
                                <div class="nav-subheader">Gestion SaaS</div>
                                
                                <a href="{{ route('saas.overview') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('saas.overview') ? 'active' : '' }}">
                                    <i class="fas fa-server"></i>
                                    <span class="font-medium">Vue d'ensemble SaaS</span>
                                </a>
                                
                                <a href="{{ route('saas.tenants.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('saas.tenants.*') ? 'active' : '' }}">
                                    <i class="fas fa-building"></i>
                                    <span class="font-medium">Gestion des Locataires</span>
                                </a>
                                
                                <a href="{{ route('saas.global-users.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('saas.global-users.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span class="font-medium">Utilisateurs Globaux</span>
                                </a>
                                
                                <a href="{{ route('saas.analytics.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('saas.analytics.*') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i>
                                    <span class="font-medium">Analyses Système</span>
                                </a>
                                
                                <a href="{{ route('saas.maintenance.index') }}" class="sidebar-nav-item text-gray-700 {{ request()->routeIs('saas.maintenance.*') ? 'active' : '' }}">
                                    <i class="fas fa-tools"></i>
                                    <span class="font-medium">Maintenance du Système</span>
                                </a>
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="sidebar-nav-item text-gray-700 w-full text-left hover:text-red-600">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="font-medium">Déconnexion</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>

                <!-- Sidebar Footer -->
                <div class="sidebar-footer p-6">
                    <!-- Language Switcher -->
                    <div class="mb-4">
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        @hasSection('sidebar')
        <div class="content-with-fixed-sidebar flex-1">
            <div class="flex">
                <div class="flex-1 main-content">
                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn fixed top-4 left-4 z-50 p-3 bg-white/90 rounded-xl shadow-lg md:hidden backdrop-blur-sm" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-gray-700"></i>
                    </button>
                    
                    <!-- Enhanced Header -->
                    <div class="mb-8">
                        @if(session('success'))
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <!-- Impersonation Notice -->
                        @if(session('impersonated_by'))
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-user-secret mr-2"></i>
                                    <span class="font-medium">You are impersonating: {{ auth()->user()->name }}</span>
                                </div>
                                <form action="{{ route('admin.return-from-impersonation') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-800 hover:text-yellow-900 underline font-medium">
                                        <i class="fas fa-arrow-left mr-1"></i>
                                        Return to Admin
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Page Content -->
                    @yield('content')
                </div>

            </div>
        </div>
        @else
        <div class="content-with-fixed-sidebar flex-1">
            <div class="main-content">
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn fixed top-4 left-4 z-50 p-3 bg-white/90 rounded-xl shadow-lg md:hidden backdrop-blur-sm" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
                
                <!-- Enhanced Header -->
                <div class="mb-8">
                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- Impersonation Notice -->
                    @if(session('impersonated_by'))
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user-secret mr-2"></i>
                                <span class="font-medium">You are impersonating: {{ auth()->user()->name }}</span>
                            </div>
                            <form action="{{ route('admin.return-from-impersonation') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-800 hover:text-yellow-900 underline font-medium">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Return to Admin
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
        @endif
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" onclick="closeMobileMenu()"></div>
    </div>

    <!-- Dynamic Validation JavaScript -->
    <script src="{{ asset('js/dynamic-validation.js') }}"></script>
    
    <!-- Enhanced JavaScript -->
    <script>
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar-fixed');
            const overlay = document.querySelector('.mobile-overlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        function closeMobileMenu() {
            const sidebar = document.querySelector('.sidebar-fixed');
            const overlay = document.querySelector('.mobile-overlay');
            
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }
        
        // Close mobile menu on window resize if switching to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        });
        
        // Enhanced form submission with validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Validate form before submission
                    if (window.dynamicValidator && !window.dynamicValidator.validateForm(form)) {
                        e.preventDefault();
                        
                        // Show validation summary
                        showValidationSummary(form);
                        
                        // Scroll to first error
                        const firstError = form.querySelector('.error');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                    }
                });
            });
        });
        
        // Show validation summary
        function showValidationSummary(form) {
            // Remove existing summary
            const existingSummary = form.querySelector('.validation-summary');
            if (existingSummary) {
                existingSummary.remove();
            }
            
            // Get all errors
            const errors = form.querySelectorAll('.error');
            if (errors.length === 0) return;
            
            // Create summary
            const summary = document.createElement('div');
            summary.className = 'validation-summary error';
            summary.innerHTML = `
                <h4>Please correct the following errors:</h4>
                <ul>
                    ${Array.from(errors).map(input => {
                        const errorMsg = document.getElementById(input.name + '-error');
                        return errorMsg ? `<li>${errorMsg.textContent}</li>` : '';
                    }).filter(Boolean).join('')}
                </ul>
            `;
            
            // Insert at the top of the form
            form.insertBefore(summary, form.firstChild);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (summary.parentNode) {
                    summary.remove();
                }
            }, 5000);
        }
    </script>
    @stack('scripts')
</body>
</html> 