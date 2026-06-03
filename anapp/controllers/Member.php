<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Member Controller.
 *
 * @class     Member
 * @author    Yuda
 * @version   1.0.0
 */
class Member extends AN_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA MEMBER
    // =============================================================================================

    /**
     * Member List Data function.
     */
    function memberlistsdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = 'WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE;
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_sponsor          = $this->input->$search_method('search_sponsor');
        $s_sponsor          = an_isset($s_sponsor, '', '', true);
        $s_upline           = $this->input->$search_method('search_upline');
        $s_upline           = an_isset($s_upline, '', '', true);
        $s_position         = $this->input->$search_method('search_position');
        $s_position         = an_isset($s_position, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_package          = $this->input->$search_method('search_package');
        $s_package          = an_isset($s_package, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if ( !empty($s_username) )      { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )          { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_sponsor) )       { $condition .= ' AND %sponsor_username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_sponsor; }
        if ( !empty($s_upline) )        { $condition .= ' AND %upline_username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_upline; }
        if ( !empty($s_position) )      { $condition .= ' AND %position% >= ?'; $params[] = $s_position; }
        if ( !empty($s_date_min) )      { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )      { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }
        if ( !empty($s_status) )        {
            $condition .= ( strtolower($s_status) == 'member' ) ? ' AND %as_stockist% = 0' : ' AND %as_stockist% > 0';
        }

        if ($column == 1)       { $order_by .= '%username% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%name% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%sponsor_username% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%upline_username% ' . $sort; } 
        elseif ($column == 5)   { $order_by .= '%position% ' . $sort; } 
        elseif ($column == 6)   { $order_by .= '%as_stockist% ' . $sort; } 
        elseif ($column == 7)   { $order_by .= '%datecreated% ' . $sort; } 
        elseif ($column == 8)   { $order_by .= '%lastlogin% ' . $sort; }

        $member_list        = ($is_admin) ? $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by, $params) : '';
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $cfg_type       = config_item('member_type');
            $access         = TRUE;
            if ($staff = an_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS2) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }
            $i = $offset + 1;
            foreach ($member_list as $row) {
                $id             = an_encrypt($row->id);
                $id_sponsor     = an_encrypt($row->sponsor);
                $id_upline      = an_encrypt($row->parent);
                $username       = an_strong(strtolower($row->username));
                $username       = ($row->as_stockist >= 1 ? '<span class="text-success">' . $username . '</span>' : $username);
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = an_strong(strtoupper($row->name));

                $sponsor        = strtoupper($row->sponsor_username);
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;

                $upline         = strtoupper($row->upline_username);
                $upline         = ($is_admin) ? '<a href="' . base_url('profile/' . $id_upline) . '">' . $upline . '</a>' : $upline;

                if ( $row->type_status == TYPE_STATUS_DROPSHIPPER ) { $status   = '<span class="badge badge-success">'.strtoupper($row->type_status).'</span>'; }
                elseif ( $row->type_status == TYPE_STATUS_RESELLER ){ $status   = '<span class="badge badge-info">'.strtoupper($row->type_status).'</span>'; }

                $last_login     = '-';
                if ($row->last_login != '0000-00-00 00:00:00') {
                    $last_login     = date('d M y H:i', strtotime($row->last_login));
                }

                $banned         = '<a href="' . base_url('member/asbanned/' . $id) . '" data-container="registration_list" class="btn btn-sm  btn-danger btn-tooltip asbanned" title="Banned" style="margin:2px 0px"><i class="fa fa-trash"></i></a>';
                $assume         = '<a href="' . base_url('backend/assume/' . $id) . '" class="btn btn-sm btn-outline-warning btn-tooltip" title="Assume"><i class="fa fa-user"></i></a>';

                $btn_status     = '<a href="'.base_url('member/searchmemberdata/'.$id).'" class="btn btn-sm btn-outline-default btn-tooltip btn-as-status-member" title="Status Member"><i class="fa fa-user"></i></a>';
                $btn_gen        = '<a href="' . base_url('member/generation/' . strtolower($row->username)) . '" class="btn btn-sm btn-outline-primary btn-tooltip" title="Generation"><i class="fa fa-sitemap"></i></a>';
                $btn_tree       = '<a href="' . base_url('member/tree/' . $id) . '" class="btn btn-sm btn-outline-primary btn-tooltip" title="Jaringan Pohon"><i class="fa fa-sitemap"></i></a>';
                $btn_omzet      = '<a href="' . base_url('member/omzet/' . strtolower($row->username)) . '" class="btn btn-sm btn-outline-primary btn-tooltip" title="History Omzet"><i class="fa fa-chart-line"></i></a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($username),
                    $name,
                    an_center($sponsor),
                    an_center($upline),
                    an_center($status),
                    an_center(date('j M y @H:i', strtotime($row->datecreated))),
                    an_center($last_login),
                    an_center((($is_admin && $access) ? $btn_status . $btn_tree . $assume : ''))
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Sponsor List Data function.
     */
    function sponsorlistsdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = ' AND %status% = ' . ACTIVE;
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_total_min        = $this->input->$search_method('search_total_min');
        $s_total_min        = an_isset($s_total_min, '', '', true);
        $s_total_max        = $this->input->$search_method('search_total_max');
        $s_total_max        = an_isset($s_total_max, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if ( !empty($s_username) )      { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )          { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_total_min) )     { $condition .= ' AND %total% >= ?'; $params[] = $s_total_min; }
        if ( !empty($s_total_max) )     { $condition .= ' AND %total% <= ?'; $params[] = $s_total_max; }
        if ( !empty($s_date_min) )      { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )      { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }
        if ( !empty($s_status) )        {
            $condition .= ( strtolower($s_status) == 'member' ) ? ' AND %as_stockist% = 0' : ' AND %as_stockist% > 0';
        }

        if ($column == 1)       { $order_by .= '%username% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%name% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%as_stockist% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%total% ' . $sort; } 
        elseif ($column == 5)   { $order_by .= '%datecreated% ' . $sort; } 

        $member_list        = ($is_admin) ? $this->Model_Member->get_all_total_sponsored_data($limit, $offset, $condition, $order_by, $params) : '';
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $access         = TRUE;
            if ($staff = an_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS2) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }
            $i = $offset + 1;
            foreach ($member_list as $row) {
                $id             = an_encrypt($row->id);
                $username       = an_strong(strtolower($row->username));
                $username       = ($row->as_stockist >= 1 ? '<span class="text-success">' . $username . '</span>' : $username);
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = an_strong(strtoupper($row->name));

                $status         = '<span class="badge badge-default">Member</span>'; 
                if ( $row->as_stockist > 0 )   { 
                    $status     = '<span class="badge badge-success">Stockist</span>'; 
                }

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($username),
                    $name,
                    an_center($status),
                    an_accounting($row->total, '', true),
                    an_center(date('j M y @H:i', strtotime($row->datecreated))),
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

        echo json_encode($records);
    }

    /**
     * Board List Data function.
     */
    function boardlistsdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $params                 = array();
        $condition              = ($is_admin) ? '' : ' AND %id_member% = '. $current_member->id;
        $order_by               = '';
        $iTotalRecords          = 0;

        $sExport                = $this->input->get('export');
        $sAction                = an_isset($_REQUEST['sAction'], '');
        $sAction                = an_isset($sExport, $sAction);

        $search_method          = 'post';
        if ($sAction == 'download_excel') {
            $search_method      = 'get';
        }

        $iDisplayLength         = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart          = intval($_REQUEST['iDisplayStart']);
        $sEcho                  = intval($_REQUEST['sEcho']);
        $sort                   = $_REQUEST['sSortDir_0'];
        $column                 = intval($_REQUEST['iSortCol_0']);

        $limit                  = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset                 = $iDisplayStart;

        $s_username             = $this->input->$search_method('search_username');
        $s_username             = an_isset($s_username, '', '', true);
        $s_name                 = $this->input->$search_method('search_name');
        $s_name                 = an_isset($s_name, '', '', true);
        $s_code                 = $this->input->$search_method('search_code');
        $s_code                 = an_isset($s_code, '', '', true);
        $s_sponsor              = $this->input->$search_method('search_sponsor');
        $s_sponsor              = an_isset($s_sponsor, '', '', true);
        $s_board                = $this->input->$search_method('search_board');
        $s_board                = an_isset($s_board, '', '', true);
        $s_status               = $this->input->$search_method('search_status');
        $s_status               = an_isset($s_status, '', '', true);
        $s_package              = $this->input->$search_method('search_package');
        $s_package              = an_isset($s_package, '', '', true);
        $s_date_min             = $this->input->$search_method('search_datecreated_min');
        $s_date_min             = an_isset($s_date_min, '', '', true);
        $s_date_max             = $this->input->$search_method('search_datecreated_max');
        $s_date_max             = an_isset($s_date_max, '', '', true);
        $s_dateactived_min      = $this->input->$search_method('search_dateactived_min');
        $s_dateactived_min      = an_isset($s_dateactived_min, '', '', true);
        $s_dateactived_max      = $this->input->$search_method('search_dateactived_max');
        $s_dateactived_max      = an_isset($s_dateactived_max, '', '', true);
        $s_datequalified_min    = $this->input->$search_method('search_datequalified_min');
        $s_datequalified_min    = an_isset($s_datequalified_min, '', '', true);
        $s_datequalified_max    = $this->input->$search_method('search_datequalified_max');
        $s_datequalified_max    = an_isset($s_datequalified_max, '', '', true);

        if ( !empty($s_username) )          { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )              { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_code) )              { $condition .= ' AND %code% LIKE CONCAT("%", ?, "%")'; $params[] = $s_code; }
        if ( !empty($s_sponsor) )           { $condition .= ' AND %sponsor_username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_sponsor; }
        if ( !empty($s_board) )             { $condition .= ' AND %board% = ?'; $params[] = $s_board; }
        if ( !empty($s_date_min) )          { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )          { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }
        if ( !empty($s_dateactived_min) )   { $condition .= ' AND DATE(%dateactived%) >= ?'; $params[] = $s_dateactived_min; }
        if ( !empty($s_dateactived_max) )   { $condition .= ' AND DATE(%dateactived%) <= ?'; $params[] = $s_dateactived_max; }
        if ( !empty($s_datequalified_min) ) { $condition .= ' AND DATE(%datequalified%) >= ?'; $params[] = $s_datequalified_min; }
        if ( !empty($s_datequalified_max) ) { $condition .= ' AND DATE(%datequalified%) <= ?'; $params[] = $s_datequalified_max; }
        if ( !empty($s_status) )            {
            if ( strtolower($s_status) == 'pending' )   { $condition .= ' AND %status% = 0'; }
            if ( strtolower($s_status) == 'active' )    { $condition .= ' AND %status% = 1'; }
            if ( strtolower($s_status) == 'qualified' ) { $condition .= ' AND %status% = 2'; }
        }

        if ($column == 1)       { $order_by .= '%code% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= ( $is_admin ? '%username% ' : '%board% ' ) . $sort; } 
        elseif ($column == 3)   { $order_by .= ( $is_admin ? '%name% ' : '%status% ' ) . $sort; } 
        elseif ($column == 4)   { $order_by .= ( $is_admin ? '%board% ' : '%datecreated% ' ) . $sort; } 
        elseif ($column == 5)   { $order_by .= ( $is_admin ? '%sponsor% ' : '%dateactived% ' ) . $sort; } 
        elseif ($column == 6)   { $order_by .= ( $is_admin ? '%status% ' : '%datequalified% ' ) . $sort; } 
        elseif ($column == 7)   { $order_by .= '%datecreated% ' . $sort; } 
        elseif ($column == 8)   { $order_by .= '%dateactived% ' . $sort; }
        elseif ($column == 9)   { $order_by .= '%datequalified% ' . $sort; }

        $access         = TRUE;
        if ($staff = an_get_current_staff()) {
            if ($staff->access == 'partial') {
                $role   = array();
                if ($staff->role) {
                    $role = $staff->role;
                }

                foreach (array(STAFF_ACCESS3) as $val) {
                    if (empty($role) || !in_array($val, $role)){
                        $access = FALSE;
                    }
                }
            }
        }

        $member_list        = $access ? $this->Model_Member->get_all_member_board_data($limit, $offset, $condition, $order_by, $params) : '';
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $cfg_type       = config_item('member_type');
            $i = $offset + 1;
            foreach ($member_list as $row) {
                $id             = an_encrypt($row->id);
                $id_member      = an_encrypt($row->id_member);
                $id_sponsor     = an_encrypt($row->sponsor);
                $username       = an_strong(strtolower($row->username));
                $username       = ($row->status == 2 ? '<span class="text-success">' . $username . '</span>' : $username);
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = an_strong(strtoupper($row->name));

                $sponsor        = strtoupper($row->sponsor_username);
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;

                $code           = '<span class="text-uppercase text-primary"><b>'. $row->code .'</b></span>'; 

                $board          = '';
                $link_tree      = '';
                if ( $row->board == 1 ) { 
                    $board      = '<span class="badge badge-default">BOARD-1</span>'; 
                    $link_tree  = base_url('board/tree1/' . $id_member .'/'. $id);
                }
                if ( $row->board == 2 ) { 
                    $board      = '<span class="badge badge-info">BOARD-2</span>'; 
                    $link_tree  = base_url('board/tree2/' . $id_member .'/'. $id);
                }
                if ( $row->board == 3 ) { 
                    $board      = '<span class="badge badge-success">BOARD-3</span>'; 
                    $link_tree  = base_url('board/tree3/' . $id_member .'/'. $id);
                }

                $status         = '<span class="badge badge-default">PENDING</span>'; 
                if ( $row->status == 1 ) { $status = '<span class="badge badge-info">ACTIVE</span>'; }
                if ( $row->status == 2 ) { $status = '<span class="badge badge-success">QUALIFIED</span>'; }

                $dateactived    = '-';
                if ( $row->dateactived && $row->dateactived != '0000-00-00 00:00:00') {
                    $dateactived = date('d M y H:i', strtotime($row->dateactived));
                }

                $datequalified  = '-';
                if ( $row->datequalified && $row->datequalified != '0000-00-00 00:00:00') {
                    $datequalified = date('d M y H:i', strtotime($row->datequalified));
                }

                $link_title     = lang('menu_board_tree');
                $btn_tree       = '<a href="' . $link_tree . '" class="btn btn-sm btn-outline-primary btn-tooltip" title="'. $link_title .' '. $row->board .'">
                                    <i class="fa fa-sitemap"></i> '. $link_title .'
                                </a>';

                $datatables     = array(
                    an_center($i),
                    an_center($code),
                );

                if ( $is_admin ) {
                    $btn_tree       = ( $access ) ? $btn_tree : '';
                    $datatables[]   = an_center($username);
                    $datatables[]   = $name;
                    $datatables[]   = an_center($board);
                    $datatables[]   = an_center($sponsor);
                } else {
                    $btn_tree       = ( $current_member->id == $row->id_member ) ? $btn_tree : '';
                    $datatables[]   = an_center($board);
                }

                $datatables[]   = an_center($status);
                $datatables[]   = an_center(date('j M y @H:i', strtotime($row->datecreated)));
                $datatables[]   = an_center($dateactived);
                $datatables[]   = an_center($datequalified);
                $datatables[]   = an_center($btn_tree);

                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Register List Data function.
     */
    function registerlistdata()
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = 'WHERE %type% = ' . MEMBER;
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

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

        $s_member           = $this->input->$search_method('search_member');
        $s_member           = an_isset($s_member, '', '', true);
        $s_sponsor          = $this->input->$search_method('search_sponsor');
        $s_sponsor          = an_isset($s_sponsor, '', '', true);
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_wa               = $this->input->$search_method('search_wa');
        $s_wa               = an_isset($s_wa, '', '', true);
        $s_email            = $this->input->$search_method('search_email');
        $s_email            = an_isset($s_email, '', '', true);
        $s_omzet_min        = $this->input->$search_method('search_omzet_min');
        $s_omzet_min        = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max        = $this->input->$search_method('search_omzet_max');
        $s_omzet_max        = an_isset($s_omzet_max, '', '', true);
        $s_access           = $this->input->$search_method('search_access');
        $s_access           = an_isset($s_access, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirmconfirm_min');
        $s_dateconfirm_min  = an_isset($s_dateconfirm_min, '', '', true);
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirmconfirm_max');
        $s_dateconfirm_max  = an_isset($s_dateconfirm_max, '', '', true);

        if ( !empty($s_member) )    { $condition .= ' AND %member% LIKE CONCAT("%", ?, "%")'; $params[] = $s_member; }
        if ( !empty($s_sponsor) )   { $condition .= ' AND %sponsor% LIKE CONCAT("%", ?, "%")'; $params[] = $s_sponsor; }
        if ( !empty($s_username) )  { $condition .= ' AND %downline% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )      { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_wa) )        { $condition .= ' AND %wa% LIKE CONCAT("%", ?, "%")'; $params[] = $s_wa; }
        if ( !empty($s_email) )     { $condition .= ' AND %email% LIKE CONCAT("%", ?, "%")'; $params[] = $s_email; }
        if ( !empty($s_access) )    { $condition .= ' AND %access% = ?'; $params[] = $s_access; }
        if ( !empty($s_omzet_min) ) { $condition .= ' AND %nominal% >= ?'; $params[] = $s_omzet_min; }
        if ( !empty($s_omzet_max) ) { $condition .= ' AND %nominal% <= ?'; $params[] = $s_omzet_max; }
        if ( !empty($s_date_min) )  { $condition .= ' AND %datecreated% >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )  { $condition .= ' AND %datecreated% <= ?'; $params[] = $s_date_max; }

        if ( !empty($s_dateconfirm_min) )   { $condition .= ' AND %dateconfirm% >= ?'; $params[] = $s_dateconfirm_min; }
        if ( !empty($s_dateconfirm_max) )   { $condition .= ' AND %dateconfirm% <= ?'; $params[] = $s_dateconfirm_max; }
        if (!empty($s_status)) {
            if ($s_status == 'cancelled')   { $condition .= ' AND %status% = 2'; }
            if ($s_status == 'confirmed')   { $condition .= ' AND %status% = 1'; }
            if ($s_status == 'pending')     { $condition .= ' AND %status% = 0'; }
        }

        if ($is_admin) {
            if ($column == 1)       { $order_by .= '%member% ' . $sort; } 
            elseif ($column == 2)   { $order_by .= '%sponsor% ' . $sort; } 
            elseif ($column == 3)   { $order_by .= '%downline% ' . $sort; } 
            elseif ($column == 4)   { $order_by .= '%name% ' . $sort; } 
            elseif ($column == 5)   { $order_by .= '%wa% ' . $sort; } 
            elseif ($column == 6)   { $order_by .= '%email% ' . $sort; } 
            elseif ($column == 7)   { $order_by .= '%status% ' . $sort; } 
            elseif ($column == 8)   { $order_by .= '%access% ' . $sort; } 
            elseif ($column == 9)   { $order_by .= '%datecreated% ' . $sort; } 
            elseif ($column == 10)   {
                $order_by .= '%dateconfirm% ' . $sort;
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
        } else {
            if ($column == 1)       { $order_by .= '%member% ' . $sort; }
            elseif ($column == 2)   { $order_by .= '%sponsor% ' . $sort; }
            elseif ($column == 3)   { $order_by .= '%downline% ' . $sort; }
            elseif ($column == 4)   { $order_by .= '%name% ' . $sort; }
            elseif ($column == 5)   { $order_by .= '%wa% ' . $sort; }
            elseif ($column == 6)   { $order_by .= '%email% ' . $sort; }
            elseif ($column == 7)   { $order_by .= '%status% ' . $sort; }
            elseif ($column == 8)   { $order_by .= '%datecreated% ' . $sort; } 
            elseif ($column == 9)   {
                $order_by .= '%dateconfirm% ' . $sort;
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
        }

        $data_list          = $this->Model_Member->get_all_member_confirm($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $id_member      = an_encrypt($row->id_member);
                $id_sponsor     = an_encrypt($row->id_sponsor);
                $id_downline    = an_encrypt($row->id_downline);
                $member         = strtoupper($row->member);
                $member         = ($is_admin) ? '<a href="' . base_url('profile/' . $id_member) . '">' . $member . '</a>' : $member;
                $sponsor        = strtoupper($row->sponsor);
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;
                $downline       = an_strong($row->downline);
                $downline       = ($is_admin ? '<a href="' . base_url('profile/' . $id_downline) . '">' . $downline . '</a>' : $downline);
                $name           = an_strong(strtoupper($row->name));
                $wa             = preg_replace('/^0?/', '62', $row->phone);
                $wa_btn         = '<a href="https://wa.me/'.$wa.'?text=Halo kami dari Alphanet, segera lakukan pendaftaran keanggotaan Anda. Terimakasih." class="btn btn-sm btn-success" target="_blank"><i class="fab fa-whatsapp my-float"></i> Whatsapp</a>';
                $email          = '<a href="mailto:'.$row->email.'">'.$row->email.'</a>';

                $status = '';
                if ($row->status == 0) {
                    $status = '<span class="badge badge-default">PENDING</span>';
                } elseif ($row->status == 1) {
                    $status = '<span class="badge badge-success">CONFIRMED</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-danger">CANCELLED</span>';
                }

                $datatable = array(
                    an_center($i),
                    an_center($member),
                    an_center($sponsor),
                    an_center($downline),
                    $name,
                    an_center($wa . br() . $wa_btn),
                    an_center($email),
                    an_center($status),
                );

                $btn_confirm        = '';
                if ($is_admin) {
                    $access         = '';
                    if ($row->access == 'admin') {
                        $access = '<span class="badge badge-success">ADMIN</span>';
                    }
                    if ($row->access == 'member') {
                        $access = '<span class="badge badge-primary">MEMBER</span>';
                    }
                    if ($row->access == 'shop') {
                        $access = '<span class="badge badge-warning">SHOP</span>';
                    }
                    $datatable[]    = an_center($access);

                    if ($row->status == 0) {
                        if( $row->access == 'member' ){
                            $btn_confirm = '<a href="javascript:;" 
                                            data-url="' . base_url('member/memberconfirm/' . $id) . '" 
                                            data-username="' . $row->downline . '"
                                            data-name="' . $row->name . '"
                                            data-nominal="' . an_accounting($row->nominal) . '"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-member-confirm" 
                                            title="Konfirmasi Pendaftaran"><i class="fa fa-check"></i> Confirm</a>';
                        }else{
                            $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-warning btn-tooltip btn-disabled" title="Pending" disabled=""><i class="fa fa-check"></i> Confirm</a>';
                        }
                    } else if ($row->status == 1) {
                        $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-success btn-tooltip" title="Confirmed" disabled=""><i class="fa fa-check"></i></a>';
                    } else if ($row->status == 2) {
                        $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-danger btn-tooltip" title="Cancelled" disabled=""><i class="fa fa-times"></i></a>';
                    }
                }

                $dateconfirm = ($row->status == 1) ? date('Y-m-d @H:i', strtotime($row->datemodified))  : '-';

                $datatable[] = an_center(date('Y-m-d @H:i', strtotime($row->datecreated)));
                $datatable[] = an_center($dateconfirm);
                $datatable[] = an_center($btn_confirm);

                $records["aaData"][] = $datatable;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Member->get_all_member_confirm(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->registerlist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * RO List Data function.
     */
    function rolistdata()
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        if (!$is_admin) {
            $condition     .= ' AND ( %id_member% = ' . $current_member->id .' OR %id_activator% = ' . $current_member->id .' )';
        }
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

        $s_invoice          = $this->input->$search_method('search_invoice');
        $s_invoice          = an_isset($s_invoice, '', '', true);
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        
        if ( !empty($s_invoice) )   { $condition .= ' AND %invoice% LIKE CONCAT("%", ?, "%")'; $params[] = $s_invoice; }
        if ( !empty($s_username) )  { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )      { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_date_min) )  { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )  { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }

        if ($column == 1)       { $order_by .= '%datecreated% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%invoice% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%username% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%name% ' . $sort; } 

        $data_list          = $this->Model_Member->get_all_member_ro($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $id_member      = an_encrypt($row->id_member);
                $username       = an_strong(strtoupper($row->username));
                $username       = ($is_admin) ? '<a href="' . base_url('profile/' . $id_member) . '">' . $username . '</a>' : $username;
                $name           = strtoupper($row->name);
                $invoice        = $row->invoice;

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($row->datecreated),
                    an_center($invoice),
                    an_center($username),
                    $name,
                    '',
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Member->get_all_member_ro(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->rolist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Member Loan List Data function.
     */
    function memberloanlistsdata( $id=0 )
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id_member          = ( !$is_admin ) ? $current_member->id : 0;
        if ( $is_admin && $id ) {
            $id_member      = an_decrypt($id);
        }

        $params             = $id_member ? array($id_member) : array();
        $condition          = $id_member ? ' AND %id_member% = ?' : '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_type             = $this->input->$search_method('search_type');
        $s_type             = an_isset($s_type, '', '', true);
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = an_isset($s_desc, '');
        $s_total_min        = $this->input->$search_method('search_total_min');
        $s_total_min        = an_isset($s_total_min, '', '', true);
        $s_total_max        = $this->input->$search_method('search_total_max');
        $s_total_max        = an_isset($s_total_max, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        
        if ( !empty($s_username) )  { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )      { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_desc) )      { $condition .= ' AND %description% LIKE CONCAT("%", ?, "%")'; $params[] = $s_desc; }
        if ( !empty($s_type) )      { $condition .= ' AND %type% = ?'; $params[] = $s_type; }
        if ( !empty($s_total_min) ) { $condition .= ' AND %amount% >= ?'; $params[] = $s_total_min; }
        if ( !empty($s_total_max) ) { $condition .= ' AND %amount% <= ?'; $params[] = $s_total_max; }
        if ( !empty($s_date_min) )  { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )  { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }

        if ( $id_member ) {
            if ($column == 1)       { $order_by .= '%datecreated% ' . $sort; } 
            elseif ($column == 2)   { $order_by .= '%type% ' . $sort; } 
            elseif ($column == 3)   { $order_by .= '%amount% ' . $sort; }  
            elseif ($column == 4)   { $order_by .= '%description% ' . $sort; }  
        } else {
            if ($column == 1)       { $order_by .= '%datecreated% ' . $sort; } 
            elseif ($column == 2)   { $order_by .= '%username% ' . $sort; } 
            elseif ($column == 3)   { $order_by .= '%name% ' . $sort; }  
            elseif ($column == 4)   { $order_by .= '%type% ' . $sort; }  
            elseif ($column == 5)   { $order_by .= '%amount% ' . $sort; }  
        }

        $data_list          = $this->Model_Member->get_all_member_loan($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $username       = an_strong(strtoupper($row->username));
                $username       = ($is_admin) ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username;
                $name           = strtoupper($row->name);

                $type_loan      = strtoupper($row->type); 
                if ( $row->type == 'deposite' )   { 
                    $type_loan  = '<span class="badge badge-success">'.strtoupper($row->type).'</span>'; 
                }
                if ( $row->type == 'withdraw' )   { 
                    $type_loan  = '<span class="badge badge-warning">'.strtoupper($row->type).'</span>'; 
                }

                $datatables     = array(
                    an_center($i),
                    an_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                );

                if ( $is_admin && !$id_member ) {
                    $datatables[] = an_center($username);
                    $datatables[] = $name;
                }

                $datatables[] = an_center($type_loan);
                $datatables[] = an_accounting($row->amount, '', TRUE);

                if ( $id_member ) {
                    $datatables[] = $row->description;
                }

                $datatables[] = '';

                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Member Deposite Loan List Data function.
     */
    function memberloandepositelistsdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

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
        
        if ( !empty($s_username) )  { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%")'; $params[] = $s_username; }
        if ( !empty($s_name) )      { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_total_min) ) { $total_condition .= ' AND %total% >= ?'; $params[] = $s_total_min; }
        if ( !empty($s_total_max) ) { $total_condition .= ' AND %total% <= ?'; $params[] = $s_total_max; }

        if ($column == 1)       { $order_by .= '%username% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%name% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%total% ' . $sort; }  

        $data_list          = $this->Model_Member->get_all_member_deposite_loan($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $username       = an_strong(strtoupper($row->username));
                $username       = ($is_admin) ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username;
                $name           = strtoupper($row->name);

                $btn_detail     = '<a href="' . base_url('member/loan/'.$id) . '" class="btn btn-sm btn-outline-primary">Detail</a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($username),
                    $name,
                    an_accounting($row->total_loan, '', TRUE),
                    an_center($btn_detail)
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Generation Member List Data function.
     */
    function generationdata($username = '')
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $memberdata         = $current_member;

        if ($is_admin && $username) {
            $username       = trim(strtolower($username));
            if ($getmember = $this->Model_Member->get_member_by('login', $username)) {
                $memberdata = $getmember;
            }
        }

        $my_gen             = $memberdata->gen;
        $condition          = ' WHERE %type% = ' . MEMBER . ' AND %tree% LIKE "' . $memberdata->tree . '-%"';
        $order_by           = '%gen% ASC, %username% ASC';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_sponsor          = $this->input->post('search_sponsor');
        $s_sponsor          = an_isset($s_sponsor, '', '', true);
        $s_package          = $this->input->post('search_package');
        $s_package          = an_isset($s_package, '', '', true);
        $s_generation       = $this->input->post('search_generation');
        $s_generation       = an_isset($s_generation, '', '', true);

        if (!empty($s_name))        { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if (!empty($s_username))    { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if (!empty($s_sponsor))     { $condition .= str_replace('%s%', $s_sponsor, ' AND %sponsor_username% LIKE "%%s%%"'); }
        if (!empty($s_package))     { $condition .= str_replace('%s%', $s_package, ' AND %package% LIKE "%s%"'); }
        if (!empty($s_generation))  { $condition .= str_replace('%s%', ($s_generation + $my_gen), ' AND %gen% = %s%'); }

        if ($column == 1)       { $order_by = '%username% ' . $sort; } 
        elseif ($column == 2)   { $order_by = '%name% ' . $sort; } 
        elseif ($column == 3)   { $order_by = '%package% ' . $sort; } 
        elseif ($column == 4)   { $order_by = '%sponsor_username% ' . $sort; } 
        elseif ($column == 5)   { $order_by = '%gen% ' . $sort; }

        $data_list          = $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $cfg_package    = config_item('package');
            $cond_grade     = array('year' => date('Y'), 'month' => date('n'));
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $id_sponsor     = an_encrypt($row->sponsor);
                $username       = an_strong(strtoupper($row->username));
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = an_strong(strtoupper($row->name));
                $sponsor        = an_strong(strtoupper($row->sponsor_username)) . ' <small>(' . strtoupper($row->sponsor_name) . ')</small>';
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;

                $package_name   = isset($cfg_package[$row->package]) ? $cfg_package[$row->package] : $row->package;
                $package_class  = 'badge-primary';
                if ($row->package == JUNIOR_MANAGER) {
                    $package_class = 'badge-success';
                }
                if ($row->package == SENIOR_MANAGER) {
                    $package_class = 'badge-info';
                }
                if ($row->package == GENERAL_MANAGER) {
                    $package_class = 'badge-default';
                }
                $package        = '<span class="badge ' . $package_class . '">' . $package_name . '</span>';

                $gen            = $row->gen - $my_gen;
                $member_gen     = '<button class="btn btn-sm btn-outline-primary">Gen-' . $gen . '</button>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($username),
                    $name,
                    an_center($package),
                    $sponsor,
                    an_center($member_gen),
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

    /**
     * Stockist List Data function.
     */
    function stockistlistsdata($type = ''){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth               = auth_redirect( true );
        $type               = !empty($type) ? an_decrypt($type) : '';
        $params             = array();
        $condition          = 'AND %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE . ' AND %stockist% >= 1';
        $order_by           = '';

        if ( $auth ) {
            $current_member = an_get_current_member();
            $condition      = 'AND  %stockist% > ?'; $params[] = $current_member->as_stockist;
        }

        $sExport            = $this->input->get('export');
        $sExport            = an_isset($sExport, '', '', true);
        $sAction            = an_isset($_REQUEST['sAction'], '', true);
        $sAction            = an_isset($sExport, $sAction, '', true);

        $search_method      = 'post';
        if( $sAction == 'download_excel' ){
            $search_method  = 'get';
        }

        $iTotalRecords      = 0;
        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_phone            = $this->input->$search_method('search_phone');
        $s_phone            = an_isset($s_phone, '', '', true);
        $s_province         = $this->input->$search_method('search_province');
        $s_province         = an_isset($s_province, '', '', true);
        $s_district         = $this->input->$search_method('search_district');
        $s_district         = an_isset($s_district, '', '', true);
        $s_subdistrict      = $this->input->$search_method('search_subdistrict');
        $s_subdistrict      = an_isset($s_subdistrict, '', '', true);
        $s_province_id      = $this->input->$search_method('search_province_id');
        $s_province_id      = an_isset($s_province_id, '', '', true);
        $s_district_id      = $this->input->$search_method('search_district_id');
        $s_district_id      = an_isset($s_district_id, '', '', true);
        $s_subdistrict_id   = $this->input->$search_method('search_subdistrict_id');
        $s_subdistrict_id   = an_isset($s_subdistrict_id, '', '', true);
        $s_address          = $this->input->$search_method('search_address');
        $s_address          = an_isset($s_address, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);

        if ( !empty($s_username) )      { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if ( !empty($s_name) )          { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if ( !empty($s_phone) )         { $condition .= ' AND %phone% LIKE CONCAT("%", ?, "%")'; $params[] = $s_phone; }
        if ( !empty($s_province) )      { $condition .= ' AND %province% LIKE CONCAT("%", ?, "%") '; $params[] = $s_province; }
        if ( !empty($s_district) )      { $condition .= ' AND %district% LIKE CONCAT("%", ?, "%") '; $params[] = $s_district; }
        if ( !empty($s_subdistrict) )   { $condition .= ' AND %subdistrict% LIKE CONCAT("%", ?, "%") '; $params[] = $s_subdistrict; }
        if ( !empty($s_address) )       { $condition .= ' AND %address% LIKE CONCAT("%", ?, "%") '; $params[] = $s_address; }
        if ( !empty($s_province_id) )   { $condition .= ' AND %province_id% = ? '; $params[] = $s_province_id; }
        if ( !empty($s_district_id) )   { $condition .= ' AND %district_id% = ? '; $params[] = $s_district_id; }
        if ( !empty($s_subdistrict_id) ){ $condition .= ' AND %subdistrict_id% = ? '; $params[] = $s_subdistrict_id; }
        if ( !empty($s_status) )        { 
            if ( strtolower($s_status) == 'stockist' )  { $condition .= ' AND %stockist% >= 1'; }
        }

        if ( $type == 'find_agent' ) {
            if( $column == 1 )      { $order_by .= '%username% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= '%name% ' . $sort; }
            elseif( $column == 3 )  { $order_by .= '%phone% ' . $sort; }
            elseif( $column == 4 )  { $order_by .= '%province% '. $sort .', %district% '. $sort .', %subdistrict% ' . $sort .', %address% ' . $sort; }
            elseif( $column == 5 )  { $order_by .= '%stockist% ' . $sort; }
        } else {
            if( $column == 1 )      { $order_by .= '%name% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= '%phone% ' . $sort; }
            elseif( $column == 3 )  { $order_by .= '%province% ' . $sort; }
            elseif( $column == 4 )  { $order_by .= '%district% ' . $sort; }
            elseif( $column == 5 )  { $order_by .= '%subdistrict% ' . $sort; }
            elseif( $column == 6 )  { $order_by .= '%stockist% ' . $sort; }
        }

        $member_list        = $this->Model_Member->get_all_stockist_address($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($member_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $cfg_type       = config_item('member_status');
            $i = $offset + 1;
            foreach($member_list as $row){
                $id                 = an_encrypt($row->id);
                $username           = an_strong(strtolower($row->username));
                $username           = ( $row->as_stockist >= 2 ? '<span class="text-primary">'.$username. '</span>' : $username );
                $name               = ucwords(strtolower($row->name));
                $type_member        = isset($cfg_type[$row->as_stockist]) ? $cfg_type[$row->as_stockist] : 'Member';


                $province_name      = ucwords(strtolower($row->province_name));
                $province_name      = str_replace('Dki ', 'DKI ', $province_name);
                $province_name      = str_replace('Di ', 'DI ', $province_name);
                $district_name      = ucwords(strtolower($row->district_type) .' '. strtolower($row->district_name));
                $subdistrict_name   = ucwords(strtolower($row->subdistrict_name));
                $village_name       = ucwords(strtolower($row->village));

                $datatables         = array(an_center($i));
                if ( $type == 'find_agent' ) {
                    $datatables[]   = an_center($username);
                }
                $datatables[]       = $name;
                $datatables[]       = an_center($row->phone);

                if ( $type == 'find_agent' && $auth ) {
                    $address        = $row->address .', '. $village_name .br();
                    $address       .= 'Kec. '. $subdistrict_name .' - '. $district_name .br();
                    $address       .= $province_name;
                    $btn_status     = '<a href="'.base_url('shopping/selectagent/'.$id).'" class="btn btn-sm btn-outline-default btn-tooltip" title="Pilih Sub/Agency"><i class="fa fa-check"></i> Pilih</a>';

                    $datatables[]   = $address;
                    $datatables[]   = an_center($btn_status);
                } else {
                    $datatables[]   = $province_name;
                    $datatables[]   = $district_name;
                    $datatables[]   = $subdistrict_name;
                    $datatables[]   = $row->address;
                    $datatables[]   = an_center($type_member);
                    $datatables[]   = '';
                }

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

    /**
     * Member Omzet List Data function.
     */
    function memberomzetlistsdata($id = 0)
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $member_data        = '';
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id_member          = $current_member->id;
        if ($is_admin && $id) {
            $id_member      = an_decrypt($id);
            if ($member_data = an_get_memberdata_by_id($id_member)) {
                $id_member = $member_data->id;
            }
        }

        $condition          = ' AND %id_member% = ' . $id_member;
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sAction            = an_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        $s_package          = $this->input->$search_method('search_package');
        $s_package          = an_isset($s_package, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_omzet_min        = $this->input->$search_method('search_omzet_min');
        $s_omzet_min        = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max        = $this->input->$search_method('search_omzet_max');
        $s_omzet_max        = an_isset($s_omzet_max, '', '', true);
        $s_omzet_group_min  = $this->input->$search_method('search_omzet_group_min');
        $s_omzet_group_min  = an_isset($s_omzet_group_min, '', '', true);
        $s_omzet_group_max  = $this->input->$search_method('search_omzet_group_max');
        $s_omzet_group_max  = an_isset($s_omzet_group_max, '', '', true);
        $s_omzet_activ_min  = $this->input->$search_method('search_group_active_min');
        $s_omzet_activ_min  = an_isset($s_omzet_activ_min, '', '', true);
        $s_omzet_activ_max  = $this->input->$search_method('search_group_active_max');
        $s_omzet_activ_max  = an_isset($s_omzet_activ_max, '', '', true);
        $s_period_min       = $this->input->$search_method('search_period_min');
        $s_period_min       = an_isset($s_period_min, '', '', true);
        $s_period_max       = $this->input->$search_method('search_period_max');
        $s_period_max       = an_isset($s_period_max, '', '', true);

        if (!empty($s_package)) {
            $condition .= str_replace('%s%', $s_package, ' AND %package% LIKE "%s%"');
        }
        if (!empty($s_omzet_min)) {
            $condition .= ' AND %total_pv% >= "' . $s_omzet_min . '"';
        }
        if (!empty($s_omzet_max)) {
            $condition .= ' AND %total_pv% <= "' . $s_omzet_max . '"';
        }
        if (!empty($s_omzet_group_min)) {
            $condition .= ' AND %total_pv_group% >= "' . $s_omzet_group_min . '"';
        }
        if (!empty($s_omzet_group_max)) {
            $condition .= ' AND %total_pv_group% <= "' . $s_omzet_group_max . '"';
        }
        if (!empty($s_omzet_activ_min)) {
            $condition .= ' AND %group_active% >= "' . $s_omzet_activ_min . '"';
        }
        if (!empty($s_omzet_activ_max)) {
            $condition .= ' AND %group_active% <= "' . $s_omzet_activ_max . '"';
        }
        if (!empty($s_period_min)) {
            $condition .= ' AND %count_qualified% >= "' . $s_period_min . '"';
        }
        if (!empty($s_period_max)) {
            $condition .= ' AND %count_qualified% <= "' . $s_period_max . '"';
        }
        if (!empty($s_date_min)) {
            $s_year_min     = date('Y', strtotime($s_date_min));
            $s_month_min    = date('n', strtotime($s_date_min));
            $condition     .= ' AND %year% >= "' . $s_year_min . '" AND %month% >= "' . $s_month_min . '"';
        }
        if (!empty($s_date_max)) {
            $s_year_max     = date('Y', strtotime($s_date_max));
            $s_month_max    = date('n', strtotime($s_date_max));
            $condition     .= ' AND %year% <= "' . $s_year_max . '" AND %month% <= "' . $s_month_max . '"';
        }
        if (!empty($s_status)) {
            if ($s_status == 'qualified') {
                $condition .= str_replace('%s%', 1, ' AND %qualified% >= %s%');
            } else {
                $condition .= str_replace('%s%', 0, ' AND %qualified% = %s%');
            }
        }

        if ($column == 1) {
            $order_by .= '%year% ' . $sort . ' %month% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%package% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%total_pv% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%total_pv_group% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%group_active% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%qualified% ' . $sort;
        }

        $member_list        = $this->Model_Member->get_all_member_grade($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $cfg_package    = config_item('package');
            $cfg_month      = config_item('month');
            $access         = TRUE;
            if ($staff = an_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS4) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }
            $i = $offset + 1;
            foreach ($member_list as $row) {
                $month_grade    = isset($cfg_month[$row->month]) ? $cfg_month[$row->month] . ', ' : $row->month . ' - ';
                $period_grade   = $month_grade . $row->year;
                $package_name   = isset($cfg_package[$row->package]) ? $cfg_package[$row->package] : $row->package;
                $package_class  = 'badge-primary';
                if ($row->package == JUNIOR_MANAGER) {
                    $package_class = 'badge-success';
                }
                if ($row->package == SENIOR_MANAGER) {
                    $package_class = 'badge-info';
                }
                if ($row->package == GENERAL_MANAGER) {
                    $package_class = 'badge-default';
                }
                $package        = '<span class="badge ' . $package_class . '">' . $package_name . '</span>';

                $status         = '<span class="badge badge-danger">NOT QUALIFIED</span>';
                if ($row->qualified >= 1) {
                    $status     = '<span class="badge badge-success">QUALIFIED</span>';
                }

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($period_grade),
                    an_center($package),
                    an_accounting($row->total_pv, '', true),
                    an_accounting($row->total_pv_group, '', true),
                    an_accounting($row->group_active, '', true),
                    an_center($status),
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

        echo json_encode($records);
    }

    /**
     * Omzet Daily List Data function.
     */
    function omzetdailylistdata()
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        $total_condition    = '';
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

        $s_reg_min          = $this->input->$search_method('search_register_min');
        $s_reg_min          = an_isset($s_reg_min, '', '', true);
        $s_reg_max          = $this->input->$search_method('search_register_max');
        $s_reg_max          = an_isset($s_reg_max, '', '', true);
        $s_so_min           = $this->input->$search_method('search_so_min');
        $s_so_min           = an_isset($s_so_min, '', '', true);
        $s_so_max           = $this->input->$search_method('search_so_max');
        $s_so_max           = an_isset($s_so_max, '', '', true);
        $s_pv_min           = $this->input->$search_method('search_pv_min');
        $s_pv_min           = an_isset($s_pv_min, '', '', true);
        $s_pv_max           = $this->input->$search_method('search_pv_max');
        $s_pv_max           = an_isset($s_pv_max, '', '', true);
        $s_omzet_min        = $this->input->$search_method('search_omzet_min');
        $s_omzet_min        = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max        = $this->input->$search_method('search_omzet_max');
        $s_omzet_max        = an_isset($s_omzet_max, '', '', true);
        $s_bonus_min        = $this->input->$search_method('search_bonus_min');
        $s_bonus_min        = an_isset($s_bonus_min, '', '', true);
        $s_bonus_max        = $this->input->$search_method('search_bonus_max');
        $s_bonus_max        = an_isset($s_bonus_max, '', '', true);
        $s_percent_min      = $this->input->$search_method('search_percent_min');
        $s_percent_min      = an_isset($s_percent_min, '', '', true);
        $s_percent_max      = $this->input->$search_method('search_percent_max');
        $s_percent_max      = an_isset($s_percent_max, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if (!empty($s_date_min))    { $condition .= ' AND %date_omzet% >= ?'; $params[] = $s_date_min; }
        if (!empty($s_date_max))    { $condition .= ' AND %date_omzet% <= ?'; $params[] = $s_date_max; }
        if (!empty($s_reg_min))     { $total_condition .= ' AND %omzet_register% >= ?'; $params[] = $s_reg_min; }
        if (!empty($s_reg_max))     { $total_condition .= ' AND %omzet_register% <= ?'; $params[] = $s_reg_max; }
        if (!empty($s_so_min))      { $total_condition .= ' AND %omzet_ro% >= ?'; $params[] = $s_so_min; }
        if (!empty($s_so_max))      { $total_condition .= ' AND %omzet_ro% <= ?'; $params[] = $s_so_max; }
        if (!empty($s_pv_min))      { $total_condition .= ' AND %omzet_bv% >= ?'; $params[] = $s_pv_min; }
        if (!empty($s_pv_max))      { $total_condition .= ' AND %omzet_bv% <= ?'; $params[] = $s_pv_max; }
        if (!empty($s_omzet_min))   { $total_condition .= ' AND %total_omzet% >= ?'; $params[] = $s_omzet_min; }
        if (!empty($s_omzet_max))   { $total_condition .= ' AND %total_omzet% <= ?'; $params[] = $s_omzet_max; }
        if (!empty($s_bonus_min))   { $total_condition .= ' AND %total_bonus% >= ?'; $params[] = $s_bonus_min; }
        if (!empty($s_bonus_max))   { $total_condition .= ' AND %total_bonus% <= ?'; $params[] = $s_bonus_max; }
        if (!empty($s_percent_min)) { $total_condition .= ' AND %percent% >= ?'; $params[] = $s_percent_min; }
        if (!empty($s_percent_max)) { $total_condition .= ' AND %percent% <= ?'; $params[] = $s_percent_max; }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1)       { $order_by .= '%date_omzet% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%omzet_register% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%omzet_ro% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%total_omzet% ' . $sort; } 
        elseif ($column == 5)   { $order_by .= '%omzet_bv% ' . $sort; } 
        elseif ($column == 6)   { $order_by .= '%total_bonus% ' . $sort; } 
        elseif ($column == 7)   { $order_by .= '%percent% ' . $sort; }

        $data_list          = $this->Model_Member->get_all_omzet_daily($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->date_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetdailydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetdailydetail"><i class="fa fa-plus"></i> Detail</a>';

                $percent = $row->percent ? $row->percent : 0;
                if ($percent <= 0) {
                    $percent = '<span style="color:#dd4b39"><b>--</b></span>';
                } else {
                    $percent = $percent . ' %';
                }

                $records["aaData"][] = array(
                    an_center($i),
                    an_center(date("Y-m-d", strtotime($row->date_omzet))),
                    an_right(an_accounting($row->total_omzet_register)),
                    an_right(an_accounting($row->total_omzet_ro)),
                    an_right(an_accounting($row->total_omzet)),
                    an_right(an_accounting($row->total_omzet_bv)),
                    an_right(an_accounting($row->total_bonus)),
                    an_center($percent),
                    // an_center($btn_detail)
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Member->get_all_omzet_daily(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->omzetdailylist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Monthly List Data function.
     */
    function omzetmonthlylistdata()
    {
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $params             = array();
        $condition          = '';
        $total_condition    = '';
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

        $s_reg_min          = $this->input->$search_method('search_register_min');
        $s_reg_min          = an_isset($s_reg_min, '', '', true);
        $s_reg_max          = $this->input->$search_method('search_register_max');
        $s_reg_max          = an_isset($s_reg_max, '', '', true);
        $s_so_min           = $this->input->$search_method('search_so_min');
        $s_so_min           = an_isset($s_so_min, '', '', true);
        $s_so_max           = $this->input->$search_method('search_so_max');
        $s_so_max           = an_isset($s_so_max, '', '', true);
        $s_pv_min           = $this->input->$search_method('search_pv_min');
        $s_pv_min           = an_isset($s_pv_min, '', '', true);
        $s_pv_max           = $this->input->$search_method('search_pv_max');
        $s_pv_max           = an_isset($s_pv_max, '', '', true);
        $s_omzet_min        = $this->input->$search_method('search_omzet_min');
        $s_omzet_min        = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max        = $this->input->$search_method('search_omzet_max');
        $s_omzet_max        = an_isset($s_omzet_max, '', '', true);
        $s_bonus_min        = $this->input->$search_method('search_bonus_min');
        $s_bonus_min        = an_isset($s_bonus_min, '', '', true);
        $s_bonus_max        = $this->input->$search_method('search_bonus_max');
        $s_bonus_max        = an_isset($s_bonus_max, '', '', true);
        $s_percent_min      = $this->input->$search_method('search_percent_min');
        $s_percent_min      = an_isset($s_percent_min, '', '', true);
        $s_percent_max      = $this->input->$search_method('search_percent_max');
        $s_percent_max      = an_isset($s_percent_max, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if (!empty($s_date_min))    { $condition .= ' AND %month_omzet% >= ?'; $params[] = $s_date_min; }
        if (!empty($s_date_max))    { $condition .= ' AND %month_omzet% <= ?'; $params[] = $s_date_max; }
        if (!empty($s_reg_min))     { $total_condition .= ' AND %omzet_register% >= ?'; $params[] = $s_reg_min; }
        if (!empty($s_reg_max))     { $total_condition .= ' AND %omzet_register% <= ?'; $params[] = $s_reg_max; }
        if (!empty($s_so_min))      { $total_condition .= ' AND %omzet_ro% >= ?'; $params[] = $s_so_min; }
        if (!empty($s_so_max))      { $total_condition .= ' AND %omzet_ro% <= ?'; $params[] = $s_so_max; }
        if (!empty($s_pv_min))      { $total_condition .= ' AND %omzet_bv% >= ?'; $params[] = $s_pv_min; }
        if (!empty($s_pv_max))      { $total_condition .= ' AND %omzet_bv% <= ?'; $params[] = $s_pv_max; }
        if (!empty($s_omzet_min))   { $total_condition .= ' AND %total_omzet% >= ?'; $params[] = $s_omzet_min; }
        if (!empty($s_omzet_max))   { $total_condition .= ' AND %total_omzet% <= ?'; $params[] = $s_omzet_max; }
        if (!empty($s_bonus_min))   { $total_condition .= ' AND %total_bonus% >= ?'; $params[] = $s_bonus_min; }
        if (!empty($s_bonus_max))   { $total_condition .= ' AND %total_bonus% <= ?'; $params[] = $s_bonus_max; }
        if (!empty($s_percent_min)) { $total_condition .= ' AND %percent% >= ?'; $params[] = $s_percent_min; }
        if (!empty($s_percent_max)) { $total_condition .= ' AND %percent% <= ?'; $params[] = $s_percent_max; }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1)       { $order_by .= '%month_omzet% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%omzet_register% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%omzet_ro% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%total_omzet% ' . $sort; } 
        elseif ($column == 5)   { $order_by .= '%omzet_bv% ' . $sort; } 
        elseif ($column == 6)   { $order_by .= '%total_bonus% ' . $sort; } 
        elseif ($column == 7)   { $order_by .= '%percent% ' . $sort; }

        $data_list          = $this->Model_Member->get_all_omzet_monthly($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->month_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetmonthlydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetmonthlydetail"><i class="fa fa-plus"></i> Detail</a>';

                $percent = $row->percent ? $row->percent : 0;
                if ($percent <= 0) {
                    $percent = '<span style="color:#dd4b39"><b>--</b></span>';
                } else {
                    $percent = $percent . ' %';
                }

                $records["aaData"][] = array(
                    an_center($i),
                    an_center(date("M, Y", strtotime($row->month_omzet))),
                    an_right(an_accounting($row->total_omzet_register)),
                    an_right(an_accounting($row->total_omzet_ro)),
                    an_right(an_accounting($row->total_omzet)),
                    an_right(an_accounting($row->total_omzet_bv)),
                    an_right(an_accounting($row->total_bonus)),
                    an_center($percent),
                    // an_center($btn_detail)
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Member->get_all_omzet_monthly(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->omzetmonthlylist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }


        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Reward List Data function.
     */
    function rewardlistdata()
    {
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = an_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_reward           = $this->input->post('search_reward');
        $s_reward           = an_isset($s_reward, '', '', true);
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = an_isset($s_nominal_min, '', '', true);
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = an_isset($s_nominal_max, '', '', true);
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        $s_dateconfirm_min  = $this->input->post('search_dateconfirm_min');
        $s_dateconfirm_min  = an_isset($s_dateconfirm_min, '', '', true);
        $s_dateconfirm_max  = $this->input->post('search_dateconfirm_max');
        $s_dateconfirm_max  = an_isset($s_dateconfirm_max, '', '', true);

        if (!empty($s_username)) {
            $condition .= ' AND %username% LIKE "%' . $s_username . '%"';
        }
        if (!empty($s_name)) {
            $condition .= ' AND %name% LIKE "%' . $s_name . '%"';
        }
        if (!empty($s_reward)) {
            $condition .= ' AND %id_reward% = ' . $s_reward . '';
        }
        if (!empty($s_nominal_min)) {
            $condition .= ' AND %nominal% >= ' . $s_nominal_min . '';
        }
        if (!empty($s_nominal_max)) {
            $condition .= ' AND %nominal% <= ' . $s_nominal_max . '';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }
        if (!empty($s_dateconfirm_min)) {
            $condition .= ' AND %datemodified% >= "' . $s_dateconfirm_min . '"';
        }
        if (!empty($s_dateconfirm_max)) {
            $condition .= ' AND %datemodified% <= "' . $s_dateconfirm_max . '"';
        }
        if (!empty($s_status)) {
            $condition .= str_replace('%s%', ($s_status == 'pending' ? 0 : 1), ' AND %status% = %s%');
        }

        if ($is_admin) {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%username% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%name% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%message% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 6) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 7) {
                $order_by .= '%datemodified% ' . $sort;
            }
        } else {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%message% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%datemodified% ' . $sort;
            }
        }

        $data_list          = $this->Model_Member->get_all_member_reward($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            $currency = config_item('currency');
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->id);
                $id_member  = an_encrypt($row->id_member);
                if ($row->status >= 1) {
                    $status     = '<span class="badge badge-sm badge-success">CONFIRMED</span>';
                    $btn_action = '<a href="javascript:;" class="btn btn-sm text-success" disabled=""><i class="fa fa-check"></i></a>';
                    $dateconfirm = an_center(date('d M Y', strtotime($row->datemodified)));
                } else {
                    $status     = '<span class="badge badge-sm badge-default">PENDING</span>';
                    $btn_action = '<a href="javascript:;" class="btn btn-sm btn-primary rewardconfirm"
                                    data-url="' . base_url('member/rewardconfirm/' . $id) . '"
                                    data-username="' . $row->username . '"
                                    data-name="' . $row->name . '"
                                    data-nominal="' . an_accounting($row->nominal, $currency) . '"
                                    data-reward="' . $row->message . '"
                                    ><i class="fa fa-check"></i> Confirm</a>';
                    $dateconfirm = '';
                }



                $datatable = array(
                    an_center($i),
                    '<div style="min-width:100px">' . an_center(date('d M Y', strtotime($row->datecreated))) . '</div>',
                );

                if ($is_admin) {
                    $datatable[] = '<div style="min-width:100px"><a href="' . base_url('profile/' . $id_member) . '"><b>' . strtoupper($row->username) . '</b></a></div>';
                    $datatable[] = '<div style="min-width:100px"><b>' . strtoupper($row->name) . '</b></div>';
                }

                $datatable[] = '<div style="min-width:100px">' . $row->message . '</div>';
                $datatable[] = '<div style="min-width:100px">' . an_accounting($row->nominal, '', TRUE) . '</div>';
                $datatable[] = '<div style="min-width:80px">' . an_center($status) . '</div>';
                $datatable[] = $dateconfirm;
                $datatable[] = ($is_admin ? an_center($btn_action) : '');

                $records["aaData"][] = $datatable;
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
     * Stockist List Data function.
     */
    function stockistlistspin($is_stockist = true)
    {
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = ' WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE . ' ';
        if ($is_stockist) {
            $condition     .= ' AND %as_stockist% > 0 ';
        }

        $sAction            = an_isset($_REQUEST['sAction'], '');
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_memberid         = $this->input->post('search_memberid');
        $s_memberid         = an_isset($s_memberid, '', '', true);
        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_username         = $this->input->post('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_province         = $this->input->post('search_province');
        $s_province         = an_isset($s_province, '', '', true);
        $s_city             = $this->input->post('search_city');
        $s_city             = an_isset($s_city, '', '', true);
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, 1, '', true);
        $s_package          = $this->input->post('search_package');
        $s_package          = an_isset($s_package, '', '', true);

        if (!empty($s_memberid)) {
            $condition .= str_replace('%s%', $s_memberid, ' AND %id% = %s%');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_province)) {
            $condition .= str_replace('%s%', $s_province, ' AND %province% = %s%');
        }
        if (!empty($s_city)) {
            $condition .= str_replace('%s%', $s_city, ' AND %city% = %s%');
        }
        if (!empty($s_package)) {
            $condition .= str_replace('%s%', $s_package, ' AND %package% = "%s%"');
        }
        if (!empty($s_status)) {
            if ($s_status == 'member') {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% = %s%');
            } else {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% > %s%');
            }
        }

        if ($column == 1) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%province% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%city% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%as_stockist% ' . $sort;
        }

        $member_list        = $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by);

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($member_list as $row) {

                $username       = '<a href="javascript:;" class="btn-stockist-pin" data-id="' . $row->username . '">' . $row->username . '</a>';
                $name           = '<a href="javascript:;" class="btn-stockist-pin" data-id="' . $row->username . '">' . $row->name . '</a>';

                $province_name  = '';
                if ($row->province) {
                    $province       = an_provinces($row->province);
                    $province_name  = $province ? $province->province_name : '';
                }

                $city_name  = '';
                if ($row->city) {
                    $cities         = an_cities($row->city);
                    $city_name      = $cities ? $cities->regional_name : '';
                }

                $status  = '<span class="label label-sm label-success"><strong>MEMBER</strong></span>';
                if ($row->as_stockist == 1) {
                    $status  = '<span class="label label-sm label-primary"><strong>STOCKIST</strong></span>';
                }

                $btn_process    = '<a href="javascript:;" class="btn btn-xs btn-flat bg-blue btn-stockist-pin" title="Pilih Stockist" data-id="' . $row->username . '"><i class="fa fa-check"></i> Pilih</a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center($username),
                    $name,
                    $province_name,
                    $city_name,
                    an_center($status),
                    an_center($btn_process)
                );
                $i++;
            }
        }

        $end                                = $iDisplayStart + $iDisplayLength;
        $end                                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    // =============================================================================================
    // ACTION MEMBER
    // =============================================================================================

    /**
     * Type Status Member Function
     */
    function memberstatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('member/lists'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $created_by         = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Status Member tidak berhasil');

        if( ! $is_admin ){
            $data['message'] = 'Maaf, Anda tidak mempunyai akses';
            die(json_encode($data));
        }

        if( ! $id ){
            $data['message'] = 'ID tidak dikenali';
            die(json_encode($data));
        }

        $id_member          = an_decrypt($id);
        $memberdata         = an_get_memberdata_by_id($id_member);
        if( ! $memberdata ){
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        if( $memberdata->status != ACTIVE ){
            $data['message'] = 'Maaf, Status Member sudah tidak aktif.';
            die(json_encode($data));
        }

        // POST Input Form
        $status             = trim( $this->input->post('stockist_status') );
        $status             = an_isset($status, '', '', true);
        $province           = trim( $this->input->post('stockist_province') );
        $province           = an_isset($province, '', '', true);
        $district           = trim( $this->input->post('stockist_district') );
        $district           = an_isset($district, '', '', true);
        $subdistrict        = trim( $this->input->post('stockist_subdistrict') );
        $subdistrict        = an_isset($subdistrict, '', '', true);
        $village            = trim( $this->input->post('stockist_village') );
        $village            = an_isset($village, '', '', true);
        $address            = trim( $this->input->post('stockist_address') );
        $address            = an_isset($address, '', '', true);

        // -------------------------------------------------
        // Check Form Validation
        // -------------------------------------------------
        //$this->form_validation->set_rules('stockist_status','Status Anggota','required');
        $this->form_validation->set_rules('stockist_province','Provinsi','required');
        $this->form_validation->set_rules('stockist_district','Kota/Kabupaten','required');
        $this->form_validation->set_rules('stockist_subdistrict','Kecamatan','required');
        $this->form_validation->set_rules('stockist_village','Kelurahan/Desa','required');
        $this->form_validation->set_rules('stockist_address','Alamat','required');
        
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if( $this->form_validation->run() == FALSE){
            $data['message'] = 'Update Status Anggota tidak berhasil diubah. '.validation_errors();
            die(json_encode($data));
        }

        // Set Log Data
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_member']  = $memberdata->id;
        $log_data['as_stockist']= $memberdata->as_stockist;
        $log_data['as_status']  = $status;
        $log_data['status']     = 'Update Status Stockst Member';
        // set data renewal
        $data_stockist          = array(
            //'as_stockist'           => $status,
            'province_stockist'     => $province,
            'district_stockist'     => $district,
            'subdistrict_stockist'  => $subdistrict,
            'village_stockist'      => $village,
            'address_stockist'      => $address,
            'datemodified'          => $datetime
        );

        if ( ! $update_status_id = $this->Model_Member->update_data_member($memberdata->id, $data_stockist)) {
            $this->db->trans_rollback();
            $data['message'] = 'Update Status Anggota tidak berhasil. Terjadi kesalahan sistem. Ulangi proses beberapa saat lagi!';
            die(json_encode($data)); // JSON encode data
        }

        an_log_action('MEMBER_STOCKIST', 'SUCCESS', $created_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Status Anggota berhasil diubah.');
        die(json_encode($data));
    }

    /**
     * As Banned function.
     */
    function asbanned($id = 0)
    {
        auth_redirect();

        if (!$id) {
            echo 'failed';
            die();
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            echo 'failed';
            die();
        }

        $memberdata             = $this->Model_Member->get_memberdata($id);
        if (!$memberdata) {
            echo 'failed';
            die();
        }

        $datamember             = array(
            'status'            => 2,
            'as_stockist'       => 0,
            'province_stockist' => '',
            'city_stockist'     => '',
            'bank'              => 0,
            'bill'              => '0000',
            'bill_name'         => '',
            'city_code'         => 0,
            'datemodified'      => date('Y-m-d H:i:s'),
        );

        if ($this->Model_Member->update_data($id, $datamember)) {
            echo 'success';
            die();
        } else {
            echo 'failed';
            die();
        }
    }

    /**
     * As Active function.
     */
    function asactive($id = 0)
    {
        auth_redirect();

        if (!$id) {
            echo 'failed';
            die();
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            echo 'failed';
            die();
        }

        $memberdata             = $this->Model_Member->get_memberdata($id);
        if (!$memberdata) {
            echo 'failed';
            die();
        }

        $datamember             = array('status' => 1, 'bill_name' => strtoupper($memberdata->name));

        if ($this->Model_Member->update_data($id, $datamember)) {
            echo 'success';
            die();
        } else {
            echo 'failed';
            die();
        }
    }

    // ------------------------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------------------------
    // Save Action Function
    // ------------------------------------------------------------------------------------------------

    /**
     * New Member Registration function.
     */
    function memberreg()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            ); die(json_encode($data));
        }

        // Set data Return
        $an_token       = $this->security->get_csrf_hash();
        $data           = array(
            'message'   => 'error',
            'token'     => $an_token,
            'data'      => array(
                'field' => '',
                'msg'   => 'Pendaftaran anggota baru tidak berhasil.',
            )
        );

        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $packagedata            = an_packages();
        $package_active         = $packagedata ? count($packagedata) : 0;

        //$pin_access             = $is_admin ? false : true;
        //$ewallet_access         = false;

        $cfg_register_fee       = get_option('register_fee');
        $cfg_register_fee       = $cfg_register_fee ? $cfg_register_fee : 0;

        $payment_method         = $this->input->post('payment_method');
        $payment_method         = trim(an_isset($payment_method, '', '', true));
        
        // Select Product
        $select_product         = $this->input->post('select_product');
        $select_product         = trim(an_isset($select_product, '', '', true));

        // Package
        $package                = $this->input->post('reg_member_package');
        $package                = trim(an_isset($package, '', '', true));

        // Upline
        $upline_id              = $this->input->post('reg_member_upline_id');
        $upline_id              = trim(an_isset($upline_id, '', '', true));
        $upline_username        = $this->input->post('reg_member_upline');
        $upline_username        = trim(an_isset($upline_username, '', '', true));
        //$position             = $this->input->post('reg_member_position');
        //$position             = trim(an_isset($position, '', '', true));

        // Sponsor
        $sponsored              = $this->input->post('sponsored');
        $sponsored              = trim(an_isset($sponsored, '', '', true));
        $sponsor_id             = $this->input->post('reg_member_sponsor_id');
        $sponsor_id             = trim(an_isset($sponsor_id, '', '', true));
        $sponsor_username       = $this->input->post('reg_member_sponsor');
        $sponsor_username       = trim(an_isset($sponsor_username, '', '', true));

        $username               = $this->input->post('reg_member_username');
        $username               = trim(an_isset($username, '', '', true));
        $password               = $this->input->post('reg_member_password');
        $password               = trim(an_isset($password, '', '', true));

        // personal info
        $name                   = $this->input->post('reg_member_name');
        $name                   = trim(an_isset($name, '', '', true));
        $pob                    = $this->input->post('reg_member_pob');
        $pob                    = trim(an_isset($pob, '', '', true));
        $dob_date               = $this->input->post('reg_member_dob_date');
        $dob_date               = trim(an_isset($dob_date, '', '', true));
        $dob_month              = $this->input->post('reg_member_dob_month');
        $dob_month              = trim(an_isset($dob_month, '', '', true));
        $dob_year               = $this->input->post('reg_member_dob_year');
        $dob_year               = trim(an_isset($dob_year, '', '', true));
        $gender                 = $this->input->post('reg_member_gender');
        $gender                 = trim(an_isset($gender, '', '', true));
        $gender                 = strtoupper($gender);
        $marital                = $this->input->post('reg_member_marital');
        $marital                = trim(an_isset($marital, '', '', true));
        $idcard_type            = $this->input->post('reg_member_idcard_type');
        $idcard_type            = trim(an_isset($idcard_type, '', '', true));
        $idcard                 = $this->input->post('reg_member_idcard');
        $idcard                 = trim(an_isset($idcard, '', '', true));
        $npwp                   = $this->input->post('reg_member_npwp');
        $npwp                   = trim(an_isset($npwp, '', '', true));

        $country                = $this->input->post('reg_member_country');
        $country                = trim(an_isset($country, '', '', true));
        $country                = 'IDN';

        // Address
        $province               = $this->input->post('reg_member_province');
        $province               = trim(an_isset($province, '', '', true));
        $district               = $this->input->post('reg_member_district');
        $district               = trim(an_isset($district, '', '', true));
        $subdistrict            = $this->input->post('reg_member_subdistrict');
        $subdistrict            = trim(an_isset($subdistrict, '', '', true));
        $village                = $this->input->post('reg_member_village');
        $village                = trim(an_isset($village, '', '', true));
        $address                = $this->input->post('reg_member_address');
        $address                = trim(an_isset($address, '', '', true));
        $postcode               = $this->input->post('reg_member_postcode');
        $postcode               = trim(an_isset($postcode, '', '', true));

        // Contact
        $email                  = $this->input->post('reg_member_email');
        $email                  = trim(an_isset($email, '', '', true));
        $phone                  = $this->input->post('reg_member_phone');
        $phone                  = trim(an_isset($phone, '', '', true));
        $phone_home             = $this->input->post('reg_member_phone_home');
        $phone_home             = trim(an_isset($phone_home, '', '', true));
        $phone_office           = $this->input->post('reg_member_phone_office');
        $phone_office           = trim(an_isset($phone_office, '', '', true));

        // Account Bank
        $bank                   = $this->input->post('reg_member_bank');
        $bank                   = trim(an_isset($bank, '', '', true));
        $bill                   = $this->input->post('reg_member_bill');
        $bill                   = trim(an_isset($bill, '', '', true));
        $bill_name              = $this->input->post('reg_member_bill_name');
        $bill_name              = trim(an_isset($bill_name, '', '', true));

        // Emergency Contact
        $emergency_name         = $this->input->post('reg_member_emergency_name');
        $emergency_name         = trim(an_isset($emergency_name, '', '', true));
        $emergency_relationship = $this->input->post('reg_member_emergency_relationship');
        $emergency_relationship = trim(an_isset($emergency_relationship, '', '', true));
        $emergency_phone        = $this->input->post('reg_member_emergency_phone');
        $emergency_phone        = trim(an_isset($emergency_phone, '', '', true));

        // -------------------------------------------------
        // Check Form Validation
        // -------------------------------------------------
        if ($sponsored == 'other_sponsor') {
            $this->form_validation->set_rules('reg_member_sponsor', 'Username Sponsor', 'required');
        }

        $this->form_validation->set_rules('reg_member_package', 'Paket Reseller', 'required');
        $this->form_validation->set_rules('reg_member_upline', 'Username Upline', 'required');
        //$this->form_validation->set_rules('reg_member_position','Posisi','required');

        $this->form_validation->set_rules('reg_member_username','Username','required');
        $this->form_validation->set_rules('reg_member_password', 'Password', 'required');
        $this->form_validation->set_rules('reg_member_name', 'Nama Anggota', 'required');
        // $this->form_validation->set_rules('reg_member_pob', 'Tempat Lahir', 'required');
        // $this->form_validation->set_rules('reg_member_dob_date', 'Tangal Lahir', 'required');
        // $this->form_validation->set_rules('reg_member_dob_month', 'Bulan Lahir', 'required');
        // $this->form_validation->set_rules('reg_member_dob_year', 'Tahun Lahir', 'required');
        // $this->form_validation->set_rules('reg_member_gender', 'Jenis Kelamin', 'required');
        // $this->form_validation->set_rules('reg_member_marital', 'Status Perkawinan', 'required');
        $this->form_validation->set_rules('reg_member_idcard_type', 'Jenis Identitas', 'required');
        $this->form_validation->set_rules('reg_member_idcard', 'No. KTP/KITAS', 'required');

        $this->form_validation->set_rules('reg_member_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('reg_member_district', 'Kota/Kabupaten', 'required');
        $this->form_validation->set_rules('reg_member_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('reg_member_village', 'Desa/Kelurahan', 'required');
        $this->form_validation->set_rules('reg_member_address', 'Alamat', 'required');
        $this->form_validation->set_rules('reg_member_postcode', 'Kode POS', 'required');

        $this->form_validation->set_rules('reg_member_email', 'Email', 'required');
        $this->form_validation->set_rules('reg_member_phone', 'No.HP/WA', 'required');
        // $this->form_validation->set_rules('reg_member_phone_home', 'No. Telp Rumah', 'required');
        // $this->form_validation->set_rules('reg_member_phone_office', 'No. Telp Kantor', 'required');

        $this->form_validation->set_rules('reg_member_bank', 'Bank', 'required');
        $this->form_validation->set_rules('reg_member_bill', 'Nomor Rekening', 'required');
        // $this->form_validation->set_rules('reg_member_bill_name','Nama Pemilik Rekening Bank','required');

        // $this->form_validation->set_rules('reg_member_emergency_name', 'Emergency Contact Name', 'required');
        // $this->form_validation->set_rules('reg_member_emergency_relationship', 'Emergency Contact Relationship', 'required');
        // $this->form_validation->set_rules('reg_member_emergency_phone', 'Emergency Contact Phone', 'required');

        $this->form_validation->set_rules('reg_member_bank', 'Bank', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE) {
            $data['data']['msg'] = 'Pendaftaran anggota baru tidak berhasil. ' . br() . validation_errors();
            die(json_encode($data)); // Set JSON data
        }

        if ( substr($phone, 0, 1) != '0' ) {
            $phone      = '0'. $phone;
        }
        
        if ( substr($emergency_phone, 0, 1) != '0' ) {
            $emergency_phone = '0'. $emergency_phone;
        }

        if ($npwp == '__.___.___._-___.___') {
            $npwp       = '';
        }

        if ($npwp == '00.000.000.0-000.000') {
            $npwp       = '';
        }

        $npwp           = str_replace('_', '', $npwp);
        if (strlen($npwp) != 20) {
            $npwp       = '';
        }

        // // -------------------------------------------------
        // // Check Province, District, SubDistrict
        // // -------------------------------------------------
        // $province_code  = '';
        // $province_name  = '';
        // if ($province && $get_province = an_provinces($province)) {
        //     $province_code = isset($get_province->province_code) ? $get_province->province_code : $get_province->id;
        //     $province_name = $get_province->province_name;
        // }

        // // -------------------------------------------------
        // // Check District Code
        // // -------------------------------------------------
        // $district_code  = '';
        // $district_name  = '';
        // if ($district && $get_district = an_districts($district)) {
        //     $district_code = isset($get_district->district_code) ? $get_district->district_code : $get_district->id;
        //     $district_name = $get_district->district_type . ' ' . $get_district->district_name;
        // }

        // // -------------------------------------------------
        // // Check SubDistrict
        // // -------------------------------------------------
        // $subdistrict_name  = '';
        // if ($subdistrict && $get_subdistrict = an_subdistricts($subdistrict)) {
        //     $subdistrict_name = $get_subdistrict->subdistrict_name;
        // }

        // if (!$province_code || !$district_code) {
        //     $data['data']['msg'] = 'Kode Provinsi atau Kode Kota/Kabupaten tidak ditemukan atau belum terdaftar!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // if (!$province_name || !$district_name || !$subdistrict_name) {
        //     $data['data']['msg'] = 'Provinsi atau Kota/Kabupaten atau Kecamatan tidak ditemukan atau belum terdaftar!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // -------------------------------------------------
        // Check Package
        // -------------------------------------------------
        $get_packages           = '';
        if ( $package ) {
            $get_packages       = an_packages($package);
        }
        if ( !$get_packages ) {
            $data['data']['msg'] = 'Data Paket tidak ditemukan atau belum terdaftar!';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Check Product
        // -------------------------------------------------
        $product_id             = an_decrypt($select_product);
        $productdata            = an_products($product_id, true);
        if( !$productdata ){
            $data['data']['msg'] = 'Data Produk tidak ditemukan atau belum terdaftar!';
            die(json_encode($data)); // Set JSON data
        }
        $product_qty            = $productdata->amount;
        $product_price          = $productdata->price;
        $product_bv             = $productdata->bv;
        
        // -------------------------------------------------
        // Check Omzet
        // -------------------------------------------------
        $total_omzet            = $product_price;
        $total_omzet_bv         = $product_bv;
        $total_price            = $product_price;

        // -------------------------------------------------
        // Check Status and Access
        // -------------------------------------------------
        if ( $is_admin ) {
            $m_status           = 1;
            $m_access           = 'admin';
        } else {
            $m_status           = 0;
            $m_access           = 'member';
        }

        // -------------------------------------------------
        // Check Sponsor
        // -------------------------------------------------
        $sponsor_id         = $is_admin ? $sponsor_id : ($sponsored == 'other_sponsor' ? $sponsor_id : $current_member->id);
        $sponsordata        = $this->Model_Member->get_memberdata($sponsor_id);
        if (!$sponsordata) {
            $data['data']['msg'] = 'Sponsor tidak ditemukan atau belum terdaftar! Silahkan masukkan username sponsor lainnya!';
            die(json_encode($data)); // Set JSON data
        }
        $sponsor_id         = $sponsordata->id;
        $sponsor_username   = $sponsordata->username;
        $sponsor_sponsor    = $sponsordata->sponsor;

        // -------------------------------------------------
        // Check If Sponsor is Downline
        // -------------------------------------------------
        if (!$is_admin) {
            $is_downline        = $this->Model_Member->get_is_downline($sponsor_id, $current_member->tree);
            if (!$is_downline) {
                $data['data']['msg'] = 'Sponsor ini bukan jaringan Anda! Silahkan masukkan Username lain!';
                die(json_encode($data)); // Set JSON data
            }
        }

        // -------------------------------------------------
        // Check Upline
        // -------------------------------------------------
        $uplinedata         = $this->Model_Member->get_memberdata($upline_id);
        if (!$uplinedata) {
            $data['data']['msg'] = 'Upline tidak ditemukan atau belum terdaftar! Silahkan masukkan username upline lainnya!';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Check If Upline is Downline Sponsor
        // -------------------------------------------------
        $is_down_sponsor    = $this->Model_Member->get_is_downline($upline_id, $sponsordata->tree);
        if (!$is_down_sponsor) {
            $data['data']['msg'] = 'Username upline ini bukan jaringan sponsor! Silahkan masukkan username upline yang lain!';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Check Position
        // -------------------------------------------------
        $position = ( $m_status == 1 ) ? an_position_upline($upline_id) : 0;

        // -------------------------------------------------
        // Check Username
        // -------------------------------------------------
        $username_exist     = an_check_username($username);
        if( $username_exist || !empty($username_exist) ){
            $data['data']['msg'] = 'Username tidak dapat digunakan. Silahkan gunakan Username lainnya!';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Check Email
        // -------------------------------------------------
        // $email_exist        = $this->Model_Member->get_member_by('email', $email);
        // if ($email_exist || !empty($email_exist)) {
        //     $data['data']['msg'] = 'Email sudah terdaftar. Silahkan gunakan Email lainnya!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // -------------------------------------------------
        // Check Phone
        // -------------------------------------------------
        // $phone_exist        = $this->Model_Member->get_member_by('phone', $phone);
        // if ($phone_exist || !empty($phone_exist)) {
        //     $data['data']['msg'] = 'No. WA/HP sudah terdaftar. Silahkan gunakan No WA/HP lainnya!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // -------------------------------------------------
        // Check ID Card
        // -------------------------------------------------
        // $idcard_exist       = $this->Model_Member->get_member_by('idcard', $idcard);
        // if ($idcard_exist || !empty($idcard_exist)) {
        //     $data['data']['msg'] = 'No. KTP sudah terdaftar. Silahkan gunakan No KTP lainnya!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // -------------------------------------------------
        // Check Bill
        // -------------------------------------------------
        // $bill_exist         = $this->Model_Member->get_member_by('bill', $bill);
        // if ($bill_exist || !empty($bill_exist)) {
        //     $data['data']['msg'] = 'No. Rekening sudah terdaftar. Silahkan gunakan No Rekening lainnya!';
        //     die(json_encode($data)); // Set JSON data
        // }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        $img_msg                    = '';
        $get_photo_img              = '';
        $get_idcard_img             = '';
        $get_cover_img              = '';
        $img_upload                 = false;
        $time                       = time();
        $photo_img_name             = strtolower($username) . '-' . $time;
        $idcard_img_name            = strtolower($username) . '-idcard-' . $time;
        $cover_img_name             = strtolower($username) . '-cover-' . $time;

        // Config Upload Image
        $config['allowed_types']        = 'jpg|png|jpeg';
        $config['max_size']             = '1048';
        $config['overwrite']            = true;

        $config_idcard                  = $config;
        $config_idcard['upload_path']   = IDCARD_IMG_PATH;
        $config_idcard['file_name']     = $idcard_img_name;

        if ($img_upload) {
            // Upload ID Card
            $this->load->library('upload', $config_idcard);
            $this->upload->initialize($config_idcard);
            if (!$this->upload->do_upload("idcard_img")) {
                $img_upload             = false;
                $img_msg                = $this->upload->display_errors();
            }
            if (!$img_upload && $img_msg) {
                $data['data']['msg'] = 'Upload Foto KTP Gagal. ' . $img_msg;
                die(json_encode($data)); // Set JSON data
            }
        }

        // -------------------------------------------------
        // Set Data Member
        // -------------------------------------------------
        // $password               = strtolower($password);
        $username               = strtolower($username);
        $name                   = strtoupper($name);
        $bill_name              = $name;
        $datetime               = date('Y-m-d H:i:s');
        $password_bcript        = an_password_hash($password);
        $password_encrypt       = an_encrypt($password);
        $uniquecode             = ($m_status == 1) ? 0 : an_generate_unique();
        $dateofbirth            = $dob_year . '-' . $dob_month . '-' . $dob_date;

        $data_member            = array(
            'username'              => $username,
            'password'              => $m_status == 1 ? $password_bcript : $password,
            'password_pin'          => $m_status == 1 ? $password_bcript : $password,
            'type'                  => MEMBER,
            'type_status'           => ( $package == 'dropshipper' ? TYPE_STATUS_DROPSHIPPER : TYPE_STATUS_RESELLER ),
            'package'               => $package,
            'rank'                  => $package,
            'sponsor'               => $sponsor_id,
            'parent'                => $upline_id,
            'position'              => $position,
            'name'                  => $name,
            // 'pob'                   => $pob,
            // 'dob'                   => $dateofbirth,
            // 'gender'                => $gender,
            // 'marital'               => $marital,
            'idcard_type'           => $idcard_type,
            'idcard'                => $idcard,
            'npwp'                  => $npwp,
            'country'               => $country,
            'province'              => $province,
            'district'              => $district,
            'subdistrict'           => $subdistrict,
            'village'               => $village,
            'address'               => $address,
            'postcode'              => $postcode,
            'email'                 => $email,
            'phone'                 => $phone,
            'phone_home'            => $phone_home,
            'phone_office'          => $phone_office,
            'bank'                  => $bank,
            'bill'                  => $bill,
            'bill_name'             => $bill_name,
            'emergency_name'        => $emergency_name,
            'emergency_relationship'=> $emergency_relationship,
            'emergency_phone'       => $emergency_phone,
            'status'                => $m_status,
            'total_omzet'           => $total_omzet_bv,
            'uniquecode'            => $uniquecode,
            'datecreated'           => $datetime,
        );

        if ($img_upload) {
            $data_member['idcard_img']  = $idcard_img_name; 
            an_resize_image($idcard_img_name, IDCARD_IMG_PATH); // Resize Image ID Card
        }

        // -------------------------------------------------
        // Save Member
        // -------------------------------------------------
        if ($member_save_id = $this->Model_Member->save_data($data_member)) {
            if ($m_status == 1) {
                // Update Member Tree
                // -------------------------------------------------
                $gen                = $sponsordata->gen + 1;
                $level              = $uplinedata->level + 1;
                $tree               = an_generate_tree($member_save_id, $uplinedata->tree);
                $tree_sponsor       = an_generate_tree_sponsor($member_save_id, $sponsordata->tree_sponsor);
                $data_tree          = array('gen' => $gen, 'level' => $level, 'tree' => $tree, 'tree_sponsor' => $tree_sponsor);
                if (!$update_tree = $this->Model_Member->update_data_member($member_save_id, $data_tree)) {
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member.';
                    die(json_encode($data)); // Set JSON data
                }

                // -------------------------------------------------
                // Generate Key Member
                // -------------------------------------------------
                $generate_key = an_generate_key();
                an_generate_key_insert($generate_key, ['id_member' => $member_save_id, 'name' => $name]);
            }
        } else {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member.';
            die(json_encode($data)); // Set JSON data
        }

        if (!$downline = an_get_memberdata_by_id($member_save_id)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member.';
            die(json_encode($data)); // Set JSON data
        }

        $total_payment          = $total_price + $uniquecode;
        $data_member_confirm    = array(
            'id_member'         => $current_member->id,
            'member'            => $current_member->username,
            'id_sponsor'        => $sponsordata->id,
            'sponsor'           => $sponsordata->username,
            'id_downline'       => $downline->id,
            'downline'          => $downline->username,
            'status'            => $m_status,
            'access'            => $m_access,
            'package'           => $package,
            'omzet'             => $total_price,
            'uniquecode'        => $uniquecode,
            'nominal'           => $total_payment,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        $insert_member_confirm  = $this->Model_Member->save_data_confirm($data_member_confirm);
        if (!$insert_member_confirm) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data confirm member.';
            die(json_encode($data)); // Set JSON data
        }

        if ($m_status == 1) {
            // save data member omzet register
            if ($total_omzet_bv > 0) {
                $data_member_omzet    = array(
                    'id_member'     => $downline->id,
                    'bv'            => $total_omzet_bv,
                    'omzet'         => $total_omzet,
                    'amount'        => $total_price,
                    'status'        => 'register',
                    'desc'          => 'New '.( $package == 'dropshipper' ? 'Dropshipper' : 'Reseller' ),
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );

                if (!$insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet)) {
                    $this->db->trans_rollback();
                    $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet.';
                    die(json_encode($data)); // Set JSON data
                }

                // -------------------------------------------------
                // calculate bonus referral
                // -------------------------------------------------
                $bonus_referral     = an_calculate_bonus_referral($downline->id, $datetime);
            }
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['data']['msg'] = 'Pendaftaran tidak berhasil. Terjadi kesalahan data transaksi.';
            die(json_encode($data)); // Set JSON data
        } else {
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();

            an_log_action('MEMBER_REG', $username, $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'username' => $username, 'password' => $password)));

            // Send Notif Email
            $this->an_email->send_email_new_member($downline, $sponsordata, $password, $total_payment);
            $this->an_email->send_email_sponsor($downline, $sponsordata);

            // Send WhatsApp
            $this->an_wa->send_wa_new_member( $downline, $sponsordata, $password, $total_payment );
            $this->an_wa->send_wa_sponsor( $downline, $sponsordata, $uplinedata);

            // Set JSON data
            $sponsorname    = $sponsordata->username . ' / ' . $sponsordata->name;
            $memberinfo     = '
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('username') . '</small></div>
                    <div class="col-sm-9"><small class="text-lowecase font-weight-bold">' . $username . '</small></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('name') . '</small></div>
                    <div class="col-sm-9"><small class="text-uppercase font-weight-bold">' . $name . '</small></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">Password</small></div>
                    <div class="col-sm-9"><small class="font-weight-bold">' . $password . '</small></div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">Sponsor</small></div>
                    <div class="col-sm-9"><small class="font-weight-bold">' . $sponsorname . '</small></div>
                </div>';

            $data           = array(
                'message'   => 'success',
                'token'     => $an_token,
                'data'      => array(
                    'msg'           => 'success',
                    'msgsuccess'    => 'Pendaftaran anggota baru berhasil!',
                    'memberinfo'    => $memberinfo
                )
            );
            die(json_encode($data));
        }
    }

    /**
     * Member RO Function
     */
    function memberro()
    {
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('member/ro'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'RO tidak berhasil.');

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $username           = trim($this->input->post('username'));
        $username           = an_isset($username, '', '', true);
        $optionro           = trim($this->input->post('optionro'));
        $optionro           = an_isset($optionro, '', '', true);
        $pin_id             = trim($this->input->post('select_pin'));
        $pin_id             = an_isset($pin_id, '', '', true);

        $this->form_validation->set_rules('select_pin', 'PIN Produk', 'required');
        if ( $optionro == 'other' ) {
            $this->form_validation->set_rules('username', 'Username', 'required');
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE) {
            $data['message'] = 'RO tidak berhasil. ' . br() . validation_errors();
            die(json_encode($data)); // Set JSON data
        }

        if ( $is_admin ) {
            $data['message'] = 'Maaf, Administrator tidak dapat melakukan RO !';
            die(json_encode($data));
        }

        if ( $optionro == 'other' ) {
            $memberdata     = $this->Model_Member->get_member_by('login', $username);
        } else {
            $memberdata     = an_get_memberdata_by_id($current_member->id);
        }
        
        if ( !$memberdata ) {
            $data['message'] = 'Username tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ( $memberdata->status != ACTIVE ) {
            $data['message'] = 'Maaf, Status Member sudah tidak aktif.';
            die(json_encode($data));
        }

        // $data['current_member'] = $current_member;
        // $data['memberdata'] = $memberdata;
        // $data['message'] = 'DEBUGGING';
        // die(json_encode($data));

        $message            = '';
        $activation_ro      = kb_activation_ro($current_member, $memberdata, $pin_id, $datetime, $message);
        if ( !$activation_ro ) {
            $data['message'] = $message;
            die(json_encode($data));
        }

        $data = array('status' => 'success', 'token' => $an_token, 'info' => $message, 'message' => 'Aktivasi RO berhasil.');
        die(json_encode($data));
    }

    /**
     * Member Loan Function
     */
    function memberloan($type = '')
    {
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('member/ro'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $type               = $type ? an_decrypt($type) : '';
        $datetime           = date('Y-m-d H:i:s');

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Deposite/Withdraw Loan tidak berhasil.');

        if (!$type) {
            $data['message'] = 'Tipe Loan tidak valid !';
            die(json_encode($data));
        }

        if (!in_array($type, array('deposite', 'withdraw'))) {
            $data['message'] = 'Tipe Loan tidak valid !';
            die(json_encode($data));
        }

        // POST Input Form
        $username           = trim($this->input->post('username'));
        $username           = an_isset($username, '', '', true);
        $amount             = trim($this->input->post('amount'));
        $amount             = an_isset($amount, 0, 0, true);
        $amount             = str_replace('.', '', $amount);
        $amount             = max(0, $amount);
        $type_loan          = ucwords(strtolower($type));

        if ( !$amount ) {
            $data['message'] = 'Nominal '. $type_loan .' tidak boleh kosong !';
            die(json_encode($data));
        }

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('amount', 'Nominal '. ucwords($type), 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE) {
            $data['message'] = $type_loan. ' Loan tidak berhasil. ' . br() . validation_errors();
            die(json_encode($data)); // Set JSON data
        }

        if ( ! $is_admin ) {
            $data['message'] = 'Maaf, hanya yang dapat Administrator melakukan input '. $type_loan .' Loan !';
            die(json_encode($data));
        }
        
        $memberdata     = $this->Model_Member->get_member_by('login', $username);
        if ( !$memberdata ) {
            $data['message'] = 'Username tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ( $memberdata->status != ACTIVE ) {
            $data['message'] = 'Maaf, Status Member sudah tidak aktif.';
            die(json_encode($data));
        }

        if ( $memberdata->as_stockist == 0 ) {
            $data['message'] = 'Username ini bukan Stockist! Silahkan masukkan Username lain!';
            die(json_encode($data));
        }

        if ( $type == 'withdraw' ) {
            $deposite       = $this->Model_Member->get_loan_deposite($memberdata->id);
            if ( $amount > $deposite ) {
                $data['message'] = 'Withdraw Loan tidak berhasil. Maksimal Withdraw '. an_accounting($deposite, config_item('currency')) .' !';
                die(json_encode($data));
            }
        }

        $data_member_loan   = array(
            'id_member'     => $memberdata->id,
            'amount'        => $amount,
            'type'          => $type,
            'status'        => 1,
            'description'   => $type_loan .' Loan sebesar '. an_accounting($amount, config_item('currency')),
            'datecreated'   => $datetime,
            'datemodified'  => $datetime,
        );

        $insert_member_loan = $this->Model_Member->save_data_loan($data_member_loan);
        if ( !$insert_member_loan ) {
            $data['message'] = 'Simpan data '. $type_loan .' tidak berhasil. Terjadi kesalahan pada data transaksi.';
            die(json_encode($data)); // Set JSON data
        }

        $data['status']     = 'success';
        $data['message']    = 'Simpan data '. $type_loan .' Loan berhasil.';
        die(json_encode($data));
    }

    /**
     * Confirm Agent Register Function
     */
    function memberconfirm($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token  = $this->security->get_csrf_hash();
        $data       = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pendaftaran tidak dikenali.');

        if (!$id) {
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim($this->input->post('password'));
        $password           = an_isset($password, '', '', true);

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Pendaftaran Reseller ini !';
            die(json_encode($data));
        }

        // Get Data Member Confirm
        if (!$memberconfirm = $this->Model_Member->get_member_confirm($id)) {
            die(json_encode($data));
        }

        if ($my_account = an_get_memberdata_by_id($current_member->id)) {
            $my_password    = $my_account->password;
        }

        if ($staff = an_get_current_staff()) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ($password_md5 == $my_password) {
            $pwd_valid  = true;
        }

        if (an_hash_verify($password, $my_password)) {
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
        $log_data['id_confirm'] = $id;
        $log_data['id_downline'] = $memberconfirm->id_downline;
        $log_data['status']     = 'Konfirmasi Pendaftaran';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($memberconfirm->status == NONACTIVE) {
                an_log_action('REGISTER_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($memberconfirm->status == ACTIVE) {
            $data['message'] = 'Status Pendaftaran Reseller sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ($memberconfirm->status != NONACTIVE) {
            $data['message'] = 'Pendaftaran tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($memberconfirm->id_downline)) {
            $data['message'] = 'Konfirmasi Pendaftaran Reseller tidak berhasil. Reseller tidak dikenali.';
            die(json_encode($data));
        }

        if ($memberdata->status != NONACTIVE) {
            $data['message'] = 'Pendaftaran tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update Data Member Confirm
        $data_update_confirm = array(
            'status'        => ACTIVE,
            'datemodified'  => $datetime,
        );

        if (!$update_confirm = $this->Model_Member->update_data_member_confirm($memberconfirm->id, $data_update_confirm)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Reseller tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Reseller.';
            die(json_encode($data));
        }

        // Get Data Sponsor 
        if (!$sponsordata = an_get_memberdata_by_id($memberdata->sponsor)) {
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Reseller tidak berhasil. Sponsor Reseller tidak dikenali.';
            die(json_encode($data));
        }

        $level              = $sponsordata->level + 1;
        //$position           = an_position_sponsor($sponsordata->id);
        $tree               = an_generate_tree($memberdata->id, $sponsordata->tree);
        $data_update_member = array(
            //'position'      => $position,
            'level'         => $level,
            'tree'          => $tree,
            'status'        => ACTIVE,
            'datemodified'  => $datetime,
        );

        if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_update_member)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Reseller tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Reseller.';
            die(json_encode($data));
        }

        // Update Data Member Omzet
        // -------------------------------------------------
        if ($memberconfirm->omzet > 0) {
            $data_member_omzet  = array(
                'id_member'     => $memberdata->id,
                'omzet'         => $memberconfirm->omzet,
                'amount'        => $memberconfirm->omzet,
                'status'        => 'register',
                'desc'          => 'New Reseller',
                'date'          => date('Y-m-d', strtotime($datetime)),
                'datecreated'   => $datetime,
                'datemodified'  => $datetime
            );

            if (!$insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet)) {
                $this->db->trans_rollback();
                $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
                die(json_encode($data));
            }
        }
        
        // -------------------------------------------------
        // calculate bonus referral
        // -------------------------------------------------
        $bonus_referral     = an_calculate_bonus_referral($memberdata->id, $datetime);

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('REGISTER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status' => 'success', 'token' => $an_token, 'message' => 'Pendaftaran Reseller berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    /**
     * Confirm Agent Reward Function
     */
    function rewardconfirm($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Reward tidak dikenali.');

        if (!$id) {
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim($this->input->post('password'));
        $password           = an_isset($password, '', '', true);

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Reward ini !';
            die(json_encode($data));
        }

        // Get Data Member Reward
        if (!$reward = $this->Model_Member->get_member_reward_by('id', $id)) {
            die(json_encode($data));
        }

        if ($my_account = an_get_memberdata_by_id($current_member->id)) {
            $my_password    = $my_account->password;
        }

        if ($staff = an_get_current_staff()) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ($password_md5 == $my_password) {
            $pwd_valid  = true;
        }

        if (an_hash_verify($password, $my_password)) {
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
        $log_data['id']         = $id;
        $log_data['id_member']  = $reward->id_member;
        $log_data['status']     = 'Konfirmasi Reward';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($reward->status == 0) {
                an_log_action('REWARD_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($reward->status >= 1) {
            $data['message'] = 'Status Reward sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ($reward->status != 0) {
            $data['message'] = 'Reward tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($reward->id_member)) {
            $data['message'] = 'Konfirmasi Reward tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        if ($memberdata->status != ACTIVE) {
            $data['message'] = 'Reward tidak dapat dikonfirmasi. Status Member sudah tidak aktif !';
            die(json_encode($data));
        }

        // Update Data Reward
        $update_data = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'confirm_by'    => $confirmed_by
        );

        if (!$update_reward = $this->Model_Member->update_data_reward($id, $update_data)) {
            // Set JSON data
            $data['message'] = 'Konfirmasi Reward tidak berhasil. Terjadi kesalahan pada transaksi.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('REWARD_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status' => 'success', 'message' => 'Reward berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    // =============================================================================================
    // PROFILE MEMBER
    // =============================================================================================

    /**
     * Profile Member function.
     */
    function profile($id = 0)
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id > 0 && $is_admin) {
            $member_data        = an_get_memberdata_by_id($id);
        } elseif ($id > 0 && !$is_admin) {
            // $is_down            = $this->Model_Member->get_is_downline($id, $current_member->id);

            // if( !$is_down ){
            //     redirect( base_url('profile'), 'location' );
            // }
            redirect(base_url('profile'), 'location');
        }

        $data['title']          = TITLE . 'Profil Member';
        $data['member']         = $current_member;
        $data['member_other']   = $member_data;
        $data['is_admin']       = $is_admin;
        $data['main_content']   = 'member/profile';

        $this->load->view(VIEW_BACK . 'template', $data);
    }

    /**
     * Profile Personal Info Update function.
     */
    function personalinfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $an_token              = $this->security->get_csrf_hash();

        $post_member_id         = $this->input->post('member_id');
        $post_member_id         = an_isset($post_member_id, 0, '', true);
        $post_member_username   = $this->input->post('member_username');
        $post_member_username   = an_isset($post_member_username, '', '', true);
        $post_member_name       = $this->input->post('member_name');
        $post_member_name       = an_isset($post_member_name, '', '', true);
        $post_member_email      = $this->input->post('member_email');
        $post_member_email      = an_isset($post_member_email, '', '', true);
        $post_member_phone      = $this->input->post('member_phone');
        $post_member_phone      = an_isset($post_member_phone, '', '', true);
        $post_phone_home        = $this->input->post('member_phone_home');
        $post_phone_home        = an_isset($post_phone_home, '', '', true);
        $post_phone_office      = $this->input->post('member_phone_office');
        $post_phone_office      = an_isset($post_phone_office, '', '', true);

        $post_pob               = $this->input->post('member_pob');
        $post_pob               = an_isset($post_pob, '', '', true);
        $post_dob_date          = $this->input->post('member_dob_date');
        $post_dob_date          = an_isset($post_dob_date, '', '', true);
        $post_dob_month         = $this->input->post('member_dob_month');
        $post_dob_month         = an_isset($post_dob_month, '', '', true);
        $post_dob_year          = $this->input->post('member_dob_year');
        $post_dob_year          = an_isset($post_dob_year, '', '', true);

        $post_gender            = $this->input->post('member_gender');
        $post_gender            = an_isset($post_gender, '', '', true);
        $post_marital           = $this->input->post('member_marital');
        $post_marital           = an_isset($post_marital, '', '', true);
        $post_idcard_type       = $this->input->post('member_idcard_type');
        $post_idcard_type       = an_isset($post_idcard_type, '', '', true);
        $post_idcard            = $this->input->post('member_idcard');
        $post_idcard            = an_isset($post_idcard, '', '', true);
        $post_npwp              = $this->input->post('member_npwp');
        $post_npwp              = an_isset($post_npwp, '', '', true);

        $post_province          = $this->input->post('member_province');
        $post_province          = an_isset($post_province, 0, '', true);
        $post_district          = $this->input->post('member_district');
        $post_district          = an_isset($post_district, 0, '', true);
        $post_subdistrict       = $this->input->post('member_subdistrict');
        $post_subdistrict       = an_isset($post_subdistrict, 0, '', true);
        $post_village           = $this->input->post('member_village');
        $post_village           = an_isset($post_village, 0, '', true);
        $post_address           = $this->input->post('member_address');
        $post_address           = an_isset($post_address, '', '', true);
        $post_postcode          = $this->input->post('member_postcode');
        $post_postcode          = an_isset($post_postcode, '', '', true);

        $post_member_bank       = $this->input->post('member_bank');
        $post_member_bank       = an_isset($post_member_bank, '', '', true);
        $post_member_bill       = $this->input->post('member_bill');
        $post_member_bill       = an_isset($post_member_bill, '', '', true);
        $post_member_bill_name  = $this->input->post('member_bill_name');
        $post_member_bill_name  = an_isset($post_member_bill_name, '', '', true);
        $post_member_branch     = $this->input->post('member_bank_branch');
        $post_member_branch     = an_isset($post_member_branch, '', '', true);
        $post_member_city_code  = $this->input->post('member_city_code');
        $post_member_city_code  = an_isset($post_member_city_code, '', '', true);

        $post_emergency_name    = $this->input->post('member_emergency_name');
        $post_emergency_name    = an_isset($post_emergency_name, '', '', true);
        $post_emergency_phone   = $this->input->post('member_emergency_phone');
        $post_emergency_phone   = an_isset($post_emergency_phone, '', '', true);
        $post_emergency_relationship = $this->input->post('member_emergency_relationship');
        $post_emergency_relationship = an_isset($post_emergency_relationship, '', '', true);
        
        $post_facebook_url      = $this->input->post('member_facebook_url');
        $post_facebook_url      = an_isset($post_facebook_url, '', '', true);
        $post_twitter_url       = $this->input->post('member_twitter_url');
        $post_twitter_url       = an_isset($post_twitter_url, '', '', true);
        $post_instagram_url     = $this->input->post('member_instagram_url');
        $post_instagram_url     = an_isset($post_instagram_url, '', '', true);
        $post_tiktok_url        = $this->input->post('member_tiktok_url');
        $post_tiktok_url        = an_isset($post_tiktok_url, '', '', true);

        $post_wd_status         = $this->input->post('member_wd_status');
        $post_wd_status         = an_isset($post_wd_status, 0, 0, true);

        $id_member              = (an_isset($post_member_id, '', '', true) > 0 ? $post_member_id : $current_member->id);
        $memberdata             = (an_isset($post_member_id, '', '', true) > 0 ? an_get_memberdata_by_id($post_member_id) : $current_member);

        $data   = array(
            'status'        => 'error',
            'token'         => $an_token,
            'message'       => 'Data member tidak ditemukan atau belum terdaftar',
        );
        if (!$memberdata) {
            // Set JSON data
            die(json_encode($data));
        }

        $access = TRUE;
        if ($staff = an_get_current_staff()) {
            if ($staff->access == 'partial') {
                $role   = array();
                if ($staff->role) {
                    $role = $staff->role;
                }

                foreach (array(STAFF_ACCESS4) as $val) {
                    if (empty($role) || !in_array($val, $role))
                        $access = FALSE;
                }
            }
        }

        if (!$access) {
            $data['message'] = 'Maaf, Anda tidak mempunyai akses untuk edit profil anggota!';
            die(json_encode($data));
        }

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');

        // $this->form_validation->set_rules('member_pob', 'Tempat Lahir', 'required');
        // $this->form_validation->set_rules('member_dob_date', 'Tanggal Lahir', 'required');
        // $this->form_validation->set_rules('member_dob_month', 'Bulan Lahir', 'required');
        // $this->form_validation->set_rules('member_dob_year', 'Tahun Lahir', 'required');
        // $this->form_validation->set_rules('member_gender', 'Jenis Kelamin', 'required');
        // $this->form_validation->set_rules('member_marital', 'Status Perkawinan', 'required');

        $this->form_validation->set_rules('member_idcard_type', 'Jenis Identitas', 'required');
        $this->form_validation->set_rules('member_idcard', 'No. KTP/KITAS', 'required');

        $this->form_validation->set_rules('member_province', 'Provinsi (saat ini)', 'required');
        $this->form_validation->set_rules('member_district', 'Kota/Kabupaten (saat ini)', 'required');
        $this->form_validation->set_rules('member_subdistrict', 'Kecamatan (saat ini)', 'required');
        $this->form_validation->set_rules('member_village', 'Desa/Kelurahan (saat ini)', 'required');
        $this->form_validation->set_rules('member_address', 'Alamat (saat ini)', 'required');
        $this->form_validation->set_rules('member_postcode', 'Kode POS (saat ini)', 'required');

        $this->form_validation->set_rules('member_bank', 'Bank', 'required');
        $this->form_validation->set_rules('member_bill', 'Nomor Rekening Bank', 'required');
        $this->form_validation->set_rules('member_bank_branch', 'Cabang Bank', 'required');
        $this->form_validation->set_rules('member_city_code', 'Kode Kabupaten/Kota', 'required');
        // $this->form_validation->set_rules('member_bill_name','Nama Pemilik Rekening Bank','required');

        $this->form_validation->set_rules('member_emergency_name', 'Emergency Contact Name', 'required');
        $this->form_validation->set_rules('member_emergency_relationship', 'Emergency Contact Relationship', 'required');
        $this->form_validation->set_rules('member_emergency_phone', 'Emergency Contact Phone', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data['message'] = 'Anda memiliki beberapa kesalahan.' . br() . validation_errors();
            die(json_encode($data));
        }

        if ( substr($post_member_phone, 0, 1) != '0' ) {
            $post_member_phone = '0'. $post_member_phone;
        }
        
        if ( substr($post_emergency_phone, 0, 1) != '0' ) {
            $post_emergency_phone = '0'. $post_emergency_phone;
        }

        if ($post_npwp == '__.___.___._-___.___') {
            $post_npwp      = '';
        }

        if ($post_npwp == '00.000.000.0-000.000') {
            $post_npwp      = '';
        }

        $post_npwp          = str_replace('_', '', $post_npwp);
        if (strlen($post_npwp) != 20) {
            $post_npwp      = '';
        }

        $curdate            = date("Y-m-d H:i:s");
        $member_name        = $post_member_name ? $post_member_name : $memberdata->name;
        $dateofbirth        = $post_dob_year . '-' . $post_dob_month . '-' . $post_dob_date;
        $dataupdate         = array(
            'name'          => strtoupper(trim($member_name)),
            'email'         => strtolower(trim($post_member_email)),
            'phone'         => $post_member_phone,
            'phone_home'    => $post_phone_home,
            'phone_office'  => $post_phone_office,
            // 'pob'           => $post_pob,
            // 'dob'           => $dateofbirth,
            // 'gender'        => $post_gender,
            // 'marital'       => $post_marital,
            'idcard_type'   => $post_idcard_type,
            'idcard'        => $post_idcard,
            'npwp'          => $post_npwp,
            'wd_status'     => $post_wd_status,
            'datemodified'  => $curdate,
        );

        if ($post_province) {
            $dataupdate['province']         = $post_province;
        }
        if ($post_district) {
            $dataupdate['district']         = $post_district;
        }
        if ($post_subdistrict) {
            $dataupdate['subdistrict']      = $post_subdistrict;
        }
        if ($post_village) {
            $dataupdate['village']          = $post_village;
        }
        if ($post_address) {
            $dataupdate['address']          = trim($post_address);
        }
        if ($post_postcode) {
            $dataupdate['postcode']         = trim($post_postcode);
        }

        if ($post_member_bank) {
            $dataupdate['bank']             = $post_member_bank;
        }
        if ($post_member_bill) {
            $dataupdate['bill']             = trim($post_member_bill);
        }
        if ($post_member_bill_name) {
            $dataupdate['bill_name']        = strtoupper(trim($post_member_bill_name));
        }
        if ($post_member_branch) {
            $dataupdate['branch']           = strtoupper(trim($post_member_branch));
        }
        if ($post_member_city_code) {
            $dataupdate['city_code']        = $post_member_city_code;
        }

        if ($post_emergency_name) {
            $dataupdate['emergency_name']   = $post_emergency_name;
        }
        if ($post_emergency_phone) {
            $dataupdate['emergency_phone']  = $post_emergency_phone;
        }
        if ($post_emergency_relationship) {
            $dataupdate['emergency_relationship'] = $post_emergency_relationship;
        }
        
        if ($post_facebook_url) {
            $dataupdate['facebook_url']     = $post_facebook_url;
        }
        if ($post_twitter_url) {
            $dataupdate['twitter_url']      = $post_twitter_url;
        }
        if ($post_instagram_url) {
            $dataupdate['instagram_url']    = $post_instagram_url;
        }
        if ($post_tiktok_url) {
            $dataupdate['tiktok_url']       = $post_tiktok_url;
        }

        if ($save_member    = $this->Model_Member->update_data($id_member, $dataupdate)) {
            an_log_action('CHANGE_PROFILE', 'SUCCESS', $memberdata->username, json_encode(array('cookie' => $_COOKIE, 'id_member' => $id_member, 'member' => $memberdata, 'member_update' => $dataupdate, 'update_by' => $current_member->username)));

            // Set Message
            $msg = ($id_member != $current_member->id ? 'Data profil <strong>(' . $memberdata->username . ')</strong> sudah tersimpan.' : 'Data profil Anda sudah tersimpan.');

            $data['status']  = 'success';
            $data['message'] = 'Edit profile berhasil! ' . br() . $msg;
        } else {
            $data['message'] = 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!';
        }

        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Profile Admin Info Update function.
     */
    function admininfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$is_admin) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        $post_name       = $this->input->post('member_name');
        $post_name       = an_isset($post_name, '', '', true);
        $post_phone      = $this->input->post('member_phone');
        $post_phone      = an_isset($post_phone, '', '', true);
        $post_email      = $this->input->post('member_email');
        $post_email      = an_isset($post_email, '', '', true);

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Anda memiliki beberapa kesalahan ( ' . validation_errors() . '). Silakan cek di formulir bawah ini!',
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $curdate            = date("Y-m-d H:i:s");
            $name               = $post_name ? $post_name : $current_member->name;
            $dataupdate         = array(
                // 'username'      => trim($post_member_username),
                'name'          => strtoupper(trim($name)),
                'email'         => strtolower(trim($post_email)),
                'phone'         => $post_phone,
                'datemodified'  => $curdate,
            );

            if ($save_member    = $this->Model_Member->update_data($current_member->id, $dataupdate)) {
                an_log_action('CHANGE_PROFILE_STAFF', 'SUCCESS', $current_member->username, json_encode(array('cookie' => $_COOKIE, 'id_member' => $current_member->id, 'memberdata' => $current_member, 'data_update' => $dataupdate, 'update_by' => $current_member->username)));

                $data = array(
                    'status'    => 'success',
                    'message'   => 'Data profil Anda sudah tersimpan.',
                );
            } else {
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!',
                );
            }

            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Profile Staff Info Update function.
     */
    function staffinfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_staff          = an_get_current_staff();
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$current_staff) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data Staff tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        if (!$is_admin) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        $post_name       = $this->input->post('member_name');
        $post_name       = an_isset($post_name, '', '', true);
        $post_phone      = $this->input->post('member_phone');
        $post_phone      = an_isset($post_phone, '', '', true);
        $post_email      = $this->input->post('member_email');
        $post_email      = an_isset($post_email, '', '', true);

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Anda memiliki beberapa kesalahan ( ' . validation_errors() . '). Silakan cek di formulir bawah ini!',
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $curdate            = date("Y-m-d H:i:s");
            $name               = $post_name ? $post_name : $current_staff->name;
            $dataupdate         = array(
                // 'username'      => trim($post_member_username),
                'name'          => strtoupper(trim($name)),
                'email'         => strtolower(trim($post_email)),
                'phone'         => $post_phone,
                'access'        => $current_staff->access,
                'role'          => $current_staff->role,
                'datemodified'  => $curdate,
            );

            if ($save_member    = $this->Model_Staff->update($current_staff->id, $dataupdate)) {
                an_log_action('CHANGE_PROFILE_STAFF', 'SUCCESS', $current_staff->username, json_encode(array('cookie' => $_COOKIE, 'id_staff' => $current_staff->id, 'staff' => $current_staff, 'staff_update' => $dataupdate, 'update_by' => $current_staff->username)));

                $data = array(
                    'status'    => 'success',
                    'message'   => 'Data profil Anda sudah tersimpan.',
                );
            } else {
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!',
                );
            }

            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Change Password function.
     */
    function changepassword()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $post_id_member_other   = $this->input->post('id_member_other');
        $post_username_other    = $this->input->post('username_other');
        $pass_type              = $this->input->post('pass_type');
        $pass_type              = an_isset($pass_type, 'login', 'login');

        if (an_isset($post_id_member_other, '', '', true) != '') {
            $id_member          = trim(an_isset($post_id_member_other, '', '', true));
            $username           = trim(an_isset($post_username_other, '', '', true));

            $memberdata         = an_get_memberdata_by_id($id_member);
            if (!$memberdata || empty($memberdata)) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Data anggota <strong>' . $username . '</strong> tidak ditemukan!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            if (!$$is_admin) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Maaf, Anda tidak mempunyai akses untuk edit password anggota!'),
                ); die(json_encode($data));
            }

            $access = TRUE;
            if ($staff = an_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS2) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }

            if (!$access) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Maaf, Anda tidak mempunyai akses untuk edit password anggota!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            if ($pass_type == 'pin') {
                $post_new_pass      = $this->input->post('new_pass_pin');
                $post_cnew_pass     = $this->input->post('cnew_pass_pin');
                $this->form_validation->set_rules('new_pass_pin', 'Pasword Baru', 'required');
                $this->form_validation->set_rules('cnew_pass_pin', 'Konfirmasi Password Baru', 'required');
            } else {
                $post_new_pass      = $this->input->post('new_pass');
                $post_cnew_pass     = $this->input->post('cnew_pass');
                $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
                $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');
            }

            $new_pass           = an_isset($post_new_pass, '', '', true);
            $cnew_pass          = an_isset($post_cnew_pass, '', '', true);

            $this->form_validation->set_message('required', '%s harus di isi');
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
                );
                // JSON encode data
                die(json_encode($data));
            }

            if ($new_pass != $cnew_pass) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Konfirmasi password tidak sesuai dengan password baru!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            // $global_pass        = get_option('global_password');
            // $new_pass           = strtolower($new_pass);
            $new_pass           = trim($new_pass);
            $password           = an_password_hash($new_pass);
            $curdate            = date("Y-m-d H:i:s");

            $passdata['datemodified'] = $curdate;
            if ($pass_type == 'pin') {
                $passdata['password_pin']   = $password;
            } else {
                $passdata['password']       = $password;
            }

            if ($save_pass      = $this->Model_Member->update_data($id_member, $passdata)) {
                an_log_action('CHANGE_PASSWORD_BY_ADMIN', $username, $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'username' => $username, 'password' => $new_pass, 'password_type' => $pass_type, 'updated_by' => $current_member->username)));

                $type_password      = ($pass_type == 'pin') ? 'Transfer PIN' : 'Login';
                $data_notif         = array(
                    'password'      => $new_pass,
                    'type_password' => $type_password
                );

                // Send Notif Email
                $this->an_email->send_email_reset_password($memberdata, $data_notif);
                // Send Notif WA
                $this->an_wa->send_wa_reset_password( $memberdata, $data_notif );

                // Set JSON data
                $data = array(
                    'message'   => 'success',
                    'access'    => 'admin',
                    'data'      => an_alert('Reset/Atur ulang password anggota <strong>' . $username . '</strong> berhasil!'),
                );
            } else {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => an_alert('Reset/Atur ulang password anggota <strong>' . $username . '</strong> tidak berhasil!'),
                );
            }
            // JSON encode data
            die(json_encode($data));
        }

        if ($pass_type == 'pin') {
            $post_cur_pass      = $this->input->post('cur_pass_pin');
            $post_new_pass      = $this->input->post('new_pass_pin');
            $post_cnew_pass     = $this->input->post('cnew_pass_pin');
            if (!$is_admin) {
                $this->form_validation->set_rules('cur_pass_pin', 'Password Lama', 'required');
            }
            $this->form_validation->set_rules('new_pass_pin', 'Pasword Baru', 'required');
            $this->form_validation->set_rules('cnew_pass_pin', 'Konfirmasi Password Baru', 'required');
        } else {
            $post_cur_pass      = $this->input->post('cur_pass');
            $post_new_pass      = $this->input->post('new_pass');
            $post_cnew_pass     = $this->input->post('cnew_pass');
            if (!$is_admin) {
                $this->form_validation->set_rules('cur_pass', 'Password Lama', 'required');
            }
            $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
            $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => ('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $cur_pass       = an_isset($post_cur_pass, '', '', true);
            $new_pass       = an_isset($post_new_pass, '', '', true);
            $new_pass_sms   = an_isset($post_new_pass, '', '', true);
            $cnew_pass      = an_isset($post_cnew_pass, '', '', true);

            // Check Member Password
            if ($pass_type == 'pin') {
                $check_pass = FALSE;
                if (an_hash_verify($cur_pass, $current_member->password_pin)) {
                    $check_pass = TRUE;
                }
            } else {
                $check_pass     = $this->Model_Auth->authenticate($current_member->username, $cur_pass);
            }

            if (!$check_pass && !$is_admin) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ('Password lama yang anda masukkan salah!'),
                );
                // JSON encode data
                die(json_encode($data));
            } else {

                if ( $cur_pass == $new_pass ) {
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => ('Password baru tidak boleh sama dengan password anda saat ini !'),
                    ); die(json_encode($data));
                }

                if ($new_pass != $cnew_pass) {
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => ('Konfirmasi password tidak sesuai dengan password baru!'),
                    );
                    // JSON encode data
                    die(json_encode($data));
                } else {
                    // $new_pass           = strtolower($new_pass);
                    $new_pass           = trim($new_pass);
                    $password           = an_password_hash($new_pass);
                    $curdate            = date("Y-m-d H:i:s");

                    $passdata['datemodified']       = $curdate;
                    $passdata['change_password']    = $current_member->change_password + 1;

                    if ($pass_type == 'pin') {
                        $passdata['password_pin']   = $password;
                    } else {
                        $passdata['password']       = $password;
                    }

                    if ($save_pass      = $this->Model_Member->update_data($current_member->id, $passdata)) {
                        an_log_action('CHANGE_PASSWORD', 'SUCCESS', $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'password' => $new_pass, 'password_type' => $pass_type)));

                        $type_password      = ($pass_type == 'pin') ? 'Transfer PIN/Produk' : 'Login';
                        $data_notif         = array(
                            'password'      => $new_pass,
                            'type_password' => $type_password
                        );

                        // Send Notif Email
                        $this->an_email->send_email_change_password($current_member, $data_notif);
                        // Send Notif WA
                        $this->an_wa->send_wa_change_password( $current_member, $data_notif );

                        if ($pass_type == 'pin') {
                            // Set JSON data
                            $data = array(
                                'message'   => 'success',
                                'access'    => 'admin',
                                'data'      => ('Reset/Atur ulang password berhasil!'),
                            );
                        } else {

                            $credentials['username']    = $current_member->username;
                            $credentials['password']    = $new_pass;
                            $credentials['remember']    = '';

                            // Logout
                            an_logout();

                            // Sign On member
                            $time           = time();
                            $membersignon   = $this->Model_Auth->signon($credentials, $time);
                            $member         = $this->an_member->member($membersignon->id);
                            $last_activity  = date('Y-m-d H:i:s', $time);

                            // Set session data
                            $session_data   = array(
                                'id'            => $member->id,
                                'username'      => $member->username,
                                'name'          => $member->name,
                                'email'         => $member->email,
                                'last_login'    => $last_activity
                            );

                            // Set session
                            $this->session->set_userdata('member_logged_in', $session_data);

                            // Set cookie domain
                            $cookie_domain  = str_replace(array('http://', 'https://', 'www.'), '', base_url());
                            $cookie_domain  = '.' . str_replace('/', '', $cookie_domain);
                            $expire         = 0;
                            // Set cookie data
                            $cookie         = array(
                                'name'      => 'logged_in_' . md5('nonssl'),
                                'value'     => $member->id,
                                'expire'    => $expire,
                                'domain'    => $cookie_domain,
                                'path'      => '/',
                                'secure'    => false,
                            );
                            // set cookie
                            setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure']);

                            // Save Auth Session
                            an_set_auth_session($current_member->username, $membersignon, '', '', $time);

                            // Set JSON data
                            $data = array(
                                'message'   => 'success',
                                'access'    => 'admin',
                                'data'      => ('Reset/Atur ulang password berhasil!'),
                                // 'access'    => 'member',
                                // 'data'      => base_url('login'),
                            );
                        }
                    } else {
                        // Set JSON data
                        $data = array(
                            'message'   => 'error',
                            'data'      => ('Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!'),
                        );
                    }
                    // JSON encode data
                    die(json_encode($data));
                }
            }
        }
    }

    /**
     * Change Password Staff function.
     */
    function changepasswordstaff()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $current_staff          = an_get_current_staff();
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$current_staff) {
            // Set JSON data
            $data = array('message' => 'error', 'data' => 'Data Staff tidak ditemukan atau belum terdaftar');
            die(json_encode($data));
        }

        if (!$is_admin) {
            // Set JSON data
            $data = array('message' => 'error', 'data' => 'Data tidak ditemukan atau belum terdaftar');
            die(json_encode($data));
        }

        $post_cur_pass      = $this->input->post('cur_pass');
        $post_new_pass      = $this->input->post('new_pass');
        $post_cnew_pass     = $this->input->post('cnew_pass');

        $this->form_validation->set_rules('cur_pass', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
        $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => ('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
            );
            die(json_encode($data));
        } else {

            $cur_pass       = an_isset($post_cur_pass, '', '', true);
            $new_pass       = an_isset($post_new_pass, '', '', true);
            $cnew_pass      = an_isset($post_cnew_pass, '', '', true);

            // Check Member Password
            $check_pass     = FALSE;
            if (an_hash_verify($cur_pass, $current_staff->password)) {
                $check_pass = TRUE;
            }

            if (!$check_pass) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ('Password lama yang anda masukkan salah!'),
                );
                die(json_encode($data));
            } else {
                if ($new_pass != $cnew_pass) {
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => ('Konfirmasi password tidak sesuai dengan password baru!'),
                    );
                    die(json_encode($data));
                } else {
                    $new_pass           = trim($new_pass);
                    $password           = an_password_hash($new_pass);
                    $curdate            = date("Y-m-d H:i:s");

                    $staff_data = array(
                        'username'      => $current_staff->username,
                        'password'      => $password,
                        'name'          => $current_staff->name,
                        'email'         => $current_staff->email,
                        'access'        => $current_staff->access,
                        'role'          => $current_staff->role,
                        'datecreated'   => $current_staff->datecreated,
                        'datemodified'  => $curdate,
                    );

                    if ($this->Model_Staff->update($current_staff->id, $staff_data)) {
                        // Set JSON data
                        $data = array(
                            'message'   => 'success',
                            'access'    => 'admin',
                            'data'      => ('Reset/Atur ulang password berhasil!'),
                        );

                        an_log_action('CHANGE_PASSWORD_STAFF', 'SUCCESS', $current_staff->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'password' => $new_pass)));

                        $data_notif         = array(
                            'password'      => $new_pass,
                            'type_password' => 'Login'
                        );

                        // Send Notif Email
                        $this->an_email->send_email_change_password($current_staff, $data_notif);
                        // Send Notif WA
                        $this->an_wa->send_wa_reset_password_by_member( $current_member, $data_wa );

                    } else {
                        // Set JSON data
                        $data = array(
                            'message'   => 'error',
                            'data'      => ('Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!'),
                        );
                    }
                    // JSON encode data
                    die(json_encode($data));
                }
            }
        }
    }

    // ------------------------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------------------------
    // Member Function
    // ------------------------------------------------------------------------------------------------

    function export()
    {
        $this->load->library('an_XLS');
        $export                         = $this->an_xls->simpleInit();
    }

    // =============================================================================================
    // GENERAL MEMBER
    // =============================================================================================

    /**
     * Member Generation function.
     */
    function generationtree($id = '', $offset = 0, $limit = 0)
    {
        auth_redirect();

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $data               = array();
        $my_gen             = an_my_gen_sponsor($current_member->id);

        if ($id > 0) {
            $conditions         = ' WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE . ' AND %sponsor% = ' . $id;
            $member_list        = $this->Model_Member->get_all_member_data($limit, $offset, $conditions, '%id% ASC');
            foreach ($member_list as $member) {
                $child          = $this->Model_Member->count_by_sponsor($member->id, false);
                $member_gen     = an_my_gen_sponsor($member->id);
                $gen            = $member_gen - $my_gen;

                $data[]         = array(
                    'id'        => $member->id,
                    'text'      => $this->generationtree_text($member, $gen),
                    'children'  => $child ? TRUE : FALSE
                );
            }
        } else {
            $child          = $this->Model_Member->count_by_sponsor($current_member->id, false);
            $data[]         = array(
                'id'        => $current_member->id,
                'text'      => $this->generationtree_text($current_member, 0),
                'children'  => $child ? TRUE : FALSE
            );
        }

        echo json_encode($data);
    }

    /**
     * Get generation tree text function
     */
    private function generationtree_text($member, $gen = 0, $childs = 0)
    {

        $is_admin = as_administrator($member);
        $username = $is_admin ? 'ROOT' : $member->username;

        return '
            <strong style="font-size:13px">' . strtoupper($member->name) . '</strong>
            <small>(' . $username . ')</small> ' .
            ($gen ? ' <span class="label bg-yellow">Gen-' . $gen . '</span>' : '<span class="label bg-blue">Anda</span>') .
            ($childs ? ' <span class="badge bg-green">' . $childs . '</span>' : '');
    }

    /**
     * Clone Member Data function.
     */
    function cloning($clone_code = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url('member/tree'), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'    => 'login',
                'message'   => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $acccess            = $this->input->post('acccess');
        $acccess            = an_isset($acccess, '', '', true);
        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $username           = strtolower($username);
        $clone_code         = $clone_code ? an_decrypt($clone_code) : '';

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username tidak ditemukan.');

        if ( empty($username) ) {
            $data['message'] = 'Username tidak boleh kosong. Silahkan inputkan Username !';
            die(json_encode($data));
        }

        if ( $clone_code != 'newmember' ) {
            $data['message'] = 'Kode Clone tidak valid !';
            die(json_encode($data));
        }

        $member             = $this->Model_Member->get_member_by('login', $username);
        if (!$member) {
            die(json_encode($data));
        }

        // Check If Member is Downline
        // -------------------------------------------------
        if ( !$is_admin ) {
            if ( $member->id != $current_member->id ) {
                $data['message'] = 'Clone data hanya bisa dilakukan dari data akun Anda sendiri !';
                die(json_encode($data));
            }

            $is_downline        = $this->Model_Member->get_is_downline($member->id, $current_member->tree);
            if (!$is_downline) {
                $data['message'] = 'Username ini bukan jaringan Anda. Clone data hanya bisa dilakukan dari jaringan Anda!';
                die(json_encode($data));
            }
        }

        $id_member          = $member->id;
        $memberdata         = an_unset_clone_member_data($member);

        $provincetext       = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_provinsi') .' --</option>';;
        $provinces          = an_provinces();
        if (!empty($provinces)) {
            foreach ($provinces as $province) {
                $provincetext .= '<option value="' . $province->id . '">' . $province->province_name . '</option>';
            }
        }
        $memberdata->opt_provinces = $provincetext;

        $citytext           = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kota') .' --</option>';;
        $cities             = an_districts_by_province($memberdata->province);
        if (!empty($cities)) {
            foreach ($cities as $city) {
                $citytext  .= '<option value="' . $city->id . '">' . $city->district_type .' '. $city->district_name . '</option>';
            }
        }
        $memberdata->opt_districts = $citytext;

        $subdistricttext    = '<option value="" selected="">-- '. lang('reg_pilih_kecamatan') .' --</option>';
        $subdistricts       = an_subdistricts_by_district($memberdata->district);
        if (!empty($subdistricts)) {
            foreach ($subdistricts as $subdistrict) {
                $subdistricttext .= '<option value="' . $subdistrict->id . '">' . $subdistrict->subdistrict_name . '</option>';
            }
        }
        $memberdata->opt_subdistricts  = $subdistricttext;

        // Set JSON data
        $data['status']     = 'success';
        $data['message']    = 'Username ditemukan dan data sudah di clone';
        $data['data']       = $memberdata;
        // JSON encode data
        die(json_encode($data));
    }

    // =============================================================================================
    // SEARCH MEMBER
    // =============================================================================================

    /**
     * Search Tree Member function.
     */
    function searchtree()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url('member/tree'), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'    => 'login',
                'message'   => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $username           = strtolower($username);

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username tidak ditemukan.');

        if ( !$username ) {
            $data['message'] = 'Username harus di isi !';
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if ( !$memberdata ) {
            die(json_encode($data));
        }

        $is_downline        = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
        if ( !$is_downline ) {
            $data['message'] = 'Username ini bukan jaringan Anda.';
            die(json_encode($data));
        }

        if ( $memberdata->id <= 8 ) {
            if ( $staff = an_get_current_staff() ) {
                if ( $staff->access == 'partial') {
                    $data['message'] = 'Maaf, anda tidak dapat melihat jaringan Username ini !';
                    die(json_encode($data));
                }
            }
        }

        $id_member          = an_encrypt($memberdata->id);
        $data['status']     = 'success';
        $data['message']    = 'Username ditemukan';
        $data['direct']     = base_url('member/tree/' . $id_member);

        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search Board Tree Member function.
     */
    function searchboardtree($board = 1)
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url('dashboard'), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'    => 'login',
                'message'   => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $username           = strtolower($username);
        $board              = $board ? $board : 1;
        $board              = is_numeric($board) ? $board : 1;

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username tidak ditemukan.');

        if ( !$username ) {
            $data['message'] = 'Username harus di isi !';
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if ( !$memberdata ) {
            die(json_encode($data));
        }

        $id_member          = an_encrypt($memberdata->id);
        $link_board_tree    = base_url('board/tree'.$board .'/' . $id_member);

        $memberboard        = an_get_memberboard_by('id_member', $memberdata->id, array('board' => $board, 'status' => 1), 1);
        if ( !$memberboard ) {
            $memberboard    = an_get_memberboard_by('id_member', $memberdata->id, array('board' => $board, 'status' => 2), 1);
        }
        
        if ( $memberboard ) {
            $link_board_tree .= '/'.an_encrypt($memberboard->id);
        }

        $data['status']     = 'success';
        $data['message']    = 'Username ditemukan';
        $data['direct']     = $link_board_tree;

        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search Upline Group function.
     */
    function searchuplinetree()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url(), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'    => 'login',
                'message'   => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id_parent          = $this->input->post('id_parent');
        $id_parent          = an_isset($id_parent, 0, '', true);
        $position           = $this->input->post('position');
        $position           = an_isset($position, '', '', true);
        $info               = '';

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Upline tidak ditemukan.');

        if ( !$id_parent ) { die(json_encode($data)); }

        $id_parent          = an_decrypt($id_parent);
        $memberdata         = an_get_memberdata_by_id($id_parent);
        if ( !$memberdata ) { die(json_encode($data)); }

        $node               = ( $position == POS_LEFT ) ? lang(POS_LEFT) : lang(POS_RIGHT);

        if (!$is_admin) {
            $is_down = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);

            if (!$is_down) {
                $data['message'] = 'Upline ini bukan jaringan Anda!';
                die(json_encode($data));
            }
        }

        $info              .= '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("reg_upline_username") . ' &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                    </div>
                    <input type="text" name="reg_member_upline" id="reg_member_upline" class="form-control text-lowercase" readonly="" value="' . ($memberdata->username) . '" />
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("name") . ' Upline &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="hidden" name="reg_member_upline_id" class="form-control" value="' . $memberdata->id . '" />
                    <input type="hidden" name="reg_member_upline_username" class="form-control" value="' . strtolower($memberdata->username) . '" />
                    <input type="text" name="reg_member_upline_name_dsb" class="form-control" placeholder="Upline" disabled="" value="' . strtoupper($memberdata->name) . '" />
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-md-3  col-form-label form-control-label">' . lang("position") . ' &nbsp;</label>
            <input type="hidden" name="reg_member_position" class="form-control" value="' . $position . '" />
            <div class="col-md-9">
                <input type="text" name="reg_member_position_dsb" class="form-control" placeholder="Posisi Anggota" disabled="" value="' . strtoupper($node) . '" />
            </div>
        </div>';

        // Set JSON data
        $data['status']     = 'success';
        $data['message']    = '';
        $data['info']       = $info;
        die(json_encode($data));
    }

    /**
     * Search member data function.
     */
    function searchmemberdata( $id_member = 0, $status_member = '' )
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = an_isset($username,'', '', true);
        $type                   = $this->input->post('type');
        $type                   = an_isset($type, $status_member, $status_member, true);
        $status                 = 'error';
        $message                = 'Data Mitra tidak ditemukan.';
        $memberdata             = '';
        $an_token               = $this->security->get_csrf_hash();

        if( !empty($username) ){
            $memberdata         = $this->Model_Member->get_member_by('login', $username);

            if( !$memberdata ) {
                $status         = 'error';
                $message        = 'Data Username '.$type.' tidak ditemukan atau belum terdaftar!';
            }else{

                if( as_administrator($memberdata) ){
                    // Set JSON data
                    $data = array(
                        'status'    => 'error',
                        'token'     => $an_token,
                        'message'   => 'Admin tidak dapat dijadikan sebagai '.ucfirst($type).'. Silahkan masukkan username '.ucfirst($type).' lainnya',
                    );
                    // JSON encode data
                    die(json_encode($data));
                }

                $status     = 'available';
                $message   .= '
                <div class="form-group">
                    <label class="control-label">'.ucfirst($type).' Name</label>
                    <input type="hidden" name="reg_member_'.$type.'_id" class="form-control" value="'.$memberdata->id.'" />
                    <input type="text" name="reg_member_'.$type.'_name_dsb" class="form-control" placeholder="NAMA '.ucwords($type).'" disabled="" value="'.strtoupper($memberdata->name).'" />
                </div>';
            }
        }else{
            if ( $id_member ) {
                $id = an_decrypt($id_member);
                if ( $getmemberdata = an_get_memberdata_by_id($id) ) {
                    $id_province    = ($getmemberdata->province_stockist) ? $getmemberdata->province_stockist : $getmemberdata->province;
                    $id_district    = ($getmemberdata->district_stockist) ? $getmemberdata->district_stockist : $getmemberdata->district;
                    $id_subdistrict = ($getmemberdata->subdistrict_stockist) ? $getmemberdata->subdistrict_stockist : $getmemberdata->subdistrict;
                    $village        = ($getmemberdata->village_stockist) ? $getmemberdata->village_stockist : $getmemberdata->village;
                    $address        = ($getmemberdata->address_stockist) ? $getmemberdata->address_stockist : $getmemberdata->address;

                    $districts      = an_districts_by_province($id_province);
                    $subdistricts   = an_subdistricts_by_district($id_district);

                    $opt_district   = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kota') .' --</option>';
                    if ( $districts ) {
                        foreach($districts as $city){
                            $opt_district .= '<option value="'.$city->id.'">'. ucwords(strtolower($city->district_name)) .'</option>';
                        }
                    }

                    $opt_subdistrict = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kecamatan') .' --</option>';
                    if ( $subdistricts ) {
                        foreach($subdistricts as $subdistrict){
                            $opt_subdistrict .= '<option value="'.$subdistrict->id.'">'. ucwords(strtolower($subdistrict->subdistrict_name)) .'</option>';
                        }
                    }

                    $status         = 'success';
                    $message        = 'Data Mitra ditemukan.';
                    $memberdata     = array(
                        'id'                => $id_member,
                        'username'          => $getmemberdata->username,
                        'name'              => $getmemberdata->name,
                        'package'           => $getmemberdata->package,
                        'status_member'     => $getmemberdata->as_stockist,
                        'id_province'       => $id_province,
                        'id_district'       => $id_district,
                        'id_subdistrict'    => $id_subdistrict,
                        'village'           => $village,
                        'address'           => $address,
                        'opt_district'      => $opt_district,
                        'opt_subdistrict'   => $opt_subdistrict
                    );
                }
            } else {
                $status     = 'error';
                $message    = 'Username '.$type.' tidak boleh kosong. Silahkan masukkan Username '.$type.'!';
            }
        }

        // Set JSON data
        $data = array(
            'status'    => $status,
            'token'     => $an_token,
            'message'   => $message,
            'member'    => $memberdata
        );
        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search Upline function.
     */
    function searchupline()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url(), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'    => 'login',
                'message'   => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $info               = '';

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username tidak ditemukan.');

        if ( empty($username) ) {
            $data['message'] = 'Username Upline harus di isi !';
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if ( !$memberdata ) {
            $data['message'] = 'Username tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ($memberdata->status == 0) {
            $data['message'] = 'Username tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ( !$is_admin ) {
            $is_down        = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
            if (!$is_down) {
                $data['message'] = 'Username upline ini tidak berada di jaringan Anda! Silahkan ketik Username lain !';
                die(json_encode($data));
            }
        }
        
        $status         = 'available';
        $message        = 'Data dari upline ini ditemukan, Anda dapat mengisi formulir pendaftaran anggota baru.';
        $info          .= '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("name") . ' Upline &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="hidden" name="reg_member_upline_id" class="form-control" value="' . $memberdata->id . '" />
                    <input type="hidden" name="reg_member_upline_username" class="form-control" value="' . strtolower($memberdata->username) . '" />
                    <input type="text" name="reg_member_upline_name_dsb" class="form-control" placeholder="Upline" disabled="" value="' . strtoupper($memberdata->name) . '" />
                </div>
            </div>
        </div>';

        // Set JSON data
        $data['status']     = $status;
        $data['message']    = $message;
        $data['info']       = $info;
        die(json_encode($data));
    }

    /**
     * Search Sponsor function.
     */
    function searchsponsor()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url(), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'        => 'login',
                'message'       => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = strtolower(an_isset($username, '', '', true));
        $upline             = $this->input->post('upline');
        $upline             = strtolower(an_isset($upline, '', '', true));
        $info               = '';

        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username tidak ditemukan.');

        if (empty($username)) {
            $data['message'] = 'Username Sponsor harus di isi !';
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata || empty($memberdata)) {
            $data['message'] = 'Username Sponsor tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ($memberdata->status == 0) {
            $data['message'] = 'Username Sponsor tidak ditemukan atau belum terdaftar !';
            die(json_encode($data));
        }

        if ($memberdata->status > 1) {
            $data['message'] = 'Username Sponsor tersebut di banned. Silahkan ketik Username Sponsor lain !';
            die(json_encode($data));
        }

        if ( $upline && $upline != $username ) {
            $uplinedata     = $this->Model_Member->get_member_by('login', $upline);
            if ( !$uplinedata ) {
                $data['message'] = 'Username Upline tidak ditemukan atau belum terdaftar !';
                die(json_encode($data));
            }

            $is_down        = $this->Model_Member->get_is_downline($uplinedata->id, $memberdata->tree);
            if (!$is_down) {
                $data['message'] = 'Username Sponsor ini tidak berada di jaringan Upline. Silahkan ketik Username lain !';
                die(json_encode($data));
            }
        } else {
            if (!$is_admin) {
                $is_down    = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
                if (!$is_down) {
                    $data['message'] = 'Username Sponsor ini tidak berada di jaringan Anda. Silahkan ketik Username lain !';
                    die(json_encode($data));
                }
            }
        }

        $info      .= '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("name") . ' Sponsor &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="hidden" name="reg_member_sponsor_id" class="form-control" value="' . $memberdata->id . '" />
                    <input type="hidden" name="reg_member_sponsor_username" class="form-control" value="' . strtolower($memberdata->username) . '" />
                    <input type="text" name="reg_member_sponsor_name_dsb" class="form-control" placeholder="Nama Sponsor" disabled="" value="' . strtoupper($memberdata->name) . '" />
                </div>
            </div>
        </div>';

        // Set JSON data
        $data['status']     = 'success';
        $data['message']    = 'Data anggota berhasil ditemukan. Silahkan cek hasil data anggota pada formulir dibawah.';
        $data['info']       = $info;
        die(json_encode($data));
    }

    /**
     * Search Member function.
     */
    function searchmember()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) { redirect(base_url('/'), 'location'); }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $type               = $this->input->post('type');
        $type               = an_isset($type, '', '', true);
        $form               = $this->input->post('form');
        $form               = an_isset($form, '', '', true);
        $info_html          = '';
        $info_data          = '';
        
        $an_token           = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'Username belum di isi. Silahkan input username member !');

        if( !empty($username) ){
            $memberdata     = $this->Model_Auth->get_user_by('login', strtolower($username));
            if( !$memberdata ) {
                $data['message'] = 'Username tidak ditemukan atau belum terdaftar';
                die(json_encode($data));
            }elseif( $memberdata->status != ACTIVE ) {
                $data['message'] = 'Member sudah tidak aktif';
                die(json_encode($data));
            }else{
                $member_admin   = as_administrator($memberdata);
                if( $member_admin ){
                    $data['message'] = 'Username tidak ditemukan';
                    die(json_encode($data));
                }

                if( strtolower($form) == 'transfer' ){
                    if($current_member->id == $memberdata->id){
                        $data['message'] = 'Anda tidak dapat men-transfer kepada akun Anda sendiri !';
                        die(json_encode($data));
                    }

                    if ( ! $is_admin && $current_member->as_stockist == 0 ) {
                        // -------------------------------------------------
                        // Check If Sponsor is Downline
                        // -------------------------------------------------
                        $is_downline        = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
                        if( !$is_downline ){
                            $data['message'] = 'Username ini bukan jaringan Anda! Silahkan masukkan Username lain!';
                            die(json_encode($data));
                        }
                    }
                }

                $form_stockist  = array('pin_generate', 'member_loan');
                if( in_array(strtolower($form), $form_stockist) ){
                    if ( $memberdata->as_stockist == 0 ) {
                        $data['message'] = 'Username ini bukan Stockist! Silahkan masukkan Username lain!';
                        die(json_encode($data));
                    }
                }

                $data['status']     = 'success';
                $data['message']    = 'Data Member ini ditemukan. Anda dapat melanjutkan proses pengisian formulir';

                // Set Data Response
                $info_data          = array(
                    'member'        => an_encrypt($memberdata->id),
                    'username'      => $memberdata->username,
                    'name'          => $memberdata->name
                );

                if ( strtolower($form) == 'member_loan' ) {
                    $deposite       = $this->Model_Member->get_loan_total($memberdata->id, 'deposite');
                    $withdraw       = $this->Model_Member->get_loan_total($memberdata->id, 'withdraw');
                    $saldo_deposite = $deposite - $withdraw;
                    $info_data['deposite'] = $deposite;
                    $info_data['withdraw'] = $withdraw;
                    $info_data['saldo_deposite'] = $saldo_deposite;
                }

                if ( strtolower($type) == 'html' ) {
                    $info_html     .= '
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label">'.lang("name").' </label>
                        <div class="col-md-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="hidden" name="member_code" class="form-control" value="'. an_encrypt($memberdata->id) .'" />
                                <input type="hidden" name="member_username" class="form-control" value="'. $memberdata->username .'" />
                                <input type="text" name="member_name" class="form-control" disabled="" value="'.strtoupper($memberdata->name).'" />
                            </div>
                        </div>
                    </div>';

                    if ( strtolower($form) == 'pin_generate' ) {
                        $id_province    = ($memberdata->province_stockist) ? $memberdata->province_stockist : $memberdata->province;
                        $id_district    = ($memberdata->district_stockist) ? $memberdata->district_stockist : $memberdata->district;
                        $id_subdistrict = ($memberdata->subdistrict_stockist) ? $memberdata->subdistrict_stockist : $memberdata->subdistrict;
                        $village        = ($memberdata->village_stockist) ? $memberdata->village_stockist : $memberdata->village;
                        $address        = ($memberdata->address_stockist) ? $memberdata->address_stockist : $memberdata->address;

                        $districts      = an_districts_by_province($id_province);
                        $subdistricts   = an_subdistricts_by_district($id_district);

                        $opt_district   = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kota') .' --</option>';
                        if ( $districts ) {
                            foreach($districts as $city){
                                $opt_district .= '<option value="'.$city->id.'">'. ucwords(strtolower($city->district_name)) .'</option>';
                            }
                        }

                        $opt_subdistrict = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kecamatan') .' --</option>';
                        if ( $subdistricts ) {
                            foreach($subdistricts as $subdistrict){
                                $opt_subdistrict .= '<option value="'.$subdistrict->id.'">'. ucwords(strtolower($subdistrict->subdistrict_name)) .'</option>';
                            }
                        }

                        $info_data['phone']         = $memberdata->phone;
                        $info_data['email']         = $memberdata->email;
                        $info_data['id_province']   = $id_province;
                        $info_data['id_district']   = $id_district;
                        $info_data['id_subdistrict']= $id_subdistrict;
                        $info_data['village']       = $village;
                        $info_data['address']       = $address;
                        $info_data['opt_district']  = $opt_district;
                        $info_data['opt_subdistrict'] = $opt_subdistrict;
                    }
                }
            }
        }

        $data['info'] = $info_html;
        $data['data'] = $info_data;
        die(json_encode($data));
    }

    /**
     * Search Stocist for Generate PIN function.
     */
    function searchstockistpin()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = an_isset($username, '', '', true);
        $info                   = '';
        $add_address            = '';
        $shipping               = '';

        if (empty($username)) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Username tidak boleh kosong. Silakan ketikkan Username Member lainnya!'
            );
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Username tidak valid atau belum terdaftar.'
            );
            die(json_encode($data));
        }

        // if( $memberdata->as_stockist == 0  ){
        //     $data = array(
        //         'status'    => 'error',
        //         'message'   => 'Username bukan Stockist. Silahkan ketikkan Username Stockist lainnya!'
        //     ); die(json_encode($data));
        // }

        if ($current_member->id == $memberdata->id) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Anda tidak dapat mentransfer PIN ke akun Anda sendiri!'
            );
            die(json_encode($data));
        }

        // If member is admin
        if (as_administrator($memberdata)) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Admin tidak perlu PIN. Silakan masukkan username lainnya!',
            );
            die(json_encode($data));
        }

        $info      .= '
        <input type="hidden" name="pin_stockist_id" class="form-control" value="' . $memberdata->id . '" />
        <div class="form-group">
            <label class="col-md-3 control-label">' . lang("name") . ' &nbsp;</label>
            <div class="col-md-6">
                <input type="text" name="pin_stockist_name_dsb" id="pin_stockist_name_dsb" class="form-control" disabled="" value="' . strtoupper($memberdata->name) . '" />
            </div>
        </div>';

        if ($is_admin) {
            $member_province    = $memberdata->province;
            $member_city        = $memberdata->city;

            $province_name      = '';
            if ($member_province) {
                $provinces      = an_provinces($member_province);
                $province_name  = $provinces ? $provinces->province_name : '';
            }

            $city_name          = '';
            $cities             = an_cities_by_provinces($member_province);
            if (!empty($cities)) {
                foreach ($cities as $c) {
                    if ($member_city == $c->regional_id) {
                        $city_name = $c->regional_name;
                    }
                }
            }

            $status = ($memberdata->as_stockist > 0) ? 'STOCKIST' : 'MEMBER';

            $info   .= '
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("phone") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text"  name="pin_stockist_phone" id="pin_stockist_phone" class="form-control" disabled="" value="' . $memberdata->phone . '" />
                </div>
            </div>';

            $info   .= '
            <div class="form-group">
                <label class="control-label col-md-3">' . lang("status") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Status Member" disabled="" value="' . $status . '" />
                </div>
            </div>';

            $input_province = '
            <input type="hidden" name="stockist_province" class="form-control" value="' . $member_province . '" />
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("province") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" disabled="" value="' . $province_name . '" />
                </div>
            </div>';
            $info   .= $input_province;

            $input_city = '
            <input type="hidden" name="stockist_city" class="form-control" value="' . $member_city . '" />
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("city") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" disabled="" value="' . $city_name . '" />
                </div>
            </div>';
            $info   .= $input_city;

            // Get shipping_address
            $add_address = true;
            $shipping    = '<div class="callout callout-warning bottom10" style="border-radius: 0;">
                            ' . strtoupper($memberdata->name) . ' (' . strtolower($memberdata->username) . ') belum menambahkan alamat pengiriman produk !
                            </div>';

            if ($shipping_address = an_shipping_addr_is_main($memberdata->id)) {
                $shipping = '<div class="callout callout-info bottom5" style="border-radius: 0;">
                                <input type="hidden" name="member_id" id="member_id" class="hide" value="' . $memberdata->id . '" />
                                <input type="hidden" name="member_shipping_id" id="member_shipping_id" class="hide" value="' . $shipping_address->id . '" />
                                <p class="lead bottom5">Alamat Pengiriman</p>
                                <strong>' . $shipping_address->label . ' <i class="fa fa-map-marker" style="margin-left: 5px"></i></strong>
                                <p class="text-muted" style="margin: 0px">
                                    <i class="fa fa-user" style="margin-right: 5px"></i> ' . $shipping_address->name . '
                                </p>
                                <strong class="text-danger">
                                    <i class="fa fa-phone" style="margin-right: 5px"></i> ' . $shipping_address->phone . '
                                </strong>
                                <p style="margin: 0px">' . $shipping_address->address . ', ' . $shipping_address->district . '</p>
                                <p style="margin: 0px">' . $shipping_address->city . ', ' . $shipping_address->province . '</p>
                            </div>';
                $add_address = false;
            }
        }

        // Set JSON data
        $data = array(
            'status'            => 'success',
            'message'           => 'Data Member ditemukan. Proses generate PIN dapat dilanjutkan',
            'info'              => $info,
            'shipping'          => $shipping,
            'add_address'       => $add_address,
            'member'            => $memberdata,
        );

        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Get Grade Upgrade Detail Function
     */
    function getgradeupgradedetail($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Data Member tidak ditemukan !');
        if (!$id) {
            die(json_encode($return));
        }

        $id             = an_decrypt($id);
        if (!$memberdata = an_get_memberdata_by_id($id)) {
            die(json_encode($return));
        }

        if (!$packagedata = an_packages($memberdata->package, false)) {
            $return['message'] = 'Data Peringkat tidak ditemukan';
            die(json_encode($return));
        }

        if ($memberdata->package == MEMBER_SALES) {
            $cfg_period = isset($packagedata->upgrade_period) ? $packagedata->upgrade_period : 1;
        } else {
            $cfg_period = isset($packagedata->maintain_period) ? $packagedata->maintain_period : 1;
        }

        $start_period   = ($memberdata->start_period && $memberdata->start_period != '0000-00-00') ? $memberdata->start_period : $memberdata->datecreated;
        $start_period   = date('Y-m', strtotime($start_period));
        $end_period     = date('Y-m', strtotime($start_period . ' + ' . ($cfg_period - 1) . ' MONTH'));

        $start          = $month = strtotime($start_period);
        $end            = strtotime($end_period);
        $periods        = array();
        if ($end >= $start) {
            while ($month <= $end) {
                $_month = date('Y-m', $month);
                $month  = strtotime("+1 month", $month);
                $periods[$_month] = $_month;
            }
        }

        $this->load->library('table');
        $this->table->set_template(array('table_open' => '', 'table_close' => ''));
        if ($periods) {
            $num = 1;
            foreach ($periods as $period) {
                $personal   = $group = $group_active = 0;
                $status     = '<span class="badge badge-danger">NOT QUALIFIED</span>';

                $year       = date('Y', strtotime($period));
                $month      = date('n', strtotime($period));
                $cond       = array('year' => $year, 'month' => $month);
                $getGrade   = $this->Model_Member->get_grade_by('id_member', $memberdata->id, $cond, 1);
                if ($getGrade) {
                    $personal       = an_isset($getGrade->total_pv, 0, 0);
                    $group          = an_isset($getGrade->total_pv_group, 0, 0);
                    $group_active   = an_isset($getGrade->group_active, 0, 0);
                    if ($getGrade->qualified) {
                        $status     = '<span class="badge badge-success">QUALIFIED</span>';
                    }
                }

                $tr = array(
                    $num,
                    an_center($period),
                    an_accounting($personal, '', true),
                    an_accounting($group, '', true),
                    an_center($group_active . ' ' . $packagedata->package_prev),
                    an_center($status),
                );
                $this->table->add_row($tr);
                $num++;
            }
        } else {
            $this->table->add_row(array('data' => an_center('No Data'), 'colspan' => 6));
        }

        // Get Data Grade
        $return['status']       = 'success';
        $return['start_period'] = $start_period;
        $return['cfg_period']   = $cfg_period;
        $return['periods']      = $periods;
        $return['end_period']   = $end_period;
        $return['tbody']        = $this->table->generate();
        $return['message']      = 'Data Kenaikan Peringkat ditemukan.';
        die(json_encode($return));
    }

    /**
     * Get Grade Maintenance Detail Function
     */
    function getgrademaintenancedetail($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Data Member tidak ditemukan !');
        if (!$id) {
            die(json_encode($return));
        }

        $id             = an_decrypt($id);
        if (!$memberdata = an_get_memberdata_by_id($id)) {
            die(json_encode($return));
        }

        if (!$packagedata = an_packages($memberdata->package, false)) {
            $return['message'] = 'Data Peringkat tidak ditemukan';
            die(json_encode($return));
        }

        if ($memberdata->package == MEMBER_SALES) {
            $cfg_period = isset($packagedata->upgrade_period) ? $packagedata->upgrade_period : 1;
        } else {
            $cfg_period = isset($packagedata->maintain_period) ? $packagedata->maintain_period : 1;
        }

        $start_period   = ($memberdata->start_period && $memberdata->start_period != '0000-00-00') ? $memberdata->start_period : $memberdata->datecreated;
        $start_period   = date('Y-m', strtotime($start_period));
        $end_period     = date('Y-m', strtotime($start_period . ' + ' . ($cfg_period - 1) . ' MONTH'));

        $start          = $month = strtotime($start_period);
        $end            = strtotime($end_period);
        $periods        = array();

        if ($end >= $start) {
            while ($month <= $end) {
                $_month = date('Y-m', $month);
                $month  = strtotime("+1 month", $month);
                $periods[$_month] = $_month;
            }
        }

        $this->load->library('table');
        $this->table->set_template(array('table_open' => '', 'table_close' => ''));
        if ($periods) {
            $num = 1;
            foreach ($periods as $period) {
                $personal   = $group = $group_active = 0;
                $status     = '<span class="badge badge-danger">NOT QUALIFIED</span>';

                $year       = date('Y', strtotime($period));
                $month      = date('n', strtotime($period));
                $cond       = array('year' => $year, 'month' => $month);
                $getGrade   = $this->Model_Member->get_grade_by('id_member', $memberdata->id, $cond, 1);

                if ($getGrade) {
                    $personal       = an_isset($getGrade->total_pv, 0, 0);
                    $group          = an_isset($getGrade->total_pv_group, 0, 0);
                    $group_active   = an_isset($getGrade->group_active, 0, 0);
                    if ($getGrade->qualified_maintain) {
                        $status     = '<span class="badge badge-success">QUALIFIED</span>';
                    }
                }

                $tr = array(
                    $num,
                    an_center($period),
                    an_accounting($personal, '', true),
                    an_accounting($group, '', true),
                    an_center($group_active . ' ' . $packagedata->package_prev),
                    an_center($status),
                );
                $this->table->add_row($tr);
                $num++;
            }
        } else {
            $this->table->add_row(array('data' => an_center('No Data'), 'colspan' => 6));
        }

        // Get Data Grade
        $return['status']       = 'success';
        $return['start_period'] = $start_period;
        $return['cfg_period']   = $cfg_period;
        $return['periods']      = $periods;
        $return['end_period']   = $end_period;
        $return['tbody']        = $this->table->generate();
        $return['message']      = 'Data Mempertahankan Peringkat ditemukan.';
        die(json_encode($return));
    }

    /**
     * Check Username function.
     */
    function checkusernamestaff()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $username           = $this->input->post('username');
        $username           = an_isset($username, '', '', true);
        $an_token          = $this->security->get_csrf_hash();

        if (!empty($username)) {
            $memberdata     = $this->Model_Member->get_member_by('login', strtolower($username));

            if ($memberdata) {
                die(json_encode(array('status' => false, 'token' => $an_token)));
            }

            // if staff with the username exists
            if ($staff = $this->Model_Staff->get_by('username', $username)) {
                die(json_encode(array('status' => false, 'token' => $an_token)));
            }
        }
        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Check Username function.
     */
    function checkusername()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $username   = $this->input->post('username');
        $username   = an_isset($username, '', '', true);
        $an_token  = $this->security->get_csrf_hash();

        if (!empty($username)) {
            $memberdata     = $this->Model_Member->get_member_by('login', strtolower($username));

            if ($memberdata) {
                die(json_encode(array('status' => false, 'token' => $an_token)));
            }

            // if staff with the username exists
            if ($staff = $this->Model_Staff->get_by('username', $username)){
                die(json_encode(array('status' => false, 'token' => $an_token)));
            }
        }

        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Check Email function.
     */
    function checkemail()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id                 = $this->input->post('id');
        $id                 = trim(an_isset($id, '', '', true));
        $id                 = $id ? an_decrypt($id) : $current_member->id;
        $email              = $this->input->post('email');
        $email              = trim(an_isset($email, '', '', true));
        $an_token           = $this->security->get_csrf_hash();

        die(json_encode(array('status' => true, 'token' => $an_token)));

        if (!empty($email)) {
            if ( $email == $current_member->email ) {
                die(json_encode(array('status' => true, 'token' => $an_token)));
            }
            
            $memberdata = $this->Model_Member->get_member_by('email', $email);
            if ($memberdata) {
                if ($id) {
                    if ($id != $memberdata->id) {
                        die(json_encode(array('status' => false, 'token' => $an_token)));
                    }
                } else {
                    die(json_encode(array('status' => false, 'token' => $an_token)));
                }
            }
        }
        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Check Phone function.
     */
    function checkphone()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id                 = $this->input->post('id');
        $id                 = trim(an_isset($id, '', '', true));
        $id                 = $id ? an_decrypt($id) : $current_member->id;
        $phone              = $this->input->post('phone');
        $phone              = trim(an_isset($phone, '', '', true));
        $an_token           = $this->security->get_csrf_hash();

        die(json_encode(array('status' => true, 'token' => $an_token)));

        if (!empty($phone)) {
            if ( $phone == $current_member->phone ) {
                die(json_encode(array('status' => true, 'token' => $an_token)));
            }

            $memberdata     = $this->Model_Member->get_member_by('phone', $phone);
            if ($memberdata) {
                if ($id) {
                    if ($id != $memberdata->id) {
                        die(json_encode(array('status' => false, 'token' => $an_token)));
                    }
                } else {
                    die(json_encode(array('status' => false, 'token' => $an_token)));
                }
            }
        }

        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Check ID Card function.
     */
    function checkidcard()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id                 = $this->input->post('id');
        $id                 = trim(an_isset($id, '', '', true));
        $id                 = $id ? an_decrypt($id) : $current_member->id;
        $idcard             = $this->input->post('idcard');
        $idcard             = trim(an_isset($idcard, '', '', true));
        $an_token           = $this->security->get_csrf_hash();

        die(json_encode(array('status' => true, 'token' => $an_token)));

        if (!empty($idcard)) {
            if ( $idcard == $current_member->idcard ) {
                die(json_encode(array('status' => true, 'token' => $an_token)));
            }

            $memberdata     = $this->Model_Member->get_member_by('idcard', $idcard);
            if ($memberdata) {
                if ($id) {
                    if ($id != $memberdata->id) {
                        die(json_encode(array('status' => false, 'token' => $an_token)));
                    }
                } else {
                    die(json_encode(array('status' => false, 'token' => $an_token)));
                }
            }
        }

        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Check Bill function.
     */
    function checkbill()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id                 = $this->input->post('id');
        $id                 = trim(an_isset($id, '', '', true));
        $id                 = $id ? an_decrypt($id) : $current_member->id;
        $bill               = $this->input->post('bill');
        $bill               = trim(an_isset($bill, '', '', true));
        $an_token           = $this->security->get_csrf_hash();

        die(json_encode(array('status' => true, 'token' => $an_token)));

        if (!empty($bill)) {
            if ( $bill == $current_member->bill ) {
                die(json_encode(array('status' => true, 'token' => $an_token)));
            }

            $memberdata     = $this->Model_Member->get_member_by('bill', $bill);
            if ($memberdata) {
                if ($id) {
                    if ($id != $memberdata->id) {
                        die(json_encode(array('status' => false, 'token' => $an_token)));
                    }
                } else {
                    die(json_encode(array('status' => false, 'token' => $an_token)));
                }
            }
        }

        die(json_encode(array('status' => true, 'token' => $an_token)));
    }

    /**
     * Change ID Card Photo function.
     */
    function changeidcardphoto($id = 0)
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $modified_by        = $current_member->username;
        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Member tidak valid.');

        if (!$id) {
            die(json_encode($data));
        }

        $id_member          = an_decrypt($id);
        if (!$is_admin && $id_member != $current_member->id) {
            die(json_encode($data));
        }

        $memberdata         = an_get_memberdata_by_id($id_member);
        if (!$memberdata) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        if ($staff = an_get_current_staff()) {
            $modified_by = $staff->username;
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        // Config Upload Image
        $img_msg                    = '';
        $img_ext                    = '';
        $get_data_img               = '';
        $img_upload                 = false;
        $img_name                   = strtolower($memberdata->username) . '-' . time();

        $config['upload_path']      = IDCARD_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '1048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload("idcard_img")) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }

        if (!$img_upload && $img_msg) {
            $data['data']['msg'] = 'Upload Foto KTP Gagal. ' . $img_msg;
            die(json_encode($data)); // Set JSON data
        }

        $get_data_img       = $this->upload->data();
        $file_name          = $get_data_img['file_name'];
        $img_msg            = 'upload success';
        $resize_image       = an_resize_image($file_name, IDCARD_IMG_PATH); // Resize Image 
        $data_member        = array('idcard_img' => $file_name, 'datemodified' => date('Y-m-d H:i:s'));

        if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_member)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Foto KTP Gagal. Terjadi kesalahan pada transaksi update data sales.';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Foto KTP Gagal. Terjadi kesalahan pada transaksi update data sales.';
            die(json_encode($data)); // Set JSON data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log('CHANGE_IDCARD_IMG', an_get_current_ip(), maybe_serialize(array('cookie' => $_COOKIE, 'memberdata' => $memberdata, 'modified_by' => $memberdata)));

        $data['status']     = 'success';
        $data['message']    = 'Upload Foto KTP berhasil.';
        die(json_encode($data)); // Set JSON data
    }
    
    /**
     * Change Logo Reseller Image function.
     */
    function changelogoimg($id = 0)
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $modified_by        = $current_member->username;
        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Member tidak valid.');

        if (!$id) {
            die(json_encode($data));
        }

        $id_member          = an_decrypt($id);
        if (!$is_admin && $id_member != $current_member->id) {
            die(json_encode($data));
        }

        $memberdata         = an_get_memberdata_by_id($id_member);
        if (!$memberdata) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        if ($staff = an_get_current_staff()) {
            $modified_by = $staff->username;
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        // Config Upload Image
        $img_msg                    = '';
        $img_ext                    = '';
        $get_data_img               = '';
        $img_upload                 = false;
        $img_name                   = strtolower($memberdata->username) . '-' . time();

        $config['upload_path']      = LOGO_RESELLER_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '1048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload("logo_img")) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }

        if (!$img_upload && $img_msg) {
            $data['data']['msg'] = 'Upload Logo Reseller Image Gagal. ' . $img_msg;
            die(json_encode($data)); // Set JSON data
        }

        $get_data_img       = $this->upload->data();
        $file_name          = $get_data_img['file_name'];
        $img_msg            = 'upload success';
        $resize_image       = an_resize_image($file_name, LOGO_RESELLER_IMG_PATH); // Resize Image 
        $data_member        = array('logo_img' => $file_name, 'datemodified' => date('Y-m-d H:i:s'));

        if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_member)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Logo Reseller Image Gagal. Terjadi kesalahan pada transaksi update data reseller.';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Logo Reseller Image Gagal. Terjadi kesalahan pada transaksi update data reseller.';
            die(json_encode($data)); // Set JSON data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log('CHANGE_LOGO_IMG', an_get_current_ip(), maybe_serialize(array('cookie' => $_COOKIE, 'memberdata' => $memberdata, 'modified_by' => $memberdata)));

        $data['status']     = 'success';
        $data['message']    = 'Upload Logo Reseller Image berhasil.';
        die(json_encode($data)); // Set JSON data
    }

    /**
     * Change Profile Photo function
     */
    function changeprofilephoto($id)
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $modified_by        = $current_member->username;
        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Member tidak valid.');

        if (!$id) {
            die(json_encode($data));
        }

        $id_member          = an_decrypt($id);
        if (!$is_admin && $id_member != $current_member->id) {
            die(json_encode($data));
        }

        $memberdata         = an_get_memberdata_by_id($id_member);
        if (!$memberdata) {
            $data['message'] = 'Data Member tidak ditemukan.';
            die(json_encode($data));
        }

        if ($staff = an_get_current_staff()) {
            $modified_by = $staff->username;
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        // Config Upload Image
        $img_msg                    = '';
        $img_ext                    = '';
        $get_data_img               = '';
        $img_upload                 = false;
        $img_name                   = strtolower($memberdata->username) . '-' . time();

        $config['upload_path']      = PROFILE_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '1048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload("profile_img")) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }

        if (!$img_upload && $img_msg) {
            $data['data']['msg'] = 'Upload Foto Profile Gagal. ' . $img_msg;
            die(json_encode($data)); // Set JSON data
        }

        $get_data_img       = $this->upload->data();
        $file_name          = $get_data_img['file_name'];
        $img_msg            = 'upload success';
        $resize_image       = an_resize_image($file_name, PROFILE_IMG_PATH); // Resize Image 
        $data_member        = array('photo' => $file_name, 'datemodified' => date('Y-m-d H:i:s'));

        if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_member)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Foto Profil Gagal. Terjadi kesalahan pada transaksi update data sales.';
            die(json_encode($data)); // Set JSON data
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Upload Foto Profil Gagal. Terjadi kesalahan pada transaksi update data sales.';
            die(json_encode($data)); // Set JSON data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log('CHANGE_PROFILE_IMG', an_get_current_ip(), maybe_serialize(array('cookie' => $_COOKIE, 'memberdata' => $memberdata, 'modified_by' => $memberdata)));

        $data['status']     = 'success';
        $data['message']    = 'Upload Foto Profil berhasil.';
        die(json_encode($data)); // Set JSON data
    }

    // ------------------------------------------------------------------------------------------------
    // Get City Function
    // ------------------------------------------------------------------------------------------------
    function citylist()
    {
        $daerah = isset($_GET['term']) ? strtolower($_GET['term']) : null;
        if ($daerah) {
            $data = $this->Model_Bank->get_city_list($daerah);
            if ($data) {
                foreach ($data as $row) {
                    $c[] = array('id' => $row->kode, 'label' => $row->daerah, 'value' => $row->daerah);
                }
                echo json_encode($c);
                exit;
            }
        }
    }

    // ------------------------------------------------------------------------------------------------
}

/* End of file Member.php */
/* Location: ./application/controllers/Member.php */
