@extends('layouts.modern-landing')

@section('title', 'Siaqa - Driving School Management')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute top-40 right-20 w-32 h-32 bg-purple-200 rounded-full opacity-20 animate-pulse delay-1000"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-pink-200 rounded-full opacity-20 animate-pulse delay-2000"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        AI-Driven driving schools with 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                            human-level precision
                        </span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl">
                        Empower your driving school with Siaqa's AI-driven management that executes tasks with human-level precision, efficiency, and reliability.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#demo" class="bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Try for free
                        </a>
                        <a href="#contact" class="bg-white text-gray-900 px-8 py-4 rounded-full text-lg font-semibold border-2 border-gray-200 hover:border-gray-300 transition-colors duration-200 shadow-lg hover:shadow-xl">
                            Request a Demo
                        </a>
                    </div>

                    <!-- Dev Login Button -->
                    <div class="mt-6 flex justify-center lg:justify-start">
                        <a href="{{ route('dev.login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-code mr-2"></i>
                            Dev Login
                        </a>
                    </div>
                </div>
                
                <!-- Right Column - Interactive Dashboard Preview -->
                <div class="relative">
                    <!-- Main Dashboard Card -->
                    <div class="bg-white rounded-3xl shadow-2xl p-8 relative z-10">
                        <!-- Dashboard Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Siaqa Dashboard</h3>
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                        </div>
                        
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <!-- Student Progress -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-2xl">
                                <div class="text-2xl font-bold text-blue-600 mb-1">48%</div>
                                <div class="text-sm text-blue-800">Students Passed</div>
                            </div>
                            
                            <!-- Revenue -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-2xl">
                                <div class="text-2xl font-bold text-purple-600 mb-1">68%</div>
                                <div class="text-sm text-purple-800">Revenue Growth</div>
                            </div>
                        </div>
                        
                        <!-- Main Revenue Card -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-2xl mb-6">
                            <div class="text-3xl font-bold mb-2">7,854 DH</div>
                            <div class="text-blue-100 text-sm">Monthly Revenue</div>
                            <div class="text-blue-200 text-xs mt-1">Previous month (5,420 DH)</div>
                        </div>
                        
                        <!-- Performance Metrics -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800 mb-1">4,682 DH</div>
                                <div class="text-sm text-gray-600">Avg. Lesson Price</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800 mb-1">88%</div>
                                <div class="text-sm text-gray-600">Success Rate</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Partners Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h3 class="text-lg text-gray-500 font-medium">Our trusted partners</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center opacity-60">
                <div class="text-center text-2xl font-bold text-gray-400">hotjar</div>
                <div class="text-center text-2xl font-bold text-gray-400">loom</div>
                <div class="text-center text-2xl font-bold text-gray-400">Lattice</div>
                <div class="text-center text-2xl font-bold text-gray-400">Evernote</div>
                <div class="text-center text-2xl font-bold text-gray-400">hotjar</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Why driving schools love our 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                        AI-Powered platform
                    </span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Streamline your driving school operations with intelligent automation, real-time insights, and seamless student management.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">AI-Powered Scheduling</h3>
                    <p class="text-gray-600 mb-6">Automatically optimize lesson schedules, instructor assignments, and vehicle allocation with intelligent algorithms.</p>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="text-sm text-blue-800 font-medium mb-2">Smart Scheduling</div>
                        <div class="text-xs text-blue-600">Reduces scheduling conflicts by 95%</div>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Real-Time Analytics</h3>
                    <p class="text-gray-600 mb-6">Track student progress, instructor performance, and revenue metrics with comprehensive dashboards.</p>
                    <div class="bg-purple-50 rounded-xl p-4">
                        <div class="text-sm text-purple-800 font-medium mb-2">Live Insights</div>
                        <div class="text-xs text-purple-600">Monitor performance 24/7</div>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Student Management</h3>
                    <p class="text-gray-600 mb-6">Complete student lifecycle management from enrollment to certification with automated workflows.</p>
                    <div class="bg-pink-50 rounded-xl p-4">
                        <div class="text-sm text-pink-800 font-medium mb-2">Automated Workflows</div>
                        <div class="text-xs text-pink-600">Streamline student journey</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    What driving schools are saying
                </h2>
                <p class="text-xl text-gray-600">
                    Trusted by high-performing driving schools worldwide
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            JG
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Jake George</div>
                            <div class="text-sm text-gray-600">Founder, Auto École Excellence</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-4">
                        "Siaqa has revolutionized our operations. The AI scheduling alone saved us 15 hours per week, and our student pass rate increased by 30%."
                    </p>
                    <div class="flex text-yellow-400">
                        ★★★★★
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            SA
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Sarah Anderson</div>
                            <div class="text-sm text-gray-600">Manager, City Driving School</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-4">
                        "The real-time analytics help us make data-driven decisions. We've seen a 40% increase in revenue since implementing the platform."
                    </p>
                    <div class="flex text-yellow-400">
                        ★★★★★
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                            MR
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Mike Rodriguez</div>
                            <div class="text-sm text-gray-600">CEO, Metro Driving Academy</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic mb-4">
                        "The student management features are incredible. Everything is automated, from enrollment to certification tracking."
                    </p>
                    <div class="flex text-yellow-400">
                        ★★★★★
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">FAQs</h2>
                <p class="text-xl text-gray-600">Everything you need to know before getting started</p>
            </div>
            
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <button class="w-full text-left flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">How does AI improve my driving school operations?</h3>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Our AI algorithms optimize scheduling, predict student success rates, automate administrative tasks, and provide insights to improve your school's performance and profitability.
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <button class="w-full text-left flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Is my data secure?</h3>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Yes, we use enterprise-grade security with end-to-end encryption, GDPR compliance, and regular security audits to protect your data.
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <button class="w-full text-left flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Can I integrate with my existing systems?</h3>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Absolutely! We offer APIs and integrations with popular accounting software, payment processors, and government exam systems.
                    </div>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <button class="w-full text-left flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Is there a free trial?</h3>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mt-4 text-gray-600">
                        Yes! We offer a 14-day free trial with full access to all features. No credit card required to get started.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Supercharge your driving school with Siaqa today!
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Join hundreds of driving schools already using Siaqa to streamline operations and boost success rates.
            </p>
            <a href="#demo" class="bg-white text-blue-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-colors duration-200 shadow-lg hover:shadow-xl inline-block">
                Start Your Free Trial
            </a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Simple FAQ toggle functionality
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('svg');
                
                if (content.style.display === 'none' || content.style.display === '') {
                    content.style.display = 'block';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    content.style.display = 'none';
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
@endpush