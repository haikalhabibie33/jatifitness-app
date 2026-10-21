<!-- JS here -->
<script src="<?= base_url('assets/js/vendor/modernizr-3.5.0.min.js') ?>"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="<?= base_url('assets/js/vendor/jquery-1.12.4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<!-- Jquery Mobile Menu -->
<script src="<?= base_url('assets/js/jquery.slicknav.min.js') ?>"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="<?= base_url('assets/js/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('assets/js/slick.min.js') ?>"></script>
<!-- One Page, Animated-HeadLin -->
<script src="<?= base_url('assets/js/wow.min.js') ?>"></script>
<script src="<?= base_url('assets/js/animated.headline.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.magnific-popup.js') ?>"></script>

<!-- Date Picker -->
<script src="<?= base_url('assets/js/gijgo.min.js') ?>"></script>
<!-- Nice-select, sticky -->
<script src="<?= base_url('assets/js/jquery.nice-select.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.sticky.js') ?>"></script>

<!-- counter , waypoint, Hover Direction-->
<script src="<?= base_url('assets/js/jquery.counterup.min.js') ?>"></script>
<script src="<?= base_url('assets/js/waypoints.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.countdown.min.js') ?>"></script>
<script src="<?= base_url('assets/js/hover-direction-snake.min.js') ?>"></script>

<!-- contact js -->
<!-- <script src="<?= base_url('assets/js/contact.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/js/mail-script.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.ajaxchimp.min.js') ?>"></script> -->

<!-- Jquery Plugins, main Jquery -->	
<script src="<?= base_url('assets/js/plugins.js') ?>"></script>
<script src="<?= base_url('assets/js/main.js') ?>"></script>

<!-- OTP functionality -->
<script src="<?= base_url('assets/js/otp.js') ?>"></script>

<!-- select2 -->
<script src="<?= base_url('assets/js/select2.min.js') ?>"></script>

<!-- Became a member form -->
<script src="<?= base_url('assets/js/show-hide-member-form.js') ?>"></script>

<script>
$(document).ready(function() {
    $('#select2').select2({
        placeholder: "-- Select club --",
        allowClear: true
    });
});
</script>

<!-- refactor besar untuk form member dan casual -->
 <script src="<?= base_url('assets/js/casual-member-form-handler.js') ?>"></script>
<!-- end of refactor -->

<script>
(function () {

  const durationInput = document.getElementById('durationInput');
  if (!durationInput) return;

  const MIN = 1;
  const MAX = 30;

  // ✅ block unwanted keys
  durationInput.addEventListener('keydown', function (e) {

    const allowedKeys = [
      'ArrowUp',
      'ArrowDown',
      'Tab',
      'Backspace',
      'Delete'
    ];

    // allow number keys
    const isNumber = /^[0-9]$/.test(e.key);

    if (!allowedKeys.includes(e.key) && !isNumber) {
      e.preventDefault();
    }
  });

  // ✅ clamp value (paste / scroll / manual edit)
  durationInput.addEventListener('input', function () {

    let v = parseInt(this.value, 10);

    if (isNaN(v)) {
      this.value = MIN;
      return;
    }

    if (v < MIN) this.value = MIN;
    if (v > MAX) this.value = MAX;
  });

})();
</script>

<script>
const $plan     = $('#packageSelect');
const $qty      = $('#quantityInput');
const $durOut   = $('#totalDurationInput');
const $priceOut = $('#totalPriceInput');

function recalc() {
const $opt = $plan.find('option:selected');

const baseDuration = parseInt($opt.data('duration'), 10) || 0;
const basePrice    = parseInt($opt.data('price'), 10) || 0;
const qty          = parseInt($qty.val(), 10) || 1;

$durOut.val(baseDuration * qty);
$priceOut.val(basePrice * qty);
}

// trigger on both
$plan.on('change', recalc);
$qty.on('input', recalc);
</script>


<script>
function formatIDR(value) {
  if (value === null || value === undefined || value === '') return '0';
  return Number(value).toLocaleString('id-ID');
}
</script>


<!-- summary modal -->
<script>
(function () {

  function formatIDR(value) {
    return Number(value || 0).toLocaleString('id-ID');
  }

  function isValidEmail(email) {
    return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.trim());
  }

  function isValidPhone(phone) {
    let clean = phone.replace(/\D/g, '');
    if (clean.startsWith('0')) {
      clean = '62' + clean.slice(1);
    } else if (clean.startsWith('62')){
      return false;
    }
    return /^62[0-9]{8,13}$/.test(clean);
  }

  function validateCasualForm(form) {
    const pkgOpt = form.querySelector('#casualPackage option:checked');

    const fields = {
      category: form.querySelector('#casualCategory'),
      package: pkgOpt,
      quantity: form.querySelector('#quantityInput'),
      duration: form.querySelector('#totalDurationInput'),
      price: form.querySelector('#totalPriceInput'),
      name: form.querySelector('#name'),
      phone: form.querySelector('#phone'),
      email: form.querySelector('#email'),
      gender: form.querySelector('[name="gender"]'),
    };

    let isValid = true;

    Object.values(fields).forEach(el => {
      if (!el || !el.value?.trim()) {
        isValid = false;
        el?.classList.add('is-invalid');
      } else {
        el.classList.remove('is-invalid');
      }
    });

    // validasi tambahan
    if (fields.email && !isValidEmail(fields.email.value)) {
      isValid = false;
      fields.email.classList.add('is-invalid');
    }

    if (fields.phone && !isValidPhone(fields.phone.value)) {
      isValid = false;
      fields.phone.classList.add('is-invalid');
    }

    return { isValid, fields, pkgOpt };
  }

  function openSummary(flow) {
    let form;
    let data = {};

    

    if (flow === 'CASUAL') {
      const titleEl = document.querySelector('#summaryModal .modal-title');
      titleEl.textContent = 'Confirm Your Casual Access';
      form = document.querySelector('.js-form-casual');
      if (!form) return;

      const { isValid, fields, pkgOpt } = validateCasualForm(form);

      if (!isValid) {
        form.querySelector('#formWarning')?.classList.remove('d-none');

        // optional: scroll ke error pertama
        const firstError = form.querySelector('.is-invalid');
        firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });

        return;
      }

      form.querySelector('#formWarning')?.classList.add('d-none');

      data.category   = fields.category.value;
      data.package    = pkgOpt.textContent;
      data.quantity   = fields.quantity.value;
      data.duration   = fields.duration.value;
      data.unitPrice  = pkgOpt.dataset.price;
      data.totalPrice = fields.price.value;

      data.name  = fields.name.value;
      data.phone = fields.phone.value;
      data.email = fields.email.value;
      data.gender = fields.gender.value;
    }

    // ==== SUMMARY ====
    document.getElementById('sCategory').textContent = data.category;
    document.getElementById('sPackage').textContent  = data.package;
    document.getElementById('sQty').textContent      = data.quantity;
    document.getElementById('sDuration').textContent = data.duration;

    document.getElementById('sUnitPrice').textContent  = formatIDR(data.unitPrice);
    document.getElementById('sTotalPrice').textContent = formatIDR(data.totalPrice);

    document.getElementById('sName').textContent  = data.name;
    document.getElementById('sPhone').textContent = data.phone;
    document.getElementById('sEmail').textContent = data.email;

    document.getElementById('confirmPaymentBtn').onclick = function () {

    const selectedPaymentMethod =
    document.querySelector('input[name="summary_payment_method"]:checked')?.value || 'CASH';
    const paymentMethodInput = form.querySelector('input[name="payment_method"]');
    if (paymentMethodInput) {
      paymentMethodInput.value = selectedPaymentMethod;
    }
      form.submit();
    };

    $('#summaryModal').modal('show');
  }

  document
    .querySelector('[data-flow="CASUAL"]')
    ?.addEventListener('click', () => openSummary('CASUAL'));

})();
</script>
<!-- above us is end of casual flow -->

<!-- member flow below -->
<script>
window.openMembershipSummary = function (form) {

  const planOpt = form.querySelector('#membershipPlan option:checked');
  if (!planOpt) return;

  const titleEl = document.querySelector('#summaryModal .modal-title');
  titleEl.textContent = 'Confirm Your Member Access';

  const data = {
    category:  form.querySelector('#targetType').value,
    package:   planOpt.textContent,
    quantity:  1,
    duration:  form.querySelector('#durationInput').value,
    unitPrice: planOpt.dataset.price,
    totalPrice: planOpt.dataset.price,
    name:      form.querySelector('input[name="name"]').value,
    phone:     form.querySelector('input[name="phone"]').value,
    email:     form.querySelector('input[name="email"]').value,
  };

  document.getElementById('sCategory').textContent = data.category;
  document.getElementById('sPackage').textContent  = data.package;
  document.getElementById('sQty').textContent      = data.quantity;
  document.getElementById('sDuration').textContent = data.duration;
  document.getElementById('sUnitPrice').textContent =
    Number(data.unitPrice).toLocaleString('id-ID');
  document.getElementById('sTotalPrice').textContent =
    Number(data.totalPrice).toLocaleString('id-ID');

  document.getElementById('sName').textContent  = data.name;
  document.getElementById('sPhone').textContent = data.phone;
  document.getElementById('sEmail').textContent = data.email;

  document.getElementById('confirmPaymentBtn').onclick = function (e) {  
    e.preventDefault();

    const selectedPaymentMethod =
    document.querySelector('input[name="summary_payment_method"]:checked')?.value || 'CASH';

    const paymentMethodInput = form.querySelector('input[name="payment_method"]');
 
    if (paymentMethodInput) {
      paymentMethodInput.value = selectedPaymentMethod;
    }
    console.log('Submitting form id:', form.id);
    console.log('Submitting form action:', form.action);
    
    form.submit(); // ✅ SATU-SATUNYA SUBMIT
  };

  $('#summaryModal').modal('show');
};
</script>

</body>
</html>