// js/reservation.js - Complete Reservation System
class ReservationSystem {
    constructor() {
        this.currentStep = 1;
        this.map = null;
        this.marker = null;
        this.selectedPackage = null;
        this.init();
    }

    init() {
        this.initStepNavigation();
        this.initPackageSelection();
        this.initDateRestrictions();
        this.initEventListeners();
        console.log('Reservation system initialized');
    }

    initStepNavigation() {
        // Make functions globally available
        window.nextStep = (step) => this.nextStep(step);
        window.prevStep = (step) => this.prevStep(step);
        window.selectPayment = (method) => this.selectPayment(method);
        window.searchAddressFromInput = () => this.searchAddressFromInput();
    }

    initPackageSelection() {
        document.querySelectorAll('.package-radio').forEach(radio => {
            radio.addEventListener('change', (e) => {
                document.querySelectorAll('.package-card').forEach(card => {
                    card.classList.remove('selected');
                });
                if (e.target.checked) {
                    e.target.closest('.package-card').classList.add('selected');
                    this.selectedPackage = e.target.value;
                }
            });
        });
    }

    initDateRestrictions() {
        const dateInput = document.querySelector('input[name="event_date"]');
        if (dateInput) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.min = tomorrow.toISOString().split('T')[0];
        }
    }

    initEventListeners() {
        // Address search on Enter key
        const addressInput = document.querySelector('textarea[name="event_address"]');
        if (addressInput) {
            addressInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.searchAddressFromInput();
                }
            });
        }

        // Package change updates downpayment
        document.querySelectorAll('.package-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                if (document.querySelector('input[name="payment_method"]:checked')?.value === 'gcash') {
                    this.calculateDownpayment();
                }
            });
        });
    }

    showStep(step) {
        document.querySelectorAll('.step-card').forEach(card => {
            card.classList.remove('active');
        });
        
        const stepElement = document.getElementById('step' + step);
        if (stepElement) {
            stepElement.classList.add('active');
        }
        this.currentStep = step;

        // Special handling for specific steps
        if (step === 2) {
            setTimeout(() => this.initMap(), 300);
        } else if (step === 4) {
            this.updateReview();
        }
    }

    nextStep(step) {
        if (this.validateStep(step)) {
            this.showStep(step + 1);
        }
    }

    prevStep(step) {
        this.showStep(step - 1);
    }

    validateStep(step) {
        switch(step) {
            case 1:
                return this.validatePackageSelection();
            case 2:
                return this.validateEventDetails();
            case 3:
                return this.validateContactDetails();
            case 4:
                return this.validatePaymentMethod();
            default:
                return true;
        }
    }

    validatePackageSelection() {
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        if (!selectedPackage) {
            this.showAlert('PLEASE SELECT A PACKAGE');
            return false;
        }
        return true;
    }

    validateEventDetails() {
        const requiredFields = document.querySelectorAll('#step2 [required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                this.showAlert('PLEASE FILL IN ALL REQUIRED FIELDS');
                field.focus();
                return false;
            }
        }

        // Validate date
        const eventDate = new Date(document.querySelector('input[name="event_date"]').value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (eventDate <= today) {
            this.showAlert('EVENT DATE MUST BE IN THE FUTURE');
            return false;
        }

        return true;
    }

    validateContactDetails() {
        const requiredFields = document.querySelectorAll('#step3 [required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                this.showAlert('PLEASE FILL IN ALL REQUIRED FIELDS');
                field.focus();
                return false;
            }
        }

        // Validate phone number
        const phone = document.querySelector('input[name="contact_phone"]').value;
        if (!this.validatePhone(phone)) {
            this.showAlert('PLEASE ENTER A VALID PHILIPPINE MOBILE NUMBER (09XXXXXXXXX)');
            return false;
        }

        return true;
    }

    validatePaymentMethod() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            this.showAlert('PLEASE SELECT A PAYMENT METHOD');
            return false;
        }
        return true;
    }

    validatePhone(phone) {
        const cleanPhone = phone.replace(/\D/g, '');
        const phoneRegex = /^(09)\d{9}$/;
        return phoneRegex.test(cleanPhone);
    }

    showAlert(message) {
        alert(message);
    }

    // Map Functions
    initMap() {
        if (this.map) {
            this.map.invalidateSize();
            return;
        }

        // Default center (Philippines)
        const defaultCenter = [14.5995, 120.9842];
        
        // Initialize map
        this.map = L.map('map').setView(defaultCenter, 13);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(this.map);

        // Create draggable marker
        this.marker = L.marker(defaultCenter, { 
            draggable: true,
            title: 'DRAG ME TO SET EXACT LOCATION'
        }).addTo(this.map);

        // Add popup to marker
        this.marker.bindPopup('EVENT LOCATION<br>DRAG ME OR CLICK MAP TO SET LOCATION').openPopup();

        // Marker drag event
        this.marker.on('dragend', (e) => {
            const position = this.marker.getLatLng();
            this.updateLocation(position.lat, position.lng);
        });

        // Map click event
        this.map.on('click', (e) => {
            this.marker.setLatLng(e.latlng);
            this.updateLocation(e.latlng.lat, e.latlng.lng);
            this.marker.bindPopup('LOCATION SET!<br>ADDRESS UPDATED').openPopup();
        });
    }

    updateLocation(lat, lng) {
        // Store coordinates
        document.getElementById('event_location').value = `${lat},${lng}`;
        
        // Update address field with coordinates temporarily
        const addressInput = document.querySelector('textarea[name="event_address"]');
        addressInput.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        
        // Try to get address from coordinates
        this.reverseGeocode(lat, lng);
    }

    reverseGeocode(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    const addressInput = document.querySelector('textarea[name="event_address"]');
                    addressInput.value = data.display_name;
                }
            })
            .catch(error => {
                console.log('Reverse geocoding error:', error);
            });
    }

    searchAddressFromInput() {
        const addressInput = document.querySelector('textarea[name="event_address"]');
        const query = addressInput.value.trim();
        this.searchAddress(query);
    }

    searchAddress(query) {
        if (query.length < 3) {
            this.showAlert('PLEASE ENTER AT LEAST 3 CHARACTERS FOR SEARCH');
            return;
        }

        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=ph`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    
                    this.marker.setLatLng([lat, lon]);
                    this.map.setView([lat, lon], 15);
                    this.updateLocation(lat, lon);
                    this.marker.bindPopup('LOCATION FOUND!<br>ADDRESS UPDATED FROM SEARCH').openPopup();
                } else {
                    this.showAlert('ADDRESS NOT FOUND. PLEASE TRY A DIFFERENT SEARCH TERM.');
                }
            })
            .catch(error => {
                console.log('Forward geocoding error:', error);
                this.showAlert('SEARCH SERVICE UNAVAILABLE. PLEASE CLICK ON THE MAP TO SET LOCATION.');
            });
    }

    // Payment Functions
    selectPayment(method) {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        const selectedOption = document.querySelector(`#${method}`).closest('.payment-option');
        selectedOption.classList.add('selected');
        document.getElementById(method).checked = true;
        
        // Show/hide downpayment info
        const downpaymentInfo = document.getElementById('downpaymentInfo');
        if (method === 'gcash') {
            downpaymentInfo.style.display = 'block';
            this.calculateDownpayment();
        } else {
            downpaymentInfo.style.display = 'none';
        }
    }

    calculateDownpayment() {
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        if (selectedPackage) {
            const packagePrice = this.getPackagePrice(selectedPackage.value);
            const downpayment = packagePrice * 0.30;
            const remaining = packagePrice - downpayment;
            
            document.getElementById('downpaymentAmount').textContent = '₱' + downpayment.toLocaleString();
            document.getElementById('remainingAmount').textContent = '₱' + remaining.toLocaleString();
        }
    }

    getPackagePrice(packageKey) {
        const prices = {
            'basic_setup': 5000,
            'upgraded_setup_6000': 6000,
            'upgraded_setup_7000': 7000,
            'mid_setup': 10000
        };
        return prices[packageKey] || 0;
    }

    // Review Functions
    updateReview() {
        this.updatePackageReview();
        this.updateEventReview();
        this.updateContactReview();
        this.updatePaymentReview();
    }

    updatePackageReview() {
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        if (selectedPackage) {
            const packageLabel = selectedPackage.parentElement.querySelector('.package-header h4').textContent;
            const packagePrice = selectedPackage.parentElement.querySelector('.package-price').textContent;
            
            document.getElementById('reviewPackage').innerHTML = `
                <p><strong>PACKAGE:</strong> ${packageLabel}</p>
                <p><strong>PRICE:</strong> ${packagePrice}</p>
            `;
            document.getElementById('reviewTotal').textContent = packagePrice;
        }
    }

    updateEventReview() {
        const eventType = document.querySelector('select[name="event_type"]').value;
        const eventDate = document.querySelector('input[name="event_date"]').value;
        const eventAddress = document.querySelector('textarea[name="event_address"]').value;

        document.getElementById('reviewEvent').innerHTML = `
            <p><strong>EVENT TYPE:</strong> ${eventType}</p>
            <p><strong>EVENT DATE:</strong> ${eventDate}</p>
            <p><strong>ADDRESS:</strong> ${eventAddress}</p>
        `;
    }

    updateContactReview() {
        const contactName = document.querySelector('input[name="contact_name"]').value;
        const contactEmail = document.querySelector('input[name="contact_email"]').value;
        const contactPhone = document.querySelector('input[name="contact_phone"]').value;

        document.getElementById('reviewContact').innerHTML = `
            <p><strong>NAME:</strong> ${contactName}</p>
            <p><strong>EMAIL:</strong> ${contactEmail}</p>
            <p><strong>PHONE:</strong> ${contactPhone}</p>
        `;
    }

    updatePaymentReview() {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (paymentMethod) {
            const method = paymentMethod.value;
            const methodText = method === 'cod' ? 'CASH ON DELIVERY' : 'GCASH';
            let paymentHTML = `<p><strong>PAYMENT METHOD:</strong> ${methodText}</p>`;
            
            if (method === 'gcash') {
                const downpayment = document.getElementById('downpaymentAmount').textContent;
                const remaining = document.getElementById('remainingAmount').textContent;
                paymentHTML += `
                    <p><strong>DOWNPAYMENT:</strong> ${downpayment}</p>
                    <p><strong>REMAINING BALANCE:</strong> ${remaining}</p>
                `;
                document.getElementById('reviewDownpayment').innerHTML = `
                    <p class="text-warning mb-0"><small>30% DOWNPAYMENT REQUIRED VIA GCASH</small></p>
                `;
            } else {
                document.getElementById('reviewDownpayment').innerHTML = `
                    <p class="text-success mb-0"><small>PAY AFTER THE EVENT - NO DOWNPAYMENT REQUIRED</small></p>
                `;
            }
            
            document.getElementById('reviewPayment').innerHTML = paymentHTML;
        }
    }
}

// Initialize reservation system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new ReservationSystem();
});