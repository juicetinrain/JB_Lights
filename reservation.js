// reservation.js - simple stepper, validation and modal preview

document.addEventListener('DOMContentLoaded', () => {
  const steps = [1,2,3,4];
  let current = 1;
  const progressFill = document.getElementById('progressFill');
  const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

  // set min date to tomorrow
  const dateEl = document.getElementById('inputDate');
  if (dateEl) {
    const t = new Date(); t.setDate(t.getDate() + 1);
    dateEl.min = t.toISOString().split('T')[0];
  }

  // package selection
  document.querySelectorAll('.pkg-card').forEach(card => {
    card.addEventListener('click', () => selectPackage(card));
  });

  window.selectPackage = function(el) {
    document.querySelectorAll('.pkg-card').forEach(c => c.classList.remove('pkg-selected'));
    el.classList.add('pkg-selected');
    const val = el.getAttribute('data-value');
    const input = document.getElementById('inputPackage');
    if (input) input.value = val;
  };

  // show step
  function showStep(step) {
    steps.forEach(s => {
      const el = document.getElementById('step-' + s);
      if (!el) return;
      if (s === step) el.classList.add('active'); else el.classList.remove('active');
    });
    const pct = ((step - 1) / (steps.length - 1)) * 100;
    if (progressFill) progressFill.style.width = pct + '%';
    window.scrollTo({ top: document.querySelector('.step-card').offsetTop - 20, behavior: 'smooth' });
  }

  window.goStep = function(step) {
    // if going forward validate current
    if (step > current) {
      if (!validateStep(current)) return;
    }
    current = step;
    showStep(step);
    if (step === 4) populateReview();
  };

  function validateStep(step) {
    if (step === 1) {
      const pkg = document.getElementById('inputPackage').value;
      if (!pkg) { alert('Please select a package'); return false; }
    }
    if (step === 2) {
      const d = document.getElementById('inputDate').value;
      if (!d) { alert('Please pick an event date'); return false; }
      const sel = new Date(d); sel.setHours(0,0,0,0);
      const today = new Date(); today.setHours(0,0,0,0);
      if (sel <= today) { alert('Please choose a date after today'); return false; }
    }
    if (step === 3) {
      const a = document.getElementById('inputAddress').value.trim();
      const n = document.getElementById('inputName').value.trim();
      const p = document.getElementById('inputPhone').value.trim();
      if (!a || !n || !p) { alert('Please fill address, contact name and phone'); return false; }
    }
    return true;
  }

  function populateReview() {
    const pkg = document.getElementById('inputPackage').value || '—';
    const evType = document.getElementById('inputEventType').value || '-';
    const date = document.getElementById('inputDate').value || '-';
    const time = document.getElementById('inputTime').value || '';
    const address = document.getElementById('inputAddress').value || '-';
    const name = document.getElementById('inputName').value || '-';
    const phone = document.getElementById('inputPhone').value || '-';
    const paymentEl = document.querySelector('input[name="payment"]:checked');
    const payment = paymentEl ? paymentEl.value : '-';

    document.getElementById('reviewPackage').textContent = pkg;
    document.getElementById('reviewEvent').textContent = `${evType} • ${date} ${time}`;
    document.getElementById('reviewAddress').textContent = address;
    document.getElementById('reviewContact').textContent = `${name} • ${phone}`;
    document.getElementById('reviewPayment').textContent = payment;

    // downpayment calc for GCash (30% if price parsed)
    const downEl = document.getElementById('reviewDown');
    if (payment === 'GCash') {
      const down = calcDownpayment(pkg);
      downEl.textContent = down;
    } else {
      downEl.textContent = '₱0';
    }
  }

  function calcDownpayment(pkgLabel) {
    // try extract numeric amount e.g. "₱5,000" or "(₱5000)" or numbers in string
    let amount = 0;
    const m = pkgLabel.match(/₱?([\d,]+)/);
    if (m) amount = parseInt(m[1].replace(/,/g,''),10);
    if (!amount) {
      const mm = pkgLabel.match(/(\d{3,})/);
      if (mm) amount = parseInt(mm[1],10);
    }
    if (!amount) return 'Contact';
    const down = Math.round(amount * 0.30);
    return '₱' + down.toLocaleString();
  }

  // open preview modal
  window.openPreviewModal = function() {
    if (!validateStep(4)) return;
    populateReview();
    const pay = document.querySelector('input[name="payment"]:checked');
    const gcashBox = document.getElementById('gcashBox');
    const modalDown = document.getElementById('modalDown');
    const paymentVal = pay ? pay.value : '';
    if (paymentVal === 'GCash') {
      gcashBox.style.display = 'flex';
      modalDown.textContent = calcDownpayment(document.getElementById('inputPackage').value || '');
    } else {
      gcashBox.style.display = 'none';
    }
    // fill modal text
    const pkg = document.getElementById('inputPackage').value || '—';
    const evType = document.getElementById('inputEventType').value || '-';
    const date = document.getElementById('inputDate').value || '-';
    const time = document.getElementById('inputTime').value || '';
    const address = document.getElementById('inputAddress').value || '-';
    const name = document.getElementById('inputName').value || '-';
    const phone = document.getElementById('inputPhone').value || '-';
    const payment = paymentVal || '-';
    const notes = document.getElementById('inputNotes').value || '-';
    const html = `
      <div><strong>Package:</strong> ${pkg}</div>
      <div class="mt-2"><strong>Event:</strong> ${evType} • ${date} ${time}</div>
      <div class="mt-2"><strong>Address:</strong> ${address}</div>
      <div class="mt-2"><strong>Contact:</strong> ${name} • ${phone}</div>
      <div class="mt-2"><strong>Payment:</strong> ${payment}</div>
      <div class="mt-2"><strong>Notes:</strong> ${notes}</div>
    `;
    document.getElementById('modalReview').innerHTML = html;
    previewModal.show();
  };

  // modal confirm -> submit form
  const modalConfirmBtn = document.getElementById('modalConfirm');
  if (modalConfirmBtn) {
    modalConfirmBtn.addEventListener('click', () => {
      modalConfirmBtn.disabled = true;
      document.getElementById('reservationForm').submit();
    });
  }

  // init
  showStep(current);

  function showStep(step) {
    steps.forEach(s => {
      const el = document.getElementById('step-' + s);
      if (!el) return;
      el.classList.toggle('active', s === step);
    });
    const pct = ((step - 1) / (steps.length - 1)) * 100;
    if (progressFill) progressFill.style.width = pct + '%';
  }
});