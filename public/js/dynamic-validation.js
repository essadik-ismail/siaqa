/**
 * Dynamic Form Validation System
 * Provides real-time validation for all forms across the application
 */

class DynamicValidator {
    constructor() {
        this.validationRules = {
            // Text fields
            'nom': {
                required: true,
                minLength: 2,
                maxLength: 255,
                message: 'Name must be between 2 and 255 characters'
            },
            'prenom': {
                required: true,
                minLength: 2,
                maxLength: 255,
                message: 'First name must be between 2 and 255 characters'
            },
            'name': {
                required: true,
                minLength: 2,
                maxLength: 255,
                message: 'Name must be between 2 and 255 characters'
            },
            'company_name': {
                required: true,
                minLength: 2,
                maxLength: 255,
                message: 'Company name must be between 2 and 255 characters'
            },
            'marque': {
                required: true,
                minLength: 2,
                maxLength: 100,
                message: 'Brand name must be between 2 and 100 characters'
            },
            'nom_agence': {
                required: true,
                minLength: 2,
                maxLength: 255,
                message: 'Agency name must be between 2 and 255 characters'
            },
            
            // Email fields
            'email': {
                required: true,
                type: 'email',
                message: 'Please enter a valid email address'
            },
            'contact_email': {
                required: true,
                type: 'email',
                message: 'Please enter a valid contact email address'
            },
            
            // Phone fields
            'telephone': {
                required: true,
                type: 'phone',
                message: 'Please enter a valid phone number'
            },
            'contact_phone': {
                required: true,
                type: 'phone',
                message: 'Please enter a valid contact phone number'
            },
            
            // Address fields
            'adresse': {
                required: true,
                minLength: 10,
                maxLength: 500,
                message: 'Address must be between 10 and 500 characters'
            },
            'address': {
                required: true,
                minLength: 10,
                maxLength: 500,
                message: 'Address must be between 10 and 500 characters'
            },
            'ville': {
                required: true,
                minLength: 2,
                maxLength: 100,
                message: 'City must be between 2 and 100 characters'
            },
            'code_postal': {
                required: true,
                minLength: 3,
                maxLength: 10,
                message: 'Postal code must be between 3 and 10 characters'
            },
            'pays': {
                required: true,
                minLength: 2,
                maxLength: 100,
                message: 'Country must be between 2 and 100 characters'
            },
            
            // Vehicle fields
            'immatriculation': {
                required: true,
                minLength: 3,
                maxLength: 20,
                message: 'Registration number must be between 3 and 20 characters'
            },
            'couleur': {
                required: false,
                maxLength: 50,
                message: 'Color must not exceed 50 characters'
            },
            'prix_location_jour': {
                required: true,
                type: 'number',
                min: 0,
                message: 'Daily rental price must be a positive number'
            },
            'prix_achat': {
                required: false,
                type: 'number',
                min: 0,
                message: 'Purchase price must be a positive number'
            },
            'caution': {
                required: false,
                type: 'number',
                min: 0,
                message: 'Deposit must be a positive number'
            },
            'kilometrage_actuel': {
                required: false,
                type: 'number',
                min: 0,
                message: 'Current mileage must be a positive number'
            },
            'nbr_place': {
                required: false,
                type: 'number',
                min: 1,
                max: 20,
                message: 'Number of seats must be between 1 and 20'
            },
            'nombre_cylindre': {
                required: false,
                type: 'number',
                min: 0,
                max: 16,
                message: 'Number of cylinders must be between 0 and 16'
            },
            
            // Date fields
            'date_debut': {
                required: true,
                type: 'date',
                minDate: 'today',
                message: 'Start date cannot be in the past'
            },
            'date_fin': {
                required: true,
                type: 'date',
                minDate: 'date_debut',
                message: 'End date must be after start date'
            },
            'date_naissance': {
                required: true,
                type: 'date',
                maxDate: 'today',
                message: 'Birth date cannot be in the future'
            },
            'date_obtention_permis': {
                required: true,
                type: 'date',
                maxDate: 'today',
                message: 'License date cannot be in the future'
            },
            
            // License fields
            'numero_permis': {
                required: true,
                minLength: 5,
                maxLength: 50,
                message: 'License number must be between 5 and 50 characters'
            },
            'numero_piece_identite': {
                required: true,
                minLength: 5,
                maxLength: 50,
                message: 'ID number must be between 5 and 50 characters'
            },
            
            // Domain fields
            'domain': {
                required: true,
                type: 'domain',
                message: 'Please enter a valid domain name'
            },
            
            // Password fields
            'password': {
                required: true,
                minLength: 8,
                message: 'Password must be at least 8 characters long'
            },
            'password_confirmation': {
                required: true,
                match: 'password',
                message: 'Password confirmation must match password'
            }
        };
        
        this.init();
    }
    
    init() {
        // Initialize validation for all forms on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeAllForms();
        });
    }
    
    initializeAllForms() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            this.initializeForm(form);
        });
    }
    
    initializeForm(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Skip hidden inputs and submit buttons
            if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                return;
            }
            
            // Add validation event listeners
            this.addValidationListeners(input);
        });
    }
    
    addValidationListeners(input) {
        const fieldName = input.name || input.id;
        if (!fieldName) return;
        
        // Validate on input (with debounce)
        input.addEventListener('input', this.debounce((e) => {
            this.validateField(fieldName, e.target.value, e.target);
        }, 500));
        
        // Validate on blur
        input.addEventListener('blur', (e) => {
            this.validateField(fieldName, e.target.value, e.target);
        });
        
        // Validate on change (for selects and date inputs)
        input.addEventListener('change', (e) => {
            this.validateField(fieldName, e.target.value, e.target);
        });
    }
    
    validateField(fieldName, value, inputElement) {
        const rules = this.validationRules[fieldName];
        if (!rules) return true;
        
        // Clear previous errors
        this.clearFieldError(fieldName, inputElement);
        
        // Check required
        if (rules.required && (!value || value.trim() === '')) {
            this.showFieldError(fieldName, `${this.getFieldLabel(fieldName)} is required`, inputElement);
            return false;
        }
        
        // Skip other validations if field is empty and not required
        if (!value || value.trim() === '') {
            return true;
        }
        
        // Validate based on type
        switch (rules.type) {
            case 'email':
                if (!this.isValidEmail(value)) {
                    this.showFieldError(fieldName, rules.message, inputElement);
                    return false;
                }
                break;
                
            case 'phone':
                if (!this.isValidPhone(value)) {
                    this.showFieldError(fieldName, rules.message, inputElement);
                    return false;
                }
                break;
                
            case 'number':
                if (!this.isValidNumber(value, rules.min, rules.max)) {
                    this.showFieldError(fieldName, rules.message, inputElement);
                    return false;
                }
                break;
                
            case 'date':
                if (!this.isValidDate(value, rules.minDate, rules.maxDate, fieldName)) {
                    this.showFieldError(fieldName, rules.message, inputElement);
                    return false;
                }
                break;
                
            case 'domain':
                if (!this.isValidDomain(value)) {
                    this.showFieldError(fieldName, rules.message, inputElement);
                    return false;
                }
                break;
        }
        
        // Validate length
        if (rules.minLength && value.length < rules.minLength) {
            this.showFieldError(fieldName, `${this.getFieldLabel(fieldName)} must be at least ${rules.minLength} characters`, inputElement);
            return false;
        }
        
        if (rules.maxLength && value.length > rules.maxLength) {
            this.showFieldError(fieldName, `${this.getFieldLabel(fieldName)} must not exceed ${rules.maxLength} characters`, inputElement);
            return false;
        }
        
        // Validate number ranges
        if (rules.type === 'number') {
            const numValue = parseFloat(value);
            if (rules.min !== undefined && numValue < rules.min) {
                this.showFieldError(fieldName, `${this.getFieldLabel(fieldName)} must be at least ${rules.min}`, inputElement);
                return false;
            }
            if (rules.max !== undefined && numValue > rules.max) {
                this.showFieldError(fieldName, `${this.getFieldLabel(fieldName)} must not exceed ${rules.max}`, inputElement);
                return false;
            }
        }
        
        // Validate password confirmation
        if (rules.match) {
            const matchField = document.querySelector(`[name="${rules.match}"]`);
            if (matchField && value !== matchField.value) {
                this.showFieldError(fieldName, rules.message, inputElement);
                return false;
            }
        }
        
        return true;
    }
    
    showFieldError(fieldName, message, inputElement) {
        // Add error class to input
        if (inputElement) {
            inputElement.classList.add('error');
        }
        
        // Create or update error message
        let errorElement = document.getElementById(`${fieldName}-error`);
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = `${fieldName}-error`;
            errorElement.className = 'error-message text-red-500 text-sm mt-1';
            
            // Insert after the input element
            if (inputElement) {
                inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
            }
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    clearFieldError(fieldName, inputElement) {
        // Remove error class from input
        if (inputElement) {
            inputElement.classList.remove('error');
        }
        
        // Hide error message
        const errorElement = document.getElementById(`${fieldName}-error`);
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }
    }
    
    // Validation helper methods
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }
    
    isValidNumber(value, min, max) {
        const num = parseFloat(value);
        if (isNaN(num)) return false;
        if (min !== undefined && num < min) return false;
        if (max !== undefined && num > max) return false;
        return true;
    }
    
    isValidDate(value, minDate, maxDate, fieldName) {
        const date = new Date(value + 'T00:00:00');
        if (isNaN(date.getTime())) return false;
        
        if (minDate === 'today') {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (date < today) return false;
        } else if (minDate === 'date_debut' && fieldName === 'date_fin') {
            const startDateField = document.querySelector('[name="date_debut"]');
            if (startDateField && startDateField.value) {
                const startDate = new Date(startDateField.value + 'T00:00:00');
                if (date <= startDate) return false;
            }
        }
        
        if (maxDate === 'today') {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (date > today) return false;
        }
        
        return true;
    }
    
    isValidDomain(domain) {
        const domainRegex = /^[a-zA-Z0-9][a-zA-Z0-9-]{0,61}[a-zA-Z0-9]?(\.[a-zA-Z0-9][a-zA-Z0-9-]{0,61}[a-zA-Z0-9]?)*$/;
        return domainRegex.test(domain);
    }
    
    getFieldLabel(fieldName) {
        const labels = {
            'nom': 'Name',
            'prenom': 'First Name',
            'name': 'Name',
            'company_name': 'Company Name',
            'marque': 'Brand',
            'nom_agence': 'Agency Name',
            'email': 'Email',
            'contact_email': 'Contact Email',
            'telephone': 'Phone',
            'contact_phone': 'Contact Phone',
            'adresse': 'Address',
            'address': 'Address',
            'ville': 'City',
            'code_postal': 'Postal Code',
            'pays': 'Country',
            'immatriculation': 'Registration Number',
            'couleur': 'Color',
            'prix_location_jour': 'Daily Rental Price',
            'prix_achat': 'Purchase Price',
            'caution': 'Deposit',
            'kilometrage_actuel': 'Current Mileage',
            'nbr_place': 'Number of Seats',
            'nombre_cylindre': 'Number of Cylinders',
            'date_debut': 'Start Date',
            'date_fin': 'End Date',
            'date_naissance': 'Birth Date',
            'date_obtention_permis': 'License Date',
            'numero_permis': 'License Number',
            'numero_piece_identite': 'ID Number',
            'domain': 'Domain',
            'password': 'Password',
            'password_confirmation': 'Password Confirmation'
        };
        
        return labels[fieldName] || fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
    
    // Utility method for debouncing
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Method to validate entire form
    validateForm(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let isValid = true;
        
        inputs.forEach(input => {
            if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                return;
            }
            
            const fieldName = input.name || input.id;
            if (fieldName && !this.validateField(fieldName, input.value, input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
}

// Initialize the validator when the script loads
const dynamicValidator = new DynamicValidator();

// Export for use in other scripts
window.DynamicValidator = DynamicValidator;
window.dynamicValidator = dynamicValidator;


