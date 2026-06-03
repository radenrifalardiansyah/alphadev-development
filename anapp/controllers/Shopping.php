<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Shopping Controller.
 *
 * @class     Shopping
 * @version   1.0.0
 */
class Shopping extends AN_Controller {
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA
    // =============================================================================================

    /**
     * Shop Order List Data function.
     */
    function shoporderlistsdata($type_order = '', $status_order = '', $product_type = ''){
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
            $data = array('status' => 'access_denied', 'data' => ''); 
            die(json_encode($data));
        }

        $this->load->helper('shop_helper');

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $type_order         = $type_order ? an_decrypt($type_order) : '';
        $load_data          = false;

        $params             = array();
        $condition          = ' AND %type_member% = ' . MEMBER;
        $order_by           = '';

        if ( $is_admin && $type_order == 'member_to_admin' ) {
            $load_data      = true;
            $condition     .= ' AND %id_stockist% = 0';
        }

        if ( $is_admin && $type_order == 'member_to_stockist' ) {
            $load_data      = true;
            $condition     .= ' AND %id_stockist% > 0';
        }

        if ( !$is_admin && $type_order == 'member_to_stockist' ) {
            $load_data      = true;
            $condition     .= ' AND %id_stockist% = '. $current_member->id;
        }

        if ( !$is_admin && $type_order == 'me_to_admin' ) {
            $load_data      = true;
            $condition     .= ' AND %id_member% = '. $current_member->id .' AND %id_stockist% = 0';
        }

        if ( !$is_admin && $type_order == 'me_to_stockist' ) {
            $load_data      = true;
            $condition     .= ' AND %id_member% = '. $current_member->id .' AND %id_stockist% > 0 AND %type% = "member_order"';
        }
        
        if ( !$is_admin && $type_order == 'mine' ) {
            $load_data      = true;
            $condition     .= ' AND %id_member% = '. $current_member->id .' AND %type% LIKE "member_order" AND %access_order% LIKE "customer"';
        }

        if ( $status_order ) {
            $status_order   = an_decrypt($status_order);
            if ( $status_order == 'pending' )   { $condition .= ' AND %status% = 0'; }
            if ( $status_order == 'confirmed' ) { $condition .= ' AND %status% = 1'; }
            if ( $status_order == 'done' )      { $condition .= ' AND %status% = 2'; }
            if ( $status_order == 'cancelled' ) { $condition .= ' AND %status% = 4'; }
        }

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

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_invoice          = $this->input->$search_method('search_invoice');
        $s_invoice          = an_isset($s_invoice, '', '', true);
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '', '', true);
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '', '', true);
        $s_access_order     = $this->input->$search_method('search_access_order');
        $s_access_order     = an_isset($s_access_order, '', '', true);
        $s_stockist         = $this->input->$search_method('search_stockist');
        $s_stockist         = an_isset($s_stockist, '', '', true);
        $s_stockist_name    = $this->input->$search_method('search_stockist_name');
        $s_stockist_name    = an_isset($s_stockist_name, '', '', true);
        $s_type             = $this->input->$search_method('search_type');
        $s_type             = an_isset($s_type, '', '', true);
        $s_qty_min          = $this->input->$search_method('search_qty_min');
        $s_qty_min          = an_isset($s_qty_min, '', '', true);
        $s_qty_max          = $this->input->$search_method('search_qty_max');
        $s_qty_max          = an_isset($s_qty_max, '', '', true);
        $s_bv_min           = $this->input->$search_method('search_bv_min');
        $s_bv_min           = an_isset($s_bv_min, '', '', true);
        $s_bv_max           = $this->input->$search_method('search_bv_max');
        $s_bv_max           = an_isset($s_bv_max, '', '', true);
        $s_payment_min      = $this->input->$search_method('search_nominal_min');
        $s_payment_min      = an_isset($s_payment_min, '', '', true);
        $s_payment_max      = $this->input->$search_method('search_nominal_max');
        $s_payment_max      = an_isset($s_payment_max, '', '', true);
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '', '', true);
        $s_shipping         = $this->input->$search_method('search_shipping');
        $s_shipping         = an_isset($s_shipping, '', '', true);
        $s_confirm          = $this->input->$search_method('search_confirm');
        $s_confirm          = an_isset($s_confirm, '', '', true);
        $s_confirm_by       = $this->input->$search_method('search_confirm_by');
        $s_confirm_by       = an_isset($s_confirm_by, '', '', true);
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '', '', true);
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '', '', true);
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirm_min');
        $s_dateconfirm_min  = an_isset($s_dateconfirm_min, '', '', true);
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirm_max');
        $s_dateconfirm_max  = an_isset($s_dateconfirm_max, '', '', true);

        if(!empty($s_invoice))      { $condition .= ' AND %invoice% LIKE CONCAT("%", ?, "%") '; $params[] = $s_invoice; }
        if(!empty($s_username))     { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if(!empty($s_name))         { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if(!empty($s_access_order)) { $condition .= ' AND %access_order% LIKE CONCAT("%", ?, "%") '; $params[] = $s_access_order; }
        if(!empty($s_stockist))     { $condition .= ' AND %stockist% LIKE CONCAT("%", ?, "%") '; $params[] = $s_stockist; }
        if(!empty($s_stockist_name)){ $condition .= ' AND %stockist_name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_stockist_name; }
        if(!empty($s_confirm))      { $condition .= ' AND %confirm% LIKE CONCAT("%", ?, "%") '; $params[] = $s_confirm; }
        if(!empty($s_confirm_by))   { $condition .= ' AND %confirmed_by% LIKE CONCAT("%", ?, "%") '; $params[] = $s_confirm_by; }
        if(!empty($s_type))         { $condition .= ' AND %type_product% = ?'; $params[] = $s_type; }
        if(!empty($s_shipping))     { $condition .= ' AND shipping_method = ?'; $params[] = $s_shipping; }
        if(!empty($s_qty_min))      { $condition .= ' AND total_qty >= ?'; $params[] = $s_qty_min; }
        if(!empty($s_qty_max))      { $condition .= ' AND total_qty <= ?'; $params[] = $s_qty_max; }
        if(!empty($s_bv_min))       { $condition .= ' AND total_bv >= ?'; $params[] = $s_bv_min; }
        if(!empty($s_bv_max))       { $condition .= ' AND total_bv <= ?'; $params[] = $s_bv_max; }
        if(!empty($s_payment_min))  { $condition .= ' AND total_payment >= ?'; $params[] = $s_payment_min; }
        if(!empty($s_payment_max))  { $condition .= ' AND total_payment <= ?'; $params[] = $s_payment_max; }
        if(!empty($s_date_min))     { $condition .= ' AND DATE(%datecreated%) >= ?'; $params[] = $s_date_min; }
        if(!empty($s_date_max))     { $condition .= ' AND DATE(%datecreated%) <= ?'; $params[] = $s_date_max; }
        if(!empty($s_dateconfirm_min))  { $condition .= ' AND DATE(%dateconfirmed%) >= ?'; $params[] = $s_dateconfirm_min; }
        if(!empty($s_dateconfirm_max))  { $condition .= ' AND DATE(%dateconfirmed%) <= ?'; $params[] = $s_dateconfirm_max; }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'pending' )   { $condition .= ' AND %status% = 0'; }
            if ( $s_status == 'confirmed' ) { $condition .= ' AND %status% = 1';  }
            if ( $s_status == 'done' )      { $condition .= ' AND %status% = 2';  }
            if ( $s_status == 'cancelled' ) { $condition .= ' AND %status% = 4';  }
        }

        if ( ($is_admin && $type_order == 'member_to_admin') || (!$is_admin && $type_order == 'member_to_stockist') || (!$is_admin && $type_order == 'mine') ) {
            if( $column == 1 )      { $order_by = '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by = '%username% ' . $sort; }
            elseif( $column == 3 )  { $order_by = '%name% ' . $sort; }
            elseif( $column == 4 )  { $order_by = '%access_order% ' . $sort; }
            elseif( $column == 5 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 6 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 7 )  { $order_by = 'total_bv ' . $sort; }
            elseif( $column == 8 )  { $order_by = 'total_payment ' . $sort; }
            elseif( $column == 9 )  { $order_by = 'shipping_method ' . $sort; }
            elseif( $column == 10 ) { $order_by = '%datecreated% ' . $sort; }
            elseif( $column == 11 ) {
                if ( $status_order == 'pending' )   { $order_by = '%dateexpired% ' . $sort;  }
                if ( $status_order == 'confirmed' ) { $order_by = '%dateconfirmed% ' . $sort;  }
                if ( $status_order == 'done' )      { $order_by = '%datemodified% ' . $sort;  }
                if ( $status_order == 'cancelled' ) { $order_by = '%datemodified% ' . $sort;  }
            }
            elseif( $column == 12 ) { $order_by = '%confirm% ' . $sort; }
            elseif( $column == 13 ) { 
                if ( $status_order == 'cancelled' ) { 
                    $order_by = 'modified_by ' . $sort;  
                } else {
                    $order_by = 'confirmed_by ' . $sort; 
                }
            }
            elseif( $column == 14 ) { $order_by = '%invoice% ' . $sort; }
        }

        if ( $is_admin && $type_order == 'member_to_stockist' ) {
            if( $column == 1 )      { $order_by = '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by = '%username% ' . $sort; }
            elseif( $column == 3 )  { $order_by = '%name% ' . $sort; }
            elseif( $column == 4 )  { $order_by = '%access_order% ' . $sort; }
            elseif( $column == 5 )  { $order_by = '%stockist% ' . $sort .', %stockist_name% ' . $sort; }
            elseif( $column == 6 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 7 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 8 )  { $order_by = 'total_bv ' . $sort; }
            elseif( $column == 9 )  { $order_by = 'total_payment ' . $sort; }
            elseif( $column == 10 ) { $order_by = '%status% ' . $sort; }
            elseif( $column == 11 ) { $order_by = 'shipping_method ' . $sort; }
            elseif( $column == 12 ) { $order_by = '%datecreated% ' . $sort; }
            elseif( $column == 13 ) { $order_by = '%datemodified% ' . $sort; }
            elseif( $column == 14 ) { $order_by = 'modified_by ' . $sort; }
        }

        if ( !$is_admin && $type_order == 'me_to_stockist' ) {
            if( $column == 1 )      { $order_by = '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by = '%stockist% ' . $sort; }
            elseif( $column == 3 )  { $order_by = '%stockist_name% ' . $sort; }
            elseif( $column == 4 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 5 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 6 )  { $order_by = 'total_bv ' . $sort; }
            elseif( $column == 7 )  { $order_by = 'total_payment ' . $sort; }
            elseif( $column == 8 )  { $order_by = '%status% ' . $sort; }
            elseif( $column == 9 )  { $order_by = 'shipping_method ' . $sort; }
            elseif( $column == 10 ) { $order_by = '%datecreated% ' . $sort; }
            elseif( $column == 11 ) { $order_by = '%datemodified% ' . $sort; }
            elseif( $column == 12 ) { $order_by = 'modified_by ' . $sort; }
        }

        if ( !$is_admin && $type_order == 'me_to_admin' ) {
            if( $column == 1 )      { $order_by = '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 3 )  { $order_by = 'total_qty ' . $sort; }
            elseif( $column == 4 )  { $order_by = 'total_bv ' . $sort; }
            elseif( $column == 5 )  { $order_by = 'total_payment ' . $sort; }
            elseif( $column == 6 )  { $order_by = '%status% ' . $sort; }
            elseif( $column == 7 )  { $order_by = 'shipping_method ' . $sort; }
            elseif( $column == 8 )  { $order_by = '%datecreated% ' . $sort; }
            elseif( $column == 9 )  { $order_by = '%datemodified% ' . $sort; }
            elseif( $column == 10 ) { $order_by = 'modified_by ' . $sort; }
        }

        if ( $load_data ) {
            $data_list      = $this->Model_Shop->get_all_shop_order_data($limit, $offset, $condition, $order_by, $params);
        } else {
            $data_list      = array();
        }
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $datenow        = date('Y-m-d H:i:s');
            $access         = FALSE;
            if ($is_admin) {
                if ($staff = an_get_current_staff()) {
                    if ($staff->access == 'partial') {
                        $role   = array();
                        if ($staff->role) {
                            $role = $staff->role;
                        }

                        if ( !empty($role) ) {
                            if (in_array(STAFF_ACCESS10, $role)) {
                                $access = TRUE;
                            }
                        }
                    } else {
                        $access = TRUE;
                    }
                } else {
                    $access = TRUE;
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = an_encrypt($row->id);
                $id_member      = an_encrypt($row->id_member);
                $id_stockist    = an_encrypt($row->id_stockist);
                $username       = an_strong(strtolower($row->username));
                $stockist       = an_strong(strtolower($row->stockist));
                $username       = ($is_admin ? '<a href="'.base_url('profile/'.$id_member).'">' . $username . '</a>' : $username);
                $stockist       = ($is_admin ? '<a href="'.base_url('profile/'.$id_stockist).'">' . $stockist . '</a>' : $stockist);
                $name           = (strtoupper($row->membername));
                $stockist_name  = (strtoupper($row->stockistname));

                $datemodified   = date('d M y H:i', strtotime($row->datemodified));
                $dateconfirm    = '-';
                $confirmed_by   = '-';
                if ( $row->status > 0 ) {
                    $confirmed_by   = $row->modified_by;
                }
                if ( $row->dateconfirmed != '0000-00-00 00:00:00' && $row->status == 1 ) {
                    $dateconfirm    = date('d M y H:i', strtotime($row->dateconfirmed));
                    $confirmed_by   = $row->confirmed_by;
                }

                $dateexpired    = '-';
                if ( $row->dateexpired && $row->dateexpired != '0000-00-00 00:00:00' ) {
                    $dateexpired    = date('d M y H:i', strtotime($row->dateexpired));
                    if ( $row->status == 0 && strtotime($datenow) > strtotime($row->dateexpired) ) {
                        $row->status = 4;
                        $datemodified = $dateexpired;
                        $confirmed_by = 'expired';
                    }
                }
                
                $confirmation   = '<span class="badge badge-default">PENDING</span>';
                if ( $row->confirm == 'manual' ){ $confirmation = '<span class="badge badge-warning">MANUAL</span>'; }
                if ( $row->confirm == 'auto' )  { $confirmation = '<span class="badge badge-info">AUTO</span>'; }

                $total_payment  = an_accounting($row->total_payment);
                $uniquecode     = $row->unique ? str_pad($row->unique, 3, '0', STR_PAD_LEFT) : '';
                $status         = '';
                if ( $row->status == 0 ) { $status = '<span class="badge badge-default">PENDING</span>'; }
                if ( $row->status == 1 ) { $status = '<span class="badge badge-info">CONFIRMED</span>'; }
                if ( $row->status == 2 ) { $status = '<span class="badge badge-success">DONE</span>'; }
                if ( $row->status == 4 ) { $status = '<span class="badge badge-danger">CANCELLED</span>'; }

                $btn_invoice    = '<a href="'.base_url('invoice/'.$id).'"  target="+_blank"
                                    class="btn btn-sm btn_block btn-outline-default" ><i class="fa fa-file"></i> '.$row->invoice.'</a>';

                $btn_product    = 'SubTotal : <b>'. an_accounting($row->subtotal) .'</b>'. br();
                $btn_product    = '<a href="javascript:;" 
                                    data-url="'.base_url('shopping/getshoporderdetail/'.$id).'" 
                                    data-invoice="'.$row->invoice.'"
                                    class="btn btn-sm btn-block btn-outline-primary btn-shop-order-detail">
                                    <i class="ni ni-bag-17 mr-1"></i> Detail Order</a>';


                if ( strtolower($row->shipping_method) == 'pickup' ) {
                    $courier    = '<center><b>PICKUP</b></center>';
                    if ( $row->status == 2 ) {
                        $courier .= '<br><b>Nama Pengambil</b> : <br><span class="text-warning font-weight-bold">'. ( $row->resi ? strtoupper($row->resi) : '-' ) .'</span>';
                    }
                } else {
                    $courier    = '<center><b>EKSPEDISI</b></center>';
                    if ( $row->courier ) {
                        $courier .= br().'<b>'. lang('courier') .'</b> : '. ( (strtolower($row->courier) == 'ekspedisi') ? '-' : strtoupper($row->courier) );
                        $courier .= br().'<b>Layanan</b> : '. ( (strtolower($row->courier) == 'ekspedisi') ? '-' : strtoupper($row->service) );
                    }
                    if ( $row->status == 2 ) {
                        $courier .= br().'<b>Resi</b> : <span class="text-warning font-weight-bold">'. ( $row->resi ? strtoupper($row->resi) : '-' ) .'</span>';
                    }
                }
                                        
                $btn_confirm    = $btn_cancel = $btn_payment = '';
                if ( $row->status == 0 ) {
                    $detail     = an_extract_products_order($row);
                    if ( $is_admin && $access && $row->id_stockist == 0 ) {
                        $btn_cancel = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/cancelorder/'.$id).'" 
                                            data-status="payment"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'.$row->invoice.'"
                                            data-name="' . $row->username . '"
                                            data-total="'. $total_payment .'"
                                            data-message="Apakah anda yakin akan membatalkan pesanan ini ?"
                                            class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-action" 
                                            title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';

                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/confirmorder/'.$id).'" 
                                            data-status="payment"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'.$row->invoice.'"
                                            data-name="' . $row->username . '"
                                            data-detail=\'' . json_encode($detail) . '\'
                                            data-total="'. $total_payment .'"
                                            data-uniquecode="'. $uniquecode .'"
                                            data-subtotal="'. $row->subtotal .'"
                                            data-shipping="' . $row->shipping . '"
                                            data-discount="' . $row->discount . '"
                                            data-voucher="' . $row->voucher . '"
                                            data-message="Apakah anda yakin akan Konfirmasi pembayaran atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pembayaran"><i class="fa fa-check"></i> Konfirmasi</a>';
                    }

                    if ( ! $is_admin && $row->id_stockist == $current_member->id ) {
                        $btn_cancel = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/cancelorder/'.$id).'" 
                                            data-status="payment"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'.$row->invoice.'"
                                            data-name="' . $row->username . '"
                                            data-total="'. $total_payment .'"
                                            data-message="Apakah anda yakin akan membatalkan pesanan ini ?"
                                            class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-action" 
                                            title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';

                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/confirmorder/'.$id).'" 
                                            data-status="payment"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'.$row->invoice.'"
                                            data-name="' . $row->username . '"
                                            data-detail=\'' . json_encode($detail) . '\'
                                            data-total="'. $total_payment .'"
                                            data-uniquecode="'. $uniquecode .'"
                                            data-subtotal="'. $row->subtotal .'"
                                            data-shipping="' . $row->shipping . '"
                                            data-discount="' . $row->discount . '"
                                            data-voucher="' . $row->voucher . '"
                                            data-message="Apakah anda yakin akan Konfirmasi pembayaran atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pembayaran"><i class="fa fa-check"></i> Konfirmasi</a>';
                    }

                    if ( $row->id_member == $current_member->id ) {
                        $btn_cancel = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/cancelorder/'.$id).'" 
                                            data-status="payment"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'.$row->invoice.'"
                                            data-name="' . $row->username . '"
                                            data-total="'. $total_payment .'"
                                            data-message="Apakah anda yakin akan membatalkan pesanan ini ?"
                                            class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-action" 
                                            title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';
                    }

                }

                if ( $row->status == 1 ) {
                    $detail     = an_extract_products_order($row);
                    if ( $is_admin && $access && $row->id_stockist == 0 ) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/confirmshipping/'.$id).'" 
                                            data-status="shipping"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'. $row->invoice .'"
                                            data-name="' . $row->username . '"
                                            data-detail=\'' . json_encode($detail) . '\'
                                            data-total="'. $total_payment .'"
                                            data-uniquecode="'. $uniquecode .'"
                                            data-subtotal="'. $row->subtotal .'"
                                            data-shipping="' . $row->shipping . '"
                                            data-discount="' . $row->discount . '"
                                            data-voucher="' . $row->voucher . '"
                                            data-courier="' . strtoupper($row->courier) . '"
                                            data-service="' . $row->service . '"
                                            data-message="Apakah anda yakin akan Konfirmasi Pengiriman atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pengiriman"><i class="fa fa-truck"></i> Kirim Produk</a>';
                    }

                    if ( ! $is_admin && $row->id_stockist == $current_member->id ) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('shopping/confirmshipping/'.$id).'" 
                                            data-status="shipping"
                                            data-shippingmethod="'. strtolower($row->shipping_method) .'"
                                            data-invoice="'. $row->invoice .'"
                                            data-name="' . $row->username . '"
                                            data-detail=\'' . json_encode($detail) . '\'
                                            data-total="'. $total_payment .'"
                                            data-uniquecode="'. $uniquecode .'"
                                            data-subtotal="'. $row->subtotal .'"
                                            data-shipping="' . $row->shipping . '"
                                            data-discount="' . $row->discount . '"
                                            data-voucher="' . $row->voucher . '"
                                            data-courier="' . strtoupper($row->courier) . '"
                                            data-service="' . $row->service . '"
                                            data-message="Apakah anda yakin akan Konfirmasi Pengiriman atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pengiriman"><i class="fa fa-truck"></i> Kirim Produk</a>';
                    }

                }
                
                $btn_label = '<a href="javascript:;" 
                                data-url="'.base_url('shopping/orderlabel/'.$id).'" 
                                class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-label" 
                                title="Download Label"><i class="fa fa-download"></i> Download</a>';

                if ( $row->status == 2 ) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-block btn-outline-success"><i class="fa fa-check"></i> Done</a>';
                }

                if ( $confirmed_by == 'expired' ) {
                    $btn_cancel = '<a href="javascript:;" class="btn btn-sm btn-block btn-outline-danger"><i class="ni ni-time-alarm"></i> expired</a>';
                }
                
                $access_order   = ( $row->access_order == 'self' ? '<span class="badge badge-info">RESELLER</span>' : '<span class="badge badge-warning">KONSUMEN</span>' );

                $datatables     = array(
                    an_center($i),
                    an_center($btn_invoice)
                );

                if ( ($is_admin && $type_order == 'member_to_admin') || (!$is_admin && $type_order == 'member_to_stockist') || (!$is_admin && $type_order == 'mine') ) {
                    $datatables[]   = an_center($username);
                    $datatables[]   = $name;
                    $datatables[]   = an_center($access_order);
                }

                if ( ! $is_admin && $type_order == 'me_to_stockist' ) {
                    $datatables[]   = an_center($stockist);
                    $datatables[]   = $stockist_name;
                }

                if ( $is_admin && $type_order == 'member_to_stockist' ) {
                    $datatables[]   = an_center($username);
                    $datatables[]   = $name;
                    $datatables[]   = an_center($access_order);
                    $datatables[]   = $stockist . br() . $stockist_name;
                }

                $datatables[]       = an_center($btn_product);
                $datatables[]       = '<div style="min-width:50px">'. an_center(an_accounting($row->total_qty)) .'</div>';
                $datatables[]       = '<div style="min-width:80px">'. an_accounting($row->total_bv, '', true) .'</div>';
                $datatables[]       = an_right($total_payment);

                if ( ($is_admin && $type_order == 'member_to_stockist') || $type_order == 'me_to_admin' || $type_order == 'me_to_stockist' ) {
                    $datatables[]   = an_center($status);
                }

                $datatables[]       = $courier;
                $datatables[]       = an_center(date('j M y @H:i', strtotime($row->datecreated)));

                if ( $status_order == 'pending' ) {
                    $datatables[]   = an_center($dateexpired);
                    $datatables[]   = an_center($confirmation);
                    $datatables[]   = an_center($confirmed_by);
                } elseif( $status_order == 'confirmed' ) {
                    $datatables[]   = an_center($datemodified);
                    $datatables[]   = an_center($confirmation);
                    $datatables[]   = an_center($confirmed_by);
                    $datatables[]   = an_center($btn_label);
                } elseif( $status_order == 'done' ) {
                    $datatables[]   = an_center($datemodified);
                    $datatables[]   = an_center($confirmation);
                    $datatables[]   = an_center($confirmed_by);
                } else {
                    $datatables[]   = an_center($datemodified);
                    $datatables[]   = an_center($confirmed_by);
                }

                $datatables[]       = an_center($btn_confirm.$btn_cancel);

                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Shop->get_all_shop_order_data(0, 0, $condition, $order_by, $params);
            $export                         = $this->an_xls->sales( $data_export, $status_order );
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        $records["load_data"]               = $load_data;
        $records["type_order"]              = $type_order;
        $records["token"]                   = $this->security->get_csrf_hash();;

        echo json_encode($records);
    }

    /**
     * Product Shop List Data function.
     */
    function productshoppinglistdata($type = 'all')
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
        $type               = $type ? strtolower($type) : 'all';

        // Get Search POST
        $s_product          = $this->input->post('product');
        $s_product          = an_isset($s_product, '', '', true);
        $s_category         = $this->input->post('category');
        $s_category         = an_isset($s_category, '', '', true);
        $s_limit            = $this->input->post('limit');
        $s_limit            = an_isset($s_limit, 12, 12, true);
        $s_offset           = $this->input->post('offset');
        $s_offset           = an_isset($s_offset, 0, 0, true);
        $s_sortby           = $this->input->post('sortby');
        $s_sortby           = an_isset($s_sortby, '', '', true);
        $s_orderby          = $this->input->post('orderby');
        $s_orderby          = an_isset($s_orderby, '', '', true);

        $condition          = ' AND %status% = 1';
        $order_by           = '';
        $params             = array();

        $iTotalRecords      = 0;
        $iDisplayLength     = $s_limit;
        $iDisplayStart      = $s_offset;

        if ( $s_sortby && $s_orderby ) {
            if ( strtolower($s_sortby) ==  'datecreated') {
                $order_by = '%dateupdated% '. $s_orderby;
            }
            if ( strtolower($s_sortby) ==  'price') {
                $order_by = ( $current_member->as_stockist >= 1 ) ? 'price '. $s_orderby : 'price_member '. $s_orderby;
            }
        }

        if( !empty($s_product) )    { $condition .= ' AND %product% LIKE CONCAT("%", ?, "%") '; $params[] = $s_product; }
        if( !empty($s_category) )   { $condition .= ' AND %slug_category% = ?'; $params[] = $s_category; }

        $get_products   = $this->Model_Product->get_all_product($s_limit, $s_offset, $condition, $order_by, $params);

        if ( $get_products ) { $iTotalRecords  = an_get_last_found_rows(); }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if ( $iDisplayLength > $end ) {
            $iTotalRecords = $end;
        }

        $data["data"]                   = $get_products;
        $data["type"]                   = $type;
        $data["type_member"]            = $current_member->as_stockist;
        $data["displayLimit"]           = $iDisplayLength;
        $data["displayStart"]           = $end;
        $data["totalRecords"]           = $iTotalRecords;
        $data["totalDisplayRecords"]    = $end;
        $data["token"]                  = $this->security->get_csrf_hash();
        $data["displayHTML"]            = $this->load->view(VIEW_BACK . 'shopping/productlists', $data, true);
        unset($data["data"]);

        echo json_encode($data);
        die();
    }

    /**
     * Omzet Order Daily List Data function.
     */
    function omzetorderdailylistdata()
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

        $s_omzet_generate_min   = $this->input->$search_method('search_omzet_generate_min');
        $s_omzet_generate_min   = an_isset($s_omzet_generate_min, '', '', true);
        $s_omzet_generate_max   = $this->input->$search_method('search_omzet_generate_max');
        $s_omzet_generate_max   = an_isset($s_omzet_generate_max, '', '', true);
        $s_omzet_order_min      = $this->input->$search_method('search_omzet_order_min');
        $s_omzet_order_min      = an_isset($s_omzet_order_min, '', '', true);
        $s_omzet_order_max      = $this->input->$search_method('search_omzet_order_max');
        $s_omzet_order_max      = an_isset($s_omzet_order_max, '', '', true);
        $s_omzet_min            = $this->input->$search_method('search_omzet_min');
        $s_omzet_min            = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max            = $this->input->$search_method('search_omzet_max');
        $s_omzet_max            = an_isset($s_omzet_max, '', '', true);
        $s_percent_min          = $this->input->$search_method('search_percent_min');
        $s_percent_min          = an_isset($s_percent_min, '', '', true);
        $s_percent_max          = $this->input->$search_method('search_percent_max');
        $s_percent_max          = an_isset($s_percent_max, '', '', true);
        $s_date_min             = $this->input->$search_method('search_datecreated_min');
        $s_date_min             = an_isset($s_date_min, '', '', true);
        $s_date_max             = $this->input->$search_method('search_datecreated_max');
        $s_date_max             = an_isset($s_date_max, '', '', true);

        if (!empty($s_date_min))            { $condition .= ' AND %date_omzet% >= ?'; $params[] = $s_date_min; }
        if (!empty($s_date_max))            { $condition .= ' AND %date_omzet% <= ?'; $params[] = $s_date_max; }
        if (!empty($s_omzet_generate_min))  { $total_condition .= ' AND %subtotal_generate% >= ?'; $params[] = $s_omzet_generate_min; }
        if (!empty($s_omzet_generate_max))  { $total_condition .= ' AND %subtotal_generate% <= ?'; $params[] = $s_omzet_generate_max; }
        if (!empty($s_omzet_order_min))     { $total_condition .= ' AND %subtotal_order% >= ?'; $params[] = $s_omzet_order_min; }
        if (!empty($s_omzet_order_max))     { $total_condition .= ' AND %subtotal_order% <= ?'; $params[] = $s_omzet_order_max; }
        if (!empty($s_omzet_min))           { $total_condition .= ' AND %subtotal_omzet% >= ?'; $params[] = $s_omzet_min; }
        if (!empty($s_omzet_max))           { $total_condition .= ' AND %subtotal_omzet% <= ?'; $params[] = $s_omzet_max; }
        if (!empty($s_percent_min))         { $total_condition .= ' AND %percent% >= ?'; $params[] = $s_percent_min; }
        if (!empty($s_percent_max))         { $total_condition .= ' AND %percent% <= ?'; $params[] = $s_percent_max; }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1)       { $order_by .= '%date_omzet% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%omzet_generate% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%omzet_order% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%total_omzet% ' . $sort; } 

        $data_list          = $this->Model_Shop->get_all_omzet_order_daily($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->date_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetorderdailydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetorderdailydetail"><i class="fa fa-plus"></i> Detail</a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center(date("Y-m-d", strtotime($row->date_omzet))),
                    an_right(an_accounting($row->omzet_generate)),
                    an_right(an_accounting($row->omzet_order)),
                    an_right(an_accounting($row->total_omzet)),
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Shop->get_all_omzet_order_daily(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->omzetorderdailylist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Order Monthly List Data function.
     */
    function omzetordermonthlylistdata()
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

        $s_omzet_generate_min   = $this->input->$search_method('search_omzet_generate_min');
        $s_omzet_generate_min   = an_isset($s_omzet_generate_min, '', '', true);
        $s_omzet_generate_max   = $this->input->$search_method('search_omzet_generate_max');
        $s_omzet_generate_max   = an_isset($s_omzet_generate_max, '', '', true);
        $s_omzet_order_min      = $this->input->$search_method('search_omzet_order_min');
        $s_omzet_order_min      = an_isset($s_omzet_order_min, '', '', true);
        $s_omzet_order_max      = $this->input->$search_method('search_omzet_order_max');
        $s_omzet_order_max      = an_isset($s_omzet_order_max, '', '', true);
        $s_omzet_min            = $this->input->$search_method('search_omzet_min');
        $s_omzet_min            = an_isset($s_omzet_min, '', '', true);
        $s_omzet_max            = $this->input->$search_method('search_omzet_max');
        $s_omzet_max            = an_isset($s_omzet_max, '', '', true);
        $s_percent_min          = $this->input->$search_method('search_percent_min');
        $s_percent_min          = an_isset($s_percent_min, '', '', true);
        $s_percent_max          = $this->input->$search_method('search_percent_max');
        $s_percent_max          = an_isset($s_percent_max, '', '', true);
        $s_date_min             = $this->input->$search_method('search_datecreated_min');
        $s_date_min             = an_isset($s_date_min, '', '', true);
        $s_date_max             = $this->input->$search_method('search_datecreated_max');
        $s_date_max             = an_isset($s_date_max, '', '', true);

        if (!empty($s_date_min))            { $condition .= ' AND %month_omzet% >= ?'; $params[] = $s_date_min; }
        if (!empty($s_date_max))            { $condition .= ' AND %month_omzet% <= ?'; $params[] = $s_date_max; }
        if (!empty($s_omzet_generate_min))  { $total_condition .= ' AND %subtotal_generate% >= ?'; $params[] = $s_omzet_generate_min; }
        if (!empty($s_omzet_generate_max))  { $total_condition .= ' AND %subtotal_generate% <= ?'; $params[] = $s_omzet_generate_max; }
        if (!empty($s_omzet_order_min))     { $total_condition .= ' AND %subtotal_order% >= ?'; $params[] = $s_omzet_order_min; }
        if (!empty($s_omzet_order_max))     { $total_condition .= ' AND %subtotal_order% <= ?'; $params[] = $s_omzet_order_max; }
        if (!empty($s_omzet_min))           { $total_condition .= ' AND %subtotal_omzet% >= ?'; $params[] = $s_omzet_min; }
        if (!empty($s_omzet_max))           { $total_condition .= ' AND %subtotal_omzet% <= ?'; $params[] = $s_omzet_max; }
        if (!empty($s_percent_min))         { $total_condition .= ' AND %percent% >= ?'; $params[] = $s_percent_min; }
        if (!empty($s_percent_max))         { $total_condition .= ' AND %percent% <= ?'; $params[] = $s_percent_max; }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1)       { $order_by .= '%month_omzet% ' . $sort; } 
        elseif ($column == 2)   { $order_by .= '%omzet_generate% ' . $sort; } 
        elseif ($column == 3)   { $order_by .= '%omzet_order% ' . $sort; } 
        elseif ($column == 4)   { $order_by .= '%total_omzet% ' . $sort; } 

        $data_list          = $this->Model_Shop->get_all_omzet_order_monthly($limit, $offset, $condition, $order_by, $total_condition, $params);
        $records            = array();
        $records["aaData"]  = array();
        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->month_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetordermonthlydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetordermonthlydetail"><i class="fa fa-plus"></i> Detail</a>';

                $records["aaData"][] = array(
                    an_center($i),
                    an_center(date("M, Y", strtotime($row->month_omzet))),
                    an_right(an_accounting($row->omzet_generate)),
                    an_right(an_accounting($row->omzet_order)),
                    an_right(an_accounting($row->total_omzet)),
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Shop->get_all_omzet_order_monthly(0, 0, $condition, $order_by);
            $export                         = $this->an_xls->omzetordermontlylist( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    // =============================================================================================
    // CART
    // =============================================================================================

    /**
     * Add To Cart
     */
    public function addToCart()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);

        $token          = $this->security->get_csrf_hash();
        $data           = array(
            'status'    => 'error',
            'token'     => $token,
            'message'   => 'Added to cart Failed!'
        );

        // Get Form POST
        $id             = $this->input->post('id');
        $id             = an_isset($id, '', '', true);
        $id             = an_decrypt($id);
        $qty            = $this->input->post('qty');
        $qty            = an_isset($qty, '', '', true);

        if ( !$qty || $qty == 0 ) {
            $data['message'] = 'Added to cart Failed! (Qty cannot zero)';
            die(json_encode($data));
        }

        $productdata    = an_products($id, true);
        if ( !$productdata ) {
            $data['message'] = 'Added to cart Failed! (product not found)';
            die(json_encode($data));
        }

        $product_title  = $productdata->name;
        $product_slug   = url_title($product_title, 'dash', TRUE);

        // Check Price
        $price          = ( $current_member->as_stockist >= 1 ) ? $productdata->price : $productdata->price_member;

        // Check Weight
        $weight         = $productdata->weight;
        $total_weight   = $weight * $qty;
        $product_weight = $total_weight;
        $product_type   = 'shop';

        // Check Type Product On Cart
        $cart_contents  = $this->cart->contents();
        if ( $cart_contents ) {
            foreach ($cart_contents as $item) {
                $_id    = isset($item['id']) ? $item['id'] : 'none';
                $_type  = isset($item['type']) ? $item['type'] : 'none';
            }
        }

        // Set Data Product Add To Cart
        $data_cart      = array(
            'id'        => $productdata->id,
            'type'      => $product_type,
            'name'      => $product_slug,
            'title'     => $product_title,
            'qty'       => $qty,
            'price'     => $price,
            'subtotal'  => ($price * $qty),
            'options'   => array(
                'weight' => $total_weight,
                'product_weight' => $product_weight,
            )
        );

        $insert_cart    = $this->cart->insert($data_cart);
        if ( $insert_cart ) {
            $data['status']     = 'success';
            $data['message']    = 'Success Added to Cart!';
            $data['total_item'] = count($this->cart->contents());
            $data['total_qty']  = $this->cart->total_items();
            $data['url_cart']   = base_url('shopping/cart');
        } else {
            $data['status']     = 'error';
            $data['message']    = 'Failed Added to Cart!';
        }
        // $data['data_cart']  = $data_cart;
        die(json_encode($data));
    }

    /**
     * Update Qty and checking stock availability
     */
    function updateQtyCart()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);

        $token          = $this->security->get_csrf_hash();
        $data           = array(
            'status'    => 'error',
            'token'     => $token,
            'message'   => 'Failed to Update Qty!'
        );

        $rowid          = $this->input->post('rowid');
        $rowid          = an_isset($rowid, '', '', true);
        $product_id     = $this->input->post('productid');
        $product_id     = an_isset($product_id, '', '', true);
        $product_id     = an_decrypt($product_id);
        $qty            = $this->input->post('qty');
        $qty            = an_isset($qty, '', '', true);

        $productdata    = an_products($product_id);
        if ( !$productdata ) {
            $data['message'] = 'Product not found';
            die(json_encode($data));
        }

        // Check Price
        $price          = ( $current_member->as_stockist >= 1 ) ? $productdata->price : $productdata->price_member;
        $subtotal       = $price * $qty;

        // Check Weight
        $weight         = $productdata->weight;
        $total_weight   = $weight * $qty;
        $product_weight = $total_weight;

        $data_cart      = array(
            'rowid'     => $rowid,
            'qty'       => $qty,
            'price'     => $price,
            'options'   => array(
                'weight'         => $product_weight,
                'product_weight' => $product_weight,
            )
        );

        // update cart
        $update_cart    = $this->cart->update($data_cart); 

        if ( $update_cart ) {
            $data['status']     = 'success';
            $data['message']    = 'Success Update Qty Cart!';
        } else {
            $data['status']     = 'error';
            $data['message']    = 'Failed Added to Cart!';
        }

        $total_payment          = $this->cart->total();
        $view_price             = an_accounting($price, '', true);
        $view_subtotal          = an_accounting($subtotal, '', true);
        $view_total             = an_accounting($total_payment, '', true);

        $data['price_cart']     = $view_price;
        $data['subtotal_cart']  = $view_subtotal;
        $data['total_cart']     = $view_total;
        $data['total_qty']      = $this->cart->total_items();
        $data['total_item']     = count($this->cart->contents());

        die(json_encode($data));
    }

    /**
     * Delete Product Cart
     */
    public function deleteCart($rowid = '')
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }
        $this->load->helper('shop_helper');


        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);

        $token          = $this->security->get_csrf_hash();
        $data           = array(
            'status'    => 'error',
            'token'     => $token,
            'message'   => 'Failed Delete Product Cart!'
        );

        // --------------------------------------
        // Get Cart Content
        // --------------------------------------
        $cart_content   = an_cart_contents();
        $product_cart   = isset($cart_content['data']) ? $cart_content['data'] : array();
        $product_type   = isset($cart_content['product_type']) ? $cart_content['product_type'] : '';

        // Get Input POST
        $id             = $this->input->post('rowid');
        $id             = an_isset($id, '', '', true);
        $rowid          = $rowid ? $rowid : $id;

        $product_cart   = array(
            'rowid'     => $rowid,
            'qty'       => 0,
        );
        $delete_cart = $this->cart->update($product_cart);

        if ( $delete_cart ) {
            $data['status']     = 'success';
            $data['message']    = 'Success Delete Product Cart!';
        }

        $total_payment          = $this->cart->total();
        $cart_payment           = an_accounting($total_payment, '', true);
        
        $data['total_cart']     = $cart_payment;
        $data['total_qty']      = $this->cart->total_items();
        $data['total_item']     = count($this->cart->contents());

        die(json_encode($data, true));
    }

    /**
     * Destroy Cart
     */
    public function emptyCart()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);

        // Empty Cart
        $this->cart->destroy();

        $token          = $this->security->get_csrf_hash();
        $data           = array(
            'status'    => 'success',
            'token'     => $token,
            'message'   => 'Cart Empty'
        ); die(json_encode($data));
    }

    /**
     * Select Agent
     */
    public function selectagent($id = 0)
    {
        auth_redirect();

        $this->load->helper('shop_helper');
        $this->load->library('user_agent');

        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);
        $refer          = '';
        
        if ( $this->agent->is_referral() ) {
            $refer      =  $this->agent->referrer();
        }

        if ( $is_admin ) {
            $refer      = $refer ? $refer : base_url('dashboard');
            redirect($refer, 'refresh');
        }

        $refer          = $refer ? $refer : base_url('cart');
        if ( $current_member->as_stockist >= 1 ) {
            redirect($refer, 'refresh');
        }

        if ( ! $id ) {
            redirect($refer, 'refresh');
        }

        $id_member      = an_decrypt($id);
        $apply_agent    = apply_code_seller($id_member);
        if ( $apply_agent ) {
            $refer      =  base_url('checkout');
        }
        redirect($refer, 'refresh');
    }

    /**
     * Clear/Remove Select Agent
     */
    public function shoppingclearagent()
    {
        auth_redirect();

        $this->load->helper('shop_helper');
        $this->load->library('user_agent');

        $current_member = an_get_current_member();
        $is_admin       = as_administrator($current_member);
        $refer          = '';
        
        if ( $this->agent->is_referral() ) {
            $refer      =  $this->agent->referrer();
        }

        if ( $is_admin ) {
            $refer      = $refer ? $refer : base_url('dashboard');
            redirect($refer, 'refresh');
        }

        $refer          = $refer ? $refer : base_url('checkout');
        if ( $current_member->as_stockist > 1 ) {
            redirect($refer, 'refresh');
        }

        remove_code_seller();
        redirect($refer, 'refresh');
    }

    // =============================================================================================
    // CHECKOUT
    // =============================================================================================

    /**
     * Add To Cart
     */
    public function checkout()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $token          = $this->security->get_csrf_hash();
        $data           = array(
            'status'    => 'error',
            'token'     => $token,
            'message'   => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
        );

        $this->load->helper('shop_helper');

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $as_stockist            = $current_member->as_stockist;
        $created_by             = strtolower($current_member->username);
        $datetime               = date('Y-m-d H:i:s');
        $dateexpired            = date('Y-m-d H:i:s', strtotime('+2 day'));
        
        $cfg_min_order_qty      = 0;
        $cfg_min_order_nominal  = 0;
        if ( $current_member->as_stockist > 0 ) {
            $cfg_min_order_qty      = get_option('cfg_stockist_minimal_order_qty');
            $cfg_min_order_qty      = is_numeric($cfg_min_order_qty) ? $cfg_min_order_qty : 0;
            $cfg_min_order_nominal  = get_option('cfg_stockist_minimal_order_nominal');
            $cfg_min_order_nominal  = is_numeric($cfg_min_order_nominal) ? $cfg_min_order_nominal : 0;
        }

        if ( $is_admin ) {
            $data['message'] = 'Maaf, Admin tidak dapat pesan produk!';
            die(json_encode($data));
        }

        // --------------------------------------
        // Get Cart Content
        // --------------------------------------
        $cart_content   = an_cart_contents();
        if ( ! $cart_content ) {
            die(json_encode($data));
        }

        if ( isset($cart_content['has_error']) && $cart_content['has_error'] ) {
            die(json_encode($data));
        }

        $product_cart   = isset($cart_content['data']) ? $cart_content['data'] : array();
        $product_type   = isset($cart_content['product_type']) ? $cart_content['product_type'] : '';

        if ( !$product_cart ) {
            die(json_encode($data));
        }

        if ( $current_member->as_stockist > 0 ) {
            if ( $cfg_min_order_qty || $cfg_min_order_nominal ) {
                if ( $cfg_min_order_qty > $this->cart->total_items() ) {
                    $data['message'] = 'Minimal qty pembelanjaan <b>'. an_accounting($cfg_min_order_qty) .' Produk</b>';
                    die(json_encode($data));
                }
                if ( $cfg_min_order_nominal > $this->cart->total() ) {
                    $data['message'] = 'Minimal belanja sebesar <b>'. an_accounting($cfg_min_order_nominal, config_item('currency')) .'</b>';
                    die(json_encode($data));
                }
            }
        } 

        // POST Input Form
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
        $courier                = trim( $this->input->post('select_courier') );
        $courier                = an_isset($courier, '', '', true);
        $service                = trim( $this->input->post('select_service') );
        $service                = an_isset($service, '', '', true);
        $courier_cost           = trim( $this->input->post('courier_cost') );
        $courier_cost           = an_isset($courier_cost, 0, 0, true);
        $courier_cost           = $courier_cost ? $courier_cost : 0;

        $this->form_validation->set_rules('payment_method','Metode Pembayaran','required');
        $this->form_validation->set_rules('shipping_method','Metode Pengiriman','required');
        if ( $shipping_method == 'ekspedisi' ) {
            $this->form_validation->set_rules('name','Nama','required');
            $this->form_validation->set_rules('phone','No. Hp/WA','required');
            $this->form_validation->set_rules('email','Email','required');
            $this->form_validation->set_rules('province','Provinsi','required');
            $this->form_validation->set_rules('district','Kota/Kabupaten','required');
            $this->form_validation->set_rules('subdistrict','Kecamatan','required');
            $this->form_validation->set_rules('village','Kelurahan/Desa','required');
            $this->form_validation->set_rules('address','Alamat','required');

            if ( $current_member->as_stockist == 0 ) {
                $this->form_validation->set_rules('select_courier','Kurir','required');
                $this->form_validation->set_rules('select_service','Layanan Kurir','required');
            }
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ( $this->form_validation->run() == FALSE ){
            $data['message'] = 'Checkout tidak berhasil. '. br() . validation_errors();
            die(json_encode($data));
        }

        if ( $phone ) {
            if ( substr($phone, 0, 1) != '0' ) {
                $phone      = '0'. $phone;
            }
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

        // --------------------------------------
        // Product Cart
        // --------------------------------------
        $cart_total_qty     = $this->cart->total_items();
        $cart_total_payment = $this->cart->total();

        // Set Product
        $product_detail     = array();
        $total_bv           = 0; 
        $total_qty          = 0; 
        $total_price        = 0;
        $total_weight       = 0;
        $total_payment      = 0;

        foreach ($product_cart as $key => $row) {
            $_id            = isset($row['id']) ? $row['id'] : 0;
            $_qty           = isset($row['qty']) ? $row['qty'] : 0;
            $_price         = isset($row['cart_price']) ? $row['cart_price'] : 0;
            if ( !$_id || !$_qty || !$_price ) { continue; }
            if ( !$productdata = an_products($_id) ) { continue; }

            $subtotal           = $_qty * $_price;
            $subtotal_bv        = $_qty * $productdata->bv;
            $product_weight     = $_qty * $productdata->weight;
            $product_price      = ( $as_stockist >= 1 ) ? $productdata->price : $productdata->price_member;
            
            // Set Product Detail
            $product_detail[]   = array(
                'id'                => $_id,
                'name'              => $productdata->name,
                'bv'                => $productdata->bv,
                'qty'               => $_qty,
                'price'             => $product_price,      // original price
                'price_cart'        => $_price,                 // cart price
                'discount'          => 0,
                'subtotal'          => $subtotal,
                'weight'            => $product_weight
            );

            $total_bv           += $subtotal_bv; 
            $total_qty          += $_qty; 
            $total_price        += $subtotal;
            $total_weight       += $product_weight;
        }

        if ( !$product_detail ) {
            die(json_encode($data));
        }

        if ( $cart_total_qty != $total_qty ) {
            die(json_encode($data));
        }

        if ( $cart_total_payment != $total_price ) {
            die(json_encode($data));
        }

        // --------------------------------------
        // Check Code Checkout
        // --------------------------------------
        $set_checkout_code  = $product_type . $current_member->id;
        $get_checkout_code  = $this->session->userdata('checkout_code');
        if ( $set_checkout_code != $get_checkout_code ) {
            $data['message'] = 'Checkout tidak berhasil. Failed Code.';
            die(json_encode($data));
        }

        // --------------------------------------
        // Check Apply Sub/Agency
        // --------------------------------------
        $id_stockist        = 0;
        $username_stockist  = '';
        $checkStockist      = false;
        
        /*
        if ( $as_stockist == 0 ) {
            $checkStockist  = an_check_agent(true);
            if ( $checkStockist ) {
                $id_stockist        = isset($checkStockist->id) ? $checkStockist->id : 0;
                $username_stockist  = isset($checkStockist->username) ? $checkStockist->username : '';
            }

            if ( $as_stockist == 0 ) {
                if ( ! $checkStockist || ! $id_stockist || ! $username_stockist ) {
                    $data['message'] = 'Anda belum pilih <b>Stockist</b>. Silahkan pilih <b>Stockist</b> terlebih dahulu untuk memesan produk !';
                    die(json_encode($data));
                }
            }
        }
        */

        // --------------------------------------
        // Check Saldo Type
        // --------------------------------------
        $saldo                  = 0;
        $status                 = 0;
        if ( $payment_method == 'deposite' ) {
            if ( $total_price > $saldo ) {
                $data['message'] = 'Checkout tidak berhasil. Saldo Anda tidak mencukupi untuk belanja produk ini !';
                die(json_encode($data));
            }
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();
        
        $type_order             = ( $as_stockist ) ? 'stockist_order' : 'member_order';
        $code_unique            = 0;
        if ( $id_stockist > 0 ) {
            $invoice            = an_generate_member_invoice($id_stockist);
            $code_unique        = an_generate_member_uniquecode($id_stockist);
        } else {
            $invoice            = an_generate_shop_invoice();
            $code_unique        = an_generate_shop_order();
        }

        $total_payment          = $total_price + $code_unique + $courier_cost;

        $data_shop_order        = array(
            'invoice'           => $invoice,
            'id_member'         => $current_member->id,
            'id_stockist'       => $id_stockist,
            'type_order'        => $type_order,
            'access_order'      => 'self',
            'products'          => maybe_serialize($product_detail),
            'total_bv'          => $total_bv,
            'total_qty'         => $total_qty,
            'subtotal'          => $total_price,
            'unique'            => $code_unique,
            'shipping'          => $courier_cost,
            'discount'          => 0,
            'total_payment'     => $total_payment,
            'status'            => $status,
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
            'name_consumer'             => $name,
            'phone_consumer'            => $phone,
            'email_consumer'            => $email,
            'id_province_consumer'      => $province,
            'id_district_consumer'      => $district,
            'id_subdistrict_consumer'   => $subdistrict,
            'province_consumer'         => $province_name,
            'district_consumer'         => $district_name,
            'subdistrict_consumer'      => $subdistrict_name,
            'village_consumer'          => $village,
            'address_consumer'          => $address,
            'courier'           => $courier,
            'service'           => $service,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
            'dateexpired'       => $dateexpired,
            'created_by'        => $created_by,
        );

        // -------------------------------------------------
        // Save Shop Order
        // -------------------------------------------------
        $saved_shop_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if( ! $saved_shop_id ){
            $this->db->trans_rollback(); // Rollback Transaction
            $data['message'] = 'Checkout tidak berhasil. Terjadi kesalahan pada proses data transaksi.';
            die(json_encode($data));
        }

        // Set shop order detail
        $data_shop_detail       = array();
        foreach ($product_detail as $prodkey => $val) {
            $data_shop_detail[]     = array(
                'id_shop_order'     => $saved_shop_id,
                'id_member'         => $current_member->id,
                'product'           => $val['id'],
                'bv'                => $val['bv'],
                'qty'               => $val['qty'],
                'price'             => $val['price'],
                'price_cart'        => $val['price_cart'],
                'discount'          => $val['discount'],
                'subtotal'          => $val['subtotal'],
                'weight'            => $val['weight'],
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );
        }

        if ( !$data_shop_detail ) {
            $this->db->trans_rollback(); // Rollback Transaction
            $data['message'] = 'Checkout tidak berhasil. Terjadi kesalahan pada proses data transaksi produk detail.';
            die(json_encode($data));
        }

        foreach ($data_shop_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $saved_shop_detail  = $this->Model_Shop->save_data_shop_order_detail($row);

            if ( !$saved_shop_detail ) {
                $this->db->trans_rollback(); // Rollback Transaction
                $data['message'] = 'Checkout tidak berhasil. Terjadi kesalahan pada proses data transaksi produk detail.';
                die(json_encode($data));
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete();    // complete database transactions  
        $this->cart->destroy();         // Empty Product Cart

        remove_code_discount();
        remove_code_seller();

        $data_log   = array(
            'cookie'        => $_COOKIE, 
            'status'        => 'SUCCESS', 
            'shop_order_id' => $saved_shop_id,
            'id_stockist'   => $id_stockist,
            'product_detail'=> $product_detail,
            'total_payment' => $total_payment,
        );

        an_log_action( 'SHOP', $invoice, $current_member->username, json_encode($data_log) );

        // Send Notif
        if ( $shop_order = $this->Model_Shop->get_shop_orders($saved_shop_id) ) {
            $this->an_email->send_email_shop_order($current_member, $shop_order);
            $this->an_wa->send_wa_shop_order($current_member, $shop_order);
            if ( $id_stockist ) {
                if ( $stockistdata = an_get_memberdata_by_id($id_stockist) ) {
                    $this->an_email->send_email_shop_order_stockist($stockistdata, $shop_order);
                    $this->an_wa->send_wa_shop_order_stockist($stockistdata, $shop_order);
                }
            }
        }

        $data['status']     = 'success';
        $data['message']    = 'Checkout berhasil';
        $data['url']        = base_url('shopping/shophistorylist');
        die(json_encode($data));
    }

    // =============================================================================================
    // ACTION SHOPPING
    // =============================================================================================

    /**
     * Confirm Shop Order Function
     */
    function confirmorder( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('shop/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token   = $this->security->get_csrf_hash();
        $data       = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenali. Silahkan pilih Pesanan lainnya untuk dikonfirmasi');

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

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        $shop_detail        = $this->Model_Shop->get_shop_detail_by('id_shop_order', $id);

        // Set Data Shop Order
        $invoice            = $shop_order->invoice;
        $id_stockist        = $shop_order->id_stockist;
        $total_nominal      = $shop_order->total_payment;
        $subtotal_nominal   = $shop_order->subtotal;
        $product_detail     = maybe_unserialize($shop_order->products);

        if ( $id_stockist > 0 ) {
            if( $is_admin ){
                $data['message'] = 'Maaf, Admin tidak dapat Konfirmasi Pesanan Member ke Stockist.';
                die(json_encode($data));
            }

            if( $id_stockist != $current_member->id ){
                $data['message'] = 'Maaf, Anda tidak dapat Konfirmasi Pesanan ini.';
                die(json_encode($data));
            }
        } else {
            if( !$is_admin ){
                $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Pesanan Produk ini !';
                die(json_encode($data));
            }
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if( $is_admin && $id_stockist == 0 ){
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
        $log_data['status']     = 'Konfirmasi Pesanan Produk';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                an_log_action('SHOP_CONFIRM_ORDER', 'ERROR', $confirmed_by, json_encode($log_data));
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
            $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Reseller tidak dikenali.';
            die(json_encode($data));
        }

        // Check Stock Produk
        if( $is_admin && $id_stockist == 0 ){
            if ( !$shop_detail ) {
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Data detail pesanan produk tidak ditemukan.';
                die(json_encode($data));
            }
            // foreach ($shop_detail as $key => $row) {
            //     $product_id     = $row->product;
            //     $product_qty    = $row->qty;
            //     $product_stock  = an_stock_product($product_id);
            //     if ( $product_qty > $product_stock ) {
            //         $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Qty pesanan produk melebihi total stok produk yang ada.';
            //         $get_product = an_products($product_id);
            //         if ( $get_product ) {
            //             if ( $product_stock > 0) {
            //                 $data['message'] = 'Jumlah pesanan Produk '.$get_product->name.' ('.$product_qty.' qty) melebihi total stok Produk yang pusat miliki ('.$product_stock.' qty) !';
            //             } else {
            //                 $data['message'] = 'Pusat tidak memiliki Stok Produk '.$get_product->name;
            //             }
            //         }
            //         die(json_encode($data));
            //     }
            // }
        }

        // Check Stock Produk stockist
        if( !$is_admin && $id_stockist > 0 ){
            if ( !$shop_detail ) {
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Data detail pesanan produk tidak ditemukan.';
                die(json_encode($data));
            }

            foreach ($shop_detail as $key => $row) {
                $product_id     = $row->product;
                $product_qty    = $row->qty;
                $product_name   = '';

                if ( !$product_id || !$product_qty ) { continue; }
                if ( $productdata = an_products($product_id) ) {
                    $product_name = $productdata->name;
                }

                $total_my_pin   = an_member_pin($id_stockist, 'active', true, $product_id);
                $total_my_pin   = $total_my_pin ? $total_my_pin : 0;

                if( !$total_my_pin ){
                    $data['message'] = 'Konfirmasi pesanan tidak berhasil. Anda tidak memiliki stok Produk <b>' . $product_name . '</b>';
                    die(json_encode($data));
                }

                if( $product_qty > $total_my_pin ){
                    $data['message'] = 'Konfirmasi pesanan tidak berhasil. Jumlah pesanan Produk <b>' . $product_name . '</b> ('.$qty.') melebihi total stok Produk yang Anda miliki ('.$total_my_pin.') !';
                    die(json_encode($data));
                }
            }
        }
        
        $for = ( $shop_order->access_order == 'customer' ? 'self' : 'sponsor' );
        
        // Get Member Registration Data if consumer want to be a reseller
        $memberconfirm = '';
        $log_data_confirm = '';
        if( $shop_order->as_reseller == 1 ){
            if (!$memberconfirm = $this->Model_Member->get_member_confirm_by_downline($shop_order->id_member)) {
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Data registrasi Reseller baru tidak ditemukan atau belum terdaftar!';
                die(json_encode($data));
            }
            
            $status_msg             = '';
            $log_data_confirm       = array('cookie' => $_COOKIE);
            $log_data_confirm['id_confirm'] = $memberconfirm->id;
            $log_data_confirm['id_downline'] = $memberconfirm->id_downline;
            $log_data_confirm['status']     = 'Konfirmasi Pendaftaran';
    
            if ($memberconfirm->status == ACTIVE) {
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Status Pendaftaran Reseller sudah dikonfirmasi.';
                die(json_encode($data));
            }
    
            if ($memberconfirm->status != NONACTIVE) {
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Pendaftaran tidak dapat dikonfirmasi.';
                die(json_encode($data));
            }
    
            if ($memberdata->status != NONACTIVE) {
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Pendaftaran tidak dapat dikonfirmasi.';
                die(json_encode($data));
            }
        }
        
        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirmed' => $datetime,
            'confirm'       => 'manual',
            'confirmed_by'  => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        // PIN Generate (Admin to Stockist)
        if( $is_admin && $id_stockist == 0 ){
            $repl_invoice           = str_replace('INV/', '', $invoice);
            $repl_invoice           = absint($repl_invoice);
            $len_invoice            = strlen($repl_invoice);
            $len_id                 = strlen($memberdata->id);
            $len_string             = $len_invoice + $len_id;
            $len_rand               = ( $len_string >= 12 ) ? 3 : (15 - $len_string);

            $data_pin               = array();
            foreach ($shop_detail as $key => $row) {
                $product_id = $row->product;
                $qty        = $row->qty;

                if ( !$product_id || !$qty  ) { continue; }

                // Set data pin
                for($i=1; $i<=$qty; $i++){
                    $code_string        = an_generate_rand_string($len_rand);
                    $uniquecode         = 'PO'. $repl_invoice . $memberdata->id . $product_id . $code_string;
                    $data_pin[]         = array(
                        'id_pin'            => strtoupper($uniquecode),
                        'id_order_pin'      => $id,
                        'id_member'         => $memberdata->id,
                        'id_member_owner'   => $memberdata->id,
                        'product'           => $product_id,
                        'bv'                => $row->bv,
                        'amount'            => $row->price,
                        'status'            => 1,
                        'datecreated'       => $datetime,
                        'datemodified'      => $datetime,
                    );
                }
            }

            if( !$data_pin ) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan pada data transaksi buat pin !';
                die(json_encode($data));
            }
            
            // save data pin
            foreach($data_pin as $row){
                if ( ! $pin_saved = $this->Model_Shop->save_data_pin($row) ) {
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan pada data transaksi buat pin !';
                    die(json_encode($data));                  
                }
            }
        }
        
        // Update data member and data member registration
        if( $shop_order->as_reseller == 1 ){
            // Update Data Member Confirm
            $data_update_confirm = array(
                'status'        => ACTIVE,
                'datemodified'  => $datetime,
            );
    
            if (!$update_confirm = $this->Model_Member->update_data_member_confirm($memberconfirm->id, $data_update_confirm)) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Reseller.';
                die(json_encode($data));
            }
    
            // Get Data Sponsor 
            if (!$sponsordata = an_get_memberdata_by_id($memberdata->sponsor)) {
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Sponsor Reseller tidak dikenali.';
                die(json_encode($data));
            }
            
            // Get Data Upline
            if( !$uplinedata = an_get_memberdata_by_id($memberdata->parent) ){
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Data Upline tidak dikenali.';
                die(json_encode($data));
            }
            $position = an_position_upline($uplinedata->id);
    
            $gen                = $sponsordata->gen + 1;
            $level              = $uplinedata->level + 1;
            $tree_sponsor       = an_generate_tree_sponsor($memberdata->id, $sponsordata->tree_sponsor);
            $tree               = an_generate_tree($memberdata->id, $uplinedata->tree);
            $position           = $position;
            $data_update_member = array(
                'password'      => an_password_hash($memberdata->password),
                'password_pin'  => an_password_hash($memberdata->password),
                'position'      => $position,
                'gen'           => $gen,
                'level'         => $level,
                'tree'          => $tree,
                'tree_sponsor'  => $tree_sponsor,
                'status'        => ACTIVE,
                'datemodified'  => $datetime,
            );
            if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_update_member)) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi pesanan tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Reseller.';
                die(json_encode($data));
            }
            
            // -------------------------------------------------
            // Generate Key Member
            // -------------------------------------------------
            $generate_key = an_generate_key();
            an_generate_key_insert($generate_key, ['id_member' => $memberdata->id, 'name' => $memberdata->name]);
            
            // Update Data Member Omzet
            // -------------------------------------------------
            if ($memberconfirm->omzet > 0) {
                $data_member_omzet  = array(
                    'id_member'     => $memberdata->id,
                    'bv'            => $shop_order->total_bv,
                    'omzet'         => $shop_order->total_bv,
                    'amount'        => $shop_order->subtotal,
                    'status'        => 'register',
                    'desc'          => 'New Reseller',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
    
                if (!$insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet)) {
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi pesanan tidak berhasil. Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
                    die(json_encode($data));
                }
            }
            
            // -------------------------------------------------
            // calculate bonus referral
            // -------------------------------------------------
            $bonus_referral     = an_calculate_bonus_referral($memberdata->id, $datetime);
        }
        
        // Proses Sales Bonus
        if( $shop_order->as_reseller == 0 ){
            an_calculate_bonus_sales($memberdata->id, $subtotal_nominal, $for);
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('SHOP_CONFIRM_ORDER', 'SUCCESS', $confirmed_by, json_encode($log_data));
        if( $shop_order->as_reseller == 1 ){
            an_log_action('REGISTER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data_confirm));
        }

        // Send Notif
        if ( $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            $this->an_email->send_email_shop_order($memberdata, $shop_order);
            $this->an_wa->send_wa_shop_order($memberdata, $shop_order);
        }

        if ( $current_member->id == $id_stockist ) {
            $this->an_email->send_email_shop_order_stockist($current_member, $shop_order);
            $this->an_wa->send_wa_shop_order_stockist($current_member, $shop_order);
        }

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil dikonfirmasi.';
        die(json_encode($data));
    }

    /**
     * Cancel Shop Order Function
     */
    function cancelorder( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('dashboard'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token   = $this->security->get_csrf_hash();
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

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        // Set Data Shop Order
        $invoice            = $shop_order->invoice;
        $id_member          = $shop_order->id_member;
        $id_stockist        = $shop_order->id_stockist;
        $total_nominal      = $shop_order->total_payment;

        if( $id_member != $current_member->id ){
            if ( $id_stockist > 0 ) {
                if( $is_admin ){
                    $data['message'] = 'Maaf, Admin tidak dapat Batalkan Pesanan Member ke Stockist.';
                    die(json_encode($data));
                }

                if( $id_stockist != $current_member->id ){
                    $data['message'] = 'Maaf, Anda tidak dapat Batalkan Pesanan ini.';
                    die(json_encode($data));
                }
            } else {
                if( !$is_admin ){
                    $data['message'] = 'Maaf, hanya Administrator yang dapat Batalkan Pesanan Produk ini !';
                    die(json_encode($data));
                } 
            }
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if( $is_admin && $id_stockist == 0 ){
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
        $log_data['invoice']    = $invoice;
        $log_data['status']     = 'Batalkan Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                an_log_action('SHOP_ORDER_CANCEL', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 4 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dibatalkan. Pesanan sudah diproses !';
            die(json_encode($data));
        }

        // Update status shop order
        $data_order     = array(
            'status'        => 4,
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        an_log_action('SHOP_ORDER_CANCEL', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // Send Notif Email
        if ( $memberdata = an_get_memberdata_by_id($shop_order->id_member) ) {
            $this->an_email->send_email_shop_order($memberdata, $shop_order);
            $this->an_wa->send_wa_shop_order($memberdata, $shop_order);
        }
        if ( $id_stockist ) {
            if ( $stockistdata = an_get_memberdata_by_id($id_stockist) ) {
                $this->an_email->send_email_shop_order_stockist($stockistdata, $shop_order);
                $this->an_wa->send_wa_shop_order_stockist($stockistdata, $shop_order);
            }
        }

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil dibatalkan.';
        die(json_encode($data));
    }

    /**
     * Input Nomor Resi Shop Order Function
     */
    function confirmshipping($id = 0)
    {
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('dashboard'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token   = $this->security->get_csrf_hash();
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
        $courier            = trim($this->input->post('courier'));
        $courier            = an_isset($courier, '', '', true);
        $service            = trim($this->input->post('service'));
        $service            = an_isset($service, '', '', true);
        $resi               = trim($this->input->post('resi'));
        $resi               = an_isset($resi, '', '', true);
        $password           = trim( $this->input->post('password') );
        $password           = an_isset($password, '', '', true);

        if ( !$resi ) {
            $data['message'] = 'Nomor Resi harus diisi !';
            die(json_encode($data));
        }

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        if ( strtolower($shop_order->shipping_method) == 'ekspedisi' ) {
            if ( ! $shop_order->courier ) {
                if ( !$courier ) {
                    $data['message'] = 'Kurir harus diisi !';
                    die(json_encode($data));
                }
            }

            if ( ! $shop_order->service ) {
                if ( !$service ) {
                    $data['message'] = 'Layanan Kurir harus diisi !';
                    die(json_encode($data));
                }
            }
        }

        $shop_detail        = $this->Model_Shop->get_shop_detail_by('id_shop_order', $id);

        // Set Data Shop Order
        $invoice            = $shop_order->invoice;
        $id_member          = $shop_order->id_member;
        $id_stockist        = $shop_order->id_stockist;
        $total_nominal      = $shop_order->total_payment;
        $product_detail     = maybe_unserialize($shop_order->products);

        if ( $id_stockist > 0 ) {
            if( $is_admin ){
                $data['message'] = 'Maaf, Admin tidak dapat Konfirmasi Pengiriman pesanan Member ke Stockist.';
                die(json_encode($data));
            }

            if( $id_stockist != $current_member->id ){
                $data['message'] = 'Maaf, Anda tidak dapat Konfirmasi Pengiriman pesanan ini.';
                die(json_encode($data));
            }
        } else {
            if( !$is_admin ){
                $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Pengiriman pesanan produk ini !';
                die(json_encode($data));
            }
        }

        if ( $my_account = an_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if( $is_admin && $id_stockist == 0 ){
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
        $log_data['status']     = 'Input Resi';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 1) {
                an_log_action('INPUT_RESI', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        $noresi = trim( an_isset($shop_order->resi, '', '', true) );
        if (!empty($noresi)) {
            $data['message'] = 'Nomor RESI sudah dibuat untuk pesanan ini.';
            die(json_encode($data));
        }

        if ($shop_order->status == 4) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 1) {
            $data['message'] = 'Pesanan belum dikonfirmasi. Silahkan Konfirmasi Pesanan terlebih dahulu!';
            die(json_encode($data));
        }

        // Check Stock Produk
        if( $is_admin && $id_stockist == 0 ){
            if ( !$shop_detail ) {
                $data['message'] = 'Konfirmasi Pengiriman tidak berhasil. Data detail pesanan produk tidak ditemukan.';
                die(json_encode($data));
            }
            foreach ($shop_detail as $key => $row) {
                $product_id     = $row->product;
                $product_qty    = $row->qty;
                $product_stock  = an_stock_product($product_id);
                if ( $product_qty > $product_stock ) {
                    $data['message'] = 'Konfirmasi Pengiriman tidak berhasil. Qty pesanan produk melebihi total stok produk yang ada.';
                    $get_product = an_products($product_id);
                    if ( $get_product ) {
                        if ( $product_stock > 0) {
                            $data['message'] = 'Jumlah pesanan Produk '.$get_product->name.' ('.$product_qty.' qty) melebihi total stok Produk yang pusat miliki ('.$product_stock.' qty) !';
                        } else {
                            $data['message'] = 'Pusat tidak memiliki Stok Produk '.$get_product->name;
                        }
                    }
                    die(json_encode($data));
                }
            }
        }

        // Update nomor resi shop order
        $data_order     = array(
            'status'        => 2,
            'resi'          => strtoupper($resi),
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( strtolower($shop_order->shipping_method) == 'ekspedisi' ) {
            if ( ! $shop_order->courier ) {
                $data_order['courier'] = strtoupper($courier);
            }

            if ( ! $shop_order->service ) {
                $data_order['service'] = strtoupper($service);
            }
        }

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }
        
        // Send Email Notifikasi
        if ( $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            $this->an_email->send_email_confirm_shipping($shop_order);
        }

        an_log_action('INPUT_RESI', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data['status']     = 'success';
        $data['message']    = 'Konfirmasi pengiriman pesanan produk berhasil.';
        die(json_encode($data));
    }

    // =============================================================================================
    // ACTION DETAIL SHOPPING
    // =============================================================================================

    /**
     * Get Shop Order Detail Function
     */
    function getshoporderdetail( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = an_decrypt($id);
        if ( ! $data_order = $this->Model_Shop->get_shop_orders($id) ) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlshoporderdetail($data_order, 'agent');
        $data = array('status'=>'success', 'message'=>'Produk Order', 'data'=>$set_html );
        die(json_encode($data));
    }

    /**
     * Set HTML Shop Order Detail function.
     */
    private function sethtmlshoporderdetail($dataorder, $type_order = 'agent'){
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $order_detail = '';
        if ( !$dataorder ) { return $order_detail; }
        $currency           = config_item('currency');
        $cfg_member_type    = config_item('member_status');
        $cfg_product_type   = config_item('product_type');

        $product_detail = '';
        if ( is_serialized($dataorder->products) ) {
            $product_detail = '<table class="table">';
            $unserialize_data = maybe_unserialize($dataorder->products);
                                                    
            $no                 = 1;
            $cart_package       = 0;
            $total_price_pack   = 0;
            $total_qty_pack     = 0;
            $package_name       = '';
            $count_data         = count($unserialize_data);

            foreach ($unserialize_data as $row) {
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 0;
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;
                $_bv            = an_accounting($bv);
                $_price         = an_accounting($price_cart);
                $_subtotal      = an_accounting($subtotal);
                $subtotal       = $qty * $price_cart;

                $total_qty  = 'Qty : <span class="font-weight-bold mr-1">'. $qty .'</span>';
                if ( $price > $price_cart ) {
                    //$total_qty .= '( <s>'. an_accounting($price) .'</s> <span class="text-warning">'. an_accounting($price_cart, $currency) .'</span> )';
                    $total_qty .= '( '. an_accounting($price_cart, $currency) .' )';
                } else {
                    $total_qty .= '( '. an_accounting($price_cart, $currency) .' )';
                }

                $product_detail .= '
                    <tr>
                        <td class="text-capitalize px-1 pl-2 py-2">
                            <span class="text-primary mr-1">'. $product_name .'</span> <span class="small">( '. $_bv .' BV )</span>'. br() . '
                            <span class="small">'. $total_qty .'</span>
                        </td>
                        <td class="text-right px-1 pr-2 py-2">'. $_subtotal .'</td>
                    </tr>';
            }
            $product_detail .= '</table>';
        }

        $status_order   = '';
        if ( $dataorder->status == 0 ) { $status_order = '<span class="badge badge-default">PENDING</span>'; }
        if ( $dataorder->status == 1 ) { $status_order = '<span class="badge badge-info">CONFIRMED</span>'; }
        if ( $dataorder->status == 2 ) { $status_order = '<span class="badge badge-success">DONE</span>'; }
        if ( $dataorder->status == 3 ) { $status_order = '<span class="badge badge-danger">CANCELLED</span>'; }

        $subtotal_cart  = an_accounting($dataorder->subtotal);
        $total_bv       = an_accounting($dataorder->total_bv);
        $total_payment  = an_accounting($dataorder->total_payment);
        $uniquecode     = str_pad($dataorder->unique, 3, '0', STR_PAD_LEFT);

        // Information Detail Product
        $info_product   = '
            <div class="card">
                <div class="card-body pt-3 pb-4">
                    <h6 class="heading-small mb-0">Ringkasan Order</h6>
                    '.$product_detail.'
                    <hr class="mt-0 mb-2">
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Subtotal</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. $subtotal_cart .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Kode Unik</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. $uniquecode .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>'. lang('shipping_fee') .'</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. an_accounting($dataorder->shipping) .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7">
                            <span>
                                '. lang('discount') . ( $dataorder->voucher ? ' (<span class="text-success">'.$dataorder->voucher.'</span>)' : '' ) .'
                            </span>
                        </div>
                        <div class="col-sm-5 text-right">
                            <span class="font-weight-bold">
                                '.( $dataorder->discount ? '<span class="text-success">- '.an_accounting($dataorder->discount).'</span>' : '' ).'
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-6"><span>Total BV</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="font-weight-bold">'. $total_bv .' BV</span>
                        </div>
                    </div>
                    <hr class="mt-2 mb-3">
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">'. lang('total_payment') .'</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">'. $total_payment .'</span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">Status Order</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">'. $status_order .'</span>
                        </div>
                    </div>
                </div>
            </div>';

        // Information Member
        $info_member        = '';
        if ( $getMember = an_get_memberdata_by_id($dataorder->id_member) ) {
            $avatar         = ( empty($getMember->photo) ? 'avatar.png' : $getMember->photo );

            // Information Shipping Address
            $address        = ( !empty($dataorder->address_consumer) ? $dataorder->address_consumer : $dataorder->address );
            if ( $dataorder->village ) {
                $address   .= ', '. ucwords(strtolower( !empty($dataorder->village) ? $dataorder->village_consumer : $dataorder->village ));
            }

            if ( $dataorder->subdistrict ) {
                $address .= ' Kec. '. ucwords(strtolower( !empty($dataorder->subdistrict_consumer) ? $dataorder->subdistrict_consumer : $dataorder->subdistrict ));
            }

            $district_name = '';
            if ( $dataorder->district ) {
                $district_name = ucwords(strtolower( !empty($dataorder->district_consumer) ? $dataorder->district_consumer : $dataorder->district ));
            }
            
            $province_name = '';
            if ( $dataorder->province ) {
                $province_name  = ucwords(strtolower( !empty($dataorder->province_consumer) ? $dataorder->province_consumer : $dataorder->province ));
                $province_name  = str_replace('Dki ', 'DKI ', $province_name);
                $province_name  = str_replace('Di ', 'DI ', $province_name);
            }

            $address .= br() .$district_name .' - '. $province_name;

            // shipping method
            $shipping_title     = lang('shipping_address');
            $_shipping          = '';
            if ( $dataorder->shipping_method == 'ekspedisi' ) {
                $_shipping  = 'Jasa Ekspedisi / Pengiriman';
                if ( $dataorder->courier ) {
                    $_shipping  = strtoupper($dataorder->courier);
                    if ( $dataorder->service ) {
                        $_shipping  .= ' (' . strtoupper($dataorder->service) .')';
                    }
                }
            }
            if ( $dataorder->shipping_method == 'pickup' ) {
                $shipping_title = 'Alamat Penagihan';
                $_shipping      = 'Pickup';
            }
            if ( $dataorder->shipping_method == 'free' ) {
                $_shipping      = 'Jasa Ekspedisi / Pengiriman (FREE)';
            }
            
            $img = BE_IMG_PATH . 'icons/avatar.png';
            if( !empty($avatar) ){
                if( file_exists(BE_IMG_PATH_NB . 'icons/'.$avatar) ){
                    $img = BE_IMG_PATH . 'icons/'.$avatar;
                }
            }
            
            $info_member    = '
                <div class="card mb-4">
                    <div class="card-body py-2">
                        <h6 class="heading-small text-capitalize mb-0">Informasi Reseller</h6>
                        <hr class="mt-0 mb-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="'.$img.'">
                                </a>
                            </div>
                            <div class="col">
                                <h4 class="mb-0">
                                    <a href="#!">'. $getMember->name .'</a>
                                </h4>
                                <p class="text-sm text-muted font-weight-bold mb-0">
                                    <i class="ni ni-single-02 text-muted mr-1"></i> '. $getMember->username .'
                                </p>
                            </div>
                        </div>
                        <hr class="my-3">';
                        
                        if( $dataorder->access_order == "customer" ){
                            $info_member .= '
                            <h6 class="heading-small text-capitalize mb-0">Informasi Konsumen</h6>
                            <hr class="my-1">
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('name') .'</span></div>
                                <div class="col-sm-9"><span>'.$dataorder->name_consumer.'</span></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('reg_no_hp') .'</span></div>
                                <div class="col-sm-9"><span>'.$dataorder->phone_consumer.'</span></div>
                            </div>';
                        }
                        
                        $info_member .= '
                        
                        <h6 class="heading-small text-capitalize mb-0">'. $shipping_title .'</h6>
                        <hr class="my-1">
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('name') .'</span></div>
                            <div class="col-sm-9"><span>'.( !empty($dataorder->name_consumer) ? $dataorder->name_consumer : $dataorder->name ).'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('reg_no_hp') .'</span></div>
                            <div class="col-sm-9"><span>'.( !empty($dataorder->phone_consumer) ? $dataorder->phone_consumer : $dataorder->phone ).'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_email').'</span></div>
                            <div class="col-sm-9"><span class="text-lowecase">'.( !empty($dataorder->email_consumer) ? $dataorder->email_consumer : $dataorder->email ).'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_alamat').'</span><br></div>
                            <div class="col-sm-9"><span class="text-capitalize">'.$address.'</span></div>
                        </div>
                        <hr class="my-3">
                        <h6 class="heading-small text-capitalize mb-0">'. lang('shipping_method') .'</h6>
                        <hr class="my-1">
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">Pengiriman</span></div>
                            <div class="col-sm-9"><span>'.$_shipping.'</span></div>
                        </div>
                    </div>
                </div>';
        }

        $info_stockist      = '';
        $view_stockist      = ( $dataorder->type_order == 'member_order' ) ? true : false;
        if ( $view_stockist && $dataorder->id_stockist ) {
            if ( $getStockist = an_get_memberdata_by_id($dataorder->id_stockist) ) {
                $avatar         = ( empty($getStockist->photo) ? 'avatar.png' : $getStockist->photo );
                $info_stockist  = '
                    <div class="card mb-4">
                        <div class="card-body py-2">
                            <h6 class="heading-small text-capitalize mb-0">Informasi Stockist</h6>
                            <hr class="mt-0 mb-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="'. BE_IMG_PATH .'icons/'.$avatar.'">
                                    </a>
                                </div>
                                <div class="col">
                                    <h4 class="mb-0">
                                        <a href="#!">'. $getStockist->name .'</a>
                                    </h4>
                                    <p class="text-sm text-muted font-weight-bold mb-0">
                                        <i class="ni ni-single-02 text-muted mr-1"></i> '. $getStockist->username .'
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">Telp</span></div>
                                <div class="col-sm-9"><span>'.$getStockist->phone.'</span></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_email').'</span></div>
                                <div class="col-sm-9"><span class="text-lowecase">'.$getStockist->email.'</span></div>
                            </div>
                        </div>
                    </div>';
            }
        }
        
        $order_detail   = '
            <div class="row">
                <div class="col-md-5 px-2">
                    '. $info_member .'
                    '. $info_stockist .'
                </div>
                <div class="col-md-7 px-2">
                    '.$info_product.'
                </div>
            </div>
        ';
        return $order_detail;
    }
    
    /**
     * Get Shop Order Detail for Track Order Function
     */
    function getshoporderdetailtrack()
    {
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url(), 'refresh'); }
        
        $s_invoice      = $this->input->post('invoice');
        $s_invoice      = an_isset($s_invoice, '', '', true);
        
        if (!$s_invoice) {
            $data = array('status' => 'error', 'message' => 'Nomor Invoice harus di isi. Silahkan inputkan Nomor Invoice!');
            die(json_encode($data));
        }

        if (!$data_order = $this->Model_Shop->get_shop_order_by('invoice', $s_invoice)) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan!');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlshoporderdetailtrack($data_order);
        $data = array('status' => 'success', 'message' => 'Produk Order', 'data' => $set_html);
        die(json_encode($data));
    }
    
    /**
     * Set HTML Shop Order Detail function.
     */
    private function sethtmlshoporderdetailtrack($dataorder){
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $order_detail = '';
        if ( !$dataorder ) { return $order_detail; }
        $currency           = config_item('currency');
        $cfg_member_type    = config_item('member_status');
        $cfg_product_type   = config_item('product_type');

        $product_detail = '';
        if ( is_serialized($dataorder->products) ) {
            $product_detail = '<table class="table">';
            $unserialize_data = maybe_unserialize($dataorder->products);
                                                    
            $no                 = 1;
            $cart_package       = 0;
            $total_price_pack   = 0;
            $total_qty_pack     = 0;
            $package_name       = '';
            $count_data         = count($unserialize_data);

            foreach ($unserialize_data as $row) {
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 0;
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;
                $_bv            = an_accounting($bv);
                $_price         = an_accounting($price_cart);
                $_subtotal      = an_accounting($subtotal);
                $subtotal       = $qty * $price_cart;

                $total_qty  = 'Qty : <span class="font-weight-bold mr-1">'. $qty .'</span>';
                if ( $price > $price_cart ) {
                    $total_qty .= '( <s>'. an_accounting($price) .'</s> <span class="text-warning">'. an_accounting($price_cart, $currency) .'</span> )';
                } else {
                    $total_qty .= '( '. an_accounting($price_cart, $currency) .' )';
                }

                $product_detail .= '
                    <tr>
                        <td class="text-capitalize px-1 pl-2 py-2">
                            <span class="text-primary mr-1">'. $product_name .'</span> <span class="small">( '. $_bv .' BV )</span>'. br() . '
                            <span class="small">'. $total_qty .'</span>
                        </td>
                        <td class="text-right px-1 pr-2 py-2">'. $_subtotal .'</td>
                    </tr>';
            }
            $product_detail .= '</table>';
        }

        $status_order   = '';
        if ( $dataorder->status == 0 ) { $status_order = '<span class="badge badge-warning">PENDING</span>'; }
        if ( $dataorder->status == 1 ) { $status_order = '<span class="badge badge-info">CONFIRMED</span>'; }
        if ( $dataorder->status == 2 ) { $status_order = '<span class="badge badge-success">DONE</span>'; }
        if ( $dataorder->status == 3 ) { $status_order = '<span class="badge badge-danger">CANCELLED</span>'; }

        $subtotal_cart  = an_accounting($dataorder->subtotal);
        $total_bv       = an_accounting($dataorder->total_bv);
        $total_payment  = an_accounting($dataorder->total_payment);
        $uniquecode     = str_pad($dataorder->unique, 3, '0', STR_PAD_LEFT);

        // Information Detail Product
        $info_product   = '
            <div class="card">
                <div class="card-body pt-3 pb-4">
                    <h6 class="heading-small mb-0">Ringkasan Order</h6>
                    '.$product_detail.'
                    <hr class="mt-0 mb-2">
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Subtotal</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. $subtotal_cart .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>Kode Unik</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. $uniquecode .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7"><span>'. lang('shipping_fee') .'</span></div>
                        <div class="col-sm-5 text-right"><span class="font-weight-bold">'. an_accounting($dataorder->shipping) .'</span></div>
                    </div>
                    <div class="row px-2">
                        <div class="col-sm-7">
                            <span>
                                '. lang('discount') . ( $dataorder->voucher ? ' (<span class="text-success">'.$dataorder->voucher.'</span>)' : '' ) .'
                            </span>
                        </div>
                        <div class="col-sm-5 text-right">
                            <span class="font-weight-bold">
                                '.( $dataorder->discount ? '<span class="text-success">- '.an_accounting($dataorder->discount).'</span>' : '' ).'
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-6"><span>Total BV</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="font-weight-bold">'. $total_bv .' BV</span>
                        </div>
                    </div>
                    <hr class="mt-2 mb-3">
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">'. lang('total_payment') .'</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">'. $total_payment .'</span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">Status Order</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">'. $status_order .'</span>
                        </div>
                    </div>
                </div>
            </div>';

        // Information Member
        $info_member        = '';
        if( $dataorder->access_order == 'self' ){
            if ( $getMember = an_get_memberdata_by_id($dataorder->id_member) ) {
                $avatar         = ( empty($getMember->photo) ? 'avatar.png' : $getMember->photo );
    
                // Information Shipping Address
                $address        = $dataorder->address;
                if ( $dataorder->village ) {
                    $address   .= ', '. ucwords(strtolower($dataorder->village));
                }
    
                if ( $dataorder->subdistrict ) {
                    $address .= ' Kec. '. ucwords(strtolower($dataorder->subdistrict));
                }
    
                $district_name = '';
                if ( $dataorder->district ) {
                    $district_name = ucwords(strtolower($dataorder->district));
                }
                
                $province_name = '';
                if ( $dataorder->province ) {
                    $province_name  = ucwords(strtolower($dataorder->province));
                    $province_name  = str_replace('Dki ', 'DKI ', $province_name);
                    $province_name  = str_replace('Di ', 'DI ', $province_name);
                }
    
                $address .= br() .$district_name .' - '. $province_name;
    
                // shipping method
                $shipping_title     = lang('shipping_address');
                $_shipping          = '';
                if ( $dataorder->shipping_method == 'ekspedisi' ) {
                    $_shipping  = 'Jasa Ekspedisi / Pengiriman';
                    if ( $dataorder->courier ) {
                        $_shipping  = strtoupper($dataorder->courier);
                        if ( $dataorder->service ) {
                            $_shipping  .= ' (' . strtoupper($dataorder->service) .')';
                        }
                    }
                }
                if ( $dataorder->shipping_method == 'pickup' ) {
                    $shipping_title = 'Alamat Penagihan';
                    $_shipping      = 'Pickup';
                }
                
                if( file_exists(BE_IMG_PATH_NB . 'icons/'.$avatar) ){
                    $img = BE_IMG_PATH_NB . 'icons/'.$avatar;
                }else{
                    $img = BE_IMG_PATH_NB . 'icons/avatar.png';
                }
                
                $info_member    = '
                    <div class="card mb-4">
                        <div class="card-body py-2">
                            <h6 class="heading-small text-capitalize mb-0">Informasi Member</h6>
                            <hr class="mt-0 mb-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="'.$img.'">
                                    </a>
                                </div>
                                <div class="col">
                                    <h4 class="mb-0">
                                        <a href="#!">'. $getMember->name .'</a>
                                    </h4>
                                    <p class="text-sm text-muted font-weight-bold mb-0">
                                        <i class="ni ni-single-02 text-muted mr-1"></i> '. $getMember->username .'
                                    </p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <h6 class="heading-small text-capitalize mb-0">'. $shipping_title .'</h6>
                            <hr class="my-1">
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('name') .'</span></div>
                                <div class="col-sm-9"><span>'.$dataorder->name.'</span></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('reg_no_hp') .'</span></div>
                                <div class="col-sm-9"><span>'.$dataorder->phone.'</span></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_email').'</span></div>
                                <div class="col-sm-9"><span class="text-lowecase">'.$dataorder->email.'</span></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_alamat').'</span><br></div>
                                <div class="col-sm-9"><span class="text-capitalize">'.$address.'</span></div>
                            </div>
                            <hr class="my-3">
                            <h6 class="heading-small text-capitalize mb-0">'. lang('shipping_method') .'</h6>
                            <hr class="my-1">
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">Pengiriman</span></div>
                                <div class="col-sm-9"><span>'.$_shipping.'</span></div>
                            </div>
                        </div>
                    </div>';
            }
        }else{
            // Information Shipping Address
            $address        = $dataorder->address_consumer;
            if ( $dataorder->village_consumer ) {
                $address   .= ', '. ucwords(strtolower($dataorder->village_consumer));
            }

            if ( $dataorder->subdistrict_consumer ) {
                $address .= ' Kec. '. ucwords(strtolower($dataorder->subdistrict_consumer));
            }

            $district_name = '';
            if ( $dataorder->district_consumer ) {
                $district_name = ucwords(strtolower($dataorder->district_consumer));
            }
            
            $province_name = '';
            if ( $dataorder->province_consumer ) {
                $province_name  = ucwords(strtolower($dataorder->province_consumer));
                $province_name  = str_replace('Dki ', 'DKI ', $province_name);
                $province_name  = str_replace('Di ', 'DI ', $province_name);
            }

            $address .= br() .$district_name .' - '. $province_name;

            // shipping method
            $shipping_title     = lang('shipping_address');
            $_shipping          = '';
            if ( $dataorder->shipping_method == 'ekspedisi' ) {
                $_shipping  = 'Jasa Ekspedisi / Pengiriman';
                if ( $dataorder->courier ) {
                    $_shipping  = strtoupper($dataorder->courier);
                    if ( $dataorder->service ) {
                        $_shipping  .= ' (' . strtoupper($dataorder->service) .')';
                    }
                }
            }
            if ( $dataorder->shipping_method == 'pickup' ) {
                $shipping_title = 'Alamat Penagihan';
                $_shipping      = 'Pickup';
            }
            
            $info_member    = '
                <div class="card mb-4">
                    <div class="card-body py-2">
                        <h6 class="heading-small text-capitalize mb-0">Informasi Member</h6>
                        <hr class="mt-0 mb-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="mb-0">
                                    <a href="#!">'. $dataorder->name_consumer .'</a>
                                </h4>
                            </div>
                        </div>
                        <hr class="my-3">
                        <h6 class="heading-small text-capitalize mb-0">'. $shipping_title .'</h6>
                        <hr class="my-1">
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('name') .'</span></div>
                            <div class="col-sm-9"><span>'.$dataorder->name_consumer.'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'. lang('reg_no_hp') .'</span></div>
                            <div class="col-sm-9"><span>'.$dataorder->phone_consumer.'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_email').'</span></div>
                            <div class="col-sm-9"><span class="text-lowecase">'.$dataorder->email_consumer.'</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_alamat').'</span><br></div>
                            <div class="col-sm-9"><span class="text-capitalize">'.$address.'</span></div>
                        </div>
                        <hr class="my-3">
                        <h6 class="heading-small text-capitalize mb-0">'. lang('shipping_method') .'</h6>
                        <hr class="my-1">
                        <div class="row">
                            <div class="col-sm-3"><span class="text-capitalize text-muted">Pengiriman</span></div>
                            <div class="col-sm-9"><span>'.$_shipping.'</span></div>
                        </div>
                    </div>
                </div>';
        }
        
        $info_stockist      = '';
        $view_stockist      = ( $dataorder->type_order == 'member_order' ) ? true : false;
        if ( $view_stockist && $dataorder->id_stockist ) {
            if ( $getStockist = an_get_memberdata_by_id($dataorder->id_stockist) ) {
                $avatar         = ( empty($getStockist->photo) ? 'avatar.png' : $getStockist->photo );
                $info_stockist  = '
                    <div class="card mb-4">
                        <div class="card-body py-2">
                            <h6 class="heading-small text-capitalize mb-0">Informasi Stockist</h6>
                            <hr class="mt-0 mb-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar rounded-circle">
                                        <img alt="Image placeholder" src="'. BE_IMG_PATH .'icons/'.$avatar.'">
                                    </a>
                                </div>
                                <div class="col">
                                    <h4 class="mb-0">
                                        <a href="#!">'. $getStockist->name .'</a>
                                    </h4>
                                    <p class="text-sm text-muted font-weight-bold mb-0">
                                        <i class="ni ni-single-02 text-muted mr-1"></i> '. $getStockist->username .'
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">Telp</span></div>
                                <div class="col-sm-9"><span>'.$getStockist->phone.'</span></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3"><span class="text-capitalize text-muted">'.lang('reg_email').'</span></div>
                                <div class="col-sm-9"><span class="text-lowecase">'.$getStockist->email.'</span></div>
                            </div>
                        </div>
                    </div>';
            }
        }
        
        $order_detail   = '
            <div class="row">
                <div class="col-md-12 px-2">
                    '. $info_member .'
                    '. $info_stockist .'
                </div>
                <div class="col-md-12 px-2">
                    '.$info_product.'
                </div>
            </div>
        ';
        return $order_detail;
    }

    // =============================================================================================
    // ACTION DETAIL PRODUCT
    // =============================================================================================

    /**
     * Get Product Detail Function
     */
    function getproductdetailshopping( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/shoplist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = an_decrypt($id);
        if ( ! $productdata = an_products($id) ) {
            $data = array('status' => 'error', 'message' => 'Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlproductdetailshopping($productdata, 'agent');
        $data = array('status'=>'success', 'message'=>'Produk Detail', 'data'=>$set_html );
        die(json_encode($data));
    }

    /**
     * Set HTML Product Detail function.
     */
    private function sethtmlproductdetailshopping($productdata = 0){
        $product_detail     = '';
        if ( !$productdata ) { return $product_detail; }

        $this->load->helper('shop_helper');

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $currency           = config_item('currency');
        $shopping_lock      = config_item('shopping_lock');
        $product_id         = an_encrypt($productdata->id);

        // --------------------------------------
        // Get Cart Content
        // --------------------------------------
        $cart_content       = an_cart_contents();
        $product_cart       = isset($cart_content['data']) ? $cart_content['data'] : array();
        $in_cart            = false;
        if ( $product_cart ) {
            foreach ($product_cart as $item) {
                $cart_product_id = isset($item['id']) ? $item['id'] : 'none';
                if ( $cart_product_id == $productdata->id ) {
                    $in_cart = true;
                }
            }
        }   

        $btn_addtocart      = '';
        if ( !$shopping_lock ) {
            $btn_addtocart  = '<a class="btn btn-default add-to-cart text-uppercase" 
                                href="'. base_url('shopping/addToCart') .'" 
                                data-type="addcart" 
                                data-cart="'. $product_id .'">
                                <span class="shopping-cart-loading mr-2" style="display:none"><img src="'. BE_IMG_PATH .'loading-spinner-blue.gif"></span>
                                ADD TO CART</a>';
            if ( $in_cart ) {
                $btn_addtocart = '<a class="btn btn-default add-to-cart text-uppercase" href="'. base_url('shopping/cart') .'" data-type="cart">GO TO CART</a>';
            }
        }

        $img_src            = an_product_image($productdata->image, false); 
        $category           = '';
        if ( $productdata->id_category ) {
            $categorydata   = an_product_category($productdata->id_category);
            $category       = isset($categorydata->name) ? $categorydata->name : '';
            if ( $category ) {
                $category   = '<h6 class="text-muted text-capitalize ls-1 mb-0" style="font-size: 0.75rem;"><i class="ni ni-tag mr-1"></i>'. $category .'</h6>';
            }
        }

        $product_price      = ( $current_member->as_stockist >= 1 ) ? $productdata->price : $productdata->price_member;
        $view_price         = an_accounting($product_price, $currency);

        $product_detail     = '
            <div class="row">
                <div class="col-xl-5 order-xl-1 bg-secondary py-2">
                    <a href="'.$img_src.'" target="_blank">
                        <div class="thumbnail mb-1">
                            <img class="img-thumbnail" width="100%" src="'.$img_src.'" style="cursor: pointer;">
                        </div>
                    </a>
                </div>
                <div class="col-xl-7 order-xl-2 pt-3">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="mb-0 text-capitalize text-capitalize">'.$productdata->name.'</h4>
                                    '. $category .'
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-3">
                            <h2 class="h2 text-warning font-weight-bold">'. $view_price .'</h2>
                            <hr class="my-3">
                            '. $btn_addtocart .'
                        </div>
                    </div>      
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0 text-capitalize text-capitalize">'. lang('information') .'</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-2 pb-3">
                            '. $productdata->description .'
                        </div>
                    </div>  
                </div>
            </div>
        ';
        return $product_detail;
    }
}

/* End of file Shopping.php */
/* Location: ./app/controllers/Shopping.php */
