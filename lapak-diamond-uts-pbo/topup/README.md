# Lapak Diamond - Kasir Top-up (UTS PBO)
Jalankan: `php -S localhost:8000` di folder ini, buka http://localhost:8000
Atau taruh di htdocs XAMPP. Tanpa database (data stok & riwayat pakai session).

- classes/Produk.php    : kelas induk (5 atribut, enkapsulasi private + getter/setter)
- classes/TopupGame.php : kelas anak (extends Produk, 5 atribut, override hitungTotal)
- data.php              : instansiasi objek (new TopupGame)
- index.php             : tampilan + alur transaksi
