// reservation.js - JB Lights & Sound Reservation System - FIXED MAP
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const steps = [1, 2, 3, 4];
    let currentStep = 1;
    const progressFill = document.getElementById('progressFill');
    
    // Set minimum date to tomorrow
    const dateInput = document.getElementById('inputDate');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
    }

    // Initialize the application
    initApplication();

    function initApplication() {
        setupPackageSelection();
        setupPaymentHandlers();
        setupFormSubmission();
        showStep(currentStep);
    }

    // Package selection functionality
    function setupPackageSelection() {
        const packageCards = document.querySelectorAll('.package-card');
        packageCards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.classList.contains('btn-select')) {
                    selectPackage(this);
                }
            });
        });
    }

    // Global function for package selection
    window.selectPackage = function(element) {
        const packageCard = element.closest('.package-card');
        if (!packageCard) return;

        // Remove selection from all cards
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add selection to clicked card
        packageCard.classList.add('selected');

        // Update hidden input value
        const packageValue = packageCard.getAttribute('data-value');
        document.getElementById('inputPackage').value = packageValue;
    };

    // Step navigation
    window.goStep = function(step) {
        // Validate current step before proceeding
        if (step > currentStep && !validateStep(currentStep)) {
            return;
        }

        currentStep = step;
        showStep(step);

        // Special handling for specific steps
        if (step === 3) {
            setTimeout(initLeafletMap, 300);
        } else if (step === 4) {
            populateReview();
        }
    };

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });

        // Show current step
        const currentStepEl = document.getElementById('step-' + step);
        if (currentStepEl) {
            currentStepEl.classList.add('active');
        }

        // Update progress bar
        updateProgressBar(step);

        // Scroll to top of form
        scrollToForm();
    }

    function updateProgressBar(step) {
        if (progressFill) {
            const progressPercentage = ((step - 1) / (steps.length - 1)) * 100;
            progressFill.style.width = progressPercentage + '%';
        }
    }

    function scrollToForm() {
        const formCard = document.querySelector('.step-card');
        if (formCard) {
            // Calculate offset for fixed header
            const headerHeight = document.querySelector('.main-header').offsetHeight;
            const formPosition = formCard.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
            
            window.scrollTo({
                top: formPosition,
                behavior: 'smooth'
            });
        }
    }

    // Step validation
    function validateStep(step) {
        switch(step) {
            case 1:
                return validatePackageSelection();
            case 2:
                return validateEventDetails();
            case 3:
                return validateContactDetails();
            default:
                return true;
        }
    }

    function validatePackageSelection() {
        const packageInput = document.getElementById('inputPackage');
        if (!packageInput.value) {
            showAlert('Please select a package to continue');
            return false;
        }
        return true;
    }

    function validateEventDetails() {
        const dateInput = document.getElementById('inputDate');
        const eventTypeInput = document.getElementById('inputEventType');

        if (!dateInput.value) {
            showAlert('Please select an event date');
            return false;
        }

        if (!eventTypeInput.value) {
            showAlert('Please select an event type');
            return false;
        }

        // Validate date is in the future
        const selectedDate = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate <= today) {
            showAlert('Please select a date after today');
            return false;
        }

        return true;
    }

    function validateContactDetails() {
        const addressInput = document.getElementById('inputAddress');
        const nameInput = document.getElementById('inputName');
        const phoneInput = document.getElementById('inputPhone');

        if (!addressInput.value.trim()) {
            showAlert('Please enter the event address');
            return false;
        }

        if (!nameInput.value.trim()) {
            showAlert('Please enter the contact person name');
            return false;
        }

        if (!phoneInput.value.trim()) {
            showAlert('Please enter a contact phone number');
            return false;
        }

        // Basic phone validation (Philippines)
        const phoneRegex = /^(09|\+639)\d{9}$/;
        const cleanPhone = phoneInput.value.replace(/\D/g, '');
        
        if (!phoneRegex.test('09' + cleanPhone.slice(-9))) {
            showAlert('Please enter a valid Philippine mobile number (09XXXXXXXXX)');
            return false;
        }

        return true;
    }

    function showAlert(message) {
        // Use Bootstrap alert or simple alert
        if (typeof bootstrap !== 'undefined') {
            // Create Bootstrap alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container').prepend(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        } else {
            alert(message);
        }
    }

    // Review section population
    function populateReview() {
        const package = document.getElementById('inputPackage').value || 'Not selected';
        const eventType = document.getElementById('inputEventType').value || 'Not specified';
        const date = document.getElementById('inputDate').value || 'Not set';
        const time = document.getElementById('inputTime').value || '';
        const address = document.getElementById('inputAddress').value || 'Not provided';
        const name = document.getElementById('inputName').value || 'Not provided';
        const phone = document.getElementById('inputPhone').value || 'Not provided';
        const facebook = document.getElementById('facebook_account').value || 'Not provided';
        
        const paymentRadio = document.querySelector('input[name="payment"]:checked');
        const payment = paymentRadio ? paymentRadio.value : 'Not selected';

        // Update review section
        document.getElementById('reviewPackage').textContent = package;
        document.getElementById('reviewEvent').textContent = `${eventType} • ${date} ${time}`.trim();
        document.getElementById('reviewAddress').textContent = address;
        document.getElementById('reviewContact').textContent = `${name} • ${phone}`;

        // Calculate and display downpayment if GCash is selected
        const downpaymentElement = document.getElementById('reviewDown');
        const downpaymentInfo = document.getElementById('downpaymentInfo');
        
        if (payment === 'GCash') {
            const downpayment = calculateDownpayment(package);
            downpaymentElement.textContent = downpayment;
            downpaymentInfo.style.display = 'block';
        } else {
            downpaymentElement.textContent = '₱0';
            downpaymentInfo.style.display = 'none';
        }
    }

    function calculateDownpayment(packageLabel) {
        // Extract numeric value from package label
        const match = packageLabel.match(/₱?([\d,]+)/);
        if (match) {
            const amount = parseInt(match[1].replace(/,/g, ''), 10);
            if (!isNaN(amount)) {
                const downpayment = Math.round(amount * 0.30);
                return '₱' + downpayment.toLocaleString();
            }
        }
        return 'Contact for details';
    }

    // Payment method handlers
    function setupPaymentHandlers() {
        const paymentRadios = document.querySelectorAll('input[name="payment"]');
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'GCash') {
                    document.getElementById('downpaymentInfo').style.display = 'block';
                } else {
                    document.getElementById('downpaymentInfo').style.display = 'none';
                }
            });
        });
    }

    // Modal preview
    window.openPreviewModal = function() {
        if (!validateStep(4)) return;

        populateReview();
        populateModalContent();
        
        const paymentRadio = document.querySelector('input[name="payment"]:checked');
        const gcashBox = document.getElementById('gcashBox');
        
        if (paymentRadio && paymentRadio.value === 'GCash') {
            gcashBox.style.display = 'block';
            document.getElementById('modalDown').textContent = 
                document.getElementById('reviewDown').textContent;
        } else {
            gcashBox.style.display = 'none';
        }

        // Show modal using Bootstrap
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        previewModal.show();
    };

    function populateModalContent() {
        const package = document.getElementById('inputPackage').value || 'Not selected';
        const eventType = document.getElementById('inputEventType').value || 'Not specified';
        const date = document.getElementById('inputDate').value || 'Not set';
        const time = document.getElementById('inputTime').value || '';
        const address = document.getElementById('inputAddress').value || 'Not provided';
        const name = document.getElementById('inputName').value || 'Not provided';
        const phone = document.getElementById('inputPhone').value || 'Not provided';
        const facebook = document.getElementById('facebook_account').value || 'Not provided';
        const notes = document.getElementById('inputNotes').value || 'None';
        
        const paymentRadio = document.querySelector('input[name="payment"]:checked');
        const payment = paymentRadio ? paymentRadio.value : 'Not selected';

        const modalContent = `
            <div class="modal-booking-details">
                <div class="modal-detail-item">
                    <strong>Package:</strong> ${package}
                </div>
                <div class="modal-detail-item">
                    <strong>Event:</strong> ${eventType} • ${date} ${time}
                </div>
                <div class="modal-detail-item">
                    <strong>Address:</strong> ${address}
                </div>
                <div class="modal-detail-item">
                    <strong>Contact Person:</strong> ${name}
                </div>
                <div class="modal-detail-item">
                    <strong>Phone:</strong> ${phone}
                </div>
                ${facebook !== 'Not provided' ? `
                <div class="modal-detail-item">
                    <strong>Facebook:</strong> ${facebook}
                </div>` : ''}
                <div class="modal-detail-item">
                    <strong>Payment Method:</strong> ${payment}
                </div>
                <div class="modal-detail-item">
                    <strong>Notes:</strong> ${notes}
                </div>
            </div>
        `;

        document.getElementById('modalReview').innerHTML = modalContent;
    }

    // Form submission
    function setupFormSubmission() {
        const modalConfirmBtn = document.getElementById('modalConfirm');
        if (modalConfirmBtn) {
            modalConfirmBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                
                // Submit the form
                document.getElementById('reservationForm').submit();
            });
        }
    }

    // Leaflet Map Integration - FIXED VERSION
    let map = null;
    let marker = null;

    window.initLeafletMap = function() {
        if (map) {
            map.invalidateSize();
            return;
        }

        // Default center (Philippines)
        const defaultCenter = [14.5995, 120.9842]; // Manila coordinates
        
        // Initialize map
        map = L.map('map').setView(defaultCenter, 13);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Create draggable marker
        marker = L.marker(defaultCenter, { 
            draggable: true,
            title: 'Drag me to set exact location'
        }).addTo(map);

        // Add popup to marker
        marker.bindPopup('Event Location<br>Drag me or click map to set location').openPopup();

        // Marker drag event - get address when dragged
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            getAddressFromCoordinates(position.lat, position.lng);
        });

        // Map click event - move marker and get address
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            getAddressFromCoordinates(e.latlng.lat, e.latlng.lng);
            
            // Update popup
            marker.bindPopup('Location Set!<br>Address updated in form').openPopup();
        });

        // Address input search - when user types and presses Enter
        const addressInput = document.getElementById('inputAddress');
        if (addressInput) {
            addressInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length > 2) {
                        searchAddress(query);
                    } else {
                        showAlert('Please enter a more specific address (at least 3 characters)');
                    }
                }
            });
        }

        // Show help message
        showAlert('Click anywhere on the map to set your event location. The address will automatically update.');
    };

    // Function to get address from coordinates
    function getAddressFromCoordinates(lat, lng) {
        const addressInput = document.getElementById('inputAddress');
        
        // Show loading state
        addressInput.placeholder = 'Getting address...';
        addressInput.disabled = true;

        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                    addressInput.placeholder = 'Event address will appear here when you click the map';
                    
                    // Show success message
                    const mapHelp = document.querySelector('.map-help');
                    if (mapHelp) {
                        mapHelp.innerHTML = '<i class="bi bi-check-circle"></i> Address updated from map location';
                        mapHelp.style.color = 'var(--blue)';
                    }
                } else {
                    addressInput.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    showAlert('Exact address not found. Coordinates have been set instead.');
                }
            })
            .catch(error => {
                console.log('Geocoding error:', error);
                addressInput.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                showAlert('Unable to get address. Using coordinates instead.');
            })
            .finally(() => {
                addressInput.disabled = false;
                addressInput.placeholder = 'House number, street, barangay, city, province';
            });
    }

    // Function to search address and move marker
    function searchAddress(query) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=ph`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    // Move marker to found location
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 15);
                    
                    // Update address input
                    document.getElementById('inputAddress').value = result.display_name;
                    
                    // Update popup
                    marker.bindPopup('Location Found!<br>Address updated from search').openPopup();
                    
                    // Show success message
                    const mapHelp = document.querySelector('.map-help');
                    if (mapHelp) {
                        mapHelp.innerHTML = '<i class="bi bi-check-circle"></i> Location found and marker moved';
                        mapHelp.style.color = 'var(--blue)';
                    }
                } else {
                    showAlert('Address not found. Please try a different search term or click on the map directly.');
                }
            })
            .catch(error => {
                console.log('Forward geocoding error:', error);
                showAlert('Search service unavailable. Please click on the map to set your location.');
            });
    }
});

// Utility function for price extraction
window.extractPriceFromPackage = function(packageText) {
    const match = packageText.match(/₱?([\d,]+)/);
    if (match) {
        return parseInt(match[1].replace(/,/g, ''), 10);
    }
    return null;
};