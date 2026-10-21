(function () {
  const inputs = document.querySelectorAll('.otp-box');
  const hidden = document.getElementById('otp_value');

  const syncHidden = () => hidden && (hidden.value = [...inputs].map(i => i.value).join(''));

  inputs.forEach((inp, i) => {
    // 1) Ketik: terima hanya angka & otomatis maju
    inp.addEventListener('input', e => {
      const v = e.target.value.replace(/\D/g,'').slice(0,1); // hanya 1 digit angka
      e.target.value = v;
      if (v && i < inputs.length - 1) inputs[i + 1].focus(); // --> pindah ke field berikutnya
      syncHidden();
    });

    // 2) Backspace di field kosong: mundur
    inp.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !inp.value && i > 0) inputs[i - 1].focus();
      if (e.key === 'ArrowLeft'  && i > 0) inputs[i - 1].focus();
      if (e.key === 'ArrowRight' && i < inputs.length - 1) inputs[i + 1].focus();
    });

    // 3) Paste: "123456" disebar ke semua kotak
    inp.addEventListener('paste', e => {
      const s = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
      if (!s) return;
      e.preventDefault();
      [...s].slice(0, inputs.length).forEach((ch, j) => inputs[j].value = ch);
      inputs[Math.min(s.length, inputs.length - 1)].focus();
      syncHidden();
    });
  });
})();