<?php
// KELAS INDUK: Produk (5 atribut, 4+ method)
class Produk
{
    // ENKAPSULASI: atribut private, diakses lewat getter/setter
    private string $kode;
    private string $nama;
    private int $harga;
    private int $stok;
    private string $kategori;

    public function __construct(string $kode, string $nama, int $harga, int $stok, string $kategori)
    {
        $this->kode = $kode;
        $this->nama = $nama;
        $this->setHarga($harga);
        $this->setStok($stok);
        $this->kategori = $kategori;
    }

    public function getKode(): string { return $this->kode; }
    public function getNama(): string { return $this->nama; }
    public function getHarga(): int { return $this->harga; }
    public function getStok(): int { return $this->stok; }
    public function getKategori(): string { return $this->kategori; }

    public function setHarga(int $harga): void
    {
        $this->harga = max(0, $harga); // harga tidak boleh negatif
    }

    public function setStok(int $stok): void
    {
        $this->stok = max(0, $stok);
    }

    // Method 1: cek apakah stok cukup
    public function cekStok(int $jumlah): bool
    {
        return $jumlah > 0 && $this->stok >= $jumlah;
    }

    // Method 2: kurangi stok setelah transaksi
    public function kurangiStok(int $jumlah): void
    {
        if (!$this->cekStok($jumlah)) {
            throw new Exception("Stok {$this->nama} tidak cukup.");
        }
        $this->stok -= $jumlah;
    }

    // Method 3: hitung total harga (akan di-override oleh kelas anak)
    public function hitungTotal(int $jumlah): int
    {
        return $this->harga * $jumlah;
    }

    // Method 4: tampilkan info singkat produk
    public function tampilInfo(): string
    {
        return "{$this->kode} - {$this->nama} ({$this->kategori}), stok {$this->stok}";
    }

    public static function rupiah(int $angka): string
    {
        return 'Rp' . number_format($angka, 0, ',', '.');
    }
}
