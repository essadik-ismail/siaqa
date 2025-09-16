# Multi-Language Support for Odys Rental Management

## Overview
This application now supports three languages:
- **English (en)** - Default language
- **French (fr)** - Primary language for French-speaking users
- **Arabic (ar)** - Full RTL support for Arabic-speaking users

## Features Implemented

### 1. Language Files
- **Location**: `lang/` directory
- **Files**: `en/app.php`, `fr/app.php`, `ar/app.php`
- **Content**: Comprehensive translations for all dashboard elements

### 2. Language Switcher Component
- **Location**: `resources/views/components/language-switcher.blade.php`
- **Features**:
  - Dropdown with flag icons
  - Current language indicator
  - RTL support for Arabic
  - Smooth transitions

### 3. RTL Support
- **HTML Direction**: Automatically set based on language
- **CSS**: Tailwind RTL plugin for Arabic
- **Components**: Language switcher adapts to RTL layout

### 4. Middleware Integration
- **SetLocale Middleware**: Automatically sets application locale
- **Session Storage**: Language preference stored in session
- **Fallback**: Defaults to French if no language is set

## Usage

### Switching Languages
Users can switch languages using the language switcher in the sidebar footer. The language preference is stored in the session and persists across page reloads.

### Adding New Translations
1. Add new keys to all three language files (`lang/en/app.php`, `lang/fr/app.php`, `lang/ar/app.php`)
2. Use the `__('app.key')` helper in Blade templates
3. Test all three languages to ensure consistency

### Translation Keys Structure
```php
// Navigation
'dashboard' => 'Dashboard',
'clients' => 'Clients',
'vehicles' => 'Vehicles',

// Actions
'create' => 'Create',
'edit' => 'Edit',
'delete' => 'Delete',

// Status
'active' => 'Active',
'inactive' => 'Inactive',
'available' => 'Available',
```

## Technical Implementation

### 1. Language Controller
- **File**: `app/Http/Controllers/LanguageController.php`
- **Method**: `switch($locale)`
- **Validation**: Only allows 'en', 'fr', 'ar'
- **Redirect**: Returns to previous page after language change

### 2. SetLocale Middleware
- **File**: `app/Http/Middleware/SetLocale.php`
- **Function**: Sets application locale from session
- **Default**: Falls back to 'fr' if no locale is set

### 3. Route Configuration
- **Route**: `GET /language/{locale}`
- **Name**: `language.switch`
- **Middleware**: None (public access)

## RTL Support Details

### Arabic Language Features
- **Text Direction**: Right-to-left (RTL)
- **Font Support**: Proper Arabic font rendering
- **Layout**: Mirrored layout for better UX
- **Icons**: Properly positioned for RTL

### CSS Classes for RTL
```css
/* RTL-specific classes */
.rtl {
    direction: rtl;
    text-align: right;
}

.rtl .space-x-2 {
    space-x-reverse: 1;
}
```

## Testing

### Manual Testing
1. Start the application: `php artisan serve`
2. Navigate to the dashboard
3. Use the language switcher to change languages
4. Verify all text elements are translated
5. Test RTL layout with Arabic

### Automated Testing
```bash
# Test translations via command line
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo __('app.dashboard', [], 'en');"
```

## File Structure
```
lang/
├── en/
│   └── app.php          # English translations
├── fr/
│   └── app.php          # French translations
└── ar/
    └── app.php          # Arabic translations

resources/views/
├── components/
│   └── language-switcher.blade.php
└── layouts/
    └── app.blade.php    # Updated with RTL support

app/Http/
├── Controllers/
│   └── LanguageController.php
└── Middleware/
    └── SetLocale.php
```

## Future Enhancements

### Planned Features
1. **Database Translations**: Store translations in database for easier management
2. **Admin Translation Panel**: Allow admins to edit translations via UI
3. **Auto-Detection**: Detect user language from browser settings
4. **More Languages**: Add Spanish, German, Italian support
5. **Pluralization**: Proper plural forms for different languages

### Adding New Languages
1. Create new language directory: `lang/[code]/`
2. Add language file: `lang/[code]/app.php`
3. Update LanguageController to include new language
4. Add language option to language switcher
5. Test RTL support if needed

## Troubleshooting

### Common Issues
1. **Translations not showing**: Check if language files exist and are properly formatted
2. **RTL not working**: Ensure Tailwind RTL plugin is loaded
3. **Language not persisting**: Check if SetLocale middleware is registered
4. **Missing translations**: Add missing keys to all language files

### Debug Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check current locale
php artisan tinker
>>> app()->getLocale()
```

## Contributing

When adding new features:
1. Always add translation keys to all three language files
2. Test in all three languages
3. Ensure RTL support for Arabic
4. Update this documentation if needed

## Support

For issues or questions about multi-language functionality:
1. Check the troubleshooting section
2. Verify all files are properly configured
3. Test with a fresh installation
4. Contact the development team
