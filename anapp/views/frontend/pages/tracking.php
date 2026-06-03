<!-- ======= Hero Section ======= -->
<section id="hero" class="pages d-flex align-items-center">
    <div class="container position-relative" data-aos="fade-up" data-aos-delay="500">
        <h1>Tracking</h1>
        <h2>Welcome to <?= COMPANY_NAME ?></h2>
    </div>
</section>

<!-- ======= Team Section ======= -->
<section id="start" class="team">
    <div class="container">

        <div class="section-title">
            <span>Invoice</span>
            <h2>Invoice</h2>
            <p>Sit sint consectetur velit quisquam cupiditate impedit suscipit alias</p>
        </div>

        <div class="row mb-5">
            <div class="col-lg-12 col-md-12 d-flex align-items-stretch tracking-invoice" data-aos="zoom-in">
                <form action="<?= base_url('shopping/getshoporderdetailtrack') ?>" method="post" id="track-order-invoice">
                    <input type="text" name="invoice-number" id="invoice-number" placeholder="Input the Invoice Number...">
                    <input type="button" value="Cek Invoice" class="btn-order-detail">
                </form>
            </div>
            <div class="col-lg-12 col-md-12 d-flex align-items-stretch">
                <div id="invoice-detail" style="display: none;">

                </div>
            </div>
        </div>

        <div class="section-title">
            <span>RESI</span>
            <h2>RESI</h2>
            <p>Anda bisa lacak/cek resi paket pengirman barang kamu yang dikirim melalui kurir <br> JNE, J&T, POS, TIKI, Wahana, SiCepat & Lion parcel</p>
        </div>

        <div class="fixed-iframe">
            <iframe scrolling="no" src="https://pluginongkoskirim.com/cek-resi/"></iframe>
        </div>

    </div>
</section><!-- End Team Section -->