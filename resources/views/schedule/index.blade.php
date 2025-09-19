@extends('layouts.app')

@section('title', 'Schedule')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Schedule
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manage lessons, exams, and instructor schedules
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-download mr-2"></i>
                Export Schedule
            </button>
            <a href="{{ route('lessons.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Schedule Lesson
            </a>
        </div>
    </div>

    <!-- Schedule Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="view_type" class="block text-sm font-medium text-gray-700">View Type</label>
                <select name="view_type" id="view_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="calendar">Calendar View</option>
                    <option value="list">List View</option>
                    <option value="instructor">By Instructor</option>
                    <option value="student">By Student</option>
                </select>
            </div>
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
                <select name="date_range" id="date_range" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div id="custom-date-range" class="hidden">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div id="custom-date-range-end" class="hidden">
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="button" id="apply-filters" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-filter mr-2"></i>
                Apply Filters
            </button>
        </div>
    </div>

    <!-- Calendar View -->
    <div id="calendar-view" class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Schedule Calendar</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">View all scheduled lessons and exams</p>
        </div>
        <div class="border-t border-gray-200 p-6">
            <div class="grid grid-cols-7 gap-4 mb-4">
                <div class="text-center font-medium text-gray-500">Monday</div>
                <div class="text-center font-medium text-gray-500">Tuesday</div>
                <div class="text-center font-medium text-gray-500">Wednesday</div>
                <div class="text-center font-medium text-gray-500">Thursday</div>
                <div class="text-center font-medium text-gray-500">Friday</div>
                <div class="text-center font-medium text-gray-500">Saturday</div>
                <div class="text-center font-medium text-gray-500">Sunday</div>
            </div>
            <div id="calendar-grid" class="grid grid-cols-7 gap-4">
                <!-- Calendar days will be loaded here -->
            </div>
        </div>
    </div>

    <!-- List View -->
    <div id="list-view" class="bg-white shadow rounded-lg hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Schedule List</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">All scheduled lessons and exams</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Instructor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="schedule-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Schedule items will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-book text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Lessons</dt>
                            <dd class="text-lg font-medium text-gray-900" id="todays-lessons">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-check text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Exams</dt>
                            <dd class="text-lg font-medium text-gray-900" id="todays-exams">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Instructors</dt>
                            <dd class="text-lg font-medium text-gray-900" id="active-instructors">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Hours</dt>
                            <dd class="text-lg font-medium text-gray-900" id="total-hours">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSchedule();
    loadQuickStats();
    
    // View type change
    document.getElementById('view_type').addEventListener('change', function() {
        const viewType = this.value;
        const calendarView = document.getElementById('calendar-view');
        const listView = document.getElementById('list-view');
        
        if (viewType === 'calendar') {
            calendarView.classList.remove('hidden');
            listView.classList.add('hidden');
        } else {
            calendarView.classList.add('hidden');
            listView.classList.remove('hidden');
        }
        
        loadSchedule();
    });
    
    // Date range change
    document.getElementById('date_range').addEventListener('change', function() {
        const customRange = document.getElementById('custom-date-range');
        const customRangeEnd = document.getElementById('custom-date-range-end');
        
        if (this.value === 'custom') {
            customRange.classList.remove('hidden');
            customRangeEnd.classList.remove('hidden');
        } else {
            customRange.classList.add('hidden');
            customRangeEnd.classList.add('hidden');
        }
    });
    
    // Apply filters
    document.getElementById('apply-filters').addEventListener('click', function() {
        loadSchedule();
    });
});

function loadSchedule() {
    const viewType = document.getElementById('view_type').value;
    const dateRange = document.getElementById('date_range').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (viewType === 'calendar') {
        loadCalendarView(dateRange, startDate, endDate);
    } else {
        loadListView(dateRange, startDate, endDate);
    }
}

function loadCalendarView(dateRange, startDate, endDate) {
    // Generate calendar grid for current week
    const today = new Date();
    const currentWeek = getWeekDates(today);
    
    const calendarGrid = document.getElementById('calendar-grid');
    calendarGrid.innerHTML = '';
    
    currentWeek.forEach(date => {
        const dayElement = document.createElement('div');
        dayElement.className = 'min-h-24 p-2 border border-gray-200 rounded-lg';
        dayElement.innerHTML = `
            <div class="text-sm font-medium text-gray-900">${date.getDate()}</div>
            <div class="mt-2 space-y-1" id="day-${date.toISOString().split('T')[0]}">
                <!-- Events will be loaded here -->
            </div>
        `;
        calendarGrid.appendChild(dayElement);
    });
    
    // Load events for each day
    loadEventsForWeek(currentWeek);
}

function loadListView(dateRange, startDate, endDate) {
    const params = new URLSearchParams();
    
    if (dateRange === 'custom' && startDate && endDate) {
        params.append('date_from', startDate);
        params.append('date_to', endDate);
    } else {
        const today = new Date();
        params.append('date_from', today.toISOString().split('T')[0]);
        params.append('date_to', today.toISOString().split('T')[0]);
    }
    
    // Load lessons
    fetch(`/api/lessons/by-date?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderScheduleList(data.data, 'lesson');
            }
        });
    
    // Load exams
    fetch(`/api/exams/by-date?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderScheduleList(data.data, 'exam');
            }
        });
}

function loadEventsForWeek(weekDates) {
    weekDates.forEach(date => {
        const dateStr = date.toISOString().split('T')[0];
        const dayContainer = document.getElementById(`day-${dateStr}`);
        
        // Load lessons for this date
        fetch(`/api/lessons/by-date?date=${dateStr}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(lesson => {
                        const eventElement = document.createElement('div');
                        eventElement.className = 'text-xs bg-blue-100 text-blue-800 p-1 rounded truncate';
                        eventElement.textContent = `${lesson.student?.name || 'Unknown'} - ${lesson.title || 'Lesson'}`;
                        dayContainer.appendChild(eventElement);
                    });
                }
            });
        
        // Load exams for this date
        fetch(`/api/exams/by-date?date=${dateStr}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(exam => {
                        const eventElement = document.createElement('div');
                        eventElement.className = 'text-xs bg-green-100 text-green-800 p-1 rounded truncate mt-1';
                        eventElement.textContent = `${exam.student?.name || 'Unknown'} - ${exam.exam_type || 'Exam'}`;
                        dayContainer.appendChild(eventElement);
                    });
                }
            });
    });
}

function renderScheduleList(items, type) {
    const tbody = document.getElementById('schedule-table-body');
    
    items.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${type === 'lesson' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                    ${type === 'lesson' ? 'Lesson' : 'Exam'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${new Date(item.scheduled_at).toLocaleString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.student?.name || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${item.instructor?.user?.name || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(item.status)}">
                    ${getStatusText(item.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewItem('${type}', ${item.id})" class="text-indigo-600 hover:text-indigo-900">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="/${type}s/${item.id}/edit" class="text-yellow-600 hover:text-yellow-900">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function loadQuickStats() {
    // Load today's lessons
    fetch('/api/lessons/by-date?date=' + new Date().toISOString().split('T')[0])
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('todays-lessons').textContent = data.data.length;
            }
        });
    
    // Load today's exams
    fetch('/api/exams/by-date?date=' + new Date().toISOString().split('T')[0])
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('todays-exams').textContent = data.data.length;
            }
        });
    
    // Load active instructors
    fetch('/api/instructors?status=active')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('active-instructors').textContent = data.data.total || 0;
            }
        });
}

function getWeekDates(date) {
    const week = [];
    const start = new Date(date);
    start.setDate(date.getDate() - date.getDay());
    
    for (let i = 0; i < 7; i++) {
        const day = new Date(start);
        day.setDate(start.getDate() + i);
        week.push(day);
    }
    
    return week;
}

function getStatusClass(status) {
    const classes = {
        'scheduled': 'bg-yellow-100 text-yellow-800',
        'in_progress': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'passed': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'scheduled': 'Scheduled',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'cancelled': 'Cancelled',
        'passed': 'Passed',
        'failed': 'Failed'
    };
    return texts[status] || 'Unknown';
}

function viewItem(type, id) {
    window.location.href = `/${type}s/${id}`;
}
</script>
@endpush
