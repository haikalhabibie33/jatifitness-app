let qrScanner = null;

function startScanner() {
    const wrapper = document.getElementById('scanner-wrapper');
    const reader = document.getElementById('qr-reader');

    if (!wrapper || !reader) return;

    wrapper.classList.remove('d-none');

    if (qrScanner) return;

    qrScanner = new Html5Qrcode("qr-reader");

    qrScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        (decodedText) => handleScan(decodedText)
    );
}

function stopScanner() {
    if (!qrScanner) return;

    qrScanner.stop()
        .then(() => {
            qrScanner.clear();
            qrScanner = null;
        });

    document.getElementById('scanner-wrapper').classList.add('d-none');
}

function handleScan(result) {
    stopScanner(); // ✅ langsung matikan kamera

    let token = result;

    try {
        if (result.includes("token=")) {
            const url = new URL(result);
            token = url.searchParams.get("token");
        }
    } catch (e) {}

    const input = document.querySelector('input[name="token"]');

    if (input) {
        input.value = token;
        input.form.submit();
    }
}

// ✅ auto kill kalau pindah halaman
window.addEventListener('beforeunload', function () {
    if (qrScanner) {
        qrScanner.stop();
    }
});