<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Productmanage Controller.
 *
 * @class     Productmanage
 * @version   1.0.0
 */
class Productmanage extends Admin_Controller {
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
     * Product List Data function.
     */
    function productlistsdata(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $params                 = array();
        $condition              = '';
        $order_by               = '';
        $iTotalRecords          = 0;

        $sExport                = $this->input->get('export');
        $sAction                = an_isset($_REQUEST['sAction'],'');
        $sAction                = an_isset($sExport, $sAction);

        $search_method          = 'post';
        if( $sAction == 'download_excel' ){
            $search_method      = 'get';
        }

        $iDisplayLength         = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart          = intval($_REQUEST['iDisplayStart']);
        $sEcho                  = intval($_REQUEST['sEcho']);
        $sort                   = $_REQUEST['sSortDir_0'];
        $column                 = intval($_REQUEST['iSortCol_0']);

        $limit                  = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset                 = $iDisplayStart;

        $s_category             = $this->input->$search_method('search_category');
        $s_category             = an_isset($s_category, '', '', true);
        $s_name                 = $this->input->$search_method('search_name');
        $s_name                 = an_isset($s_name, '', '', true);
        $s_bv_min               = $this->input->$search_method('search_bv_min');
        $s_bv_min               = an_isset($s_bv_min, '', '', true);
        $s_bv_max               = $this->input->$search_method('search_bv_max');
        $s_bv_max               = an_isset($s_bv_max, '', '', true);
        $s_price_member_min     = $this->input->$search_method('search_price_member_min');
        $s_price_member_min     = an_isset($s_price_member_min, '', '', true);
        $s_price_member_max     = $this->input->$search_method('search_price_member_max');
        $s_price_member_max     = an_isset($s_price_member_max, '', '', true);
        $s_price_cust_min       = $this->input->$search_method('search_price_customer_min');
        $s_price_cust_min       = an_isset($s_price_cust_min, '', '', true);
        $s_price_cust_max       = $this->input->$search_method('search_price_customer_max');
        $s_price_cust_max       = an_isset($s_price_cust_max, '', '', true);
        $s_status               = $this->input->$search_method('search_status');
        $s_status               = an_isset($s_status, '', '', true);
        $s_type                 = $this->input->$search_method('search_type');
        $s_type                 = an_isset($s_type, '', '', true);
        $s_date_min             = $this->input->$search_method('search_dateupdated_min');
        $s_date_min             = an_isset($s_dateupdated_min, '', '', true);
        $s_date_max             = $this->input->$search_method('search_dateupdated_max');
        $s_date_max             = an_isset($s_dateupdated_max, '', '', true);

        if ( !empty($s_category) )          { $condition .= ' AND %category% LIKE CONCAT("%", ?, "%")'; $params[] = $s_category; }
        if ( !empty($s_name) )              { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%")'; $params[] = $s_name; }
        if ( !empty($s_bv_min) )            { $condition .= ' AND bv >= ?'; $params[] = $s_bv_min; }
        if ( !empty($s_bv_max) )            { $condition .= ' AND bv <= ?'; $params[] = $s_bv_max; }
        if ( !empty($s_price_member_min) )  { $condition .= ' AND price_member >= ?'; $params[] = $s_price_member_min; }
        if ( !empty($s_price_member_max) )  { $condition .= ' AND price_member <= ?'; $params[] = $s_price_member_max; }
        if ( !empty($s_price_cust_min) )    { $condition .= ' AND price_customer >= ?'; $params[] = $s_price_cust_min; }
        if ( !empty($s_price_cust_max) )    { $condition .= ' AND price_customer <= ?'; $params[] = $s_price_cust_max; }
        if ( !empty($s_date_min) )          { $condition .= ' AND %dateupdated% >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )          { $condition .= ' AND %dateupdated% <= ?'; $params[] = $s_date_max; }
        if ( !empty($s_status) )            { 
            if ( $s_status == 'active' ) {
                $condition .= ' AND %status% = 1'; 
            } else {
                $condition .= ' AND %status% <> 1'; 
            }
        }
        if ( !empty($s_type) )              { $condition .= ' AND %type% LIKE CONCAT("%", ?, "%")'; $params[] = $s_type; }

        if( $column == 1 )      { $order_by .= 'name ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%category% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= 'bv ' . $sort; }
        elseif( $column == 4 )  { $order_by .= 'price_member ' . $sort; }
        elseif( $column == 5 )  { $order_by .= 'price_customer ' . $sort; }
        elseif( $column == 7 )  { $order_by .= '%status% ' . $sort; }
        elseif( $column == 8 )  { $order_by .= '%type% ' . $sort; }
        elseif( $column == 9 )  { $order_by .= '%dateupdated% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_product($limit, $offset, $condition, $order_by, $params) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $access         = TRUE;
            if ( $staff = an_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS4 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = an_encrypt($row->id);
                $category       = an_strong(ucwords($row->category));
                $img_src        = an_product_image($row->image, true); 
                
                $product    = '
                    <div class="media align-items-center">
                        <a href="'.base_url('productmanage/productedit/'.$id).'" class="avatar mr-3">
                            <img alt="Image placeholder" src="'. $img_src .'">
                        </a>
                        <div class="media-body">
                            <a href="'.base_url('productmanage/productedit/'.$id).'" class="">
                                <span class="name mb-0 font-weight-bold text-primary">'. $row->name .'</span>
                            </a>
                        </div>
                    </div>';
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('productmanage/productstatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-product" data-product="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('productmanage/productstatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-product" data-product="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }
                
                if ( $row->type == 'perdana' ) {
                    $type       = '<span class="badge badge-success">PERDANA</span>';
                } else {
                    $type       = '<span class="badge badge-info">RO</span>';
                }

                $btn_edit       = '<a href="'.base_url('productmanage/productedit/'.$id).'" class="btn btn-sm btn-default btn-tooltip" title="Edit Produk"><i class="fa fa-edit"></i></a>';

                $btn_delete     = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/productdelete/'.$id).'"
                                    data-product="'.ucwords($row->name).'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-product" 
                                    title="Delete Produk"><i class="fa fa-trash"></i></a>';

                $price_credit   = ( $row->price_customer ) ? an_accounting($row->price_customer, '', true) : an_right('<b>-</b>');
                $stock_product  = an_stock_product($row->id);

                $records["aaData"][] = array(
                    an_center($i),
                    $product,
                    an_center($category),
                    '<div style="min-width:90px">'. an_accounting($row->bv, '', true) .'</div>',
                    '<div style="min-width:90px">'. an_accounting($row->price_member, '', true) .'</div>',
                    '<div style="min-width:90px">'. an_accounting($row->price_customer, '', true) .'</div>',
                    an_accounting($stock_product, '', true),
                    an_center( $status ),
                    an_center( $type ),
                    an_center( date('Y-m-d @H:i', strtotime($row->dateupdated)) ),
                    an_center( ( ($is_admin && $access) ? $btn_edit.$btn_delete : '' ) )
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
     * Product Category List Data function.
     */
    function categorylistsdata(){
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

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'],'');
        $sAction            = an_isset($sExport, $sAction);

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

        $s_category         = $this->input->$search_method('search_category');
        $s_category         = an_isset($s_category, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);

        if ( !empty($s_category) )          { $condition .= str_replace('%s%', $s_category, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'active' ) {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); 
            } else {
                $condition .= str_replace('%s%', 1, ' AND %status% <> %s%'); 
            }
        }

        if( $column == 1 )      { $order_by .= 'name ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_category($limit, $offset, $condition, $order_by) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $access         = TRUE;
            if ( $staff = an_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS4 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = an_encrypt($row->id);
                $category       = an_strong(ucwords($row->name));
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('productmanage/categorystatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-category" data-category="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('productmanage/categorystatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-category" data-category="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit       = '<a href="'.base_url('productmanage/savecategory/'.$id).'" class="btn btn-sm btn-primary btn-tooltip btn-edit-category" title="Edit Kategori" data-category="'.$row->name.'"><i class="fa fa-edit"></i></a>';
                $btn_delete     = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/categorydelete/'.$id).'"
                                    data-category="'.ucwords($row->name).'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-category" 
                                    title="Delete Kategori"><i class="fa fa-trash"></i></a>';
                $btn_status     = '';

                $records["aaData"][] = array(
                    an_center($i),
                    $category,
                    an_center($status),
                    an_center( ( ($is_admin && $access) ? $btn_edit.$btn_delete : '' ) )
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
     * Product In List Data function.
     */
    function productinlistsdata(){
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
        $order_by           = '';

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'],'');
        $sAction            = an_isset($sExport, $sAction);

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

        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_qty_min          = $this->input->$search_method('search_qty_min');
        $s_qty_min          = an_isset($s_qty_min, '', '', true);
        $s_qty_max          = $this->input->$search_method('search_qty_max');
        $s_qty_max          = an_isset($s_qty_max, '', '', true);
        $s_price_min        = $this->input->$search_method('search_price_min');
        $s_price_min        = an_isset($s_price_min, '', '', true);
        $s_price_max        = $this->input->$search_method('search_price_max');
        $s_price_max        = an_isset($s_price_max, '', '', true);
        $s_total_min        = $this->input->$search_method('search_total_min');
        $s_total_min        = an_isset($s_total_min, '', '', true);
        $s_total_max        = $this->input->$search_method('search_total_max');
        $s_total_max        = an_isset($s_total_max, '', '', true);
        $s_supplier         = $this->input->$search_method('search_supplier');
        $s_supplier         = an_isset($s_supplier, '', '', true);
        $s_description      = $this->input->$search_method('search_description');
        $s_description      = an_isset($s_description, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);

        if ( !empty($s_name) )              { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if ( !empty($s_supplier) )          { $condition .= ' AND supplier_name LIKE CONCAT("%", ?, "%") '; $params[] = $s_supplier; }
        if ( !empty($s_description) )       { $condition .= ' AND %description% LIKE CONCAT("%", ?, "%") '; $params[] = $s_description; }
        if ( !empty($s_qty_min) )           { $condition .= ' AND %qty% >= ?'; $params[] = $s_qty_min; }
        if ( !empty($s_qty_max) )           { $condition .= ' AND %qty% <= ?'; $params[] = $s_qty_max; }
        if ( !empty($s_price_min) )         { $condition .= ' AND %price% >= ?'; $params[] = $s_price_min; }
        if ( !empty($s_price_max) )         { $condition .= ' AND %price% <= ?'; $params[] = $s_price_max; }
        if ( !empty($s_total_min) )         { $condition .= ' AND %total% >= ?'; $params[] = $s_total_min; }
        if ( !empty($s_total_max) )         { $condition .= ' AND %total% <= ?'; $params[] = $s_total_max; }
        if ( !empty($s_date_min) )          { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if ( !empty($s_date_max) )          { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }

        if( $column == 1 )      { $order_by = '%product% ' . $sort; }
        elseif( $column == 2 )  { $order_by = '%qty% ' . $sort; }
        elseif( $column == 3 )  { $order_by = '%price% ' . $sort; }
        elseif( $column == 4 )  { $order_by = '%total% ' . $sort; }
        elseif( $column == 5 )  { $order_by = 'supplier_name ' . $sort; }
        elseif( $column == 6 )  { $order_by = '%description% ' . $sort; }
        elseif( $column == 7 )  { $order_by = '%datecreated% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_product_in($limit, $offset, $condition, $order_by, $params) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $access         = TRUE;
            if ( $staff = an_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS13 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id         = an_encrypt($row->id);
                $product    = '
                    <div class="media align-items-center" style="width:200px; white-space: normal">
                        <div class="media-body">
                            <a href="javascript:;">
                                <span class="name mb-0 font-weight-bold text-primary">'. $row->product_name .'</span>
                            </a>
                        </div>
                    </div>';

                $btn_edit   = '<a href="'.base_url('productmanage/productstockedit/'.$id).'" class="btn btn-sm btn-default btn-tooltip" title="Edit Stok Produk"><i class="fa fa-edit"></i></a>';

                $btn_delete = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/productstockdelete/'.$id).'"
                                    data-product="'.$row->product_name.'"
                                    data-qty="'. an_accounting($row->qty) .'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-product-stock" 
                                    title="Delete Stok Produk"><i class="fa fa-trash"></i></a>';
                
                $records["aaData"][] = array(
                    an_center($i),
                    $product,
                    '<div style="min-width:70px">'. an_accounting($row->qty, '', true) .'</div>',
                    '<div style="min-width:90px">'. an_accounting($row->price, '', true) .'</div>',
                    '<div style="min-width:100px">'. an_accounting($row->total, '', true) .'</div>',
                    an_center(strtoupper($row->supplier_name)),
                    $row->description,
                    an_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                    an_center($btn_edit.$btn_delete)
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
     * Product Stock List Data function.
     */
    function productstocklistsdata(){
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

        $sExport            = $this->input->get('export');
        $sAction            = an_isset($_REQUEST['sAction'],'');
        $sAction            = an_isset($sExport, $sAction);

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

        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_stock_in_min     = $this->input->$search_method('search_stock_in_min');
        $s_stock_in_min     = an_isset($s_stock_in_min, '', '', true);
        $s_stock_in_max     = $this->input->$search_method('search_stock_in_max');
        $s_stock_in_max     = an_isset($s_stock_in_max, '', '', true);
        $s_stock_out_min    = $this->input->$search_method('search_stock_out_min');
        $s_stock_out_min    = an_isset($s_stock_out_min, '', '', true);
        $s_stock_out_max    = $this->input->$search_method('search_stock_out_max');
        $s_stock_out_max    = an_isset($s_stock_out_max, '', '', true);
        $s_stock_min        = $this->input->$search_method('search_stock_min');
        $s_stock_min        = an_isset($s_stock_min, '', '', true);
        $s_stock_max        = $this->input->$search_method('search_stock_max');
        $s_stock_max        = an_isset($s_stock_max, '', '', true);

        if ( !empty($s_name) )              { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if ( !empty($s_stock_in_min) )      { $total_condition .= ' AND %stock_in% >= ?'; $params[] = $s_stock_in_min; }
        if ( !empty($s_stock_in_max) )      { $total_condition .= ' AND %stock_in% <= ?'; $params[] = $s_stock_in_max; }
        if ( !empty($s_stock_out_min) )     { $total_condition .= ' AND %stock_out% >= ?'; $params[] = $s_stock_out_min; }
        if ( !empty($s_stock_out_max) )     { $total_condition .= ' AND %stock_out% <= ?'; $params[] = $s_stock_out_max; }
        if ( !empty($s_stock_min) )         { $total_condition .= ' AND %total% >= ?'; $params[] = $s_stock_min; }
        if ( !empty($s_stock_max) )         { $total_condition .= ' AND %total% <= ?'; $params[] = $s_stock_max; }
        
        if( $column == 1 )      { $order_by = '%product% ' . $sort; }
        elseif( $column == 2 )  { $order_by = '%stock_in% ' . $sort; }
        elseif( $column == 3 )  { $order_by = '%stock_out% ' . $sort; }
        elseif( $column == 4 )  { $order_by = '%total% ' . $sort; }

        $data_list          = $this->Model_Product->get_all_product_stock($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $access         = TRUE;
            if ( $staff = an_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS13 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = an_encrypt($row->id);
                $img_src        = an_product_image($row->image, true); 
                
                $product    = '
                    <div class="media align-items-center">
                        <a href="'.base_url('productmanage/productstocklist/'.$id).'" class="avatar mr-3">
                            <img alt="Image placeholder" src="'. $img_src .'">
                        </a>
                        <div class="media-body">
                            <a href="'.base_url('productmanage/productedit/'.$id).'" class="">
                                <span class="name mb-0 font-weight-bold text-primary">'. $row->name .'</span>
                            </a>
                        </div>
                    </div>';

                $btn_detail     = '<a href="'.base_url('productmanage/productstocklist/'.$id).'" class="btn btn-sm btn-primary">Detail</a>';
                
                $records["aaData"][] = array(
                    an_center($i),
                    $product,
                    an_accounting($row->total_stock_in, '', true),
                    an_accounting($row->total_stock_out, '', true),
                    an_accounting($row->total_stock, '', true),
                    // an_center($btn_detail)
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

    // =============================================================================================
    // ACTION FUNCTIO
    // =============================================================================================

    /**
     * Save Product Function
     */
    function saveproduct( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productnew'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token               = $this->security->get_csrf_hash();
        $return                 = array('status' => 'error', 'token' => $an_token, 'message' => 'Data Produk tidak berhasil disimpan.');

        $product_id             = '';
        $product_name           = '';
        $data_product           = '';
        if ( $id ) {
            $id = an_decrypt($id);
            if ( ! $data_product = an_products($id) ) {
                $return['message'] = 'Data Produk tidak berhasil disimpan. ID Produk tidak ditemukan !';
                die(json_encode($return));
            }
            $product_id         = $data_product->id;
            $product_name       = $data_product->name;
        }

        // set variables
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        // POST Input Form
        $product                = trim( $this->input->post('product_name') );
        $product                = an_isset($product, '');
        $category               = $this->input->post('product_category');
        $category               = an_isset($category, 0, '', true);
        $price_member           = trim( $this->input->post('price_member') );
        $price_member           = an_isset($price_member, 0, '', true);
        $price_customer         = trim( $this->input->post('price_customer') );
        $price_customer         = an_isset($price_customer, 0, '', true);
        $bv                     = trim( $this->input->post('bv') );
        $bv                     = an_isset($bv, 0, '', true);
        $stock                  = trim( $this->input->post('stock') );
        $stock                  = an_isset($stock, 0, '', true);
        $weight                 = trim( $this->input->post('weight') );
        $weight                 = an_isset($weight, 0, '', true);
        $type                   = trim( $this->input->post('product_type') );
        $type                   = an_isset($type, 0, '', true);
        $description            = trim( $this->input->post('description') );
        $description            = an_isset($description, '', '', false, false);

        // Discount
        $discount_min           = trim( $this->input->post('discount_min') );
        $discount_min           = an_isset($discount_min, 0);
        $discount_type          = $this->input->post('discount_type');
        $discount_type          = an_isset($discount_type, '');
        $discount               = trim( $this->input->post('discount') );
        $discount               = an_isset($discount, 0);

        // Free Shipping
        $qty_free_shipping       = trim( $this->input->post('qty_free_shipping') );
        $qty_free_shipping       = an_isset($qty_free_shipping, 0);

        $this->form_validation->set_rules('product_name','Nama Product','required');
        $this->form_validation->set_rules('product_category','Kategori Produk','required');
        $this->form_validation->set_rules('price_member','Harga Reseller','required');
        $this->form_validation->set_rules('price_customer','Harga Konsumen','required');
        $this->form_validation->set_rules('bv','BV Produk','required');
        $this->form_validation->set_rules('weight','Berat Produk','required');
        $this->form_validation->set_rules('product_type','Jenis Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $return['message'] = 'Data Produk tidak berhasil disimpan. '.validation_errors();
            die(json_encode($return));
        }else{
            $slug                       = url_title($product, 'dash', TRUE);
            $check_slug                 = true;
            if ( $product_id == $id && strtolower($product_name) == strtolower($product) ) {
                $check_slug             = false;
            }

            if ( $check_slug ) {
                $condition              = ' AND %slug% = "'.$slug.'" OR %slug% LIKE "'.$slug.'-%" ';
                if ( $check_slug = $this->Model_Product->get_all_product(0, 0, $condition) ) {
                    $count_product      = count($check_slug);
                    $slug               = $slug .'-'. $count_product;
                }
            }

            // Config Upload Image
            $img_msg                    = '';
            $img_ext                    = '';
            $get_data_img               = '';
            $img_upload                 = true;
            $img_name                   = $slug.'-'.time();

            $config['upload_path']      = PRODUCT_IMG_PATH;
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '2048';
            $config['overwrite']        = true;
            $config['file_name']        = $img_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if( ! $this->upload->do_upload("product_img")) {
                $img_upload             = false;
                $img_msg                = $this->upload->display_errors();
            }

            $created_by         = $current_member->username;
            if ( $staff = an_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'name'              => $product,
                'slug'              => $slug,
                'id_category'       => $category,
                'price'             => str_replace('.', '', $price_member),
                'price_member'      => str_replace('.', '', $price_member),
                'price_customer'    => str_replace('.', '', $price_customer),
                'bv'                => str_replace('.', '', $bv),
                'weight'            => str_replace('.', '', $weight),
                'stock'             => str_replace('.', '', $stock),
                'qty_free_shipping' => str_replace('.', '', $qty_free_shipping),
                'type'              => $type,
                'description'       => $description,
                'datecreated'       => $datetime,
                'dateupdated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $discount ) {
                $data['discount_min']     = $discount_min;
                $data['discount_type']    = $discount_type;
                $data['discount']         = str_replace('.', '', $discount);
            }

            if ( $img_upload ) {
                $get_data_img       = $this->upload->data();
                $img_msg            = 'upload success';
                $data['image']      = $get_data_img['file_name'];

                create_thumbnail($data['image'] , PRODUCT_IMG_PATH); // Create thumbnail
            }

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $update_data = $this->Model_Product->update_data_product($id, $data) ) {
                    $return['message'] = 'Data Produk tidak berhasil disimpan. Silahkan cek form produk !';
                    die(json_encode($return));
                }

                // Delete Image
                if ( $product_id && $data_product && $img_msg == "upload success" ) {
                    $file_path = $file_thumb_path = $file = $file_thumb = ''; 
                    if ( $data_product->image ) {
                        $file_path = PRODUCT_IMG_PATH . $data_product->image;
                        if ( file_exists($file_path) ) {
                            $file = $file_path;
                        }
                        $file_thumb_path = PRODUCT_IMG_PATH . 'thumbnail/' . $data_product->image;
                        if ( file_exists($file_thumb_path) ) {
                            $file_thumb = $file_thumb_path;
                        }
                    }
                    if ( $file ) { unlink($file); }
                    if ( $file_thumb ) { unlink($file_thumb); }
                }

            } else {
                $data['status']     = 1;
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product($data) ) {
                    $return['message'] = 'Data Produk tidak berhasil disimpan. Silahkan cek form produk !';
                    die(json_encode($return));
                }
                $id = $saved_data;
            }

            $id_encrypt         = an_encrypt($id);
            $direct             = base_url('productmanage/productedit/'.$id_encrypt);
            $direct             = base_url('productmanage/productlist');
            // Save Success
            $return['status']   = 'success';
            $return['message']  = 'Data Produk berhasil disimpan.';
            $return['url']      = $direct;
            die(json_encode($return));
        }
    }

    /**
     * Save Category Product Function
     */
    function savecategory( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $category_id            = '';
        $category_name          = '';
        if ( $id ) {
            $id = an_decrypt($id);
            if ( ! $data_category = an_product_category($id) ) {
                $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. ID Kategori Produk tidak ditemukan !');
                die(json_encode($data));
            }
            $category_id        = $data_category->id;
            $category_name      = $data_category->name;
        }

        // set variables
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        // POST Input Form
        $category               = trim( $this->input->post('category') );
        $category               = an_isset($category, 0);
        $form_input             = trim( $this->input->post('form') );
        $form_input             = an_isset($form_input, '');

        $this->form_validation->set_rules('category','Kategori Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{
            $category           = strtolower($category);
            $slug               = url_title($category, 'dash', TRUE);
            $check_slug         = true;
            if ( $category_id == $id && strtolower($category_name) == strtolower($category) ) {
                $check_slug     = false;
            }

            if ( $check_slug ) {
                $condition      = ' AND %slug% = "'.$slug.'" OR %slug% LIKE "'.$slug.'-%" ';
                if ( $check_slug = $this->Model_Product->get_all_category(0, 0, $condition) ) {
                    $count_slug = count($check_slug);
                    $slug       = $slug .'-'. $count_slug;
                }
            }

            $created_by         = $current_member->username;
            if ( $staff = an_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'name'          => ucwords($category),
                'slug'          => $slug,
                'datecreated'   => $datetime,
                'datemodified'  => $datetime,
            );

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $datacategory = an_product_category($id) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
                if ( ! $update_data = $this->Model_Product->update_data_product_category($id, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
            } else {
                $data['status']     = 1;
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product_category($data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
                $id = $saved_data;
            }

            if ( strtolower($form_input) == 'product' ) {
                $option = '<option value="" disabled="" selected="">-- '. lang('select') .' '. lang('select') .'--</option>';
                if ( $get_categories = an_product_category(0, true) ) {
                    foreach($get_categories as $row){
                        if ( $id == $row->id ) {
                            $selected = 'selected=""';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="'. $row->id .'" '. $selected .'>'. ucwords($row->name) .'</option>';
                    }
                }
            } else {
                $option = '';
            }

            // Save Success
            $data = array('status'=>'success', 'option'=>$option, 'form_input'=>$form_input, 'message'=>'Data Kategori Produk berhasil disimpan.');
            die(json_encode($data));
        }
    }

    /**
     * Save Product Stock Function
     */
    function saveproductstock( $id = 0 ){
        $direct     = base_url('productmanage/productstocknew');
        if ( $id ) {
            $direct = base_url('productmanage/productstockedit/'.$id);
        }

        if ( ! $this->input->is_ajax_request() ) { redirect($direct, 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token               = $this->security->get_csrf_hash();
        $return                 = array('status' => 'error', 'token' => $an_token, 'message' => 'Data Stok Produk tidak berhasil disimpan.');

        $product_stock_id       = '';
        $data_product           = '';
        if ( $id ) {
            $id = an_decrypt($id);
            if ( ! $data_product = $this->Model_Product->get_product_stock_by('id', $id) ) {
                $return['message'] = 'Data Stok Produk tidak berhasil disimpan. ID Stok Produk tidak ditemukan !';
                die(json_encode($return));
            }
            $product_stock_id   = $data_product->id;
        }

        // set variables
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        // POST Input Form
        $product                = trim( $this->input->post('product') );
        $product                = an_isset($product, '');
        $qty                    = trim( $this->input->post('qty') );
        $qty                    = an_isset($qty, 0, 0, true);
        $qty                    = str_replace('.', '', $qty);
        $price                  = trim( $this->input->post('price') );
        $price                  = an_isset($price, 0, 0, true);
        $price                  = str_replace('.', '', $price);
        $total                  = trim( $this->input->post('total') );
        $total                  = an_isset($total, 0, 0, true);
        $total                  = str_replace('.', '', $total);
        $supplier               = trim( $this->input->post('supplier') );
        $supplier               = an_isset($supplier, '', '', true);
        $description            = trim( $this->input->post('description') );
        $description            = an_isset($description, '', '', true);

        $this->form_validation->set_rules('product','Produk','required');
        $this->form_validation->set_rules('qty','Qty Produk','required');
        $this->form_validation->set_rules('price','Harga','required');
        $this->form_validation->set_rules('total','Total Harga','required');
        $this->form_validation->set_rules('description','Deskripsi Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $return['message'] = 'Data Stok Produk tidak berhasil disimpan. '.validation_errors();
            die(json_encode($return));
        }else{

            if ( ! $qty ) {
                $return['message'] = 'Qty tidak boleh kosong !';
                die(json_encode($return));
            }

            $created_by         = $current_member->username;
            if ( $staff = an_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'product_id'        => $product,
                'qty'               => $qty,
                'price'             => $price,
                'total'             => $total,
                'supplier_name'     => $supplier,
                'description'       => $description,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $update_data = $this->Model_Product->update_data_product_stock($id, $data) ) {
                    $return['message'] = 'Data Stok Produk tidak berhasil disimpan. Silahkan cek form stok produk !';
                    die(json_encode($return));
                }
            } else {
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product_stock($data) ) {
                    $return['message'] = 'Data Stok Produk tidak berhasil disimpan. Silahkan cek form stok produk !';
                    die(json_encode($return));
                }
                $id = $saved_data;
            }

            $id_encrypt = an_encrypt($id);
            $direct     = base_url('productmanage/productstockedit/'.$id_encrypt);
            $direct     = base_url('productmanage/historystockin');

            // Save Success
            $return['status']   = 'success';
            $return['message']  = 'Data Stok Produk berhasil disimpan.';
            $return['url']      = $direct;
            die(json_encode($return));
        }
    }

    /**
     * Status Product Function
     */
    function productstatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = an_decrypt($id);
        if ( ! $data_product = an_products($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_product->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = an_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Product->update_data_product($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Produk tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Produk berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Delete Product Function
     */
    function productdelete( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = an_decrypt($id);
        if ( ! $data_product = an_products($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $product_img    = $data_product->image;
        if ( $data_shop = $this->Model_Shop->get_shop_detail_by('product', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak dapat di hapus! Data Produk ini sudah ada di Pesanan Produk.');
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Product->delete_data_product($id) ) {
            $data = array('status' => 'error', 'message' => 'Produk tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Delete Image
        $file_path = $file_thumb_path = $file = $file_thumb = ''; 
        if ( $product_img ) {
            $file_path = PRODUCT_IMG_PATH . $product_img;
            if ( file_exists($file_path) ) {
                $file = $file_path;
            }
            $file_thumb_path = PRODUCT_IMG_PATH . 'thumbnail/' . $product_img;
            if ( file_exists($file_thumb_path) ) {
                $file_thumb = $file_thumb_path;
            }
        }
        if ( $file ) { unlink($file); }
        if ( $file_thumb ) { unlink($file_thumb); }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Produk berhasil dihapus.');
        die(json_encode($data));
    }

    /**
     * Status Category Product Function
     */
    function categorystatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = an_decrypt($id);
        if ( ! $data_category = an_product_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_category->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = an_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Product->update_data_product_category($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Kategori Produk tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Kategori Produk berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Delete Category Product Function
     */
    function categorydelete( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = an_decrypt($id);
        if ( ! $data_category = an_product_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        if ( $data_product = an_product_by('id_category', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori tidak dapat di hapus! Data Kategori ini sudah digunakan di Data Produk.');
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Product->delete_data_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Kategori Produk tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Kategori Produk berhasil dihapus.');
        die(json_encode($data));
    }
}

/* End of file Productmanage.php */
/* Location: ./app/controllers/Productmanage.php */
