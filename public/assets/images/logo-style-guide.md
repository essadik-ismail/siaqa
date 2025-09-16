# Odys Rental Management - Logo Style Guide

## Logo Variations

### 1. Full Logo (odys-logo-modern.svg)
- **Usage**: Main application header, landing pages
- **Dimensions**: 180x50px
- **Features**: Complete branding with icon and text
- **Best for**: Desktop navigation, marketing materials

### 2. Compact Logo (odys-logo-compact.svg)
- **Usage**: Mobile navigation, small spaces, login/register pages
- **Dimensions**: 120x40px
- **Features**: Condensed version with essential elements
- **Best for**: Mobile devices, tight spaces

### 3. Icon Only (favicon.svg)
- **Usage**: Browser favicon, app icons, social media
- **Dimensions**: 32x32px
- **Features**: Just the car icon in a circle
- **Best for**: Favicons, app icons, social media profiles

## Design Elements

### Color Palette
- **Primary Blue**: #3B82F6 (Blue-500)
- **Secondary Purple**: #8B5CF6 (Purple-500)
- **Text Dark**: #1E293B (Slate-800)
- **Text Light**: #64748B (Slate-500)

### Typography
- **Font Family**: 'Segoe UI', Arial, sans-serif
- **Main Text**: Bold, 20px, letter-spacing 2px
- **Subtitle**: Medium, 9px, letter-spacing 1px

### Icon Design
- **Style**: Modern, minimalist car icon
- **Shape**: Circular background with gradient
- **Elements**: Car body, roof, and wheels
- **Colors**: White icon on gradient background

## Usage Guidelines

### Do's
- ✅ Use SVG format for scalability
- ✅ Maintain aspect ratio when resizing
- ✅ Use appropriate version for space constraints
- ✅ Ensure sufficient contrast on backgrounds
- ✅ Keep clear space around the logo

### Don'ts
- ❌ Don't distort or stretch the logo
- ❌ Don't change colors without approval
- ❌ Don't place on busy backgrounds
- ❌ Don't use low-resolution versions
- ❌ Don't modify the icon design

## Implementation

### HTML Usage
```html
<!-- Full logo for headers -->
<img src="{{ asset('assets/images/odys-logo-modern.svg') }}" alt="Odys Rental Management" class="h-10 w-auto">

<!-- Compact logo for mobile -->
<img src="{{ asset('assets/images/odys-logo-compact.svg') }}" alt="Odys Rental Management" class="h-8 w-auto">

<!-- Icon only for favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
```

### CSS Classes
```css
.logo-full { height: 3rem; width: auto; }
.logo-compact { height: 2rem; width: auto; }
.logo-icon { height: 2rem; width: 2rem; }
```

## File Locations
- `/public/assets/images/odys-logo-modern.svg` - Full logo
- `/public/assets/images/odys-logo-compact.svg` - Compact logo
- `/public/favicon.svg` - Icon only
- `/public/assets/images/logo-style-guide.md` - This guide

## Last Updated
September 16, 2025
