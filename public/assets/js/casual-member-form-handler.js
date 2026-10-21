
/* =========================================================
   MEMBER FLOW (PRECHECK + MODAL + SUBMIT)
   ========================================================= */

(function () {

  let isSubmitting = false;

  // ✅ helper validasi format
  function isValidEmail(email) {
    if (!email) return false;

    // trim spasi di awal/akhir
    email = email.trim();

    // regex lebih ketat (tidak terlalu longgar tapi masih praktis)
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    return regex.test(email);
  }

  function isValidPhone(phone) {
    if (!phone) return false;

    // hapus semua selain angka
    let clean = phone.replace(/\D/g, '');

    // handle format Indonesia:
    // 08xxxx -> 628xxxx
    if (clean.startsWith('0')) {
        clean = '62' + clean.slice(1);
    } else if (!clean.startsWith('62')) {
        return false;
    }

    // validasi:
    // - hanya angka
    // - panjang 10–15 digit
    // - harus diawali 62 (Indonesia)
    const regex = /^62[0-9]{8,13}$/;

    return regex.test(clean);
  }

  // ✅ RESET LOCK SAAT MODAL DITUTUP (BUG #1 FIX)
  document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery) {
      $('#summaryModal').on('hidden.bs.modal', function () {
        isSubmitting = false;
      });
    }
  });

  // ✅ SATU-SATUNYA HANDLER CLICK
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-flow="MEMBERSHIP"]');
    if (!btn) return;

    e.preventDefault();
    if (isSubmitting) return;

    const form = document.querySelector('.js-form-member');
    if (!form) return;

    const warningBox = document.getElementById('memberFormWarning');
    if (!warningBox) return;

    // ✅ ambil nilai
    const targetType = form.querySelector('#targetType')?.value.trim();
    const plan       = form.querySelector('#membershipPlan')?.value.trim();
    const gender     = form.querySelector('select[name="gender"]')?.value.trim();
    const name       = form.querySelector('input[name="name"]')?.value.trim();
    const phone      = form.querySelector('input[name="phone"]')?.value.trim();
    const email      = form.querySelector('input[name="email"]')?.value.trim();

    // ✅ VALIDASI KOSONG (GAYA CASUAL)
    if (!targetType || !plan || !gender || !name || !phone || !email) {
      warningBox.textContent =
        'Please complete all required fields before continuing.';
      warningBox.classList.remove('d-none');
      return;
    }

    // ✅ VALIDASI FORMAT EMAIL
    if (!isValidEmail(email)) {
      warningBox.textContent = 'Please enter a valid email address.';
      warningBox.classList.remove('d-none');
      return;
    }

    // ✅ VALIDASI FORMAT PHONE
    if (!isValidPhone(phone)) {
      warningBox.textContent = 'Please enter a valid phone number.';
      warningBox.classList.remove('d-none');
      return;
    }

    // ✅ SEMUA VALID → SEMBUNYIKAN WARNING
    warningBox.classList.add('d-none');

    // ✅ LOCK
    isSubmitting = true;

    // ✅ DUPLICATE PRECHECK
    const formData = new FormData(form);

    fetch('/membership/check-duplicate', {
      method: 'POST',
      body: formData
    })
    .then(r => {
      const type = r.headers.get('content-type') || '';
      if (!type.includes('application/json')) {
        throw new Error('Invalid response');
      }
      return r.json();
    })
    .then(res => {
      if (!res.ok) {
        warningBox.textContent =
          res.message || 'Email or phone already registered.';
        warningBox.classList.remove('d-none');
        isSubmitting = false;
        return;
      }

      // ✅ SUKSES → BUKA SUMMARY MODAL
      window.openMembershipSummary(form);
    })
    .catch(() => {
      warningBox.textContent =
        'Something went wrong. Please try again.';
      warningBox.classList.remove('d-none');
      isSubmitting = false;
    });
  });

})();



/* =========================================================
   UTIL: HARD RESET FORM + NICE SELECT
   ========================================================= */
function hardReset(formWrapperId, selectIds = []) {

    const wrapper = document.getElementById(formWrapperId);
    if (!wrapper) return;

    const form = wrapper.querySelector('form');
    if (form) form.reset();

    selectIds.forEach(id => {
        const $sel = $('#' + id);
        if (!$sel.length) return;

        // paksa ke placeholder (index 0)
        $sel.prop('selectedIndex', 0);

        // rebuild nice select dari kondisi bersih
        $sel.niceSelect('destroy');
        $sel.find('option').prop('disabled', false);
        $sel.niceSelect();
    });
}

/* =========================================================
   TAB SWITCH (SATU-SATUNYA PINTU PERPINDAHAN)
   ========================================================= */
function showForm(type, el) {

    // aktifkan tab
    document.querySelectorAll('.nav-link')
        .forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    // toggle form
    document.getElementById('memberForm').style.display =
        type === 'member' ? 'block' : 'none';

    document.getElementById('casualForm').style.display =
        type === 'casual' ? 'block' : 'none';

    // HARD RESET KEDUA FORM (tidak peduli arah pindah)
    hardReset('memberForm', ['targetType', 'membershipPlan']);
    hardReset('casualForm', ['casualCategory', 'casualPackage']);

    // reset field hitungan
    $('#durationInput, #priceInput').val('');
    $('#quantityInput').val(1);
    $('#totalDurationInput, #totalPriceInput').val('');
}

/* =========================================================
   DOCUMENT READY
   ========================================================= */
$(function () {

    /* ---------------- MEMBER LOGIC ---------------- */
    const $memberTarget = $('#targetType');
    const $memberPlan   = $('#membershipPlan');

    
  // ✅ FIX: native change event untuk CI (tanpa nice-select)
      document.getElementById('membershipPlan')
          .addEventListener('change', function () {
              const opt = this.selectedOptions[0];
              document.getElementById('durationInput').value =
                  opt?.dataset?.duration || '';
              document.getElementById('priceInput').value =
                  opt?.dataset?.price || '';
          });


    function rebuildMember(target) {

        $memberPlan.niceSelect('destroy');

        $memberPlan.find('option').each(function () {
            const type = $(this).data('type');
            if (!type) return;

            const ok = (type === target || type === 'ALL');
            $(this).prop('disabled', !ok);
        });

        $memberPlan.prop('selectedIndex', 0).niceSelect();
        $('#durationInput, #priceInput').val('');
    }

    $memberTarget.on('change', function () {
        rebuildMember(this.value);
    });

    $memberPlan.on('change', function () {
        const $opt = $memberPlan.find(':selected');
        $('#durationInput').val($opt.data('duration') || '');
        $('#priceInput').val($opt.data('price') || '');
    });

    /* ---------------- CASUAL LOGIC ---------------- */
    const $casualTarget = $('#casualCategory');
    const $casualPkg    = $('#casualPackage');
    const $qty          = $('#quantityInput');

    function rebuildCasual(target) {

        $casualPkg.niceSelect('destroy');

        $casualPkg.find('option').each(function () {
            const t = $(this).data('target');
            if (!t) return;

            const ok = (t === target || t === 'ALL');
            $(this).prop('disabled', !ok);
        });

        $casualPkg.prop('selectedIndex', 0).niceSelect();
        $('#totalDurationInput, #totalPriceInput').val('');
    }

    function recalcCasual() {
        const $opt = $casualPkg.find(':selected');
        const q = Number($qty.val() || 1);
        const d = Number($opt.data('duration') || 0);
        const p = Number($opt.data('price') || 0);

        $('#totalDurationInput').val(d * q);
        $('#totalPriceInput').val(p * q);
    }

    $casualTarget.on('change', function () {
        rebuildCasual(this.value);
    });

    $casualPkg.on('change', recalcCasual);
    $qty.on('input', recalcCasual);
});
