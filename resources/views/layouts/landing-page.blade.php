<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>@yield('title', 'Odys - Rent your favourite car')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Dynamic Validation CSS -->
    <link rel="stylesheet" href="{{ asset('css/dynamic-validation.css') }}">
    
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
                        'material-1': '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)',
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
        }
        
        .material-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        }
        
        .material-card:hover {
            box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
            transform: translateY(-2px);
        }
        
        .material-button {
            background: #2196f3;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .material-button:hover {
            background: #1976d2;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        
        .material-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .material-input {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 16px;
            transition: all 0.3s ease;
        }
        
        .material-input:focus {
            background: white;
            border-color: #2196f3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.2);
            outline: none;
        }
        
        .material-fab {
            background: #2196f3;
            color: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }
        
        .material-fab:hover {
            background: #1976d2;
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
            transform: translateY(-2px);
        }
        
        .material-chip {
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 16px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .material-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 24px 0;
        }
        
        .material-elevation-1 { box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); }
        .material-elevation-2 { box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); }
        .material-elevation-3 { box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23); }
        .material-elevation-4 { box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22); }
        .material-elevation-5 { box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22); }

        /* Status Indicators */
        .status-available { @apply bg-green-100 text-green-800; }
        .status-rented { @apply bg-red-100 text-red-800; }
        .status-maintenance { @apply bg-yellow-100 text-yellow-800; }

        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* RTL Support */
        [dir="rtl"] .container {
            text-align: right;
        }

        [dir="rtl"] .btn {
            direction: rtl;
        }

        /* Arabic Language Specific Styles */
        [dir="rtl"] {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        [dir="rtl"] .hero-title {
            text-align: right;
            line-height: 1.4;
        }

        [dir="rtl"] .hero-text {
            text-align: right;
            line-height: 1.6;
        }

        [dir="rtl"] .section-title {
            text-align: right;
        }

        [dir="rtl"] .card-title {
            text-align: right;
        }

        [dir="rtl"] .card-text {
            text-align: right;
            line-height: 1.6;
        }

        [dir="rtl"] .stats-text {
            text-align: right;
        }

        [dir="rtl"] .blog-link {
            text-align: right;
        }

        [dir="rtl"] .featured-car-link {
            text-align: right;
        }

        /* RTL Navigation Adjustments */
        [dir="rtl"] .navbar .flex {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .navbar .ml-3 {
            margin-left: 0;
            margin-right: 0.75rem;
        }

        [dir="rtl"] .navbar .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        [dir="rtl"] .navbar .space-x-4 > * + * {
            margin-left: 0;
            margin-right: 1rem;
        }

        [dir="rtl"] .navbar .space-x-3 > * + * {
            margin-left: 0;
            margin-right: 0.75rem;
        }

        [dir="rtl"] .navbar .space-x-1 > * + * {
            margin-left: 0;
            margin-right: 0.25rem;
        }

        [dir="rtl"] .navbar .space-x-2 > * + * {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        [dir="rtl"] .navbar .space-x-6 > * + * {
            margin-left: 0;
            margin-right: 1.5rem;
        }

        /* RTL Search Bar */
        [dir="rtl"] .search-bar .pl-12 {
            padding-left: 1rem;
            padding-right: 3rem;
        }

        [dir="rtl"] .search-bar .pr-4 {
            padding-right: 1rem;
            padding-left: 1rem;
        }

        [dir="rtl"] .search-bar .pl-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        [dir="rtl"] .search-bar .pl-10 {
            padding-left: 1rem;
            padding-right: 2.5rem;
        }

        [dir="rtl"] .search-bar .pr-3 {
            padding-right: 1rem;
            padding-left: 0.75rem;
        }

        /* RTL Dropdown Menu */
        [dir="rtl"] .user-dropdown-menu {
            right: auto;
            left: 0;
        }

        [dir="rtl"] .user-dropdown-menu .mr-3 {
            margin-right: 0;
            margin-left: 0.75rem;
        }

        /* RTL Footer */
        [dir="rtl"] .footer .text-left {
            text-align: right;
        }

        [dir="rtl"] .footer .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        /* RTL Form Elements */
        [dir="rtl"] .form-group label {
            text-align: right;
        }

        [dir="rtl"] .form-group input,
        [dir="rtl"] .form-group textarea,
        [dir="rtl"] .form-group select {
            text-align: right;
        }

        /* RTL Button Icons */
        [dir="rtl"] .btn svg {
            transform: scaleX(-1);
        }

        [dir="rtl"] .btn .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        /* RTL Card Elements */
        [dir="rtl"] .card-list-item .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        [dir="rtl"] .card-price-wrapper .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        /* RTL Modal */
        [dir="rtl"] .modal-header .close-btn {
            margin-left: 0;
            margin-right: auto;
        }

        [dir="rtl"] .form-actions {
            justify-content: flex-start;
        }

        /* RTL Language Switcher */
        [dir="rtl"] .language-switcher .space-x-1 > * + * {
            margin-left: 0;
            margin-right: 0.25rem;
        }

        /* RTL Search Filters */
        [dir="rtl"] .filter-grid {
            direction: rtl;
        }

        [dir="rtl"] .filter-grid > * {
            direction: ltr;
        }

        /* RTL Pagination */
        [dir="rtl"] .pagination-wrapper {
            direction: rtl;
        }

        [dir="rtl"] .pagination-wrapper > * {
            direction: ltr;
        }

        /* RTL Status Badge */
        [dir="rtl"] .status-badge {
            right: auto;
            left: 15px;
        }

        /* RTL Hero Form */
        [dir="rtl"] .hero-form {
            direction: rtl;
        }

        [dir="rtl"] .hero-form > * {
            direction: ltr;
        }

        /* RTL Blog Cards */
        [dir="rtl"] .blog-card .card-content {
            text-align: right;
        }

        [dir="rtl"] .blog-card .btn {
            text-align: center;
        }

        /* RTL Featured Car Cards */
        [dir="rtl"] .featured-car-card .card-content {
            text-align: right;
        }

        [dir="rtl"] .featured-car-card .btn {
            text-align: center;
        }

        /* RTL Stats Cards */
        [dir="rtl"] .stats-card .stats-content {
            text-align: right;
        }

        /* RTL Quick Links */
        [dir="rtl"] .quick-links ul {
            text-align: right;
        }

        /* RTL Services */
        [dir="rtl"] .services ul {
            text-align: right;
        }

        /* RTL Contact Info */
        [dir="rtl"] .contact-info {
            text-align: right;
        }

        /* RTL Alert Messages */
        [dir="rtl"] .alert {
            text-align: right;
        }

        /* RTL Input Placeholders */
        [dir="rtl"] input::placeholder,
        [dir="rtl"] textarea::placeholder {
            text-align: right;
        }

        /* RTL Select Options */
        [dir="rtl"] select option {
            text-align: right;
        }

        /* RTL Table Content */
        [dir="rtl"] table th,
        [dir="rtl"] table td {
            text-align: right;
        }

        /* RTL List Items */
        [dir="rtl"] ul li,
        [dir="rtl"] ol li {
            text-align: right;
        }

        /* RTL Paragraphs */
        [dir="rtl"] p {
            text-align: right;
        }

        /* RTL Headings */
        [dir="rtl"] h1, [dir="rtl"] h2, [dir="rtl"] h3, 
        [dir="rtl"] h4, [dir="rtl"] h5, [dir="rtl"] h6 {
            text-align: right;
        }

        /* RTL Spans */
        [dir="rtl"] span {
            text-align: right;
        }

        /* RTL Divs */
        [dir="rtl"] div {
            text-align: right;
        }

        /* RTL Hero Section Fixes */
        [dir="rtl"] .hero {
            direction: rtl;
        }

        [dir="rtl"] .hero .container {
            direction: ltr;
        }

        [dir="rtl"] .hero-content {
            text-align: right;
        }

        [dir="rtl"] .hero-banner {
            direction: ltr;
        }

        /* RTL Featured Car Section Fixes */
        [dir="rtl"] .featured-car .container {
            direction: rtl;
        }

        [dir="rtl"] .featured-car .container > * {
            direction: ltr;
        }

        [dir="rtl"] .featured-car-list {
            direction: rtl;
        }

        [dir="rtl"] .featured-car-list > * {
            direction: ltr;
        }

        [dir="rtl"] .featured-car-card {
            direction: rtl;
        }

        [dir="rtl"] .featured-car-card > * {
            direction: ltr;
        }

        /* RTL Blog Section Fixes */
        [dir="rtl"] .blog .container {
            direction: rtl;
        }

        [dir="rtl"] .blog .container > * {
            direction: ltr;
        }

        [dir="rtl"] .blog-list {
            direction: rtl;
        }

        [dir="rtl"] .blog-list > * {
            direction: ltr;
        }

        [dir="rtl"] .blog-card {
            direction: rtl;
        }

        [dir="rtl"] .blog-card > * {
            direction: ltr;
        }

        /* RTL Stats Section Fixes */
        [dir="rtl"] .stats .container {
            direction: rtl;
        }

        [dir="rtl"] .stats .container > * {
            direction: ltr;
        }

        [dir="rtl"] .stats-grid {
            direction: rtl;
        }

        [dir="rtl"] .stats-grid > * {
            direction: ltr;
        }

        /* RTL Section Layout Fixes */
        [dir="rtl"] .section .container {
            direction: rtl;
        }

        [dir="rtl"] .section .container > * {
            direction: ltr;
        }

        /* RTL Grid Layout Fixes */
        [dir="rtl"] .grid {
            direction: rtl;
        }

        [dir="rtl"] .grid > * {
            direction: ltr;
        }

        /* RTL Flexbox Fixes */
        [dir="rtl"] .flex {
            direction: rtl;
        }

        [dir="rtl"] .flex > * {
            direction: ltr;
        }

        /* RTL Card Layout Fixes */
        [dir="rtl"] .card-banner {
            direction: ltr;
        }

        [dir="rtl"] .card-content {
            direction: rtl;
        }

        [dir="rtl"] .card-content > * {
            direction: ltr;
        }

        /* RTL Title Wrapper Fixes */
        [dir="rtl"] .title-wrapper {
            direction: rtl;
        }

        [dir="rtl"] .title-wrapper > * {
            direction: ltr;
        }

        /* RTL Form Layout Fixes */
        [dir="rtl"] .hero-form {
            direction: rtl;
        }

        [dir="rtl"] .hero-form .input-wrapper {
            direction: ltr;
        }

        /* RTL Button Layout Fixes */
        [dir="rtl"] .btn {
            direction: ltr;
        }

        /* RTL List Layout Fixes */
        [dir="rtl"] .card-list {
            direction: rtl;
        }

        [dir="rtl"] .card-list > * {
            direction: ltr;
        }

        [dir="rtl"] .card-list-item {
            direction: rtl;
        }

        [dir="rtl"] .card-list-item > * {
            direction: ltr;
        }

        /* RTL Price Wrapper Fixes */
        [dir="rtl"] .card-price-wrapper {
            direction: rtl;
        }

        [dir="rtl"] .card-price-wrapper > * {
            direction: ltr;
        }

        /* RTL Footer Layout Fixes */
        [dir="rtl"] footer .container {
            direction: rtl;
        }

        [dir="rtl"] footer .container > * {
            direction: ltr;
        }

        /* RTL Navigation Layout Fixes */
        [dir="rtl"] nav .container {
            direction: rtl;
        }

        [dir="rtl"] nav .container > * {
            direction: ltr;
        }

        /* RTL Search Bar Layout Fixes */
        [dir="rtl"] .search-bar {
            direction: rtl;
        }

        [dir="rtl"] .search-bar > * {
            direction: ltr;
        }

        /* RTL Language Switcher Layout Fixes */
        [dir="rtl"] .language-switcher {
            direction: rtl;
        }

        [dir="rtl"] .language-switcher > * {
            direction: ltr;
        }

        /* RTL User Actions Layout Fixes */
        [dir="rtl"] .user-actions {
            direction: rtl;
        }

        [dir="rtl"] .user-actions > * {
            direction: ltr;
        }

        /* RTL Mobile Menu Layout Fixes */
        [dir="rtl"] .mobile-menu {
            direction: rtl;
        }

        [dir="rtl"] .mobile-menu > * {
            direction: ltr;
        }

        /* RTL Login Popup Layout Fixes */
        [dir="rtl"] .login-popup {
            direction: rtl;
        }

        [dir="rtl"] .login-popup > * {
            direction: ltr;
        }

        /* RTL Modal Layout Fixes */
        [dir="rtl"] .modal {
            direction: rtl;
        }

        [dir="rtl"] .modal > * {
            direction: ltr;
        }

        /* RTL Form Group Layout Fixes */
        [dir="rtl"] .form-group {
            direction: rtl;
        }

        [dir="rtl"] .form-group > * {
            direction: ltr;
        }

        /* RTL Form Actions Layout Fixes */
        [dir="rtl"] .form-actions {
            direction: rtl;
        }

        [dir="rtl"] .form-actions > * {
            direction: ltr;
        }

        /* RTL Alert Layout Fixes */
        [dir="rtl"] .alert {
            direction: rtl;
        }

        [dir="rtl"] .alert > * {
            direction: ltr;
        }

        /* RTL Status Badge Layout Fixes */
        [dir="rtl"] .status-badge {
            direction: ltr;
        }

        /* RTL Pagination Layout Fixes */
        [dir="rtl"] .pagination-wrapper {
            direction: rtl;
        }

        [dir="rtl"] .pagination-wrapper > * {
            direction: ltr;
        }

        /* RTL Filter Grid Layout Fixes */
        [dir="rtl"] .filter-grid {
            direction: rtl;
        }

        [dir="rtl"] .filter-grid > * {
            direction: ltr;
        }

        /* RTL Input Wrapper Layout Fixes */
        [dir="rtl"] .input-wrapper {
            direction: rtl;
        }

        [dir="rtl"] .input-wrapper > * {
            direction: ltr;
        }

        /* RTL Close Button Layout Fixes */
        [dir="rtl"] .close-btn {
            direction: ltr;
        }

        /* RTL Dropdown Menu Layout Fixes */
        [dir="rtl"] .user-dropdown-menu {
            direction: rtl;
        }

        [dir="rtl"] .user-dropdown-menu > * {
            direction: ltr;
        }

        /* RTL Mobile Search Bar Layout Fixes */
        [dir="rtl"] .mobile-search-bar {
            direction: rtl;
        }

        [dir="rtl"] .mobile-search-bar > * {
            direction: ltr;
        }

        /* RTL Hero Text and Form Specific Fixes */
        [dir="rtl"] .hero-text {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero-form {
            direction: rtl;
        }

        [dir="rtl"] .hero-form .input-wrapper {
            direction: ltr;
        }

        [dir="rtl"] .hero-form .input-label {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero-form .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero-form .btn {
            direction: ltr;
        }

        /* RTL Filter Form Specific Fixes */
        [dir="rtl"] .filter-form {
            direction: rtl;
        }

        [dir="rtl"] .filter-form .input-wrapper {
            direction: ltr;
        }

        [dir="rtl"] .filter-form .input-label {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .filter-form .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .filter-form .btn {
            direction: ltr;
        }

        /* RTL Input Field Specific Fixes */
        [dir="rtl"] .input-field {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        /* RTL Select Options Fixes */
        [dir="rtl"] select option {
            text-align: left !important;
            direction: ltr !important;
        }

        /* Additional RTL Form Styling */
        [dir="rtl"] .input-wrapper {
            direction: ltr;
        }

        [dir="rtl"] .input-wrapper > * {
            direction: ltr;
        }

        [dir="rtl"] .input-label {
            text-align: left !important;
            direction: ltr !important;
            display: block;
        }

        [dir="rtl"] .input-field {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        /* RTL Button Styling */
        [dir="rtl"] .btn {
            direction: ltr;
            text-align: center;
        }

        /* RTL Hero Section Specific */
        [dir="rtl"] .hero .hero-text {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero .hero-form {
            direction: rtl;
        }

        [dir="rtl"] .hero .hero-form .input-wrapper {
            direction: ltr;
        }

        [dir="rtl"] .hero .hero-form .input-label {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero .hero-form .input-field {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .hero .hero-form .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        /* RTL Filter Form Specific Styling */
        [dir="rtl"] .filter-form {
            direction: rtl;
        }

        [dir="rtl"] .filter-form .filter-grid {
            direction: rtl;
        }

        [dir="rtl"] .filter-form .filter-grid > * {
            direction: ltr;
        }

        [dir="rtl"] .filter-form .input-wrapper {
            direction: ltr;
        }

        [dir="rtl"] .filter-form .input-label {
            text-align: left !important;
            direction: ltr !important;
            display: block;
        }

        [dir="rtl"] .filter-form .input-field {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .filter-form .input-field::placeholder {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .filter-form select {
            text-align: left !important;
            direction: ltr !important;
        }

        [dir="rtl"] .filter-form select option {
            text-align: left !important;
            direction: ltr !important;
        }

        /* Language Switcher */
        .language-switcher {
            margin-left: 1rem;
        }

        /* Login Popup Styles */
        .login-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-popup-content {
            background: white;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            margin: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .login-popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .login-popup-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: #dc3545;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .login-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e9ecef;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .login-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            width: 100%;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #5a6fd8;
        }

        /* Success/Error Messages */
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .navbar-nav {
                text-align: center;
                margin-top: 1rem;
            }
            
            .language-switcher {
                margin: 1rem 0 0 0;
                text-align: center;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-roboto antialiased bg-surface-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-100 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo and Brand -->
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-3 hover:opacity-90 transition-opacity duration-200">
                        <img src="{{ asset('assets/images/odys-logo-modern.svg') }}" alt="Odys Rental Management" class="h-10 w-auto">
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <form action="{{ route('landing.cars') }}" method="GET" class="relative w-full">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search cars, brands, or models..." 
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                                   value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Side Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- User Actions -->
                    <div class="flex items-center space-x-3">
                        @guest
                            <button onclick="showLoginPopup()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('app.sign_in') }}
                            </button>
                        @else
                            <!-- User Menu -->
                            <div class="relative user-menu-container">
                                <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-2 transition-all duration-200">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium shadow-md">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100 opacity-0 invisible transform scale-95 transition-all duration-200 origin-top-right user-dropdown-menu">
                                    <a href="{{ route('dashboard') }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                        </svg>
                                        {{ __('app.dashboard') }}
                                    </a>
                                    <form method="POST" action="{{ route('landing.logout') }}" class="block">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            {{ __('app.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Search Bar -->
            <div class="md:hidden pb-4">
                <form action="{{ route('landing.cars') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Search cars..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           value="{{ request('search') }}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Login Popup -->
    <div id="loginPopup" class="login-popup" style="display: none;">
        <div class="login-popup-content">
            <div class="login-popup-header">
                <h3>{{ __('app.sign_in') }}</h3>
                <button type="button" class="close-btn" onclick="hideLoginPopup()">&times;</button>
            </div>
            <form method="POST" action="{{ route('landing.login') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="login-email">{{ __('app.email') }}</label>
                    <input type="email" id="login-email" name="email" value="superadmin@rental.com" required>
                </div>
                <div class="form-group">
                    <label for="password">{{ __('app.password') }}</label>
                    <input type="password" id="password" name="password" value="password" required>
                </div>
                <button type="submit" class="login-btn">{{ __('app.sign_in') }}</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-800 text-white py-16 mt-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                                         <h5 class="text-xl font-bold text-primary-400 mb-4">ODYS</h5>
                     <p class="text-surface-300 leading-relaxed">Premium car rental service providing quality vehicles and exceptional customer experience.</p>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-white mb-4">{{ __('app.quick_links') }}</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ route('landing') }}" class="text-surface-300 hover:text-primary-400 transition-colors">{{ __('app.home') }}</a></li>
                        <li><a href="{{ route('landing.cars') }}" class="text-surface-300 hover:text-primary-400 transition-colors">{{ __('app.car_rental') }}</a></li>
                        <li><a href="#" class="text-surface-300 hover:text-primary-400 transition-colors">{{ __('app.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-white mb-4">{{ __('app.services') }}</h5>
                    <ul class="space-y-2">
                        <li class="text-surface-300">{{ __('app.car_rental') }}</li>
                        <li class="text-surface-300">{{ __('app.long_term_leasing') }}</li>
                        <li class="text-surface-300">{{ __('app.corporate_solutions') }}</li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-white mb-4">{{ __('app.contact_info') }}</h5>
                    <div class="space-y-2 text-surface-300">
                        <p><i class="fas fa-phone mr-2"></i>+1 234 567 890</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@alpino.com</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>123 Car Street, City</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-surface-700 mt-8 pt-8 text-center">
                                 <p class="text-surface-400">&copy; {{ date('Y') }} ODYS. {{ __('app.all_rights_reserved') }}</p>
            </div>
        </div>
    </footer>

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        // Login Popup Functions
        function showLoginPopup() {
            document.getElementById('loginPopup').style.display = 'flex';
        }

        function hideLoginPopup() {
            document.getElementById('loginPopup').style.display = 'none';
        }

        // User Dropdown Menu Functions
        function toggleUserMenu() {
            const menu = document.querySelector('.user-dropdown-menu');
            if (menu) {
                menu.classList.toggle('opacity-0');
                menu.classList.toggle('invisible');
                menu.classList.toggle('scale-95');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu-container');
            const dropdownMenu = document.querySelector('.user-dropdown-menu');
            
            if (userMenu && !userMenu.contains(event.target) && dropdownMenu) {
                dropdownMenu.classList.add('opacity-0', 'invisible', 'scale-95');
            }
        });

        // Close popup when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('loginPopup');
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    hideLoginPopup();
                }
            });

            // Display success/error messages
            @if(session('success'))
                showMessage('{{ session('success') }}', 'success');
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    showMessage('{{ $error }}', 'error');
                @endforeach
            @endif
        });

        function showMessage(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const messageDiv = document.createElement('div');
            messageDiv.className = `alert ${alertClass}`;
            messageDiv.textContent = message;
            
            // Insert message at the top of the main content
            const main = document.querySelector('main');
            main.insertBefore(messageDiv, main.firstChild);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
    
    <!-- Dynamic Validation JavaScript -->
    <script src="{{ asset('js/dynamic-validation.js') }}"></script>

    @stack('scripts')
</body>
</html>
