// reservation.js - JB Lights & Sound Reservation System
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const steps = [1, 2, 3, 4];
    let currentStep = 1;
    const progressFill = document.getElementById('progressFill');
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    
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
        alert(message);
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

    // Leaflet Map Integration
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
        marker = L.marker(defaultCenter, { draggable: true }).addTo(map);

        // Marker drag event
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            reverseGeocode(position.lat, position.lng);
        });

        // Map click event
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        // Address input search
        const addressInput = document.getElementById('inputAddress');
        if (addressInput) {
            addressInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length > 2) {
                        forwardGeocode(query);
                    }
                }
            });
        }
    };

    function reverseGeocode(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('inputAddress').value = data.display_name;
                }
            })
            .catch(error => {
                console.log('Reverse geocoding error:', error);
            });
    }

    function forwardGeocode(query) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 15);
                    document.getElementById('inputAddress').value = result.display_name;
                } else {
                    showAlert('Address not found. Please try a different search term.');
                }
            })
            .catch(error => {
                console.log('Forward geocoding error:', error);
                showAlert('Geocoding service unavailable. Please try again later.');
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