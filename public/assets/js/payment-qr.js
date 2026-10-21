let qrScanner = null;
let isScanning = false;

window.startScanner = function () {

    const reader = document.getElementById("qr-reader");

    if (!reader) {
        console.error("QR reader not found ❌");
        return;
    }

    if (isScanning) {
        console.warn("Already scanning ⚠️");
        return;
    }

    reader.style.display = "block";

    if (!qrScanner) {
        qrScanner = new Html5Qrcode("qr-reader");
    }

    qrScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },

        (decodedText) => {

            console.log("QR RESULT:", decodedText);

            // ✅ isi token
            const input = document.getElementById('tokenInput');

            let value = decodedText;

            try {
                // ✅ kalau URL, extract token
                if (value.startsWith("http")) {
                    const url = new URL(value);
                    const token = url.searchParams.get("token");

                    if (token) {
                        value = token;
                    }
                }
            } catch (e) {
                console.warn("Bukan URL, pakai langsung ✅");
            }

            if (input) {
                input.value = value;
            }

            stopScanner();

            // ✅ auto submit
            const form = document.getElementById('tokenSearchForm');

            if (form) {
                setTimeout(() => form.submit(), 200);
            }
        }

    ).then(() => {
        isScanning = true;
        console.log("CAMERA STARTED ✅");
    }).catch(err => {
        console.error("CAMERA ERROR ❌", err);
        alert("Camera error: " + err);
    });
};

window.stopScanner = function () {

    if (qrScanner && isScanning) {
        qrScanner.stop().then(() => {
            console.log("Scanner stopped ✅");
        }).catch(console.warn);
    }

    isScanning = false;

    const reader = document.getElementById("qr-reader");
    if (reader) {
        reader.style.display = "none";
    }
};
