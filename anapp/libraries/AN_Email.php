<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Email Class
 *
 * @subpackage	Libraries
 */
class AN_Email
{
    var $CI;
    var $active;

    /**
     * Constructor - Sets up the object properties.
     */
    function __construct()
    {
        $this->CI       = &get_instance();
        $this->active    = config_item('email_active');

        require_once SWIFT_MAILSERVER;
    }

    /**
     * Send email function.
     *
     * @param string    $to         (Required)  To email destination
     * @param string    $subject    (Required)  Subject of email
     * @param string    $message    (Required)  Message of email
     * @param string    $from       (Optional)  From email
     * @param string    $from_name  (Optional)  From name email
     * @return Mixed
     */
    function send($to, $subject, $message, $from = '', $from_name = '', $debug = false)
    {
        if (!$this->active) return false;

        $mailserver_host    = config_item('mailserver_host');
        $port               = config_item('mailserver_port');
        $username           = config_item('mailserver_username');
        $password           = config_item('mailserver_password');

        require_once(APPPATH . 'libraries/vendor/phpmailer/src/PHPMailer.php');
        require_once(APPPATH . 'libraries/vendor/phpmailer/src/SMTP.php');
        require_once(APPPATH . 'libraries/vendor/phpmailer/src/Exception.php');

        try {

            $this->CI->phpmailer = new PHPMailer\PHPMailer\PHPMailer();

            $mail               = $this->CI->phpmailer;

            $mail->IsSMTP();                // telling the class to use SMTP
            $mail->SMTPDebug    = false;    // debug email sending (inspect: "Network" in browser)

            $mail->SMTPOptions  = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                )
            );

            $mail->SMTPAuth     = true;                 // Enable SMTP authentication (TRUE / FALSE)
            $mail->SMTPSecure   = "tls"; // tls/ssl
            $mail->Host         = $mailserver_host;     // sets GMAIL as the SMTP server
            $mail->Port         = $port;                // gmail smtp port
            $mail->Username     = $username;            // SMTP gmail address
            $mail->Password     = $password;            // SMTP account password

            $mail->From         = $from;                // sender's address
            $mail->FromName     = $from_name;           // sender's name

            $mail->AddAddress($to);                     // send to receiver's e-mail address
            $mail->Subject      = ($subject);           // e-mail subject
            $mail->Body         = html_entity_decode($message->html);
            $mail->Encoding     = "base64";
            $mail->CharSet      = "UTF-8";
            $mail->IsHTML(true);
            $mail->WordWrap     = 50;

            if ($mail->Send()) {
                an_log_notif('email', $subject, $to, $message->plain, 'SUCCESS');
                return true;
            } else {
                an_log_notif('email', $subject, $to, $message->plain, 'FAILED');
                return false;
            }
            $mail->SmtpClose();
        } catch (Exception $e) {
            an_log_notif('email', $subject, $to, $e->getMessage(), 'ERROR');
        }

        return false;
    }

    // GMAIL
    function send_gmail($to, $subject, $message, $from = '', $from_name = '', $debug = false)
    {
        if (!$this->active) return false;

        $mailserver_host    = '';
        $username           = '';
        $password           = '';
        $port               = 587;

        try {
            $transport = (new Swift_SmtpTransport($mailserver_host, $port, 'tls'))->setUsername($username)->setPassword($password);
            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $mail_msg = (new Swift_Message($subject))
                ->setFrom(array($from => $from_name))
                ->setTo($to)
                ->setBody($message->plain)
                ->addPart($message->html, 'text/html');

            $result = $mailer->send($mail_msg);
            if ($debug) {
                var_dump($result);
            }
            if ($result) {
                an_log_notif('email', $subject, $to, $message->plain, 'SUCCESS');
            }
            return $result;
        } catch (Exception $e) {
            if ($debug) {
                var_dump($e->getMessage());
            }
            // Should be database log in here
            an_log_notif('email', $subject, $to, $e->getMessage(), 'FAILED');
        }
        return false;
    }

    /**
     * Send email to New Member function.
     *
     * @param   Object  $member             (Required)  Member Data of Downline
     * @param   Object  $sponsor            (Required)  Member Data of Sponsor
     * @param   Object  $password           (Required)  Password of Downline
     * @param   String  $transfer_amount    (Required)  Transfer Amount
     * @return  Mixed
     */
    function send_email_new_member($member, $sponsor, $password, $transfer_amount, $view = false)
    {
        if (!$member) return false;
        if (!$sponsor) return false;
        if (!$password) return false;
        if (!$transfer_amount) return false;
        if (empty($member->email)) return false;

        if( $member->status == 1 ){
            if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reseller-active', 'email')) {
                return false;
            }
        }else{
            if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reseller-non-active', 'email')) {
                return false;
            }
        }

        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $url_login      = '<a href="' . base_url('login') . '" style="text-decoration: none; color: #FFFFFF;" target="_blank"><b>' . base_url('login') . '</b></a>';
        
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
        
        $payment_detail = '
        Silahkan lakukan <strong>Pembayaran sebesar ' . $transfer_amount . '</strong> ke rekening Perusahaan!<br />
        Bank : '.strtoupper($bill_bank).'<br />
        No. Rekening : '.$bill_no.'<br />
        Atas Nama : '.$bill_name.'';

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Pendaftaran';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $password, $text);
        $text           = str_replace("%sponsor_name%",     strtoupper($sponsor->name), $text);
        $text           = str_replace("%sponsor_username%", strtolower($sponsor->username), $text);
        $text           = str_replace("%sponsor_phone%",    $sponsor->phone, $text);
        $text           = str_replace("%company_bank%",     $bill_bank, $text);
        $text           = str_replace("%company_bill%",     $bill_no, $text);
        $text           = str_replace("%company_bill_name%",$bill_name, $text);
        $text           = str_replace("%payment_detail%",   $payment_detail, $text);
        
        $text           = str_replace("%login_url%",        $url_login, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name, true);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email to New Staff function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $password   (Required)  Password of Downline
     * @return  Mixed
     */
    function send_email_new_staff($staff, $password, $view = false)
    {
        if (!$staff) return false;
        if (!$password) return false;
        if (empty($staff->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-staff', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $url_login      = '<a href="' . base_url('login') . '" style="text-decoration: none; color: #FFFFFF;" target="_blank"><b>' . base_url('login') . '</b></a>';

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Pendaftaran';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $staff->name, $text);
        $text           = str_replace("%username%",         $staff->username, $text);
        $text           = str_replace("%password%",         $password, $text);
        $text           = str_replace("%url_login%",        $url_login, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($staff->email, $subject, $message, $from_mail, $from_name, true);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email to Sponsor (New Member) function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $upline     (Required)  Member Data of Upline
     * @return  Mixed
     */
    function send_email_sponsor($member, $sponsor, $view = false)
    {
        if (!$member) return false;
        if (!$sponsor) return false;
        if (empty($sponsor->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-sponsor', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Member Baru';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%email%",            $member->email, $text);
        $text           = str_replace("%phone%",            $member->phone, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($sponsor->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email withdraw function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $withdraw   (Required)  Data of Withdraw
     * @return  Mixed
     */
    function send_email_withdraw($member, $withdraw, $view = false)
    {
        if (!$member) return false;
        if (!$withdraw) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-withdraw', 'email')) {
            return false;
        }
        if (!$bank = an_banks($withdraw->bank)) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $currency       = config_item('currency');
        $rekening       = $withdraw->bill . ' - ' . strtoupper($withdraw->bill_name);

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Transfer Bonus';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      ucwords(strtolower($member->name)), $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%name_bank%",        $bank->nama, $text);
        $text           = str_replace("%bill%",             $rekening, $text);
        $text           = str_replace("%nominal%",          an_accounting($withdraw->nominal_receipt, $currency), $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email PIN Transfer (Sender) function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Array   $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_email_pin_transfer_sender($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-sender', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $currency       = config_item('currency');

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Transfer PIN Product';
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $pin_detail     = isset($data['pin_detail']) ? $data['pin_detail'] : '';
        $datenow        = isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d H:i:s');
        $date           = date('j M Y', strtotime($datenow));
        $hour           = date('H:i', strtotime($datenow));
        $datetime       = $date . ' Pukul ' . $hour . ' WIB';

        if ( empty($pin_detail) ) return false;
        if ( !isset($data['receiver_username']) || !isset($data['receiver_name']) || empty($data['receiver_username']) || empty($data['receiver_name'])) {
            return false;
        }

        // Set Data Detail PIN
        $transfer       = '<ol>';
        foreach ($pin_detail as $row) {
            $transfer  .= '<li>'. $row['product_name'] . ' (' . an_accounting($row['product_qty']) . ' qty)</li>';
        }
        $transfer      .= '</ol>';

        $text           = $notif->content;
        $text           = str_replace("%member_name%",          $member_name, $text);
        $text           = str_replace("%transfer_date%",        $datetime, $text);
        $text           = str_replace("%receiver_username%",    $data['receiver_username'], $text);
        $text           = str_replace("%receiver_name%",        $data['receiver_name'], $text);
        $text           = str_replace("%pin_detail%",           $transfer, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email PIN Transfer (Receiver) function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Array   $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_email_pin_transfer_receiver($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-receiver', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $currency       = config_item('currency');

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Transfer PIN Product';
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $pin_detail     = isset($data['pin_detail']) ? $data['pin_detail'] : '';
        $datenow        = isset($data['transfer_date']) ? $data['transfer_date'] : date('Y-m-d H:i:s');
        $date           = date('j M Y', strtotime($datenow));
        $hour           = date('H:i', strtotime($datenow));
        $datetime       = $date . ' Pukul ' . $hour . ' WIB';

        if ( empty($pin_detail) || !is_array($pin_detail) ) return false;
        if ( !isset($data['sender_username']) || !isset($data['sender_name']) || empty($data['sender_username']) || empty($data['sender_name'])) {
            return false;
        }

        // Set Data Detail PIN
        $transfer       = '<ol>';
        foreach ($pin_detail as $row) {
            $transfer  .= '<li>'. $row['product_name'] . ' (' . an_accounting($row['product_qty']) . ' qty)</li>';
        }
        $transfer      .= '</ol>';

        $text           = $notif->content;
        $text           = str_replace("%member_name%",          $member_name, $text);
        $text           = str_replace("%transfer_date%",        $datetime, $text);
        $text           = str_replace("%sender_username%",      $data['sender_username'], $text);
        $text           = str_replace("%sender_name%",          $data['sender_name'], $text);
        $text           = str_replace("%pin_detail%",           $transfer, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email change password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_change_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-change-password', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password']) || !isset($data['type_password'])) return false;
        if (empty($data['password']) || empty($data['type_password'])) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $login_url      = '<a href="'. base_url('login') .'" style="color:#FFFFFF; text-decoration:none; font-weight:600;">'. base_url('login') .'</a>';

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Ganti Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);
        $text           = str_replace("%type_password%",    $data['type_password'], $text);
        $text           = str_replace("%base_url%",         $login_url, $text);
        $text           = str_replace("%login_url%",        $login_url, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email reset password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_reset_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reset-password', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password']) || !isset($data['type_password'])) return false;
        if (empty($data['password']) || empty($data['type_password'])) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $login_url      = '<a href="'. base_url('login') .'" style="color:#FFFFFF; text-decoration:none; font-weight:600;">'. base_url('login') .'</a>';

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Ganti Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);
        $text           = str_replace("%type_password%",    $data['type_password'], $text);
        $text           = str_replace("%login_url%",        $login_url, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email forget password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_forget_password($member, $data, $view = false)
    {
        if (!$member) return false;
        if (!$data) return false;
        if (empty($member->email)) return false;

        if (!$notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-forgot-password', 'email')) {
            return false;
        }
        if ($notif->status == 0) return false;
        if (empty($notif->content)) return false;
        if (!isset($data['password'])) return false;
        if (empty($data['password'])) return false;


        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $login_url      = '<a href="'. base_url('login') .'" style="color:#FFFFFF; text-decoration:none; font-weight:600;">'. base_url('login') .'</a>';

        // Set Variable Email
        $subject        = (!empty($notif->title)) ? $notif->title : 'Informasi Reset Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);
        $text           = str_replace("%login_url%",        $login_url, $text);

        $plain_mail     = an_html2text($text);
        $html_mail      = an_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email shop order function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @return  Mixed
     */
    function send_email_shop_order($member, $shop_order, $view = false)
    {
        if (!$member) return false;
        if (!$shop_order) return false;
        if (empty($member->email)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '(' . $shop_order->invoice . ')' : '';
        $subject        = 'Informasi Pemesanan Produk';
        if ($shop_order->status == 1) {
            $subject = 'Informasi Konfirmasi Pesanan';
        }
        if ($shop_order->status == 4) {
            $subject = 'Informasi Pembatalan Pesanan';
        }

        $subject_email  = $subject . ' ' . $invoice;

        $html_mail      = an_notification_shop_template($shop_order, $subject, 'member', $member);
        $plain_mail     = an_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }

    /**
     * Send email shop order function.
     *
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @param   Boolena $view       (Required)  View email
     * @return  Mixed
     */
    function send_email_shop_order_stockist($member, $shop_order, $view = false)
    {
        if (!$member) return false;
        if (!$shop_order) return false;
        if (empty($member->email)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '(' . $shop_order->invoice . ')' : '';
        $subject        = 'Informasi Pemesanan Produk';
        if ($shop_order->status == 1) {
            $subject = 'Informasi Konfirmasi Pesanan';
        }
        if ($shop_order->status == 4) {
            $subject = 'Informasi Pembatalan Pesanan';
        }

        $subject_email  = $subject . ' ' . $invoice;
        $html_mail      = an_notification_shop_template($shop_order,  $subject, 'stockist', $member);
        $plain_mail     = an_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($shop_order->email, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }
    
    /**
     * Send email confirm shipping function.
     *
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @param   Boolena $view       (Required)  View email
     * @return  Mixed
     */
    function send_email_confirm_shipping($shop_order, $view = false)
    {
        if (!$shop_order) return false;
        if (empty($shop_order->email_consumer)) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '(' . $shop_order->invoice . ')' : '';
        $subject        = 'Informasi Konfirmasi Pengiriman Produk';

        $subject_email  = $subject . ' ' . $invoice;
        $html_mail      = an_notification_confirm_shipping_template($shop_order,  $subject);
        $plain_mail     = an_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ($view) {
            return $message;
        } else {
            $send       = $this->send($shop_order->email_consumer, $subject, $message, $from_mail, $from_name);
            if ($send) {
                return true;
            }
            return false;
        }
    }
}
