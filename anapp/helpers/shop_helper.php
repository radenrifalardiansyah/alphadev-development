<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// -------------------------------------------------------------------------
// Shop functions helper
// -------------------------------------------------------------------------

if (!function_exists('an_cart_contents')){
    /**
     * Get Product Cart function.
     */
    function an_cart_contents()
    {
        $CI = &get_instance();
        $auth                   = auth_redirect( true );
        if ( $auth ) {
            $current_member     = an_get_current_member();
            $is_admin           = as_administrator($current_member);

            // Get Product Cart
            $cart_contents      = $CI->cart->contents();
            $product_type       = '';

            if ( $cart_contents ) {
                foreach ($cart_contents as $item) {
                    $product_id     = isset($item['id']) ? $item['id'] : 'none';
                    $product_type   = isset($item['type']) ? $item['type'] : '';
                    $productdata    = an_products($product_id);
                    $qty            = 0;
                    $price          = 0;
                    $img_src        = ASSET_PATH . 'backend/img/no_image.jpg';
                    if ( $productdata ) {
                        $qty        = $item['qty'];
                        $price      = ( $current_member->as_stockist >= 1 ) ? $productdata->price : $productdata->price_member;
                        $img_src    = an_product_image($productdata->image, false); 
                    }

                    $shop_order[] = array(
                        'id'                => $item['id'],
                        'rowid'             => $item['rowid'],
                        'cart_price'        => $item['price'],
                        'cart_subtotal'     => $item['subtotal'],
                        'qty'               => $qty,
                        'weight'            => ($productdata) ? $item['options']['weight'] : 0,
                        'product_status'    => (isset($productdata->status) && $productdata->status == 1) ? 'exist' : 'noexist',
                        'product_id'        => isset($productdata->id) ? $productdata->id : 0,
                        'product_name'      => isset($productdata->name) ? $productdata->name : 'Produk Tidak Ditemukan',
                        'product_slug'      => isset($productdata->slug) ? $productdata->slug : 'notfound',
                        'product_image'     => $img_src,
                        'product_weight'    => isset($productdata->weight) ? $productdata->weight : 0,
                        'product_stock'     => isset($productdata->stock) ? $productdata->stock : 0,
                        'product_price'     => $price,
                        'product_type'      => $product_type,
                        'min_order'         => 1,
                        'product_owner'     => 0,
                    );

                    foreach ($shop_order as $row) {
                        $product_status[]   = $row['product_status'];
                        $price_check[]      = ($row['product_price'] <> $row['cart_price']) ? 'error' : FALSE;
                        // $stock_check[]      = ($row['product_stock'] < $row['qty']) ? 'error' : FALSE;
                    }
                    $product_status_error   = (array_search('noexist', $product_status) !== FALSE) ? TRUE : FALSE;
                    $price_error            = (array_search('error', $price_check) !== FALSE) ? TRUE : FALSE;
                    // $stock_error            = (array_search('error', $stock_check) !== FALSE) ? TRUE : FALSE;
                    $stock_error            = FALSE;
                }

                if ( $stock_error || $product_status_error ) {
                    $has_error = TRUE;
                } else {
                    $has_error = FALSE;
                }

                $response = array(
                    'data'          => $shop_order,
                    'product_type'  => $product_type,
                    'has_error'     => $has_error,
                );
                return $response;
            } else {
                return false;
            }
        }

        return false;
    }
}

if (!function_exists('an_shop_product')){
    /**
     * Get Shop Product function.
     */
    function an_shop_product($id)
    {
        if ( !$id ) { return false; }

        $CI = &get_instance();
        $auth                   = auth_redirect( true );
        if ( $auth ) {
            $current_member     = an_get_current_member();
            $is_admin           = as_administrator($current_member);

            // Get Product
            $productdata        = an_products($id);
            if ( $productdata ) {
                $price          = $productdata->price;
                if ( $current_member->as_stockist == 1 ) { $price = $productdata->price_subagent; }
                if ( $current_member->as_stockist == 2 ) { $price = $productdata->price_agent; }
                $productdata->price = $price;
                return $productdata;
            } else {
                return false;
            }
        }

        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Proce
|--------------------------------------------------------------------------
*/
if (!function_exists('product_discount')) {
    function product_discount($product)
    {
        if ( ! $product ) { return 0; }
        if ( ! isset($product->price_agent) && ! isset($product->price_customer)) { return 0; }
        if ( ! isset($product->discount_agent) && ! isset($product->discount_agent)) { return 0; }
        if ( ! isset($product->discount_agent_min) && ! isset($product->discount_agent_min)) { return 0; }
        if ( ! isset($product->discount_agent_type) && ! isset($product->discount_agent_type)) { return 0; }

        $promo      = '';
        $currency   = config_item('currency');
        $price      = ( is_logged_in() ) ? $product->price_agent : $product->price_customer;
        $discount   = ( is_logged_in() ) ? $product->discount_agent : $product->discount_customer;
        $min_qty    = ( is_logged_in() ) ? $product->discount_agent_min : $product->discount_customer_min;
        $disctype   = ( is_logged_in() ) ? $product->discount_agent_type : $product->discount_customer_type;

        if ( $min_qty <= 1 && $discount > 0 ) {
            if ( $disctype == 'percent' ) {
                $promo = $discount .' %';
            } else {
                $promo = an_accounting($discount, $currency);
            }
        }

        return $promo;
    }
}

/*
|--------------------------------------------------------------------------
| Checking stock product availability
| With $qty = Check stock | Available / Not
| Without $qty = Display current stock
|--------------------------------------------------------------------------
*/
if (!function_exists('stock_availability')) {
    function stock_availability($id, $qty = '') {
        $CI = &get_instance();
        if ( $product = an_products($id, false) ) {
            if ($qty) {
                if ($qty > $product->stock) {
                    $result = array(
                        'status'    => 'failed',
                        'message'   => 'Maaf produk yang Anda order melebihi jumlah stok kami. Saat ini stok kami berjumlah ' . $product->stock,
                        'stock'     => $product->stock,
                    );
                    return $result;
                } else {
                    $result = array(
                        'status'    => 'success',
                        'message'   => 'On Stock',
                        'stock'     => $product->stock,
                    );
                    return $result;
                }
            } else {
                $result = array(
                    'stock' => $product->stock,
                );
                return $result;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Checking stock product availability
| With $qty = Check stock | Available / Not
| Without $qty = Display current stock
|--------------------------------------------------------------------------
*/
if (!function_exists('stock_product_order')) {
    function stock_product_order($id, $qty = '') {
        $CI = &get_instance();
        if ( $product = an_products($id, false) ) {
            if ($qty) {
                if ( $auth = auth_redirect( true ) ) {
                    $status         = 'success';
                    $message        = 'On Stock';
                    $stock_order    = $qty;
                    $stock_mod      = $qty % $product->min_order;
                    if ( $stock_mod ) {
                        $status         = 'failed';
                        $message        = 'Qty produk harus sesuai dengan syarat minimal order agen (kelipatan '.$product->min_order.' pcs)';
                        $stock_order    = $qty - $stock_mod;
                    }
                    $result = array(
                        'status'    => $status,
                        'stock'     => $stock_order,
                        'message'   => $message,
                    ); return $result;   
                } else {
                    $result = array(
                        'status'    => 'success',
                        'message'   => 'On Stock',
                        'stock'     => $qty,
                    ); return $result;                   
                }
            } else {
                $result = array(
                    'stock' => $product->stock,
                );
                return $result;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get Discount Code
|--------------------------------------------------------------------------
*/
if (!function_exists('discount_code')) {
    function discount_code($code)
    {
        if ( ! $code ) { return false; }
        $CI = &get_instance();
        $discount = $CI->Model_Option->get_promo_code_by('promo_code', $code);
        return $discount;
    }
}

/*
|--------------------------------------------------------------------------
| SUM of cart options
| ex : sum_cart_option('weight') = GET Total Weight
|--------------------------------------------------------------------------
*/
function sum_cart_option($value)
{
    $CI = &get_instance();

    $total_result = 0;
    if ($CI->cart->contents()) {
        foreach ($CI->cart->contents() as $total) {
            $total_result += $total['options'][$value];
        }
        return $total_result;
    } else {
        return 'No cart data';
    }
}

/*
|--------------------------------------------------------------------------
| Get Total With Promo Applied
| Param = discount / amount
|--------------------------------------------------------------------------
*/
function total_promo($param)
{
    $CI = &get_instance();

    $auth           = auth_redirect( true );
    $promo_amount   = $CI->session->userdata('promo_amount');
    $promo_type     = $CI->session->userdata('promo_type');
    $promo_product  = $CI->session->userdata('promo_product');
    $total          = $CI->cart->total();

    //print_r($promo_type);die;

    // return total discount
    if ( $param == 'discount' ) {
        if ($promo_type == 'nominal') {
            $new = $promo_amount;
        } else {
            if ( $promo_product ) {
                $total_price = 0;
                if ($CI->cart->contents()) {
                    foreach ($CI->cart->contents() as $item) {
                        $productId  = $item['id'];
                        $qty        = $item['qty'];
                        if ( $auth ) {
                            if ( $getPackage = an_product_package('id', $productId) ) {
                                $productDetail = isset($getPackage->product_details) ? $getPackage->product_details : false;
                                $productDetail = ($productDetail) ? maybe_unserialize($productDetail) : false; 
                                if ( $productDetail ) {
                                    foreach ($productDetail as $row) {
                                        foreach ($promo_product  as $key => $product) {
                                            if ( $product == $row['id'] ) {
                                                $price      = $row['price'] * $row['qty'];
                                                $subtotal   = $price * $qty;
                                                $total_price += ($subtotal);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($promo_product  as $key => $product) {
                                if ( $product == $productId ) {
                                    $total_price += ($item['subtotal']);
                                }
                            }
                        }
                    }
                }
                $new = $total_price * ($promo_amount / 100);
            } else {
                $new = $total * ($promo_amount / 100);
            }
        }
    }

    // return total cart - total discount
    if ( $param == 'amount' ) {
        if ( $promo_type == 'nominal' ) {
            $new = $total - $promo_amount;
        } else {
            $new = $total - ($total * ($promo_amount / 100));
        }
    }

    return ceil($new);
}

/*
|--------------------------------------------------------------------------
| Apply Code Promo
|--------------------------------------------------------------------------
*/
function apply_code_discount($owner, $code, $type, $amount, $products = '')
{
    $CI = &get_instance();

    // Unset previous Session
    remove_code_discount();

    // Set SESSION
    $promo_session = array(
        'promo_applied' => TRUE,
        'promo_owner'   => $owner,
        'promo_code'    => $code,
        'promo_type'    => $type,
        'promo_product' => $products,
        'promo_amount'  => $amount,
    );
    $CI->session->set_userdata($promo_session);
}

/*
|--------------------------------------------------------------------------
| Remove Code Promo
|--------------------------------------------------------------------------
*/
function remove_code_discount()
{
    $CI = &get_instance();

    $promo_session = array(
        'promo_applied',
        'promo_owner',
        'promo_code',
        'promo_type',
        'promo_product',
        'promo_amount'
    );

    $CI->session->unset_userdata($promo_session);
}

/*
|--------------------------------------------------------------------------
| Apply Code Promo
|--------------------------------------------------------------------------
*/
function apply_code_seller($code = '')
{
    $CI = &get_instance();
    $status = false;

    if ( $code ) {
        // Unset previous Session
        remove_code_seller();

        $conditions = array('type' => MEMBER, 'status' => 1);
        $sellerCode = $CI->Model_Member->get_member_by('id', $code, $conditions);
        if ( $sellerCode ) {
            if ( $sellerCode->as_stockist > 0 ) {

                $province_name = '';
                if ( $sellerCode->province_stockist && $getprovince = an_provinces($sellerCode->province_stockist) ) {
                    $province_name = ucwords(strtolower($getprovince->province_name));
                    $province_name = str_replace('Dki ', 'DKI ', $province_name);
                    $province_name = str_replace('Di ', 'DI ', $province_name);
                }

                $district_name = '';
                if ( $sellerCode->district_stockist && $getdistrict = an_districts($sellerCode->district_stockist) ) {
                    $district_name = ucwords(strtolower($getdistrict->district_name));
                }

                $subdistrict_name = '';
                if ( $sellerCode->subdistrict_stockist && $getsubdistrict = an_subdistricts($sellerCode->subdistrict_stockist) ) {
                    $subdistrict_name = ucwords(strtolower($getsubdistrict->subdistrict_name));
                }

                $village_name = '';
                if ( $sellerCode->village_stockist && $getvillage = an_villages($sellerCode->village_stockist) ) {
                    $village_name = ucwords(strtolower($getvillage->village_name));
                }

                $address        = $sellerCode->address .', '. $village_name .br();
                $address       .= 'Kec. '. $subdistrict_name .' '. $district_name .br();
                $address       .= $province_name;

                // Set SESSION
                $session = array(
                    'seller_ref_applied'    => TRUE,
                    'seller_ref_id'         => $sellerCode->id,
                    'seller_ref_username'   => $sellerCode->username,
                    'seller_ref_name'       => $sellerCode->name,
                    'seller_ref_phone'      => $sellerCode->phone,
                    'seller_ref_address'    => $address,
                );
                $CI->session->set_userdata($session);
                $status = true; 
            }
        }
    }

    return $status;
}


/*
|--------------------------------------------------------------------------
| Remove Code Seller
|--------------------------------------------------------------------------
*/
function remove_code_seller()
{
    $CI = &get_instance();

    $session = array(
        'seller_ref_applied',
        'seller_ref_id',
        'seller_ref_username',
        'seller_ref_name',
        'seller_ref_phone',
        'seller_ref_address',
    );

    $CI->session->unset_userdata($session);
}

/*
|--------------------------------------------------------------------------
| Check Agent
|--------------------------------------------------------------------------
*/
if (!function_exists('an_check_agent')) {
    function an_check_agent($remove_seller = false)
    {
        $CI = &get_instance();
        $status     = true;
        $idSeller   = $CI->session->userdata('seller_ref_id');
        if ( ! $idSeller ) {
            return false;
        }

        if ( ! $seller = an_get_memberdata_by_id($idSeller) ) {
            if ( $remove_seller ) {
                remove_code_seller();
            }
            return false;
        }

        if ( $seller->status != ACTIVE || $seller->type != MEMBER || $seller->as_stockist == 0 ) {
            if ( $remove_seller ) {
                remove_code_seller();
            }
            return false;
        }

        $usernameSeller = $CI->session->userdata('seller_ref_username');
        if ( $usernameSeller ) {
            if ( strtolower($usernameSeller) !== strtolower($seller->username)) {
                if ( $remove_seller ) {
                    remove_code_seller();
                }
                return false;
            }
        }

        $seller             = new stdClass();
        $seller->id         = $idSeller;
        $seller->username   = $usernameSeller;
        $seller->name       = $CI->session->userdata('seller_ref_name');
        $seller->phone      = $CI->session->userdata('seller_ref_phone');
        $seller->address    = $CI->session->userdata('seller_ref_address');
        return $seller;
    }
}

if ( !function_exists('an_shipping_fee') )
{
    /**
     * Get Shipping Fee data
     * @author  Yuda
     * @param   Int         $origin         (Required)  ID City Of Origin
     * @param   Int         $id_city        (Required)  ID City Of Destination
     * @param   Int         $id_district    (Required)  ID District/Subdistrict Of Destination
     * @param   Int         $weight         (Required)  Total Weight Of Product
     * @param   Varchar     $courier        (Required)  Courier
     * @return  Shipping Fee data
     */
    function an_shipping_fee($origin, $id_city, $id_district = 0, $weight, $courier = 'jne', $origin_type = '') {
        $CI =& get_instance();

        if ( !is_numeric($origin) ) return false;
        if ( !is_numeric($id_city) ) return false;
        if ( !is_numeric($weight) ) return false;
        if ( !$courier ) return false;

        $shipping_active    = config_item('rajaongkir_active');
        $token              = config_item('rajaongkir_token');
        $url                = config_item('rajaongkir_url');

        if ( !$shipping_active ) return false;

        $destination        = $id_district ? $id_district : $id_city;
        $destinationType    = $id_district ? 'subdistrict' : 'city';
        $originType         = $origin_type ? $origin_type : 'city';

        $post_params            = array(
            'origin'            => $origin,
            'originType'        => $originType,
            'destination'       => $destination,
            'destinationType'   => $destinationType,
            'weight'            => $weight,
            'courier'           => $courier,
        );

        $postfield  = http_build_query($post_params);
        $curl       = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url .'cost',
            CURLOPT_RETURNTRANSFER  => true,           
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $postfield,
            CURLOPT_HTTPHEADER      => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $token
            )
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ( $err ) return false;

        $response       = ( is_json($response) ? json_decode($response) : false );
        $rajaongkir     = isset($response->rajaongkir) ? $response->rajaongkir : false;
        $status         = isset($rajaongkir->status) ? $rajaongkir->status : false;
        $status_code    = isset($status->code) ? $status->code : false;
        $description    = isset($status->description) ? $status->description : '';
        
        if ( $status_code == 400 ) {
            $message    = str_replace('Weight', 'Berat', $description);
            return array( 'status' => false, 'data' => $message );
        }

        if ( $status_code == 200 ) {
            $results        = isset($rajaongkir->results) ? $rajaongkir->results : false;
            $origin         = isset($rajaongkir->origin_details) ? $rajaongkir->origin_details : false;
            $destination    = isset($rajaongkir->destination_details) ? $rajaongkir->destination_details : false;
            $costs          = isset($results[0]->costs) ? $results[0]->costs : false;
            
            if ( $costs ) {
                return array( 'status' => true, 'data' => $costs );
            }
        }

        return false;
    }
}


/*
|--------------------------------------------------------------------------
| Generate Mitra Invoice Shop
|--------------------------------------------------------------------------
*/
function an_generate_member_invoice($id_member)
{
    if ( !$id_member ) { return false; }
    $CI = &get_instance();

    $sql = 'SELECT username, unique_shop_invoice AS value FROM ' . TBL_USERS . ' WHERE id = ? FOR UPDATE';
    $qry = $CI->db->query($sql, array($id_member));
    if(!$qry || !$qry->num_rows()) return false;
    $row = $qry->row();

    $invoice_prefix = config_item('invoice_prefix');
    $number         = intval($row->value);
    $unique_number  = str_pad($number + 1, 8, '0', STR_PAD_LEFT);
    $invoice        = $invoice_prefix . strtoupper($row->username) .'/'. $unique_number; // XX/username/000001

    if($unique_number == 99999999) {
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET unique_shop_invoice = ? WHERE id = ?';
        $no_update  = 0;
    } else {
        $no_update  = $number + 1;
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET unique_shop_invoice = ? WHERE id = ?';
    }

    $CI->db->query($sql_update, array($no_update, $id_member));
    return $invoice;
}


/*
|--------------------------------------------------------------------------
| Generate Uniquecode Shop
|--------------------------------------------------------------------------
*/
function an_generate_member_uniquecode($id_member)
{
    if ( !$id_member ) { return false; }
    $CI = &get_instance();

    $sql = 'SELECT username, uniquecode_shop AS value FROM ' . TBL_USERS . ' WHERE id = ? FOR UPDATE';
    $qry = $CI->db->query($sql, array($id_member));
    if(!$qry || !$qry->num_rows()) return false;
    $row = $qry->row();

    $number         = intval($row->value);
    $unique_number  = str_pad($number + 1, 8, '0', STR_PAD_LEFT);

    if($unique_number == 999) {
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET uniquecode_shop = ? WHERE id = ?';
        $no_update  = 0;
    } else {
        $no_update  = $number + 1;
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET uniquecode_shop = ? WHERE id = ?';
    }

    $CI->db->query($sql_update, array($no_update, $id_member));
    return $unique_number;
}

/*
|--------------------------------------------------------------------------
| Generate Invoice Shop
|--------------------------------------------------------------------------
*/
function an_generate_shop_invoice()
{
    $CI = &get_instance();
    $invoice_prefix = config_item('invoice_prefix');
    $invoice_number = an_generate_invoice();
    $invoice        = $invoice_prefix . $invoice_number; // XX-000001
    return $invoice;
}

/*
|--------------------------------------------------------------------------
| Generate Code Unique Shop Payment
|--------------------------------------------------------------------------
*/
function generate_uniquecode()
{
    $CI = &get_instance();
    $uniquecode = an_generate_shop_order();
    return $uniquecode;
}

/*
|--------------------------------------------------------------------------
| Get Product Order
|--------------------------------------------------------------------------
*/
if (!function_exists('an_extract_products_order')) {
    function an_extract_products_order($order)
    {
        $res        = array();
        if ( !$order ) { return $res; }

        $products   = isset($order->products) ? $order->products : false;
        $currency   = config_item('currency');
        if ( is_serialized($products) ) {
            $unserialize_data = maybe_unserialize($products);

            $no                 = 1;
            $cart_package       = 0;
            $total_price_pack   = 0;
            $total_qty_pack     = 0;
            $package_name       = '';
            $count_data         = count($unserialize_data);

            foreach ($unserialize_data as $row) {
                $id             = isset($row['id']) ? $row['id'] : 0;
                $name           = isset($row['name']) ? $row['name'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $subtotal       = $qty * $price_cart;


                $data['product_name']       = $name;
                $data['qty']                = $qty;
                $data['price']              = $price;
                $data['price_cart']         = $price_cart;
                $data['subtotal']           = $subtotal;
                $res[]                      = $data;
            }
        }

        return $res;
    }
}


/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_product($id, $provincearea = 1) //default 1 aja
{
    if ( !$id ) { return false; }

    $CI = &get_instance();

    if ( $product = an_products($id, false) ) {
        $price      = ( is_logged_in() ) ? $product->{"price_agent".$provincearea} : $product->{"price_customer".$provincearea};
        $min_qty    = ( is_logged_in() ) ? $product->discount_agent_min : $product->discount_customer_min;
        $discount   = ( is_logged_in() ) ? $product->discount_agent : $product->discount_customer;
        $disctype   = ( is_logged_in() ) ? $product->discount_agent_type : $product->discount_customer_type;
        $min_order  = ( is_logged_in() ) ? $product->min_order : 1;

        $product->min_order_agent   = $product->min_order;
        $product->min_order         = $min_order;
        $product->price             = $price;
        $product->min_qty           = $min_qty;
        $product->discount          = $discount;
        $product->discount_type     = $disctype;

        return $product;
    } else {
        return false;
    }
}
/*
|--------------------------------------------------------------------------
| Get Product Package Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_product_package($limit=0, $offset=0, $conditions='', $order_by='')
{
    $CI = &get_instance();
    if ( $data = $CI->Model_Product->get_all_product_package($limit, $offset, $conditions, $order_by) ) {
        $total_data = an_get_last_found_rows();
        return array('data' => $data, 'total_row' => $total_data);
    } else {
        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function data_product($id, $param)
{
    if ( !$id && ! $param ) { return false; }

    $CI = &get_instance();

    if ( $product = an_products($id, false) ) {
        if ( $param == 'price' ) {
            if ( is_logged_in() ) {
                $param = 'price_agent';
            } else {
                $param = 'price_customer';
            }
        }
        return $product->$param;
    } else {
        return 'Produk tidak ditemukan';
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_search_product($limit = 0, $start = 0, $condition = '', $order_by = '')
{
    $CI = &get_instance();

    if ( $data = $CI->Model_Product->get_all_product($limit, $start, $condition, $order_by) ) {
        $total_data = an_get_last_found_rows();
        return array('data' => $data, 'total_row' => $total_data);
    }
    return false;
}

/*
|--------------------------------------------------------------------------
| Get Category Name By ID
|--------------------------------------------------------------------------
*/
if (!function_exists('shop_category')) {
    function shop_category($id, $val)
    {
        $CI = &get_instance();
        $query = an_product_category($id, false);

        if ($query) {
            $category = isset($query->$val) ? $query->$val : false;
            return $category;
        } else {
            return 'No Category Found';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Proce
|--------------------------------------------------------------------------
*/
if (!function_exists('product_price')) {
    function product_price($product)
    {
        if ( ! $product ) { return 0; }
        if ( ! isset($product->price_agent) && ! isset($product->price_customer)) { return 0; }
        $price = ( is_logged_in() ) ? $product->price_agent : $product->price_customer;
        return $price;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Proce
|--------------------------------------------------------------------------
*/
if (!function_exists('product_discount')) {
    function product_discount($product)
    {
        if ( ! $product ) { return 0; }
        if ( ! isset($product->price_agent) && ! isset($product->price_customer)) { return 0; }
        if ( ! isset($product->discount_agent) && ! isset($product->discount_agent)) { return 0; }
        if ( ! isset($product->discount_agent_min) && ! isset($product->discount_agent_min)) { return 0; }
        if ( ! isset($product->discount_agent_type) && ! isset($product->discount_agent_type)) { return 0; }

        $promo      = '';
        $currency   = config_item('currency');
        $price      = ( is_logged_in() ) ? $product->price_agent : $product->price_customer;
        $discount   = ( is_logged_in() ) ? $product->discount_agent : $product->discount_customer;
        $min_qty    = ( is_logged_in() ) ? $product->discount_agent_min : $product->discount_customer_min;
        $disctype   = ( is_logged_in() ) ? $product->discount_agent_type : $product->discount_customer_type;

        if ( $min_qty <= 1 && $discount > 0 ) {
            if ( $disctype == 'percent' ) {
                $promo = $discount .' %';
            } else {
                $promo = an_accounting($discount, $currency);
            }
        }

        return $promo;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Iamge
|--------------------------------------------------------------------------
*/
if (!function_exists('product_image')) {
    function product_image($image, $thumbnail = true )
    {
        $CI = &get_instance();

        $no_image = ASSET_PATH . 'backend/img/no_image.jpg';
        if ( $image ) {
            $thumb_path = $thumbnail ? 'thumbnail/' : '';
            $img_src    = PRODUCT_IMG_PATH . $thumb_path . $image;
            if ( file_exists($img_src) ) {
                $img_src = PRODUCT_IMG . $thumb_path . $image;
            } else {
                $img_src = $no_image; 
            }
            return $img_src;
        } else {
            return $no_image;
        }
    }
}
