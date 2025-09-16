<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Default Locale: " . app()->getLocale() . PHP_EOL;
echo "Fallback Locale: " . config('app.fallback_locale') . PHP_EOL;
echo "Dashboard (FR): " . __('app.dashboard') . PHP_EOL;
echo "Charges (FR): " . __('app.charges') . PHP_EOL;
echo "Clients (FR): " . __('app.clients') . PHP_EOL;
