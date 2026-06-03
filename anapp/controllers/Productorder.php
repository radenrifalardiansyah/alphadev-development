<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Productorder Controller.
 *
 * @class     Productorder
 * @author    Yuda
 * @version   1.0.0
 */
class Productorder extends AN_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('shop_helper');
    }

    // =============================================================================================
    // LIST DATA PRODUCT ORDER
    // =============================================================================================

    /**
     * Sales Order List Data function.
     */
    function salesorderlistsdata()
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
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
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

        $s_invoice          = $this->input->$search_method('search_invoice');
        $s_invoice          = an_isset($s_invoice, '');
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = an_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = an_isset($s_name, '');
        $s_payment_min      = $this->input->$search_method('search_nominal_min');
        $s_payment_min      = an_isset($s_payment_min, '');
        $s_payment_max      = $this->input->$search_method('search_nominal_max');
        $s_payment_max      = an_isset($s_payment_max, '');
        $s_payment_type     = $this->input->$search_method('search_payment_type');
        $s_payment_type     = an_isset($s_payment_type, '');
        $s_payment_method   = $this->input->$search_method('search_payment_method');
        $s_payment_method   = an_isset($s_payment_method, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = an_isset($s_status, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = an_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = an_isset($s_date_max, '');
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirm_min');
        $s_dateconfirm_min  = an_isset($s_dateconfirm_min, '');
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirm_max');
        $s_dateconfirm_max  = an_isset($s_dateconfirm_max, '');
        $s_dateapproved_min = $this->input->$search_method('search_dateapproved_min');
        $s_dateapproved_min = an_isset($s_dateapproved_min, '');
        $s_dateapproved_max = $this->input->$search_method('search_dateapproved_max');
        $s_dateapproved_max = an_isset($s_dateapproved_max, '');
        $s_datepaid_min     = $this->input->$search_method('search_datepaid_min');
        $s_datepaid_min     = an_isset($s_datepaid_min, '');
        $s_datepaid_max     = $this->input->$search_method('search_datepaid_max');
        $s_datepaid_max     = an_isset($s_datepaid_max, '');
        $s_datedone_min     = $this->input->$search_method('search_datedone_min');
        $s_datedone_min     = an_isset($s_datedone_min, '');
        $s_datedone_max     = $this->input->$search_method('search_datedone_max');
        $s_datedone_max     = an_isset($s_datedone_max, '');

        if (!empty($s_invoice))         { $condition .= ' AND %invoice% LIKE CONCAT("%", ?, "%") '; $params[] = $s_invoice; }
        if (!empty($s_username))        { $condition .= ' AND %username% LIKE CONCAT("%", ?, "%") '; $params[] = $s_username; }
        if (!empty($s_name))            { $condition .= ' AND %name% LIKE CONCAT("%", ?, "%") '; $params[] = $s_name; }
        if (!empty($s_payment_type))    { $condition .= ' AND payment_type = ? '; $params[] = $s_payment_type; }
        if (!empty($s_payment_method))  { $condition .= ' AND payment_method = ? '; $params[] = $s_payment_method; }
        if (!empty($s_payment_min))     { $condition .= ' AND total_payment >= ? '; $params[] = $s_payment_min . ''; }
        if (!empty($s_payment_max))     { $condition .= ' AND total_payment <= ? '; $params[] = $s_payment_max . ''; }
        if (!empty($s_date_min))        { $condition .= ' AND DATE(%datecreated%) >= "? '; $params[] = $s_date_min . '"'; }
        if (!empty($s_date_max))        { $condition .= ' AND DATE(%datecreated%) <= "? '; $params[] = $s_date_max . '"'; }
        if (!empty($s_dateconfirm_min)) { $condition .= ' AND DATE(%dateconfirm%) >= "? '; $params[] = $s_dateconfirm_min . '"'; }
        if (!empty($s_dateconfirm_max)) { $condition .= ' AND DATE(%dateconfirm%) <= "? '; $params[] = $s_dateconfirm_max . '"'; }
        if (!empty($s_status)) {
            if ($s_status == 'pending')     { $condition .= ' AND %status% = 0'; }
            if ($s_status == 'confirmed')   { $condition .= ' AND %status% = 1'; }
            if ($s_status == 'cancelled')   { $condition .= ' AND %status% = 2'; }
        }

        if ($is_admin) {
            if ($column == 1)       { $order_by = '%invoice% ' . $sort; } 
            elseif ($column == 2)   { $order_by = '%username% ' . $sort; } 
            elseif ($column == 3)   { $order_by = '%name% ' . $sort; } 
            elseif ($column == 4)   { $order_by = '%customer% ' . $sort; } 
            elseif ($column == 5)   { $order_by = '%phone% ' . $sort; } 
            elseif ($column == 6)   { $order_by = 'total_payment ' . $sort; } 
            elseif ($column == 7)   { $order_by = 'payment_type ' . $sort; } 
            elseif ($column == 8)   { $order_by = 'payment_method ' . $sort; } 
            elseif ($column == 9)   { $order_by = 'products ' . $sort; } 
            elseif ($column == 10)  { $order_by = '%status% ' . $sort; } 
            elseif ($column == 11)  { $order_by = '%datecreated% ' . $sort; } 
            elseif ($column == 12)  { $order_by = '%dateconfirm% ' . $sort; } 
            elseif ($column == 13)  { $order_by = '%dateapproved% ' . $sort; } 
            elseif ($column == 14)  { $order_by = '%datepaid% ' . $sort; } 
            elseif ($column == 15)  { $order_by = '%datedone% ' . $sort; }
            $order_by = ($order_by) ? $order_by .', %status% ASC' : $order_by;
        } else {
            if ($column == 1)       { $order_by = '%invoice% ' . $sort; } 
            elseif ($column == 2)   { $order_by = '%customer% ' . $sort; } 
            elseif ($column == 3)   { $order_by = '%phone% ' . $sort; } 
            elseif ($column == 4)   { $order_by = 'products ' . $sort; } 
            elseif ($column == 5)   { $order_by = 'total_payment ' . $sort; } 
            elseif ($column == 6)   { $order_by = 'payment_type ' . $sort; } 
            elseif ($column == 7)   { $order_by = 'payment_method ' . $sort; } 
            elseif ($column == 8)   { $order_by = '%status% ' . $sort; } 
            elseif ($column == 9)   { $order_by = '%datecreated% ' . $sort; } 
            elseif ($column == 10)  { $order_by = '%dateconfirm% ' . $sort; }
        }

        $data_list          = $this->Model_Shop->get_all_shop_order_data($limit, $offset, $condition, $order_by, $params);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $cfg_pay_type   = config_item('payment_type');
            $cfg_pay_method = config_item('payment_method');
            $access         = TRUE;
            $access_confirm = $access_approved = $access_paid = $access_done = $access_cancel = FALSE;
            if ($is_admin) {
                if ($staff = an_get_current_staff()) {
                    if ($staff->access == 'partial') {
                        $role   = array();
                        if ($staff->role) {
                            $role = $staff->role;
                        }
                        if (!empty($role)) {
                            if (in_array(STAFF_ACCESS6, $role)) {
                                $access_confirm = $access_cancel = TRUE;
                            }
                            if (in_array(STAFF_ACCESS7, $role)) {
                                $access_approved = TRUE;
                            }
                            if (in_array(STAFF_ACCESS8, $role)) {
                                $access_paid = TRUE;
                            }
                            if (in_array(STAFF_ACCESS9, $role)) {
                                $access_done = TRUE;
                            }
                        }
                    } else {
                        $access_confirm = $access_approved = $access_paid = $access_done = $access_cancel = TRUE;
                    }
                } else {
                    $access_confirm = $access_approved = $access_paid = $access_done = $access_cancel = TRUE;
                }
            }
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $id_member      = an_encrypt($row->id_member);
                $username       = an_strong(strtoupper($row->username));
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = strtoupper($row->membername);
                $customer       = an_strong(strtoupper($row->name));
                $phone          = $row->phone;

                $status         = '';
                if ($row->status == 0) { $status = '<span class="badge badge-sm badge-primary">PENDING</span>'; }
                if ($row->status == 1) { $status = '<span class="badge badge-sm badge-default">CONFIRMED</span>'; }
                if ($row->status == 2) { $status = '<span class="badge badge-sm badge-danger">CANCELLED</span>'; }

                $payment_method = '';
                if ($cfg_pay_method) {
                    foreach ($cfg_pay_method as $key => $method) {
                        if ($key == $row->payment_method) {
                            $lbl_class = 'primary';
                            if ($key == 'cash')     { $lbl_class = 'default'; }
                            if ($key == 'transfer') { $lbl_class = 'info'; }
                            $payment_method = '<span class="badge badge-sm badge-' . $lbl_class . '">' . strtoupper($method) . '</span>';
                        }
                    }
                }

                $datemodified   = date('d M y H:i', strtotime($row->datemodified));
                $dateconfirmed  = ($row->dateconfirmed && $row->dateconfirmed != '0000-00-00 00:00:00' && $row->status != 0) ? date('d M y H:i', strtotime($row->dateconfirmed)) : '-';

                $courier       = '<b>Resi</b> : <span class="text-warning font-weight-bold">' . ($row->resi ? $row->resi : '-') . '</span>' . br();
                $courier       .= '<b>' . lang('courier') . '</b> : ' . strtoupper($row->courier) . br();
                $courier       .= '<b>Layanan</b> : ' . strtoupper($row->service);

                $btn_invoice    = '<a href="' . base_url('invoice/' . $id) . '" 
                                    class="btn btn-sm btn_block btn-outline-default" target="_blank"><i class="fa fa-file"></i> ' . $row->invoice . '</a>';

                $btn_product    = '<a href="javascript:;" 
                                    data-url="' . base_url('productorder/getsalesorderdetail/' . $id) . '" 
                                    data-invoice="' . $row->invoice . '"
                                    class="btn btn-sm btn-block btn-outline-default btn-shop-order-detail">
                                    <i class="ni ni-bag-17 mr-1"></i> Detail Order</a>';


                $btn_confirm    = $btn_cancel = $btn_payment = '';
                if ($row->status == 0) {
                    $btn_cancel = '<a href="javascript:;" 
                                        data-url="' . base_url('productorder/salesordercancel/' . $id) . '" 
                                        data-invoice="' . $row->invoice . '"
                                        data-name="' . $row->name . '"
                                        data-total="' . an_accounting($row->total_payment, $currency) . '"
                                        data-message="Apakah anda yakin akan Batalkan pesanan ini ?"
                                        class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-action" 
                                        title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';

                    if ($access_confirm) {
                        $detail     = an_extract_products_order($row);
                        $discount   = $row->discount ? an_accounting($row->total_payment, $currency) : 0;
                        $subtotal   = $row->subtotal ? $row->subtotal : 0;
                        $unique     = isset($row->unique) ? sprintf('%03d', $row->unique) : 0;
                        $name       = isset($row->name) ? $row->name : '-';
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="' . base_url('productorder/salesorderconfirm/' . $id) . '" 
                                            data-invoice="' . $row->invoice . '"
                                            data-order=\'' . json_encode($detail) . '\'
                                            data-subtotal="' . an_accounting($subtotal, $currency) . '"
                                            data-unique="' . $unique . '"
                                            data-total="' . an_accounting($row->total_payment, $currency) . '"
                                            data-name="' . $name . '"
                                            data-discount="' . $discount . '"
                                            data-message="Apakah anda yakin akan Konfirmasi pembayaran DP atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pembayaran DP"><i class="fa fa-check"></i> DP Confirmed</a>';
                    }

                    if (!$access_cancel) {
                        $btn_cancel = '';
                    }
                }

                if ($row->status == 1) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-default btn-tooltip" title="DP CONFIRMED"><i class="fa fa-check"></i></a>';
                    if ($access_approved) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="' . base_url('productorder/salesorderapproved/' . $id) . '" 
                                            data-invoice="' . $row->invoice . '"
                                            data-name="' . $row->name . '"
                                            data-total="' . an_accounting($row->total_payment, $currency) . '"
                                            data-message="Apakah anda yakin akan Approved Survey atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-default btn-tooltip btn-shop-order-action" 
                                            title="Approved Survey"><i class="fa fa-check mr-1"></i> Survey Approved</a>';
                    }
                }

                if ($row->status == 2) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-info btn-tooltip" title="SURVEY APPROVED"><i class="fa fa-check"></i></a>';
                    if ($access_paid) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="' . base_url('productorder/salesorderpaid/' . $id) . '" 
                                            data-invoice="' . $row->invoice . '"
                                            data-name="' . $row->name . '"
                                            data-total="' . an_accounting($row->total_payment, $currency) . '"
                                            data-message="Apakah anda yakin akan Konfirmasi Pembayaran untuk Pelunasan Invoice atas pesanan ini ?"
                                            class="btn btn-sm btn-block btn-info btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Pelunasan Invoice"><i class="fa fa-check mr-1"></i> Invoice Paid</a>';
                    }
                }

                if ($row->status == 3) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-info btn-tooltip" title="PAID"><i class="fa fa-check"></i></a>';
                    if ($access_done) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="' . base_url('productorder/salesorderdone/' . $id) . '" 
                                            data-invoice="' . $row->invoice . '"
                                            data-name="' . $row->name . '"
                                            data-total="' . an_accounting($row->total_payment, $currency) . '"
                                            data-message="Apakah anda yakin akan Konfirmasi Instalasi Unit pesanan ini ?"
                                            class="btn btn-sm btn-block btn-success btn-tooltip btn-shop-order-action" 
                                            title="Konfirmasi Instalasi Selesai"><i class="fa fa-check mr-1"></i> Installation Unit Done</a>';
                    }
                }

                if ($row->status == 4) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-block btn-outline-success"><i class="fa fa-check"></i> Installation Unit Done</a>';
                }

                $datatables     = array(
                    an_center($i),
                    an_center($btn_invoice)
                );

                if ($is_admin) {
                    $datatables[]   = an_center($username);
                    $datatables[]   = $name;
                }

                $datatables[]       = an_center($btn_product);
                $datatables[]       = an_accounting($row->total_payment, '', TRUE);
                $datatables[]       = an_accounting($row->total_bv, '', TRUE);
                $datatables[]       = an_center($payment_method);
                $datatables[]       = an_center(date('j M y @H:i', strtotime($row->datecreated)));
                $datatables[]       = '<div class="column-date-confirm">'.an_center($dateconfirmed).'</div>';
                $datatables[]       = '<div class="column-date-cancel">'.an_center($datemodified).'</div>';
                $datatables[]       = an_center($btn_confirm . $btn_cancel);

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
    // ACTION PRODUCT ORDER
    // =============================================================================================

    /**
     * Confirm Agent Order Function
     */
    function savesalesorder()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('salesorder'), 'refresh');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'Order produk tidak berhasil.');

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        if ($is_admin) {
            $data['message'] = 'Maaf, Admin tidak dapat input sales order ini !';
            die(json_encode($data));
        }

        // POST Input Form
        $name               = trim($this->input->post('name'));
        $name               = an_isset($name, '');
        $phone_home         = trim($this->input->post('phone_home'));
        $phone_home         = an_isset($phone_home, '');
        $phone              = trim($this->input->post('phone'));
        $phone              = an_isset($phone, '');
        $email              = trim($this->input->post('email'));
        $email              = an_isset($email, '');
        $province           = trim($this->input->post('province'));
        $province           = an_isset($province, '');
        $district           = trim($this->input->post('district'));
        $district           = an_isset($district, '');
        $subdistrict        = trim($this->input->post('subdistrict'));
        $subdistrict        = an_isset($subdistrict, '');
        $address            = trim($this->input->post('address'));
        $address            = an_isset($address, '');
        $payment_type       = trim($this->input->post('payment_type'));
        $payment_type       = an_isset($payment_type, '');
        $payment_method     = trim($this->input->post('payment_method'));
        $payment_method     = an_isset($payment_method, '');

        $voucher            = trim($this->input->post('voucher'));
        $voucher            = an_isset($voucher, '');
        $total_discount     = trim($this->input->post('discount'));
        $total_discount     = an_isset($total_discount, '');
        $down_payment       = trim($this->input->post('down_payment'));
        $down_payment       = an_isset($down_payment, '');


        ## Validation Form --------------------------------------------------------------
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('phone', 'No. HP/WA', 'numeric|required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('province', 'Provinsi', 'required');
        $this->form_validation->set_rules('district', 'Kabupaten/Kota', 'required');
        $this->form_validation->set_rules('subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('address', 'Alamat', 'required');

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $data['message'] = validation_errors();
            die(json_encode($data));
        }

        ## Get Data Product Order ---------------------------------------------------
        $post_products      = $this->input->post('products');
        if (!$post_products) {
            $data['message'] = 'Order produk tidak berhasil. Data produk tidak ditemukan.';
            die(json_encode($data));
        }

        if (substr($phone, 0, 1) != '0') {
            $phone          = '0' . $phone;
        }

        ## Set Data Product Detail ---------------------------------------------------
        $data_product       = array();
        $total_point        = 0;
        $total_qty          = 0;
        $total_pv           = 0;
        $total_unit         = 0;
        $total_price        = 0;
        $total_price_credit = 0;
        $total_weight       = 0;
        foreach ($post_products as $item) {
            $productId      = isset($item['id']) ? an_decrypt($item['id']) : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            if (!$productId || !$qty) {
                continue;
            }
            if (!$getProduct = an_products($productId, true)) {
                continue;
            }

            $product_name           = isset($getProduct->name) ? $getProduct->name : '';
            $product_pv             = isset($getProduct->pv) ? $getProduct->pv : 0;
            $product_price          = isset($getProduct->price) ? $getProduct->price : 0;
            $product_price_credit   = isset($getProduct->price) ? $getProduct->price : 0;
            $product_weight         = isset($getProduct->weight) ? $getProduct->weight : 0;
            $product_unit           = isset($getProduct->unit) ? ($getProduct->unit + 0) : 0;

            $subtotal               = ($qty * $product_price);
            $subtotal_credit        = ($qty * $product_price_credit);
            $subtotal_weight        = ($qty * $product_weight);
            $subtotal_unit          = ($qty * $product_unit);
            $subtotal_pv            = ($qty * $product_pv);

            $data_product[] = array(
                'id'                => $productId,
                'qty'               => $qty,
                'name'              => $product_name,
                'pv'                => $product_pv,
                'unit'              => $product_unit,
                'price'             => ($payment_method == 'credit') ? $product_price_credit : $product_price,
                'price_cash'        => $product_price,
                'price_credit'      => $product_price_credit,
                'price_order'       => ($payment_method == 'credit') ? $product_price_credit : $product_price,
                'weight'            => $product_weight,
                'subtotal'          => ($payment_method == 'credit') ? $subtotal_credit : $subtotal,
                'subtotal_cash'     => $subtotal,
                'subtotal_credit'   => $subtotal_credit,
                'subtotal_order'    => ($payment_method == 'credit') ? $subtotal_credit : $subtotal,
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

        if (!$data_product) {
            $data['message'] = 'Order produk tidak berhasil. Data produk tidak ditemukan.';
            die(json_encode($data));
        }

        $province_id        = $province;
        $province_name      = '';
        if ($getProvince = an_provinces($province)) {
            $province_name  = $getProvince->province_name;
        }
        $city_id            = $district;
        $city_name          = '';
        if ($getCity = an_districts($city_id)) {
            $city_name      = $getCity->district_type . ' ' . $getCity->district_name;
        }
        $subdistrict_id     = $subdistrict;
        $subdistrict_name   = '';
        if ($getSubdistrict = an_subdistricts($subdistrict_id)) {
            $subdistrict_name = $getSubdistrict->subdistrict_name;
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        ## Set Data Sales Order ---------------------------------------------------
        $invoice_prefix         = config_item('invoice_prefix');
        $invoice_number         = an_generate_invoice();
        $invoice                = $invoice_prefix . $invoice_number; // XX-000001
        $code_unique            = an_generate_shop_order();
        $total_payment_cash     = $total_price + $code_unique - $total_discount;
        $total_payment_credit   = $total_price_credit + $code_unique - $total_discount;
        $total_payment          = ($payment_method == 'credit') ? $total_payment_credit : $total_payment_cash;

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
            'down_payment'      => str_replace('.', '', $down_payment),
            'voucher'           => $voucher,

            'payment_type'      => $payment_type,
            'payment_method'    => $payment_method,
            'shipping_method'   => '',
            'name'              => ucwords(strtolower($name)),
            'phone'             => $phone,
            'email'             => strtolower($email),
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => $address,
            'created_by'        => $current_member->username,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        // -------------------------------------------------
        // Save Shop Order
        // -------------------------------------------------
        $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if (!$shop_order_id) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Order produk tidak berhasil. Terjadi pada kesalahan data transaksi.';
            die(json_encode($data));
        }

        $data_order_detail = array();
        foreach ($data_product as $key => $row) {
            $price_order        = ($payment_method == 'credit') ? $row['price_credit'] : $row['price'];
            $subtotal_order     = ($payment_method == 'credit') ? $row['subtotal_credit'] : $row['subtotal'];
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

        if (!$data_order_detail) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Order produk tidak berhasil. Terjadi kesalahan pada data detail transaksi.';
            die(json_encode($data));
        }

        foreach ($data_order_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
            if (!$order_detail_saved) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Order produk tidak berhasil. Terjadi kesalahan pada data detail transaksi.';
                die(json_encode($data));
            }
        }


        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Order produk tidak berhasil. Terjadi kesalahan data transaksi.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        // Set Log Data
        $status_msg     = '';
        $log_data       = array(
            'cookie'    => $_COOKIE,
            'id_shop'   => $shop_order_id,
            'invoice'   => $invoice,
            'status'    => 'Create Sales Order'
        );
        an_log_action('SALES_ORDER', 'SUCCESS', $current_member->username, json_encode($log_data));

        if ($shop_order = $this->Model_Shop->get_shop_orders($shop_order_id)) {
            // SEND EMAIL
            $this->an_email->send_email_shop_order($current_member, $shop_order);
            $this->an_email->send_email_shop_order_customer($shop_order);
        }

        $data = array('status' => 'success', 'message' => 'Sales Order berhasil dibuat.');
        die(json_encode($data));
    }

    /**
     * Confirm Shop Order Function
     */
    function salesorderconfirm($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

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
        $password           = an_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Produk Order ini !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
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
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Konfirmasi Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 0) {
                an_log_action('ORDER_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($shop_order->status == 1) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ($shop_order->status == 5) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 0) {
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi. Pesanan sudah diproses.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($shop_order->id_member)) {
            $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirm'   => $datetime,
            'confirmed_by'  => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pesanan tidak berhasil dikonfirmasi. Terjadi kesalahan proses transaksi update data pesanan.';
            die(json_encode($data)); // JSON encode data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('ORDER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // SEND EMAIL
        // $this->an_email->send_email_shop_order($memberdata, $shop_order);
        $this->an_email->send_email_shop_order_customer($shop_order);

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil dikonfirmasi.';
        die(json_encode($data));
    }

    /**
     * Approved Shop Function
     */
    function salesorderapproved($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

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
        $password           = an_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Produk Order ini !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
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
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Approved Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 1) {
                an_log_action('ORDER_APPROVED', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($shop_order->status == 2) {
            $data['message'] = 'Status Pesanan sudah diapproved.';
            die(json_encode($data));
        }

        if ($shop_order->status == 5) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 1) {
            $data['message'] = 'Pesanan tidak dapat diapproved. Pesanan sudah diproses.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($shop_order->id_member)) {
            $data['message'] = 'Approved Pesanan tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 2,
            'datemodified'  => $datetime,
            'dateapproved'  => $datetime,
            'approved_by'   => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pesanan tidak berhasil diapproved. Terjadi kesalahan proses transaksi update data pesanan.';
            die(json_encode($data)); // JSON encode data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('ORDER_APPROVED', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // SEND EMAIL
        // $this->an_email->send_email_shop_order($memberdata, $shop_order);
        $this->an_email->send_email_shop_order_customer($shop_order);

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil diapproved.';
        die(json_encode($data));
    }

    /**
     * Paid Shop Order Function
     */
    function salesorderpaid($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

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
        $password           = an_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Produk Order ini !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
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
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Pelunasan Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 2) {
                an_log_action('ORDER_PAID', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($shop_order->status == 3) {
            $data['message'] = 'Status Pesanan sudah lunas.';
            die(json_encode($data));
        }

        if ($shop_order->status == 5) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 2) {
            $data['message'] = 'Pelunasan Pesanan tidak berhasil. Pesanan sudah diproses.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($shop_order->id_member)) {
            $data['message'] = 'Pelunasan Pesanan tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 3,
            'datemodified'  => $datetime,
            'datepaid'      => $datetime,
            'paid_by'       => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pelunasan Pesanan tidak berhasil. Terjadi kesalahan proses transaksi update data pesanan.';
            die(json_encode($data)); // JSON encode data
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('ORDER_PAID', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // SEND EMAIL
        // $this->an_email->send_email_shop_order($memberdata, $shop_order);
        $this->an_email->send_email_shop_order_customer($shop_order);

        $data['status']     = 'success';
        $data['message']    = 'Pelunasan Pesanan Produk berhasil.';
        die(json_encode($data));
    }

    /**
     * Done Shop Order Function
     */
    function salesorderdone($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

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
        $password           = an_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Produk Order ini !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
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
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Selesai Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 3) {
                an_log_action('ORDER_DONE', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($shop_order->status == 4) {
            $data['message'] = 'Status Pesanan sudah selesai.';
            die(json_encode($data));
        }

        if ($shop_order->status == 5) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 3) {
            $data['message'] = 'Pesanan tidak dapat diselesaikan.';
            die(json_encode($data));
        }

        if (!$memberdata = an_get_memberdata_by_id($shop_order->id_member)) {
            $data['message'] = 'Pesanan tidak dapat diselesaikan. Agen tidak dikenali.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 4,
            'datemodified'  => $datetime,
            'datedone'      => $datetime,
            'done_by'       => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pesanan tidak berhasil diselesaikan. Terjadi kesalahan proses transaksi update data pesanan.';
            die(json_encode($data)); // JSON encode data
        }

        // save data member omzet shop order
        $total_omzet        = $shop_order->subtotal - $shop_order->discount;
        $total_payment      = $shop_order->total_payment - $shop_order->unique;
        $data_member_omzet  = array(
            'id_member'     => $memberdata->id,
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

        if (!$insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pesanan tidak berhasil diselesaikan. Terjadi kesalahan proses transaksi omset sales order.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        an_log_action('ORDER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // SEND EMAIL
        $this->an_email->send_email_shop_order($memberdata, $shop_order);
        $this->an_email->send_email_shop_order_customer($shop_order);

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil diselesaikan.';
        die(json_encode($data));
    }

    /**
     * Cancel Shop Order Function
     */
    function salesordercancel($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $an_token, 'message' => 'ID Pesanan tidak dikenal');

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
        $password           = an_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
            die(json_encode($data));
        }

        if (!$is_admin) {
            if ($shop_order->id_member !== $current_member->id) {
                die(json_encode($data));
            }
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
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Batalkan Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($shop_order->status == 0) {
                an_log_action('ORDER_CANCEL', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($shop_order->status == 5) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 0) {
            $data['message'] = 'Pesanan tidak dapat dibatalkan. Pesanan sudah diproses.';
            die(json_encode($data));
        }

        // Update status shop order
        $data_order     = array(
            'status'        => 5,
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Pesanan tidak berhasil dibatalkan. Terjadi kesalahan proses transaksi update data pesanan.';
            die(json_encode($data)); // JSON encode data
        }

        an_log_action('ORDER_CANCEL', 'SUCCESS', $confirmed_by, json_encode($log_data));

        // SEND EMAIL
        $this->an_email->send_email_shop_order($memberdata, $shop_order);
        $this->an_email->send_email_shop_order_customer($shop_order);

        $data['status']     = 'success';
        $data['message']    = 'Pesanan Produk berhasil dibatalkan.';
        die(json_encode($data));
    }

    /**
     * Input Nomor Resi Shop Order Function
     */
    function inputresi($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pesanan tidak dikenal');

        if (!$id) {
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = an_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $resi               = trim($this->input->post('resi'));
        $resi               = an_isset($resi, '');
        $password           = trim($this->input->post('password'));
        $password           = an_isset($password, '');

        if (!$resi) {
            $data['message'] = 'Nomor Resi harus diisi !';
            die(json_encode($data));
        }

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Input Resi Produk Order ini !';
            die(json_encode($data));
        }

        if (!$shop_order = $this->Model_Shop->get_shop_orders($id)) {
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

        if (!empty(trim($shop_order->resi))) {
            $data['message'] = 'Nomor RESI sudah dibuat untuk pesanan ini.';
            die(json_encode($data));
        }

        if ($shop_order->status == 2) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ($shop_order->status != 1) {
            $data['message'] = 'Pesanan belum dikonfirmasi. Silahkan Konfirmasi Pesanan terlebih dahulu!';
            die(json_encode($data));
        }

        // Update nomor resi shop order
        $data_order     = array(
            'resi'          => $resi,
            'datesent'      => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if (!$update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        an_log_action('INPUT_RESI', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status' => 'success', 'message' => 'Input Resi berhasil.');
        die(json_encode($data));
    }


    /*
    |--------------------------------------------------------------------------
    | Set Discount
    |--------------------------------------------------------------------------
    */
    function applyDiscount()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $auth           = auth_redirect(true);
        $codeDiscount   = $this->input->post('code_discount');
        $codeDiscount   = an_isset($codeDiscount, '');
        $payment_type   = $this->input->post('payment_type');
        $payment_type   = an_isset($payment_type, '');
        $products       = $this->input->post('products');

        if (!$codeDiscount) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher belum di isi!');
            die(json_encode($response));
        }

        if (!$getDiscount = discount_code($codeDiscount)) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan!');
            die(json_encode($response));
        }

        if ($getDiscount->status == 0) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher sudah tidak tersedia !');
            die(json_encode($response));
        }

        if ($getDiscount->discount_agent == 0) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan !');
            die(json_encode($response));
        }

        $discount_type  = $getDiscount->discount_agent_type;
        $discount       = $getDiscount->discount_agent;

        if ($discount_products = is_json($getDiscount->products)) {
            $discount_products = json_decode($getDiscount->products);
        }

        // Set Data Product
        $total_qty          = 0;
        $total_price        = 0;
        $data_product       = array();
        $no = 1;
        foreach ($products as $item) {
            $productId      = isset($item['id']) ? an_decrypt($item['id']) : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            $price_cart     = isset($item['price']) ? $item['price'] : 0;
            $price_credit   = isset($item['price_credit']) ? $item['price_credit'] : 0;
            $price          = ($payment_type == 'cash') ? $price_cart : $price_credit;

            if (!$productId || !$qty) {
                continue;
            }

            $subtotal       = ($price * $qty);
            $total_qty     += $qty;
            $total_price   += $subtotal;

            $data_product[$no]  = array(
                'id'            => $productId,
                'price'         => $price,
                'qty'           => $qty,
                'subtotal'      => $subtotal,
            );
            $no++;
        }

        if (!$total_price || !$total_qty) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan !');
            die(json_encode($response));
        }

        $total_price_product = $total_price;

        if ($discount_products && $data_product) {
            $total_price        = 0;
            foreach ($data_product as $row) {
                $productId  = $row['id'];
                foreach ($discount_products  as $key => $product) {
                    if ($product == $productId) {
                        $total_price += $row['subtotal'];
                    }
                }
            }
        }

        $total_discount      = 0;
        if ($discount_type == 'percent') {
            $total_discount = $total_price * ($discount / 100);
        } else {
            $total_discount = $total_price ? $discount : 0;;
        }

        if (!$total_discount) {
            $message = ($discount_products) ? 'Kode Voucher hanya untuk produk tertentu !' : 'Kode Voucher tidak dapat digunakan.';
            $response = array('status'  => 'failed', 'message' => $message);
            die(json_encode($response));
        }

        $message = 'Kode Voucher berhasil digunakan.';
        if ($discount_products) {
            $message .= ' Anda mendapatkan diskon dari produk tertentu';
        }

        $delete_voucher = '
            Kode Voucher berhasil digunakan. <a href="javascript:;" class="removeDiscount" style="color:red">[Hapus Diskon]</a>
            <input type="hide" class="form-control" name="voucher_code" style="display: none" value="' . strtoupper($codeDiscount) . '">
            <input type="hide" class="form-control" name="total_discount" style="display: none" value="' . $total_discount . '">
        ';


        $response       = array(
            'status'            => 'success',
            'message'           => $message,
            'discount'          => ($total_discount) ? ($discount_type == 'percent' ? ($discount + 0) . '%' : an_accounting($total_discount)) : '-',
            'subtotal'          => $total_price_product,
            'total_discount'    => $total_discount,
            'delete_discount'   => $delete_voucher,
        );
        die(json_encode($response));
    }

    /**
     * Get Agent Order Detail Function
     */
    function getsalesorderdetail($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if (!$id) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = an_decrypt($id);
        if (!$data_order = $this->Model_Shop->get_shop_orders($id)) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlsalesorderdetail($data_order, 'sales');
        $data = array('status' => 'success', 'message' => 'Produk Order', 'data' => $set_html);
        die(json_encode($data));
    }

    /**
     * Agent Order  List Data function.
     */
    private function sethtmlsalesorderdetail($dataorder, $type_order = 'sales')
    {
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $order_detail = '';
        if (!$dataorder) {
            return $order_detail;
        }
        $currency       = config_item('currency');

        $product_detail = '';
        if (is_serialized($dataorder->products)) {
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
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_order    = isset($row['price_order']) ? $row['price_order'] : 0;
                $subtotal       = $qty * $price;

                $total_qty  = 'Qty : <span class="font-weight-bold mr-1">' . $qty . '</span>';
                if ($price_order > $price) {
                    $total_qty .= '( <s>' . an_accounting($price_order) . '</s> <span class="text-warning">' . an_accounting($price, $currency) . '</span> )';
                } else {
                    $total_qty .= '( ' . an_accounting($price, $currency) . ' )';
                }

                $product_detail .= '
                    <tr>
                        <td class="text-capitalize px-1 pl-2 py-2" style="border-left: 1px solid #11cdef">
                            <span class="text-primary font-weight-bold">' . $product_name . '</span>' . br() . '
                            <span class="small">' . $total_qty . '</span>
                        </td>
                        <td class="text-right px-1 pr-2 py-1" style="border-right: 1px solid #1171ef">' . an_accounting($subtotal) . '</td>
                    </tr>';
            }
            $product_detail .= '</table>';
        }

        // Information Detail Product
        $cfg_pay_type   = config_item('payment_type');
        $cfg_pay_method = config_item('payment_method');
        $payment_type   = isset($cfg_pay_type[$dataorder->payment_type]) ? $cfg_pay_type[$dataorder->payment_type] : $dataorder->payment_type;
        $payment_method = isset($cfg_pay_method[$dataorder->payment_method]) ? $cfg_pay_method[$dataorder->payment_method] : $dataorder->payment_method;
        $uniquecode     = str_pad($dataorder->unique, 3, '0', STR_PAD_LEFT);
        $info_product   = '
            <div class="card">
                <div class="card-body py-2">
                    <h6 class="heading-small mb-0">Ringkasan Order</h6>
                    ' . $product_detail . '
                    <hr class="mt-0 mb-0">
                    <div class="px-2 py-2">
                        <div class="row">
                            <div class="col-sm-7"><small class="text-muted">Subtotal</small></div>
                            <div class="col-sm-5 text-right"><small class="font-weight-bold">' . an_accounting($dataorder->subtotal) . '</small></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7"><small class="text-muted">Kode Unik</small></div>
                            <div class="col-sm-5 text-right"><small class="font-weight-bold">' . $uniquecode . '</small></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7">
                                <small class="text-muted">
                                    ' . lang('discount') . ($dataorder->voucher ? ' (<small class="text-success">' . $dataorder->voucher . '</small>)' : '') . '
                                </small>
                            </div>
                            <div class="col-sm-5 text-right">
                                <small class="font-weight-bold">
                                    ' . ($dataorder->discount ? '<span class="text-success">- ' . an_accounting($dataorder->discount) . '</span>' : '-') . '
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-3 align-items-center">
                        <div class="col-sm-6"><span class="heading-small text-capitalize font-weight-bold">' . lang('total_payment') . '</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-capitalize text-warning font-weight-bold">' . an_accounting($dataorder->total_payment, $currency) . '</span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-6"><span class="heading-small text-capitalize font-weight-bold">Total PV</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading-small text-capitalize text-primary font-weight-bold">' . an_accounting($dataorder->total_pv) . ' PV</span>
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    <div class="row pt-3 align-items-center">
                        <div class="col-sm-6"><span class="heading-small text-capitalize font-weight-bold">' . lang('payment') . '</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading-small text-capitalize text-default font-weight-bold">' . $payment_type . '</span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-sm-6"><span class="heading-small text-capitalize font-weight-bold">' . lang('payment_method') . '</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading-small text-capitalize text-default font-weight-bold">' . $payment_method . '</span>
                        </div>
                    </div>

                    <div class="row pb-2 align-items-center">
                        <div class="col-sm-6"><span class="heading-small text-capitalize font-weight-bold">Down Payment (DP)</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading-small text-capitalize text-primary font-weight-bold">' . an_accounting($dataorder->down_payment, $currency) . '</span>
                        </div>
                    </div>

                </div>
            </div>';

        // Information Agent
        $info_sales     = '';
        if ($getsales = an_get_memberdata_by_id($dataorder->id_member)) {

            $profile_path       = PROFILE_IMG;
            $image_path         = BE_IMG_PATH . 'icons/';

            $avatar     = $image_path . 'avatar.png';

            if ($getsales->photo && file_exists(PROFILE_IMG_PATH . $getsales->photo)) {
                $avatar = $profile_path . $getsales->photo;
            }

            $info_sales = '
                <div class="card mb-4">
                    <div class="card-body py-2">
                        <h6 class="heading-small mb-0">Informasi Agen</h6>
                        <hr class="mt-0 mb-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar avatar-xl rounded-circle">
                                    <img alt="Image placeholder" src="' . $avatar  . '">
                                </a>
                            </div>
                            <div class="col ml--2">
                                <h4 class="mb-0"><a href="#!">' . $getsales->name . '</a></h4>
                                <p class="text-sm text-muted mb-0">
                                    <small class="font-weight-bold"><i class="ni ni-single-02 text-success mr-1"></i> ' . strtoupper($getsales->username) . '</small>
                                </p>
                                <p class="text-sm text-muted mb-0">
                                    <small><i class="fa fa-phone-alt text-success mr-1"></i> ' . $getsales->phone . '</small>
                                </p>
                                <p class="text-sm text-muted mb-0">
                                    <small><i class="fa fa-envelope text-success mr-1"></i> ' . $getsales->email . '</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        // Information Shipping Address
        $address        = $dataorder->address . ', Kec. ' . $dataorder->subdistrict . br();
        $address       .= $dataorder->city . ' - ' . $dataorder->province;
        $address       .= ($dataorder->postcode) ? ' (' . $dataorder->postcode . ')' : '';
        $info_shipping  = '
            <div class="card">
                <div class="card-body py-2">
                    <h6 class="heading-small mb-0">Informasi Konsumen</h6>
                    <hr class="mt-0 mb-2">
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('name') . '</small></div>
                        <div class="col-sm-9"><small class="text-uppercase font-weight-bold">' . $dataorder->name . '</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">Telp Rumah</small></div>
                        <div class="col-sm-9"><small class="font-weight-bold">' . $dataorder->phone_home . '</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">Telp</small></div>
                        <div class="col-sm-9"><small class="font-weight-bold">' . $dataorder->phone . '</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('reg_email') . '</small></div>
                        <div class="col-sm-9"><small class="text-lowecase font-weight-bold">' . $dataorder->email . '</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('reg_alamat') . '</small><br></div>
                        <div class="col-sm-9"><small class="text-capitalize font-weight-bold">' . $address . '</small></div>
                    </div>
                </div>
            </div>';

        $info_payment   = '';
        $order_detail   = '
            <div class="row">
                <div class="col-md-5 px-2">
                    ' . $info_sales . '
                    ' . $info_shipping . '
                    ' . $info_payment . '
                </div>
                <div class="col-md-7 px-2">
                    ' . $info_product . '
                </div>
            </div>
        ';
        return $order_detail;
    }
    
    // ------------------------------------------------------------------------------------------------
}

/* End of file Productorder.php */
/* Location: ./app/controllers/Productorder.php */
