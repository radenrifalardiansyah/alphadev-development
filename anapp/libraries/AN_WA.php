<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WA (Woonotif) class.
 *
 * @class WhatsApps
 */
class AN_WA 
{
	var $CI;
    var $url;
    var $token;
    var $active;
    
	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
    {
        // Set Get CI Instance
        $this->CI       =& get_instance();
        // Set Wanotif URL
        $this->url      = trim(config_item('wanotif_url'));

        // Set Wanotif Token
        $an_token       = get_option('wanotif_token');
        $an_token       = (!empty($an_token)) ? trim($an_token) : '';        
        $this->token    = $an_token;
        $this->token    = trim(config_item('wanotif_token'));

        // Set Wanotif Active/Not Active
        $an_active      = get_option('wanotif_active');
        $an_active      = ( $an_active == 1 ) ? true : false;    
        $this->active   = $an_active;
        $this->active   = config_item('wanotif_active');
	}
    
    /**
	 * Send WA function.
	 *
     * @param string    $to         (Required)  To WA destination
     * @param string    $message    (Required)  Message of WA
	 * @return Mixed
	 */
	function send_wa($to, $message, $subject = 'WhatsApps'){
        if ( !$this->active || !$this->token ) return false;

        $pos    = strpos($to, '0');
        if ($pos !== false) {
            $to = substr_replace($to, '+62', $pos, strlen('0'));
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'Apikey'    => $this->token,
            'Phone'     => $to,
            'Message'   => $message,
        ));

        $response   = curl_exec($curl);
        $curl_error = curl_error($curl);
        $http       = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl); 

        if ( $curl_error || $response === false ) {
            an_log_notif('whatsapp', $subject, $to, $message, 'FAILED : (code : ' . $http . ') ' . $curl_error);
            return $response;
        }

        $response   = json_decode($response);
        $status     = isset($response->wanotif->status) ? $response->wanotif->status : 'failed';
        an_log_notif('whatsapp', $subject, $to, $message, strtoupper($status));

        return $status;
	}
    
    /**
     * Send WA to New Member function.
     *
     * @param   Object  $member             (Required)  Member Data of Downline
     * @param   Object  $sponsor            (Required)  Member Data of Sponsor
     * @param   Object  $password           (Required)  Password of Downline
     * @param   String  $transfer_amount    (Required)  Transfer Amount
     * @return  Mixed
     */
    function send_wa_new_member($member, $sponsor, $password, $transfer_amount, $view = false){
        if (!$member) return false;
        if (!$sponsor) return false;
        if (!$password) return false;
        if (empty($member->phone)) return false;

        if( $member->status == 1 ){
            if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reseller-active', 'whatsapp')) {
                return false;
            }
        }else{
            if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reseller-non-active', 'whatsapp')) {
                return false;
            }
        }
            
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $to             = $member->phone;
        $login_url      = base_url('login');
        $message        = $notif->content;
        $sponsor_name   = strtolower($sponsor->username) . ' / ' . strtoupper($sponsor->name);
        
        $bill_bank      = '';
        $bill_no        = get_option('company_bill');
        $bill_name      = get_option('company_bill_name');
        if ($company_bank = get_option('company_bank')) {
            if ($getBank = an_banks($company_bank)) {
                $bill_bank = $getBank->nama;
            }
        }
        
        $url_login      = '<a href="' . base_url('login') . '" style="text-decoration: none; color: #FFFFFF;" target="_blank"><b>' . base_url('login') . '</b></a>';

        if ($bill_no) {
            $bill_format = '';
            $arr_bill    = str_split($bill_no, 4);
            foreach ($arr_bill as $no) {
                $bill_format .= $no . ' ';
            }
            $bill_no = $bill_format ? $bill_format : $bill_no;;
        }
        
        $payment_detail = '
        Silahkan lakukan <strong>Pembayaran sebesar ' . $transfer_amount . '</strong> ke rekening Perusahaan!<br />
        Bank : '.strtoupper($bill_bank).'<br />
        No. Rekening : '.$bill_no.'<br />
        Atas Nama : '.$bill_name.'';

        if (!$message) return false;

        $message        = str_replace("%username%",     $member->username, $message);
        $message        = str_replace("%name%",         $member->name, $message);
        $message        = str_replace("%password%",     $password, $message);
        $message        = str_replace("%sponsor_name%",     strtoupper($sponsor->name), $message);
        $message        = str_replace("%sponsor_username%", strtolower($sponsor->username), $message);
        $message        = str_replace("%sponsor_phone%",    $sponsor->phone, $message);
        $message        = str_replace("%login_url%",    $login_url, $message);
        $message        = str_replace("%payment_detail%",   $payment_detail, $message);

        if ($view) {
            return $message;
        }
        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA to Sponsor (New Member) function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $upline     (Required)  Member Data of Upline
     * @return  Mixed
     */
    function send_wa_sponsor($member, $sponsor, $upline, $view = false)
    {
        if (!$member) return false;
        if (!$sponsor) return false;
        if (!$upline) return false;
        if (empty($sponsor->phone)) return false;


        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-sponsor', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $to             = $sponsor->phone;
        $message        = $notif->content;
        $member_name    = strtolower($sponsor->username) . ' / ' . strtoupper($sponsor->name);
        $upline_name    = strtolower($upline->username) . ' / ' . strtoupper($upline->name);

        if (!$message) return false;

        $message        = str_replace("%member_name%",  $member_name, $message);
        $message        = str_replace("%username%",     $member->username, $message);
        $message        = str_replace("%name%",         $member->name, $message);
        $message        = str_replace("%upline%",       $upline_name, $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA withdraw function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $withdraw   (Required)  Data of Withdraw
     * @return  Mixed
     */
    function send_wa_withdraw($member, $withdraw, $view = false)
    {
        if (!$member) return false;
        if (!$withdraw) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-withdraw', 'whatsapp')) {
            return false;
        }
        if (!$bank = an_banks($withdraw->bank)) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $rekening       = $withdraw->bill . ' (A.N. ' . strtoupper($withdraw->bill_name) . ')';
        $currency       = config_item('currency');

        $message        = str_replace("%member_name%",      $member_name, $message);
        $message        = str_replace("%username%",         $member->username, $message);
        $message        = str_replace("%name%",             $member->name, $message);
        $message        = str_replace("%name_bank%",        $bank->nama, $message);
        $message        = str_replace("%bill%",             $rekening, $message);
        $message        = str_replace("%nominal%",          an_accounting($withdraw->nominal_receipt, $currency), $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA PIN transfer to Sender function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_wa_pin_transfer_sender($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-sender', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['receiver_username']) || !isset($data['receiver_name']) || empty($data['receiver_username']) || empty($data['receiver_name'])) {
            return false;
        }

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $pin_detail     = isset($data['pin_detail']) ? $data['pin_detail'] : '';
        $datenow        = isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d H:i:s');
        $date           = date('j M Y', strtotime($datenow));
        $hour           = date('H:i', strtotime($datenow));
        $datetime       = $date . ' Pukul ' . $hour . ' WIB';

        if ( !$message ) return false;
        if ( empty($pin_detail) || !is_array($pin_detail) ) return false;

        // Set Data Detail PIN
        $no             = 1;
        $transfer       = '';
        foreach ($pin_detail as $row) {
            $transfer  .= $no . '. ' . $row['product_name'] . ' (' . an_accounting($row['product_qty']) . " qty) \n";
            $no++;
        }

        $message        = str_replace("%member_name%",          $member_name, $message);
        $message        = str_replace("%transfer_date%",        $datetime, $message);
        $message        = str_replace("%receiver_username%",    $data['receiver_username'], $message);
        $message        = str_replace("%receiver_name%",        $data['receiver_name'], $message);
        $message        = str_replace("%pin_detail%",           $transfer, $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA PIN transfer to Receiver function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_wa_pin_transfer_receiver($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-receiver', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['sender_username']) || !isset($data['sender_name']) || empty($data['sender_username']) || empty($data['sender_name'])) {
            return false;
        }

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $pin_detail     = isset($data['pin_detail']) ? $data['pin_detail'] : '';
        $datenow        = isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d H:i:s');
        $date           = date('j M Y', strtotime($datenow));
        $hour           = date('H:i', strtotime($datenow));
        $datetime       = $date . ' Pukul ' . $hour . ' WIB';

        if ( !$message ) return false;
        if ( empty($pin_detail) || !is_array($pin_detail) ) return false;

        // Set Data Detail PIN
        $no             = 1;
        $transfer       = '';
        foreach ($pin_detail as $row) {
            $transfer  .= $no . '. ' . $row['product_name'] . ' (' . an_accounting($row['product_qty']) . " qty) \n";
            $no++;
        }

        $message        = str_replace("%member_name%",          $member_name, $message);
        $message        = str_replace("%transfer_date%",        $datetime, $message);
        $message        = str_replace("%sender_username%",      $data['sender_username'], $message);
        $message        = str_replace("%sender_name%",          $data['sender_name'], $message);
        $message        = str_replace("%pin_detail%",           $transfer, $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA change password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_wa_change_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-change-password', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password']) || !isset($data['type_password'])) return false;
        if (empty($data['password']) || empty($data['type_password'])) return false;

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        if (!$message) return false;

        $message           = str_replace("%member_name%",      $member_name, $message);
        $message           = str_replace("%username%",         $member->username, $message);
        $message           = str_replace("%password%",         $data['password'], $message);
        $message           = str_replace("%type_password%",    $data['type_password'], $message);
        $message           = str_replace("%login_url%",        base_url('login'), $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA reset password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_wa_reset_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reset-password', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password']) || !isset($data['type_password'])) return false;
        if (empty($data['password']) || empty($data['type_password'])) return false;

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        if (!$message) return false;

        $message           = str_replace("%member_name%",      $member_name, $message);
        $message           = str_replace("%username%",         $member->username, $message);
        $message           = str_replace("%password%",         $data['password'], $message);
        $message           = str_replace("%type_password%",    $data['type_password'], $message);
        $message           = str_replace("%login_url%",        base_url('login'), $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA forgot password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_wa_forget_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->phone)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-forgot-password', 'whatsapp')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password'])) return false;
        if (empty($data['password'])) return false;

        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $url_login      = base_url('login');

        if (!$message) return false;

        $message        = str_replace("%member_name%",      $member_name, $message);
        $message        = str_replace("%username%",         $member->username, $message);
        $message        = str_replace("%password%",         $data['password'], $message);
        $message        = str_replace("%login_url%",        $url_login, $message);

        if ($view) {
            return $message;
        }

        return $this->send_wa($to, $message, $notif->title);
    }

    /**
     * Send WA to Order Product Stockist function.
     *
     * @param   Object  $member     (Required)  Member Data
     * @param   Object  $shop_order (Required)  Product Order Data
     * @return  Mixed
     */
    function send_wa_generate_product($member, $shop_order, $view = false)
    {
        $CI = &get_instance();
        if (!$member) return false;
        if (!$shop_order) return false;
        if ($shop_order->status != 1) return false;
        if (empty($member->phone)) return false;

        // Set Variable
        $to             = $member->phone;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $currency       = config_item('currency');

        $num            = 1;
        $total          = 0;
        $detail_product = '';
        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;
                $total         += $subtotal;

                $detail_product .= $num . ". *" . $product_name . "* (" . $qty . " x " . an_accounting($price_cart) . ")";
                $detail_product .= " = " . an_accounting($subtotal) . "\r\n";
                $num++;
            }
        }

        // shipping address
        $shipping_address   = ucwords(strtolower($shop_order->address)) . ', ' . $shop_order->village .' Kec'. $shop_order->subdistrict . ' ';
        $shipping_address  .= $shop_order->district .' - '. $shop_order->province;

        // shipping method
        $shipping_information = '';
        if ($shop_order->shipping_method == 'ekspedisi') {
            $_shipping  = 'JASA EKSPEDISI / PENGIRIMAN';
            if ($shop_order->courier) {
                $_shipping  = strtoupper($shop_order->courier);
                if ($shop_order->service) {
                    $_shipping  .= ' (' . strtoupper($shop_order->service) . ')';
                }
            }
            $shipping_information  = "METODE PENGIRIMAN : " . $_shipping . "\r\n";
        }

        $shipping_information .= "INFORMASI MEMBER \r\n";
        $shipping_information .= "Username : " . $member->username . "\r\n";
        $shipping_information .= "Nama : " . $shop_order->name . "\r\n";
        $shipping_information .= "No. Telp/HP : " . $shop_order->phone . "\r\n";
        $shipping_information .= "Alamat : " . $shipping_address . "\r\n";

        $uniquecode = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);

        $message    = "*Informasi Generate PIN Produk*\r\n\r\n";
        $message   .= "Hi *" . $member_name . "*\r\n";
        $message   .= "Admin Alpha Network telah Generate Order PIN Produk ke akun anda. \r\n";
        $message   .= "Berikut informasi data generate PIN produk : \r\n\r\n";
        $message   .= "DETAIL PRODUK :\r\n";
        $message   .= "---------------------------\r\n";
        $message   .= $detail_product;
        $message   .= "---------------------------\r\n";
        $message   .= "*Subtotal :* " . an_accounting($shop_order->subtotal, $currency) . "\r\n";
        $message   .= "*Kode Unik :* " . $uniquecode . "\r\n";
        $message   .= "*Ongkir :* " . an_accounting($shop_order->shipping, $currency) . "\r\n";
        if ( $shop_order->discount ) {
            $message   .= "*Diskon :* " . an_accounting($shop_order->discount, $currency) . "\r\n";
        }
        $message   .= "---------------------------\r\n";
        $message   .= "*Total Pembayaran : " . an_accounting($shop_order->total_payment, $currency) . "*\r\n";
        $message   .= "---------------------------\r\n\r\n";
        $message   .= $shipping_information . "\r\n\r\n";
        $message   .= "---------------------------\r\n";
        $message   .= "Salam Sukses, \r\n";
        $message   .= "Manajamen Alpha Network";

        if ($view) {
            return $message;
        }

        $wa = $this->send_wa($to, $message, 'Generate Produk');
        return TRUE;
    }

    /**
     * Send WA to Order Product Stockist function.
     *
     * @param   Object  $member     (Required)  Member Data
     * @param   Object  $shop_order (Required)  Product Order Data
     * @return  Mixed
     */
    function send_wa_shop_order($member, $shop_order, $view = false)
    {
        $CI = &get_instance();
        if (!$member) return false;
        if (!$shop_order) return false;
        if (empty($member->phone)) return false;

        $notif      = false;
        if ( $shop_order->status == 0 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-order-member', 'whatsapp');
        }
        if ( $shop_order->status == 1 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-confirmation-order-member', 'whatsapp');
        }
        if ( $shop_order->status == 4 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-cancelation-order-member', 'whatsapp');
        }

        if (!$notif) return false;
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        // Set Variable
        $to             = $member->phone;
        $message        = $notif->content;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $currency       = config_item('currency');
        $subject        = 'Informasi Pemesanan Produk';
        if ($shop_order->status == 1) {
            $subject    = 'Informasi Konfirmasi Pesanan';
        }
        if ($shop_order->status == 4) {
            $subject    = 'Informasi Pembatalan Pesanan';
        }

        if (!$message) return false;

        $num            = 1;
        $total          = 0;
        $detail_product = '';
        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;
                $total         += $subtotal;

                $detail_product .= $num . ". *" . $product_name . "* (" . $qty . " x " . an_accounting($price_cart) . ")";
                $detail_product .= " = " . an_accounting($subtotal) . "\r\n";
                $num++;
            }
        }

        $uniquecode     = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);
        $order_detail   = "DETAIL PRODUK :\r\n";
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= $detail_product;
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= "*Subtotal :* " . an_accounting($shop_order->subtotal) . "\r\n";
        $order_detail  .= "*Kode Unik :* " . $uniquecode . "\r\n";
        $order_detail  .= "*Ongkir :* " . an_accounting($shop_order->shipping) . "\r\n";
        if ( $shop_order->discount ) {
            $order_detail .= "*Diskon :* " . an_accounting($shop_order->discount) . "\r\n";
        }
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= "*Total Pembayaran : " . an_accounting($shop_order->total_payment, $currency) . "*\r\n";
        $order_detail  .= "---------------------------\r\n";

        // shipping address
        $shipping_address   = ucwords(strtolower($shop_order->address)) . ', ' . $shop_order->village .' Kec'. $shop_order->subdistrict . ' ';
        $shipping_address  .= $shop_order->district .' - '. $shop_order->province;

        // shipping method
        $shipping_detail    = '';
        if ($shop_order->shipping_method == 'ekspedisi') {
            $_shipping      = 'JASA EKSPEDISI / PENGIRIMAN';
            if ($shop_order->courier) {
                $_shipping  = strtoupper($shop_order->courier);
                if ($shop_order->service) {
                    $_shipping  .= ' (' . strtoupper($shop_order->service) . ')';
                }
            }
            $shipping_detail = "METODE PENGIRIMAN : " . $_shipping . "\r\n";
            $shipping_detail .= "---------------------------\r\n\r\n";
            $shipping_detail .= "ALAMAT PENGIRIMAN :\r\n";
        } else {
            $shipping_detail = "METODE PENGIRIMAN : PICKUP \r\n";
            $shipping_detail .= "---------------------------\r\n\r\n";
            $shipping_detail .= "DATA KONSUMEN :\r\n";
        }

        $shipping_detail .= "---------------------------\r\n";
        $shipping_detail .= "Nama : " . $shop_order->name . "\r\n";
        $shipping_detail .= "Telp : " . $shop_order->phone . "\r\n";
        $shipping_detail .= "Alamat : " . $shipping_address . "\r\n";
        $shipping_detail .= "---------------------------\r\n\r\n";

        // Information Stockist
        $info_stockist      = '';
        if ( $shop_order->id_stockist ) {
            if ( $stockistdata = an_get_memberdata_by_id($shop_order->id_stockist) ) {

                $info_stockist = "INFORMASI STOCKIST \r\n";
                $info_stockist .= "---------------------------\r\n";
                $info_stockist .= "Nama : " . ucwords(strtolower($stockistdata->name)) . "\r\n";
                $info_stockist .= "Telp : " . $stockistdata->phone . "\r\n";
                $info_stockist .= "Email : " . strtolower($stockistdata->email) . "\r\n";
                $info_stockist .= "---------------------------\r\n\r\n";
            }
        }

        // Information Billing Account
        $billing_detail     = '';
        if ( $shop_order->status == 0 && $shop_order->id_stockist == 0) {
            $bill_bank      = '';
            $bill_no        = get_option('company_bill');
            $bill_name      = get_option('company_bill_name');
            if ($company_bank = get_option('company_bank')) {
                if ($getBank = an_banks($company_bank)) {
                    $bill_bank = $getBank->nama;
                }
            }

            if ($bill_no) {
                $bill_format = '';
                $arr_bill    = str_split($bill_no, 4);
                foreach ($arr_bill as $no) {
                    $bill_format .= $no . ' ';
                }
                $bill_no = $bill_format ? $bill_format : $bill_no;;
            }

            $billing_detail = "INFORMASI REKENING PERUSAHAAN \r\n";
            $billing_detail .= "---------------------------\r\n";
            $billing_detail .= "Bank : " . strtoupper($bill_bank) . "\r\n";
            $billing_detail .= "No. Rek : " . $bill_no . "\r\n";
            $billing_detail .= "A.N : " . ucwords(strtolower($bill_name)) . "\r\n";
            $billing_detail .= "---------------------------\r\n";

            if ($shop_order->status == 0) {
                $billing_detail .= "Silahkan Transfer Pembayaran sebasar *" . an_accounting($shop_order->total_payment, $currency) . "* Ke Rekening Perusahaan.\r\n";
                $billing_detail .= "---------------------------\r\n";
            }
        }

        $message    = str_replace("%name%",                     $member->name, $message);
        $message    = str_replace("%memberuid%",                $member->username, $message);
        $message    = str_replace("%order_detail%",             $order_detail, $message);
        $message    = str_replace("%shipping_detail%",          $shipping_detail, $message);
        $message    = str_replace("%stockist_information%",     $info_stockist, $message);

        if ( $shop_order->id_stockist == 0 ) {
            $message = str_replace("%billing_detail%",          $billing_detail, $message);
        } else {
            $message = str_replace("%billing_detail%",          $info_stockist, $message);
        }

        if ($view) {
            return $message;
        }

        $to = $member->phone;
        $wa = $this->send_wa($to, $message, $subject);
        return TRUE;
    }

    /**
     * Send WA to Order Product Stockist function.
     *
     * @param   Object  $member     (Required)  Member Data
     * @param   Object  $shop_order (Required)  Product Order Data
     * @return  Mixed
     */
    function send_wa_shop_order_stockist($stockist, $shop_order, $view = false)
    {
        $CI = &get_instance();
        if (!$stockist) return false;
        if (!$shop_order) return false;
        if (empty($stockist->phone)) return false;

        $notif      = false;
        if ( $shop_order->status == 0 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-order-stockist', 'whatsapp');
        }
        if ( $shop_order->status == 1 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-confirmation-order-stockist', 'whatsapp');
        }
        if ( $shop_order->status == 4 ) {
            $notif  = $this->CI->Model_Option->get_notification_by('slug', 'notification-cancelation-order-stockist', 'whatsapp');
        }

        if ( !$member = an_get_memberdata_by_id($shop_order->id_member) ) {
            return false;
        }

        if (!$notif) return false;
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        // Set Variable
        $to             = $stockist->phone;
        $message        = $notif->content;
        $member_name    = strtolower($stockist->username) . ' / ' . strtoupper($stockist->name);
        $currency       = config_item('currency');
        $subject        = 'Informasi Pemesanan Produk';
        if ($shop_order->status == 1) {
            $subject    = 'Informasi Konfirmasi Pesanan';
        }
        if ($shop_order->status == 4) {
            $subject    = 'Informasi Pembatalan Pesanan';
        }

        if (!$message) return false;

        $num            = 1;
        $total          = 0;
        $detail_product = '';
        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;
                $total         += $subtotal;

                $detail_product .= $num . ". *" . $product_name . "* (" . $qty . " x " . an_accounting($price_cart) . ")";
                $detail_product .= " = " . an_accounting($subtotal) . "\r\n";
                $num++;
            }
        }

        $uniquecode     = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);
        $order_detail   = "DETAIL PRODUK :\r\n";
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= $detail_product;
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= "*Subtotal :* " . an_accounting($shop_order->subtotal) . "\r\n";
        $order_detail  .= "*Kode Unik :* " . $uniquecode . "\r\n";
        $order_detail  .= "*Ongkir :* " . an_accounting($shop_order->shipping) . "\r\n";
        if ( $shop_order->discount ) {
            $order_detail .= "*Diskon :* " . an_accounting($shop_order->discount) . "\r\n";
        }
        $order_detail  .= "---------------------------\r\n";
        $order_detail  .= "*Total Pembayaran : " . an_accounting($shop_order->total_payment, $currency) . "*\r\n";
        $order_detail  .= "---------------------------\r\n";

        // shipping address
        $shipping_address   = ucwords(strtolower($shop_order->address)) . ', ' . $shop_order->village .' Kec'. $shop_order->subdistrict . ' ';
        $shipping_address  .= $shop_order->district .' - '. $shop_order->province;

        // shipping method
        $shipping_detail    = '';
        if ($shop_order->shipping_method == 'ekspedisi') {
            $_shipping      = 'JASA EKSPEDISI / PENGIRIMAN';
            if ($shop_order->courier) {
                $_shipping  = strtoupper($shop_order->courier);
                if ($shop_order->service) {
                    $_shipping  .= ' (' . strtoupper($shop_order->service) . ')';
                }
            }
            $shipping_detail = "METODE PENGIRIMAN : " . $_shipping . "\r\n";
            $shipping_detail .= "---------------------------\r\n\r\n";
            $shipping_detail .= "ALAMAT PENGIRIMAN :\r\n";
        } else {
            $shipping_detail = "METODE PENGIRIMAN : PICKUP \r\n";
            $shipping_detail .= "---------------------------\r\n\r\n";
            $shipping_detail .= "ALAMAT PENAGIHAN :\r\n";
        }

        $shipping_detail .= "---------------------------\r\n";
        $shipping_detail .= "Username : " . $member->username . "\r\n";
        $shipping_detail .= "---------------------------\r\n";
        $shipping_detail .= "Nama : " . $shop_order->name . "\r\n";
        $shipping_detail .= "Telp : " . $shop_order->phone . "\r\n";
        $shipping_detail .= "Alamat : " . $shipping_address . "\r\n";
        $shipping_detail .= "---------------------------\r\n\r\n";

        $message    = str_replace("%name%",                     $stockist->name, $message);
        $message    = str_replace("%memberuid%",                $stockist->username, $message);
        $message    = str_replace("%order_detail%",             $order_detail, $message);
        $message    = str_replace("%shipping_detail%",          $shipping_detail, $message);

        if ($view) {
            return $message;
        }

        $to = $member->phone;
        $wa = $this->send_wa($to, $message, $subject);
        return TRUE;
    }
}