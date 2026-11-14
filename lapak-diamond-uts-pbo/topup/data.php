<?php
require_once __DIR__ . '/classes/TopupGame.php';

// Katalog: setiap baris = 1 INSTANSIASI objek (new TopupGame)
function buatKatalog(): array
{
    $katalog = [
        new TopupGame('ML-86',  'Diamond 86',   22000, 20, 'Mobile Legends', '86 Diamond', 0),
        new TopupGame('ML-172', 'Diamond 172',  44000, 15, 'Mobile Legends', '172 Diamond', 5),
        new TopupGame('FF-70',  'Diamond 70',   10000, 30, 'Free Fire',      '70 Diamond', 0),
        new TopupGame('FF-355', 'Diamond 355',  50000, 10, 'Free Fire',      '355 Diamond', 10),
        new TopupGame('GI-300', 'Genesis 300',  75000,  8, 'Genshin Impact', '300 Genesis Crystal', 0),
        new TopupGame('PUBG-60','UC 60',        15000, 25, 'PUBG Mobile',    '60 UC', 0),
    ];
    // stok terbaru disimpan di session
    foreach ($katalog as $p) {
        if (isset($_SESSION['stok'][$p->getKode()])) {
            $p->setStok($_SESSION['stok'][$p->getKode()]);
        }
    }
    return $katalog;
}
