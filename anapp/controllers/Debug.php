<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug Controller.
 *
 * @class     Debug
 * @version   1.0.0
 */
class Debug extends Public_Controller
{

    /**
     * Update Member HLR Code of Phone
     * @author	Iqbal
     */
    public function decode_pwd($password = '')
    {
        $this->benchmark->mark('started');
        $this->load->library('user_agent');

        $password   = $password ? $password : 'P@SS4alphanet';
        $password   = $password ? $password : '123@alphanet';
        $pass       = an_password_hash($password);
        $encrypt    = an_encrypt($password);

        echo "<pre>";

        echo "Password : " . $password . br();
        echo "----------------------------------------------" . br();
        echo "  encrypt  " . br();
        echo "----------------------------------------------" . br();
        echo "MD5      : " . md5($password) . br();
        echo "Hash     : " . $pass . br(2);
        echo "encrypt  : " . $encrypt . br(2);
        echo "----------------------------------------------" . br();

        if (password_verify($password, $pass)) {
            echo 'Password is valid!';
        } else {
            echo 'Invalid password.';
        }
        echo br(5);

        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        echo 'browser : ' .  $this->agent->browser() . ' ' . $this->agent->version() . br();
        echo 'mobile : ' .  $this->agent->mobile() . br();
        echo 'robot : ' .  $this->agent->robot() . br();
        echo 'referrer : ' .  $this->agent->referrer() . br(3);

        echo 'agent : ' .  $agent . br();
        echo $this->agent->platform() . br();
        echo $this->agent->agent_string() . br();

        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo br() . 'Elapsed Time : ' . $elapsed_time . ' seconds' . "\n";
        echo "</pre>";
    }

    function email_register($id_member = 0, $send = false)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        if (!$id_member) die('Member not found!');

        if (!$member  = an_get_memberdata_by_id($id_member)) die('Member not found!');
        if (!$sponsor = an_get_memberdata_by_id($member->sponsor)) die('Sponsor not found!');
        if (!$upline  = an_get_memberdata_by_id($member->parent)) die('Upline not found!');

        $member->email  = 'radenmuhamadiqbalmuchridin@gmail.com';
        $member->phone  = '087776662002';
        $sponsor->email = 'radenmuhamadiqbalmuchridin@gmail.com';
        $sponsor->phone = '087776662002';
        $upline->email  = 'radenmuhamadiqbalmuchridin@gmail.com';
        
        $transfer_amount= 100000;
        $rand           = random_string('alnum', 8);

        echo '<pre style="color:#111">';
        echo '----------------------------------------------------' . br();
        echo '              Send Notif New Member ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Member    : ' . $member->id . br();
        echo ' Username     : ' . $member->username . br();
        echo ' Name         : ' . $member->name . br();
        echo ' Email        : ' . $member->email . br();
        echo ' Phone        : ' . $member->phone . br();
        echo '----------------------------------------------------' . br();
        echo ' Password     : ' . $rand . br();
        echo '----------------------------------------------------' . br();
        echo ' Sponsor      : ' . $sponsor->username . ' / ' . $sponsor->name . br();
        echo ' Email        : ' . $sponsor->email . br();
        echo ' Phone        : ' . $sponsor->phone . br();
        echo '----------------------------------------------------' . br();
        echo ' Upline       : ' . $upline->username . ' / ' . $upline->name . br();
        echo '----------------------------------------------------' . br(3);

        if ($send) {
            //$this->an_email->send_email_new_member($member, $sponsor, $rand);
            //$this->an_email->send_email_sponsor($member, $sponsor, $upline);
            $wa = $this->an_wa->send_wa_new_member($member, $sponsor, $rand);
        } else {
            $wa = $this->an_wa->send_wa_new_member($member, $sponsor, $rand, TRUE);
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp New Member : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa;
            echo br(3);

            $wa_sponsor = $this->an_wa->send_wa_sponsor($member, $sponsor, $upline, TRUE);
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Sponsor : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa_sponsor;
            echo br(3);

            $mail = $this->an_email->send_email_new_member($member, $sponsor, $rand, $transfer_amount, TRUE);
            if (isset($mail->html)) {
                echo '</pre>';
                echo $mail->html;
                echo '<pre>';
                echo br(3);
            }

            $mail_sponsor = $this->an_email->send_email_sponsor($member, $sponsor, $upline, TRUE);
            if (isset($mail_sponsor->html)) {
                echo '</pre>';
                echo $mail_sponsor->html;
                echo '<pre>';
                echo br(3);
            }
        }

        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_withdraw($id = 0, $send = false)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        if (!$id) die('Withdraw not found!');

        if (!$withdraw = $this->Model_Bonus->get_withdraw_by_id($id)) die('Sponsor not found!');
        if (!$member  = an_get_memberdata_by_id($withdraw->id_member)) die('Member not found!');

        $member->email  = 'developer.dhaeka@gmail.com';
        $member->phone  = '085211838515';

        echo '<pre style="color:#111">';
        echo '----------------------------------------------------' . br();
        echo '              Send Notif Withdraw ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Member    : ' . $member->id . br();
        echo ' Username     : ' . $member->username . br();
        echo ' Name         : ' . $member->name . br();
        echo ' Email        : ' . $member->email . br();
        echo ' Phone        : ' . $member->phone . br();
        echo '----------------------------------------------------' . br();
        echo '</pre>';

        if ($send) {
            $this->an_email->send_email_withdraw($member, $withdraw);
        } else {
            $wa = $this->an_wa->send_wa_withdraw($member, $withdraw, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Withdraw : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa;
            echo br(3);
            echo '</pre>';

            $mail = $this->an_email->send_email_withdraw($member, $withdraw, TRUE);
            if (isset($mail->html)) {
                echo '<pre style="color:#111">';
                echo 'Email Withdraw : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo '</pre>';
                echo $mail->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_pin_transfer($sender = '', $receiver = '', $date = '', $send = false)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        if (!$sender || !$receiver || !$date) die('PIN Transfer not found!');
        $date = str_replace('%20', ' ', $date);

        echo '<pre style="color:#222">';
        echo '----------------------------------------------------' . br();
        echo '              Send Notif PIN Transfer ' . br();
        echo '----------------------------------------------------' . br();
        echo ' Pengirim     : ' . $sender . br();
        echo ' Penerima     : ' . $receiver . br();
        echo ' Tanggal      : ' . $date . br();
        echo '----------------------------------------------------' . br();
        echo '</pre>';

        $condition  = str_replace('%s%', $sender, ' AND %username_sender% LIKE "%%s%%"');
        $condition .= str_replace('%s%', $receiver, ' AND %username% LIKE "%%s%%"');
        $condition .= str_replace('%s%', $date, ' AND DATE(%datecreated%) = "%s%"');

        if (!$transfer_list = $this->Model_Shop->get_all_pin_transfer(0, 0, $condition)) {
            die('PIN Transfer not found!');
        }

        if (!$sender  = an_get_memberdata_by_id($transfer_list[0]->id_member_sender)) die('Member Pengirim not found!');
        if (!$receiver  = an_get_memberdata_by_id($transfer_list[0]->id_member)) die('Member Penerima not found!');

        $sender->email      = 'developer.dhaeka@gmail.com';
        $sender->phone      = '085861066808';
        $receiver->email    = 'developer.dhaeka@gmail.com';
        $receiver->phone    = '085861066808';

        echo '<pre style="color:#222">';
        echo ' Pengirim ' . br();
        echo '----------------------------------------------------' . br();
        echo ' Username     : ' . $sender->username . br();
        echo ' Name         : ' . $sender->name . br();
        echo ' Email        : ' . $sender->email . br();
        echo ' Phone        : ' . $sender->phone . br();
        echo '----------------------------------------------------' . br(2);
        echo ' Penerima ' . br();
        echo '----------------------------------------------------' . br();
        echo ' Username     : ' . $receiver->username . br();
        echo ' Name         : ' . $receiver->name . br();
        echo ' Email        : ' . $receiver->email . br();
        echo ' Phone        : ' . $receiver->phone . br();
        echo '----------------------------------------------------' . br(3);
        echo '</pre>';

        $data = array(
            'receiver_username' => $receiver->username,
            'receiver_name'     => $receiver->name,
            'sender_username'   => $sender->username,
            'sender_name'       => $sender->name,
            'transfer_date'     => $transfer_list[0]->datecreated,
        );

        $product_transfer       = array();
        $detail_pin             = array();
        foreach ($transfer_list as $key => $row) {
            $detail_pin[$row->product_name] = $row->qty;
            $product_transfer[$row->product] = array(
                'product_id'        => $row->product,
                'product_name'      => $row->product_name,
                'product_qty'       => $row->qty,
            );
        }

        $data['pin_detail'] = $product_transfer;

        if ($send) {
            $this->an_email->send_email_pin_transfer_sender($sender, $data);
            $this->an_email->send_email_pin_transfer_receiver($receiver, $data);
            $this->an_wa->send_wa_pin_transfer_sender($sender, $data);
            $this->an_wa->send_wa_pin_transfer_receiver($receiver, $data);
        } else {
            $wa = $this->an_wa->send_wa_pin_transfer_sender($sender, $data, TRUE);
            if (isset($wa)) {
                echo 'WA Pengirim PIN : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo '<pre style="color:#222">';
                echo $wa;
                echo '</pre>';
                echo br(3);
            }

            $wa2 = $this->an_wa->send_wa_pin_transfer_receiver($receiver, $data, TRUE);
            if (isset($wa)) {
                echo 'WA Penerima PIN : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo '<pre style="color:#222">';
                echo $wa2;
                echo '</pre>';
                echo br(3);
            }

            $mail = $this->an_email->send_email_pin_transfer_sender($sender, $data, TRUE);
            if (isset($mail->html)) {
                echo 'Email Pengirim : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo $mail->html;
                echo br(3);
            }

            $mail2 = $this->an_email->send_email_pin_transfer_receiver($receiver, $data, TRUE);
            if (isset($mail2->html)) {
                echo 'Email Penerima : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo $mail2->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_password($id_member = 0, $send = false)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        if (!$id_member) die('Member not found!');

        if (!$member  = an_get_memberdata_by_id($id_member)) die('Member not found!');

        $member->email  = 'developer.dhaeka@gmail.com';
        $member->phone  = '085211838515';
        $rand           = random_string('alnum', 8);
        $data           = array('password' => $rand, 'type_password' => 'Login');

        echo '<pre  style="color:#111">';
        echo '----------------------------------------------------' . br();
        echo '              Send Notif New Member ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Member    : ' . $member->id . br();
        echo ' Username     : ' . $member->username . br();
        echo ' Name         : ' . $member->name . br();
        echo ' Email        : ' . $member->email . br();
        echo ' Phone        : ' . $member->phone . br();
        echo '----------------------------------------------------' . br();
        echo ' Password     : ' . $rand . br();
        echo '----------------------------------------------------' . br();
        echo '</pre>';

        if ($send) {
            $this->an_email->send_email_change_password($member, $data);
            $this->an_email->send_email_forget_password($member, $data);
            $this->an_email->send_email_reset_password($member, $data);
        } else {
            $wa = $this->an_wa->send_wa_change_password($member, $data, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Change Password : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa;
            echo br(3);
            echo '</pre>';

            $wa2 = $this->an_wa->send_wa_forget_password($member, $data, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Forget Password : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa2;
            echo br(3);
            echo '</pre>';

            $wa3 = $this->an_wa->send_wa_reset_password($member, $data, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Reset Password : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa3;
            echo br(3);
            echo '</pre>';

            $mail = $this->an_email->send_email_change_password($member, $data, TRUE);
            if (isset($mail->html)) {
                echo 'Email Change Password : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo $mail->html;
                echo br(3);
            }

            $mail2 = $this->an_email->send_email_forget_password($member, $data, TRUE);
            if (isset($mail2->html)) {
                echo 'Email Forget Password : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo $mail2->html;
                echo br(3);
            }

            $mail3 = $this->an_email->send_email_reset_password($member, $data, TRUE);
            if (isset($mail3->html)) {
                echo 'Email Reset Password : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo $mail3->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_shop_order($id_order = 0, $send = false)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        $stockist           = false;   
        if (!$id_order) die('Product Order not found!');
        if (!$shop_order = $this->Model_Shop->get_shop_orders($id_order)) die('Product Order not found!');
        if (!$member = an_get_memberdata_by_id($shop_order->id_member)) die('Member not found!');

        if ( $shop_order->id_stockist ) {
            if (!$stockist = an_get_memberdata_by_id($shop_order->id_stockist)) die('Stockist not found!');
        }
        

        $member->email      = 'developer.dhaeka@gmail.com';
        $shop_order->email  = 'saddam.almahali@gmail.com';

        if ( $stockist ) {
            $stockist->email  = 'developer.dhaeka@gmail.com';
        }

        echo '<pre style="color:#333">';
        echo '----------------------------------------------------' . br();
        echo '              Send Notif Product Order ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Order     : ' . $shop_order->id . br();
        echo ' Name         : ' . $shop_order->name . br();
        echo ' Email        : ' . $shop_order->email . br();
        echo ' Phone        : ' . $shop_order->phone . br();
        echo '----------------------------------------------------' . br();
        echo ' Username     : ' . $member->username . br();
        echo ' Name         : ' . $member->name . br();
        echo ' Email        : ' . $member->email . br();
        echo ' Phone        : ' . $member->phone . br();
        echo '----------------------------------------------------' . br();
        echo '</pre>';

        if ($send) {
            $mail = $this->an_email->send_email_shop_order($member, $shop_order);
            if ( $stockist ) {
                $mail = $this->an_email->send_email_shop_order_stockist($stockist, $shop_order);
            }
        } else {
            $wa = $this->an_wa->send_wa_generate_product($member, $shop_order, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp PO : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa;
            echo br(3);
            echo '</pre>';

            $wa2 = $this->an_wa->send_wa_shop_order($member, $shop_order, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp PO : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa2;
            echo br(3);
            echo '</pre>';

            $wa3 = $this->an_wa->send_wa_shop_order_stockist($stockist, $shop_order, TRUE);
            echo '<pre style="color:#111">';
            echo '----------------------------------------------------' . br();
            echo 'WhatsApp Stockist : ' . br();
            echo '----------------------------------------------------' . br(2);
            echo $wa3;
            echo br(3);
            echo '</pre>';

            $mail1 = $this->an_email->send_email_shop_order($member, $shop_order, TRUE);

            if (isset($mail1->html)) {
                echo '<pre style="color:#333">';
                echo 'Email Sales : ' . br();
                echo '----------------------------------------------------' . br(2);
                echo '</pre>';
                echo $mail1->html;
                echo br(3);
            }
            if ( $stockist ) {
                $mail2 = $this->an_email->send_email_shop_order_stockist($stockist, $shop_order, TRUE);
                if (isset($mail2->html)) {
                    echo '<pre style="color:#333">';
                    echo 'Email Stockist : ' . br();
                    echo '----------------------------------------------------' . br(2);
                    echo '</pre>';
                    echo $mail2->html;
                    echo br(3);
                }
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    // ========================================
    // Function Helper Check
    // ========================================
    function getAnchestry($id_member = 0, $debug = true)
    {
        if ($id_member > 0) {
            $data_member = an_get_memberdata_by_id($id_member);
            if ($data_member) {
                $cfg_package    = config_item('package');
                $member_package_name   = isset($cfg_package[$data_member->package]) ? $cfg_package[$data_member->package] : $data_member->package;

                echo '<pre>';
                echo '============================' . br();
                echo 'Informasi Member' . br();
                echo '============================' . br();
                echo 'ID Member : ' . $data_member->id . br();
                echo 'Nama Member : ' . $data_member->name . br();
                echo 'Peringkat : ' . $member_package_name . br();
                echo '============================' . br(2);

                $data_ancestry[$id_member] = $id_member;
                $ancestry       = an_ancestry($id_member);
                $ancestry       = explode(',', $ancestry);
                $ancestry_detail = array();

                if (count($ancestry) > 0) {
                    $gen = 1;
                    foreach ($ancestry as $key => $_id) {
                        if ($_id == 1) {
                            continue;
                        }

                        if (!isset($ancestry_detail[$_id])) {
                            $ancestry_detail[$_id] = $_id;
                        }

                        if ($gen == 5) {
                            break;
                        }
                        $gen++;
                    }
                }

                if (count($ancestry_detail) > 0) {
                    echo '============================' . br();
                    echo 'Informasi Turunan' . br();
                    echo '============================' . br(2);
                    $gen = 1;
                    foreach ($ancestry_detail as $key => $row) {
                        $data = an_get_memberdata_by_id($row);
                        $sp_name = isset($cfg_package[$data->package]) ? $cfg_package[$data->package] : $data->package;;

                        if ($debug) {
                            $sp_member = an_get_memberdata_by_id($data->sponsor);
                            echo 'Data ke : ' . $gen . br();
                            echo '----------------------------' . br();
                            echo 'Member ID : ' . $data->id . br();
                            echo 'Member Name : ' . $data->name . br();
                            echo 'Sponsor Name : ' . ($sp_member ? $sp_member->name : '-') . br();
                            echo 'Sponsor Code : ' . ($sp_member ? $sp_member->username : '-') . br();
                            echo 'Peringkat : ' . $sp_name . br();
                            echo '----------------------------' . br(2);
                            $gen++;
                        }
                    }
                }

                echo '============================';
            } else {
                echo '============================' . br();
                echo 'Data Not Found' . br();
                echo '============================' . br();
            }
            die();
            echo '<pre>';
        }
    }

    function member_available_position($id_member = 0)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        echo '<pre style="color:#333">';
        echo '----------------------------------------------------' . br();
        echo ' Get Upline Available Position ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Member    : ' . $id_member . br();
        echo '----------------------------------------------------' . br(2);

        if ( $id_member ) {
            if (!$memberdata  = an_get_memberdata_by_id($id_member) ) die('Member not found!');
            echo ' Username     : ' . $memberdata->username . br();
            echo ' Nama         : ' . $memberdata->name . br();
            echo '----------------------------------------------------' . br(2);
            $sponsor        = $this->Model_Member->get_upline_available_position($id_member);
        } else {
            $sponsor        = $this->Model_Member->get_upline_available_position();
        }

        var_dump($sponsor);

        echo br(2). '----------------------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    function member_board($id_member_board = 0)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        echo '<pre style="color:#333">';
        echo '----------------------------------------------------' . br();
        echo ' Get Member Board ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Board     : ' . $id_member_board . br();
        echo '----------------------------------------------------' . br(2);

        if ( $id_member_board ) {
            if ( ! $memberboard = an_get_memberboard_by('id', $id_member_board) ) die('Member Board not found!');
            echo ' ID Member    : ' . $memberboard->id_member . br();
            echo ' Username     : ' . $memberboard->username . br();
            echo ' Board        : ' . $memberboard->board . br();
            echo '----------------------------------------------------' . br(2);
            kb_generate_downline_board_tree($id_member_board);
        } else {
            echo ' ID Member Board required.';
        }

        echo br(2). '----------------------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    function generate_board_tree($id_board = 0)
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        if (!$memberboard  = an_get_memberboard_by('id', $id_board) ) die('Member Board not found!');

        echo '<pre style="color:#333">';
        echo '----------------------------------------------------' . br();
        echo '              Generate Board Tree ' . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Board     : ' . $memberboard->id . br();
        echo '----------------------------------------------------' . br();
        echo ' ID Member    : ' . $memberboard->id_member . br();
        echo ' Username     : ' . $memberboard->username . br();
        echo '----------------------------------------------------' . br();
        echo ' Status       : ' . $memberboard->status . br();
        echo '----------------------------------------------------' . br();

        $message    = '';
        $board      = kb_generate_board_tree($memberboard->id, $memberboard->dateactived, $message);
        echo " Message      : ". $message .br(2);

        var_dump($board);

        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    function generate_member($sponsor_id = 1, $length = 1, $debug = true, $username = '')
    {
        set_time_limit(0);
        $this->benchmark->mark('started');

        echo '<pre style="color:#333">';
        echo '----------------------------------------------------' . br();
        echo ' Get Upline Available Position ' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function       : ' . ( $debug ? 'View' : 'Save' ) . br();
        echo ' ID Sponsor     : ' . $sponsor_id . br();
        echo ' length         : ' . $length . br();
        echo '----------------------------------------------------' . br(2);
        echo '</pre>';

        if ( $memberdata  = an_get_memberdata_by_id($sponsor_id) ) {
            // Generate Data Member 
            an_generate_member($sponsor_id, $length, $debug, $username);
        } else {
            echo '<pre style="color:red">  Data Sponsor not found! ' .br(). '</pre>';
        }

        echo '<pre style="color:#333">';
        echo br(2). '----------------------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    function generate_transaction($member_id, $product_id, $yearmonth = '', $count = 1,  $debug = true)
    {
        if (!$member_id) return false;

        if (!$product_id) return false;

        if ($count) {
            echo 'Generate Transaction Count : ' . $count;

            $current_member = an_get_memberdata_by_id($member_id);

            $product = an_products($product_id);
            include APPPATH . '/third_party/faker/autoload.php';
            $faker = Faker\Factory::create('id_ID');
            $faker->seed(1);

            $yearmonth                              = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m');
            $year                                   = date('Y', strtotime($yearmonth));
            $month                                  = date('n', strtotime($yearmonth));

            // -------------------------------------------------------
            // Set Data Product
            // -------------------------------------------------------
            $post_products[]                        = array(
                'id'    =>  an_encrypt($product_id),
                'qty'   => 1
            );

            // -------------------------------------------------------
            // Get Country Data
            // -------------------------------------------------------
            $country                                = 'IDN';
            $data_address['country']                = $country;

            // -------------------------------------------------------
            // Get Province Data
            // -------------------------------------------------------
            $list_province                          = an_provinces();
            $province_change                        = $faker->randomElement($list_province);
            $province_code                          = $province_change->id;
            $data_address['province']               = $province_change;

            // -------------------------------------------------------
            // Get District Data
            // ------------------------------------------------------- 
            $list_district                          = an_districts_by_province($province_change->id, '');
            $district_change                        = $faker->randomElement($list_district);
            $district_code                          = $district_change->id;
            $data_address['district']               = $district_change;

            // -------------------------------------------------------
            // Get Sub District Data
            // ------------------------------------------------------- 
            $list_subdistrict                       = an_subdistricts_by_district($district_change->id);
            $subdistrict_change                     = $faker->randomElement($list_subdistrict);
            $data_address['subdistrict']            = $subdistrict_change;

            // -------------------------------------------------------
            // Get Village Data
            // ------------------------------------------------------- 
            $village                                = strtoupper($subdistrict_change->subdistrict_name);
            $data_address['village']                = $village;

            // -------------------------------------------------------
            // Get Member Order Data
            // -------------------------------------------------------
            $gender                                 = $faker->randomElement(array('M', 'F'));
            $name                                   = $faker->name($gender == 'M' ? 'male' : 'female');
            $address                                = $faker->address;
            $pob                                    = $faker->city;
            $npwp                                   = 0;
            $email                                  = $faker->unique()->safeEmail;
            $phone                                  = $faker->unique()->phoneNumber;

            // -------------------------------------------------------
            // Set Data Product Detail
            // ------------------------------------------------------- 
            $datetime                               = $yearmonth ? date('Y-m-d H:i:s', strtotime($yearmonth)) : date('Y-m-d H:i:s');
            $data_product                           = array();
            $total_discount                         = 0;
            $total_point                            = 0;
            $total_qty                              = 0;
            $total_pv                               = 0;
            $total_unit                             = 0;
            $total_price                            = 0;
            $total_price_credit                     = 0;
            $total_weight                           = 0;

            foreach ($post_products as $item) {

                $productId                          = isset($item['id']) ? an_decrypt($item['id']) : 0;
                $qty                                = isset($item['qty']) ? $item['qty'] : 0;
                if (!$productId || !$qty) {
                    continue;
                }
                if (!$getProduct = an_products($productId, true)) {
                    continue;
                }

                $product_name                       = isset($getProduct->name) ? $getProduct->name : '';
                $product_pv                         = isset($getProduct->pv) ? $getProduct->pv : 0;
                $product_price                      = isset($getProduct->price) ? $getProduct->price : 0;
                $product_price_credit               = isset($getProduct->price) ? $getProduct->price : 0;
                $product_weight                     = isset($getProduct->weight) ? $getProduct->weight : 0;
                $product_unit                       = isset($getProduct->unit) ? ($getProduct->unit + 0) : 0;

                $subtotal                           = ($qty * $product_price);
                $subtotal_credit                    = ($qty * $product_price_credit);
                $subtotal_weight                    = ($qty * $product_weight);
                $subtotal_unit                      = ($qty * $product_unit);
                $subtotal_pv                        = ($qty * $product_pv);

                $data_product[] = array(
                    'id'                => $productId,
                    'qty'               => $qty,
                    'name'              => $product_name,
                    'pv'                => $product_pv,
                    'unit'              => $product_unit,
                    'price'             => $product_price,
                    'price_cash'        => $product_price,
                    'price_credit'      => $product_price_credit,
                    'price_order'       => $product_price,
                    'weight'            => $product_weight,
                    'subtotal'          => $subtotal,
                    'subtotal_cash'     => $subtotal,
                    'subtotal_credit'   => $subtotal_credit,
                    'subtotal_order'    => $subtotal,
                    'subtotal_pv'       => $subtotal_pv,
                    'subtotal_unit'     => $subtotal_unit,
                    'subtotal_weight'   => $subtotal_weight,
                );

                $total_qty              += $qty;
                $total_pv               += ($subtotal_pv);
                $total_unit             += ($subtotal_unit);
                $total_price            += ($subtotal);
                $total_price_credit     += ($subtotal_credit);
                $total_weight           += ($subtotal_weight);
            }

            // -------------------------------------------------------
            // Set Data Sales Order
            // -------------------------------------------------------
            $sales_order_dp                         = config_item('sales_order_dp');
            $invoice_prefix                         = config_item('invoice_prefix');
            $invoice_number                         = an_generate_invoice();
            $invoice                                = $invoice_prefix . $invoice_number; // XX-000001
            $code_unique                            = an_generate_shop_order();
            $total_payment_cash                     = $total_price + $code_unique - $total_discount;
            $total_payment_credit                   = $total_price_credit + $code_unique - $total_discount;
            $total_payment                          = $total_payment_cash;
            $down_payment                           = ( $total_payment * $sales_order_dp ) / 100;

            $data_shop_order        = array(
                'invoice'           => $invoice,
                'id_member'         => $current_member->id,
                'products'          => maybe_serialize($data_product),
                'total_qty'         => $total_qty,
                'total_pv'          => $total_pv,
                'total_unit'        => $total_unit,
                'weight'            => $total_weight,
                'subtotal'          => $total_price,
                'shipping'          => 0,
                'unique'            => $code_unique,
                'discount'          => $total_discount,
                'total_payment'     => $total_payment,
                'down_payment'      => $down_payment,
                'voucher'           => '',
                'payment_type'      => 'cash',
                'payment_method'    => 'cash',
                'shipping_method'   => '',
                'status'            => 4,
                'name'              => ucwords(strtolower($name)),
                'phone'             => $phone,
                'email'             => strtolower($email),
                'province'          => $province_change->province_name,
                'city'              => $district_change->district_name,
                'subdistrict'       => $subdistrict_change->subdistrict_name,
                'address'           => $address,
                'created_by'        => $current_member->username,
                'confirmed_by'      => 'admin',
                'modified_by'       => 'admin',
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
                'dateconfirm'       => $datetime,
            );

            // var_dump($data_shop_order);
            // die();

            for ($i = 0; $i < $count; $i++) {
                $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);

                $data_order_detail = array();

                foreach ($data_product as $key => $row) {
                    $price_order        = $row['price'];
                    $subtotal_order     = $row['subtotal'];
                    $data_order_detail[$key] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $current_member->id,
                        'product'       => $row['id'],
                        'price_cash'    => $row['price'],
                        'price_credit'  => $row['price_credit'],
                        'price_order'   => $price_order,
                        'qty'           => $row['qty'],
                        'pv'            => $row['pv'],
                        'unit'          => $row['unit'],
                        'subtotal'      => $subtotal_order,
                        'subtotal_pv'   => $row['subtotal_pv'],
                        'subtotal_unit' => $row['subtotal_unit'],
                        'discount'      => 0,
                        'weight'        => $row['weight'],
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime,
                    );
                }

                foreach ($data_order_detail as $row) {
                    // -------------------------------------------------
                    // Save Shop Order Detail
                    // -------------------------------------------------
                    $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
                }

                // -------------------------------------------------
                // Save Confirm Order
                // -------------------------------------------------
                $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id);

                $total_omzet        = $shop_order->subtotal - $shop_order->discount;
                $total_payment      = $shop_order->total_payment - $shop_order->unique;
                $data_member_omzet  = array(
                    'id_member'     => $current_member->id,
                    'omzet'         => $total_omzet,
                    'amount'        => $total_payment,
                    'pv'            => $shop_order->total_pv,
                    'unit'          => $shop_order->total_unit,
                    'status'        => 'salesorder',
                    'desc'          => 'Omzet Sales Order (#' . $shop_order->invoice . ') ',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );

                $insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet);

                echo '<pre>';
                echo 'Data Produk : ' . br();
                $data_order = array(
                    'data_product'                  => $data_product,
                    'data_shop_order'               => $data_shop_order,
                    'data_order_detail'             => $data_order_detail
                );
                echo var_dump($data_order);
                echo '</pre>';
            }
        }
    }

    function check_call_month_in_two_date($date1, $date2)
    {
        $month = call_month_in_two_date($date1, $date2);

        echo $month;
    }

    function dateComparation($date1, $date2)
    {
        // $date1 = date('Y-m', strtotime($date1));

        // $date2 = date('Y-m', strtotime($date2));

        echo 'Sama Dengan       = ' . ($date1 == $date2 ? 'Ya' : 'Tidak') . br();
        echo 'Lebih Besar Smdg  = ' . ($date1 >= $date2 ? 'Ya' : 'Tidak') . br();
        echo 'Lebih Kecil Smdg  = ' . ($date1 <= $date2 ? 'Ya' : 'Tidak') . br();
    }
    
    function bonusreferral(){
        an_calculate_bonus_referral(3);
    }
    
    function getupline(){
        $upline = $this->Model_Member->get_upline_available_position(2);
        
        echo "<pre>";
        print_r($upline);
        echo "</pre>";
    }
    
    function fastpay(){
        $merchant       = 'AlphaNet';
        $merchant_id	= '33960';
        $user_id 		= 'bot'.$merchant_id;
        $passw 			= 'p@ssw0rd';
        $bill_no 		= date('Ymdhis');
        $bill_date 		= date('Y-m-d H:i:s');
        $bill_expired 	= date("Y-m-d H:i:s", strtotime ("+1 hour"));
        $payment_channel = '402';
        $signature		= sha1(md5(($user_id.$passw.$bill_no)));
        $env			= 'dev';
        
        $data = array('request' 			=> 'Transmisi Info Detil Pembelian' ,
        				'merchant_id' 		=> $merchant_id ,
        				'merchant'			=> $merchant ,
        				'bill_no'			=> $bill_no,
        				'bill_reff'			=> 'AZ'.$bill_no ,
        				'bill_date'			=> $bill_date,
        				'bill_expired'		=> $bill_expired,
        				"bill_desc"			=> "Pembayaran #".$bill_no,
        				"bill_currency"		=> "IDR",
        				"bill_gross"		=> "1000000",
        				"bill_miscfee"		=> "500000",
        				"bill_total"		=> "1500000",
        				"cust_no"			=> "A001",
        				"cust_name"			=> "faspay",
        				"cust_lastname"		=> "test",
        				"payment_channel"	=> $payment_channel,
        				"pay_type"			=> "1",
        				"bank_userid"		=> "",
        				"msisdn"			=> "08123456789",
        				"email"				=> "test@test.com",
        				"terminal"			=> "10",
        				"billing_name"		=> "test faspay",
        				"billing_lastname"	=> "0",
        				"billing_address"	=> "jalan pintu air raya",
        				"billing_address_city"		=> "Jakarta Pusat",
        				"billing_address_region"	=> "DKI Jakarta",
        				"billing_address_state"		=> "Indonesia",
        				"billing_address_poscode"	=> "10710",
        				"billing_msisdn"			=> "08123456789",
        				"billing_address_country_code"	=> "ID",
        				"receiver_name_for_shipping"	=> "Faspay Test",
        				"shipping_lastname"				=> "",
        				"shipping_address"				=> "jalan pintu air raya",
        				"shipping_address_city"			=> "Jakarta Pusat",
        				"shipping_address_region"		=> "DKI Jakarta",
        				"shipping_address_state"		=> "Indonesia",
        				"shipping_address_poscode"		=> "10710",
        				"shipping_msisdn"				=> "08123456789",
        				"shipping_address_country_code"	=> "ID",
        				"item" => array('id' 			=> "XYZ001" ,
        								"product"		=> "Iphone 12",
        								"qty"			=> "1",
        								"amount"		=> "1000000",
        								"payment_plan"	=> "01",
        								"merchant_id"	=> "BC001",
        								"tenor"			=> "00",
        								"type"			=> "Smartphone"
        
        				 ),
        				"reserve1"						=> "",
        				"reserve2"						=> "",
        				"signature"						=> $signature
        
        
         );
        
        $request = json_encode($data);
        
        if ($env == 'dev') {
        	$url='https://dev.faspay.co.id/cvr/300011/10';
        }else{
        	$url='https://web.faspay.co.id/cvr/300011/10';
        }
        
        $c = curl_init ($url);
        curl_setopt ($c, CURLOPT_POST, true);
        curl_setopt ($c, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt ($c, CURLOPT_POSTFIELDS, $request);
        curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec ($c);
        
        curl_close($c);
        $data_response = json_decode($response);
        
        $redirect_url = $data_response->redirect_url;
        
        /* ======= redirect to faspay URL =======*/
        header("Location:$redirect_url");
    }
    
    function inquiryreg(){
        
        include FASPAY_SENDME_LIB;
        
        $fp_va  = config_item('fp_virtual_account');

        $sendme = new SendMe();	
        $data_reg = array(
            "virtual_account"           => $fp_va,
            "beneficiary_account"       => '9998887776',
            "beneficiary_account_name"  => 'Reseller Two',
            "beneficiary_va_name"       => 'Reseller Two',
            "beneficiary_bank_code"     => '014',
            "beneficiary_bank_branch"   => "KCP Bogor",
            "beneficiary_region_code"   => "0192",
            "beneficiary_country_code"  => "ID",
            "beneficiary_purpose_code"  => "1"
        );
        $reg = $sendme->register($data_reg);
        $reg = (object) $reg;
        
        echo "<pre>";
        print_r($reg);
        die();
        
        /*
        $reg = $sendme->transfer([
            "virtual_account"               => "9920001189",
            "beneficiary_virtual_account"   => "9920013336",
            "beneficiary_account"           => "1060045760",
            "beneficiary_name"              => "BCA Dummy",
            "beneficiary_bank_code"         => "014",
            "beneficiary_region_code"       => "0102",
            "beneficiary_country_code"      => "ID",
            "beneficiary_purpose_code"      => "1",
            "beneficiary_email"             => "radenmuhamadiqbalmuchridin@gmail.com",
            "trx_no"                        => "INQ000002",
            "trx_date"                      => date('Y-m-d H:i:s'),
            "instruct_date"                 => "",
            "trx_amount"                    => "10000000",
            "trx_desc"                      => "Test Transfer",
            "callback_url"                  => base_url('fastpay/fastpay_inquiry')
        ]);
        */
        
        echo "<pre>";
        print_r($reg);
        die();
    }
    
    function wd(){
        $date           = '';
        $start_date     = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d', strtotime('-1 day'));
        $params         = array($start_date);
        $condition      = ' AND %status% = 0 AND DATE(%datecreated%) = ?';
        $data           = $this->Model_Bonus->get_all_member_withdraw(0, 0, $condition, '%id% ASC', $params);
        
        echo "<pre>";
        print_r($data);
        die();
    }
}

/* End of file debug.php */
/* Location: ./application/controllers/debug.php */
