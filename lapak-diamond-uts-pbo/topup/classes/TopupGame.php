<?php
require_once __DIR__ . '/Produk.php';

// KELAS ANAK: TopupGame mewarisi Produk (INHERITANCE)
class TopupGame extends Produk
{
    // 5 atribut milik kelas anak
    private string $game;
    private string $nominal;
    private int $diskonPersen;
    private string $idPemain = '';
    private string $server = '';

    public function __construct(
        string $kode, string $nama, int $harga, int $stok,
        string $game, string $nominal, int $diskonPersen = 0
    ) {
        parent::__construct($kode, $nama, $harga, $stok, 'Top-up Game');
        $this->game = $game;
        $this->nominal = $nominal;
        $this->diskonPersen = $diskonPersen;
    }

    public function getGame(): string { return $this->game; }
    public function getNominal(): string { return $this->nominal; }
    public function getDiskonPersen(): int { return $this->diskonPersen; }
    public function getIdPemain(): string { return $this->idPemain; }
    public function getServer(): string { return $this->server; }

    // Method 1: isi data pemain
    public function setPemain(string $id, string $server): void
    {
        $this->idPemain = trim($id);
        $this->server = trim($server);
    }

    // Method 2: validasi ID pemain (6-12 angka)
    public function validasiID(): bool
    {
        return (bool) preg_match('/^\d{6,12}$/', $this->idPemain);
    }

    // Method 3: override hitungTotal (POLIMORFISME) -> ada diskon
    public function hitungTotal(int $jumlah): int
    {
        $kotor = parent::hitungTotal($jumlah);
        return (int) round($kotor - ($kotor * $this->diskonPersen / 100));
    }

    // Method 4: label diskon untuk tampilan
    public function labelDiskon(): string
    {
        return $this->diskonPersen > 0 ? "Diskon {$this->diskonPersen}%" : 'Harga normal';
    }

    // Method 5: susun data struk
    public function buatStruk(int $jumlah): array
    {
        return [
            'no'       => 'TRX' . date('ymdHis'),
            'waktu'    => date('d/m/Y H:i'),
            'game'     => $this->game,
            'item'     => $this->nominal,
            'id'       => $this->idPemain,
            'server'   => $this->server,
            'jumlah'   => $jumlah,
            'total'    => $this->hitungTotal($jumlah),
            'diskon'   => $this->labelDiskon(),
        ];
    }
}
