<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pin Controller.
 *
 * @class     PIN
 * @version   1.0.0
 */
class Pin extends AN_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA PIN
    // =============================================================================================

    /**
     * Deposite PIN List function.
     */
    function depositepinlistdata()
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $search_method      = 'post';
        if( $sAction == 'export_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = isset($_REQUEST['iDisplayLength']) ? intval($_REQUEST['iDisplayLength']) : 0;
        $iDisplayStart      = isset($_REQUEST['iDisplayStart']) ? intval($_REQUEST['iDisplayStart']) : 0;
        $sEcho              = isset($_REQUEST['sEcho']) ? intval($_REQUEST['sEcho']) : '';
        $sort               = isset($_REQUEST['sSortDir_0']) ? $_REQUEST['sSortDir_0'] : '';
        $column             = isset($_REQUEST['iSortCol_0']) ? intval($_REQUEST['iSortCol_0']) : '';

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_total_min        = $this->input->$search_method('search_total_min');
        $s_total_min        = an_isset($s_total_min, '', '', true);
        $s_total_max        = $this->input->$search_method('search_total_max');
        $s_total_max        = an_isset($s_total_max, '', '', true);
        $s_active_min       = $this->input->$search_method('search_active_min');
        $s_active_min       = an_isset($s_active_min, '', '', true);
        $s_active_max       = $this->input->$search_method('search_active_max');
        $s_active_max       = an_isset($s_active_max, '', '', true);
        
        if(!empty($s_username))     { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if(!empty($s_name))         { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if(!empty($s_total_min))    { $condition .= ' AND %total% >= ?'; $params[] = $s_total_min; }
        if(!empty($s_total_max))    { $condition .= ' AND %total% <= ?'; $params[] = $s_total_max; }
        if(!empty($s_active_min))   { $condition .= ' AND %total_active% >= ?'; $params[] = $s_active_min; }
        if(!empty($s_active_max))   { $condition .= ' AND %total_active% <= ?'; $params[] = $s_active_max; }

        if($column == 1)            { $order_by .= '%username% ' . $sort; } 
        elseif($column == 2)        { $order_by .= '%name% ' . $sort; } 
        elseif($column == 3)        { $order_by .= '%total% ' . $sort; }
        elseif($column == 4)        { $order_by .= '%total_active% ' . $sort; }


        $data_list          = ( $is_admin ) ? $this->Model_Shop->get_all_total_pin_member($limit, $offset, $condition, $order_by, $params) : '';
        $records            = array();
        $records["aaData"]  = array();

        if(!empty($data_list)) {
            $iTotalRecords = an_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row) {
                $id         = an_encrypt($row->id);
                $btn_detail = '<a href="'.base_url('productdatalists/'.$id).'" class="btn btn-sm btn-default">Detail</a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center('<a href="'.base_url('profile/'.$id).'">' . an_strong(strtoupper($row->username)) . '</a>'),
                    strtoupper($row->name),
                    an_right(an_accounting($row->total)),
                    an_right(an_accounting($row->total_active)),
                    an_center($btn_detail)
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Shop->get_all_total_pin_member(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->depositepinlist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["token"]                   = $this->security->get_csrf_hash();

        echo json_encode($records);
    }

    /**
     * PIN Used List function.
     */
    function pinusedlistdata()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = ' WHERE %id_memberreg% > 0 AND %status% = 2';
        if ( !$is_admin ) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sAction            = an_isset($_REQUEST['sAction'], '');

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_id_pin           = $this->input->post('search_id_pin');
        $s_id_pin           = an_isset($s_id_pin, '', '', true);
        $s_product          = $this->input->post('search_product');
        $s_product          = an_isset($s_product, '', '', true);
        $s_register         = $this->input->post('search_register');
        $s_register         = an_isset($s_register, '', '', true);
        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_source           = $this->input->post('search_source');
        $s_source           = an_isset($s_source, '', '', true);
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        
        if(!empty($s_id_pin))       { $condition .= ' AND %id_pin% LIKE CONCAT("%", ?, "%") '; $params[] = $s_id_pin; }
        if(!empty($s_product))      { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_product; }
        if(!empty($s_register))     { $condition .= ' AND %owner% LIKE CONCAT("%", ?, "%") '; $params[] = $s_register; }
        if(!empty($s_username))     { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if(!empty($s_name))         { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if(!empty($s_source))       { $condition .= ' AND %used% = ?'; $params[] = $s_source; }
        if(!empty($s_date_min))     { $condition .= ' AND DATE(%dateused%) >= ?'; $params[] = $s_date_min; }
        if(!empty($s_date_max))     { $condition .= ' AND DATE(%dateused%) <= ?'; $params[] = $s_date_max; }

        if($column == 1)            { $order_by .= '%id_pin% ' . $sort; } 
        elseif($column == 2)        { $order_by .= '%product% ' . $sort; } 
        elseif($column == 3)        { $order_by .= '%owner% ' . $sort; }
        elseif($column == 4)        { $order_by .= '%username% ' . $sort; }
        elseif($column == 5)        { $order_by .= '%name% ' . $sort; }
        elseif($column == 6)        { $order_by .= '%used% ' . $sort; }
        elseif($column == 7)        { $order_by .= '%dateused% ' . $sort; }

        $data_list          = $this->Model_Shop->get_all_pin_member($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if(!empty($data_list)) {
            $iTotalRecords = an_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row) {
                $id_member      = an_encrypt($row->id_member);
                $id_registered  = an_encrypt($row->id_registered);
                $username       = an_strong(strtolower($row->username));
                $registered     = an_strong(strtolower($row->username_registered));
                $username       = ( $is_admin ) ?  '<a href="'.base_url('profile/'.$id_member).'">' . $username . '</a>' : $username;
                $registered     = ( $is_admin ) ?  '<a href="'.base_url('profile/'.$id_registered).'">' . $registered . '</a>' : $registered;

                $source         = '';
                if (  $row->used == 'register' ) {
                    $source     = '<span class="badge badge-default">'. strtoupper($row->used) .'</span>';
                }
                if (  $row->used == 'ro' ) {
                    $source     = '<span class="badge badge-success">'. strtoupper($row->used) .'</span>';
                }

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($row->id_pin),
                    $row->product_name,
                    an_center($username),
                    an_center($registered),
                    strtoupper($row->name_registered),
                    an_center($source),
                    an_center(date('Y-m-d @H:i', strtotime($row->dateused))),
                    ''
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["token"]                   = $this->security->get_csrf_hash();

        echo json_encode($records);
    }

    /**
     * PIN Status List function.
     */
    function pinstatuslistdata()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sAction            = an_isset($_REQUEST['sAction'], '');

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_bv_min           = $this->input->post('search_bv_min');
        $s_bv_min           = an_isset($s_bv_min, '', '', true);
        $s_bv_max           = $this->input->post('search_bv_max');
        $s_bv_max           = an_isset($s_bv_max, '', '', true);
        $s_price_min        = $this->input->post('search_price_min');
        $s_price_min        = an_isset($s_price_min, '', '', true);
        $s_price_max        = $this->input->post('search_price_max');
        $s_price_max        = an_isset($s_price_max, '', '', true);
        $s_total_min        = $this->input->post('search_total_min');
        $s_total_min        = an_isset($s_total_min, '', '', true);
        $s_total_max        = $this->input->post('search_total_max');
        $s_total_max        = an_isset($s_total_max, '', '', true);
        $s_total_in_min     = $this->input->post('search_total_in_min');
        $s_total_in_min     = an_isset($s_total_in_min, '', '', true);
        $s_total_in_max     = $this->input->post('search_total_in_max');
        $s_total_in_max     = an_isset($s_total_in_max, '', '', true);
        $s_total_out_min    = $this->input->post('search_total_out_min');
        $s_total_out_min    = an_isset($s_total_out_min, '', '', true);
        $s_total_out_max    = $this->input->post('search_total_out_max');
        $s_total_out_max    = an_isset($s_total_out_max, '', '', true);
        
        if(!empty($s_name))             { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if(!empty($s_bv_min))           { $condition .= ' AND %bv% >= ?'; $params[] = $s_bv_min; }
        if(!empty($s_bv_max))           { $condition .= ' AND %bv% <= ?'; $params[] = $s_bv_max; }
        if(!empty($s_price_min))        { $condition .= ' AND %price% >= ?'; $params[] = $s_price_min; }
        if(!empty($s_price_max))        { $condition .= ' AND %price% <= ?'; $params[] = $s_price_max; }
        if(!empty($s_total_min))        { $condition .= ' AND %total% >= ?'; $params[] = $s_total_min; }
        if(!empty($s_total_max))        { $condition .= ' AND %total% <= ?'; $params[] = $s_total_max; }
        if(!empty($s_total_in_min))     { $condition .= ' AND %total_active% >= ?'; $params[] = $s_total_in_min; }
        if(!empty($s_total_in_max))     { $condition .= ' AND %total_active% <= ?'; $params[] = $s_total_in_max; }
        if(!empty($s_total_out_min))    { $condition .= ' AND %total_used% >= ?'; $params[] = $s_total_out_min; }
        if(!empty($s_total_out_max))    { $condition .= ' AND %total_used% <= ?'; $params[] = $s_total_out_max; }

        if($column == 1)            { $order_by .= '%name% ' . $sort; } 
        elseif($column == 2)        { $order_by .= '%bv% ' . $sort; } 
        elseif($column == 3)        { $order_by .= '%price% ' . $sort; } 
        elseif($column == 4)        { $order_by .= '%total% ' . $sort; } 
        elseif($column == 5)        { $order_by .= '%total_active% ' . $sort; }
        elseif($column == 6)        { $order_by .= '%total_used% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Shop->get_all_total_pin_product($limit, $offset, $condition, $order_by, $params) : '';
        $records            = array();
        $records["aaData"]  = array();

        if(!empty($data_list)) {
            $iTotalRecords = an_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row) {
                $records["aaData"][] = array(
                    an_center($i),
                    strtoupper($row->name),
                    an_right(an_accounting($row->bv)),
                    an_right(an_accounting($row->price)),
                    an_right(an_accounting($row->total_pin)),
                    an_right(an_accounting($row->total_active)),
                    an_right(an_accounting($row->total_used)),
                    ''
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["token"]                   = $this->security->get_csrf_hash();

        echo json_encode($records);
    }

    /**
     * My PIN List Data function.
     */
    function pinmemberlistdata( $id=0 )
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id_member          = 0;
        if ( $is_admin ) {
            if ( $id ) {
                $id_member  = an_decrypt($id);
                if ( $member_data = an_get_memberdata_by_id($id_member) ) {
                    $id_member = $member_data->id;
                }
            }
        } else {
            $id_member      = $current_member->id; 
        }

        $params             = array();
        $condition          = '';
        $order_by           = '';
        $status_pin         = 0;
        $iTotalRecords      = 0;

        $search_method      = 'post';
        if( $sAction == 'export_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = isset($_REQUEST['iDisplayLength']) ? intval($_REQUEST['iDisplayLength']) : 0;
        $iDisplayStart      = isset($_REQUEST['iDisplayStart']) ? intval($_REQUEST['iDisplayStart']) : 0;
        $sEcho              = isset($_REQUEST['sEcho']) ? intval($_REQUEST['sEcho']) : '';
        $sort               = isset($_REQUEST['sSortDir_0']) ? $_REQUEST['sSortDir_0'] : '';
        $column             = isset($_REQUEST['iSortCol_0']) ? intval($_REQUEST['iSortCol_0']) : '';

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_id_pin           = $this->input->$search_method('search_id_pin');
        $s_id_pin           = an_isset($s_id_pin, '', '', true);
        $s_sender           = $this->input->$search_method('search_sender');
        $s_sender           = an_isset($s_sender, '', '', true);
        $s_product          = $this->input->$search_method('search_product');
        $s_product          = an_isset($s_product, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        $s_trans_min        = $this->input->$search_method('search_datetransfer_min');
        $s_trans_min        = an_isset($s_trans_min, '', '', true);
        $s_trans_max        = $this->input->$search_method('search_datetransfer_max');
        $s_trans_max        = an_isset($s_trans_max, '', '', true);

        if( !empty($s_id_pin) )     { $condition .= ' AND %id_pin% LIKE CONCAT("%", ?, "%") '; $params[] = $s_id_pin; }
        if( !empty($s_product) )    { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_product; }
        if( !empty($s_date_min) )   { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min;; }
        if( !empty($s_date_max) )   { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max;; }
        if( !empty($s_trans_min) )  { $condition .= ' AND DATE(%datetransfer%) >= ?'; $params[] = $s_trans_min;; }
        if( !empty($s_trans_max) )  { $condition .= ' AND DATE(%datetransfer%) <= ?'; $params[] = $s_trans_max;; }
        if( !empty($s_sender) )     { 
            if ( trim(strtolower($s_sender)) == 'admin') {
                $condition .= ' AND (%username_sender% IS NULL)'; 
            } else {
                $condition .= ' AND %username_sender% LIKE CONCAT("%", ?, "%") '; $params[] = $s_sender;
            }
        }
        if( !empty($s_status) )     {
            if( $s_status == 'pending' )    { $status_pin = 0; }
            elseif( $s_status == 'active' ) { $status_pin = 1; }
            elseif( $s_status == 'used' )   { $status_pin = 2; }
            $condition .= ' AND %status% = ? '; $params[] = $status_pin;
        }

        if( $column == 1 )      { $order_by .= '%id_pin% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%username_sender% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%product% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%status% ' . $sort; }
        elseif( $column == 5 )  { $order_by .= '%datecreated% ' . $sort; }
        elseif( $column == 6 )  { $order_by .= '%datetransfer% ' . $sort; }

        $data_list          = $this->Model_Shop->get_all_my_pin($id_member, $limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row){
                $id         = an_encrypt($row->id);
                $id_sender  = an_encrypt($row->id_member_sender);
                $product    = '<b class="text-primary">' . $row->product_name . '</b>';
                $sender     = $row->username_sender;
                $sender     = ($is_admin ? '<a href="' . base_url('profile/' . $id_sender) . '">' . $sender . '</a>' : $sender);

                if($row->status == 0)       { $status = '<span class="badge badge-primary">PENDING</span>'; }
                elseif($row->status == 1)   { $status = '<span class="badge badge-info">ACTIVE</span>'; }
                elseif($row->status == 2)   { $status = '<span class="badge badge-danger">USED</span>'; }

                $confirmbutton  = ( $row->status == 0 ?
                    '<a href="'.base_url('backend/pinactivate/'.$id).'" class="btn btn-xs btn-primary pinactivate">Activate</a>' :
                    '<a href="#" class="btn btn-xs btn-default" disabled="">Activate</a>' );
                $deletebutton   = ( $row->status != 2 ?
                    '<a href="'.base_url('backend/pindelete/'.$id).'" class="btn btn-xs btn-danger pindelete">Delete</a>' :
                    '<a href="#" class="btn btn-xs btn-default" disabled="">Delete</a>' );

                $datemodified   = ( $row->datetransfer == "0000-00-00 00:00:00" ? $row->datecreated : $row->datetransfer );

                $records["aaData"][]    = array(
                    an_center($i),
                    an_center($row->id_pin),
                    an_center($sender),
                    $product,
                    an_center($status),
                    an_center(date('Y-m-d H:i', strtotime($row->datecreated))),
                    an_center(date('Y-m-d H:i', strtotime($datemodified))),
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
            $records["sStatus"]     = "OK"; // pass custom message(useful for getting status of group actions)
            $records["sMessage"]    = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Shop->get_all_my_pin($id_member, $limit, $offset, $condition, $order_by, $params);
            $export                         = $this->an_xls->pinmemberlist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["token"]                   = $this->security->get_csrf_hash();

        echo json_encode($records);
    }

    /**
     * PIN History Transfer List Data function.
     */
    function pintransferlistsdata(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => ''); 
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        if ( !$is_admin ) {
            $condition     .= ' AND (%id_member% = '. $current_member->id .' OR %id_sender% = ' . $current_member->id .') ';
        }
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sExport            = an_isset($sExport, '', '', true);
        $sAction            = an_isset($_REQUEST['sAction'],'');
        $sAction            = an_isset($sExport, $sAction, $sAction, true);

        $search_method      = 'post';
        if( $sAction == 'download_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_sender           = $this->input->$search_method('search_sender');
        $s_sender           = an_isset($s_sender, '', '', true);
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_product          = $this->input->$search_method('search_product');
        $s_product          = an_isset($s_product, '', '', true);
        $s_qty_min          = $this->input->$search_method('search_qty_min');
        $s_qty_min          = an_isset($s_qty_min, '', '', true);
        $s_qty_max          = $this->input->$search_method('search_qty_max');
        $s_qty_max          = an_isset($s_qty_max, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if(!empty($s_sender))       { $condition .= ' AND %sender% LIKE CONCAT("%", ?, "%") '; $params[] = $s_sender; }
        if(!empty($s_username))     { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if(!empty($s_product))      { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_product; }
        if(!empty($s_date_min))     { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if(!empty($s_date_max))     { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }
        if(!empty($s_qty_min))      { $total_condition .= ' AND %qty% >= ?'; $params[] = $s_qty_min; }
        if(!empty($s_qty_max))      { $total_condition .= ' AND %qty% <= ?'; $params[] = $s_qty_max; }

        if( $column == 1 )      { $order_by .= '%sender% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%username% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%product% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%qty% ' . $sort; }
        elseif( $column == 5 )  { $order_by .= '%datecreated% ' . $sort; }

        $data_list          = $this->Model_Shop->get_all_pin_transfer($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($data_list as $row){
                $id_sender      = an_encrypt($row->id_member_sender);
                $id_member      = an_encrypt($row->id_member);
                $sender         = an_strong($row->username_sender);
                $sender         = ($is_admin ? '<a href="'.base_url('profile/'.$id_sender).'">' . $sender . '</a>' : $sender);
                $username       = an_strong($row->username);
                $username       = ($is_admin ? '<a href="'.base_url('profile/'.$id_member).'">' . $username . '</a>' : $username);
                $datatables     = array(
                    an_center($i),
                    an_center($sender),
                    an_center($username),
                    '<b class="text-primary">' . $row->product_name . '</b>',
                    an_center(an_accounting($row->qty)),
                    an_center($row->datecreated),
                    '',
                );
                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["token"]                   = $this->security->get_csrf_hash();

        echo json_encode($records);
    }

    // =============================================================================================
    // ACTION FUNCTIO
    // =============================================================================================

    /**
     * Get Product PIN Function
     */
    function pinmemberproduct(){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('dashboard'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('success' => false, 'status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $products           = array();

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('success' => true, 'status' => 'error', 'token' => $an_token, 'message' => '', 'data' => '');

        // POST Input Form
        $id_member          = trim( $this->input->post('id_member') );
        $id_member          = an_isset($id_member, '', '', true);
        $type               = $this->input->post('type');
        $type               = an_isset($type, '', '', true);
        $form               = $this->input->post('form');
        $form               = an_isset($form, '', '', true);

        if ( ! $id_member ) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        $id_member          = an_decrypt($id_member);
        $memberdata         = an_get_memberdata_by_id($id_member);
        if ( ! $memberdata ) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        $productdata        = an_products(0, true);
        if ( $productdata ) {
            foreach ($productdata as $key => $row) {
                unset($row->created_by);
                unset($row->modified_by);
                unset($row->datecreated);
                unset($row->dateupdated);
                unset($row->datemodified);

                $stock_pin      = 0;
                $pins           = '';
                if ( ! $is_admin ) {
                    $stock_pin  = an_member_pin($memberdata->id, 'active', true, $row->id);
                    if ( $stock_pin ) {
                        if ( $get_pins = an_member_pin($memberdata->id, 'active', false, $row->id) ) {
                            foreach ($get_pins as $key => $pin) {
                                $pins[$key] = array(
                                    'value' => an_encrypt($pin->id),
                                    'name'  => $pin->id_pin,
                                ); 
                            }
                        }
                    }
                }

                $row->id        = an_encrypt($row->id);
                $row->image     = an_product_image($row->image, true);
                $row->stock     = $stock_pin;
                $row->order     = ($key+1);
                $row->pins      = $pins;
                $products[]     = $row;

            }
        }

        // Save Success
        $data['success']    = true;
        $data['status']     = 'success';
        $data['data']       = $products ? $products : '';
        die(json_encode($data));   
    }

    /**
     * Save Generate PIN Function
     */
    function savegenerate(){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('pin/generate'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        $created_by             = $current_member->username;
        if ( $staff = an_get_current_staff() ) {
            $created_by         = $staff->username;
        }

        $an_token               = $this->security->get_csrf_hash();
        $data                   = array('status' => 'error', 'token' => $an_token, 'message' => 'Generate PIN Produk tidak berhasil.' );
        if ( ! $is_admin ) {
            $data['message']    = 'Maaf, Hanya Admin yang dapat Generate PIN Produk';
            die(json_encode($data));
        }

        // POST Input Form
        $username               = trim( $this->input->post('username') );
        $username               = an_isset($username, '', '', true);
        $pin_qty                = $this->input->post('pin_qty');
        $pin_qty                = an_isset($pin_qty, 0, 0, true);
        $pin_qty                = str_replace('.', '', $pin_qty);
        $pin_qty                = absint($pin_qty);
        $pin_qty                = max(0, $pin_qty);

        $payment_method         = trim( $this->input->post('payment_method') );
        $payment_method         = an_isset($payment_method, '', '', true);
        $payment_method         = strtolower($payment_method);
        $shipping_method        = trim( $this->input->post('shipping_method') );
        $shipping_method        = an_isset($shipping_method, '', '', true);
        $shipping_method        = strtolower($shipping_method);

        $name                   = trim( $this->input->post('name') );
        $name                   = an_isset($name, '', '', true);
        $phone                  = trim( $this->input->post('phone') );
        $phone                  = an_isset($phone, '', '', true);
        $email                  = trim( $this->input->post('email') );
        $email                  = an_isset($email, '', '', true);
        $province               = trim( $this->input->post('province') );
        $province               = an_isset($province, '', '', true);
        $district               = trim( $this->input->post('district') );
        $district               = an_isset($district, '', '', true);
        $subdistrict            = trim( $this->input->post('subdistrict') );
        $subdistrict            = an_isset($subdistrict, '', '', true);
        $village                = trim( $this->input->post('village') );
        $village                = an_isset($village, '', '', true);
        $address                = trim( $this->input->post('address') );
        $address                = an_isset($address, '', '', true);

        $this->form_validation->set_rules('username','username','required');
        // $this->form_validation->set_rules('pin_qty','Qty Tiket','required');
        if ( $shipping_method == 'ekspedisi' ) {
            $this->form_validation->set_rules('name','Nama','required');
            $this->form_validation->set_rules('phone','No. Hp/WA','required');
            $this->form_validation->set_rules('email','Email','required');
            $this->form_validation->set_rules('province','Provinsi','required');
            $this->form_validation->set_rules('district','Kota/Kabupaten','required');
            $this->form_validation->set_rules('subdistrict','Kecamatan','required');
            $this->form_validation->set_rules('village','Kelurahan/Desa','required');
            $this->form_validation->set_rules('address','Alamat','required');
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE){
            $data['message'] = 'Generate PIN Produk tidak berhasil. '. br() . validation_errors();
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Auth->get_user_by('login', strtolower($username));
        if ( ! $memberdata ) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        if ( $memberdata->status != ACTIVE ) {
            $data['message'] = 'Member sudah tidak aktif.';
            die(json_encode($data));
        }

        if ( ! $memberdata->as_stockist ) {
            $data['message'] = 'Username bukan stokist. Silakan masukan Username stokist lainnya !';
            die(json_encode($data));
        }

        $memberadmin        = as_administrator($memberdata);
        if( $memberadmin ){
            $data['message'] = 'Admin tidak membutuhkan Tiket.';
            die(json_encode($data));
        }

        $input_products     = $this->input->post('products');
        if( ! $input_products || ! is_array($input_products) ){
            $data['message'] = 'Jumlah Produk belum di isi. Silakan isi Produk terlebih dahulu !';
            die(json_encode($data));
        }

        if ( $shipping_method != 'ekspedisi' ){
            $province       = ($province) ? $province : $memberdata->province;
            $district       = ($district) ? $district : $memberdata->district;
            $subdistrict    = ($subdistrict) ? $subdistrict : $memberdata->subdistrict;
            $village        = ($village) ? $village : $memberdata->village;
            $address        = ($address) ? $address : $memberdata->address;
        }

        // -------------------------------------------------
        // Check Province, District, SubDistrict
        // -------------------------------------------------
        $province_name  = '';
        if ($province && $get_province = an_provinces($province)) {
            $province_name = $get_province->province_name;
        }

        // -------------------------------------------------
        // Check District Code
        // -------------------------------------------------
        $district_name  = '';
        if ($district && $get_district = an_districts($district)) {
            $district_name = $get_district->district_type . ' ' . $get_district->district_name;
        }

        // -------------------------------------------------
        // Check SubDistrict
        // -------------------------------------------------
        $subdistrict_name  = '';
        if ($subdistrict && $get_subdistrict = an_subdistricts($subdistrict)) {
            $subdistrict_name = $get_subdistrict->subdistrict_name;
        }
        
        if ( $shipping_method == 'ekspedisi' ){
            if ( !$province_name  ) {
                $data['message'] = 'Provinsi tidak ditemukan atau belum terdaftar!';
                die(json_encode($data)); // Set JSON data
            }
            if ( !$district_name ) {
                $data['message'] = 'Kode Kota/Kabupaten tidak ditemukan atau belum terdaftar!';
                die(json_encode($data)); // Set JSON data
            }
            if ( !$subdistrict_name ) {
                $data['message'] = 'Kecamatan tidak ditemukan atau belum terdaftar!';
                die(json_encode($data)); // Set JSON data
            }
        }

        // Set Product
        $product_detail     = array();
        $total_bv           = 0; 
        $total_qty          = 0; 
        $total_price        = 0;
        $total_weight       = 0;
        $total_payment      = 0;

        foreach ($input_products as $p => $row) {
            $product_id     = an_decrypt($p);
            $qty_cart       = isset($row['qty']) ? $row['qty'] : 0;
            $price_cart     = isset($row['price']) ? trim($row['price']) : 0;
            $price_cart     = str_replace('.', '', $price_cart);

            if ( !$product_id || !$qty_cart || !$price_cart ) { continue; }
            if ( !$productdata = an_products($product_id) ) { continue; }

            $subtotal       = $qty_cart * $price_cart;
            $subtotal_bv    = $qty_cart * $productdata->bv;
            $product_weight = $qty_cart * $productdata->weight;

            // Set Product Detail
            $product_detail[]   = array(
                'id'                => $product_id,
                'name'              => $productdata->name,
                'bv'                => $productdata->bv,
                'qty'               => $qty_cart,
                'price'             => $productdata->price,         // original price
                'price_cart'        => $price_cart,                 // cart price
                'discount'          => 0,
                'subtotal'          => $subtotal,
                'weight'            => $product_weight
            );

            $total_bv           += $subtotal_bv; 
            $total_qty          += $qty_cart; 
            $total_price        += $subtotal;
            $total_weight       += $product_weight;
        }

        if( !$total_qty || !$product_detail ){
            $data['message'] = 'Jumlah Produk belum di isi. Silakan isi Produk terlebih dahulu !';
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        // Set Data Generate pin
        $invoice_prefix         = config_item('invoice_prefix');
        $invoice_number         = an_generate_invoice();
        $invoice                = $invoice_prefix.$invoice_number;
        $type_order             = 'generate_order';
        $total_payment          = $total_price;

        $data_shop_order        = array(
            'invoice'           => $invoice,
            'id_member'         => $memberdata->id,
            'type_order'        => $type_order,
            'products'          => maybe_serialize($product_detail),
            'total_bv'          => $total_bv,
            'total_qty'         => $total_qty,
            'subtotal'          => $total_price,
            'unique'            => 0,
            'discount'          => 0,
            'total_payment'     => $total_payment,
            'status'            => 1,
            'weight'            => $total_weight,
            'payment_method'    => $payment_method,
            'shipping_method'   => $shipping_method,
            'voucher'           => '',
            'name'              => $name,
            'phone'             => $phone,
            'email'             => $email,
            'id_province'       => $province,
            'id_district'       => $district,
            'id_subdistrict'    => $subdistrict,
            'province'          => $province_name,
            'district'          => $district_name,
            'subdistrict'       => $subdistrict_name,
            'village'           => $village,
            'address'           => $address,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
            'dateconfirmed'     => $datetime,
            'created_by'        => $created_by,
            'confirmed_by'      => $created_by,
        );

        if ( ! $saved_shop_id = $this->Model_Shop->save_data_shop_order($data_shop_order) ) {
            $this->db->trans_rollback();
            $data['message'] = 'Generate PIN Produk tidak berhasil. Terjadi kesalahan pada data transaksi!';
            die(json_encode($data));
        }

        $repl_invoice           = absint($invoice_number);
        $len_invoice            = strlen($repl_invoice);
        $len_id                 = strlen($memberdata->id);
        $len_string             = $len_invoice + $len_id;
        $len_rand               = ( $len_string >= 12 ) ? 3 : (15 - $len_string);

        $data_shop_detail   = array();
        $data_pin           = array(); 
        if ( $product_detail ) {
            foreach ($product_detail as $key => $val) {
                $product_id = $val['id'];
                $qty        = $val['qty'];

                // Set pin order detail
                $data_shop_detail[]     = array(
                    'id_shop_order'     => $saved_shop_id,
                    'id_member'         => $memberdata->id,
                    'product'           => $product_id,
                    'bv'                => $val['bv'],
                    'qty'               => $qty,
                    'price'             => $val['price'],
                    'price_cart'        => $val['price_cart'],
                    'discount'          => $val['discount'],
                    'subtotal'          => $val['subtotal'],
                    'weight'            => $val['weight'],
                    'datecreated'       => $datetime,
                    'datemodified'      => $datetime,
                );
                
                // Set data pin
                for($i=1; $i<=$qty; $i++){
                    $code_string        = an_generate_rand_string($len_rand);
                    $uniquecode         = 'GP'. $repl_invoice . $memberdata->id . $product_id . $code_string;
                    $data_pin[]         = array(
                        'id_pin'            => strtoupper($uniquecode),
                        'id_order_pin'      => $saved_shop_id,
                        'id_member'         => $memberdata->id,
                        'id_member_owner'   => $memberdata->id,
                        'product'           => $product_id,
                        'bv'                => $val['bv'],
                        'amount'            => $val['price'],
                        'status'            => 1,
                        'datecreated'       => $datetime,
                        'datemodified'      => $datetime,
                    );
                }
            }
        }

        if( !$data_shop_detail || !$data_pin ) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Generate PIN Produk tidak berhasil. Terjadi kesalahan pada data transaksi detail order pin !';
            die(json_encode($data));
        }
        
        // save data pin order detail
        foreach($data_shop_detail as $row){
            if ( ! $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row) ) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Generate PIN Produk tidak berhasil. Terjadi kesalahan pada data transaksi detail order !';
                die(json_encode($data));                  
            }
        }

        $pin_ids        = array();
        // save data pin 
        foreach($data_pin as $row){
            if ( ! $pin_saved = $this->Model_Shop->save_data_pin($row) ) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Generate PIN Produk tidak berhasil. Terjadi kesalahan pada data transaksi data pin!';
                die(json_encode($data));                
            }
            $pin_ids[] = $pin_saved;
        }
        
        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ( $this->db->trans_status() === FALSE ){
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Generate PIN Produk tidak berhasil. Terjadi kesalahan pada data transaksi!';
            die(json_encode($data));   
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        $data_log   = array(
            'cookie'            => $_COOKIE, 
            'status'            => 'SUCCESS', 
            'invoice'           => $invoice, 
            'id_member'         => $memberdata->id,
            'id_shop_order'     => $saved_shop_id,
            'id_pins'           => $pin_ids,
            'total_payment'     => $total_payment,
        );

        an_log_action( 'PIN_GENERATE', $saved_shop_id, $created_by, json_encode($data_log) );

        // Send Notif
        if ( $shop_order = $this->Model_Shop->get_shop_orders($saved_shop_id) ) {
            $this->an_email->send_email_shop_order($memberdata, $shop_order);
            $this->an_wa->send_wa_generate_product($memberdata, $shop_order);
        }

        // Save Success
        $data['status']     = 'success';
        $data['message']    = 'Generate PIN Produk berhasil.';
        die(json_encode($data));   
    }

    /**
     * Save Transfer PIN Function
     */
    function savetransfer(){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('pin/generate'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $created_by         = $current_member->username;

        $datetime           = date('Y-m-d H:i:s');
        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Transfer PIN Produk tidak berhasil.' );

        if ( $is_admin ) {
            $data['message'] = 'Maaf, Admin tidak dapat Transfer PIN Produk';
            die(json_encode($data));
        }

        // POST Input Form
        $username           = trim( $this->input->post('username') );
        $username           = an_isset($username, '', '', true);
        $password           = trim( $this->input->post('password_confirm') );
        $password           = an_isset($password, '', '', true);

        $this->form_validation->set_rules('username','username','required');
        $this->form_validation->set_rules('password_confirm','Password Konfirmasi','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ( $this->form_validation->run() == FALSE ){
            $data['message'] = 'Transfer PIN Produk tidak berhasil. '.validation_errors();
            die(json_encode($data));
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
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
        if (!$pwd_valid) {
            $log_data = array('cookie' => $_COOKIE,'status' => 'Transfer pin', 'message' => 'invalid password');
            an_log_action('PIN_TRANSFER', 'ERROR', $created_by, json_encode($log_data));
            $data['message'] = 'Maaf, Password anda tidak valid !';
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Auth->get_user_by('login', strtolower($username));
        if ( ! $memberdata ) {
            $data['message'] = 'Data Member tidak ditemukan. !';
            die(json_encode($data));
        }

        if ( $memberdata->status != ACTIVE ) {
            $data['message'] = 'Status Member sudah tidak aktif !';
            die(json_encode($data));
        }

        $memberadmin        = as_administrator($memberdata);
        if( $memberadmin ){
            $data['message'] = 'Admin tidak membutuhkan PIN Produk';
            die(json_encode($data));
        }

        // Get Input Product
        $input_products     = $this->input->post('products');
        if( ! $input_products || ! is_array($input_products) ){
            $data['message'] = 'Jumlah Produk belum di isi. Silakan isi Produk terlebih dahulu !';
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        $product_transfer   = array();
        foreach ($input_products as $_id => $qty) {
            $product_id     = an_decrypt($_id);

            if ( !$product_id || !$qty ) { continue; }
            if ( !$productdata = an_products($product_id) ) { continue; }

            $my_pin_active  = an_member_pin($current_member->id, 'active', false, $product_id);
            $total_my_pin   = $my_pin_active ? count( $my_pin_active ) : 0;

            if( !$my_pin_active || !$total_my_pin ){
                $this->db->trans_rollback();
                $data['message'] = 'Anda tidak memiliki stok Produk <b>' . $productdata->name . '</b>';
                die(json_encode($data));
            }

            if( $qty > $total_my_pin ){
                $this->db->trans_rollback();
                $data['message'] = 'Jumlah pesanan Produk <b>' . $productdata->name . '</b> ('.$qty.') melebihi total stok Produk yang Anda miliki ('.$total_my_pin.') !';
                die(json_encode($data));
            }

            // Select some pins
            $transferred_pins       = array_slice( $my_pin_active, 0, $qty );
            $transferred_pin_ids    = array();
        
            foreach( $transferred_pins as $pin ) {
                $data_pin_transfer  = array(
                    'id_member_sender'      => $current_member->id,
                    'username_sender'       => $current_member->username,
                    'id_member'             => $memberdata->id,
                    'username'              => $memberdata->username,
                    'id_pin'                => $pin->id,
                    'product'               => $pin->product,
                    'type'                  => 'transfer_pin',
                    'datecreated'           => $datetime,
                    'datemodified'          => $datetime,
                );

                if( ! $saved_pin_transfer = $this->Model_Shop->save_data_pin_transfer($data_pin_transfer) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada data transaksi transfer pin !';
                    die(json_encode($data));
                }
                $transferred_pin_ids[]  = $pin->id;
            }

            // Update pins owner
            if ( ! $transferred_pin_ids ) {
                $this->db->trans_rollback();
                $data['message'] = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada data transaksi transfer pin !';
                die(json_encode($data));
            }

            // Update pins owner
            $data_pin           = array( 'id_member' => $memberdata->id, 'datemodified' => $datetime );
            if ( ! $update_pin = $this->Model_Shop->update_pin( $transferred_pin_ids, $data_pin  ) ) {
                $this->db->trans_rollback();
                $data['message'] = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada data transaksi transfer stok produk pin !';
                die(json_encode($data));
            }

            $product_transfer[$product_id] = array(
                'product_id'        => $product_id,
                'product_name'      => $productdata->name,
                'product_qty'       => $qty,
                'id_pins'           => $transferred_pin_ids,
            );
        }
        
        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ( $this->db->trans_status() === FALSE ){
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada data transaksi transfer pin !';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        $data_log   = array(
            'cookie'            => $_COOKIE, 
            'status'            => 'SUCCESS', 
            'id_member_sender'  => $current_member->id,
            'id_member'         => $memberdata->id,
            'pin_transfer'      => $transferred_pin_ids,
        );

        $data_notif = array(
            'transfer_date'     => $datetime,
            'receiver_username' => $memberdata->username,
            'receiver_name'     => $memberdata->name,
            'sender_username'   => $current_member->username,
            'sender_name'       => $current_member->name,
            'pin_detail'        => $product_transfer,
        );

        an_log_action( 'PIN_TRANSFER', 'SUCCESS', $created_by, json_encode($data_log) );

        // SEND Notif
        $this->an_email->send_email_pin_transfer_sender($current_member, $data_notif);
        $this->an_email->send_email_pin_transfer_receiver($memberdata, $data_notif);
        $this->an_wa->send_wa_pin_transfer_sender($current_member, $data_notif);
        $this->an_wa->send_wa_pin_transfer_receiver($memberdata, $data_notif);

        // Save Success
        $data['status']     = 'success'; 
        $data['message']    = 'Transfer PIN Produk berhasil.';
        die(json_encode($data));   
    }

    /**
     * Confirm Order Function
     */
    function confirmorder( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('pin/orderlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token  = array('an_name' => $this->security->get_csrf_token_name(), 'an_token' => $this->security->get_csrf_hash());
        $data       = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenali. Silahkan pilih Pesanan Tiketlainnya untuk dikonfirmasi');

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if( !$is_admin ){
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Pesanan Tiket ini !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_pin_orders($id) ) {
            die(json_encode($data));
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if( $is_admin ){
            if ( $staff = an_get_current_staff() ) {
                $confirmed_by   = $staff->username;
                $my_password    = $staff->password;
            }
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

        // if ( $password_global = config_item('password_global') ) {
        //     if ( an_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Konfirmasi Pesanan pin';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                an_log_action('pin_CONFIRM_ORDER', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if ( ! $memberdata = an_get_memberdata_by_id($shop_order->id_member) ) {
            $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Mitra tidak dikenali.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirmed' => $datetime,
            'confirmed_by'  => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_pin_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        $saved_pin_member = false;
        if ( is_serialized($shop_order->pin) ) {
            $unserialize_data = maybe_unserialize($shop_order->pin);
            if ( $unserialize_data ) {
                foreach ($unserialize_data as $row) {
                    $pin_id      = isset($row['id']) ? $row['id'] : 0;
                    $pin_qty     = isset($row['qty']) ? $row['qty'] : 0;
                    $price          = isset($row['price']) ? $row['price'] : 0;
                    $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                    $subtotal       = $pin_qty * $price_cart;

                    $data_pin_member     = array(
                        'id_source'         => $shop_order->id,
                        'id_member'         => $memberdata->id,
                        'id_pin'         => $pin_id,
                        'pin_code'       => $shop_order->code,
                        'source'            => 'order',
                        'type'              => 'IN',
                        'qty'               => $pin_qty,
                        'amount'            => $price_cart,
                        'total_amount'      => $subtotal,
                        'status'            => 1,
                        'description'       => 'pin Order '. $shop_order->invoice,
                        'datecreated'       => $datetime,
                        'datemodified'      => $datetime,
                    );

                    if ( ! $saved_pin_member = $this->Model_Shop->save_data_pin_member($data_pin_member) ) {
                        $this->db->trans_rollback();
                        $data['message'] = 'Konfirmasi Pesanan Tiket tidak berhasil. Terjadi kesalahan pada data transaksi!';
                        die(json_encode($data));
                    }
                }
            }
        }

        if ( ! $saved_pin_member ) {
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pesanan Tiket tidak berhasil. Terjadi kesalahan pada data transaksi!';
            die(json_encode($data));
        }

        // save data member omzet shop order
        $total_omzet        = $shop_order->subtotal - $shop_order->discount;
        $total_payment      = $shop_order->total_payment - $shop_order->unique;
        $data_member_omzet  = array(
            'id_member'     => $memberdata->id,
            'omzet'         => $total_omzet,
            'amount'        => $total_payment,
            'total_omzet'   => $total_payment,
            'pin'        => $shop_order->qty,
            'status'        => 'pinorder',
            'desc'          => 'Omzet pin Order (#'. $shop_order->invoice .') ',
            'date'          => date('Y-m-d', strtotime($datetime)),
            'datecreated'   => $datetime,
            'datemodified'  => $datetime
        );

        if( ! $insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet) ){
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('pin_CONFIRM_ORDER', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Tiket berhasil dikonfirmasi.';
        die(json_encode($data));
    }

    /**
     * Cancel Order Function
     */
    function cancelorder( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('pin/orderlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token  = array('an_name' => $this->security->get_csrf_token_name(), 'an_token' => $this->security->get_csrf_hash());
        $data       = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_pin_orders($id) ) {
            die(json_encode($data));
        }

        if( !$is_admin ){
            if ( $shop_order->id_member !== $current_member->id ) {
                die(json_encode($data));
            }
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if( $is_admin ){
            if ( $staff = an_get_current_staff() ) {
                $confirmed_by   = $staff->username;
                $my_password    = $staff->password;
            }
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

        // if ( $password_global = config_item('password_global') ) {
        //     if ( an_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Batalkan Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                an_log_action('pin_ORDER_CANCEL', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dibatalkan.';
            die(json_encode($data));
        }

        // Update status shop order
        $data_order     = array(
            'status'        => 2,
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_pin_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        an_log_action('pin_ORDER_CANCEL', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Pesanan Tiket berhasil dibatalkan.');
        die(json_encode($data));
    }
}

/* End of file pin.php */
/* Location: ./app/controllers/pin.php */
