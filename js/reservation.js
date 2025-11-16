// reservation.js - COMPLETELY REWRITTEN AND FIXED
document.addEventListener('DOMContentLoaded', function() {
    console.log('Reservation system loaded');
    
    let map = null;
    let marker = null;
    let currentStep = 1;
    const totalSteps = 5;
    let selectedPackage = null;
    let selectedPayment = null;

    // Initialize everything
    initEverything();

    function initEverything() {
        console.log('Initializing reservation system...');
        
        initSidebarSteps();
        initPackageSelection();
        initAddressSearch();
        initPaymentOptions();
        initTimeFields();
        
        // Initialize map
        initMap();
        
        updateProgress();
        updateNavigation();
    }

    // MAP INITIALIZATION - COMPLETELY REWRITTEN
    function initMap() {
        console.log('Initializing map...');
        const mapContainer = document.getElementById('map');
        
        if (!mapContainer) {
            console.error('Map container not found!');
            return;
        }

        try {
            // Clear loading message
            const loadingEl = mapContainer.querySelector('.map-loading');
            if (loadingEl) {
                loadingEl.style.display = 'none';
            }

            // Create map with Pampanga center
            const pampangaCenter = [15.0794, 120.6200];
            map = L.map('map').setView(pampangaCenter, 11);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18,
                minZoom: 10
            }).addTo(map);
            
            // Define Pampanga bounds (approximate)
            const pampangaBounds = L.latLngBounds(
                [14.9, 120.4],  // Southwest corner
                [15.3, 120.8]   // Northeast corner
            );
            
            // Set map bounds to Pampanga
            map.setMaxBounds(pampangaBounds);
            map.setMinZoom(10);
            
            // Add bounds rectangle for visualization (optional)
            L.rectangle(pampangaBounds, {
                color: "#0066ff",
                weight: 2,
                fillOpacity: 0.1
            }).addTo(map);
            
            // Add draggable marker
            marker = L.marker(pampangaCenter, {
                draggable: true,
                autoPan: true
            }).addTo(map);
            
            // Set initial coordinates and get address
            document.getElementById('event_location').value = `${pampangaCenter[0]},${pampangaCenter[1]}`;
            getAddressFromCoordinates(pampangaCenter[0], pampangaCenter[1]);
            
            // MAP CLICK - Set location and get address
            map.on('click', function(e) {
                console.log('Map clicked at:', e.latlng);
                marker.setLatLng(e.latlng);
                getAddressFromCoordinates(e.latlng.lat, e.latlng.lng);
            });
            
            // MARKER DRAG - Update location when marker is moved
            marker.on('dragend', function(e) {
                const coords = e.target.getLatLng();
                console.log('Marker dragged to:', coords);
                getAddressFromCoordinates(coords.lat, coords.lng);
            });
            
            console.log('Map initialized successfully!');
            
        } catch (error) {
            console.error('Map initialization failed:', error);
            showMapError();
        }
    }

    // Get address from coordinates - SIMPLIFIED AND FIXED
    function getAddressFromCoordinates(lat, lng) {
        console.log('Getting address for:', lat, lng);
        
        // Update coordinates immediately
        document.getElementById('event_location').value = `${lat},${lng}`;
        
        // Show loading in address field
        const addressInput = document.getElementById('event_address');
        const addressTextarea = document.querySelector('textarea[name="event_address"]');
        addressInput.value = 'Getting address...';
        addressTextarea.value = 'Getting address...';
        
        // Use Nominatim for reverse geocoding
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=16`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data && data.display_name) {
                    console.log('Address found:', data.display_name);
                    addressInput.value = data.display_name;
                    addressTextarea.value = data.display_name;
                } else {
                    throw new Error('No address found');
                }
            })
            .catch(error => {
                console.warn('Reverse geocoding failed:', error);
                // Set coordinates as address
                const coordinateAddress = `Location at ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                addressInput.value = coordinateAddress;
                addressTextarea.value = coordinateAddress;
            });
    }

    // Show map error
    function showMapError() {
        const mapContainer = document.getElementById('map');
        mapContainer.innerHTML = `
            <div class="map-error">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h4>Map Not Available</h4>
                <p>You can still book by entering your address manually below.</p>
                <button class="btn btn-primary mt-3" onclick="location.reload()">
                    <i class="bi bi-arrow-repeat"></i> Reload Page
                </button>
            </div>
        `;
    }

    // ADDRESS SEARCH - SIMPLIFIED AND FIXED
    function initAddressSearch() {
        const addressInput = document.getElementById('event_address');
        const resultsContainer = document.querySelector('.autocomplete-results');
        const searchButton = document.getElementById('search-address-btn');
        
        if (!addressInput || !resultsContainer) {
            console.error('Address search elements not found');
            return;
        }

        let searchTimeout;

        // Input event for real-time search
        addressInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 3) {
                resultsContainer.style.display = 'none';
                return;
            }

            // Show loading
            resultsContainer.innerHTML = '<div class="autocomplete-item">Searching locations...</div>';
            resultsContainer.style.display = 'block';

            searchTimeout = setTimeout(() => {
                searchAddressOnline(query);
            }, 500);
        });

        // Search button click
        if (searchButton) {
            searchButton.addEventListener('click', function() {
                const query = addressInput.value.trim();
                if (query.length >= 3) {
                    searchAddressOnline(query);
                }
            });
        }

        // Handle Enter key
        addressInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length >= 3) {
                    searchAddressOnline(query);
                }
            }
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                resultsContainer.style.display = 'none';
            }
        });
    }

    // SEARCH FUNCTION - SIMPLIFIED
    function searchAddressOnline(query) {
        const resultsContainer = document.querySelector('.autocomplete-results');
        
        if (!query || query.length < 3) {
            resultsContainer.innerHTML = '<div class="autocomplete-item">Please enter at least 3 characters</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        // Show loading
        resultsContainer.innerHTML = '<div class="autocomplete-item">Searching locations...</div>';
        resultsContainer.style.display = 'block';

        // Search URL - Add Pampanga to query to bias results
        const searchUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Pampanga')}&limit=8`;
        
        console.log('Searching for:', query);
        
        fetch(searchUrl)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                console.log('Found results:', data);
                if (data && data.length > 0) {
                    displaySearchResults(data);
                } else {
                    showNoResults();
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                showSearchError();
            });
    }

    // Display search results
    function displaySearchResults(results) {
        const resultsContainer = document.querySelector('.autocomplete-results');
        const addressInput = document.getElementById('event_address');
        
        resultsContainer.innerHTML = '';

        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.innerHTML = `
                <div class="location-name">${result.display_name.split(',')[0]}</div>
                <div class="location-address">${result.display_name}</div>
            `;
            
            item.addEventListener('click', function() {
                console.log('Location selected:', result);
                
                // Update address fields
                addressInput.value = result.display_name;
                document.querySelector('textarea[name="event_address"]').value = result.display_name;
                
                // Update map and coordinates
                if (map && marker) {
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    map.setView([lat, lon], 16);
                    marker.setLatLng([lat, lon]);
                    document.getElementById('event_location').value = `${lat},${lon}`;
                }
                
                // Hide results
                resultsContainer.style.display = 'none';
            });
            
            resultsContainer.appendChild(item);
        });

        resultsContainer.style.display = 'block';
    }

    // Show no results message
    function showNoResults() {
        const resultsContainer = document.querySelector('.autocomplete-results');
        resultsContainer.innerHTML = `
            <div class="autocomplete-item text-center text-muted">
                <i class="bi bi-search me-2"></i>
                <div>No locations found</div>
                <small>Try different search terms</small>
            </div>
        `;
    }

    // Show search error
    function showSearchError() {
        const resultsContainer = document.querySelector('.autocomplete-results');
        resultsContainer.innerHTML = `
            <div class="autocomplete-item text-center text-muted">
                <i class="bi bi-wifi-off me-2"></i>
                <div>Search unavailable</div>
                <small>Try again later</small>
            </div>
        `;
    }

    // PACKAGE SELECTION - FIXED
    function initPackageSelection() {
        document.querySelectorAll('.package-card').forEach(card => {
            const expandBtn = card.querySelector('.expand-toggle');
            
            // Select package on card click
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.expand-toggle')) {
                    const packageKey = card.dataset.package;
                    selectPackage(packageKey);
                }
            });
            
            // Expand/collapse details
            if (expandBtn) {
                expandBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    togglePackageDetails(card);
                });
            }
        });
    }

    function togglePackageDetails(card) {
        const isExpanded = card.classList.contains('expanded');
        const icon = card.querySelector('.expand-toggle i');
        const text = card.querySelector('.expand-toggle span');
        
        // Close all other expanded packages
        document.querySelectorAll('.package-card.expanded').forEach(otherCard => {
            if (otherCard !== card) {
                otherCard.classList.remove('expanded');
                const otherIcon = otherCard.querySelector('.expand-toggle i');
                const otherText = otherCard.querySelector('.expand-toggle span');
                otherIcon.className = 'bi bi-chevron-down';
                otherText.textContent = 'Show Package Details';
            }
        });
        
        // Toggle current package
        if (isExpanded) {
            card.classList.remove('expanded');
            icon.className = 'bi bi-chevron-down';
            text.textContent = 'Show Package Details';
        } else {
            card.classList.add('expanded');
            icon.className = 'bi bi-chevron-up';
            text.textContent = 'Hide Package Details';
        }
    }

    function selectPackage(packageKey) {
        selectedPackage = packageKey;
        document.getElementById('selected_package').value = packageKey;
        
        // Update UI - AUTO EXPAND SELECTED PACKAGE
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected', 'expanded');
        });
        
        const selectedCard = document.querySelector(`[data-package="${packageKey}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected', 'expanded');
            
            // Update toggle button to show "hide"
            const icon = selectedCard.querySelector('.expand-toggle i');
            const text = selectedCard.querySelector('.expand-toggle span');
            icon.className = 'bi bi-chevron-up';
            text.textContent = 'Hide Package Details';
        }
        
        updateReviewSection();
        updateNavigation();
        
        // ENABLE THE NEXT BUTTON IMMEDIATELY
        const nextButton = document.querySelector('#step1 .btn-reservation');
        if (nextButton) {
            nextButton.disabled = false;
        }
    }

    // PAYMENT OPTIONS - FIXED
    function initPaymentOptions() {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const paymentMethod = this.dataset.payment;
                selectPayment(paymentMethod);
            });
        });
    }

    function selectPayment(paymentMethod) {
        selectedPayment = paymentMethod;
        document.getElementById('selected_payment').value = paymentMethod;
        
        // Update UI
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        const selectedOption = document.querySelector(`[data-payment="${paymentMethod}"]`);
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }
        
        // Show/hide downpayment info
        const downpaymentInfo = document.getElementById('downpaymentInfo');
        if (paymentMethod === 'gcash') {
            downpaymentInfo.style.display = 'block';
            calculateDownpayment();
        } else {
            downpaymentInfo.style.display = 'none';
        }
        
        updateReviewSection();
        updateNavigation();
    }

    function calculateDownpayment() {
        if (!selectedPackage) return;
        
        const packagePrices = {
            'basic_setup': 5000,
            'upgraded_setup_6000': 6000,
            'upgraded_setup_7000': 7000,
            'mid_setup': 10000
        };
        
        const total = packagePrices[selectedPackage] || 0;
        const downpayment = total * 0.20; // 20% downpayment
        const remaining = total - downpayment;
        
        document.getElementById('downpaymentAmount').textContent = `₱${downpayment.toFixed(2)}`;
        document.getElementById('remainingAmount').textContent = `₱${remaining.toFixed(2)}`;
    }

    // TIME FIELDS
    function initTimeFields() {
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        
        // Set default times
        startTime.value = '08:00';
        endTime.value = '17:00';
        
        // Validate on change
        [startTime, endTime].forEach(field => {
            field.addEventListener('change', validateTimeRange);
        });
    }

    function validateTimeRange() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const errorElement = document.getElementById('time_error');
        
        if (!startTime || !endTime) {
            errorElement.classList.remove('show');
            return true;
        }
        
        if (endTime <= startTime) {
            errorElement.textContent = 'End time must be after start time';
            errorElement.classList.add('show');
            return false;
        }
        
        errorElement.classList.remove('show');
        return true;
    }

    // STEP NAVIGATION - FIXED
    function initSidebarSteps() {
        document.querySelectorAll('.step-item').forEach((step, index) => {
            step.addEventListener('click', function() {
                const stepNumber = index + 1;
                if (stepNumber <= currentStep) {
                    goToStep(stepNumber);
                }
            });
        });
    }

    window.nextStep = function(current) {
        if (validateStep(current)) {
            currentStep = Math.min(current + 1, totalSteps);
            goToStep(currentStep);
            updateNavigation();
        }
    };

    window.prevStep = function(current) {
        currentStep = Math.max(current - 1, 1);
        goToStep(currentStep);
        updateNavigation();
    };

    function goToStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Show target step
        const targetStep = document.getElementById(`step${step}`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
        
        // Update sidebar
        updateSidebarSteps(step);
        updateProgress();
        
        // Scroll to step
        targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function updateSidebarSteps(currentStep) {
        document.querySelectorAll('.step-item').forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber === currentStep) {
                step.classList.add('active');
            } else if (stepNumber < currentStep) {
                step.classList.add('completed');
            }
        });
    }

    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        const progressBar = document.querySelector('.progress-bar');
        const progressText = document.querySelector('.progress-text');
        
        if (progressBar) progressBar.style.width = `${progress}%`;
        if (progressText) progressText.textContent = `Step ${currentStep} of ${totalSteps}`;
    }

    function updateNavigation() {
        const nextButton = document.querySelector(`#step${currentStep} .btn-reservation`);
        if (nextButton) {
            nextButton.disabled = !validateStep(currentStep);
        }
    }

    function validateStep(step) {
        switch (step) {
            case 1: return !!selectedPackage;
            case 2: return validateEventDetails();
            case 3: return validateContactDetails();
            case 4: return !!selectedPayment;
            default: return true;
        }
    }

    function validateEventDetails() {
        const eventType = document.querySelector('select[name="event_type"]').value;
        const eventDate = document.querySelector('input[name="event_date"]').value;
        const eventAddress = document.querySelector('textarea[name="event_address"]').value;
        
        if (!eventType || !eventDate || !eventAddress) return false;
        
        // Check if date is in future
        const selectedDate = new Date(eventDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate <= today) return false;
        
        return validateTimeRange();
    }

    function validateContactDetails() {
        const contactPhone = document.querySelector('input[name="contact_phone"]').value;
        return contactPhone && /^09\d{9}$/.test(contactPhone.replace(/\D/g, ''));
    }

    // REVIEW SECTION
    function updateReviewSection() {
        updatePackageReview();
        updateEventReview();
        updateContactReview();
        updatePaymentReview();
    }

    function updatePackageReview() {
        const element = document.getElementById('reviewPackage');
        if (!selectedPackage) {
            element.innerHTML = '<em>No package selected</em>';
            return;
        }
        
        const card = document.querySelector(`[data-package="${selectedPackage}"]`);
        const name = card?.querySelector('h4')?.textContent || 'Unknown';
        const price = card?.querySelector('.package-price')?.textContent || '₱0.00';
        
        element.innerHTML = `<strong>Package:</strong> ${name}<br><strong>Price:</strong> ${price}`;
    }

    function updateEventReview() {
        const eventType = document.querySelector('select[name="event_type"]').value;
        const eventDate = document.querySelector('input[name="event_date"]').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const eventAddress = document.querySelector('textarea[name="event_address"]').value;
        const landmarkNotes = document.querySelector('textarea[name="landmark_notes"]').value;
        
        let html = `<strong>Event Type:</strong> ${eventType || 'Not specified'}<br>`;
        html += `<strong>Date:</strong> ${eventDate ? new Date(eventDate).toLocaleDateString() : 'Not specified'}<br>`;
        
        if (startTime && endTime) {
            html += `<strong>Time:</strong> ${startTime} - ${endTime}<br>`;
        }
        
        html += `<strong>Address:</strong> ${eventAddress || 'Not specified'}`;
        
        if (landmarkNotes) {
            html += `<br><strong>Landmark Notes:</strong> ${landmarkNotes}`;
        }
        
        document.getElementById('reviewEvent').innerHTML = html;
    }

    function updateContactReview() {
        const name = document.querySelector('input[name="contact_name"]').value;
        const email = document.querySelector('input[name="contact_email"]').value;
        const phone = document.querySelector('input[name="contact_phone"]').value;
        const preferredContact = document.querySelector('select[name="preferred_contact"]').value;
        const socialMedia = document.querySelector('input[name="social_media_handle"]').value;
        
        let html = `<strong>Name:</strong> ${name}<br>`;
        html += `<strong>Email:</strong> ${email}<br>`;
        html += `<strong>Phone:</strong> ${phone || 'Not specified'}<br>`;
        html += `<strong>Preferred Contact:</strong> ${preferredContact || 'Not specified'}`;
        
        if (socialMedia) {
            html += `<br><strong>Social Media:</strong> ${socialMedia}`;
        }
        
        document.getElementById('reviewContact').innerHTML = html;
    }

    function updatePaymentReview() {
        const packagePrices = {
            'basic_setup': 5000, 'upgraded_setup_6000': 6000, 
            'upgraded_setup_7000': 7000, 'mid_setup': 10000
        };
        
        const total = packagePrices[selectedPackage] || 0;
        document.getElementById('reviewTotal').textContent = `₱${total.toFixed(2)}`;
        
        if (selectedPayment) {
            let html = `<strong>Method:</strong> ${selectedPayment.toUpperCase()}`;
            
            if (selectedPayment === 'gcash') {
                const downpayment = total * 0.20; // 20% downpayment
                const remaining = total - downpayment;
                html += `<br><strong>Downpayment:</strong> ₱${downpayment.toFixed(2)}`;
                html += `<br><strong>Remaining:</strong> ₱${remaining.toFixed(2)}`;
                
                document.getElementById('reviewDownpayment').innerHTML = `
                    <small class="text-warning">
                        <i class="bi bi-info-circle me-1"></i>
                        20% downpayment required (₱${downpayment.toFixed(2)})
                    </small>
                `;
            } else {
                document.getElementById('reviewDownpayment').innerHTML = `
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        No downpayment required
                    </small>
                `;
            }
            
            document.getElementById('reviewPayment').innerHTML = html;
        } else {
            document.getElementById('reviewPayment').innerHTML = '<em>No payment method selected</em>';
            document.getElementById('reviewDownpayment').innerHTML = '';
        }
    }

    // Add animation for spinner
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spinner {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);
});