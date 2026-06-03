<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('AN_Model.php');

class Model_Shop extends AN_Model{
    /**
     * Initialize table
     */
    var $shop_order             = TBL_PREFIX . "shop_order";
    var $shop_order_detail      = TBL_PREFIX . "shop_order_detail";
    var $shop_order_customer    = TBL_PREFIX . "shop_order_customer";
    var $shop_detail_customer   = TBL_PREFIX . "shop_order_detail_customer";
    var $member                 = TBL_PREFIX . "member";
    var $customer               = TBL_PREFIX . "customer";
    var $product                = TBL_PREFIX . "product";
    var $product_category       = TBL_PREFIX . "product_category";
    var $pin                    = TBL_PREFIX . "pin";
    var $pin_transfer           = TBL_PREFIX . "pin_transfer";
    var $payment_evidence       = TBL_PREFIX . "payment_evidence";
    
    /**
     * Initialize primary field
     */
    var $primary            = "id";
    
    /**
    * Constructor - Sets up the object properties.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /*
	|--------------------------------------------------------------------------
    | Get All Products
	|--------------------------------------------------------------------------
    */
    function get_products($condition = "")
    {
        $this->db->select('*');
        $this->db->from(TBL_PRODUCT);

        if ($condition) {
            $condition;
        }

        $result = $this->db->get();
        //print_r($result);die;
        return $result;
    }
    
    /**
     * Get shop order
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of data
     * @return  Mixed   False on invalid date parameter, otherwise data of data(s).
     */
    function get_shop_orders($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("datecreated", "DESC"); 
        $query      = $this->db->get($this->shop_order);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_order_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->shop_order);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'invoice' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_detail_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("id", "ASC"); 
        $query  = $this->db->get($this->shop_order_detail);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order customer by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_order_customer_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->shop_order_customer);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'invoice' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }
    
    /**
     * Get customer
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of data
     * @return  Mixed   False on invalid date parameter, otherwise data of data(s).
     */
    function get_customers($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("datecreated", "DESC"); 
        $query      = $this->db->get($this->customer);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get all member pin
     *
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID
     * @param   String  $status     (Optional)  Status of Pin, default 'all'
     * @param   Boolean $count      (Optional)  Count PIN, default 'false'
     * @return  Mixed   False on invalid member id, otherwise array of all pin.
     */
    function get_pins($id_member, $status='all', $count=false, $product=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member  = absint($id_member);
        if ( !$id_member ) return false;

        $data       = array('id_member' => $id_member);

        if ( $status == 'active' ){
            $data['status'] = 1;
        } elseif ( $status == 'pending' ){
            $data['status'] = 0;
        } elseif ( $status == 'used' ){
            $data['status'] = 2;
        }

        if ( !empty($product) ) { $data['product'] = $product; }

        $this->db->where($data);
        $query = $this->db->get($this->pin);

        if( $count ){
            if ( $query->num_rows() > 0 ){
                return $query->num_rows();
            }
            return 0;
        }else{
            if ( !$query->num_rows() ){
                return false;
            }
            return $query->result();
        }
    }

    /**
     * Get pin by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_pin_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "DESC"); 
        $query  = $this->db->get($this->pin);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get customer by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_customer_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->customer);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'phone' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_payment_evidence_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("id", "ASC"); 
        $query  = $this->db->get($this->payment_evidence);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'id_source' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Retrieve all shop order data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_shop_order_data($limit=0, $offset=0, $conditions='', $order_by='', $params = ''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",                   "PO.id", $conditions);
            $conditions = str_replace("%id_member%",            "PO.id_member", $conditions);
            $conditions = str_replace("%id_stockist%",          "PO.id_stockist", $conditions);
            $conditions = str_replace("%invoice%",              "PO.invoice", $conditions);
            $conditions = str_replace("%type%",                 "PO.type_order", $conditions);
            $conditions = str_replace("%customer%",             "PO.name", $conditions);
            $conditions = str_replace("%phone%",                "PO.phone", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%access_order%",         "PO.access_order", $conditions);
            $conditions = str_replace("%type_member%",          "M.type", $conditions);
            $conditions = str_replace("%stockist%",             "ST.username", $conditions);
            $conditions = str_replace("%stockist_name%",        "ST.name", $conditions);
            $conditions = str_replace("%unique%",               "PO.unique", $conditions);
            $conditions = str_replace("%status%",               "PO.status", $conditions);
            $conditions = str_replace("%received%",             "PO.name", $conditions);
            $conditions = str_replace("%email%",                "PO.email", $conditions);
            $conditions = str_replace("%phone%",                "PO.phone", $conditions);
            $conditions = str_replace("%province%",             "PO.province", $conditions);
            $conditions = str_replace("%city%",                 "PO.city", $conditions);
            $conditions = str_replace("%subdistrict%",          "PO.subdistrict", $conditions);
            $conditions = str_replace("%address%",              "PO.address", $conditions);
            $conditions = str_replace("%datecreated%",          "PO.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "PO.datemodified", $conditions);
            $conditions = str_replace("%dateconfirmed%",        "PO.dateconfirmed", $conditions);
            $conditions = str_replace("%dateexpired%",          "PO.dateexpired", $conditions);
            $conditions = str_replace("%confirm%",              "PO.confirm", $conditions);
            $conditions = str_replace("%confirmed_by%",         "PO.confirmed_by", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",                   "PO.id",  $order_by);
            $order_by   = str_replace("%id_member%",            "PO.id_member", $order_by);
            $order_by   = str_replace("%invoice%",              "PO.invoice", $order_by);
            $order_by   = str_replace("%type%",                 "PO.type_order", $order_by);
            $order_by   = str_replace("%customer%",             "PO.name", $order_by);
            $order_by   = str_replace("%phone%",                "PO.phone", $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%access_order%",         "PO.access_order", $order_by);
            $order_by   = str_replace("%stockist%",             "ST.username", $order_by);
            $order_by   = str_replace("%stockist_name%",        "ST.name", $order_by);
            $order_by   = str_replace("%unique%",               "PO.unique", $order_by);
            $order_by   = str_replace("%status%",               "PO.status", $order_by);
            $order_by   = str_replace("%received%",             "PO.name", $order_by);
            $order_by   = str_replace("%email%",                "PO.email", $order_by);
            $order_by   = str_replace("%phone%",                "PO.phone", $order_by);
            $order_by   = str_replace("%province%",             "PO.province", $order_by);
            $order_by   = str_replace("%city%",                 "PO.city", $order_by);
            $order_by   = str_replace("%subdistrict%",          "PO.subdistrict", $order_by);
            $order_by   = str_replace("%address%",              "PO.address", $order_by);
            $order_by   = str_replace("%datecreated%",          "PO.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "PO.datemodified", $order_by);
            $order_by   = str_replace("%dateconfirmed%",        "PO.dateconfirmed", $order_by);
            $order_by   = str_replace("%dateexpired%",          "PO.dateexpired", $order_by);
            $order_by   = str_replace("%confirm%",              "PO.confirm", $order_by);
            $order_by   = str_replace("%confirmed_by%",         "PO.confirmed_by", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    PO.*,
                    M.username,
                    M.name AS membername,
                    M.photo AS photoprofile,
                    ST.username AS stockist,
                    ST.name AS stockistname,
                    ST.photo AS stockistphoto
                FROM ' . $this->shop_order . ' AS PO 
                INNER JOIN ' . $this->member . ' AS M ON (M.id = PO.id_member) 
                LEFT JOIN ' . $this->member . ' AS ST ON (ST.id = PO.id_stockist) 
                WHERE PO.id > 0 ';

        if( !empty($conditions) ){ $sql .= $conditions; }
        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'PO.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ){
            $query = $this->db->query( $sql, $params );
        } else {
            $query = $this->db->query( $sql );
        }

        if(!$query || !$query->num_rows()) return false;
        return $query->result();
    }

    /**
     * Retrieve all shop order data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_shop_order_customer_data($limit=0, $offset=0, $conditions='', $order_by='', $num_rows = false){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",                   "PO.id", $conditions);
            $conditions = str_replace("%id_member%",            "PO.id_member", $conditions);
            $conditions = str_replace("%id_customer%",          "PO.id_customer", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%type%",                 "M.type", $conditions);
            $conditions = str_replace("%unique%",               "PO.unique", $conditions);
            $conditions = str_replace("%status%",               "PO.status", $conditions);
            $conditions = str_replace("%received%",             "PO.name", $conditions);
            $conditions = str_replace("%email%",                "PO.email", $conditions);
            $conditions = str_replace("%phone%",                "PO.phone", $conditions);
            $conditions = str_replace("%province%",             "PO.province", $conditions);
            $conditions = str_replace("%city%",                 "PO.city", $conditions);
            $conditions = str_replace("%subdistrict%",          "PO.subdistrict", $conditions);
            $conditions = str_replace("%address%",              "PO.address", $conditions);
            $conditions = str_replace("%datecreated%",          "PO.datecreated", $conditions);
            $conditions = str_replace("%dateconfirm%",          "PO.dateconfirm", $conditions);
            $conditions = str_replace("%datemodified%",         "PO.datemodified", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",                   "PO.id",  $order_by);
            $order_by   = str_replace("%id_member%",            "PO.id_member", $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%unique%",               "PO.unique", $order_by);
            $order_by   = str_replace("%status%",               "PO.status", $order_by);
            $order_by   = str_replace("%received%",             "PO.name", $order_by);
            $order_by   = str_replace("%email%",                "PO.email", $order_by);
            $order_by   = str_replace("%phone%",                "PO.phone", $order_by);
            $order_by   = str_replace("%province%",             "PO.province", $order_by);
            $order_by   = str_replace("%city%",                 "PO.city", $order_by);
            $order_by   = str_replace("%subdistrict%",          "PO.subdistrict", $order_by);
            $order_by   = str_replace("%address%",              "PO.address", $order_by);
            $order_by   = str_replace("%datecreated%",          "PO.datecreated", $order_by);
            $order_by   = str_replace("%dateconfirm%",          "PO.dateconfirm", $order_by);
            $order_by   = str_replace("%datemodified%",         "PO.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    PO.*,
                    M.username,
                    M.name AS membername
                FROM ' . $this->shop_order_customer . ' AS PO 
                INNER JOIN ' . $this->member . ' AS M ON (M.id = PO.id_member) ';

        if( !empty($conditions) ){ $sql .= $conditions; }
        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'PO.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query( $sql );
        if(!$query || !$query->num_rows()) return false;

        if ( $num_rows ){
            return $query->num_rows();
        }

        return $query->result();
    }

    /**
     * Retrieve all omzet Order Monthly data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_shop_order_monthly($limit=0, $offset=0, $conditions='', $order_by='', $total_conditions=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%month_omzet%",          "A.month_omzet", $conditions);
        }

        if ( $total_conditions ) {
            $total_conditions = str_replace("%total_trx%",          "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%subtotal%",           "SUM(A.subtotal)", $total_conditions);
            $total_conditions = str_replace("%total_shipping%",     "SUM(A.shipping)", $total_conditions);
            $total_conditions = str_replace("%total_discount%",     "SUM(A.discount)", $total_conditions);
            $total_conditions = str_replace("%total_payment%",      "SUM(A.payment)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        "SUM(A.omzet)", $total_conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%month_omzet%",          "A.month_omzet", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
            $order_by   = str_replace("%subtotal%",             "subtotal", $order_by);
            $order_by   = str_replace("%total_shipping%",       "total_shipping", $order_by);
            $order_by   = str_replace("%total_discount%",       "total_discount", $order_by);
            $order_by   = str_replace("%total_payment%",        "total_payment", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
        }        

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(A.subtotal), 0 ) AS subtotal,
                        IFNULL( SUM(A.shipping), 0 ) AS total_shipping,
                        IFNULL( SUM(A.discount), 0 ) AS total_discount,
                        IFNULL( SUM(A.payment), 0 ) AS total_payment,
                        IFNULL( SUM(A.omzet), 0 ) AS total_omzet
                    FROM (
                        SELECT 
                            DATE_FORMAT(S.datecreated, "%Y-%m") AS month_omzet,
                            S.subtotal AS subtotal,
                            S.shipping AS shipping,
                            S.discount AS discount,
                            S.total_payment AS payment,
                            (S.total_payment - S.shipping - S.unique) AS omzet
                        FROM `'. $this->shop_order .'` AS S
                        WHERE S.status = 1
                    ) AS A ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' GROUP BY 1 ';

        if ( $total_conditions ) { $sql .= ' HAVING ' . ltrim( $total_conditions, ' AND' ); }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'month_omzet DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all total pin member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $conditions         Condition of query          default ''
     * @param   Array   $params             Parameter of Condition      default ''
     * @return  Object  Result of data list
     */
    function get_all_total_pin_member($limit=0, $offset=0, $conditions='', $order_by='', $params=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%type%",                 "A.type", $conditions);
            $conditions = str_replace("%username%",             "A.username", $conditions);
            $conditions = str_replace("%id_member%",            "A.id", $conditions);
            $conditions = str_replace("%name%",                 "A.name", $conditions);
            $conditions = str_replace("%total%",                "B.total", $conditions);
            $conditions = str_replace("%total_active%",         "C.total_active", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",             "A.username",  $order_by);
            $order_by   = str_replace("%id_member%",            "A.id",  $order_by);
            $order_by   = str_replace("%name%",                 "A.name",  $order_by);
            $order_by   = str_replace("%total%",                "B.total",  $order_by);
            $order_by   = str_replace("%total_active%",         "C.total_active",  $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS A.id, A.username, A.name, A.type, B.total, C.total_active
                FROM ' . $this->member . ' AS A
                LEFT JOIN (
                    SELECT id_member, IFNULL(COUNT(id),0) AS total
                    FROM ' . $this->pin . '
                    GROUP BY id_member
                ) AS B ON (B.id_member = A.id) 
                LEFT JOIN (
                    SELECT id_member, IFNULL(COUNT(id),0) AS total_active
                    FROM ' . $this->pin . '
                    WHERE status = 1
                    GROUP BY id_member
                ) AS C ON (C.id_member = A.id) WHERE A.type != 1 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'C.total_active DESC, B.total DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all pin member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of pin                default 0
     * @param   Int     $offset             Offset ot pin               default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   Array   $params             Parameter of Condition      default ''
     * @return  Object  Result of data list
     */
    function get_all_pin_member($limit=0, $offset=0, $conditions='', $order_by='', $params=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id_member%",            "A.id_member", $conditions);
            $conditions = str_replace("%id_memberreg%",         "A.id_member_registered", $conditions);
            $conditions = str_replace("%id_memberres%",         "A.id_member_register", $conditions);
            $conditions = str_replace("%id_pin%",               "A.id_pin", $conditions);
            $conditions = str_replace("%product%",              "P.name", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%used%",                 "A.used", $conditions);
            $conditions = str_replace("%owner%",                "B.username", $conditions);
            $conditions = str_replace("%owner_name%",           "B.name", $conditions);
            $conditions = str_replace("%username%",             "C.username", $conditions);
            $conditions = str_replace("%name%",                 "C.name", $conditions);
            $conditions = str_replace("%register%",             "D.username", $conditions);
            $conditions = str_replace("%register_name%",        "D.name", $conditions);
            $conditions = str_replace("%datecreated%",          "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "A.datemodified", $conditions);
            $conditions = str_replace("%dateused%",             "A.dateused", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id_member%",            "A.id_member", $order_by);
            $order_by   = str_replace("%id_memberreg%",         "A.id_member_registered", $order_by);
            $order_by   = str_replace("%id_memberres%",         "A.id_member_register", $order_by);
            $order_by   = str_replace("%id_pin%",               "A.id_pin",  $order_by);
            $order_by   = str_replace("%product%",              "P.name",  $order_by);
            $order_by   = str_replace("%status%",               "A.status",  $order_by);
            $order_by   = str_replace("%used%",                 "A.used",  $order_by);
            $order_by   = str_replace("%owner%",                "B.username",  $order_by);
            $order_by   = str_replace("%owner_name%",           "B.name", $order_by);
            $order_by   = str_replace("%username%",             "C.username",  $order_by);
            $order_by   = str_replace("%name%",                 "C.name", $order_by);
            $order_by   = str_replace("%register%",             "D.username",  $order_by);
            $order_by   = str_replace("%register_name%",        "D.name", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated",  $order_by);
            $order_by   = str_replace("%datemodified%",         "A.datemodified",  $order_by);
            $order_by   = str_replace("%dateused%",             "A.dateused",  $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.*,
                    B.username AS username,
                    B.name AS name,
                    B.type AS type,
                    C.id AS id_registered,
                    C.username AS username_registered,
                    C.name AS name_registered,
                    D.id AS id_register,
                    D.username AS username_register,
                    D.name AS name_register,
                    P.name AS product_name
                FROM ' . $this->pin . ' AS A
                LEFT JOIN ' . $this->member . ' AS B ON (B.id = A.id_member)
                LEFT JOIN (
                    SELECT id, username, name FROM ' . $this->member . '
                ) AS C ON (C.id = A.id_member_registered)
                LEFT JOIN (
                    SELECT id, username, name FROM ' . $this->member . '
                ) AS D ON (D.id = A.id_member_register)
                LEFT JOIN `'. $this->product .'` AS P ON (P.id = A.product) ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.datemodified DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all total pin product data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   Array   $params             Parameter of Condition      default ''
     * @return  Object  Result of data list
     */
    function get_all_total_pin_product($limit=0, $offset=0, $conditions='', $order_by='', $params=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%name%",             "A.name", $conditions);
            $conditions = str_replace("%bv%",               "A.bv", $conditions);
            $conditions = str_replace("%price%",            "A.price", $conditions);
            $conditions = str_replace("%price_member%",     "A.price_member", $conditions);
            $conditions = str_replace("%price_customer%",   "A.price_customer", $conditions);
            $conditions = str_replace("%total_active%",     "B.total", $conditions);
            $conditions = str_replace("%total_used%",       "C.total_used", $conditions);
            $conditions = str_replace("%total%",            "( IFNULL(B.total_active,0) + IFNULL(C.total_used,0) )", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "A.id", $order_by);
            $order_by   = str_replace("%name%",             "A.name", $order_by);
            $order_by   = str_replace("%bv%",               "A.bv", $order_by);
            $order_by   = str_replace("%price%",            "A.price", $order_by);
            $order_by   = str_replace("%price_member%",     "A.price_member", $order_by);
            $order_by   = str_replace("%price_customer%",   "A.price_customer", $order_by);
            $order_by   = str_replace("%total_active%",     "B.total_active", $order_by);
            $order_by   = str_replace("%total_used%",       "C.total_used", $order_by);
            $order_by   = str_replace("%total%",            "total_pin", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.*, 
                    B.total_active, C.total_used, ( IFNULL(B.total_active,0) + IFNULL(C.total_used,0) ) AS total_pin
                FROM ' . $this->product . ' AS A
                LEFT JOIN (
                    SELECT PA.product, IFNULL(COUNT(PA.id),0) AS total_active
                    FROM ' . $this->pin . ' PA
                    WHERE PA.status = 1
                    GROUP BY PA.product
                ) AS B ON (B.product = A.id) 
                LEFT JOIN (
                    SELECT PU.product, IFNULL(COUNT(PU.id),0) AS total_used
                    FROM ' . $this->pin . ' PU
                    WHERE PU.status = 2
                    GROUP BY PU.product
                ) AS C ON (C.product = A.id) 
                WHERE A.id > 0';

        if( !empty($conditions) ){ $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.name ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member pin data
     *
     * @author  Yuda
     * @param   Integer $member_id          Member ID
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   Array   $params             Parameter of Condition      default ''
     * @return  Object  Result of member bonus list
     */
    function get_all_my_pin($id_member, $limit=0, $offset=0, $conditions='', $order_by='', $params=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member = absint($id_member);
        if ( !$id_member ) return false;

        if( !empty($conditions) ){
            $conditions = str_replace("%id_pin%",           "A.id_pin", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "C.username", $conditions);
            $conditions = str_replace("%product%",          "P.name", $conditions);
            $conditions = str_replace("%product_bv%",       "P.bv", $conditions);
            $conditions = str_replace("%bv%",               "A.bv", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%price%",            "P.price", $conditions);
            $conditions = str_replace("%price_member%",     "P.price_member", $conditions);
            $conditions = str_replace("%username_sender%",  "B.username_sender", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%datetransfer%",     "B.datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id_pin%",           "A.id_pin",  $order_by);
            $order_by   = str_replace("%status%",           "A.status",  $order_by);
            $order_by   = str_replace("%username%",         "C.username",  $order_by);
            $order_by   = str_replace("%product%",          "P.name", $order_by);
            $order_by   = str_replace("%product_bv%",       "P.bv", $order_by);
            $order_by   = str_replace("%bv%",               "A.bv", $order_by);
            $order_by   = str_replace("%amount%",           "A.amount", $order_by);
            $order_by   = str_replace("%price%",            "P.price", $order_by);
            $order_by   = str_replace("%price_member%",     "P.price_member", $order_by);
            $order_by   = str_replace("%username_sender%",  "B.username_sender",  $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%datetransfer%",     "datetransfer",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS A.*,
                IFNULL(B.id_member_sender, 0) AS id_member_sender,
                IFNULL(B.username_sender, "admin") AS username_sender,
                IFNULL(B.id_member, 0) AS id_member_receiver,
                IFNULL(B.username, 0) AS username_receiver,
                IFNULL(B.id_pin, 0) AS pin_transfered,
                IFNULL(B.datecreated, A.datecreated) AS datetransfer,
                C.username,
                A.bv,
                A.amount,
                P.name AS product_name,
                P.bv AS product_bv,
                P.price,
                P.price_member
            FROM `'. $this->pin .'` AS A
            LEFT JOIN (
                SELECT
                    max(id),
                    id_member_sender,
                    username_sender,
                    id_member,
                    username,
                    id_pin,
                    max(datecreated) AS datecreated
                FROM `'. $this->pin_transfer .'`
                WHERE id_member = '. $id_member .'
                GROUP BY id_pin
            ) AS B ON (B.id_pin = A.id)
            LEFT JOIN `'. $this->member .'` AS C ON (C.id = A.id_member)
            LEFT JOIN `'. $this->product .'` AS P ON (P.id = A.product)
            WHERE A.id_member = ? ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'B.datecreated DESC, A.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;


        if ( $params && is_array($params) ) {
            $array = array_merge(array($id_member), $params);
            $query = $this->db->query($sql, $array);
        } else {
            $query = $this->db->query($sql, array($id_member));
        }
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all pin transfer data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   Array   $params             Parameter of Condition      default ''
     * @return  Object  Result of member pin transfer list
     */
    function get_all_pin_transfer($limit=0, $offset=0, $conditions='', $order_by='', $total_condition = '', $params = ''){
        if( !empty($conditions) ){
            $conditions = str_replace("%username_sender%",      "M1.username", $conditions);
            $conditions = str_replace("%name_sender%",          "M1.name", $conditions);
            $conditions = str_replace("%status_sender%",        "M1.as_stockist", $conditions);
            $conditions = str_replace("%username%",             "M2.username", $conditions);
            $conditions = str_replace("%name%",                 "M2.name", $conditions);
            $conditions = str_replace("%status%",               "M2.as_stockist", $conditions);
            $conditions = str_replace("%id_sender%",            "T.id_member_sender", $conditions);
            $conditions = str_replace("%id_member%",            "T.id_member", $conditions);
            $conditions = str_replace("%product%",              "P.name", $conditions);
            $conditions = str_replace("%datecreated%",          "T.datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username_sender%",      "M1.username",  $order_by);
            $order_by   = str_replace("%name_sender%",          "M1.name",  $order_by);
            $order_by   = str_replace("%status_sender%",        "M1.as_stockist", $order_by);
            $order_by   = str_replace("%username%",             "M2.username",  $order_by);
            $order_by   = str_replace("%name%",                 "M2.name",  $order_by);
            $order_by   = str_replace("%status%",               "M2.as_stockist", $order_by);
            $order_by   = str_replace("%id_sender%",            "T.id_member_sender",  $order_by);
            $order_by   = str_replace("%id_member%",            "T.id_member",  $order_by);
            $order_by   = str_replace("%product%",              "P.name",  $order_by);
            $order_by   = str_replace("%qty%",                  "qty",  $order_by);
            $order_by   = str_replace("%datecreated%",          "T.datecreated",  $order_by);
        }

        $total_qty      = ' IFNULL( COUNT(T.id), 0 ) ';

        if ( $total_condition ) {
            $total_condition = str_replace("%qty%",             $total_qty, $total_condition);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    T.id_member_sender, T.id_member, T.product, T.datecreated, 
                    M1.username AS username_sender, M1.name AS name_sender, M1.as_stockist AS status_sender, 
                    M2.username, M2.name, M2.as_stockist, 
                    P.name AS product_name, 
                    '.$total_qty.' AS qty
                FROM ' . $this->pin_transfer . ' T 
                INNER JOIN ' . $this->member . ' M1 ON (M1.id = T.id_member_sender)
                INNER JOIN ' . $this->member . ' M2 ON (M2.id = T.id_member)
                INNER JOIN ' . $this->product . ' P ON (P.id = T.product) 
                WHERE T.id > 0 ';

        if( !empty($conditions) ){ $sql .= $conditions; }

        $sql   .= ' GROUP BY T.id_member, T.datecreated, T.product';

        if ( $total_condition ) {
            $sql .= ' HAVING ' . ltrim( $total_condition, ' AND' );
        }

        $sql   .= ' ORDER BY ' . ( !empty($order_by) ? $order_by : 'T.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all omzet daily data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_order_daily($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%date_omzet%",           "date_omzet", $conditions);
        }

        $subtotal_omzet = 'IFNULL( SUM(A.subtotal_generate), 0 ) + IFNULL( SUM(A.subtotal_order), 0 )';
        $payment_omzet  = 'IFNULL( SUM(A.payment_generate), 0 ) + IFNULL( SUM(A.payment_order), 0 )';
        $total_omzet    = 'IFNULL( SUM(A.omzet_generate), 0 ) + IFNULL( SUM(A.omzet_order), 0 )';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",       "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%subtotal_generate%",  "SUM(A.subtotal_generate)", $total_conditions);
            $total_conditions = str_replace("%subtotal_order%",     "SUM(A.subtotal_order)", $total_conditions);
            $total_conditions = str_replace("%payment_generate%",   "SUM(A.payment_generate)", $total_conditions);
            $total_conditions = str_replace("%payment_order%",      "SUM(A.payment_order)", $total_conditions);
            $total_conditions = str_replace("%omzet_generate%",     "SUM(A.omzet_generate)", $total_conditions);
            $total_conditions = str_replace("%omzet_order%",        "SUM(A.omzet_order)", $total_conditions);
            $total_conditions = str_replace("%subtotal_omzet%",     $subtotal_omzet, $total_conditions);
            $total_conditions = str_replace("%payment_omzet%",      $payment_omzet, $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        $total_omzet, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%date_omzet%",           "date_omzet", $order_by);
            $order_by   = str_replace("%subtotal_generate%",    "subtotal_generate", $order_by);
            $order_by   = str_replace("%payment_generate%",     "payment_generate", $order_by);
            $order_by   = str_replace("%subtotal_order%",       "subtotal_order", $order_by);
            $order_by   = str_replace("%payment_order%",        "payment_order", $order_by);
            $order_by   = str_replace("%omzet_generate%",       "omzet_generate", $order_by);
            $order_by   = str_replace("%omzet_order%",          "omzet_order", $order_by);
            $order_by   = str_replace("%subtotal_omzet%",       "subtotal_omzet", $order_by);
            $order_by   = str_replace("%payment_omzet%",        "total_payment_omzet", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.date_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.subtotal_generate), 0) AS subtotal_generate,
                        IFNULL(SUM(A.subtotal_order), 0) AS subtotal_order,
                        IFNULL(SUM(A.payment_generate), 0) AS payment_generate,
                        IFNULL(SUM(A.payment_order), 0) AS payment_order,
                        IFNULL(SUM(A.omzet_generate), 0) AS omzet_generate,
                        IFNULL(SUM(A.omzet_order), 0) AS omzet_order,
                        ' . $subtotal_omzet . ' AS subtotal_omzet,
                        ' . $payment_omzet . ' AS total_payment_omzet,
                        ' . $total_omzet . ' AS total_omzet
                    FROM (
                        SELECT 
                            DATE_FORMAT(PG.dateconfirmed, "%Y-%m-%d") AS date_omzet,
                            PG.subtotal AS subtotal_generate,
                            PG.total_payment AS payment_generate,
                            (PG.subtotal - PG.discount) AS omzet_generate,
                            0 AS subtotal_order,
                            0 AS payment_order,
                            0 AS omzet_order
                        FROM `' . $this->shop_order . '` PG
                        WHERE PG.type_order = "generate_order" AND PG.`id_stockist` = 0 AND PG.`status` IN (1,2)
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(PO.dateconfirmed, "%Y-%m-%d") AS date_omzet,
                            0 AS subtotal_generate,
                            0 AS payment_generate,
                            0 AS omzet_generate,
                            PO.subtotal AS subtotal_order,
                            PO.total_payment AS payment_order,
                            (PO.subtotal - PO.discount) AS omzet_order
                        FROM `' . $this->shop_order . '` PO
                        WHERE PO.type_order = "stockist_order" AND PO.`id_stockist` = 0 AND PO.`status` IN (1,2)
                    ) AS A ';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' GROUP BY 1 ';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'date_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all omzet monthly data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_order_monthly($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%month_omzet%",          "month_omzet", $conditions);
        }

        $subtotal_omzet = 'IFNULL( SUM(A.subtotal_generate), 0 ) + IFNULL( SUM(A.subtotal_order), 0 )';
        $payment_omzet  = 'IFNULL( SUM(A.payment_generate), 0 ) + IFNULL( SUM(A.payment_order), 0 )';
        $total_omzet    = 'IFNULL( SUM(A.omzet_generate), 0 ) + IFNULL( SUM(A.omzet_order), 0 )';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",       "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%subtotal_generate%",  "SUM(A.subtotal_generate)", $total_conditions);
            $total_conditions = str_replace("%payment_generate%",   "SUM(A.payment_generate)", $total_conditions);
            $total_conditions = str_replace("%subtotal_order%",     "SUM(A.subtotal_order)", $total_conditions);
            $total_conditions = str_replace("%payment_order%",      "SUM(A.payment_order)", $total_conditions);
            $total_conditions = str_replace("%omzet_generate%",     "SUM(A.omzet_generate)", $total_conditions);
            $total_conditions = str_replace("%omzet_order%",        "SUM(A.omzet_order)", $total_conditions);
            $total_conditions = str_replace("%subtotal_omzet%",     $subtotal_omzet, $total_conditions);
            $total_conditions = str_replace("%payment_omzet%",      $payment_omzet, $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        $total_omzet, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%month_omzet%",          "month_omzet", $order_by);
            $order_by   = str_replace("%subtotal_generate%",    "subtotal_generate", $order_by);
            $order_by   = str_replace("%subtotal_order%",       "subtotal_order", $order_by);
            $order_by   = str_replace("%payment_generate%",     "payment_generate", $order_by);
            $order_by   = str_replace("%payment_order%",        "payment_order", $order_by);
            $order_by   = str_replace("%omzet_generate%",       "omzet_generate", $order_by);
            $order_by   = str_replace("%omzet_order%",          "omzet_order", $order_by);
            $order_by   = str_replace("%subtotal_omzet%",       "subtotal_omzet", $order_by);
            $order_by   = str_replace("%payment_omzet%",        "total_payment_omzet", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.subtotal_generate), 0) AS subtotal_generate,
                        IFNULL(SUM(A.subtotal_order), 0) AS subtotal_order,
                        IFNULL(SUM(A.payment_generate), 0) AS payment_generate,
                        IFNULL(SUM(A.omzet_generate), 0) AS omzet_generate,
                        IFNULL(SUM(A.omzet_order), 0) AS omzet_order,
                        ' . $subtotal_omzet . ' AS subtotal_omzet,
                        ' . $payment_omzet . ' AS total_payment_omzet,
                        ' . $total_omzet . ' AS total_omzet
                    FROM (
                        SELECT 
                            DATE_FORMAT(PG.dateconfirmed, "%Y-%m") AS month_omzet,
                            PG.subtotal AS subtotal_generate,
                            PG.total_payment AS payment_generate,
                            (PG.subtotal - PG.discount) AS omzet_generate,
                            0 AS subtotal_order,
                            0 AS payment_order,
                            0 AS omzet_order
                        FROM `' . $this->shop_order . '` PG
                        WHERE PG.type_order = "generate_order" AND PG.`id_stockist` = 0 AND PG.`status` IN (1,2)
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(PO.dateconfirmed, "%Y-%m") AS month_omzet,
                            0 AS subtotal_generate,
                            0 AS payment_generate,
                            0 AS omzet_generate,
                            PO.subtotal AS subtotal_order,
                            PO.total_payment AS payment_order,
                            (PO.subtotal - PO.discount) AS omzet_order
                        FROM `' . $this->shop_order . '` PO
                        WHERE PO.type_order = "stockist_order" AND PO.`id_stockist` = 0 AND PO.`status` IN (1,2)
                    ) AS A ';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' GROUP BY 1 ';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'month_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Get PINs with row lock
     *
     * Please only use this if you intend to update the rows inside a transaction
     *
     * @since 1.0.0
     * @access public
     *
     * @author ahmad
     */
    function get_pins_with_lock( $id_member, $product, $status = 1, $limit = 0 ) {
        if ( empty( $id_member ) )
            return false;

        $sql = 'SELECT * FROM ' . $this->pin . ' WHERE id_member = ? AND product = ? AND status = ?';
        if ( $limit )
            $sql .= ' LIMIT ' . $limit;

        $sql .= ' FOR UPDATE';
        $qry = $this->db->query( $sql, array( $id_member, $product, $status ) );
        if ( ! $qry || ! $qry->num_rows() )
            return false;

        return $qry->result();
    }

    /**
     * Retrieve Total Shop Order
     *
     * @author  Yuda
     * @param   String  $conditions         Condition of query          default ''
     * @return  Object  Result of data total
     */
    function get_total_shop_order($conditions='') {
        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(total_bv), 0 ) AS total_bv,
                        IFNULL( SUM(total_qty), 0 ) AS total_qty,
                        IFNULL( SUM(subtotal), 0 ) AS subtotal,
                        IFNULL( SUM(shipping), 0 ) AS total_shipping,
                        IFNULL( SUM(`unique`), 0 ) AS total_unique,
                        IFNULL( SUM(discount), 0 ) AS total_discount,
                        IFNULL( SUM(total_payment), 0 ) AS total_payment,
                        ( IFNULL( SUM(subtotal), 0 ) - IFNULL( SUM(discount), 0 ) ) AS total_omzet
                    FROM `'. $this->shop_order .'` WHERE id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }
        
        $query  = $this->db->query($sql);

        if ( !$query || !$query->num_rows() )
            return false;

        return $query->row();
    }

    /*
	|--------------------------------------------------------------------------
    | Fetch Product To show in select2
	|--------------------------------------------------------------------------
    */
    function fetchProduct($searchTerm = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("name like '%" . $searchTerm . "%' ");
        $this->db->where("status", 1);
        $fetched_records = $this->db->get(TBL_PRODUCT);
        $fetch = $fetched_records->result_array();
        // Initialize Array with fetched data
        $data = array();
        foreach ($fetch as $row) {
            $data[] = array("id" => $row['id'], "text" => $row['name']);
        }
        return $data;
    }
    
    /**
     * Save data of shop order
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop orders
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order detail
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop order details
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order_detail($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order_detail, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of payment evidence
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_payment_evidence($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->payment_evidence, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of customer
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order customer
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order_customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order detail
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop order details
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_detail_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_detail_customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of pin
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of pins
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_pin($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->pin, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of pin transfer
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of transfer pins
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_pin_transfer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->pin_transfer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update shop order
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_shop_order($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->shop_order, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->shop_order, $data) )
            return true;

        return false;
    }

    /**
     * Update pin
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  Pin ID
     * @param   Array   $data   (Required)  Data Pin ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_pin($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->primary, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->pin, $data) )
            return true;

        return false;
    }

    /**
     * Update customer
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_customer($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->customer, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->customer, $data) )
            return true;

        return false;
    }

    /**
     * Update shop order customer
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_shop_order_customer($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->shop_order_customer, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->shop_order_customer, $data) )
            return true;

        return false;
    }

    // END OF FILE #################################################################################
}
