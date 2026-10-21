document.addEventListener('DOMContentLoaded', function () {

  const form = document.getElementById('adminMemberPassForm');
  const warningBox = document.getElementById('adminMemberWarning');

  if (!form) return;

  let isSubmitting = false;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (isSubmitting) return;
    isSubmitting = true;

    if (warningBox) {
      warningBox.classList.add('d-none');
    }

    const formData = new FormData(form);

    fetch('/admin/payment/member-pass/check-duplicate', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(res => {

      if (!res.ok) {
        warningBox.textContent =
          res.message || 'Email or phone already registered.';
        warningBox.classList.remove('d-none');
        isSubmitting = false;
        return;
      }

      // ✅ Lanjut submit asli
      form.submit();
    })
    .catch(() => {
      warningBox.textContent = 'Something went wrong.';
      warningBox.classList.remove('d-none');
      isSubmitting = false;
    });

  });

});