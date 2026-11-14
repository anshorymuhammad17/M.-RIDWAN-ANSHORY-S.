<?php
require_once __DIR__ . '/data.php';
session_start();
$katalog = buatKatalog();
$struk = null; $error = null;

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'] ?? '';
    $jumlah = (int) ($_POST['jumlah'] ?? 1);
    $produk = null;
    foreach ($katalog as $p) { if ($p->getKode() === $kode) $produk = $p; }

    try {
        if (!$produk) throw new Exception('Pilih paket dulu.');
        $produk->setPemain($_POST['id'] ?? '', $_POST['server'] ?? '');
        if (!$produk->validasiID()) throw new Exception('ID pemain harus 6-12 angka.');
        $produk->kurangiStok($jumlah);
        $_SESSION['stok'][$kode] = $produk->getStok();
        $struk = $produk->buatStruk($jumlah);
        $_SESSION['riwayat'][] = $struk;
    } catch (Exception $ex) {
        $error = $ex->getMessage();
    }
}
$riwayat = array_reverse($_SESSION['riwayat'] ?? []);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lapak Diamond PT HERU - Kasir Top-up</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="top">
  <h1>Lapak Diamond PT HERU </h1>
  <p>Kasir top-up game. Pilih paket, isi ID, struk langsung jadi.</p>
</header>

<main class="grid">
  <section class="papan" aria-label="Daftar harga">
    <h2>Daftar harga</h2>
    <?php foreach ($katalog as $p): ?>
      <label class="baris <?= $p->getStok() === 0 ? 'habis' : '' ?>">
        <input type="radio" name="kode" value="<?= e($p->getKode()) ?>" form="order" <?= $p->getStok() === 0 ? 'disabled' : '' ?> required>
        <span class="game"><?= e($p->getGame()) ?></span>
        <span class="item"><?= e($p->getNominal()) ?></span>
        <span class="harga"><?= Produk::rupiah($p->hitungTotal(1)) ?>
          <?php if ($p->getDiskonPersen() > 0): ?><small class="coret"><?= Produk::rupiah($p->getHarga()) ?></small><?php endif; ?>
        </span>
        <span class="stok"><?= $p->getStok() === 0 ? 'Habis' : 'Sisa ' . $p->getStok() ?></span>
      </label>
    <?php endforeach; ?>
  </section>

  <section class="panel">
    <form id="order" method="post">
      <h2>Data pemain</h2>
      <?php if ($error): ?><p class="pesan salah"><?= e($error) ?></p><?php endif; ?>
      <label>ID pemain <input name="id" inputmode="numeric" placeholder="contoh: 123456789" required></label>
      <label>Server / Zona <input name="server" placeholder="contoh: 2041"></label>
      <label>Jumlah <input type="number" name="jumlah" value="1" min="1" max="10"></label>
      <button type="submit">Bayar sekarang</button>
    </form>

    <?php if ($struk): ?>
    <article class="struk">
      <h3>Transaksi berhasil</h3>
      <dl>
        <dt>No</dt><dd><?= e($struk['no']) ?></dd>
        <dt>Game</dt><dd><?= e($struk['game']) ?></dd>
        <dt>Item</dt><dd><?= e($struk['item']) ?> x<?= $struk['jumlah'] ?></dd>
        <dt>ID</dt><dd><?= e($struk['id']) ?> (<?= e($struk['server'] ?: '-') ?>)</dd>
        <dt>Promo</dt><dd><?= e($struk['diskon']) ?></dd>
        <dt>Total</dt><dd class="total"><?= Produk::rupiah($struk['total']) ?></dd>
      </dl>
    </article>
    <?php endif; ?>
  </section>
</main>

<?php if ($riwayat): ?>
<section class="riwayat">
  <h2>Riwayat transaksi</h2>
  <ul>
  <?php foreach ($riwayat as $r): ?>
    <li><span><?= e($r['waktu']) ?></span> <?= e($r['game']) ?> - <?= e($r['item']) ?> x<?= $r['jumlah'] ?> <b><?= Produk::rupiah($r['total']) ?></b></li>
  <?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>
</body>
</html>
