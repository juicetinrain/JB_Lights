/* reservation.js
  Handles storing & restoring reservation data across steps using localStorage.
  Key: 'jbReservation'
*/
(function(){
  const KEY = 'jbReservation';

  function read(){
    try{
      return JSON.parse(localStorage.getItem(KEY) || '{}');
    }catch(e){ return {} }
  }
  function write(data){
    localStorage.setItem(KEY, JSON.stringify(data || {}));
  }
  function clear(){
    localStorage.removeItem(KEY);
  }

  // Expose helper functions to window for pages to call
  window.jbRes = {
    read, write, clear
  };

  // Utility: format date YYYY-MM-DD -> readable
  window.jbFormatDate = function(iso){
    if(!iso) return '';
    const d = new Date(iso);
    if(isNaN(d)) return iso;
    return d.toLocaleDateString(undefined, {weekday:'short',month:'short',day:'numeric',year:'numeric'});
  };

  // Simple availability checker:
  // - if selected date is within 3 days from today -> "Limited (confirm)"
  // - otherwise "Available"
  window.jbCheckAvailability = function(dateIso){
    if(!dateIso) return {ok:false,msg:'No date'};
    const sel = new Date(dateIso); sel.setHours(0,0,0,0);
    const today = new Date(); today.setHours(0,0,0,0);
    const diff = Math.round((sel - today) / (1000*60*60*24));
    if(diff < 0) return {ok:false,msg:'Selected date is in the past'};
    if(diff <= 3) return {ok:false,msg:'Limited availability — confirm with us'};
    return {ok:true,msg:'Available'};
  };

  // When loaded on any reservation page, populate inputs if elements exist
  document.addEventListener('DOMContentLoaded', ()=>{
    const data = read();

    // populate package selection (cards)
    const packVal = data.package;
    if(packVal){
      const el = document.querySelector(`[data-pack="${packVal}"]`);
      if(el) el.classList.add('selected');
    }

    // date field
    const dateInput = document.querySelector('#eventDate');
    if(dateInput && data.date) dateInput.value = data.date;

    // time
    const timeInput = document.querySelector('#eventTime');
    if(timeInput && data.time) timeInput.value = data.time;

    // address and contact
    const addr = document.querySelector('#eventAddress');
    if(addr && data.address) addr.value = data.address;
    const contact = document.querySelector('#contactName');
    if(contact && data.name) contact.value = data.name;
    const phone = document.querySelector('#contactPhone');
    if(phone && data.phone) phone.value = data.phone;

    // payment
    const pay = data.payment;
    if(pay){
      const pm = document.querySelector(`input[name="payment"][value="${pay}"]`);
      if(pm) pm.checked = true;
      const pmCard = document.querySelector(`.payment-method[data-pay="${pay}"]`);
      if(pmCard) pmCard.classList.add('selected');
    }

    // show review fields if present
    const reviewPack = document.querySelector('#reviewPackage');
    if(reviewPack && data.packageLabel) reviewPack.textContent = data.packageLabel;
    const reviewDate = document.querySelector('#reviewDate');
    if(reviewDate) reviewDate.textContent = jbFormatDate(data.date || '');
    const reviewAddr = document.querySelector('#reviewAddress');
    if(reviewAddr) reviewAddr.textContent = data.address || '-';
    const reviewPayment = document.querySelector('#reviewPayment');
    if(reviewPayment) reviewPayment.textContent = (data.payment === 'gcash') ? 'GCash (Downpayment)' : 'Cash on Arrival';

    // availability helper display (if exists)
    const availBox = document.querySelector('#availabilityBox');
    if(availBox && dateInput){
      const check = jbCheckAvailability(dateInput.value);
      availBox.textContent = check.msg;
      availBox.className = check.ok ? 'badge-available' : 'badge-check';
    }

  });

})();