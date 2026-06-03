<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Cron Controller.
 *
 * @class     Cron
 * @author    Yuda
 * @version   1.0.0
 */
class Cron extends AN_Controller
{

    /**
     * ADVcron.
     */
    protected $codecron = '$2y$05$QklDK3HFks3SLBIuUY6Ih.cqVJNAPlVkbMTIXnBDTqWDmmNeX6JYu'; // CRONAplhanet

    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }
    /**
     * Start Cron Process
     */
    private function _start_cron($cron_name = '', $debug = true, $cron_exist = false, $cli = true)
    {
        $this->benchmark->mark('started');
        if (!$debug) {
            if ( $cli ) {
                if ( ! $this->input->is_cli_request() ){
                    echo "Access Denied! No direct script access allowed!'";
                    echo "</pre>";
                    die();
                }
            }
            $cron_name = $cron_name ? $cron_name : 'cronjob';
            if ( $cron_exist ) {
                $date   = date('Y-m-d');
                $check_cron_exist = an_check_log_cron($cron_name, $date);
                if ( $check_cron_exist ) {
                    die('Cron Already Execute.');
                }
            }
            an_log_cron($cron_name, 'STARTED');
        }
        echo "<pre>";
    }

    /**
     * End Cron Process
     */
    private function _end_cron($cron_name = '', $debug = true, $log = '')
    {
        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        if (!$debug) {
            $cron_name  = $cron_name ? $cron_name : 'cronjob';
            $log_desc   = $elapsed_time . ', Log : ' . $log;
            an_log_cron($cron_name, 'ENDED', $log_desc);
        }
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    /**
     * CRON JOB : Withdraw
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Date       $date       default current date
     * @author   Yuda
     */
    function withdraw($keycode = '', $debug = true, $date = '')
    {
        set_time_limit(0);

        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = an_hash_verify($keycode, $this->codecron);

        if (!$validate) die();

        $datetime               = $date ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d 23:59:5', strtotime('-1 day')) . rand(0, 9);
        $date                   = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d', strtotime('-1 day'));

        // Config Withdraw
        $withdraw_minimal       = get_option('setting_withdraw_minimal');
        $withdraw_minimal       = $withdraw_minimal ? $withdraw_minimal : 0;
        $admin_fee              = get_option('setting_withdraw_fee');
        $admin_fee              = isset($admin_fee) ? $admin_fee : 0;
        $currency               = config_item('currency');
        $cron_log               = '';

        $this->_start_cron('Withdraw', $debug, true);
        echo '-------------------------------------------------------' . br();
        echo "                    Withdrawal" . br();
        echo '-------------------------------------------------------' . br();
        echo ' Function     : ' . ($debug ? 'View' : 'Save') . br();
        echo ' Datetime     : ' . $datetime . br();
        echo '-------------------------------------------------------' . br();
        echo ' WD Minimal   : ' . an_accounting($withdraw_minimal) . br();
        echo ' Admin Fee    : ' . an_accounting($admin_fee) . br();
        echo '-------------------------------------------------------' . br();

        $condition  = ' AND %wd_status% = 0 AND %status% = ' . ACTIVE . ' ';
        $data       = $this->Model_Bonus->get_all_total_ewallet_member(0, 0, $condition, '', ' %total% >= ' . $withdraw_minimal, $date);

        if ($data && $withdraw_minimal) {
            echo ' Total Data   : ' . count($data) . br();
            echo '-------------------------------------------------------' . br(3);
            $no = 0;
            foreach ($data as $row) {
                $wd_nominal             = $row->total_deposite;
                if ($withdraw_minimal > $wd_nominal) {
                    continue;
                }
                
                if (!$row->bank || !$row->bill) {
                    continue;
                }

                $bank_code              = '';
                if ($bankdata = an_banks($row->bank)) {
                    $bank_code          = $bankdata->kode;
                }

                $tax                    = an_calc_tax($wd_nominal, $row->npwp);
                $bill_name              = $row->bill_name ? $row->bill_name : $row->name;
                $tax                    = 0;
                $amount_receipt         = $wd_nominal - $tax - $admin_fee;

                echo "No. " . ($no += 1) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member             : ' . $row->id . br();
                echo 'Username              : ' . $row->username . ' - ' . $row->name . br();
                echo 'Bank                  : ' . $row->bank . br();
                echo 'Bill                  : ' . $row->bill . ' (' . $bill_name . ')' . br();
                echo '-------------------------------------------------------' . br();
                echo 'Nominal WD            = ' . an_accounting($wd_nominal, 'Rp') . br();
                echo 'Biaya Transfer        = ' . an_accounting($admin_fee, 'Rp') . br();
                echo '-------------------------------------------------------' . br();
                echo 'WD Diterima           = ' . an_accounting($amount_receipt, 'Rp') . br();
                echo '-------------------------------------------------------' . br(3);

                if (!$debug && $row->bank && $row->bill) {
                    // -------------------------------------------------
                    // Begin Transaction
                    // -------------------------------------------------
                    $this->db->trans_begin();

                    $data_withdraw          = array(
                        'id_member'         => $row->id,
                        'bank'              => $row->bank,
                        'bank_code'         => $bank_code,
                        'bill'              => $row->bill,
                        'bill_name'         => $bill_name,
                        'nominal'           => $wd_nominal,
                        'nominal_receipt'   => $amount_receipt,
                        'tax'               => $tax,
                        'admin_fund'        => $admin_fee,
                        'datecreated'       => $datetime,
                        'datemodified'      => $datetime
                    );
                    if (!$withdraw_id  = $this->Model_Bonus->save_data_withdraw($data_withdraw)) {
                        $this->db->trans_rollback();
                        $separated          = $cron_log ? ', ' : '';
                        $cron_log          .= $separated . 'ID member : ' . $row->id . ' (Withdraw Failed Save)';
                        continue;
                    }

                    $data_ewallet = array(
                        'id_member'     => $row->id,
                        'id_source'     => $withdraw_id,
                        'amount'        => $wd_nominal,
                        'source'        => 'withdraw',
                        'type'          => 'OUT',
                        'status'        => 1,
                        'description'   => 'Withdraw tgl ' . date('Y-m-d', strtotime($datetime)) . ' ' . an_accounting($wd_nominal, $currency),
                        'datecreated'   => $datetime
                    );
                    if (!$wallet_id  = $this->Model_Bonus->save_data_ewallet($data_ewallet)) {
                        $this->db->trans_rollback();
                        $separated          = $cron_log ? ', ' : '';
                        $cron_log          .= $separated . 'ID Withdraw : ' . $withdraw_id . ' (Wallet Failed Save)';
                        continue;
                    }

                    // -------------------------------------------------
                    // Commit or Rollback Transaction
                    // -------------------------------------------------
                    if ($this->db->trans_status() === FALSE) {
                        // Rollback Transaction
                        $this->db->trans_rollback();
                    } else {
                        // Commit Transaction
                        $this->db->trans_commit();
                        // Complete Transaction
                        $this->db->trans_complete();
                    }
                }
            }
        } else {
            $cron_log = 'Data Deposite tidak ditemukan';
            echo br(1) . " " . $cron_log . br();
        }

        if (!$debug) {
            echo br(2) . '----------------------------------------------------' . br();
            echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            echo '----------------------------------------------------' . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Withdraw', $debug, $cron_log);

        $this->daily_faspay_bank_inquiry($keycode, $debug, $datetime);

    }

    /**
     * CRON JOB : Daily Inquiry Cron
     *
     * @param    boolean    $code    default true
     * @param    Date       $date    default current date
     * @author   Yuda
     */
    function daily_faspay_bank_inquiry($keycode = '', $debug = true, $date = '')
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode        = trim($keycode);
        $validate       = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $start_date     = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d', strtotime('-1 day'));
        $datetime       = $start_date . ' ' . date('23:59:5') . rand(5, 9);
        $fp_active      = get_option('fp_active');
        $fp_va          = config_item('fp_virtual_account');
        $fp_env         = config_item('fp_env');
        $total_data     = 0;
        $total_inquiry  = 0;

        if ( $fp_active != ACTIVE ) {
            die('Fitur Faspay belum diaktifkan !');
        }

        $this->_start_cron('FASPAY_INQUIRY', $debug);
        echo '----------------------------------------------------' . br();
        echo ' FASPAY INQUIRY CRON' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function     : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Date         : ' . $start_date . br();
        echo ' Datetime     : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $params     = array($start_date);
        $condition  = ' AND %status% = 0 AND DATE(%datecreated%) = ?';
        if ($data = $this->Model_Bonus->get_all_member_withdraw(0, 0, $condition, '%id% ASC', $params)) {
            include FASPAY_SENDME_LIB;
            $sendme = new SendMe();
            if( $fp_env == "prod" ){ $sendme->enableProd(); }	
            
            $total_data = count($data);
            echo ' Total Member : ' . $total_data . br();
            echo '----------------------------------------------------' . br(3);
            
            $no = 1;
            foreach ($data as $k => $row) {
                $bank_code          = $row->member_bank_code;
                $bank_branch        = $row->member_bank_branch;
                $region_code        = str_pad($row->member_city_code, 4, '0', STR_PAD_LEFT);
                $account_number     = trim($row->bill);
                $account_holder     = strtoupper($row->bill_name);

                echo " No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo ' ID Member     : ' . $row->id_member . br();
                echo ' Username      : ' . $row->username . br();
                echo ' Name          : ' . $row->name . br();
                echo ' Bank          : ' . $bank_code . ' (' . $row->member_bank_name . ')' . br();
                echo ' No. Rek       : ' . $account_number . br();
                echo ' A.N. Rek      : ' . $account_holder . br();
                echo '-------------------------------------------------------' . br();

                if ( !$debug && $bank_code && $bank_branch && $region_code ) {
                    $total_inquiry     += 1;
                    $data_reg           = array(
                        "virtual_account"           => $fp_va,
                        "beneficiary_account"       => $account_number,
                        "beneficiary_account_name"  => $account_holder,
                        "beneficiary_va_name"       => $account_holder,
                        "beneficiary_bank_code"     => $bank_code,
                        "beneficiary_bank_branch"   => $bank_branch,
                        "beneficiary_region_code"   => $region_code,
                        "beneficiary_country_code"  => "ID",
                        "beneficiary_purpose_code"  => "1"
                    );
                    $reg = $sendme->register($data_reg);
                    $reg = (object) $reg;
                    
                    if( !$reg ){
                        continue;
                    }
                    
                    if( $reg->response_code == "00" ){
                        $bank_code      = isset($reg->beneficiary_bank_code) ? $reg->beneficiary_bank_code : $bank_code;
                        $bank_branch    = isset($reg->beneficiary_bank_branch) ? $reg->beneficiary_bank_branch : $bank_branch;
                        $region_code    = isset($reg->beneficiary_region_code) ? $reg->beneficiary_region_code : $region_code;
                        $account_number = (isset($reg->bank_account_number) && !empty($reg->bank_account_number)) ? $reg->bank_account_number : $account_number;
                        $account_holder = (isset($reg->bank_account_name) && !empty($reg->bank_account_name)) ? $reg->bank_account_name : $account_holder;
                        $status         = $reg->status;
                        $message        = $reg->message;
                        $bank_name      = $reg->beneficiary_bank_name;
    
                        $data_inquiry   = array(
                            "bank_code"         => $bank_code,
                            "bank_name"         => $bank_name,
                            "branch"            => $bank_branch,
                            "region_code"       => $region_code,
                            "account_number"    => $account_number,
                            "account_holder"    => $account_holder,
                            "status"            => $status,
                            "status_message"    => $message,
                            "datecreated"       => $datetime
                        );
    
                        if ($inquiry = $this->Model_Faspay->get_detail_faspay_inquiry_by_account($account_number)) {
                            unset($data_inquiry['account_number']);
                            unset($data_inquiry['datecreated']);
                            $this->Model_Faspay->update_faspay_inquiry_data($account_number, '', $data_inquiry);
                        } else {
                            $this->Model_Faspay->save_data_faspay_inquiry($data_inquiry);
                        }

                        if ( $withdraw = $this->Model_Bonus->get_withdraw_by_id($row->id) ) {
                            if ( $withdraw->bank_code == $bank_code && trim($withdraw->bill) == $account_number ) {
                                $datawithdraw   = array( 'bill_name' => $account_holder, 'inquiry_status' => $status );
                                $update_wd_data = $this->Model_Bonus->update_data_withdraw($row->id, $datawithdraw);
                            }
                        }
                    }
                    
                    an_log_fastpay('FASTPAY_SENDME_REG_INQUIRY', strtoupper($message), json_encode($reg), 0, $row->id);

                    echo ' Inquiry       : ' . $message . br();
                    echo '-------------------------------------------------------' . br();
                }

                echo br(2);
                $no++;
            }
        }

        echo br() . '----------------------------------------------------' . br();
        $log_desc = 'Total Data WD : ' . an_accounting($total_data) .' Total Inquiry : '. an_accounting($total_inquiry);
        echo ' ' . $log_desc . br();
        echo '----------------------------------------------------' . br();
        $this->_end_cron('FASPAY_INQUIRY', $debug, $log_desc);
    }

    /**
     * CRON JOB : Bonus Pairing
     *
     * @param    boolean    $code    default true
     * @param    Date       $date    default current date
     * @author   Yuda
     */
    function bonus_pairing($keycode = '', $debug = true, $date = '') {
        set_time_limit( 0 );
        if ( ! $keycode ) die();

        $keycode        = trim($keycode);
        $validate       = password_verify($keycode, $this->codecron);
        
        if ( ! $validate ) die();

        $start_date     = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d',strtotime('-1 day'));
        $start_calc     = config_item('start_calculation');
        if ( date('Ymd', strtotime($start_date)) <= date('Ymd', strtotime($start_calc)) ) {
            $start_date = date('Y-m-d');
        }

        $datetime       = $start_date . ' ' . date('23:59:3') . rand(0,9); 
        $reg_member     = array();
        $ro_member      = array();

        $this->_start_cron('Bonus_Pairing', $debug);
        echo '----------------------------------------------------'. br();
        echo ' Calculate Bonus Pairing'. br();
        echo '----------------------------------------------------'. br();
        echo ' Member Join at '. date('d F Y', strtotime($start_date)) . br();
        echo '----------------------------------------------------'. br();
        echo ' Function    : ' . ( $debug ? 'Debug ' : 'Save ' ) . br();
        echo ' Datecreated : ' . $start_date . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------'. br();

        $member_omzet   = '';
        $condition      = ' WHERE DATE(%datecreated%) <= "'.$start_date.'"';
        if ( $data_omzet = $this->Model_Member->get_all_member_data(0, 0, $condition, '%datecreated% ASC') ) {
            $iResult    = count($data_omzet);
            foreach ($data_omzet as $k => $val) {
                $member_omzet .= $val->tree;

                if ( ($k+1) < $iResult ) {
                    $member_omzet .= '-';
                }
            }

            if ( $member_omzet ) {
                if(substr($member_omzet, -1) == '-') $member_omzet = substr_replace($member_omzet, '', -1);
                $member_omzet = explode('-', $member_omzet);
                $member_omzet = array_unique($member_omzet);
            }
        }
        if ( $member_omzet ) {
            rsort($member_omzet);
            $count_member = count($member_omzet);
            echo ' Total Member : ' . ($count_member-1) . br();
            echo '----------------------------------------------------'. br(3);
            $no =1;
            foreach ($member_omzet as $key => $id) {
                if ( $id == 1 ) { continue; }
                echo ($no++) .".  Member ID : " . $id . br();
                // -------------------------------------------------
                // calculate bonus pairing
                // -------------------------------------------------
                an_calculate_pairing_bonus($id, $datetime, $debug);
                echo br(2);
            }
        }

        echo br(2) . '----------------------------------------------------'. br();
        $this->_end_cron('Bonus_Pairing', $debug);
    }
}
