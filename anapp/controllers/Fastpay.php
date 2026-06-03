<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fastpay Controller.
 *
 * @class     Fastpay
 * @version   1.0.0
 */
class Fastpay extends AN_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA Faspay
    // =============================================================================================

    /**
     * Faspay Transaction Out List Data function.
     */
    function faspaytrxlistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('dashboard'), 'refresh');
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $records            = array();
            die(json_encode($records));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '');
        $s_date_wd_min      = $this->input->post('search_datewd_min');
        $s_date_wd_min      = an_isset($s_date_wd_min, '');
        $s_date_wd_max      = $this->input->post('search_datewd_max');
        $s_date_wd_max      = an_isset($s_date_wd_max, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = an_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = an_isset($s_nominal_max, '');
        $s_fastpay_id       = $this->input->post('search_fastpay_id');
        $s_fastpay_id       = an_isset($s_fastpay_id, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '');
        $s_bank             = $this->input->post('search_bank');
        $s_bank             = an_isset($s_bank, '');
        $s_bill             = $this->input->post('search_bill');
        $s_bill             = an_isset($s_bill, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_fastpay_id)) {
            $condition .= str_replace('%s%', $s_fastpay_id, ' AND %fastpay_id% LIKE "%%s%%"');
        }
        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_bank)) {
            $condition .= str_replace('%s%', $s_bank, ' AND %bank_code% LIKE "%%s%%"');
        }
        if (!empty($s_bill)) {
            $condition .= str_replace('%s%', $s_bill, ' AND %bill% LIKE "%%s%%"');
        }
        if (!empty($s_nominal_min)) {
            $condition .= ' AND %nominal% >= ' . $s_nominal_min . '';
        }
        if (!empty($s_nominal_max)) {
            $condition .= ' AND %nominal% <= ' . $s_nominal_max . '';
        }
        if (!empty($s_date_wd_min)) {
            $condition .= ' AND %datewd% >= "' . $s_date_wd_min . '"';
        }
        if (!empty($s_date_wd_max)) {
            $condition .= ' AND %datewd% <= "' . $s_date_wd_max . '"';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND DATE(%datecreated%) >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND DATE(%datecreated%) <= "' . $s_date_max . '"';
        }
        if (!empty($s_status)) {
            if (strtolower($s_status) == 'pending') {
                $condition .= str_replace('%s%', 0, ' AND %status% = %s%');
            }
            if (strtolower($s_status) == 'done') {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
            if (strtolower($s_status) == 'cancelled') {
                $condition .= str_replace('%s%', 2, ' AND %status% = %s%');
            }
        }

        if ($column == 1) {
            $order_by .= '%datecreated% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%datewd% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%fastpay_id% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%bank_code% ' . $sort . ', %bill% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%nominal% ' . $sort;
        } elseif ($column == 7) {
            $order_by .= '%status% ' . $sort;
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($is_admin) {
            $data_list      = $this->Model_Faspay->get_all_faspay_out($limit, $offset, $condition, $order_by);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->id_member);
                $trx_id     = an_encrypt($row->trx_id);
                $nominal    = an_accounting($row->nominal, $currency, true);

                $bank       = 'Bank : <b>' . strtoupper($row->bank_code) . '</b>' . br();
                $bank      .= 'No. Rek : <b>' . $row->bill . '</b>' . br();
                $bank      .= 'AN. Rek : <b>' . strtoupper($row->bill_name) . '</b>' . br();

                $status     = '<span class="badge badge-default">PENDING</span>';
                $btn_action = '<a href="' . base_url('fastpay/checkstatustransferfastpay/' . $trx_id) . '" 
                                class="btn btn-xs btn-flat bg-blue btn-tooltip checktransferfastpay" 
                                title="Cek Status Transaksi Fastpay"><i class="fa fa-refresh"></i> Cek Status</a>';

                if ($row->status == 1) {
                    $status = '<span class="badge badge-success">DONE</span>';
                    $btn_action = '-';
                }
                if ($row->status >= 2) {
                    $status = '<span class="badge badge-danger">CANCELLED</span>';
                    $btn_action = '-';
                }

                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center(date('Y-m-d @H:i', strtotime($row->datecreated))) . '</div>',
                    '<div style="min-width:110px">' . an_center(date('Y-m-d', strtotime($row->date_withdraw))) . '</div>',
                    an_center(an_strong($row->trx_id)),
                    an_center('<a href="' . base_url('profile/' . $id) . '">' . an_strong(strtolower($row->username)) . '</a>'),
                    $bank,
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    an_center($status),
                    an_center($btn_action)
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }
    
    /**
     * Faspay Transaction Total List Data function.
     */
    function faspaytrxtotallistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('dashboard'), 'refresh');
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $records            = array();
            die(json_encode($records));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '');
        
        $s_trx_count_min        = $this->input->post('search_trx_count_min');
        $s_trx_count_min        = an_isset($s_trx_count_min, '');
        $s_trx_count_max        = $this->input->post('search_trx_count_max');
        $s_trx_count_max        = an_isset($s_trx_count_max, '');
        $s_trx_nominal_min      = $this->input->post('search_trx_nominal_min');
        $s_trx_nominal_min      = an_isset($s_trx_nominal_min, '');
        $s_trx_nominal_max      = $this->input->post('search_trx_nominal_max');
        $s_trx_nominal_max      = an_isset($s_trx_nominal_max, '');
        $s_fee_nominal_min      = $this->input->post('search_fee_nominal_min');
        $s_fee_nominal_min      = an_isset($s_fee_nominal_min, '');
        $s_fee_nominal_max      = $this->input->post('search_fee_nominal_max');
        $s_fee_nominal_max      = an_isset($s_fee_nominal_max, '');
        $s_trx_fee_nominal_min  = $this->input->post('search_trx_fee_nominal_min');
        $s_trx_fee_nominal_min  = an_isset($s_trx_fee_nominal_min, '');
        $s_trx_fee_nominal_max  = $this->input->post('search_trx_fee_nominal_max');
        $s_trx_fee_nominal_max  = an_isset($s_trx_fee_nominal_max, '');

        if (!empty($s_date_min)) {
            $condition .= ' AND %date% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %date% <= "' . $s_date_max . '"';
        }
        if (!empty($s_trx_count_min)) {
            $condition .= ' AND %trx_count% >= ' . $s_trx_count_min . '';
        }
        if (!empty($s_trx_count_max)) {
            $condition .= ' AND %trx_count% <= ' . $s_trx_count_max . '';
        }
        if (!empty($s_trx_nominal_min)) {
            $condition .= ' AND %trx_nominal% >= ' . $s_trx_nominal_min . '';
        }
        if (!empty($s_trx_nominal_max)) {
            $condition .= ' AND %trx_nominal% <= ' . $s_trx_nominal_max . '';
        }
        if (!empty($s_fee_nominal_min)) {
            $condition .= ' AND %fee_nominal% >= ' . $s_fee_nominal_min . '';
        }
        if (!empty($s_fee_nominal_max)) {
            $condition .= ' AND %fee_nominal% <= ' . $s_fee_nominal_max . '';
        }
        if (!empty($s_trx_fee_nominal_min)) {
            $condition .= ' AND %trx_fee_nominal% >= ' . $s_trx_fee_nominal_min . '';
        }
        if (!empty($s_trx_fee_nominal_max)) {
            $condition .= ' AND %trx_fee_nominal% <= ' . $s_trx_fee_nominal_max . '';
        }

        if ($column == 1) {
            $order_by .= '%date% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%trx_count% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%trx_nominal% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%fee_nominal% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%trx_fee_nominal% ' . $sort;
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($is_admin) {
            $data_list      = $this->Model_Faspay->get_all_faspay_total_out($limit, $offset, $condition, $order_by);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {

                $nominal    = an_accounting($row->nominal, $currency, true);


                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center($row->date) . '</div>',
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    an_center($btn_action)
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }
    
    /**
     * Faspay Inquiry Bank List Data function.
     */
    function faspayinquirylistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('dashboard'), 'refresh');
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $records            = array();
            die(json_encode($records));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '');
        $s_bank             = $this->input->post('search_bank');
        $s_bank             = an_isset($s_bank, '');
        $s_bill             = $this->input->post('search_bill');
        $s_bill             = an_isset($s_bill, '');
        $s_bill_name        = $this->input->post('search_bill_name');
        $s_bill_name        = an_isset($s_bill_name, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_bank)) {
            $condition .= str_replace('%s%', $s_bank, ' AND %bank_code% LIKE "%%s%%"');
        }
        if (!empty($s_bill)) {
            $condition .= str_replace('%s%', $s_bill, ' AND %account_number% LIKE "%%s%%"');
        }
        if (!empty($s_bill_name)) {
            $condition .= str_replace('%s%', $s_bill_name, ' AND %account_holder% LIKE "%%s%%"');
        }
        if (!empty($s_status)) {
            $condition .= str_replace('%s%', $s_status, ' AND %status% LIKE "%%s%%"');
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND DATE(%datecreated%) >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND DATE(%datecreated%) <= "' . $s_date_max . '"';
        }

        if ($column == 1) {
            $order_by .= '%bank_code% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%account_number% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%account_holder% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%status% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%datecreated% ' . $sort;
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($is_admin) {
            $data_list      = $this->Model_Faspay->get_all_faspay_inquiry($limit, $offset, $condition, $order_by);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $account_holder = ($row->account_holder) ? $row->account_holder : '-';                
                if( $row->status == 1 )     { $status = '<span class="badge badge-info">ON PROCESS</span>'; }
                elseif( $row->status == 2 ) { $status = '<span class="badge badge-success">SUCCESS</span>'; }
                elseif( $row->status == 3 ) { $status = '<span class="badge badge-warning">UNCONFIRMED</span>'; }
                elseif( $row->status == 4 ) { $status = '<span class="badge badge-danger">FAILED</span>'; }
                elseif( $row->status == 6 ) { $status = '<span class="badge badge-warning">ALREADY REGISTERED</span>'; }
                elseif( $row->status == 9 ) { $status = '<span class="badge badge-danger">INVALID</span>'; }

                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center(date('Y-m-d @H:i', strtotime($row->datecreated))) . '</div>',
                    an_center(an_strong(strtoupper($row->bank_code .' - '. $row->bank_name))),
                    an_center(an_strong($row->account_number)),
                    an_center(an_strong(strtoupper($account_holder))),
                    an_center($status),
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }
    
    // =============================================================================================
    // FASPAY PROSES 
    // =============================================================================================
    
    /**
     * Faspay Inquiry function.
     */
    function inquiry($id_withdraw = '')
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('dashboard'), 'refresh');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $curdate                = date('Y-m-d H:i:s');
        $fp_va                  = config_item('fp_virtual_account');
        $fp_env                 = get_option('fp_env');

        $username               = $this->input->post('username');
        $username               = an_isset($username, '');
        $account_number         = $this->input->post('account_number');
        $account_number         = an_isset($account_number, 0);

        if ($id_withdraw) {
            $id_withdraw        = an_encrypt($id_withdraw, 'decrypt');
            if (!$withdraw = $this->Model_Bonus->get_withdraw_by_id($id_withdraw)) {
                $data = array('status' => 'error', 'message' => 'Data Withdraw tidak ditemukan. Silahkan pilih ID Witdraw lainnya!');
                die(json_encode($data));
            }

            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$memberdata = an_get_memberdata_by_id($withdraw->id_member)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Data Member tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }

            $username           = $memberdata->username;
            $account_number     = $memberdata->bill;
        } else {
            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$memberdata = $this->Model_Member->get_member_by('login', $username)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Data Member tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }
        }
        // -------------------------------------------------
        // Check Bank Data Data
        // -------------------------------------------------
        if (!$bankdata = an_banks($memberdata->bank)) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Data Bank tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Check Data Bank (Bill Number)
        // -------------------------------------------------
        if ($account_number != $memberdata->bill) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Nomor rekening WD tidak sesuai dengan Nomor Rekening data Member<br />Silahkan update terlebih dahulu data bank Member di halaman Profil Member',
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Process Send Inquiry
        // -------------------------------------------------
        $account_holder         = strtoupper($memberdata->bill_name);
        $bank_code              = $bankdata->kode;
        $bank_branch            = $memberdata->branch;
        $region_code            = str_pad($memberdata->city_code, 4, '0', STR_PAD_LEFT);
        $account_number         = trim($memberdata->bill);
        
        include FASPAY_SENDME_LIB;
        $sendme = new SendMe();	
        if( $fp_env == "prod" ){ $sendme->enableProd(); }
        
        $data_reg               = array(
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
        
        if( $reg->response_code != "00" ){
            an_log_fastpay('FASTPAY_SENDME_INQUIRY', strtoupper($reg->response_desc), json_encode($reg));
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Respon Faspay Inquiry : '.strtoupper($reg->response_desc),
            );
            die(json_encode($data));
        }
        
        if( $reg->status == 1 ){
            $bank_code      = isset($reg->beneficiary_bank_code) ? $reg->beneficiary_bank_code : $bank_code;
            $bank_branch    = isset($reg->beneficiary_bank_branch) ? $reg->beneficiary_bank_branch : $bank_branch;
            $region_code    = isset($reg->beneficiary_region_code) ? $reg->beneficiary_region_code : $region_code;
            $account_number = (isset($reg->bank_account_number) && !empty($reg->bank_account_number)) ? $reg->bank_account_number : $account_number;
            $account_holder = (isset($reg->bank_account_name) && !empty($reg->bank_account_name)) ? $reg->bank_account_name : $account_holder;
            $status         = $reg->status;
            $message        = $reg->message;
            $bank_name      = $reg->beneficiary_bank_name;
            
            $data_inquiry   = array(
                "bank_code"         => isset($reg->beneficiary_bank_code) ? $reg->beneficiary_bank_code : $bank_code,
                "bank_name"         => $bank_name,
                "branch"            => isset($reg->beneficiary_bank_branch) ? $reg->beneficiary_bank_branch : $bank_branch,
                "region_code"       => isset($reg->beneficiary_region_code) ? $reg->beneficiary_region_code : $region_code,
                "account_number"    => (isset($reg->bank_account_number) && !empty($reg->bank_account_number)) ? $reg->bank_account_number : $account_number,
                "account_holder"    => (isset($reg->bank_account_name) && !empty($reg->bank_account_name)) ? $reg->bank_account_name : $account_holder,
                "status"            => $status,
                "status_message"    => $message,
                "datecreated"       => date('Y-m-d H:i:s')
            );
            
            if ($inquiry = $this->Model_Faspay->get_detail_faspay_inquiry_by_account($account_number)) {
                unset($data_inquiry['account_number']);
                unset($data_inquiry['datecreated']);
                if (!$this->Model_Faspay->update_faspay_inquiry_data($account_number, '', $data_inquiry)) {
                    $data = array(
                        'status'        => 'error',
                        'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses update data inquiry',
                    );
                    die(json_encode($data));
                }
            } else {
                if (!$this->Model_Faspay->save_data_faspay_inquiry($data_inquiry)) {
                    $data = array(
                        'status'        => 'error',
                        'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry',
                    );
                    die(json_encode($data));
                }
            }
            
            if ( $withdraw = $this->Model_Bonus->get_withdraw_by_id($id_withdraw) ) {
                if ( $withdraw->bank_code == $bank_code && trim($withdraw->bill) == $account_number ) {
                    $datawithdraw   = array( 'bill_name' => $account_holder, 'inquiry_status' => $status );
                    if( !$update_wd_data = $this->Model_Bonus->update_data_withdraw($id_withdraw, $datawithdraw) ){
                        $data = array(
                            'status'    => 'error', 
                            'message'   => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry.'
                        );
                        die(json_encode($data));
                    }
                }
            }
            $data = array(
                'status'        => 'success',
                'message'       => 'Proses Inquiry Akun Bank berhasil! Nomor Rekening Bank Valid'
            );
            die(json_encode($data));
        }elseif( $reg->status == 4 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Inquiry Faspay Failed'
            );
            die(json_encode($data));
        }elseif( $reg->status == 6 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Nomor Rekening Bank sudah terdaftar'
            );
            die(json_encode($data));
        }elseif( $reg->status == 9 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! '.$reg->message
            );
            die(json_encode($data));
        }else{
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Nomor Rekening Bank Invalid'
            );
            die(json_encode($data));
        }
    }
    
    /**
     * Faspay Confirm Inquiry function.
     */
    function inquiryconfirm($id_withdraw = '')
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('dashboard'), 'refresh');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $curdate                = date('Y-m-d H:i:s');
        $fp_va                  = config_item('fp_virtual_account');
        $fp_env                 = gen_option('fp_env');

        $username               = $this->input->post('username');
        $username               = an_isset($username, '');
        $account_number         = $this->input->post('account_number');
        $account_number         = an_isset($account_number, 0);

        if ($id_withdraw) {
            $id_withdraw        = an_encrypt($id_withdraw, 'decrypt');
            if (!$withdraw = $this->Model_Bonus->get_withdraw_by_id($id_withdraw)) {
                $data = array('status' => 'error', 'message' => 'Data Withdraw tidak ditemukan. Silahkan pilih ID Witdraw lainnya!');
                die(json_encode($data));
            }

            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$memberdata = an_get_memberdata_by_id($withdraw->id_member)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Data Member tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }

            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$bankdata = an_banks($memberdata->bank)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Data Bank tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }

            $username           = $memberdata->username;
            $account_number     = $memberdata->bill;
        } else {
            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$memberdata = $this->Model_Member->get_member_by('login', $username)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Data Member tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }
        }

        // -------------------------------------------------
        // Check Data Bank (Bill Number)
        // -------------------------------------------------
        if ($account_number != $memberdata->bill) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Nomor rekening WD tidak sesuai dengan Nomor Rekening data Member<br />Silahkan update terlebih dahulu data bank Member di halaman Profil Member',
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Check Data Inquiry
        // -------------------------------------------------
        if( !$inquiry = $this->Model_Faspay->get_detail_faspay_inquiry_by_account($account_number) ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Data Inquiry Bank tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Process Send Confirm Inquiry
        // -------------------------------------------------
        $bank_code              = $inquiry->bank_code;
        $bank_branch            = $inquiry->branch;
        $bank_name              = $inquiry->bank_name;
        $region_code            = $inquiry->region_code;
        $account_number         = trim($inquiry->account_number);
        $account_holder         = $inquiry->account_holder;
        $account_name           = strtoupper($memberdata->bill_name);

        include FASPAY_SENDME_LIB;
        $sendme = new SendMe();	
        if( $fp_env == "prod" ){ $sendme->enableProd(); }
        
        $data_confirm           = array(
            "virtual_account"           => $fp_va,
            "beneficiary_account"       => $account_number,
            "beneficiary_account_name"  => $account_name,
            "beneficiary_va_name"       => $account_name,
            "beneficiary_bank_code"     => $bank_code,
            "beneficiary_bank_name"     => $bank_name,
            "beneficiary_bank_branch"   => $bank_branch,
            "beneficiary_region_code"   => $region_code,
            "beneficiary_country_code"  => "ID",
            "beneficiary_purpose_code"  => "1",
            "bank_account_number"       => $account_number,
            "bank_account_name"         => $account_holder,
            "confirm"                   => "2"
        );
        $confirm = $sendme->confirm($data_confirm);
        $confirm = (object) $confirm;
        
        if( $confirm->response_code != "00" ){
            an_log_fastpay('FASTPAY_SENDME_INQUIRY_CONFIRM', strtoupper($confirm->response_desc), json_encode($confirm));
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Respon Faspay Inquiry : '.strtoupper($confirm->response_desc),
            );
            die(json_encode($data));
        }

        $data_update_inquiry = array(
            "status"                    => $confirm->status,
            "status_message"            => $confirm->message,
        );
        if( isset($confirm->beneficiary_virtual_account) ){
            $data_update_inquiry['beneficiary_va_account'] = $confirm->beneficiary_virtual_account;
        }
        
        if (!$this->Model_Faspay->update_faspay_inquiry_data($account_number, '', $data_update_inquiry)) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses update data inquiry',
            );
            die(json_encode($data));
        }

        if( $confirm->status == 2 ){
            if ($id_withdraw) {
                $datawithdraw   = array(
                    'bank'              => $memberdata->bank,
                    'bank_code'         => $confirm->beneficiary_bank_code,
                    'bill'              => $confirm->bank_account_number,
                    'bill_name'         => $confirm->bank_account_name,
                    'inquiry_status'    => $confirm->status
                );
                if (!$update_wd_data = $this->Model_Bonus->update_data_withdraw($id_withdraw, $datawithdraw)) {
                    $data = array('status' => 'error', 'message' => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry.');
                    die(json_encode($data));
                }
            }
            $data = array(
                'status'        => 'success',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank berhasil! Nomor Rekening Bank Valid'
            );
            die(json_encode($data));
        }elseif( $confirm->status == 4 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Konfirmasi Faspay Failed'
            );
            die(json_encode($data));
        }elseif( $confirm->status == 6 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Nomor Rekening Bank sudah terdaftar'
            );
            die(json_encode($data));
        }elseif( $confirm->status == 9 ){
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! '.$confirm->message
            );
            die(json_encode($data));
        }else{
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Konfirmasi Inquiry Akun Bank tidak berhasil! Nomor Rekening Bank Invalid'
            );
            die(json_encode($data));
        }
    }
    
    /**
     * Withdraw Transfer Faspay function
     */
    function transfer($id = 0)
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) redirect(base_url('financial/withdraw'), 'refresh');
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        // Check withdraw id variable
        $an_token           = $this->security->get_csrf_hash();
        if (!$id) {
            $data = array('status' => 'error', 'token' => $an_token, 'message' => 'Data Withdraw tidak ditemukan!');
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $fp_active          = get_option('fp_active');
        $fp_active          = $fp_active ? $fp_active : 0;
        $fp_va              = config_item('fp_virtual_account');
        $fp_env             = gen_option('fp_env');
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Proses Transfer Bonus oleh Faspay Tidak Berhasil');

        // POST Input Form
        $nominal            = trim( $this->input->post('nominal') );
        $nominal            = an_isset($nominal, 0, 0, true);
        $nominal            = str_replace('.', '', $nominal);
        $nominal            = max(0, $nominal);
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if ( $fp_active != ACTIVE ) {
            $data['message'] = 'Maaf, Fitur Faspay belum diaktifkan !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, yang dapat melakukan Transfer Bonus oleh Faspay hanya Administrator !';
            die(json_encode($data));
        }

        // Check Withdraw Data
        $id                     = an_encrypt($id, 'decrypt');
        $withdrawdata           = $this->Model_Bonus->get_withdraw_by_id($id);
        if (!$withdrawdata) {
            $data['message'] = 'Data withdraw tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }

        if ($withdrawdata->status > 0) {
            $data['message'] = 'Data Withdraw sudah ditransfer. Silahkan pilih ID Witdraw lainnya!';
            die(json_encode($data));
        }

        // Check Ewallet Data
        if ( $withdrawdata->id_member > 1 ) {
            $ewalletoutdata         = $this->Model_Bonus->get_ewallet_out_data_by_wd($id);
            if (!$ewalletoutdata) {
                $data['message'] = 'Data Ewallet tidak ditemukan atau belum terdaftar!';
                die(json_encode($data));
            }
        }

        // Check memberdata
        $memberdata = an_get_memberdata_by_id($withdrawdata->id_member);
        if (!$memberdata) {
            $data['message'] = 'Data member withdrawal tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        
        // Set Amount Faspay
        $amount_accept      = $withdrawdata->nominal_receipt;
        $amount_faspay      = $amount_accept;

        // Check Rekening Bank Inquiry
        $account_number     = trim($withdrawdata->bill);
        $account_holder     = trim($withdrawdata->bill_name);
        $bank_code          = trim($withdrawdata->bank_code);
        $trx_no             = 'FPT' . date('YmdHis') . an_generate_rand_string(4, 'num');
        
        // Check Inquiry Data
        if( !$inquiry = $this->Model_Faspay->get_detail_faspay_inquiry_by_account($account_number) ){
            $data['message'] = 'Data Inquiry Bank tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        
        if( $withdrawdata->inquiry_status == 1 )    { $inquiry_status = 'ON PROCESS'; }
        elseif( $withdrawdata->inquiry_status == 3 ){ $inquiry_status = 'UNCONFIRMED'; }
        elseif( $withdrawdata->inquiry_status == 4 ){ $inquiry_status = 'FAILED'; }
        elseif( $withdrawdata->inquiry_status == 6 ){ $inquiry_status = 'REGISTERED'; }
        elseif( $withdrawdata->inquiry_status == 9 ){ $inquiry_status = 'INVALID'; }

        if ($withdrawdata->inquiry_status != 2) {
            $data['message'] = 'Tidak dapat dilakukan proces Faspay. Status Inquiry '.$inquiry_status.'!';
            die(json_encode($data));
        }
        
        include FASPAY_SENDME_LIB;
        $sendme = new SendMe();	
        if( $fp_env == "prod" ){ $sendme->enableProd(); }
        
        // Check Faspay Balance
        $balance = $sendme->balance_inquiry();
        $balance = (object) $balance;
        
        $faspay_balance = 0;
        if( $balance->response_code != "00" ){
            $data['message'] = 'Proses cek saldo Faspay tidak berhasil!'.$balance->response_desc;
            die(json_encode($data));
        }
        $faspay_balance = absint($balance->available_balance);

        if ($amount_accept > $faspay_balance) {
            $data['amount_accept']  = $amount_accept;
            $data['saldo_flip']     = $faspay_balance;
            $data['message']        = 'Proses Transfer Faspay tidak berhasil. Saldo Faspay tidak mencukupi untuk proses transfer ini';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Faspay API Process
        $data_transfer = array(
            "virtual_account"               => $fp_va,
            "beneficiary_virtual_account"   => $inquiry->beneficiary_va_account,
            "beneficiary_account"           => $account_number,
            "beneficiary_name"              => $account_holder,
            "beneficiary_bank_code"         => $bank_code,
            "beneficiary_region_code"       => $inquiry->region_code,
            "beneficiary_country_code"      => "ID",
            "beneficiary_purpose_code"      => "1",
            "beneficiary_email"             => $memberdata->email,
            "trx_no"                        => $trx_no,
            "trx_date"                      => $datetime,
            "instruct_date"                 => "",
            "trx_amount"                    => $amount_faspay * 100,
            "trx_desc"                      => "Transfer WD Member ".strtoupper($memberdata->name)." (".$memberdata->username.")",
            "callback_url"                  => base_url('fastpay/fastpay_transfer_callback')
        );
        $transfer = $sendme->transfer($data_transfer);
        $transfer = (object) $transfer;
        
        if( $transfer->status == 1 ){ $transfer_status = 'ON PROCESS'; }
        elseif( $transfer->status == 2 ){ $transfer_status = 'SUCCESS'; }
        elseif( $transfer->status == 4 ){ $transfer_status = 'FAILED'; }
        else{ $transfer_status = 'INVALID'; }
        
        an_log_fastpay('FASPAY_WD', $transfer_status, json_encode($transfer), $transfer->trx_id, $withdrawdata->id);
        
        if( $transfer->response_code != "00" ){
            $this->db->trans_rollback();
            $data['message'] = 'Proses Transfer Faspay tidak berhasil. '.$transfer->response_desc;
            die(json_encode($data));
        }
        
        if( $transfer->status == 4 ){
            $data['message'] = 'Proses Transfer Faspay tidak berhasil. Status Transfer Faspay FAILED';
            die(json_encode($data));
        }
        
        $confirm_by     = $current_member->username;
        if ($staff = an_get_current_staff()) {
            $confirm_by = $staff->username;
        }

        $data_wd_update_faspay  = array(
            'status'            => 1,
            'trx_id'            => $transfer->trx_id,
            'trx_no'            => $transfer->trx_no,
            'datemodified'      => $datetime,
            'dateconfirm'       => $datetime,
            'confirm_by'        => $confirm_by
        );
        if ($this->Model_Bonus->update_data_withdraw($withdrawdata->id, $data_wd_update_faspay)) {
            $data_faspay_out    = array(
                'trx_id'        => $transfer->trx_id,
                'trx_no'        => $transfer->trx_no,
                'id_withdraw'   => $withdrawdata->id,
                'id_member'     => $withdrawdata->id_member,
                'bank_code'     => $bank_code,
                'bill'          => $account_number,
                'bill_name'     => $account_holder,
                'nominal'       => $amount_faspay,
                'status'        => 0,
                'description'   => 'Faspay Withdraw Tanggal ' . date('Y-m-d', strtotime($withdrawdata->datecreated)),
                'date_withdraw' => date('Y-m-d', strtotime($withdrawdata->datecreated)),
                'datecreated'   => $datetime
            );
            if (!$this->Model_Faspay->save_data_faspay_out($data_faspay_out)) {
                $this->db->trans_rollback();
                $data['message'] = 'Proses Transfer Faspay tidak berhasil. Simpan data Faspay Out ERROR';
                die(json_encode($data));
            }
        }

        // if withdraw succeed
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Proses Transfer Faspay tidak berhasil. Terjadi kesalahan pada proses data transaksi.';
            die(json_encode($data));
        } else {
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();

            an_log_fastpay('FASPAY_WD', 'ATTEMPTING', json_encode($transfer), $transfer->trx_id, $withdrawdata->id);

            // Set JSON data
            $data['status']     = 'success';
            $data['message']    = 'Proses Transfer Withdraw oleh Faspay berhasil.';
            // JSON encode data
            die(json_encode($data));
        }
    }

    // =============================================================================================
    // FASPAY CALLBACK ACTION 
    // =============================================================================================
    
    /**
     * Callback URL for Fastpay Transfer function.
     */
    function fastpay_transfer_callback()
    {
        $data_notif = file_get_contents('php://input');
        $data       = json_decode($data_notif);
        $datetime   = date('Y-m-d H:i:s');

        if( $data ){
            // Set data Faspay Trx
            $va                             = ( isset($data->virtual_account) ? $data->virtual_account : '' );
            $va_name                        = ( isset($data->va_name) ? $data->va_name : '' );
            $beneficiary_virtual_account    = ( isset($data->beneficiary_virtual_account) ? $data->beneficiary_virtual_account : '' );
            $beneficiary_account            = ( isset($data->beneficiary_account) ? $data->beneficiary_account : '' );
            $beneficiary_name               = ( isset($data->beneficiary_name) ? $data->beneficiary_name : '' );
            $trx_id                         = ( isset($data->trx_id) ? $data->trx_id : '' );
            $trx_date                       = ( isset($data->trx_date) ? $data->trx_date : '' );
            $trx_amount                     = ( isset($data->trx_amount) ? $data->trx_amount : '' );
            $trx_desc                       = ( isset($data->trx_desc) ? $data->trx_desc : '' );
            $trx_status                     = ( isset($data->trx_status) ? $data->trx_status : '' );
            $trx_status_date                = ( isset($data->trx_status_date) ? $data->trx_status_date : '' );
            $trx_reff                       = ( isset($data->trx_reff) ? $data->trx_reff : '' );
            $trx_no                         = ( isset($data->trx_no) ? $data->trx_no : '' );
            $bank_code                      = ( isset($data->bank_code) ? $data->bank_code : '' );
            $bank_name                      = ( isset($data->bank_name) ? $data->bank_name : '' );
            $bank_response_code             = ( isset($data->bank_response_code) ? $data->bank_response_code : '' );
            $bank_response_msg              = ( isset($data->bank_response_msg) ? $data->bank_response_msg : '' );
            $signature                      = ( isset($data->signature) ? $data->signature : '' );

            if( $trx_status == 1 ){ $status = 'ONPROCESS'; }
            elseif( $trx_status == 2 ){ $status = 'SUCCESS'; }
            elseif( $trx_status == 4 ){ $status = 'FAILED'; }
            else{ $status = 'INVALID'; }

            an_log_fastpay('FASPAY_WD_CALLBACK', $status, json_encode($data), $trx_id);
            
            if ($FaspayTrx = $this->Model_Faspay->get_faspay_trx_id($trx_id, TRUE)) {
                $dataFaspayTrx = array(
                    "va"                            => $va,
                    "va_name"                       => $va_name,
                    "beneficiary_virtual_account"   => $beneficiary_virtual_account,
                    "beneficiary_account"           => $beneficiary_account,
                    "beneficiary_name"              => $beneficiary_name,
                    "trx_id"                        => $trx_id,
                    "trx_date"                      => $trx_date,
                    "trx_amount"                    => $trx_amount,
                    "trx_desc"                      => $trx_desc,
                    "trx_status"                    => $trx_status,
                    "trx_status_date"               => $trx_status_date,
                    "trx_reff"                      => $trx_reff,
                    "trx_no"                        => $trx_no,
                    "bank_code"                     => $bank_code,
                    "bank_name"                     => $bank_name,
                    "bank_response_code"            => $bank_response_code,
                    "bank_response_msg"             => $bank_response_msg,
                    "signature"                     => $signature,
                    "response"                      => json_encode($data),
                    "datemodified"                  => $datetime
                );
                
                // Get WD Data by Faspay Trx ID
                $wd = $this->Model_Faspay->get_withdraw_by_faspay($trx_id);
                if( $wd ){
                    $dataFaspayTrx['id_member']     = $wd->id_member;
                    
                    // Update Data Faspay Trx
                    if( $trx_status != $FaspayTrx->trx_status ){
                        $this->Model_Faspay->update_faspay_trx_data($trx_id, $dataFaspayTrx);
                        
                        // Update Data Faspay Trx Out
                        $data_out_update    = array(
                            'status'        => ( $trx_status == 2 ? 1 : $trx_status ),
                            'datemodified'  => $datetime
                        );
                        if( $trx_status == 4 ){
                            $data_out_update['description'] = 'TRANSFER PROCESS FAILED';
                        }
                        $this->Model_Faspay->update_faspay_out_data_by_trx_id($trx_id, $data_out_update);
                        
                        // Update Data WD
                        $data_wd_update     = array(
                            'status'        => ( $trx_status == 2 ? $trx_status : 3 ), 
                            'datemodified'  => $datetime
                        );
                        $this->Model_Faspay->update_data_withdraw($wd->id, $data_wd_update);
                        
                        // Send Notification
                        if ($member = $this->Model_Member->get_memberdata($wd->id_member)) {
                            // Send Email
                            $this->an_email->send_email_withdraw($member, $wd);
                        }
                    }
                }
            }else{
                $dataFaspayTrx = array(
                    "va"                            => $va,
                    "va_name"                       => $va_name,
                    "beneficiary_virtual_account"   => $beneficiary_virtual_account,
                    "beneficiary_account"           => $beneficiary_account,
                    "beneficiary_name"              => $beneficiary_name,
                    "trx_id"                        => $trx_id,
                    "trx_date"                      => $trx_date,
                    "trx_amount"                    => $trx_amount,
                    "trx_desc"                      => $trx_desc,
                    "trx_status"                    => $trx_status,
                    "trx_status_date"               => $trx_status_date,
                    "trx_reff"                      => $trx_reff,
                    "trx_no"                        => $trx_no,
                    "bank_code"                     => $bank_code,
                    "bank_name"                     => $bank_name,
                    "bank_response_code"            => $bank_response_code,
                    "bank_response_msg"             => $bank_response_msg,
                    "signature"                     => $signature,
                    "response"                      => json_encode($data),
                    "datecreated"                   => $datetime
                );

                // Get WD Data by Faspay Trx ID
                $wd = $this->Model_Faspay->get_withdraw_by_faspay($trx_id);
                if( $wd ){
                    $dataFaspayTrx['id_member']     = $wd->id_member;
                    
                    // Save Data Faspay Trx
                    if ($this->Model_Faspay->save_data_faspay_trx($dataFaspayTrx)) {
                        // Update Data Faspay Trx Out
                        $data_out_update    = array(
                            'status'        => ( $trx_status == 2 ? 1 : $trx_status ),
                            'datemodified'  => $datetime
                        );
                        if( $trx_status == 4 ){
                            $data_out_update['description'] = 'TRANSFER PROCESS FAILED';
                        }
                        $this->Model_Faspay->update_faspay_out_data_by_trx_id($trx_id, $data_out_update);
                        
                        // Update Data WD
                        $data_wd_update     = array(
                            'status'        => ( $trx_status == 2 ? $trx_status : 3 ), 
                            'datemodified'  => $datetime
                        );
                        $this->Model_Faspay->update_data_withdraw($wd->id, $data_wd_update);
                        
                        // Send Notification
                        if ($member = $this->Model_Member->get_memberdata($wd->id_member)) {
                            // Send Email
                            $this->an_email->send_email_withdraw($member, $wd);
                        }
                    }
                }
            }
        }
        die();
    }
}

/* End of file Flip.php */
/* Location: ./application/controllers/Flip.php */
