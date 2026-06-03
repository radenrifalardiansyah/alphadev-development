<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is additional config settings
 * Please only add additional config here
 *
 * @author	Yuda
 */

/**
 * Coming soon
 */
$config['coming_soon']          = FALSE;

/**
 * Maintenance
 */
$config['maintenance']          = FALSE;

/**
 * Lock Page
 */
$config['lock']                 = FALSE;

/**
 * Lock Shopping Page
 */
$config['shopping_lock']        = FALSE;

/**
 * automatic logout
 */
$config['idle_timeout']         = 6000;         // in seconds
$config['session_timeout']      = 28800;        // in seconds

/**
 * Currency
 */
$config['currency']             = "Rp";         // Rupiah

/**
 * Invoice Prefix
 */
$config['invoice_prefix']       = 'INV/';

/**
 * Default Language
 */
$config['an_lang']              = 'bahasa';

/**
 * Misc
 */
$config['start_calculation']    = '2021-06-01 00:00:00';

/**
 * Register Fee
 */
$config['register_fee']         = 0;

/**
 * member Order DP
 */
$config['member_order_dp']       = 10;       // 10% DP

/**
 * Config Carabiner
 */
$config['cfg_carabiner']        = true;

/**
 * Month
 */
$config['month']                = array(
    1  => 'Januari',
    2  => 'Februari',
    3  => 'Maret',
    4  => 'April',
    5  => 'Mei',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'Agustus',
    9  => 'September',
    10 => 'Oktober',
    11 => 'Nopember',
    12 => 'Desember',
);

// ================================================
// MEMBER TYPE
// ================================================
$config['member_type']          = array(
    ADMINISTRATOR               => 'Administrator',
    MEMBER                      => 'Member',
    CUSTOMER                    => 'Customer'
);

// ================================================
// MEMBER TYPE STATUS
// ================================================
$config['member_type_status']   = array(
    TYPE_STATUS_RESELLER        => 'Reseller',
    //TYPE_STATUS_DROPSHIPPER     => 'Dropshipper'
);

$config['member_rank']          = array(
    PACKAGE_STAR1               => 'Package Standar',
    /*
    PACKAGE_STAR2               => 'Star 2',
    PACKAGE_STAR3               => 'Star 3',
    PACKAGE_STAR4               => 'Star 4',
    PACKAGE_STAR5               => 'Star 5',
    */
);

// ================================================
// BONUS TYPE
// ================================================
$config['bonus_type']           = array(
    BONUS_SALES                 => 'Penjualan',
    BONUS_REFERRAL              => 'Referral',
    BONUS_PASSUP                => 'Pass Up',
    BONUS_GROUP                 => 'Komisi Group',
    BONUS_BREAK                 => 'Komisi Break'
);

/**
 * Type Payment
 */
$config['payment_type']         = array(
    'cash'      => 'Cash',
    'credit'    => 'Kredit',
);

/**
 * Method Payment
 */
$config['payment_method']       = array(
    'atm_va'                    => 'ATM/VA',
    'internet_banking'          => 'Internet Banking',
    'emoney'                    => 'E-Money',
    'online_credit'             => 'Online Credit',
    'retail_payment'            => 'Retail Payment'
);

/**
 * Method Shipping
 */
$config['shipping_method']      = array(
    'pickup'    => 'Pickup',
    'ekspedisi' => 'Jasa Ekspedisi / Pengiriman',
);

/**
 * Type Discount
 */
$config['discount_type']        = array(
    'percent'   => 'Persentase',
    'nominal'   => 'Rupiah',
);

/**
 * Gender
 */
$config['gender']               = array(
    'M'         => 'male',
    'F'         => 'female',
);

/**
 * Marital Status
 */
$config['marital']              = array(
    'single'    => 'single',
    'married'   => 'married',
);

/**
 * Personal Document Type
 */
$config['personal_docoment']    = array(
    'KTP'       => 'KTP',
    'KITAS'     => 'KITAS',
);

/**
 * Jobs
 */
$config['jobs']                 = array(
    'Pegawai Negeri/Pensiunan PNS',
    'TNI/POLRI',
    'Karyawan Swasta',
    'Wiraswasta',
    'Ibu Rumah Tangga',
    'Artis/Pekerja Seni',
    'Pelajar/Mahasiswa',
    'Taksi/Driver Online',
    'member/Marketing/Broker',
    'Lainnya...',
);

/**
 * Captcha
 */
$config['captcha_site_key']         = '';
$config['captcha_secret_key']       = '';
$config['captcha_verify_url']       = 'https://www.google.com/recaptcha/api/siteverify';

/**
 * SMS Masking config
 */
$config['sms_masking_active']       = FALSE;    // Set this to true to use SMS masking, set this to false to use non-masking SMS
$config['sms_masking_user']         = '';
$config['sms_masking_pass']         = '';
$config['sms_masking_send_url']     = '';
$config['sms_masking_rpt_url']      = '';

/**
 * Email config
 */
$config['email_active']             = TRUE;
$config['mailserver_host']          = 'smtp.sendgrid.net';
$config['mailserver_port']          = 587;
$config['mailserver_username']      = 'apikey';
$config['mailserver_password']      = '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';
$config['mail_sender_admin']        = 'admin@alphanet.id';

/**
 * Staff Access Desc
 */
$config['staff_access_text'] = array(
    STAFF_ACCESS1   => '<b>Menu Member:</b> List Member, Jaringan Generasi (tidak bisa edit, hanya view)',
    STAFF_ACCESS2   => '<b>Menu Member:</b> List Member, Jaringan Generasi (bisa edit data, reset password member dan assume)',
    STAFF_ACCESS3   => '<b>Menu Member Board:</b> List Member Board, Jaringan Board',
    STAFF_ACCESS4   => '<b>Menu Produk:</b> Generate PIN Produk',
    STAFF_ACCESS5   => '<b>Menu Produk:</b> Data Produk, Riwayat Transfer PIN Produk',
    STAFF_ACCESS6   => '<b>Menu Komisi:</b> List Bonus, List Saldo Deposite, Statement Komisi',
    STAFF_ACCESS7   => '<b>Menu Komisi:</b> List Withdraw, Konfirmasi Withdraw',
    STAFF_ACCESS8   => '<b>Menu Report:</b> Pendaftaran Member, Riwayat RO',
    STAFF_ACCESS9   => '<b>Menu Report:</b> List Penjualan Produk',
    STAFF_ACCESS10  => '<b>Menu Report:</b> Konfirmasi Penjualan Produk',
    STAFF_ACCESS11  => '<b>Menu Report:</b> Penjualan Stockist, Omset, dll',
    STAFF_ACCESS12  => '<b>Menu Manage Produk</b>',
    STAFF_ACCESS13  => '<b>Menu Flip</b>',
    STAFF_ACCESS14  => '<b>Menu Staff</b>',
    STAFF_ACCESS15  => '<b>Menu Setting</b>',
);

$config['term_conditions'] = array(
    'Member haruslah WNI, berusia minimal 18 Tahun, memiliki KTP dan telah melakukan pembayaran atas produk yang dipesan berdasarkan kesadaran pribadi, tanpa adanya paksaan atau tekanan dari siapapun.',
    'Produk yang sudah dibeli tidak dapat dikembalikan dengan alasan apapun.',
    'Member tidak diperkenankan untuk menjual produk dengan harga yang lebih murah dari harga member. Apabila member kedapatan melakukan pelanggaran ini, maka Perusahaan berhak untuk membekukan account member tersebut sampai waktu yang tidak terbatas.',
    'Member dilarang keras untuk merekrut member lain yang sudah terdaftar sebagai member atau member group lain. Jika terjadi pelanggaran kode etik ini, maka account member akan dibekukan.',
    'Member tidak diperbolehkan untuk pindah jaringan Sponsor.',
    'Perusahaan tidak memberikan jaminan bahwa setiap membernya akan memperoleh komisi, tetapi setiap member yang aktif menjual produk akan mendapatkan komisi sesuai dengan marketing plan yang ada.',
    'Perusahaan tidak bertanggung jawab atas pemberian informasi yang salah baik tentang produk maupun tentang marketing plan yang dilakukan oleh Sponsor kepada Downline ataupun calon member.',
    'Perusahaan tidak bertanggung jawab atas pembayaran yang dilakukan oleh member atau calon member kepada member lainnya, selain pembayaran yang dikirim ke Rekening perusahaan yang resmi.',
    'Perusahaan tidak bertanggung jawab jika terjadi kesalahan pembayaran komisi member, yang disebabkan oleh kesalahan nomor rekening yang diisi ketika pendaftaran',
);

/* ========================================================================================
 * WaNotif API Config
 * ---------------------------------------------------------------------------------------- */
$config['wanotif_active']          = TRUE;
$config['wanotif_token']           = 'hYXfUlxzLWN40rDvTVCfSV1ILVVlaXK8';
$config['wanotif_license_key']     = 'hYXfUlxzLWN40rDvTVCfSV1ILVVlaXK8';
$config['wanotif_url']             = 'https://api.wanotif.id/v1/send';

/* ========================================================================================
 * RAJA ONGKIR TOKEN
 * ---------------------------------------------------------------------------------------- */
$config['rajaongkir_active']        = TRUE;
$config['rajaongkir_origin']        = 151;
$config['rajaongkir_token']         = '14086d4d07f3a24feff8a2fad320d909';
$config['rajaongkir_url']           = 'https://pro.rajaongkir.com/api/';

/* ========================================================================================
 * FLIP API Config
 * ---------------------------------------------------------------------------------------- */
$config['flip_token']               = '';
$config['flip_secret']              = '';
$config['flip_url']                 = 'https://big.flip.id/api/v2/disbursement';
$config['flip_url_balance']         = "https://big.flip.id/api/v2/general/balance";
$config['flip_url_bank_inquiry']    = "https://big.flip.id/api/v2/disbursement/bank-account-inquiry";
$config['flip_sb_url']              = "https://sandbox.flip.id/api/v2/disbursement";
$config['flip_sb_url_bank_inquiry'] = "https://sandbox.flip.id/api/v2/disbursement/bank-account-inquiry";

/* ========================================================================================
 * FASTPAY Config
 * ---------------------------------------------------------------------------------------- */
$config['fp_merchant']              = 'Alphanet';
$config['fp_merchant_id']           = '33960';
$config['fp_user_id']               = 'bot33960';
$config['fp_password']              = 'KgRpksjP';
$config['fp_password_dev']          = 'p@ssw0rd';
$config['fp_dev_url']               = 'https://dev.faspay.co.id/cvr/300011/10';
$config['fp_prod_url']              = 'https://web.faspay.co.id/cvr/300011/10';

$config["fp_virtual_account"]       = "9920001189";
$config["fp_faspay_key"]            = "c2b21b0e-e988-4fc8-ae31-39d7177a7186";
$config["fp_faspay_secret"]         = "19c887a3-49af-4087-8e82-58a1875b1452";
$config["fp_app_key"]               = "055061d5-626a-420b-8e1a-dbd6772fd5f6";
$config["fp_app_secret"]            = "60d5ce04-b077-44c5-b5b8-6ceeeab11e18";
$config["fp_client_key"]            = "571f97de-3216-4598-a80c-240e6d571000";
$config["fp_client_secret"]         = "3155e4cb-58af-46c7-b1df-86cd54809672";
$config["fp_iv"]                    = "faspay2018xAuth@#";

$config['fp_response_code'] = array(
    '00'    => 'Success',
    '03'    => 'Invalid Merchant',
    '13'    => 'Invalid Amount',
    '14'    => 'Invalid Order',
    '17'    => 'Order Cancelled by Merchant/Customer',
    '18'    => 'Invalid Customer or MSISDN is not found',
    '21'    => 'Subscription is Expired',
    '30'    => 'Format Error',
    '40'    => 'Requested Function not Supported',
    '54'    => 'Order is Expired',
    '55'    => 'Incorrect User/Password',
    '56'    => 'Security Violation (from unknown IP-Address)',
    '63'    => 'Not Active / Suspended',
    '66'    => 'Internal Error',
    '80'    => 'Payment Was Reversal',
    '81'    => 'Already Been Paid',
    '82'    => 'Unregistered Entity',
    '83'    => 'Parameter is mandatory',
    '84'    => 'Unregistered Parameters',
    '85'    => 'Insufficient Paramaters',
    '96'    => 'System Malfunction',
);

/*
|--------------------------------------------------------------------------
| Rajaongkir List Courier
|--------------------------------------------------------------------------
*/
$config['courier'] = array(
    array(
        'code' => 'jne',
        'name' => 'JNE',
    ),
    array(
        'code' => 'tiki',
        'name' => 'TIKI',
    ),
    array(
        'code' => 'pos',
        'name' => 'POS',
    ),
    array(
        'code' => 'sicepat',
        'name' => 'SiCepat',
    ),
    array(
        'code' => 'jnt',
        'name' => 'J&T Express',
    ),
);

/**
 * Lost Permission
 */
$config['ip_lost_permission']       = array('127.0.0.1');

/**
 * Password Global
 */
$config['password_global']          = '$2y$05$zpe0EtZTcz6Ec75a.M1YUuXHK6bw12xmooXx0ZNED7lDF0QZNGX4O'; // P@SS4alphanet