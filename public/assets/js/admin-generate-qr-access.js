(function () {

  const token = window.ACCESS_TOKEN;
  const qrContainer = document.getElementById('qrCode');

  if (!token || !qrContainer) return;

  
  // ✅ DETECT SCREEN WIDTH
  const isMobile = window.innerWidth < 576;

  const size = isMobile ? 250 : 400; // 🔥 INI KUNCI

  new QRCode(qrContainer, {
    text: token,
    width: size,
    height: size
  });


  // ✅ DOWNLOAD FULL CARD
  const btn = document.getElementById('downloadQr');

  btn?.addEventListener('click', () => {

    const card = document.getElementById('qrCard');
    if (!card) return;

    html2canvas(card, {
      scale: 2 // 🔥 biar HD
    }).then(canvas => {

      const link = document.createElement('a');
      link.href = canvas.toDataURL('image/png');
      link.download = 'access-card.png';
      link.click();

    });

  });

})();
