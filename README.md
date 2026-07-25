<p align="center"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"> </p> <h1 align="center">Print Manager</h1> <p align="center"> Aplikasi manajemen printer berbasis Laravel untuk memonitor status printer dan level tinta secara real-time. </p> <p align="center"> <img src="https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white" alt="PHP Version"> <img src="https://img.shields.io/badge/Laravel-Framework-FF2D20?logo=laravel&logoColor=white" alt="Laravel"> <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"> </p>
Tentang Print Manager

Print Manager adalah aplikasi web yang dibangun di atas framework Laravel untuk membantu mengelola dan memantau printer dalam suatu organisasi atau jaringan kantor. Aplikasi ini memberikan visibilitas penuh terhadap kondisi printer secara terpusat, sehingga tim IT atau admin dapat mengambil tindakan sebelum masalah (seperti tinta habis atau printer offline) mengganggu operasional.

Fitur Utama
Monitoring Status Printer — Memantau status printer secara real-time (online/offline, error, siap digunakan).
Monitoring Level Tinta/Toner — Melacak sisa tinta atau toner pada setiap printer dan memberikan peringatan saat levelnya rendah.

Fitur tambahan seperti manajemen antrian cetak, laporan penggunaan, dan histori maintenance dapat dikembangkan lebih lanjut sesuai kebutuhan.

Teknologi yang Digunakan
Laravel — Framework PHP untuk backend aplikasi
Eloquent ORM — Untuk pengelolaan data printer dan status secara database-agnostic
Laravel Queue — Untuk memproses pengecekan status printer secara background (opsional)
