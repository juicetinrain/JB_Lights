// js/reservation.js - Fixed with working OpenStreetMap search
class ReservationSystem {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5;
        this.selectedPackage = null;
        this.selectedPayment = null;
        this.map = null;
        this.marker = null;
        this.init();
    }

    init() {
        this.initEventListeners();
        this.initMap();
        this.updateProgress();
        console.log('Reservation system initialized');
    }

    initEventListeners() {
        // Package selection
        document.querySelectorAll('.package-card').forEach(card => {
            card.addEventListener('click', (e) => {
                this.selectPackage(card);
            });
        });

        // Payment selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', (e) => {
                const method = option.querySelector('input').value;
                this.selectPayment(method);
            });
        });

        // Address search
        document.getElementById('search-address-btn')?.addEventListener('click', () => {
            this.searchAddressFromInput();
        });

        // Enter key in address field
        document.getElementById('event_address')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.searchAddressFromInput();
            }
        });

        // Package price change handler
        document.querySelectorAll('.package-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                this.updateDownpaymentInfo();
            });
        });

        // Update address textarea when input changes
        document.getElementById('event_address')?.addEventListener('input', (e) => {
            document.querySelector('textarea[name="event_address"]').value = e.target.value;
        });
    }

    initMap() {
        // Default to JB Lights & Sound location in Mabalacat
        const defaultLocation = [15.1963, 120.6093];
        
        this.map = L.map('map').setView(defaultLocation, 15);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(this.map);

        // Add default marker
        this.marker = L.marker(defaultLocation)
            .addTo(this.map)
            .bindPopup('JB Lights & Sound - Default Location')
            .openPopup();

        // Store initial location
        document.getElementById('event_location').value = defaultLocation.join(',');

        // Add click event to map
        this.map.on('click', (e) => {
            this.updateMapLocation(e.latlng.lat, e.latlng.lng, 'Selected Event Location');
            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    }

    async searchAddressFromInput() {
        const address = document.getElementById('event_address').value.trim();
        if (!address) {
            alert('Please enter an address to search');
            return;
        }

        // Show loading state
        const searchBtn = document.getElementById('search-address-btn');
        const originalHtml = searchBtn.innerHTML;
        searchBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Searching...';
        searchBtn.disabled = true;

        try {
            // Use Nominatim for geocoding with proper headers
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address + ', Philippines')}&limit=1&addressdetails=1`,
                {
                    headers: {
                        'Accept': 'application/json',
                        'User-Agent': 'JBLightsAndSound/1.0'
                    }
                }
            );
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                const displayName = data[0].display_name;
                
                this.updateMapLocation(lat, lon, displayName);
                document.getElementById('event_address').value = displayName;
                document.querySelector('textarea[name="event_address"]').value = displayName;
            } else {
                alert('Address not found. Please try a different search term.');
            }
        } catch (error) {
            console.error('Error geocoding address:', error);
            alert('Error searching address. Please try again.');
        } finally {
            // Reset button state
            searchBtn.innerHTML = originalHtml;
            searchBtn.disabled = false;
        }
    }

    async reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`,
                {
                    headers: {
                        'Accept': 'application/json',
                        'User-Agent': 'JBLightsAndSound/1.0'
                    }
                }
            );
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data && data.display_name) {
                document.getElementById('event_address').value = data.display_name;
                document.querySelector('textarea[name="event_address"]').value = data.display_name;
            }
        } catch (error) {
            console.error('Error reverse geocoding:', error);
        }
    }

    updateMapLocation(lat, lng, popupText = 'Event Location') {
        if (this.marker) {
            this.marker.setLatLng([lat, lng]);
            this.marker.bindPopup(popupText).openPopup();
        } else {
            this.marker = L.marker([lat, lng])
                .addTo(this.map)
                .bindPopup(popupText)
                .openPopup();
        }

        this.map.setView([lat, lng], 15);
        document.getElementById('event_location').value = `${lat},${lng}`;
    }

    selectPackage(packageElement) {
        // Remove selected class from all packages
        document.querySelectorAll('.package-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Add selected class to clicked package
        packageElement.classList.add('selected');
        
        // Check the radio button
        const radio = packageElement.querySelector('.package-radio');
        radio.checked = true;
        this.selectedPackage = radio.value;
        
        this.updateDownpaymentInfo();
    }

    selectPayment(method) {
        this.selectedPayment = method;
        
        // Remove selected class from all payment options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('selected');
        });

        // Add selected class to clicked payment option
        document.querySelectorAll('.payment-option').forEach(option => {
            if (option.querySelector('input').value === method) {
                option.classList.add('selected');
            }
        });

        // Show/hide downpayment info
        const downpaymentInfo = document.getElementById('downpaymentInfo');
        if (method === 'gcash') {
            downpaymentInfo.style.display = 'block';
            this.updateDownpaymentInfo();
        } else {
            downpaymentInfo.style.display = 'none';
        }
    }

    updateDownpaymentInfo() {
        if (this.selectedPayment === 'gcash' && this.selectedPackage) {
            const packageElement = document.querySelector(`.package-card.selected`);
            if (packageElement) {
                const priceText = packageElement.querySelector('.package-price').textContent;
                const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
                const downpayment = price * 0.3;
                const remaining = price - downpayment;

                document.getElementById('downpaymentAmount').textContent = `₱${downpayment.toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                document.getElementById('remainingAmount').textContent = `₱${remaining.toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
            }
        }
    }

    nextStep(current) {
        if (this.validateStep(current)) {
            this.hideStep(current);
            this.showStep(current + 1);
            this.currentStep = current + 1;
            this.updateProgress();
            this.updateReview();
        }
    }

    prevStep(current) {
        this.hideStep(current);
        this.showStep(current - 1);
        this.currentStep = current - 1;
        this.updateProgress();
    }

    hideStep(step) {
        const stepElement = document.getElementById(`step${step}`);
        if (stepElement) {
            stepElement.classList.remove('active');
        }
    }

    showStep(step) {
        const stepElement = document.getElementById(`step${step}`);
        if (stepElement) {
            stepElement.classList.add('active');
        }
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
                return this.validatePaymentSelection();
            default:
                return true;
        }
    }

    validatePackageSelection() {
        const selectedPackage = document.querySelector('.package-radio:checked');
        if (!selectedPackage) {
            alert('Please select a package');
            return false;
        }
        return true;
    }

    validateEventDetails() {
        const eventType = document.querySelector('select[name="event_type"]');
        const eventDate = document.querySelector('input[name="event_date"]');
        const eventAddress = document.querySelector('textarea[name="event_address"]');

        if (!eventType.value) {
            alert('Please select an event type');
            eventType.focus();
            return false;
        }

        if (!eventDate.value) {
            alert('Please select an event date');
            eventDate.focus();
            return false;
        }

        // Check if date is in the future
        const today = new Date();
        const selectedDate = new Date(eventDate.value);
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate <= today) {
            alert('Event date must be in the future');
            eventDate.focus();
            return false;
        }

        if (!eventAddress.value.trim()) {
            alert('Please enter the event address');
            eventAddress.focus();
            return false;
        }

        return true;
    }

    validateContactDetails() {
        const contactPhone = document.querySelector('input[name="contact_phone"]');

        if (!contactPhone.value.trim()) {
            alert('Please enter your phone number');
            contactPhone.focus();
            return false;
        }

        // Validate phone number format (Philippine mobile)
        const phoneRegex = /^09\d{9}$/;
        const cleanPhone = contactPhone.value.replace(/\D/g, '');
        
        if (!phoneRegex.test(cleanPhone)) {
            alert('Please enter a valid Philippine mobile number (09XXXXXXXXX)');
            contactPhone.focus();
            return false;
        }

        return true;
    }

    validatePaymentSelection() {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            alert('Please select a payment method');
            return false;
        }
        return true;
    }

    updateProgress() {
        console.log(`Current step: ${this.currentStep}`);
    }

    updateReview() {
        if (this.currentStep === 5) {
            this.updatePackageReview();
            this.updateEventReview();
            this.updateContactReview();
            this.updatePaymentReview();
        }
    }

    updatePackageReview() {
        const selectedPackage = document.querySelector('.package-card.selected');
        if (selectedPackage) {
            const packageName = selectedPackage.querySelector('h4').textContent;
            const packagePrice = selectedPackage.querySelector('.package-price').textContent;
            document.getElementById('reviewPackage').innerHTML = `
                <strong>${packageName}</strong><br>
                ${packagePrice}
            `;
            document.getElementById('reviewTotal').textContent = packagePrice;
        }
    }

    updateEventReview() {
        const eventType = document.querySelector('select[name="event_type"]').value;
        const eventDate = document.querySelector('input[name="event_date"]').value;
        const eventAddress = document.querySelector('textarea[name="event_address"]').value;

        document.getElementById('reviewEvent').innerHTML = `
            <strong>${eventType}</strong><br>
            Date: ${eventDate}<br>
            Address: ${eventAddress}
        `;
    }

    updateContactReview() {
        const contactName = document.querySelector('input[name="contact_name"]').value;
        const contactEmail = document.querySelector('input[name="contact_email"]').value;
        const contactPhone = document.querySelector('input[name="contact_phone"]').value;

        document.getElementById('reviewContact').innerHTML = `
            <strong>${contactName}</strong><br>
            Email: ${contactEmail}<br>
            Phone: ${contactPhone}
        `;
    }

    updatePaymentReview() {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (selectedPayment) {
            const paymentText = selectedPayment.value === 'cod' ? 'Cash on Delivery' : 'GCash';
            let downpaymentText = '';
            
            if (selectedPayment.value === 'gcash') {
                const downpaymentAmount = document.getElementById('downpaymentAmount').textContent;
                const remainingAmount = document.getElementById('remainingAmount').textContent;
                downpaymentText = `<br>Downpayment: ${downpaymentAmount}<br>Remaining: ${remainingAmount}`;
            }

            document.getElementById('reviewPayment').innerHTML = `
                <strong>${paymentText}</strong>${downpaymentText}
            `;

            document.getElementById('reviewDownpayment').innerHTML = downpaymentText ? 
                `30% Downpayment Required: ${downpaymentAmount}` : 
                'No Downpayment Required';
        }
    }
}

// Global functions for HTML onclick
function nextStep(step) {
    if (window.reservationSystem) {
        window.reservationSystem.nextStep(step);
    }
}

function prevStep(step) {
    if (window.reservationSystem) {
        window.reservationSystem.prevStep(step);
    }
}

function selectPackage(element) {
    if (window.reservationSystem) {
        window.reservationSystem.selectPackage(element);
    }
}

function selectPayment(method) {
    if (window.reservationSystem) {
        window.reservationSystem.selectPayment(method);
    }
}

function searchAddressFromInput() {
    if (window.reservationSystem) {
        window.reservationSystem.searchAddressFromInput();
    }
}

// Initialize reservation system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.reservationSystem = new ReservationSystem();
});