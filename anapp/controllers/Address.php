<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Address Controller.
 *
 * @class     Address
 * @version   1.0.0
 */
class Address extends AN_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // GET DATA CHANGE
    // =============================================================================================

    /**
	 * Get Province function.
	 */
    function getprovince()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }

        $option             = '<option value="" disabled selected>'. lang('reg_pilih_provinsi') .'</option>';
        $data               = array(
            'status'        => 'failed',
            'data'          => $option
        );

        if ( ! $provinces = $this->Model_Address->get_provinces() ) {
            die(json_encode($data));
        }

        $option             = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_provinsi') .' --</option>';
        foreach($provinces as $province){
            $option .= '<option value="'.$province->id.'">'.$province->province_name.'</option>';
        }
        
        $data['status']     = 'success';
        $data['data']       = $option;
        die(json_encode($data));
    }

    /**
     * Select Province function.
     */
    function selectprovince()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }

        $province           = $this->input->post('province');
        $province           = an_isset($province, '');
        $option             = '<option value="">'. lang('reg_pilih_kota') .'</option>';
        $data               = array(
            'status'        => 'failed',
            'data'          => $option,
            'subdistrict'   => '<option value="">'. lang('reg_pilih_kecamatan') .'</option>',
            'village'       => '<option value="">'. lang('reg_pilih_desa') .'</option>',
        );

        if( empty($province) ){
            die(json_encode($data));
        }

        if ( strlen($province) > 2 ) {
            $provinces      = an_provinces();
            foreach ($provinces as $key => $row) {
                if ( $row->province_name == $province || $row->id == $province ) {
                    $province = $row->id;
                }
            }
        }

        if ( ! $districts = $this->Model_Address->get_districts_by_province($province) ) {
            die(json_encode($data));
        }

        $option             = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_kota') .' --</option>';
        foreach($districts as $city){
            $option .= '<option value="'.$city->id.'">'. $city->district_type .' '. $city->district_name .'</option>';
        }
        
        $data['status']     = 'success';
        $data['data']       = $option;
        die(json_encode($data));
    }

    /**
     * Select District function.
     */
    function selectdistrict(){
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }
        
        $district               = $this->input->post('district');
        $district               = an_isset($district, '');
        $option             = '<option value="">'. lang('reg_pilih_kecamatan') .'</option>';
        $data               = array(
            'status'        => 'failed',
            'data'          => $option,
            'village'       => '<option value="">'. lang('reg_pilih_desa') .'</option>',
        );

        if( empty($district) ){
            die(json_encode($data));
        }

        if( ! $subdistricts = $this->Model_Address->get_subdistricts_by_district($district) ){
            die(json_encode($data));
        }

        $option             = '<option value="" selected="">-- '. lang('reg_pilih_kecamatan') .' --</option>';
        foreach($subdistricts as $subdistrict){
            $option .= '<option value="'.$subdistrict->id.'" >'.$subdistrict->subdistrict_name.'</option>';
        }
        
        $data['status']     = 'success';
        $data['data']       = $option;
        die(json_encode($data));
    }

    /**
     * Select Subdistrict function.
     */
    function selectsubdistrict(){
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }
        
        $subdistrict        = $this->input->post('subdistrict');
        $subdistrict        = an_isset($subdistrict, '');
        $option             = '<option value="">'. lang('reg_pilih_desa') .'</option>';
        $data               = array(
            'status'        => 'failed',
            'data'          => $option,
        );

        if( empty($subdistrict) ){
            die(json_encode($data));
        }

        if( ! $villages = $this->Model_Address->get_villages_by_subdistrict($subdistrict) ){
            die(json_encode($data));
        }

        $option             = '<option value="" disabled="" selected="">-- '. lang('reg_pilih_desa') .' --</option>';
        foreach($villages as $village){
            $option .= '<option value="'.$village->id.'" >'.$village->village_name.'</option>';
        }
        
        $data['status']     = 'success';
        $data['data']       = $option;
        die(json_encode($data));
    }

    /**
     * Select Province function.
     */
    function selectcourier()
    {
        // This is for AJAX request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('/'), 'location'); }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $this->load->helper('shop_helper');

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $courier            = $this->input->post('courier');
        $courier            = an_isset($courier, '');
        $province           = $this->input->post('province');
        $province           = an_isset($province, '');
        $district           = $this->input->post('district');
        $district           = an_isset($district, '');
        $subdistrict        = $this->input->post('subdistrict');
        $subdistrict        = an_isset($subdistrict, '');

        $options            = '<option value="">'. lang('select').' '.lang('service') .'</option>';
        $data               = array(
            'status'        => 'error',
            'message'       => 'Silahkan periksa kembali keranjang belanjaan anda!',
            'data'          => $options,
        );

        if( empty($district) ){
            $data['message'] = 'Kab/Kota belum di pilih. Silahkan pilih Kab/Kota terlabih dahulu !';
            die(json_encode($data));
        }

        if( empty($subdistrict) ){
            $data['message'] = 'Kecamatan belum di pilih. Silahkan pilih Kecamatan terlabih dahulu !';
            die(json_encode($data));
        }

        if( empty($courier) ){
            $data['message'] = 'Kurir belum di pilih. Silahkan pilih Kurir terlabih dahulu !';
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

        // --------------------------------------
        // Product Cart
        // --------------------------------------
        $free_shipping      = false;
        $cart_total_qty     = $this->cart->total_items();
        $total_payment      = $this->cart->total();
        $total_weight       = 0;

        foreach ($product_cart as $key => $row) {
            $_id            = isset($row['id']) ? $row['id'] : 0;
            $_qty           = isset($row['qty']) ? $row['qty'] : 0;
            if ( !$_id || !$_qty ) { continue; }
            if ( !$productdata = an_products($_id) ) { continue; }

            $product_weight     = $_qty * $productdata->weight;
            $total_weight       += $product_weight;
        }

        if( empty($total_weight) ){
            $data['message'] = 'Terjadi kesalahan pada sistem pengiriman.';
            die(json_encode($data));
        }

        if ( $current_member->as_stockist > 0 ) {
            $origin         = config_item('rajaongkir_origin'); // kota asal pengirim
            $origin_type    = 'city'; // type
        } else {
            $origin         = config_item('rajaongkir_origin'); // kota asal pengirim
            $origin_type    = 'city'; // type
            
            // Check Apply Stockist
            /*
            $checkStockist = an_check_agent(true);
            if ( ! $checkStockist ) {
                $data['message'] = 'Anda belum pilih <b>Stockist</b>. Silahkan pilih <b>Stockist</b> terlebih dahulu untuk memesan produk !';
                die(json_encode($data));
            }

            $id_stockist    = isset($checkStockist->id) ? $checkStockist->id : 0;
            if ( ! $id_stockist ) {
                $data['message'] = 'Anda belum pilih <b>Stockist</b>. Silahkan pilih <b>Stockist</b> terlebih dahulu untuk memesan produk !';
                die(json_encode($data));
            }

            $stockistdata   = an_get_memberdata_by_id($id_stockist);
            if ( !$stockistdata  ) {
                $data['message'] = 'Anda belum pilih <b>Stockist</b>. Silahkan pilih <b>Stockist</b> terlebih dahulu untuk memesan produk !';
                die(json_encode($data));
            }
            $origin         = ($stockistdata->district_stockist) ? $stockistdata->district_stockist : 0;
            $origin_type    = 'city';

            if ( !$origin  ) {
                $data['message'] = 'Maaf Stockist belum setting alamat pengiriman. Silahkan pilih Stockist lainnya !';
                die(json_encode($data));
            }
            */
        }

        if ( ! $shipping_fee  = an_shipping_fee($origin, $district, $subdistrict, $total_weight, $courier, $origin_type) ) {
            $data['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            die(json_encode($data, true));
        }

        $status_shipping    = isset($shipping_fee['status']) ? $shipping_fee['status'] : false;
        if ( !$status_shipping ) {
            $data['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            die(json_encode($data, true));
        }

        $courier_services   = isset($shipping_fee['data']) ? $shipping_fee['data'] : array();
        if ( ! $courier_services ) {
            $data['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            die(json_encode($data, true));
        }

        foreach ($courier_services as $key => $row) {
            $service_name   = $row->service.' - '. $row->description;
            $ongkir         = isset($row->cost[0]->value) ? $row->cost[0]->value : 'failed';
            $day            = isset($row->cost[0]->etd) ? $row->cost[0]->etd : '';
            if ( $ongkir == 'failed' ) { continue; }

            if ( strtolower($courier) != 'pos' ) {
                $day .= ' Hari';
            }

            $value_opt      = $row->service .','. $ongkir;
            $options       .= '<option value="'.$row->service.'" data-cost="'.$ongkir.'" data-day="'.$day.'">'. $service_name .'</option>';
        }
        
        $data['status']     = 'success';
        $data['message']    = 'Data Layanan Kurir ditemukan. Silahkan Pilih Layanan Kurir !';
        $data['weight']     = $total_weight;
        $data['data']       = $options;
        die(json_encode($data));
    }


    // =============================================================================================
    // GET DATA RAJA ONGKIR API
    // =============================================================================================
    

    /*
    | Get Courier City
    */
    public function get_courier()
    {
        $couriers = config_item('courier');

        foreach ($couriers as $row) {

            $data[] = array(
                'code'  => $row['code'],
                'name'  => $row['name']
            );
            //print_r($row['code'] . '<br>');
        };
        echo json_encode($data);
    }

    /*
    | get courier service | Show service and cost 
    */
    public function get_courier_cost()
    {
        $this->load->helper('shop_helper');

        $auth           = auth_redirect( true );
        $courier        = $this->input->post('courier');
        $courier        = an_isset($courier, '');
        $weight         = $this->input->post('weight');
        $weight         = an_isset($weight, '');
        $destination    = $this->input->post('destination');
        $destination    = an_isset($destination, '');
        $opt_agent      = $this->input->post('opt_agent');
        $opt_agent      = an_isset($opt_agent, '');

        $free_shipping  = false;
        if ( $auth ) {
            $total_weight       = sum_cart_option('weight');
            $product_weight     = sum_cart_option('product_weight');
            if ( $total_weight <> $product_weight ) {
                $weight         = 1;
                $free_shipping  = true;
            }
        }

        $data_options   = '<option value="">-- Silahkan Pilih Layanan Kurir --</option>';
        $data           = array( 'status' => 'failed', 'message' => 'failed', 'data' => $data_options, 'weight' => $weight );

        $origin         = config_item('rajaongkir_origin'); // kota asal pengirim
        $origin_type    = 'city'; // type

        if ( !is_logged_in() && !$opt_agent ) {
            $check_agent    = check_agent();
            $_origin        = isset($check_agent->district) ? $check_agent->district : 0;
            $origin         = ( $_origin ) ? $_origin : $origin;
        }

        $dest           = $destination; // id
        $dest_type      = 'subdistrict'; // type

        // Get List Data Courier Service
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => config_item('rajaongkir_url') . "cost",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => "origin=" . $origin . "&originType=" . $origin_type . "&destination=" . $dest . "&destinationType=" . $dest_type . "&weight=" . $weight . "&courier=" . $courier,
            CURLOPT_HTTPHEADER      => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . config_item('rajaongkir_token')
            ),
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Get response from API
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        curl_close($curl);

        if ( $err ) {
            die(json_encode($data));
        }

        $response       = ( is_json($response) ? json_decode($response) : false );
        $rajaongkir     = isset($response->rajaongkir) ? $response->rajaongkir : false;
        $status         = isset($rajaongkir->status) ? $rajaongkir->status : false;
        $status_code    = isset($status->code) ? $status->code : false;
        
            // var_dump($costs);
        if ( $status_code == 400 ) {
            $description        = isset($response->rajaongkir->status->description) ? $response->rajaongkir->status->description : '';
            $data['message']    = str_replace('Weight', 'Berat', $description);
            die(json_encode($data));
        }
        if ( $status_code == 200 ) {
            $origin         = isset($response->rajaongkir->origin_details) ? $response->rajaongkir->origin_details : false;
            $destination    = isset($response->rajaongkir->destination_details) ? $response->rajaongkir->destination_details : false;
            $results        = isset($response->rajaongkir->results) ? $response->rajaongkir->results : false;
            $costs          = isset($results[0]->costs) ? $results[0]->costs : false;
            if ( $costs ) {
                $courier_service_reg = array('jne', 'tiki', 'sicepat', 'sicepat');
                $count_cost          = count($costs);
                foreach ($costs as $key => $row) {
                    $ongkir     = isset($row->cost[0]->value) ? $row->cost[0]->value : 'failed';
                    $day        = isset($row->cost[0]->etd) ? $row->cost[0]->etd : false;
                    if ( $ongkir == 'failed' ) { continue; }

                    if ( strtolower($courier) != 'pos' ) {
                        $day .= ' Hari';
                    }

                    $service_name = $row->service.' - '. $row->description;

                    if ( $free_shipping ) { 
                        $ongkir = 0;
                        $service_name .= ' (Free ongkir)';
                        // if ( $count_cost > 1 ) {
                        //     if ( in_array(strtolower($courier), $courier_service_reg) && strtoupper($row->service) == 'REG' ) {
                        //         $ongkir = 0;
                        //     }
                        // }
                    }
                    $value_opt      = $row->service .','. $ongkir;
                    $data_options  .= '<option value="'.$value_opt.'" data-day="'.$day.'">'. $service_name .'</option>';
                }
                $data['status'] = 'success';
                $data['data']   = $data_options;
                $data['rajaongkir']   = $rajaongkir;
            }
        }
        die(json_encode($data, true));
    }
    
    /**
     * Select Courier function.
     */
    function selectcourierexp(){
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id_stockist        = $this->input->post('pin_stockist_id');
        $id_stockist        = an_isset($id_stockist, 0);        
        $courier            = $this->input->post('shipping_courier');
        $courier            = an_isset($courier, '');
        $data_options       = '<option value="">-- Silahkan Pilih Layanan Kurir --</option>';
        
        if( ! $products = $this->input->post('product') ){
            $data = array(
                'data'      => $data_options, 
                'status'    => 'error', 
                'message'   => 'Anda belum memasukkan jumlah pesanan produk. Minimal pesan 1 produk !' 
            ); die(json_encode($data));
        }
        
        if( ! $id_stockist ){
            $data = array(
                'data'      => $data_options, 
                'status'    => 'error', 
                'message'   => 'Stockist belum di pilih. Silahkan pilih Stockist terlabih dahulu !' 
            ); die(json_encode($data));
        }
        
        if( ! $courier ){
            $data = array(
                'data'      => $data_options, 
                'status'    => 'error', 
                'message'   => 'Kurir belum di pilih. Silahkan pilih Kurir terlabih dahulu !' 
            ); die(json_encode($data));
        }

        if ( ! $shipping_address = an_shipping_addr_is_main($current_member->id) ) {
            $data = array('status' => 'error', 'data' => $data_options , 'message' => 'Anda belum menambahkan alamat pengiriman !' );
            die(json_encode($data));
        }

        $total_qty          = 0;
        $total_weight       = 0;
        $product_digital    = true;
        foreach ($products as $key => $qty) {
            if ( !$qty ) { continue; }
            if ( ! $get_product = an_products($key) ) { continue; }

            if ( strtolower($get_product->type) == 'product' ) {
                $product_digital = false;
            }

            $total_qty      += $qty;
            $total_weight   += $get_product->weight * (int) $qty;
        }

        if ( ! $total_qty ) {
            $data = array(
                'data'      => $data_options, 
                'status'    => 'error', 
                'message'   => 'Anda belum memasukkan jumlah pesanan produk. Minimal pesan 1 produk !' 
            ); die(json_encode($data));
        }

        if ( $product_digital ) {
            $data_options       = '<option value="transferpin" data-cost="0" data-day="Same Day">Produk Digital => Transfer PIN</option>';
            $data = array(
                'data'      => $data_options, 
                'status'    => 'success', 
                'message'   => 'Layanan Kurir Tersedia.' 
            ); die(json_encode($data));
        }

        if ( ! $stockist_address = an_stockist_address($id_stockist) ) {
            $data = array('status' => 'error', 'data' => $data_options , 'message' => 'Maaf, untuk sementara Stockist tidak dapat menerima orderan produk  !' );
            die(json_encode($data));
        }

        $origin             = $stockist_address->id_city;       // id kota asal pengirim
        $id_city            = $shipping_address->id_city;       // Kota tujuan penerima
        $id_district        = $shipping_address->id_district;   // Kecamatan tujuan penerima
        $total_weight       = $total_weight ? $total_weight : 1;
        $courier_services   = '';
        $service_data       = '';

        if ( ! $shipping_fee  = an_shipping_fee($origin, $id_city, $id_district, $total_weight, $courier) ) {
            $data = array('status' => 'error', 'data' => $data_options , 'message' => 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !' );
            die(json_encode($data, true));
        }

        if ( !$shipping_fee['status'] ) {
            $message    = isset($shipping_fee['data']) ? $shipping_fee['data'] : 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            $data       = array('status' => 'error', 'data' => $data_options , 'message' => $message );
            die(json_encode($data, true));
        }

        $shipping_fee = isset($shipping_fee['data']) ? $shipping_fee['data'] : array();
        if ( ! $shipping_fee ) {
            $data = array('status' => 'error', 'data' => $data_options , 'message' => 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !' );
            die(json_encode($data, true));
        }

        foreach ($shipping_fee as $key => $row) {
            $ongkir     = $row->cost[0]->value;
            $day        = $row->cost[0]->etd;
            if ( strtolower($courier) != 'pos' ) {
                $day .= ' Hari';
            }
            $data_options .= '<option value="'.$row->service.'" data-cost="'.$ongkir.'" data-day="'.$day.'">'.$row->service.'</option>';
        }

        $data = array('status' => 'success', 'total_weight' => $total_weight, 'data' => $data_options, 'message' => 'Data Layanan Kurir ditemukan. Silahkan Pilih Data Layanan Kurir' );
        die(json_encode($data, true));
    }
    
    /**
     * Add Shipping Address function.
     */
    function addshippingaddress(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) redirect( base_url('dashboard'), 'refresh' );

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array( 'status' => 'login', 'login' => base_url('login') );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $shipping_id        = $this->input->post('shipping_id');
        $shipping_id        = an_isset($shipping_id, '');
        $id_member          = $this->input->post('shipping_member_id');
        $id_member          = an_isset($id_member, '');
        $label              = $this->input->post('shipping_label');
        $label              = an_isset($label, '');
        $name               = $this->input->post('shipping_name');
        $name               = an_isset($name, '');
        $phone              = $this->input->post('shipping_phone');
        $phone              = an_isset($phone, '');
        $province           = $this->input->post('shipping_province');
        $province           = an_isset($province, '');
        $city               = $this->input->post('shipping_city');
        $city               = an_isset($city, '');
        $district           = $this->input->post('shipping_district');
        $district           = an_isset($district, '');
        $address            = $this->input->post('shipping_address');
        $address            = an_isset($address, '');
        $postcode           = $this->input->post('shipping_postcode');
        $postcode           = an_isset($postcode, '');
        $prov_name          = $this->input->post('prov_name');
        $prov_name          = an_isset($prov_name, '');
        $city_name          = $this->input->post('city_name');
        $city_name          = an_isset($city_name, '');
        $district_name      = $this->input->post('district_name');
        $district_name      = an_isset($district_name, '');

        $this->form_validation->set_rules('shipping_label','Label','required');
        $this->form_validation->set_rules('shipping_name','Nama Penerima','required');
        $this->form_validation->set_rules('shipping_phone','No. Telp/HP Penerima','required');
        $this->form_validation->set_rules('shipping_address','Alamat','required');
        $this->form_validation->set_rules('shipping_province','Propinsi','required');
        $this->form_validation->set_rules('shipping_city','Kota/Kabupaten','required');
        $this->form_validation->set_rules('shipping_district','Kecamatan','required');
        
        $this->form_validation->set_message('required', '<br />%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');
        
        if( $this->form_validation->run() == FALSE){
            $data = array(
                'status'    => 'error',
                'message'   => 'Simpan Alamat Pengiriman tidak berhasil. '.validation_errors().''
            ); die(json_encode($data));
        }

        if ( $is_admin ) {
            if ( ! $member = an_get_memberdata_by_id($id_member) ) {
                $data = array('status' => 'error', 'message' => 'Data Member tidak ditemukan.');
                die(json_encode($data));
            }
        } else {
            $id_member      = $current_member->id;
        }

        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        $label              = ucwords(strtolower($label));
        $name               = ucwords(strtolower($name));
        $name               = ucwords(strtolower($name));
        $address            = ucwords(strtolower($address));
        $district_name      = ucwords(strtolower($district_name));
        $city_name          = ucwords(strtolower($city_name));
        $province_name      = ucwords(strtolower($prov_name));
        $province_name      = str_replace('Dki ', 'DKI ', $province_name);
        $province_name      = str_replace('Di ', 'DI ', $province_name);

        $data_address       = array(
            'id_member'     => $id_member,
            'label'         => $label,
            'name'          => $name,
            'phone'         => $phone,
            'id_province'   => $province,
            'id_city'       => $city,
            'id_district'   => $district,
            'province'      => $province_name,
            'city'          => $city_name,
            'district'      => $district_name,
            'address'       => $address,
            'postcode'      => $postcode,
            'is_main'       => 1
        );

        // Check Data Shipping Address Member is Main
        if ( $shipping_address = an_shipping_addr_is_main($id_member) ) {
            if ( ! $this->Model_Address->update_data_shipping_addr($shipping_address->id, array('is_main' => 0) ) ) {
                $this->db->trans_rollback();
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Tambah Alamat Pengiriman tidak berhasil. Terjadi kesalahan pada transaksi !'
                ); die(json_encode($data));
            }
        }

        if ( $shipping_id ) {
            if ( ! $update_data_shipping = $this->Model_Address->update_data_shipping_addr($shipping_id, $data_address) ) {
                $this->db->trans_rollback();
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Edit Alamat Pengiriman tidak berhasil. Terjadi kesalahan pada transaksi !'
                ); die(json_encode($data));
            }
            $save_data_id = $shipping_id;
        } else {
            if ( ! $save_data_id = $this->Model_Address->save_data_shipping_addr($data_address) ) {
                $this->db->trans_rollback();
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Tambah Alamat Pengiriman tidak berhasil. Terjadi kesalahan pada transaksi !'
                ); die(json_encode($data));
            }            
        }

        if ( $this->db->trans_status() === FALSE ){
            $this->db->trans_rollback();
            $data = array('status' => 'error','message' => 'Aktivasi Program tidak berhasil. Terjadi kesalahan pada transaksi !');
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        $html = '<div class="callout callout-info bottom5" style="border-radius: 0;">
                    <input type="hidden" name="member_shipping_id" id="member_shipping_id" class="hide" value="'.$save_data_id.'" />
                    <p class="lead bottom5">Alamat Pengiriman</p>
                    <strong>'. $label .' <i class="fa fa-map-marker" style="margin-left: 5px"></i> </strong>
                    <p class="text-muted" style="margin: 0px">
                        <i class="fa fa-user" style="margin-right: 5px"></i> '. $name .'
                    </p>
                    <strong class="text-danger"><i class="fa fa-phone" style="margin-right: 5px"></i> '. $phone .'</strong>
                    <p style="margin: 0px">'. $address .', '. $district_name .'</p>
                    <p style="margin: 0px">'. $city_name .', '. $province_name .'</p>
                </div>';

        $message    = $shipping_id ? 'Edit' : 'Tambah';
        $message    = $message . ' Alamat Pengiriman berhasil.';
        $data       = array('status' => 'success', 'data' => $html, 'message' => $message); 
        die(json_encode($data));
    }
    
    /**
     * Set Shipping Address function.
     */
    function setshippingaddress($id=0){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) redirect( base_url('dashboard'), 'refresh' );

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array( 'status' => 'login', 'login' => base_url('login') );
            die(json_encode($data));
        }
        if( !$id ){
            $data = array( 'status' => 'error', 'message' =>'Data Alamat Pengiriman tidak ditemukan !' );
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        if( !$shipping = $this->Model_Address->get_shipping_addr($id) ){
            $data = array( 'status' => 'error', 'message' =>'Data Alamat Pengiriman tidak ditemukan !' );
            die(json_encode($data));
        }

        // Check Data Shipping Address Member is Main
        if ( $shipping_address = an_shipping_addr_is_main($shipping->id_member) ) {
            if ( ! $this->Model_Address->update_data_shipping_addr($shipping_address->id, array('is_main' => 0) ) ) {
                $this->db->trans_rollback();
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Set Alamat Pengiriman tidak berhasil. Terjadi kesalahan pada transaksi !'
                ); die(json_encode($data));
            }
        }

        // Update Data Shipping Address Member is Main
        if ( ! $this->Model_Address->update_data_shipping_addr($shipping->id, array('is_main' => 1) ) ) {
            $this->db->trans_rollback();
            $data = array(
                'status'    => 'error',
                'message'   => 'Set Alamat Pengiriman tidak berhasil. Terjadi kesalahan pada transaksi !'
            ); die(json_encode($data));
        }

        $html = '<div class="callout callout-info bottom5" style="border-radius: 0;">
                    <input type="hidden" name="member_shipping_id" id="member_shipping_id" class="hide" value="'.$shipping->id.'" />
                    <p class="lead bottom5">Alamat Pengiriman</p>
                    <strong>'. $shipping->label .' <i class="fa fa-map-marker" style="margin-left: 5px"></i> </strong>
                    <p class="text-muted" style="margin: 0px">
                        <i class="fa fa-user" style="margin-right: 5px"></i> '. $shipping->name .'
                    </p>
                    <strong class="text-danger"><i class="fa fa-phone" style="margin-right: 5px"></i> '. $shipping->phone .'</strong>
                    <p style="margin: 0px">'. $shipping->address .', '. $shipping->district .'</p>
                    <p style="margin: 0px">'. $shipping->city .', '. $shipping->province .'</p>
                </div>';

        $data = array(
            'status'    => 'success',
            'data'      => $html,
            'message'   => 'Set Alamat Pengiriman berhasil.'
        ); die(json_encode($data));
    }
    
    /**
     * Edit Shipping Address function.
     */
    function editshippingaddress($id=0){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) redirect( base_url('store'), 'refresh' );

        $token  = config_item('rajaongkir_token');
        $data   = array( 'status' => 'error', 'message' =>'Data Alamat Pengiriman tidak ditemukan !' );
        $auth   = auth_store_redirect( $this->input->is_ajax_request() );

        if( $auth ){
            if ( ! $member = an_get_current_member() ) {
                $data = array( 'status' => 'login', 'login' => base_url('login') );
                die(json_encode($data, true));
            }
            if ( ! $id ) {
                die(json_encode($data, true));
            }
            if ( ! $shipping = $this->Model_Address->get_shipping_addr($id) ) {
                die(json_encode($data, true));
            }
            if ( $shipping->id_member != $member->id ) {
                die(json_encode($data, true));
            }
        } else {
            if ( ! $shipping = $this->session->userdata( 'data_addr_customer' ) ) {
                die(json_encode($data, true));
            }
            $shipping =  (object) $shipping;
        }

        // Get City
        $select_city = '<option value="">-- SILAHKAN PILIH KOTA/KABUPATEN --</option>';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL                 => "https://pro.rajaongkir.com/api/city?province=" . $shipping->id_province,
            CURLOPT_CUSTOMREQUEST       => "GET",
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_SSL_VERIFYPEER      => false,
            CURLOPT_ENCODING            => "",
            CURLOPT_MAXREDIRS           => 10,
            CURLOPT_TIMEOUT             => 15,
            CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER          => array( "key: " . $token ),
        ));

        $response           = curl_exec($curl);
        $error              = curl_error($curl);

        if( !$error && $response ){
            $response       = json_decode($response);
            $cities         = $response ? $response->rajaongkir->results : '';
            if( !empty($cities) ){
                $status = 'success';
                foreach($cities as $key => $city){
                    $sort_type[$key] = $city->type;
                    $sort_name[$key] = $city->city_name;
                }
                array_multisort($sort_type, SORT_DESC, $sort_name, SORT_ASC, $cities);
                foreach($cities as $city){
                    $city_name      = strtoupper($city->type. ' ' . $city->city_name);
                    $select_city   .= '<option value="'.$city->city_id.'" name="'. $city_name .'">'. $city_name .'</option>';
                }
            }
        }
        $shipping->select_city = $select_city;

        // Get District
        $select_district        = '<option value="">-- SILAHKAN PILIH KECAMATAN --</option>';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL                 => "https://pro.rajaongkir.com/api/subdistrict?city=" . $shipping->id_city,
            CURLOPT_CUSTOMREQUEST       => "GET",
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_SSL_VERIFYPEER      => false,
            CURLOPT_ENCODING            => "",
            CURLOPT_MAXREDIRS           => 10,
            CURLOPT_TIMEOUT             => 15,
            CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER          => array( "key: " . $token ),
        ));

        $response           = curl_exec($curl);
        $error              = curl_error($curl);

        if( !$error && $response ){
            $response       = json_decode($response);
            $districts      = $response ? $response->rajaongkir->results : '';
        
            if( !empty($districts) ){
                $status = 'success';
                foreach($districts as $row){
                    $district_name    = strtoupper($row->subdistrict_name);
                    $select_district .= '<option value="'.$row->subdistrict_id.'" name="'. $row->subdistrict_name .'">'. $district_name .'</option>';
                }
            }
        }
        $shipping->select_district = $select_district;


        $data = array(
            'status'    => 'success',
            'data'      => $shipping,
            'message'   => ''
        ); die(json_encode($data));
    }
    
    /**
     * Shipping List function.
     */
    function shippingaddresslistdata($id = 0){
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
        
        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;
        
        $s_member_id        = $this->input->post('search_member_id');
        $s_member_id        = an_isset($s_member_id, '');

        if ( $is_admin ) {
            $id_member      = ( !empty($s_member_id) ) ? $s_member_id : $id;
            $condition      = ' AND %id_member% = ' . $id_member;
        } else {
            $condition      = ' AND %id_member% = ' . $current_member->id;
        }
        
        if( $column == 1 )          { $order_by .= '%label% ' . $sort . ', %name% ' . $sort. ', %province% ' . $sort; }
        elseif( $column == 2 )      { $order_by .= '%is_main% ' . $sort; }
        
        $product_list       = $this->Model_Address->get_all_shipping_address($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array(); 
 
        if( !empty($product_list) ){
            $iTotalRecords  = an_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($product_list as $row){
                $address    = '<div>
                                <strong>'. $row->label .' <i class="fa fa-map-marker" style="margin-left: 5px"></i> </strong>
                                <p class="text-muted" style="margin: 0px">
                                    <i class="fa fa-user" style="margin-right: 5px"></i> '. $row->name .'
                                </p>
                                <strong class="text-danger"><i class="fa fa-phone" style="margin-right: 5px"></i> '. $row->phone .'</strong>
                                <p style="margin: 0px">'. $row->address .', '. $row->district .'</p>
                                <p style="margin: 0px">'. $row->city .', '. $row->province .'</p>
                            </div>';

                $btn_main   = '<a href="'.base_url('address/setshippingaddress/'.$row->id).'" class="btn btn-xs btn-block btn-flat bg-blue btn_set_shipping_address" title="Jadikan Alamat Utama"><i class="fa fa-check"></i> Set Alamat</a>';
                $btn_edit   = '<a href="'.base_url('address/editshippingaddress/'.$row->id).'" class="btn btn-xs btn-block btn-flat btn-warning btn_edit_shipping_address" title="Edit Alamat"><i class="fa fa-edit"></i> Edit</a>';
                $btn_delete = '<a href="'.base_url('address/deleteshippingaddress/'.$row->id).'" class="btn btn-xs btn-block btn-flat btn-danger btn_delete_shipping_address" title="Edit Alamat"><i class="fa fa-trash"></i> Hapus</a>';

                if ( $current_member->id != $row->id_member ) {
                    $btn_edit = $btn_delete = '';
                    if ( $is_admin ) {
                        if ( ! $s_member_id ) {
                            $btn_main = '';
                        }
                    } else {
                        $btn_main = '';
                    }
                }

                $status     = ( $row->is_main == 1 ) ? '<span class="label label-info"><i class="fa fa-check"></i> Utama</span>' : '';
                $set_main   = ( $row->is_main == 0 ) ? $btn_main.$btn_edit.$btn_delete  : $btn_edit;
                
                $records["aaData"][]    = array(
                    an_center($i),
                    $address,
                    an_center($status),
                    an_center($set_main)
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
}

/* End of file Address.php */
/* Location: ./application/controllers/Address.php */
