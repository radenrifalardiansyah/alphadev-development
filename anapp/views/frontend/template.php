<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8" />

    <title><?= $title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="theme-color" content="#4d2989" />

    <meta name="robots" content="" />
    <meta name="copyright" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="index,follow" />
    <meta name="googlebot" content="" />


    <meta name="viewport" content="width=device-width" />
    <meta name="format-detection" content="telephone=no" />

    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta property="og:description" , content="" />
    <meta property="og:site_name" , content="Official Site of <?= COMPANY_NAME ?>" />
    <meta content="<?= LOGO_IMG ?>" name="og:image">
    <meta property="og:title" , content="<?= COMPANY_NAME ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= base_url() ?>">
    <meta property="og:title" content="<?= COMPANY_NAME ?>">
    <meta property="og:description" content="">
    <meta property="og:image" content="<?= LOGO_IMG ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= base_url() ?>">
    <meta property="twitter:title" content="<?= COMPANY_NAME ?>">
    <meta property="twitter:description" content="">
    <meta property="twitter:image" content="<?= LOGO_IMG ?>">

    <link rel="icon" type="image/x-icon" href="<?= FAVICON ?>" />
    <link media="all" rel="stylesheet" href="<?= FE_CSS_PATH ?>app.css?ver=<?= CSS_VER_FRONT ?>" />
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&amp;display=swap" rel="stylesheet">

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '12345678910');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=12345678910&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

</head>

<body>
    <div class="fixed top-0 w-full z-40">
        <nav x-data="{ open: false }" class="h-12 bg-lime-900">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-12">
                    <div class="flex items-center text-gray-700">
                    </div>
                    <div class="sm:ml-6 sm:block">
                        <div class="flex items-center">
                            <div class="text-gray-700 text-xs uppercase" style="font-size: 16px;font-weight: 900;">
                                <a href="<?= base_url('login') ?>" style="color:white">Log In</a></a>
                            </div>
                        </div>
                    </div>
                </div>
        </nav>

        <!-- Main Navigation -->
        <?php $this->load->view(VIEW_FRONT . 'components/nav.php'); ?>

    </div>

    <main class="content" role="main">
        <div style="margin-top: 7rem">
            <?php $this->load->view(VIEW_FRONT . $main_content); ?>

            <style>
                .card:hover {
                    z-index: 2;
                    box-shadow: 10px 10px 22px 2px rgba(0, 0, 0, 0.39);
                    -webkit-box-shadow: 10px 10px 22px 2px rgba(0, 0, 0, 0.39);
                    -moz-box-shadow: 10px 10px 22px 2px rgba(0, 0, 0, 0.39);
                }
            </style>

            <div class="grid grid-cols-1 md:grid-cols-3 h-64">
                <a href="#" class="card transition-transform transform duration-200 hover:scale-105 relative flex justify-center items-center bg-cover bg-center cursor-pointer" style="background-image: url(<?= FE_IMG_PATH ?>fhgfjhk.PNG)">
                    <h1 class="z-10 text-2xl text-white">Lihat Hasilnya</h1>
                    <div class="shadow-bg absolute top-0 bottom-0 left-0 right-0 bg-black bg-opacity-30"></div>
                </a>
                <a href="#" class="card transition-transform transform duration-200 hover:scale-105 relative flex justify-center items-center bg-cover bg-center cursor-pointer" style="background-image: url(<?= FE_IMG_PATH ?>hfjhgkgjl.PNG)">
                    <h1 class="z-10 text-2xl text-white">Temukan AlphaKing</h1>
                    <div class="absolute top-0 bottom-0 left-0 right-0 bg-black bg-opacity-30"></div>
                </a>
                <a href="#" class="card transition-transform transform duration-200 hover:scale-105 relative flex justify-center items-center bg-cover bg-center cursor-pointer" style="background-image: url(<?= FE_IMG_PATH ?>hlhlhlk.PNG)">
                    <h1 class="z-10 text-2xl text-white">Berbelanja Sekarang</h1>
                    <div class="absolute top-0 bottom-0 left-0 right-0 bg-black bg-opacity-30"></div>
                </a>
            </div>

            <script>
                const cards = document.querySelectorAll('.card')
                cards.forEach((el) => {
                    el.children[0].addEventListener('mouseover', (ev) => {
                        console.log(ev.target.parentElement)
                        ev.target.parentElement.children[1].classList.add('bg-opacity-0')
                        ev.target.parentElement.children[1].classList.remove('bg-opacity-30')
                    })

                    el.addEventListener('mouseover', (ev) => {
                        ev.target.classList.add('bg-opacity-0')
                        ev.target.classList.remove('bg-opacity-30')
                    })

                    el.children[0].addEventListener('mouseout', (ev) => {
                        ev.target.parentElement.children[1].classList.add('bg-opacity-30')
                        ev.target.parentElement.children[1].classList.remove('bg-opacity-0')
                    })

                    el.addEventListener('mouseout', (ev) => {
                        ev.target.classList.add('bg-opacity-30')
                        ev.target.classList.remove('bg-opacity-0')
                    })
                })
            </script>

        </div>
    </main>

    <div class="bg-purple-900 border-lime-900" style="border-bottom-width: 4rem">
        <div class="max-w-screen-xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-8">
                <div class="md:grid md:grid-cols-1 md:gap-8">
                    <div>
                        <img src="<?= LOGO_IMG ?>" alt="Logo" width="100px" />
                        <p class="text-gold">
                            AlphaNet adalah Bisnis Baru Reseller Online untuk Mami Gaul Produk Kesehatan dan Kecantikan langsung dari Pabrik Bonus EMAS setiap Bulan tanpa diundi serta bonus tunai harian dan mingguan.
                        </p>
                    </div>
                </div>
                <div class="md:grid md:grid-cols-1 md:gap-8">
                    <div class="mt-12 md:mt-0">
                        <h4 class="text-sm leading-5 font-semibold tracking-wider text-gray-100 uppercase text-gold">About </h4>
                        <ul class="mt-4">
                            <li>
                                <a href="#" class="text-sm leading-6 text-gray-100 hover:text-q-purple text-gold">Disclaimers </a>
                            </li>
                            <li class="mt-4">
                                <a href="#" rel="noopener noreferrer" target="_blank" class="text-sm leading-6 text-gray-100 hover:text-q-purple text-gold">Policies and Procedures </a>
                            </li>
                            <li class="mt-4">
                                <a href="#" target="_blank" class="text-sm leading-6 text-gray-100 hover:text-q-purple text-gold">Blog </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="md:grid md:grid-cols-1 md:gap-8">
                    <div class="mt-12 md:mt-0">
                        <h4 class="text-sm leading-5 font-semibold tracking-wider text-gray-100 uppercase text-gold">Have a question? </h4>
                        <ul class="mt-4">
                            <li>
                                <a class="text-gray-100 text-sm text-gold" href="mailto:info@<?= DOMAIN_NAME ?>">info@<?= DOMAIN_NAME ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-100 pt-8 md:flex md:items-center md:justify-between">
                <div class="flex md:order-2">
                    <a href="https://www.facebook.com/Alphanetid-110925937767534" class="text-gray-100 hover:text-purple-300">
                        <span class="sr-only">Facebook</span>
                        <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z" />
                        </svg>
                    </a>
                    <a href="https://twitter.com/AlphanetId" class="ml-6 text-gray-100 hover:text-purple-300">
                        <span class="sr-only">Twitter</span>
                        <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z" />
                        </svg>
                    </a>
                    <a href="https://id.linkedin.com/in/alphanet-id-431a71214" class="ml-6 text-gray-100 hover:text-purple-300">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z" />
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@alphanet.idn?lang=id-ID" class="ml-6 text-gray-100 hover:text-purple-300">
                        <svg width="28px" height="28px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Tiktok</title>
                            <g id="Icon/Social/tiktok-color" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Group-7" transform="translate(8.000000, 6.000000)">
                                    <path d="M29.5248245,9.44576327 C28.0821306,9.0460898 26.7616408,8.29376327 25.6826204,7.25637551 C25.5109469,7.09719184 25.3493143,6.92821224 25.1928245,6.75433469 C23.9066204,5.27833469 23.209151,3.38037551 23.2336408,1.42290612 L17.3560898,1.42290612 L17.3560898,23.7086204 C17.3560898,27.7935184 15.1520082,29.9535184 12.416498,29.9535184 C11.694049,29.9611102 10.9789469,29.8107429 10.3213959,29.5124571 C9.6636,29.2144163 9.07951837,28.7758041 8.60955918,28.2272327 C8.1398449,27.6789061 7.79551837,27.0340898 7.60180408,26.3385796 C7.4078449,25.6430694 7.36890612,24.9132735 7.48743673,24.2008653 C7.60596735,23.4884571 7.87902857,22.8105796 8.28751837,22.2154776 C8.69625306,21.6198857 9.23037551,21.1212735 9.85241633,20.7546612 C10.474702,20.3878041 11.1694776,20.1617633 11.8882531,20.0924571 C12.6070286,20.023151 13.3324163,20.1122939 14.0129878,20.3535184 L14.0129878,14.3584163 C13.4889061,14.2430694 12.9530694,14.1862531 12.416498,14.1894367 L12.3917633,14.1894367 C10.2542939,14.1943347 8.16604898,14.8325388 6.39127347,16.0234776 C4.61649796,17.2149061 3.23429388,18.9051918 2.41976327,20.8812735 C1.60523265,22.8578449 1.39486531,25.0310694 1.8151102,27.1269061 C2.2351102,29.2227429 3.2671102,31.1469061 4.78033469,32.6564571 C6.29380408,34.1660082 8.22066122,35.1933551 10.3174776,35.6082122 C12.4142939,36.0230694 14.5870286,35.8073143 16.561151,34.9878857 C18.5355184,34.1682122 20.2226204,32.7820898 21.409151,31.0041306 C22.5959265,29.2264163 23.2289878,27.136702 23.228498,24.9992327 L23.228498,12.8155592 C25.5036,14.392702 28.2244163,15.134498 31.1289061,15.1886204 L31.1289061,9.68551837 C30.5869469,9.66568163 30.049151,9.5851102 29.5248245,9.44576327" id="Fill-1" fill="#FE2C55"></path>
                                    <path d="M25.195102,6.75428571 C24.7946939,6.47510204 24.4148571,6.1675102 24.0587755,5.83346939 C22.8210612,4.66016327 22.0062857,3.11020408 21.7420408,1.42530612 C21.6622041,0.954367347 21.6220408,0.47755102 21.6220408,0 L15.7444898,0 L15.7444898,22.6408163 C15.7444898,27.5069388 13.5404082,28.5183673 10.804898,28.5183673 C10.0829388,28.5262041 9.36783673,28.3758367 8.71028571,28.0773061 C8.0524898,27.7792653 7.46791837,27.3406531 6.99820408,26.7920816 C6.5282449,26.2437551 6.18440816,25.5989388 5.99044898,24.9034286 C5.7964898,24.2079184 5.75755102,23.4781224 5.87583673,22.7657143 C5.99461224,22.053551 6.26767347,21.3756735 6.67640816,20.7800816 C7.08489796,20.1847347 7.61902041,19.6861224 8.24106122,19.3195102 C8.86334694,18.952898 9.55787755,18.7266122 10.276898,18.6573061 C10.9959184,18.588 11.7208163,18.6773878 12.4016327,18.9183673 L12.4016327,12.9328163 C5.40489796,11.8236735 0,17.4783673 0,23.5760816 C0.00465306122,26.4426122 1.14514286,29.1898776 3.17191837,31.216898 C5.19869388,33.2434286 7.94595918,34.3839184 10.8124898,34.3885714 C16.7730612,34.3885714 21.6220408,30.7444898 21.6220408,23.5760816 L21.6220408,11.3924082 C23.8995918,12.9795918 26.6204082,13.7142857 29.524898,13.7632653 L29.524898,8.26040816 C27.9658776,8.18914286 26.4617143,7.66604082 25.195102,6.75428571" id="Fill-3" fill="#25F4EE"></path>
                                    <path d="M21.6220653,23.5764245 L21.6220653,11.392751 C23.8996163,12.9794449 26.6204327,13.7141388 29.5251673,13.7633633 L29.5251673,9.44581224 C28.0822286,9.04613878 26.7617388,8.29381224 25.6824735,7.25617959 C25.5110449,7.09724082 25.3494122,6.92826122 25.1926776,6.75438367 C24.7922694,6.4752 24.4126776,6.16736327 24.056351,5.83356735 C22.8186367,4.66026122 22.0041061,3.11030204 21.7396163,1.42540408 L17.3730857,1.42540408 L17.3730857,23.7111184 C17.3730857,27.7957714 15.1690041,29.9560163 12.4334939,29.9560163 C11.6569224,29.9538122 10.8918612,29.7681796 10.2005143,29.414302 C9.50941224,29.0601796 8.91186122,28.5476082 8.45635102,27.9182204 C7.49071837,27.3946286 6.72712653,26.5636898 6.2865551,25.5571592 C5.84573878,24.5508735 5.75341224,23.4260571 6.02377959,22.3609959 C6.29390204,21.2959347 6.91177959,20.3516082 7.77896327,19.6771592 C8.64639184,19.0027102 9.71365714,18.6365878 10.8122694,18.6365878 C11.3564327,18.6412408 11.8961878,18.7362612 12.4090041,18.9182204 L12.4090041,14.1894857 C10.304351,14.1921796 8.24647347,14.8093224 6.48786122,15.9657306 C4.72924898,17.1221388 3.3470449,18.7666286 2.51047347,20.6978939 C1.67390204,22.6291592 1.41969796,24.7627102 1.77896327,26.8362612 C2.13822857,28.9098122 3.09553469,30.8334857 4.53308571,32.3704653 C6.36271837,33.6848327 8.55945306,34.3906286 10.8122694,34.3884296 C16.7730857,34.3884296 21.6220653,30.7445878 21.6220653,23.5764245" id="Fill-5" fill="#000000"></path>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
                <p class="mt-8 text-base leading-6 text-gray-100 md:mt-0 md:order-1 text-gold">
                    &copy; 2021 <?= COMPANY_NAME ?>. All rights reserved.
                </p>
            </div>
        </div>
    </div>
    <script src="<?= FE_JS_PATH ?>app.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F45LK3HFBJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-F45LK3HFBJ');
    </script>

</body>

</html>