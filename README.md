<p align="center"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"> </p> <h1 align="center">Print Manager</h1> <p align="center"> Aplikasi manajemen printer berbasis Laravel untuk memantau kondisi printer, menganalisis data penggunaan, dan mempercepat pembahasan printer pada weekly meeting IT. </p> <p align="center"> <img src="https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white" alt="PHP Version"> <img src="https://img.shields.io/badge/Laravel-Framework-FF2D20?logo=laravel&logoColor=white" alt="Laravel"> <img src="https://img.shields.io/badge/SQLite-Database-003B57?logo=sqlite&logoColor=white" alt="SQLite"> <img src="https://img.shields.io/badge/TailwindCSS-UI-38BDF8?logo=tailwindcss&logoColor=white" alt="Tailwind CSS"> <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"> </p>
Tentang Print Manager

Print Manager adalah aplikasi web berbasis Laravel yang dibangun untuk membantu tim IT memantau kondisi printer di seluruh organisasi tanpa perlu mengecek satu per satu secara manual. Data printer diimpor dari file Excel, lalu diolah menjadi ringkasan visual yang langsung menyorot printer-printer yang butuh perhatian.

Tujuan utama aplikasi ini adalah mempersingkat waktu pembahasan printer pada weekly meeting IT — tim tidak perlu lagi membahas satu per satu seluruh printer, cukup fokus ke printer yang benar-benar bermasalah, lengkap dengan data pembanding antar periode.

Fitur Utama
🔴 Highlight Printer Bermasalah

Menyoroti otomatis printer yang butuh perhatian lebih, seperti:

Status printer offline
Stok toner/tinta warna di bawah 15%
📥 Import Data via Excel

Data kondisi printer diambil langsung dari file Excel (.xlsx), tanpa perlu input manual satu per satu.

🔁 Perbandingan Data Antar Periode

Membandingkan 2 data hasil import dari periode yang berbeda, sehingga terlihat jelas tren perubahan status dan stok toner dari waktu ke waktu.

📊 Analisis Deskriptif

Menyajikan hasil analisis deskriptif dari data printer (misalnya ringkasan status, distribusi level toner, dsb.) untuk mendukung pengambilan keputusan saat meeting.

Teknologi yang Digunakan
Teknologi	Fungsi
Laravel	Framework backend utama
SQLite	Database aplikasi
Tailwind CSS	Styling & UI
PhpSpreadsheet	Membaca & memproses file Excel

Alur Penggunaan Singkat
Import file Excel data printer melalui halaman upload.
Sistem otomatis menyoroti printer dengan status offline atau stok toner warna < 15%.
Untuk melihat perkembangan, import data periode berikutnya lalu gunakan fitur perbandingan periode.
Cek halaman analisis deskriptif untuk ringkasan kondisi printer secara keseluruhan sebelum weekly meeting IT.
Kontribusi

Kontribusi untuk pengembangan Print Manager sangat terbuka. Silakan buat pull request atau laporkan issue jika menemukan bug atau ingin mengusulkan fitur baru.

Keamanan

Jika Anda menemukan celah keamanan pada aplikasi ini, mohon laporkan melalui email pengembang, bukan melalui public issue tracker.

Pengembang

Ghazy Fadhilah Abid NIM 3312301073 Program Studi D3 Teknik Informatika
