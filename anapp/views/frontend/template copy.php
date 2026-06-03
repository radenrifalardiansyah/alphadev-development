<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= FE_IMG_PATH ?>logo.png" rel="icon">
    <link href="<?= FE_IMG_PATH ?>logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= FE_VENDOR_PATH ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= FE_VENDOR_PATH ?>icofont/icofont.min.css" rel="stylesheet">
    <link href="<?= FE_VENDOR_PATH ?>boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= FE_VENDOR_PATH ?>venobox/venobox.css" rel="stylesheet">
    <link href="<?= FE_VENDOR_PATH ?>owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?= FE_VENDOR_PATH ?>aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= FE_CSS_PATH ?>style.css?ver=<?= CSS_VER_FRONT ?>" rel="stylesheet">

</head>

<body>

    <!-- ======= Top Bar ======= -->
    <div id="topbar" class="d-none d-lg-flex align-items-center fixed-top ">
        <div class="container d-flex">
            <div class="contact-info mr-auto">
                <i class="icofont-envelope"></i> <a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a>
                <i class="icofont-phone"></i> <?= COMPANY_PHONE ?>
            </div>
            <div class="social-links">
                <a href="#" class="twitter"><i class="icofont-twitter"></i></a>
                <a href="#" class="facebook"><i class="icofont-facebook"></i></a>
                <a href="#" class="instagram"><i class="icofont-instagram"></i></a>
                <a href="#" class="youtube"><i class="icofont-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">

            <!-- <h1 class="logo mr-auto"><a href="<?= base_url() ?>"><?= COMPANY_NAME ?></a></h1> -->
            <!-- Uncomment below if you prefer to use an image logo -->
            <a href="<?= base_url() ?>" class="logo mr-auto"><img src="<?= LOGO_IMG ?>" alt="" class="img-fluid"></a>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="<?= ($this->uri->uri_string() == '' ? 'active' : '') ?>"><a href="<?= base_url() ?>">Home</a></li>
                    <li class="nav-item dropdown" style="padding: 10px 0;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Company
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= base_url('about') ?>">About</a>
                            <a class="dropdown-item" href="<?= base_url('about') ?>">Mission and Vision</a>
                            <a class="dropdown-item" href="<?= base_url('team') ?>">Meet The team</a>
                        </div>
                    </li>
                    <li class="<?= ($this->uri->uri_string() == 'product' ? 'active' : '') ?>"><a href="<?= base_url('product') ?>">Product</a></li>
                    <li class="<?= ($this->uri->uri_string() == 'contact' ? 'active' : '') ?>"><a href="<?= base_url('contact') ?>">Contact</a></li>
                    <li class="<?= ($this->uri->uri_string() == 'tracking' ? 'active' : '') ?>"><a href="<?= base_url('tracking') ?>">Tracking</a></li>
                    <li><a href="<?= base_url('login') ?>">Login</a></li>

                </ul>
            </nav><!-- .nav-menu -->

        </div>
    </header><!-- End Header -->

    <?php $this->load->view(VIEW_FRONT . $main_content); ?>


    <div class="row" style="margin: 0 1px;">
        <div class="col-md-4 d-flex align-items-stretch nopadding" data-aos="fade-up">
            <img class="img-fluid" src="<?= FE_IMG_PATH ?>4251260.jpg">
            <div class="overlay"></div>
            <h4 class="text-overlay"><a href="javascript:;">Sed ut perspiciatis</a></h4>
        </div>

        <div class="col-md-4 d-flex align-items-stretch nopadding" data-aos="fade-up">
            <img class="img-fluid" src="<?= FE_IMG_PATH ?>4251289.jpg">
            <div class="overlay"></div>
            <h4 class="text-overlay"><a href="javascript:;">Sed ut perspiciatis</a></h4>
        </div>

        <div class="col-md-4 d-flex align-items-stretch nopadding" data-aos="fade-up">
            <img class="img-fluid" src="<?= FE_IMG_PATH ?>4251260.jpg">
            <div class="overlay"></div>
            <h4 class="text-overlay"><a href="javascript:;">Sed ut perspiciatis</a></h4>
        </div>
    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-4 col-md-6">
                        <div class="footer-info pt-0">
                            <h3><?= COMPANY_NAME ?></h3>
                            <p>
                                <?= COMPANY_ADDRESS ?>
                                <br>
                                <strong>Phone:</strong> <?= COMPANY_PHONE ?><br>
                                <strong>Email:</strong> <?= COMPANY_EMAIL ?><br>
                            </p>
                            <div class="social-links mt-3">
                                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                                <a href="#" class="youtube"><i class="bx bxl-youtube"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Product</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Pembayaran</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Bank BRI</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Bank BCA</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Bank Mandiri</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Bank BNI</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Bank Permata</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-newsletter">
                        <h4>Jam Kerja</h4>
                        <p>Senin – Sabtu
                            08.00 – 17.00 Wib
                            Minggu libur
                        </p>
                        <br>
                        <h4>Hubungi Kami</h4>
                        <p>Whatsapp : <?= COMPANY_PHONE ?> </p>
                        <form action="" method="post">
                            <input type="email" name="email"><input type="submit" value="Subscribe">
                        </form>

                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright 2021 <strong><span><?= COMPANY_NAME ?></span></strong>. All Rights Reserved
            </div>
        </div>
    </footer>

    <!-- ======= Top Bar ======= -->
    <div id="topbar" class="d-none d-lg-flex align-items-center" style="height: 50px;"></div>
    <!-- End Footer -->

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="<?= FE_VENDOR_PATH ?>jquery/jquery.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>jquery.easing/jquery.easing.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>php-email-form/validate.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>isotope-layout/isotope.pkgd.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>venobox/venobox.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>owl.carousel/owl.carousel.min.js"></script>
    <script src="<?= FE_VENDOR_PATH ?>aos/aos.js"></script>

    <!-- Template Main JS File -->
    <script src="<?= FE_JS_PATH ?>main.js?ver=<?= JS_VER_FRONT ?>"></script>

</body>

</html>