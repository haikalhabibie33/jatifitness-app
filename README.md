==== Cara Execute Program Aplikasi====

==== Setup IP address di ENV ===
1. Buka terminal di folder project (gym-app)
2. Ketik `ipconfig` untuk dapatkan IP address local, paste kan ke `app.baseURL` di dalam file `.env` dan simpan perubahan

==== Mengaktifkan database lewat docker dan run aplikasi ===
1. Install docker windows di local 
2. Buka terminal Ubuntu (WSL) folder project (gym-app) 
3. CD ke project dengan cara `cd /mnt/d` (drive D, isi `cd /mnt/c` apabila di drive c) kemudian arahkan ke direktori app nya
4. Jalankan command database: `docker compose up -d` (sebelumnya pernah command `sudo usermod -aG docker $USER` supaya bisa run docker tanpa sudo)
5. Kemudian jalankan aplikasi: `php spark serve --host 0.0.0.0`

==== Membuka aplikasi di browser ===
Buka browser: http://ip-address-from-ipconfig:8080

==== Membuka halaman login ===
1. Ketik URL di browser `http://ip-address-from-ipconfig:8080/login`
2. Login menggunakan data dari `app/Database/Seeds/json/non-pages/users.json`. 
Terdapat 2 data user untuk testing. Gunakan username dan password nya.
