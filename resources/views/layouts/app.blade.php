<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Car Rental SaaS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
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
        
        /* Enhanced sidebar styles with Material Design */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem; /* Always 256px */
            overflow-y: auto;
            z-index: 40;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid rgba(0,0,0,0.06);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(20px);
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
        
        /* Enhanced sidebar footer area */
        .sidebar-footer {
            position: relative;
            z-index: 2;
        }
        
        .content-with-fixed-sidebar {
            margin-left: 16rem; /* Always 256px */
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
            border-radius: 16px;
            margin: 0.375rem 0.75rem;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            letter-spacing: 0.025em;
            cursor: pointer;
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
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            background: rgba(25, 118, 210, 0.04);
            color: var(--md-primary);
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
        
        /* Active navigation item with Material Design */
        .sidebar-nav-item.active {
            background: transparent;
            color: var(--md-primary);
            box-shadow: none;
            transform: none;
            position: relative;
            overflow: hidden;
            font-weight: 600;
            border-left: 4px solid #1e3a8a;
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
            background: linear-gradient(135deg, var(--md-primary) 0%, var(--md-secondary) 100%);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 8px 32px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .logo-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: logo-shine 6s infinite;
        }
        
        @keyframes logo-shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        /* Enhanced user profile section */
        .user-profile {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        /* Enhanced navigation group headers */
        .nav-group-header {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6b7280;
            margin: 1.5rem 0.75rem 0.5rem;
            padding-left: 0.5rem;
            position: relative;
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
                    <div class="logo-section mb-8">
                        <div class="flex items-center space-x-4 relative z-10">
                            <div class="w-14 h-14 bg-white/25 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-lg">
                                <span class="text-white font-bold text-3xl drop-shadow-lg">C</span>
                            </div>
                            <div>
                                <span class="text-white font-bold text-2xl drop-shadow-lg block">CarRental</span>
                                <span class="text-white/80 text-sm font-medium">Management System</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <div class="sidebar-navigation px-6">
                    <!-- Enhanced Navigation -->
                    <nav class="space-y-1">
                        <!-- Main Navigation -->
                        <div class="nav-section">
                            <a href="{{ route('dashboard') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dashboard') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="font-medium">Dashboard</span>
                            </a>

                            <!-- <a href="{{ route('reports.customers') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('reports.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-chart-bar"></i>
                                <span class="font-medium">Reports</span>
                            </a> -->
                        </div>

                        <!-- Business Management -->
                        <div class="nav-section">
                            <div class="nav-group-header">Business</div>
                            
                            <a href="{{ route('clients.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('clients.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-users"></i>
                                <span class="font-medium">Clients</span>
                            </a>

                            <a href="{{ route('vehicules.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('vehicules.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-car"></i>
                                <span class="font-medium">Vehicles</span>
                            </a>

                            <a href="{{ route('charges.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('charges.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span class="font-medium">Les charges</span>
                            </a>
                        </div>

                        <!-- Operations -->
                        <div class="nav-section">
                            <div class="nav-group-header">Operations</div>
                            
                            <a href="{{ route('reservations.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('reservations.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span class="font-medium">Reservations</span>
                            </a>

                            <a href="{{ route('contrats.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('contrats.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-file-contract"></i>
                                <span class="font-medium">Contracts</span>
                            </a>


                        </div>

                        <!-- System -->
                        <div class="nav-section">
                            <div class="nav-group-header">System</div>
                            
                            <a href="{{ route('settings.index') }}" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('settings.*') ? 'active' : 'hover:bg-gray-50' }}">
                                <i class="fas fa-cog"></i>
                                <span class="font-medium">Settings</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="sidebar-nav-item flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-50 w-full text-left">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>

                <!-- Sidebar Footer -->
                <div class="sidebar-footer p-6">
                    <!-- Enhanced User Profile -->
                    <div class="user-profile p-5">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg border-2 border-white/20">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                                <div class="text-xs text-blue-600 font-medium mt-1">Administrator</div>
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-with-fixed-sidebar flex-1 flex">
            <div class="flex-1 main-content">
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn fixed top-4 left-4 z-50 p-3 bg-white/90 rounded-xl shadow-lg md:hidden backdrop-blur-sm" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
                
                <!-- Enhanced Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">@yield('title', 'Dashboard')</h1>
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
                </div>

                <!-- Page Content -->
                @yield('content')
            </div>

            <!-- Right Sidebar (if needed) -->
            @hasSection('sidebar')
            <div class="w-80 bg-white/80 backdrop-blur-sm shadow-lg p-6 rounded-l-2xl border-l border-gray-200">
                @yield('sidebar')
            </div>
            @endif
        </div>
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" onclick="closeMobileMenu()"></div>
    </div>

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
        

    </script>
    @stack('scripts')
</body>
</html> 