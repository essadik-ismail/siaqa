@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold gradient-text">Tableau de Bord</h1>
            <p class="mt-2 text-gray-600 text-lg">Planifiez, priorisez et gérez votre auto-école avec facilité.</p>
        </div>

        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Étudiants</p>
                        <p class="text-4xl font-bold text-gray-900 mb-2" id="total-students">{{ $stats['total_students'] ?? 0 }}</p>
                        <div class="flex items-center">
                            <i class="fas fa-arrow-up text-blue-500 text-xs mr-1 pulse-animation"></i>
                            <span class="text-xs text-blue-600 font-medium">Augmenté depuis le mois dernier</span>
                        </div>
                    </div>
                    <div class="icon-container w-14 h-14">
                        <i class="fas fa-user-graduate text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Lessons -->
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Leçons Actives</p>
                        <p class="text-4xl font-bold text-gray-900 mb-2" id="total-lessons">{{ $stats['total_lessons'] ?? 0 }}</p>
                        <div class="flex items-center">
                            <i class="fas fa-arrow-up text-purple-500 text-xs mr-1 pulse-animation"></i>
                            <span class="text-xs text-purple-600 font-medium">Augmenté depuis le mois dernier</span>
                        </div>
                    </div>
                    <div class="icon-container w-14 h-14" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                        <i class="fas fa-book text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Instructors -->
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Instructeurs</p>
                        <p class="text-4xl font-bold text-gray-900 mb-2" id="total-instructors">{{ $stats['total_instructors'] ?? 0 }}</p>
                        <div class="flex items-center">
                            <i class="fas fa-arrow-up text-indigo-500 text-xs mr-1 pulse-animation"></i>
                            <span class="text-xs text-indigo-600 font-medium">Augmenté depuis le mois dernier</span>
                        </div>
                    </div>
                    <div class="icon-container w-14 h-14" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Exams -->
            <div class="stats-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Examens en Attente</p>
                        <p class="text-4xl font-bold text-gray-900 mb-2" id="total-exams">{{ $stats['total_exams'] ?? 0 }}</p>
                        <div class="flex items-center">
                            <span class="text-xs text-pink-600 font-medium">À l'Heure</span>
                        </div>
                    </div>
                    <div class="icon-container w-14 h-14" style="background: linear-gradient(135deg, #ec4899 0%, #f97316 100%);">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Lesson Analytics -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 material-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Analyses des Leçons</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Cette Semaine</button>
                        </div>
                    </div>
                    <div class="h-64 flex items-end space-x-2">
                        @php
                            $chartData = $chartData ?? ['lessons' => [12, 19, 8, 15, 22, 18, 25]];
                            $maxValue = max($chartData['lessons']);
                            // Prevent division by zero
                            if ($maxValue == 0) {
                                $maxValue = 1;
                            }
                        @endphp
                        @foreach($chartData['lessons'] as $index => $value)
                            <div class="flex-1 flex flex-col items-center group">
                                <div class="w-full bg-gradient-to-t from-blue-500 to-purple-400 rounded-t-lg mb-2 transition-all duration-300 group-hover:from-blue-600 group-hover:to-purple-500" 
                                     style="height: {{ ($value / $maxValue) * 200 }}px;">
                                </div>
                                <span class="text-xs text-gray-500 font-medium">{{ ['S', 'M', 'T', 'W', 'T', 'F', 'S'][$index] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Lessons -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 material-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Leçons Récentes</h3>
                        <button class="text-blue-600 text-sm font-medium">+ Nouveau</button>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentLessons ?? [] as $lesson)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $lesson->title ?? 'Leçon Sans Titre' }}</p>
                                        <p class="text-sm text-gray-500">{{ $lesson->student->name ?? 'Aucun Étudiant' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Échéance: {{ $lesson->scheduled_at ? $lesson->scheduled_at->format('d M, Y') : 'Non programmé' }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $lesson->status ?? 'pending')) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Aucune leçon récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Upcoming Exams -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Examens à Venir</h3>
                        <button class="text-blue-600 text-sm font-medium">+ Nouveau</button>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentExams ?? [] as $exam)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $exam->title ?? 'Examen Sans Titre' }}</p>
                                        <p class="text-sm text-gray-500">{{ $exam->student->name ?? 'Aucun Étudiant' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Échéance: {{ $exam->scheduled_at ? $exam->scheduled_at->format('d M, Y') : 'Non programmé' }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $exam->status == 'completed' ? 'bg-green-100 text-green-800' : ($exam->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $exam->status ?? 'pending')) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No upcoming exams</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Instructor Performance -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Instructor Performance</h3>
                        <button class="text-blue-600 text-sm font-medium">+ Add Member</button>
                    </div>
                    <div class="space-y-4">
                        @forelse($instructors ?? [] as $instructor)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($instructor->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $instructor->name ?? 'Unknown Instructor' }}</p>
                                        <p class="text-sm text-gray-500">{{ $instructor->lessons_count ?? 0 }} lessons completed</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ ($instructor->is_available ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ($instructor->is_available ?? false) ? 'Available' : 'Busy' }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No instructors</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Progress Overview -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Progress Overview</h3>
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4">
                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                <circle cx="60" cy="60" r="50" stroke="#10b981" stroke-width="8" fill="none" 
                                        stroke-dasharray="314" stroke-dashoffset="185" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-900">41%</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Students Completed</p>
                        <div class="flex justify-center space-x-4 text-xs">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">Completed</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-gray-600">In Progress</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                <span class="text-gray-600">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="material-card p-6 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="text-xl font-bold mb-6">Actions Rapides</h3>
                    <div class="space-y-4">
                        <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 text-left transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                            <i class="fas fa-plus mr-3 text-lg"></i>
                            <span class="font-medium">Ajouter un Nouvel Étudiant</span>
                        </button>
                        <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 text-left transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                            <i class="fas fa-calendar mr-3 text-lg"></i>
                            <span class="font-medium">Programmer une Leçon</span>
                        </button>
                        <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 text-left transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                            <i class="fas fa-chart-bar mr-3 text-lg"></i>
                            <span class="font-medium">Voir les Rapports</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh stats every 30 seconds
setInterval(function() {
    fetch('{{ route("dashboard.tab-data") }}?tab=stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-students').textContent = data.data.total_students || 0;
                document.getElementById('total-lessons').textContent = data.data.total_lessons || 0;
                document.getElementById('total-instructors').textContent = data.data.total_instructors || 0;
                document.getElementById('total-exams').textContent = data.data.total_exams || 0;
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}, 30000);
</script>
@endsection