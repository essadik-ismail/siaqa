/**
 * Global Frontend Filtering System
 * Provides client-side filtering for all tables across the application
 */

// Global filter management
window.FrontendFilters = {
    // Configuration for different pages
    configs: {
        vehicles: {
            tableId: 'vehiclesTable',
            filters: {
                search: { selector: '#searchInput', fields: ['name', 'registration', 'color'] },
                brand: { selector: '#brandFilter', fields: ['brand'] },
                status: { selector: '#statusFilter', fields: ['status'] },
                landing: { selector: '#landingFilter', fields: ['landing'] }
            },
            fieldMappings: {
                name: 'td:nth-child(1) .text-base',
                registration: 'td:nth-child(1) .text-sm',
                color: 'td:nth-child(2) .text-sm',
                brand: 'td:nth-child(2) .text-base',
                status: 'td:nth-child(4) .inline-flex',
                landing: 'td:nth-child(1) .bg-green-100'
            },
            statusMap: {
                'disponible': 'available',
                'en_location': 'on rental',
                'en_maintenance': 'in maintenance',
                'hors_service': 'out of service'
            }
        },
        clients: {
            tableId: 'clientsTable',
            filters: {
                search: { selector: '#searchInput', fields: ['name', 'email', 'phone'] },
                status: { selector: '#statusFilter', fields: ['status'] },
                sort: { selector: '#sortFilter', fields: [] }
            },
            fieldMappings: {
                name: 'td:nth-child(2) .text-base',
                email: 'td:nth-child(3) .text-sm',
                phone: 'td:nth-child(4) .text-sm',
                status: 'td:nth-child(5) .inline-flex'
            }
        },
        reservations: {
            tableId: 'reservationsTable',
            filters: {
                search: { selector: '#searchInput', fields: ['client', 'vehicle'] },
                status: { selector: '#statutFilter', fields: ['status'] },
                client: { selector: '#clientFilter', fields: ['client'] },
                vehicle: { selector: '#vehiculeFilter', fields: ['vehicle'] },
                dateStart: { selector: '#dateDebutFilter', fields: ['dateStart'] },
                dateEnd: { selector: '#dateFinFilter', fields: ['dateEnd'] }
            },
            fieldMappings: {
                client: 'td:nth-child(2) .text-base',
                vehicle: 'td:nth-child(3) .text-base',
                dateStart: 'td:nth-child(4) .text-sm',
                dateEnd: 'td:nth-child(5) .text-sm',
                status: 'td:nth-child(6) .inline-flex'
            }
        },
        contracts: {
            tableId: 'contractsTable',
            filters: {
                search: { selector: '#searchInput', fields: ['client', 'vehicle'] },
                status: { selector: '#etatFilter', fields: ['status'] },
                client: { selector: '#clientFilter', fields: ['client'] },
                vehicle: { selector: '#vehiculeFilter', fields: ['vehicle'] },
                dateStart: { selector: '#dateDebutFilter', fields: ['dateStart'] },
                dateEnd: { selector: '#dateFinFilter', fields: ['dateEnd'] }
            },
            fieldMappings: {
                client: 'td:nth-child(2) .text-base',
                vehicle: 'td:nth-child(3) .text-base',
                dateStart: 'td:nth-child(4) .text-sm',
                dateEnd: 'td:nth-child(5) .text-sm',
                status: 'td:nth-child(6) .inline-flex'
            }
        }
    },

    // Current page configuration
    currentConfig: null,

    // Initialize filtering for a specific page
    init: function(pageType) {
        this.currentConfig = this.configs[pageType];
        if (!this.currentConfig) {
            console.warn(`No configuration found for page type: ${pageType}`);
            return;
        }

        this.setupEventListeners();
        this.initializeFilters();
    },

    // Setup event listeners for all filters
    setupEventListeners: function() {
        const config = this.currentConfig;
        
        Object.keys(config.filters).forEach(filterKey => {
            const filter = config.filters[filterKey];
            const element = document.querySelector(filter.selector);
            
            if (element) {
                if (element.tagName === 'INPUT') {
                    element.addEventListener('input', () => this.filter());
                } else if (element.tagName === 'SELECT') {
                    element.addEventListener('change', () => this.filter());
                }
            }
        });
    },

    // Initialize filters from URL parameters
    initializeFilters: function() {
        const urlParams = new URLSearchParams(window.location.search);
        const config = this.currentConfig;
        
        Object.keys(config.filters).forEach(filterKey => {
            const filter = config.filters[filterKey];
            const element = document.querySelector(filter.selector);
            const urlValue = urlParams.get(filterKey);
            
            if (element && urlValue) {
                element.value = urlValue;
            }
        });
        
        // Apply initial filtering
        this.filter();
    },

    // Main filtering function
    filter: function() {
        const config = this.currentConfig;
        const table = document.getElementById(config.tableId);
        
        if (!table) {
            console.warn(`Table with ID ${config.tableId} not found`);
            return;
        }

        const rows = table.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            try {
                let showRow = true;

                // Apply each filter
                Object.keys(config.filters).forEach(filterKey => {
                    const filter = config.filters[filterKey];
                    const element = document.querySelector(filter.selector);
                    
                    if (!element || !element.value) return;

                    const filterValue = element.value.toLowerCase();
                    
                    // Check each field for this filter
                    filter.fields.forEach(fieldName => {
                        if (!showRow) return; // Skip if already filtered out
                        
                        const fieldSelector = config.fieldMappings[fieldName];
                        if (!fieldSelector) return;
                        
                        const fieldElement = row.querySelector(fieldSelector);
                        if (!fieldElement) return;
                        
                        let fieldValue = fieldElement.textContent.toLowerCase();
                        
                        // Special handling for status fields
                        if (fieldName === 'status' && config.statusMap) {
                            const statusMap = config.statusMap;
                            const mappedStatus = Object.keys(statusMap).find(key => 
                                statusMap[key] === fieldValue
                            );
                            if (mappedStatus) {
                                fieldValue = mappedStatus;
                            }
                        }
                        
                        // Special handling for landing display
                        if (fieldName === 'landing') {
                            const hasLandingBadge = fieldElement !== null;
                            if (filterValue === '1' && !hasLandingBadge) {
                                showRow = false;
                            } else if (filterValue === '0' && hasLandingBadge) {
                                showRow = false;
                            }
                            return;
                        }
                        
                        // Regular text matching
                        if (!fieldValue.includes(filterValue)) {
                            showRow = false;
                        }
                    });
                });

                // Show/hide row
                if (showRow) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            } catch (error) {
                console.error('Error filtering row:', error);
                row.style.display = '';
                visibleCount++;
            }
        });

        // Update results count
        this.updateResultsCount(visibleCount);
        
        // Update clear buttons
        this.updateClearButtons();
    },

    // Update results count display
    updateResultsCount: function(count) {
        const resultsElements = document.querySelectorAll('.text-gray-600, .results-count');
        resultsElements.forEach(element => {
            if (element.textContent.includes('found') || element.textContent.includes('results')) {
                element.textContent = `${count} results found`;
            }
        });
    },

    // Update clear button visibility
    updateClearButtons: function() {
        const config = this.currentConfig;
        
        Object.keys(config.filters).forEach(filterKey => {
            const filter = config.filters[filterKey];
            const element = document.querySelector(filter.selector);
            const clearButton = document.querySelector(`#clear${filterKey.charAt(0).toUpperCase() + filterKey.slice(1)}`);
            
            if (element && clearButton) {
                if (element.value) {
                    clearButton.classList.remove('hidden');
                } else {
                    clearButton.classList.add('hidden');
                }
            }
        });
    },

    // Clear a specific filter
    clearFilter: function(filterKey) {
        const config = this.currentConfig;
        const filter = config.filters[filterKey];
        
        if (filter) {
            const element = document.querySelector(filter.selector);
            if (element) {
                element.value = '';
                this.filter();
            }
        }
    },

    // Clear all filters
    clearAllFilters: function() {
        const config = this.currentConfig;
        
        Object.keys(config.filters).forEach(filterKey => {
            const filter = config.filters[filterKey];
            const element = document.querySelector(filter.selector);
            
            if (element) {
                element.value = '';
            }
        });
        
        this.filter();
    }
};

// Global functions for backward compatibility
window.clearFilter = function(type) {
    if (window.FrontendFilters.currentConfig) {
        window.FrontendFilters.clearFilter(type);
    }
};

window.clearAllFilters = function() {
    if (window.FrontendFilters.currentConfig) {
        window.FrontendFilters.clearAllFilters();
    }
};

// Auto-initialize based on page
document.addEventListener('DOMContentLoaded', function() {
    const path = window.location.pathname;
    
    if (path.includes('/vehicules')) {
        window.FrontendFilters.init('vehicles');
    } else if (path.includes('/clients')) {
        window.FrontendFilters.init('clients');
    } else if (path.includes('/reservations')) {
        window.FrontendFilters.init('reservations');
    } else if (path.includes('/contrats')) {
        window.FrontendFilters.init('contracts');
    }
});

