<div class="header bg-gradient-info pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_faq') ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body wrapper-setting-general">
                    <div class="accordion" id="accordionFAQ">
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ1" data-toggle="collapse" data-target="#FAQ1" aria-expanded="false" aria-controls="FAQ1">
                                <h5 class="text-white mb-0">Apa itu BONUS PENJUALAN ?</h5>
                            </div>
                            <div id="FAQ1" class="collapse show" aria-labelledby="headFAQ1" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    BONUS PENJUALAN adalah bonus yang didapatkan dari Personal RO (Pembelian reseller pribadi atau pembelian konsumen)<br />
                                    Pembelian oleh Konsumen, akan dianggap RO reseller yang memiliki link Affiliate<br />
                                    Misal, Andika membeli produk dari link reseller Budi. Maka, Budi mendapat 25% dari BV pembelanjaan Andika<br /><br />
                                    <strong>Rumus:</strong> 25% x Total BV Pembelanjaan (RO)<br />
                                    <strong>Peiode:</strong> Realtime<br />
                                    <strong>Syarat:</strong> Personal RO
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ2" data-toggle="collapse" data-target="#FAQ2" aria-expanded="false" aria-controls="FAQ2">
                                <h5 class="text-white mb-0">Apa itu BONUS REFERRAL ?</h5>
                            </div>
                            <div id="FAQ2" class="collapse" aria-labelledby="headFAQ2" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    BONUS REFERRAL adalah bonus yang didapatkan dari sponsorisasi Reseller Downline<br />
                                    Berlaku PassUp<br />
                                    Misal, Reseller Star1 rekrut Reseller Star4, Bonus Referral 18%, maka ada PassUp 7%<br /><br />
                                    <strong>Rumus:</strong><br />
                                    Berdasarkan Paket Anda sebagai sponsor (Persentase Paket Anda x Total Pembelanjaan Reseller Downline)
                                    <ul class="mb-0">
                                        <li>Paket Reseller Star 1 : 18%</li>
                                        <li>Paket Reseller Star 2 : 20%</li>
                                        <li>Paket Reseller Star 3 : 22%</li>
                                        <li>Paket Reseller Star 4 : 25%</li>
                                    </ul>
                                    <strong>Peiode:</strong> Realtime<br />
                                    <strong>Syarat:</strong> Rekrut Reseller
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ3" data-toggle="collapse" data-target="#FAQ3" aria-expanded="false" aria-controls="FAQ3">
                                <h5 class="text-white mb-0">Apa itu BONUS PAIRING ?</h5>
                            </div>
                            <div id="FAQ3" class="collapse" aria-labelledby="headFAQ3" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    BONUS PAIRING adalah bonus yang di dapatkan dari poin pairing Pohon Jaringan Anda (Kiri:Kanan)<br />
                                    6 Pair pertama, dihitung dan diconvert ke poin EMAS (API ke pihak ke-3)<br />
                                    Setelah 6 pair pertama tercapai, selanjutnya wajib min 6 poin/minggu tersebut. Jika kurang, misal hanya 5 pair, tidak dihitung dan hangus<br />
                                    <span class="text-danger">Jika tidah ada RO minggu tersebut, maka akan dikenakan Max Pairing</span><br /><br />
                                    Contoh Perhitungan Pair:<br />
                                    <ul class="mb-0">
                                        <li>
                                            Anda memiliki 100 : 50 dan Minggu tersebut RO<br />
                                            Maka 10:10 dihitung sebagai Pair-1 dan sisanya 40:40 dihitung sebagai Pair-2<br />
                                            Sisa 50:0 Carry Forward
                                        </li>
                                        <li>
                                            Anda memiliki 100 : 50 dan Minggu tersebut tidak RO<br />
                                            Maka 10:10 dihitung sebagai Pair-1. Tidak ada Pair-2<br />
                                            Sisa 50:0 Carry Forward
                                        </li>
                                    </ul><br />
                                    <strong>Rumus:</strong><br />
                                    Berdasarkan Paket Anda (Nominal Pair x Total Poin Pair)<br />
                                    - Pair-1 Nominal Rp 10.000<br />
                                    - Pair-2 Nominal Rp 20.000 (<span class="text-danger">*</span>)<br />
                                    Detail Paket<br />
                                    <ul class="mb-0">
                                        <li>Paket Reseller Star 1 : Tidak Ada Bonus Pairing</li>
                                        <li>Paket Reseller Star 2 : Pair-1 => 10 Poin/Minggu | Pair-2 => 200 Poin/Minggu</li>
                                        <li>Paket Reseller Star 3 : Pair-1 => 10 Poin/Minggu | Pair-2 => 350 Poin/Minggu</li>
                                        <li>Paket Reseller Star 4 : Pair-1 => 10 Poin/Minggu | Pair-2 => 500 Poin/Minggu</li>
                                    </ul>
                                    <small><span class="text-danger">* Pair-2 bersyarat, yaitu Anda wajib RO Minggu tersebut</span></small><br />
                                    <strong>Peiode:</strong> Mingguan<br />
                                    <strong>Syarat:</strong> Flush jika tidak RO Minggu tersebut
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ4" data-toggle="collapse" data-target="#FAQ4" aria-expanded="false" aria-controls="FAQ4">
                                <h5 class="text-white mb-0">Apa itu KOMISI GROUP RO ?</h5>
                            </div>
                            <div id="FAQ4" class="collapse" aria-labelledby="headFAQ4" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    KOMISI GROUP RO adalah komisi yang didapatkan dari aktifitas RO Group Anda<br /><br />
                                    <strong>Rumus:</strong><br />
                                    Berdasarkan Paket Anda (5 Gen x Persentase Paket Anda)<br />
                                    <ul class="mb-0">
                                        <li>Paket Reseller Star 1 : 5 Gen x 2%</li>
                                        <li>Paket Reseller Star 2 : 5 Gen x 3%</li>
                                        <li>Paket Reseller Star 3 : 5 Gen x 4%</li>
                                        <li>Paket Reseller Star 4 : 5 Gen x 5%</li>
                                    </ul>
                                    <strong>Peiode:</strong> Bulanan<br />
                                    <strong>Syarat:</strong> RO bulan tersebut min 300 BV
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ5" data-toggle="collapse" data-target="#FAQ5" aria-expanded="false" aria-controls="FAQ5">
                                <h5 class="text-white mb-0">Apa itu KOMISI SEASONAL ?</h5>
                            </div>
                            <div id="FAQ5" class="collapse" aria-labelledby="headFAQ5" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    KOMISI SEASONAL adalah komisi yang didapatkan dari aktifitas RO Nasional dan Reseller Qualified<br /><br />
                                    <strong>Rumus:</strong> 7% x Omset RO Nasional / Reseller Qualified<br />
                                    <strong>Peiode:</strong> 3 Bulanan<br />
                                    <strong>Syarat:</strong> RO bulan tersebut min 1.000 BV
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFAQ6" data-toggle="collapse" data-target="#FAQ6" aria-expanded="false" aria-controls="FAQ6">
                                <h5 class="text-white mb-0">Apa itu KOMISI PARTNER ?</h5>
                            </div>
                            <div id="FAQ6" class="collapse" aria-labelledby="headFAQ6" data-parent="#accordionFAQ">
                                <div class="card-body">
                                    KOMISI PARTNER adalah komisi yang didapatkan dari aktifitas RO Nasional dan Partner Qualified<br /><br />
                                    <strong>Rumus:</strong> 3% x Omset RO Nasional / Reseller Qualified<br />
                                    <strong>Peiode:</strong> 6 Bulanan<br />
                                    <strong>Syarat:</strong>
                                    <ul class="mb-0">
                                        <li>Mencapai 50,000 Pairing akumulasi</li>
                                        <li>RO bulan tersebut min 1.000 BV</li>
                                        <li>Minimal Rekrut 3 Reseller</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>