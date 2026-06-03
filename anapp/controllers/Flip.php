<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Flip Controller.
 *
 * @class     Flip
 * @version   1.0.0
 */
class Flip extends AN_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA FLIP
    // =============================================================================================

    /**
     * Flip Transaction Out List Data function.
     */
    function fliptrxlistdata()
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
        $s_flip_id          = $this->input->post('search_flip_id');
        $s_flip_id          = an_isset($s_flip_id, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '');
        $s_bank             = $this->input->post('search_bank');
        $s_bank             = an_isset($s_bank, '');
        $s_bill             = $this->input->post('search_bill');
        $s_bill             = an_isset($s_bill, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_flip_id)) {
            $condition .= str_replace('%s%', $s_flip_id, ' AND %flip_id% LIKE "%%s%%"');
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
            $order_by .= '%flip_id% ' . $sort;
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
            $data_list      = $this->Model_Flip->get_all_flip_out($limit, $offset, $condition, $order_by);
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
                $flip_id    = an_encrypt($row->flip_id);
                $nominal    = an_accounting($row->nominal, $currency, true);

                $bank       = 'Bank : <b>' . strtoupper($row->bank_code) . '</b>' . br();
                $bank      .= 'No. Rek : <b>' . $row->bill . '</b>' . br();
                $bank      .= 'AN. Rek : <b>' . strtoupper($row->bill_name) . '</b>' . br();

                $status     = '<span class="label label-sm label-default">PENDING</span>';
                $receipt    = '-';

                $btn_action = '<a href="' . base_url('flip/checkstatustransferflip/' . $flip_id) . '" 
                                class="btn btn-xs btn-flat bg-blue btn-tooltip checktransferflip" 
                                title="Cek Status Transaksi Flip"><i class="fa fa-refresh"></i> Cek Status</a>';

                if ($row->status == 1) {
                    $status = '<span class="label label-sm label-success">DONE</span>';
                    $receipt = '<a href="' . $row->receipt . '" target="_blank" class="btn btn-xs btn-flat btn-success"><i class="fa fa-download"></i> Download</a>';
                    $btn_action = '-';
                }
                if ($row->status >= 2) {
                    $status = '<span class="label label-sm label-danger">CANCELLED</span>';
                    $btn_action = '-';
                }

                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center(date('Y-m-d @H:i', strtotime($row->datecreated))) . '</div>',
                    '<div style="min-width:110px">' . an_center(date('Y-m-d', strtotime($row->date_withdraw))) . '</div>',
                    an_center(an_strong($row->flip_id)),
                    an_center('<a href="' . base_url('profile/' . $id) . '">' . an_strong(strtolower($row->username)) . '</a>'),
                    $bank,
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    an_center($status),
                    an_center($receipt),
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
     * Flip Topup List Data function.
     */
    function fliptopuplistdata()
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
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = an_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = an_isset($s_nominal_max, '');
        $s_topup_id         = $this->input->post('search_topup_id');
        $s_topup_id         = an_isset($s_topup_id, '');
        $s_bank             = $this->input->post('search_bank');
        $s_bank             = an_isset($s_bank, '');
        $s_bill             = $this->input->post('search_bill');
        $s_bill             = an_isset($s_bill, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_topup_id)) {
            $condition .= str_replace('%s%', $s_topup_id, ' AND %topup_id% LIKE "%%s%%"');
        }
        if (!empty($s_bank)) {
            $condition .= str_replace('%s%', $s_bank, ' AND %bank% LIKE "%%s%%"');
        }
        if (!empty($s_bill)) {
            $condition .= str_replace('%s%', $s_bill, ' AND %bill% LIKE "%%s%%"');
        }
        if (!empty($s_nominal_min)) {
            $condition .= ' AND %amount% >= ' . $s_nominal_min . '';
        }
        if (!empty($s_nominal_max)) {
            $condition .= ' AND %amount% <= ' . $s_nominal_max . '';
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
            $order_by .= '%topup_id% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%bank% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%amount% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%amount% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%status% ' . $sort;
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($is_admin) {
            $data_list      = $this->Model_Flip->get_all_flip_in($limit, $offset, $condition, $order_by);
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
                $id         = an_encrypt($row->id);
                $nominal    = an_accounting($row->amount, $currency, true);

                $bank       = 'Bank : <b>' . strtoupper($row->bank) . '</b>' . br();
                $bank      .= 'No. Rek : <b>' . $row->bill . '</b>' . br();
                $bank      .= 'AN. Rek : <b>' . strtoupper($row->bill_name) . '</b>';

                $detail     = 'Nominal : <b>' . an_accounting($row->real_amount, $currency) . '</b>' . br();
                $detail    .= 'Kode Unik : <b>' . an_accounting($row->code, $currency) . '</b>';

                $status     = '<span class="label label-sm label-default">PENDING</span>';
                $btn_action = '<a href="' . base_url('flip/checkstatustopup/' . $id) . '" 
                                class="btn btn-xs btn-flat bg-blue btn-tooltip checkfliptopup" 
                                title="Cek Status Topup"><i class="fa fa-refresh"></i> Cek Status</a>';

                if ($row->status == 1) {
                    $status = '<span class="label label-sm label-success">DONE</span>';
                    $btn_action = '';
                }
                if ($row->status >= 2) {
                    $status = '<span class="label label-sm label-danger">CANCELLED</span>';
                    $btn_action = '';
                }

                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center(date('Y-m-d @H:i', strtotime($row->datecreated))) . '</div>',
                    an_center(an_strong($row->topup_id)),
                    $bank,
                    '<div style="min-width:70px">' . $nominal . '</div>',
                    $detail,
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
     * Flip Inquiry Bank List Data function.
     */
    function flipinquirylistdata()
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
            $data_list      = $this->Model_Flip->get_all_flip_inquiry($limit, $offset, $condition, $order_by);
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
                $lbl_class      = 'danger';
                if (strtoupper($row->status) == 'PENDING') {
                    $lbl_class = 'default';
                }
                if (strtoupper($row->status) == 'SUCCESS') {
                    $lbl_class = 'success';
                }
                $status = '<span class="label label-sm label-' . $lbl_class . '">' . strtoupper($row->status) . '</span>';

                $records["aaData"][]    = array(
                    an_center($i),
                    '<div style="min-width:110px">' . an_center(date('Y-m-d @H:i', strtotime($row->datecreated))) . '</div>',
                    an_center(an_strong(strtoupper($row->bank_code))),
                    an_center(an_strong($row->account_number)),
                    an_center(an_strong($account_holder)),
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

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // FLIP ACTION 
    // =============================================================================================

    /**
     * Topup Saldo function.
     */
    function topupsaldo()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('flip/fliptopup'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $flip_active        = get_option('flip_active');
        $flip_active        = $flip_active ? $flip_active : 0;

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Proses Top Up Dana Flip Tidak Berhasil');

        // POST Input Form
        $nominal            = trim( $this->input->post('nominal') );
        $nominal            = an_isset($nominal, 0, 0, true);
        $nominal            = str_replace('.', '', $nominal);
        $nominal            = max(0, $nominal);
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if ( $flip_active != ACTIVE ) {
            $data['message'] = 'Maaf, Fitur Flip belum diaktifkan !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, yang dapat melakukan Top Up Flip hanya Administrator !';
            die(json_encode($data));
        }

        if (!$nominal || $nominal == 0) {
            $data['message'] = 'Jumlah Top Up harus di isi. Silahkan inputkan jumlah nominal Top Up !';
            die(json_encode($data));
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if ( $staff = an_get_current_staff() ) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( an_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['status']     = 'Topup Flip';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            an_log_action('FLIP_TOPUP', 'ERROR', $confirmed_by, json_encode($log_data));
            die(json_encode($data));
        }

        $payloads       = array("sender_bank" => "bca", "amount" => $nominal);
        $ch             = curl_init();
        $secret_key     = get_option('flip_secret');
        curl_setopt($ch, CURLOPT_URL, "https://big.flip.id/api/v2/balance/top-up");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response       = curl_exec($ch);
        $curl_error     = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $response === false) {
            an_log_flip('FLIP_TOPUP', 'ERROR', 'ERROR REQUEST TOPUP');
            $data['message'] = 'Proses Top Up Saldo Flip tidak berhasil. Silahkan coba beberapa saat lagi !';
            die(json_encode($data));
        }

        $js_response    = json_decode($response);
        $log_data       = array('cookie' => $_COOKIE, 'response' => $response);

        if (isset($js_response->total_amount)) {
            $data_topup = array(
                'topup_id'          => $js_response->id,
                'code'              => $js_response->unique_code,
                'amount'            => $js_response->total_amount,
                'real_amount'       => $amount,
                'bank'              => $js_response->transfer_to->bank_code,
                'bill'              => $js_response->transfer_to->bank_account_number,
                'bill_name'         => $js_response->transfer_to->account_holder_name,
                'status'            => 0,
                'datecreated'       => date('Y-m-d H:i:s'),
            );

            $log_data['flip_id']    = $js_response->id;
            an_log_flip('FLIP_TOPUP', 'ATTEMPTING', json_encode($log_data), $js_response->id);

            if ($save_id = $this->Model_Flip->save_data_flip_in($data_topup)) {
                $data['status']  = 'success';
                $data['message'] = 'Proses Top Up Dana Flip Berhasil';
            } else {
                $data['message'] = 'Proses Top Up Dana Flip Tidak Berhasil';
            }
            die(json_encode($return));
        } else {
            $log_data['status']     = 'ERROR';
            an_log_flip('FLIP_TOPUP', 'ATTEMPTING', json_encode($log_data));
            die(json_encode($data));
        }
    }

    /**
     * Flip Inquiry function.
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

        $username               = $this->input->post('username');
        $username               = an_isset($username, '');
        $account_number         = $this->input->post('account_number');
        $account_number         = an_isset($account_number, 0);
        $bank_flipcode          = $this->input->post('bank_flipcode');
        $bank_flipcode          = an_isset($bank_flipcode, '');
        $access                 = $this->input->post('access');
        $access                 = an_isset($access, '');

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

            // -------------------------------------------------
            // Check Member Data
            // -------------------------------------------------
            if (!$bankdata = an_banks($memberdata->bank)) {
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Data Bank tidak ditemukan atau belum terdaftar',
                );
                die(json_encode($data));
            }

            $username           = $memberdata->username;
            $account_number     = $memberdata->bill;
            $bank_flipcode      = $bankdata->flipcode;
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
        // Check Data Inquiry
        // -------------------------------------------------
        // if( $inquiry = $this->Model_Flip->get_detail_flip_inquiry_by_account($account_number) ){
        //     if( $inquiry->status != "SUCCESS" ){
        //         if( !$this->Model_Flip->delete_inquiry($inquiry->id) ){
        //             $data = array(
        //                 'status'        => 'error',
        //                 'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Delete data FLIP Inquiry error',
        //             ); die(json_encode($data));
        //         }
        //     }
        // }

        // -------------------------------------------------
        // Process Send Inquiry
        // -------------------------------------------------
        $account_holder         = strtoupper($memberdata->bill_name);
        $payloads               = array("account_number" => trim($account_number), "bank_code" => $bank_flipcode);
        $ch                     = curl_init();
        // $secret_key             = config_item('flip_secret');
        $secret_key             = get_option('flip_secret');
        $flip_url_inquiry       = config_item('flip_url_bank_inquiry');

        curl_setopt($ch, CURLOPT_URL, $flip_url_inquiry);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            'idempotency-key: ' . md5($memberdata->username)
        ));
        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response               = curl_exec($ch);
        $curl_error             = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $response === false) {
            an_log_flip('FLIP_INQUIRY', 'ERROR', 'ERROR REQUEST INQUIRY DATA');
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Respon Flip Inquiry Gagal',
            );
            die(json_encode($data));
        }

        $decoded_data = json_decode($response);
        $data_inquiry = array(
            "bank_code"         => $decoded_data->bank_code,
            "account_number"    => $decoded_data->account_number,
            "account_holder"    => ($decoded_data->account_holder ? $decoded_data->account_holder : $account_holder),
            "status"            => $decoded_data->status,
            "datecreated"       => $curdate
        );

        if ($inquiry = $this->Model_Flip->get_detail_flip_inquiry_by_account($decoded_data->account_number)) {
            unset($data_inquiry['account_number']);
            unset($data_inquiry['datecreated']);
            if (!$this->Model_Flip->update_flip_inquiry_data($decoded_data->account_number, '', $data_inquiry)) {
                // an_log_flip('FLIP_INQUIRY', 'FAILED', json_encode($decoded_data));
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry',
                );
                die(json_encode($data));
            }
        } else {
            if (!$this->Model_Flip->save_data_flip_inquiry($data_inquiry)) {
                // an_log_flip('FLIP_INQUIRY', 'FAILED', json_encode($decoded_data));
                $data = array(
                    'status'        => 'error',
                    'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry',
                );
                die(json_encode($data));
            }
        }

        if ($decoded_data->status == "INVALID_ACCOUNT_NUMBER") {
            // an_log_flip('FLIP_INQUIRY', 'INVALID_ACCOUNT', json_encode($decoded_data));
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank tidak berhasil! Nomor Rekening Bank Invalid'
            );
            die(json_encode($data));
        } elseif ($decoded_data->status == "PENDING") {
            // an_log_flip('FLIP_INQUIRY', 'PENDING', json_encode($decoded_data));
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Inquiry Akun Bank sedang dalam proses. Status PENDING. Mohon untuk menunggu beberapa saat dan silahkan refresh kembali data Withdraw'
            );
            die(json_encode($data));
        } else {
            if ($id_withdraw) {
                $datawithdraw   = array(
                    'bank'              => $memberdata->bank,
                    'bank_code'         => $decoded_data->bank_code,
                    'bill'              => $decoded_data->account_number,
                    'bill_name'         => $decoded_data->account_holder,
                    'inquiry_status'    => $decoded_data->status
                );

                if (!$update_wd_data = $this->Model_Bonus->update_data_withdraw($id_withdraw, $datawithdraw)) {
                    $data = array('status' => 'error', 'message' => 'Proses Inquiry Akun Bank tidak berhasil! Terjadi kesalahan pada proses simpan data inquiry.');
                    die(json_encode($data));
                }
            }

            // an_log_flip('FLIP_INQUIRY', 'SUCCESS', json_encode($decoded_data));
            $data = array(
                'status'        => 'success',
                'message'       => 'Proses Inquiry Akun Bank berhasil! Nomor Rekening Bank Valid'
            );
            die(json_encode($data));
        }
    }

    /**
     * Withdraw Transfer Flip function
     */
    function transferflip($id = 0)
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
        $flip_active        = get_option('flip_active');
        $flip_active        = $flip_active ? $flip_active : 0;
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Proses Transfer Bonus oleh Flip Tidak Berhasil');

        // POST Input Form
        $nominal            = trim( $this->input->post('nominal') );
        $nominal            = an_isset($nominal, 0, 0, true);
        $nominal            = str_replace('.', '', $nominal);
        $nominal            = max(0, $nominal);
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if ( $flip_active != ACTIVE ) {
            $data['message'] = 'Maaf, Fitur Flip belum diaktifkan !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, yang dapat melakukan Transfer Bonus oleh Flip hanya Administrator !';
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
        $memberdata             = an_get_memberdata_by_id($withdrawdata->id_member);
        if (!$memberdata) {
            $data['message'] = 'Data member withdrawal tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }

        // Set Amount Flip
        $amount_accept      = $withdrawdata->nominal_receipt;
        $amount_flip        = $amount_accept;

        // Check Rekening Bank Inquiry
        $account_number     = trim($withdrawdata->bill);
        $account_holder     = trim($withdrawdata->bill_name);
        $bank_code          = trim($withdrawdata->bank_code);

        if ($withdrawdata->inquiry_status != 'SUCCESS') {
            $data['message'] = 'Tidak dapat dilakukan proces Flip. Status Inquiry PENDING atau INVALID!';
            die(json_encode($data));
        }

        // Set Bank Data
        if (!$account_number || empty($bank_code) || strlen($bank_code) <= 2) {
            $data['message'] = 'Proses Transfer Flip tidak berhasil. Data Kode Flip Bank tidak ditemukan';
            die(json_encode($data));
        }

        $total_topup        = $this->Model_Flip->count_total_topup();
        $total_trf          = $this->Model_Flip->count_total_trx_done();
        $total_trf_fee      = $this->Model_Flip->count_total_trx_fee();
        if ( $trf_pending = $this->Model_Flip->count_total_trx_pending() ) {
            $total_trf     += $trf_pending->total_pending;
            $total_trf_fee += $trf_pending->total_fee;
        }
        $saldo_flip         = $total_topup - $total_trf - $total_trf_fee - 5000;
        $saldo_flip         = max($saldo_flip, 0);

        if ($amount_accept > $saldo_flip) {
            $data['amount_accept']  = $amount_accept;
            $data['saldo_flip']     = $saldo_flip;
            $data['message']        = 'Proses Transfer Flip tidak berhasil. Saldo FLIP tidak mencukupi untuk proses transfer ini';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Flip API Process
        $payloads = array(
            "account_number"    => $account_number,
            "bank_code"         => $bank_code,
            "amount"            => $amount_accept,
            "remark"            => "Alpha Netwotk"
        );

        if (!empty($memberdata->city_code)) {
            $payloads["recipient_city"] = $memberdata->city_code;
        }

        $ch             = curl_init();
        // $secret_key     = config_item('flip_secret');
        $secret_key     = get_option('flip_secret');
        $flip_url       = config_item('flip_url');
        $flip_id        = !empty($withdrawdata->flip_id) ? $withdrawdata->flip_id : 0;
        $idempotency    = $withdrawdata->id . '-' . $flip_id;
        $idempotency    = md5($idempotency);

        curl_setopt($ch, CURLOPT_URL, $flip_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            'idempotency-key: ' . $idempotency
        ));

        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response       = curl_exec($ch);
        $curl_error     = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $response === false) {
            $this->db->trans_rollback();
            $data['message'] = 'Proses Transfer Flip tidak berhasil. Request ke Sistem Flip Gagal';
            die(json_encode($data));
        }

        $endata = json_decode($response);
        if (!$endata) {
            $this->db->trans_rollback();
            $data['message'] = 'Proses Transfer Flip tidak berhasil. Tidak ada respon dari Sistem Flip';
            die(json_encode($data));
        }

        // Set Log Data
        $log_data       = array('cookie' => $_COOKIE, 'response' => $endata);

        if (!isset($endata->id) || strlen($endata->id) < 2) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $log_status = 'ERROR';
            $msg_error  = 'Proses Transfer Flip tidak berhasil. Status Flip ERROR';
            if (an_isset($endata->code, '') == 'BALANCE_INSUFFICIENT') {
                $log_status = 'BALANCE_INSUFFICIENT';
                $msg_error  = 'Proses Transfer Flip tidak berhasil. Saldo FLIP tidak mencukupi untuk proses transfer ini';
            }

            an_log_flip('FLIP_WD', $log_status, json_encode($log_data), '', $withdrawdata->id);

            // Set JSON data
            $data['message']    = $msg_error;
            $data['response']   = $response;
            die(json_encode($data));
        }

        if (an_isset($endata->status, "") == 'CANCELLED') {
            // Rollback Transaction
            $this->db->trans_rollback();
            an_log_flip('FLIP_WD', 'CANCELLED', json_encode($log_data), $endata->id, $withdrawdata->id);
            $data['message'] = 'Proses Transfer Flip tidak berhasil. Status Transfer Flip CANCELLED';
            die(json_encode($data));
        }

        $confirm_by     = $current_member->username;
        if ($staff = an_get_current_staff()) {
            $confirm_by = $staff->username;
        }

        $data_wd_update_flip = array(
            'status'            => 2,
            'flip_id'           => $endata->id,
            'datemodified'      => $curdate,
            'dateconfirm'       => $curdate,
            'confirm_by'        => $confirm_by
        );
        if ($this->Model_Flip->update_data_withdraw($withdrawdata->id, $data_wd_update_flip)) {
            $data_flip_out      = array(
                'flip_id'       => $endata->id,
                'id_withdraw'   => $withdrawdata->id,
                'id_member'     => $withdrawdata->id_member,
                'bank_code'     => $bank_code,
                'bill'          => $account_number,
                'bill_name'     => $account_holder,
                'nominal'       => $amount_accept,
                'status'        => 0,
                'description'   => 'Flip Withdraw Tanggal ' . date('Y-m-d', strtotime($withdrawdata->datecreated)),
                'date_withdraw' => date('Y-m-d', strtotime($withdrawdata->datecreated)),
                'datecreated'   => $curdate
            );
            if (!$this->Model_Flip->save_data_flip_out($data_flip_out)) {
                $this->db->trans_rollback();
                $data['message'] = 'Proses Transfer Flip tidak berhasil. Simpan data Flip Out ERROR';
                die(json_encode($data));
            }
        }

        // if withdraw succeed
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Proses Transfer Flip tidak berhasil. Terjadi kesalahan pada proses data transaksi.';
            die(json_encode($data));
        } else {
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();

            an_log_flip('FLIP_WD', 'ATTEMPTING', json_encode($log_data), $endata->id, $withdrawdata->id);

            // Set JSON data
            $data['status']     = 'success';
            $data['message']    = 'Proses Transfer Withdraw oleh Flip berhasil.';
            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Check Status Topup Saldo Flip function.
     */
    function checkstatustopup($id = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('flip/fliptopup'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        if (!$id) {
            $data = array('status' => 'error', 'message' => 'ID Top Up Saldo Flip tidak boleh kosong !');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_encrypt($id, 'decrypt');

        if (!$is_admin) {
            $data = array('status' => 'error', 'message' => 'Maaf, yang dapat melakukan Cek Status Top Up Flip hanya Administrator !');
            die(json_encode($data));
        }

        if (!$get_flip_in =  $this->Model_Flip->get_all_flip_in(0, 0, 'WHERE %id% = ' . $id)) {
            $data = array('status' => 'error', 'message' => 'ID Top Up tidak ditemukan. Silahkan Pilih Top Up FLip !');
            die(json_encode($data));
        }

        $_upd_data      = ($get_flip_in[0]->status == 0) ? true : false;
        $topup_id       = $get_flip_in[0]->topup_id;

        // $secret_key     = config_item('flip_secret');
        $secret_key     = get_option('flip_secret');
        $ch             = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://big.flip.id/api/v2/balance/top-up/' . $topup_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response       = curl_exec($ch);
        $curl_error     = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $response === false) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Cek Top Up Saldo Flip tidak berhasil. Silahkan coba beberapa saat lagi !',
            );
            die(json_encode($data));
        }

        $js_response    = json_decode($response);
        $log_data       = array('cookie' => $_COOKIE, 'response' => $response);

        if (isset($js_response->status)) {
            if ($_upd_data) {
                $status_flip = 0;
                if ($js_response->status == 'CANCELLED') {
                    $status_flip = 2;
                }
                if ($js_response->status == 'DONE') {
                    $status_flip = 1;
                }

                if ($status_flip) {
                    $dataupt = array('status' => $status_flip);
                    $this->Model_Flip->update_flip_in_data($js_response->id, $dataupt);
                }
            }

            $log_data['flip_id']    = $js_response->id;
            an_log_flip('FLIP_TOPUP_CHECK', $js_response->status, json_encode($log_data), $js_response->id);

            $message  = ' Check transaksi status Topup Flip Berhasil. <br>';
            $message .= ' * Topup ID : <b>' . $js_response->id . '</b><br>';
            $message .= ' * Nominal Transfer Topup : <b>' . an_accounting($js_response->total_amount, 'Rp') . '</b> <br> ';
            $message .= ' * Status Topup Flip : <b>' . $js_response->status . '</b>';

            $js_response = 'response';
            $return = array('status' => 'success', 'message' => $message, 'data' => $js_response);
            die(json_encode($return));
        } else {
            an_log_flip('FLIP_TOPUP_CHECK', 'ERROR', json_encode($log_data), $topup_id);

            $return = array('status' => 'error', 'message' => 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
            die(json_encode($return));
        }
    }

    /**
     * Check Status Transfer Flip function.
     */
    function checkstatustransferflip($flip_id = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('flip/fliptopup'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        if (!$flip_id) {
            $data = array('status' => 'error', 'message' => 'FLIP ID tidak boleh kosong !');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $flip_id            = an_encrypt($flip_id, 'decrypt');

        if (!$is_admin) {
            $data = array('status' => 'error', 'message' => 'Maaf, yang dapat melakukan Cek Status Transaksi Flip hanya Administrator !');
            die(json_encode($data));
        }

        if (!$withdraw = $this->Model_Flip->get_withdraw_by_flip($flip_id)) {
            $data = array('status' => 'error', 'message' => 'FLIP ID tidak ditemukan. Silahkan Pilih transaksi Flip lainnya !');
            die(json_encode($data));
        }

        // $secret_key     = config_item('flip_secret');
        $secret_key     = get_option('flip_secret');
        $flip_url       = config_item('flip_url');
        $ch             = curl_init();

        curl_setopt($ch, CURLOPT_URL, $flip_url . '/' . $flip_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");

        $response       = curl_exec($ch);
        $curl_error     = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $response === false) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Proses Cek transaksi Flip tidak berhasil. Silahkan coba beberapa saat lagi !',
            );
            die(json_encode($data));
        }

        $js_response    = json_decode($response);
        $log_data       = array('cookie' => $_COOKIE, 'response' => $response);

        if (isset($js_response->status)) {
            $_updated   = false;
            $datetime   = date('Y-m-d H:i:s');
            $data_flip  = array(
                "user_id"           =>  $js_response->user_id,
                "amount"            =>  $js_response->amount,
                "status"            =>  $js_response->status,
                "timestamp"         =>  $js_response->timestamp,
                "bank_code"         =>  $js_response->bank_code,
                "account_number"    =>  $js_response->account_number,
                "recipient_name"    =>  $js_response->recipient_name,
                "sender_bank"       =>  $js_response->sender_bank,
                "remark"            =>  $js_response->remark,
                "receipt"           =>  $js_response->receipt,
                "time_served"       =>  $js_response->time_served,
                "bundle_id"         =>  $js_response->bundle_id,
                "company_id"        =>  $js_response->company_id,
                "recipient_city"    =>  $js_response->recipient_city,
                "created_from"      =>  $js_response->created_from,
                "direction"         =>  $js_response->direction,
                "sender"            =>  $js_response->sender,
                "fee"               =>  $js_response->fee,
                "datemodified"      =>  $datetime
            );

            if (strtoupper($js_response->status) == 'CANCELLED') {
                $data_flip['fee_manual'] = 0;
            }

            if (strtoupper($js_response->status) == 'DONE') {
                $data_flip['fee_manual'] = 5000;
            }

            if ($getFlip    = $this->Model_Flip->get_flip_trx_id($flip_id, TRUE)) {
                if (strtoupper($getFlip->status) != strtoupper($js_response->status)) {
                    if ($this->Model_Flip->update_flip_data($flip_id, $data_flip)) {
                        $_updated = true;
                    }
                }
            } else {
                $data_flip['flip_id'] = $js_response->id;
                if ($this->Model_Flip->save_data_flip($data_flip)) {
                    $_updated = true;
                }
            }

            if ($_updated) {
                if (strtoupper($js_response->status) == 'CANCELLED') {
                    // Update Data Withdraw
                    $datawithdraw       = array('status' => 0, 'datemodified' => $datetime);
                    $this->Model_Flip->update_data_withdraw_flip($flip_id, $datawithdraw);

                    // Update Data Flip Out
                    $data_out_update    = array(
                        'status'        => 2,
                        'receipt'       => '',
                        'description'   => 'CANCELED BY FLIP',
                        'datemodified'  => $datetime
                    );
                    $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);
                }

                if (strtoupper($js_response->status) == 'DONE') {
                    // Update Data Withdraw
                    $datawithdraw       = array('status' => 1, 'datemodified' => $datetime);
                    $this->Model_Flip->update_data_withdraw($withdraw->id, $datawithdraw);

                    // Update Data Flip Out
                    $data_out_update    = array(
                        'status'        => 1,
                        'receipt'       => $js_response->receipt,
                        'description'   => 'DONE BY FLIP',
                        'datemodified'  => $datetime
                    );
                    $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);

                    if ($member = $this->Model_Member->get_memberdata($withdraw->id_member)) {
                        // Send Email and WhatsApp
                        $this->an_email->send_email_withdraw($member, $withdraw);
                        $this->an_wa->send_an_withdraw($member, $withdraw);
                    }
                }
            }

            an_log_flip('FLIP_WD_CHECK', strtoupper($js_response->status), json_encode($log_data), $js_response->id, $withdraw->id);

            $message  = ' Cek status transaksi Flip Berhasil. <br>';
            $message .= ' * FLIP ID : <b>' . $js_response->id . '</b><br>';
            $message .= ' * Nominal Transfer Topup : <b>' . an_accounting($js_response->amount, 'Rp') . '</b> <br> ';
            $message .= ' * Status Flip : <b>' . $js_response->status . '</b>';

            $js_response = 'response';
            $return = array('status' => 'success', 'message' => $message, 'data' => $js_response);
            die(json_encode($return));
        } else {
            an_log_flip('FLIP_WD_CHECK', 'ERROR', json_encode($log_data), $topup_id);

            $return = array('status' => 'error', 'message' => 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.');
            die(json_encode($return));
        }
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // FLIP CALLBACK ACTION 
    // =============================================================================================

    /**
     * Callback URL for Flip Inquiry function.
     */
    function flip_inquiry()
    {
        $data       = isset($_POST['data']) ? $_POST['data'] : null;
        $token      = isset($_POST['token']) ? $_POST['token'] : null;
        $cfg_token  = trim(get_option('flip_token'));
        // $cfg_token  = trim(config_item('flip_token'));

        if ($token === $cfg_token) {
            $decoded_data = json_decode($data, TRUE);
            an_log_flip('FLIP_INQUIRY_CALLBACK', $decoded_data['status'], json_encode($decoded_data));
            if ($decoded_data['status'] == 'SUCCESS') {
                $data_inquiry_update = array(
                    "account_holder"    =>  $decoded_data['account_holder'],
                    "status"            =>  $decoded_data['status']
                );
                $this->Model_Flip->update_flip_inquiry_data($decoded_data['account_number'], $decoded_data['bank_code'], $data_inquiry_update);
            } else {
                $data_inquiry_update = array(
                    "account_holder"    =>  $decoded_data['account_holder'],
                    "status"            =>  $decoded_data['status']
                );
                $this->Model_Flip->update_flip_inquiry_data($decoded_data['account_number'], $decoded_data['bank_code'], $data_inquiry_update);
            }
        }
        die();
    }

    /**
     * Callback URL for Flip Serve function.
     */
    function flip_serve()
    {
        $data       = isset($_POST['data']) ? $_POST['data'] : null;
        $token      = isset($_POST['token']) ? $_POST['token'] : null;
        // $cfg_token  = trim(config_item('flip_token'));
        $cfg_token  = trim(get_option('flip_token'));
        $datetime   = date('Y-m-d H:i:s');

        if ($token === $cfg_token) {
            $decoded_data   = json_decode($data, TRUE);
            $flip_id        = an_isset($decoded_data['id'], 0);
            $status_trx     = an_isset($decoded_data['status'], 'NONE');

            an_log_flip('FLIP_WD_CALLBACK', strtoupper($status_trx), json_encode($decoded_data), $flip_id);

            if ($getFlip    = $this->Model_Flip->get_flip_trx_id($flip_id, TRUE)) {
                $dataflip = array(
                    "user_id"           =>  $decoded_data['user_id'],
                    "amount"            =>  $decoded_data['amount'],
                    "status"            =>  $decoded_data['status'],
                    "timestamp"         =>  $decoded_data['timestamp'],
                    "bank_code"         =>  $decoded_data['bank_code'],
                    "account_number"    =>  $decoded_data['account_number'],
                    "recipient_name"    =>  $decoded_data['recipient_name'],
                    "sender_bank"       =>  $decoded_data['sender_bank'],
                    "remark"            =>  $decoded_data['remark'],
                    "receipt"           =>  $decoded_data['receipt'],
                    "time_served"       =>  $decoded_data['time_served'],
                    "bundle_id"         =>  $decoded_data['bundle_id'],
                    "company_id"        =>  $decoded_data['company_id'],
                    "recipient_city"    =>  $decoded_data['recipient_city'],
                    "created_from"      =>  $decoded_data['created_from'],
                    "direction"         =>  $decoded_data['direction'],
                    "sender"            =>  $decoded_data['sender'],
                    "fee"               =>  $decoded_data['fee'],
                    "datemodified"      =>  $datetime
                );

                if ($decoded_data['status'] == 'CANCELLED') {
                    $dataflip['fee_manual'] = 0;
                }

                if ($decoded_data['status'] == 'DONE') {
                    $dataflip['fee_manual'] = 5000;
                }

                if ($getFlip->status != $decoded_data['status']) {
                    $this->Model_Flip->update_flip_data($flip_id, $dataflip);
                    if ($decoded_data['status'] == 'CANCELLED') {
                        $datawithdraw       = array('status' => 0, 'datemodified' => $datetime);
                        $this->Model_Flip->update_data_withdraw_flip($flip_id, $datawithdraw);
                        $data_out_update    = array(
                            'status'        => 2,
                            'receipt'       => '',
                            'description'   => 'CANCELED BY FLIP',
                            'datemodified'  => $datetime
                        );
                        $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);
                    }

                    if ($decoded_data['status'] == 'DONE') {
                        if ($withdraw = $this->Model_Flip->get_withdraw_by_flip($flip_id)) {
                            // Update Data Withdraw
                            $datawithdraw       = array('status' => 1, 'datemodified' => $datetime);
                            $this->Model_Flip->update_data_withdraw($withdraw->id, $datawithdraw);

                            // Update Data Flip Out
                            $data_out_update    = array(
                                'status'        => 1,
                                'receipt'       => $decoded_data['receipt'],
                                'description'   => 'DONE BY FLIP',
                                'datemodified'  => $datetime
                            );
                            $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);

                            if ($member = $this->Model_Member->get_memberdata($withdraw->id_member)) {
                                // Send Email and WhatsApp
                                $this->an_email->send_email_withdraw($member, $withdraw);
                                $this->an_wa->send_an_withdraw($member, $withdraw);
                            }
                        }
                    }
                }
            } else {
                $dataflip = array(
                    "flip_id"           =>  $flip_id,
                    "user_id"           =>  $decoded_data['user_id'],
                    "amount"            =>  $decoded_data['amount'],
                    "status"            =>  $decoded_data['status'],
                    "timestamp"         =>  $decoded_data['timestamp'],
                    "bank_code"         =>  $decoded_data['bank_code'],
                    "account_number"    =>  $decoded_data['account_number'],
                    "recipient_name"    =>  $decoded_data['recipient_name'],
                    "sender_bank"       =>  $decoded_data['sender_bank'],
                    "remark"            =>  $decoded_data['remark'],
                    "receipt"           =>  $decoded_data['receipt'],
                    "time_served"       =>  $decoded_data['time_served'],
                    "bundle_id"         =>  $decoded_data['bundle_id'],
                    "company_id"        =>  $decoded_data['company_id'],
                    "recipient_city"    =>  $decoded_data['recipient_city'],
                    "created_from"      =>  $decoded_data['created_from'],
                    "direction"         =>  $decoded_data['direction'],
                    "sender"            =>  $decoded_data['sender'],
                    "fee"               =>  $decoded_data['fee'],
                    "datecreated"       =>  $datetime,
                    "datemodified"      =>  $datetime
                );

                if ($decoded_data['status'] == 'CANCELLED') {
                    $dataflip['fee_manual'] = 0;
                }

                if ($decoded_data['status'] == 'DONE') {
                    $dataflip['fee_manual'] = 5000;
                }

                if ($this->Model_Flip->save_data_flip($dataflip)) {
                    if ($decoded_data['status'] == 'CANCELLED') {
                        // Update Data Withdraw
                        $datawithdraw       = array('status' => 0, 'datemodified' => $datetime);
                        $this->Model_Flip->update_data_withdraw_flip($flip_id, $datawithdraw);

                        // Update Data Flip Out
                        $data_out_update    = array(
                            'status'        => 2,
                            'receipt'       => '',
                            'description'   => 'CANCELED BY FLIP',
                            'datemodified'  => $datetime
                        );
                        $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);
                    }

                    if ($decoded_data['status'] == 'DONE') {
                        if ($withdraw = $this->Model_Flip->get_withdraw_by_flip($flip_id)) {
                            // Update Data Withdraw
                            $datawithdraw       = array('status' => 1, 'datemodified' => $datetime);
                            $this->Model_Flip->update_data_withdraw($withdraw->id, $datawithdraw);

                            // Update Data Flip Out
                            $data_out_update    = array(
                                'status'        => 1,
                                'receipt'       => $decoded_data['receipt'],
                                'description'   => 'DONE BY FLIP',
                                'datemodified'  => $datetime
                            );
                            $this->Model_Flip->update_flip_out_data_by_flip($flip_id, $data_out_update);

                            if ($member = $this->Model_Member->get_memberdata($withdraw->id_member)) {
                                // Send Email and WhatsApp
                                $this->an_email->send_email_withdraw($member, $withdraw);
                                $this->an_wa->send_an_withdraw($member, $withdraw);
                            }
                        }
                    }
                }
            }

            return true;
        }
        die();
    }

    /**
     * Callback URL for Flip Serve Topup function.
     */
    function flip_serve_topup()
    {
        $data       = isset($_POST['data']) ? $_POST['data'] : null;
        $token      = isset($_POST['token']) ? $_POST['token'] : null;
        $cfg_token  = trim(get_option('flip_token'));
        // $cfg_token  = config_item('flip_token');

        $decoded_data   = json_decode($data, TRUE);
        if ($decoded_data) {
            $topup_id   = isset($decoded_data['id']) ? $decoded_data['id'] : 0;
            $status     = isset($decoded_data['status']) ? $decoded_data['status'] : 'ERROR';
            an_log_flip('FLIP_TOPUP_CALLBACK', strtoupper($status), json_encode($decoded_data), $topup_id);
            if ($token === $cfg_token) {
                if ($topup_id && strtoupper($status) == 'DONE') {
                    $this->Model_Flip->update_flip_in_data($topup_id, array('status' => 1));
                }
                if ($topup_id && strtoupper($status) == 'CANCELLED') {
                    $this->Model_Flip->update_flip_in_data($topup_id, array('status' => 2));
                }
            }
        }
        die();
    }
}

/* End of file Flip.php */
/* Location: ./application/controllers/Flip.php */
