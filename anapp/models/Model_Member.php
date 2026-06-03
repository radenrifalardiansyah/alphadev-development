<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('AN_Model.php');

class Model_Member extends AN_Model
{
    /**
     * For AN_Model
     */
    public $_table          = 'member';

    /**
     * Initialize table
     */
    var $member             = TBL_PREFIX . "member";
    var $member_confirm     = TBL_PREFIX . "member_confirm";
    var $member_board       = TBL_PREFIX . "member_board";
    var $member_omzet       = TBL_PREFIX . "member_omzet";
    var $pairing_qualified  = TBL_PREFIX . "pairing_qualified";
    var $bank               = TBL_PREFIX . "banks";
    var $bonus              = TBL_PREFIX . "bonus";
    var $board_tree         = TBL_PREFIX . "board_tree";
    var $package            = TBL_PREFIX . "package";
    var $ro                 = TBL_PREFIX . "ro";
    var $shop_order         = TBL_PREFIX . "shop_order";
    var $shop_order_detail  = TBL_PREFIX . "shop_order_detail";
    var $reward             = TBL_PREFIX . "reward";
    var $loan               = TBL_PREFIX . "loan";
    var $upgrade            = TBL_PREFIX . "upgrade";
    var $province           = TBL_PREFIX . "province";
    var $district           = TBL_PREFIX . "district";
    var $subdistrict        = TBL_PREFIX . "subdistrict";

    /**
     * Initialize primary field
     */
    var $primary            = "id";
    var $parent             = "parent";

    /**
     * Constructor - Sets up the object properties.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ---------------------------------------------------------------------------------
    // CRUD (Manipulation) data member
    // ---------------------------------------------------------------------------------

    /**
     * Retrieve all member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default array()
     * @param   String  $order_by           Column that make to order   default array()
     * @return  Object  Result of member list
     */
    function get_data($limit = 0, $offset = 0, $conditions = array(), $order_by = array())
    {
        $this->limit($limit, $offset);

        if ($order_by) {
            foreach ($order_by as $criteria => $order)
                $this->order_by($criteria, $order);
        }

        if ($conditions) {
            return $this->get_many_by($conditions);
        }

        return $this->get_all();
    }

    /**
     * Get member data by member ID
     *
     * @author  Yuda
     * @param   Integer $member_id  (Required)  Member ID
     * @return  Mixed   False on failed process, otherwise object of member.
     */
    function get_memberdata($member_id)
    {
        if (!is_numeric($member_id)) return false;

        $member_id = absint($member_id);
        if (!$member_id) return false;

        $query = $this->db->get_where($this->member, array($this->primary => $member_id));
        if (!$query->num_rows())
            return false;

        foreach ($query->result() as $row) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Get member confirm
     *
     * @author  Yuda
     * @param   Int     $id  (Required)  Member Confirm ID
     * @return  Mixed   False on invalid onfirm id, otherwise array of member confirm.
     */
    function get_member_confirm($id)
    {
        if (!is_numeric($id)) return false;

        $id  = absint($id);
        if (!$id) return false;

        $data       = array($this->primary => $id);
        $this->db->where($data);

        $query      = $this->db->get($this->member_confirm);

        if (!$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Get member confirm by id downline
     *
     * @author  Yuda
     * @param   Int     $id  (Required)  Member Confirm ID
     * @return  Mixed   False on invalid onfirm id, otherwise array of member confirm.
     */
    function get_member_confirm_by_downline($id_downline)
    {
        if (!is_numeric($id_downline)) return false;

        $id_downline  = absint($id_downline);
        if (!$id_downline) return false;

        $data       = array('id_downline' => $id_downline);
        $this->db->where($data);

        $query      = $this->db->get($this->member_confirm);

        if (!$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Get member data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member
     */
    function get_member_by($field, $value = '', $conditions = '')
    {
        $id = '';

        switch ($field) {
            case 'id':
                $id     = $value;
                break;
            case 'email':
                $value  = sanitize_email($value);
                $id     = '';
                $field  = 'email';
                break;
            case 'phone':
                $value  = $value;
                $id     = '';
                $field  = 'phone';
                break;
            case 'idcard':
                $value  = $value;
                $id     = '';
                $field  = 'idcard';
                break;
            case 'bill':
                $value  = $value;
                $id     = '';
                $field  = 'bill';
                break;
            case 'login':
                $value  = $value;
                $id     = '';
                $field  = 'login';
                break;
            default:
                return false;
        }

        if ($id != '' && $id > 0)
            return $this->get_memberdata($id);

        if (empty($field)) return false;

        $db     = $this->db;

        if ($field == 'login') {
            $db->where('username', $value);
        } else {
            $db->where($field, $value);
        }

        if ($conditions) {
            $db->where($conditions);
        }

        $query  = $db->get($this->member);

        if (!$query->num_rows())
            return false;

        foreach ($query->result() as $row) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Get Member Board data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of session
     */
    function get_member_board_by($field, $value='', $conditions='', $limit = 0, $count = false)
    {
        if ( !$field || !$value ) return false;

        $db     = $this->db;

        $db->where($field, $value);

        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        if ( $limit == 1 ) {
            $this->db->order_by("datecreated", "ASC");
        } else {
            $this->db->order_by("datecreated", "DESC");
        }

        $query  = $db->get($this->member_board);

        if ( !$query->num_rows() )
            return false;

        if ( $count ) {
            return $query->num_rows();
        }

        foreach ( $query->result() as $row ) {
            $session = $row;
        }
        return $session;
    }

    /**
     * Get Board Tree data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of session
     */
    function get_board_tree_by($field, $value='', $conditions='', $limit = 0, $count = false)
    {
        if ( !$field || !$value ) return false;

        $db     = $this->db;

        $db->where($field, $value);

        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("position", "ASC");
        $query  = $db->get($this->board_tree);

        if ( !$query->num_rows() )
            return false;

        if ( $count ) {
            return $query->num_rows();
        }

        foreach ( $query->result() as $row ) {
            $session = $row;
        }
        return $session;
    }

    /**
     * Get member omzet by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_member_omzet_by($field = '', $value = '', $conditions = '', $limit = 0)
    {
        if (!$field || !$value) return false;

        $this->db->where($field, $value);
        if ($conditions) {
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "DESC");
        $query  = $this->db->get($this->member_omzet);
        if (!$query->num_rows()) {
            return false;
        }

        $data   = $query->result();
        if ($field == 'id' || $limit == 1) {
            foreach ($data as $row) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get Reward by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member reward
     */
    function get_member_reward_by($field = '', $value = '', $condition = array())
    {
        if (!$field || !$value) return false;
        switch ($field) {
            case 'id':
                $field  = 'id';
                $id     = $value;
                break;
            case 'id_member':
                $field  = 'id_member';
                $value  = $value;
                break;
            case 'id_reward':
                $field  = 'id_reward';
                $value  = $value;
                break;
                return false;
        }

        if (empty($field)) return false;

        $data = array($field => $value);
        $this->db->where($data);

        if (!empty($condition)) {
            $this->db->where($condition);
        }

        $query = $this->db->get($this->reward);
        if (!$query->num_rows()) {
            return false;
        }
        return $query->row();
    }

    /**
     * Get is dowline
     *
     * @author  Yuda
     * @param   Int     $id_member  (Required)  ID Member
     * @param   String  $up_tree    (Required)  Tree of upline
     * @return  Boolean false if invalid data, otherwise true if is downline.
     */
    function get_is_downline($id_member, $up_tree)
    {
        if (!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        if (empty($up_tree) || !$up_tree) return false;

        $this->db->where('id', $id_member);
        $this->db->like('tree', $up_tree, 'after');
        $query  = $this->db->get($this->member);

        if ($query->num_rows() > 0)
            return true;

        return false;
    }

    /**
     * Get ancestry of member
     * @author  Yuda
     */
    function get_ancestry($id_member)
    {
        $id_member = absint($id_member);
        if (!$id_member) return false;

        $sql = 'SELECT GetAncestry(id) AS ancestry FROM ' . $this->member . ' WHERE id=?';
        $qry = $this->db->query($sql, array($id_member));

        if (!$qry || !$qry->num_rows()) return false;
        return $qry->row()->ancestry;
    }

    /**
     * Get ancestry sponsor of member
     * @author  Yuda
     */
    function get_ancestry_sponsor($id_member)
    {
        $id_member = absint($id_member);
        if (!$id_member) return false;

        $sql = 'SELECT GetAncestrySponsor(id) AS ancestry FROM ' . $this->member . ' WHERE id=?';
        $qry = $this->db->query($sql, array($id_member));

        if (!$qry || !$qry->num_rows()) return false;
        return $qry->row()->ancestry;
    }

    /**
     * Get Position member of sponsor
     * @author  Yuda
     */
    function get_position_sponsor($id_sponsor)
    {
        $id_sponsor = absint($id_sponsor);
        if (!$id_sponsor) return 0;

        $position = 1;

        $sql = 'SELECT position FROM ' . $this->member . ' WHERE sponsor = ? ORDER BY position DESC';
        $qry = $this->db->query($sql, array($id_sponsor));

        if ($qry && $qry->num_rows()) {
            $position = $qry->row()->position + 1;
        }
        return $position;
    }
    
    /**
     * Get Position member of upline
     * @author  Yuda
     */
    function get_position_upline($id_upline)
    {
        $id_upline = absint($id_upline);
        if (!$id_upline) return 0;

        $position = 1;

        $sql = 'SELECT position FROM ' . $this->member . ' WHERE parent = ? ORDER BY position DESC';
        $qry = $this->db->query($sql, array($id_upline));

        if ($qry && $qry->num_rows()) {
            $position = $qry->row()->position + 1;
        }
        return $position;
    }

    /**
     * Get node available
     *
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @param   Boolean $count (Optional)  Count of node
     * @param   String $position (Optional)  Position of Node
     * @return  Mixed   False on invalid member id, otherwise array of node available.
     */
    function get_node_available($id_member, $count = false, $position = '')
    {
        if(!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if(!$id_member) return false;

        $memberdata = $this->get_memberdata($id_member);
        if(!$memberdata) return false;

        $arr = array($this->parent => $id_member);
        if(!empty($position)) $arr = array($this->parent => $id_member, 'position' => $position);

        $query = $this->db->get_where($this->member, $arr);

        if($count) return $query->num_rows();

        return $query->result();
    }

    /**
     * Get downline data or count downline (child level 1)
     *
     * @author  Yuda
     * @param   Int     $id (Required)      Member ID
     * @param   String  $group (Optional)   Group of downline, default ''
     * @param   String  $status (Optional)  Status of Downline, value ('active' or 'pending')
     * @param   Boolean $count (Optional)   Get Count of downline
     * @return  Mixed   False on invalid member id, otherwise array of downline.
     */
    function get_downline($id_member, $position = '', $status = '', $count = false)
    {
        if (!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if (!$id_member) return false;

        $this->db->where("parent", $id_member);
        if (!empty($status)) $this->db->where("status", ($status == 'active' ? 1 : 0));

        if (!empty($position)) $this->db->where("position", $position);
        $this->db->order_by("position", "ASC");

        $query = $this->db->get($this->member);

        if ($count) return $query->num_rows();
        if (!empty($position)) return $query->row();

        return $query->result();
    }

    /**
     * Get downline data (child level 1 < 2 member)
     *
     * @author  Yuda
     * @param   Int $id (Required)  Member ID
     * @return  Mixed   False on invalid member id, otherwise array of downline.
     */
    function get_upline_available_position($id_member = '', $firstrow = true, $date = '', $level_qualified = '', $level_equal = false)
    {
        $conditions = '';
        $sel_level  = ' M.level';
        if ( !empty($id_member) ) {
            if(!is_numeric($id_member)) return false;

            $id_member  = absint($id_member);
            if(!$id_member) return false;

            $member     = $this->get_memberdata($id_member);
            if(!$member) return false;

            $conditions = ' AND M.tree LIKE "' . $member->tree . '%" ';
            if ( ! $firstrow ) {
                $conditions .= ' AND M.level > ' . $member->level .' ';
                if ( $level_qualified ) {
                    if ( $level_equal ) {
                        $conditions .= ' AND M.level = ' . ( $member->level + $level_qualified ) .' ';
                    } else {
                        $conditions .= ' AND M.level <= ' . ( $member->level + $level_qualified ) .' ';
                    }
                }
            }
            $sel_level  = ' ( M.level - ' . $member->level . ' )';
        }

        if ( $date ) {
            $conditions .= ' AND DATE(M.datecreated) <= "' . $date .'" ';
        }

        $sql = 'SELECT 
                    M.id, 
                    M.username,
                    M.name,
                    M.parent,
                    M.tree,
                    M.level,
                    '.$sel_level.' AS level_downline,
                    COUNT(C.downline) AS total_downline,
                    GROUP_CONCAT(C.position) AS position
                FROM `' . $this->member . '` M
                LEFT JOIN (
                    SELECT A.id AS downline, A.username AS username_downline, A.parent, A.position, A.tree FROM ' . $this->member . ' A
                ) AS C ON (C.parent = M.id)
                WHERE M.status = ' . ACTIVE . '
                ' . $conditions . '
                GROUP BY M.id ';

        if ( $firstrow ) { 
            $sql .= ' HAVING COUNT(C.downline) < 2 
                        ORDER BY M.level ASC, total_downline ASC LIMIT 1 '; 
        } else {
            $sql .= ' HAVING COUNT(C.downline) <= 2 ORDER BY M.level ASC, total_downline ASC  '; 
        }

        $qry = $this->db->query($sql);

        if( !$qry || !$qry->num_rows() ) {
            return false;
        }

        if ( $firstrow ) { 
            return $qry->row();
        } else {
            return $qry->result();
        }
    }

    /**
     * Retrieve all member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_member_data($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "A.id", $conditions);
            $conditions = str_replace("%type%",                 "A.type", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%username%",             "A.username", $conditions);
            $conditions = str_replace("%name%",                 "A.name", $conditions);
            $conditions = str_replace("%package%",              "A.package", $conditions);
            $conditions = str_replace("%position%",             "A.position", $conditions);
            $conditions = str_replace("%email%",                "A.email", $conditions);
            $conditions = str_replace("%phone%",                "A.phone", $conditions);
            $conditions = str_replace("%parent%",               "A.parent", $conditions);
            $conditions = str_replace("%sponsor%",              "A.sponsor", $conditions);
            $conditions = str_replace("%as_stockist%",          "A.as_stockist", $conditions);
            $conditions = str_replace("%province%",             "A.province", $conditions);
            $conditions = str_replace("%city%",                 "A.city", $conditions);
            $conditions = str_replace("%gen%",                  "A.gen", $conditions);
            $conditions = str_replace("%level%",                "A.level", $conditions);
            $conditions = str_replace("%tree%",                 "A.tree", $conditions);
            $conditions = str_replace("%sponsor_username%",     "B.username", $conditions);
            $conditions = str_replace("%upline_username%",      "C.username", $conditions);
            $conditions = str_replace("%lastlogin%",            "A.last_login", $conditions);
            $conditions = str_replace("%datecreated%",          "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "A.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "A.id",  $order_by);
            $order_by   = str_replace("%type%",                 "A.type", $order_by);
            $order_by   = str_replace("%status%",               "A.status", $order_by);
            $order_by   = str_replace("%username%",             "A.username", $order_by);
            $order_by   = str_replace("%name%",                 "A.name", $order_by);
            $order_by   = str_replace("%package%",              "A.package", $order_by);
            $order_by   = str_replace("%position%",             "A.position", $order_by);
            $order_by   = str_replace("%email%",                "A.email", $order_by);
            $order_by   = str_replace("%phone%",                "A.phone", $order_by);
            $order_by   = str_replace("%parent%",               "A.parent", $order_by);
            $order_by   = str_replace("%sponsor%",              "A.sponsor", $order_by);
            $order_by   = str_replace("%as_stockist%",          "A.as_stockist", $order_by);
            $order_by   = str_replace("%province%",             "A.province", $order_by);
            $order_by   = str_replace("%city%",                 "A.city", $order_by);
            $order_by   = str_replace("%gen%",                  "A.gen", $order_by);
            $order_by   = str_replace("%level%",                "A.level", $order_by);
            $order_by   = str_replace("%tree%",                 "A.tree", $order_by);
            $order_by   = str_replace("%sponsor_username%",     "B.username", $order_by);
            $order_by   = str_replace("%upline_username%",      "C.username", $order_by);
            $order_by   = str_replace("%lastlogin%",            "A.last_login", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "A.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.* ,
                    B.name AS sponsor_name,
                    C.name AS upline_name,
                    B.username AS sponsor_username,
                    C.username AS upline_username
                FROM ' . $this->member . ' AS A 
                LEFT JOIN ' . $this->member . ' AS B ON (B.id = A.sponsor)
                LEFT JOIN ' . $this->member . ' AS C ON (C.id = A.parent) ';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if (!$query || !$query->num_rows()) return false;

        if ($num_rows){
            return $query->num_rows();
        }

        return $query->result();
    }

    /**
     * Retrieve all member confirm data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member confirm list
     */
    function get_all_member_confirm($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%id_sponsor%",       "A.id_sponsor", $conditions);
            $conditions = str_replace("%id_downline%",      "A.id_downline", $conditions);
            $conditions = str_replace("%member%",           "A.member", $conditions);
            $conditions = str_replace("%sponsor%",          "A.sponsor", $conditions);
            $conditions = str_replace("%downline%",         "A.downline", $conditions);
            $conditions = str_replace("%name%",             "B.name", $conditions);
            $conditions = str_replace("%wa%",               "B.phone", $conditions);
            $conditions = str_replace("%email%",            "B.email", $conditions);
            $conditions = str_replace("%package%",          "A.package", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%status_member%",    "B.status", $conditions);
            $conditions = str_replace("%type%",             "B.type", $conditions);
            $conditions = str_replace("%access%",           "A.access", $conditions);
            $conditions = str_replace("%province%",         "B.province", $conditions);
            $conditions = str_replace("%city%",             "B.city", $conditions);
            $conditions = str_replace("%omzet%",            "A.omzet", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%dateconfirm%",      "DATE(A.datemodified)", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "A.id",  $order_by);
            $order_by   = str_replace("%member%",           "A.member",  $order_by);
            $order_by   = str_replace("%sponsor%",          "A.sponsor",  $order_by);
            $order_by   = str_replace("%downline%",         "A.downline",  $order_by);
            $order_by   = str_replace("%name%",             "B.name",  $order_by);
            $order_by   = str_replace("%wa%",               "B.phone", $order_by);
            $order_by   = str_replace("%email%",            "B.email", $order_by);
            $order_by   = str_replace("%package%",          "A.package",  $order_by);
            $order_by   = str_replace("%status%",           "A.status",  $order_by);
            $order_by   = str_replace("%access%",           "A.access", $order_by);
            $order_by   = str_replace("%province%",         "B.province", $order_by);
            $order_by   = str_replace("%city%",             "B.city", $order_by);
            $order_by   = str_replace("%omzet%",            "A.omzet", $order_by);
            $order_by   = str_replace("%nominal%",          "A.nominal", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%dateconfirm%",      "A.datemodified",  $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS
                        A.*,
                        B.name,
                        B.phone,
                        B.email,
                        B.address,
                        B.province,
                        B.district
                    FROM ' . $this->member_confirm . ' AS A
                    LEFT JOIN ' . $this->member . ' AS B ON B.id = A.id_downline ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

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
     * Retrieve all Total Sponsored data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_total_sponsored_data($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "A.id", $conditions);
            $conditions = str_replace("%username%",             "A.username", $conditions);
            $conditions = str_replace("%name%",                 "A.name", $conditions);
            $conditions = str_replace("%package%",              "A.package", $conditions);
            $conditions = str_replace("%email%",                "A.email", $conditions);
            $conditions = str_replace("%phone%",                "A.phone", $conditions);
            $conditions = str_replace("%as_stockist%",          "A.as_stockist", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%total%",                "B.total", $conditions);
            $conditions = str_replace("%datecreated%",          "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "A.id",  $order_by);
            $order_by   = str_replace("%username%",             "A.username", $order_by);
            $order_by   = str_replace("%name%",                 "A.name", $order_by);
            $order_by   = str_replace("%package%",              "A.package", $order_by);
            $order_by   = str_replace("%email%",                "A.email", $order_by);
            $order_by   = str_replace("%phone%",                "A.phone", $order_by);
            $order_by   = str_replace("%as_stockist%",          "A.as_stockist", $order_by);
            $order_by   = str_replace("%status%",               "A.status", $order_by);
            $order_by   = str_replace("%total%",                "B.total", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS  A.* ,  B.total
                FROM ' . $this->member . ' AS A 
                LEFT JOIN ( 
                    SELECT S.sponsor, COUNT(S.id) AS total FROM ' . $this->member . ' S  WHERE S.total_omzet > 0 GROUP BY S.sponsor
                ) AS B ON (B.sponsor = A.id) 
                WHERE A.type = '. MEMBER.' AND A.type_status LIKE "'.TYPE_STATUS_RESELLER.'" ';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'B.total DESC');

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
     * Retrieve all member ro data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member ro list
     */
    function get_all_member_ro($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%id_order%",         "A.id_order", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%name%",             "B.name", $conditions);
            $conditions = str_replace("%invoice%",          "C.invoice", $conditions);
            $conditions = str_replace("%omzet%",            "A.omzet", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%dateconfirm%",      "A.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "A.id",  $order_by);
            $order_by   = str_replace("%id_member%",        "A.id_member", $order_by);
            $order_by   = str_replace("%id_order%",         "A.id_order", $order_by);
            $order_by   = str_replace("%username%",         "B.username", $order_by);
            $order_by   = str_replace("%name%",             "B.name", $order_by);
            $order_by   = str_replace("%invoice%",          "C.invoice", $order_by);
            $order_by   = str_replace("%omzet%",            "A.omzet", $order_by);
            $order_by   = str_replace("%amount%",           "A.amount", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
            $order_by   = str_replace("%dateconfirm%",      "A.datemodified", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS
                        A.*,
                        B.username,
                        B.name,
                        C.invoice
                    FROM ' . $this->ro . ' AS A
                    INNER JOIN ' . $this->member . ' AS B ON (B.id = A.id_member) 
                    INNER JOIN ' . $this->shop_order . ' AS C ON (C.id = A.id_order)
                    WHERE A.id > 0';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

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
     * Retrieve all member omzet data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member omzet list
     */
    function get_all_member_omzet($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "B.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%package%",          "B.package", $conditions);
            $conditions = str_replace("%date%",             "A.date", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "B.id", $order_by);
            $order_by   = str_replace("%id_member%",        "A.id_member", $order_by);
            $order_by   = str_replace("%status%",           "A.status", $order_by);
            $order_by   = str_replace("%username%",         "B.username", $order_by);
            $order_by   = str_replace("%package%",          "B.package", $order_by);
            $order_by   = str_replace("%date%",             "A.date", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS A.*, B.username, B.name, B.package, B.sponsor, B.tree
                    FROM ' . $this->member_omzet . ' AS A
                    JOIN ' . $this->member . ' AS B ON B.id = A.id_member
                    WHERE B.status = ' . ACTIVE . ' AND B.type = ' . MEMBER;

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.date DESC, A.datecreated ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member address data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_member_address($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "M.id", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%email%",                "M.email", $conditions);
            $conditions = str_replace("%phone%",                "M.phone", $conditions);
            $conditions = str_replace("%type%",                 "M.type", $conditions);
            $conditions = str_replace("%status%",               "M.status", $conditions);
            $conditions = str_replace("%stockist%",             "M.as_stockist", $conditions);
            $conditions = str_replace("%province_id%",          "P.id", $conditions);
            $conditions = str_replace("%district_id%",          "D.id", $conditions);
            $conditions = str_replace("%subdistrict_id%",       "S.id", $conditions);
            $conditions = str_replace("%province%",             "P.province_name", $conditions);
            $conditions = str_replace("%district%",             "D.district_name", $conditions);
            $conditions = str_replace("%subdistrict%",          "S.subdistrict_name", $conditions);
            $conditions = str_replace("%datecreated%",          "M.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "M.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "M.id",  $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%email%",                "M.email", $order_by);
            $order_by   = str_replace("%phone%",                "M.phone", $order_by);
            $order_by   = str_replace("%type%",                 "M.type", $order_by);
            $order_by   = str_replace("%status%",               "M.status", $order_by);
            $order_by   = str_replace("%stockist%",             "M.as_stockist", $order_by);
            $order_by   = str_replace("%province%",             "P.province_name", $order_by);
            $order_by   = str_replace("%district%",             "D.district_name", $order_by);
            $order_by   = str_replace("%subdistrict%",          "S.subdistrict_name", $order_by);
            $order_by   = str_replace("%datecreated%",          "M.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "M.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    M.id, M.username, M.name, M.phone, M.email, M.as_stockist, M.village, M.address,
                    P.id AS province_id, P.province_name, 
                    D.id AS district_id, D.district_name, D.district_type, 
                    S.id AS subdistrict_id, S.subdistrict_name
                FROM ' . $this->member . ' AS M 
                INNER JOIN ' . $this->province . ' AS P ON (P.id = M.province) 
                INNER JOIN ' . $this->district . ' AS D ON (D.id = M.district AND D.province_id = P.id) 
                INNER JOIN ' . $this->subdistrict . ' AS S ON (S.id = M.subdistrict AND S.district_id = D.id) 
                WHERE M.id > 1 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }
        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'M.name ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }
        
        if (!$query || !$query->num_rows()) return false;

        if ($num_rows)
            return $query->num_rows();

        return $query->result();
    }

    /**
     * Retrieve all stockist address data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_stockist_address($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "M.id", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%email%",                "M.email", $conditions);
            $conditions = str_replace("%phone%",                "M.phone", $conditions);
            $conditions = str_replace("%type%",                 "M.type", $conditions);
            $conditions = str_replace("%status%",               "M.status", $conditions);
            $conditions = str_replace("%stockist%",             "M.as_stockist", $conditions);
            $conditions = str_replace("%province_id%",          "P.id", $conditions);
            $conditions = str_replace("%district_id%",          "D.id", $conditions);
            $conditions = str_replace("%subdistrict_id%",       "S.id", $conditions);
            $conditions = str_replace("%province%",             "P.province_name", $conditions);
            $conditions = str_replace("%district%",             "D.district_name", $conditions);
            $conditions = str_replace("%subdistrict%",          "S.subdistrict_name", $conditions);
            $conditions = str_replace("%datecreated%",          "M.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "M.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "M.id",  $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%email%",                "M.email", $order_by);
            $order_by   = str_replace("%phone%",                "M.phone", $order_by);
            $order_by   = str_replace("%type%",                 "M.type", $order_by);
            $order_by   = str_replace("%status%",               "M.status", $order_by);
            $order_by   = str_replace("%stockist%",             "M.as_stockist", $order_by);
            $order_by   = str_replace("%province%",             "P.province_name", $order_by);
            $order_by   = str_replace("%district%",             "D.district_name", $order_by);
            $order_by   = str_replace("%subdistrict%",          "S.subdistrict_name", $order_by);
            $order_by   = str_replace("%datecreated%",          "M.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "M.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    M.id, M.username, M.name, M.phone, M.email, M.as_stockist, M.subdistrict_stockist AS subdistrict, M.village_stockist AS village, M.address_stockist AS address,
                    P.id AS province_id, P.province_name, 
                    D.id AS district_id, D.district_name, D.district_type,
                    S.id AS subdistrict_id, S.subdistrict_name
                FROM ' . $this->member . ' AS M 
                INNER JOIN ' . $this->province . ' AS P ON (P.id = M.province_stockist) 
                INNER JOIN ' . $this->district . ' AS D ON (D.id = M.district_stockist AND D.province_id = P.id) 
                INNER JOIN ' . $this->subdistrict . ' AS S ON (S.id = M.subdistrict_stockist AND S.district_id = D.id)
                WHERE M.id > 1 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }
        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'M.name ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;
        
        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }
        
        if (!$query || !$query->num_rows()) return false;

        if ($num_rows)
            return $query->num_rows();

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
    function get_all_omzet_daily($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%date_omzet%",           "date_omzet", $conditions);
        }

        $total_omzet_bv = 'IFNULL( SUM(A.omzet_reg_bv), 0 ) + IFNULL( SUM(A.omzet_ro_bv), 0 )';
        $total_omzet    = 'IFNULL( SUM(A.omzet_register), 0 ) + IFNULL( SUM(A.omzet_ro), 0 )';
        $percent        = 'ROUND( ( IFNULL(SUM(A.bonus), 0) / ( '. $total_omzet .' ) * 100 ), 2 ) ';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",       "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%omzet_register%",     "SUM(A.omzet_register)", $total_conditions);
            $total_conditions = str_replace("%omzet_ro%",           "SUM(A.omzet_ro)", $total_conditions);
            $total_conditions = str_replace("%omzet_bv%",           "SUM(A.omzet_bv)", $total_conditions);
            $total_conditions = str_replace("%total_bonus%",        "SUM(A.bonus)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        $total_omzet, $total_conditions);
            $total_conditions = str_replace("%percent%",            $percent, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%date_omzet%",           "date_omzet", $order_by);
            $order_by   = str_replace("%omzet_register%",       "total_omzet_register", $order_by);
            $order_by   = str_replace("%omzet_ro%",             "total_omzet_ro", $order_by);
            $order_by   = str_replace("%omzet_bv%",             "total_omzet_bv", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_bonus%",          "total_bonus", $order_by);
            $order_by   = str_replace("%percent%",              "percent", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.date_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.omzet_register), 0) AS total_omzet_register,
                        IFNULL(SUM(A.omzet_reg_bv), 0) AS total_omzet_reg_bv,
                        IFNULL(SUM(A.omzet_ro), 0) AS total_omzet_ro,
                        IFNULL(SUM(A.omzet_ro_bv), 0) AS total_omzet_ro_bv,
                        IFNULL(SUM(A.bonus), 0) AS total_bonus,
                        ' . $total_omzet . ' AS total_omzet,
                        ' . $total_omzet_bv . ' AS total_omzet_bv,
                        ' . $percent . ' AS percent
                    FROM (
                        SELECT 
                            DATE_FORMAT(MREG.date, "%Y-%m-%d") AS date_omzet,
                            MREG.amount AS omzet_register,
                            MREG.bv AS omzet_reg_bv,
                            0 AS omzet_ro,
                            0 AS omzet_ro_bv,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MREG
                        WHERE MREG.status = "register" AND MREG.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MO.date, "%Y-%m-%d") AS date_omzet,
                            0 AS omzet_register,
                            0 AS omzet_reg_bv,
                            MO.amount AS omzet_ro,
                            MO.bv AS omzet_ro_bv,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MO
                        WHERE MO.status = "ro"
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(B.datecreated, "%Y-%m-%d") AS date_omzet,
                            0 AS omzet_register,
                            0 AS omzet_reg_bv,
                            0 AS omzet_ro,
                            0 AS omzet_ro_bv,
                            B.amount AS bonus
                        FROM `' . $this->bonus . '` B
                    ) AS A ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

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
    function get_all_omzet_monthly($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%month_omzet%",          "month_omzet", $conditions);
        }

        $total_omzet_bv = 'IFNULL( SUM(A.omzet_reg_bv), 0 ) + IFNULL( SUM(A.omzet_ro_bv), 0 )';
        $total_omzet    = 'IFNULL( SUM(A.omzet_register), 0 ) + IFNULL( SUM(A.omzet_ro), 0 )';
        $percent        = 'ROUND( ( IFNULL(SUM(A.bonus), 0) / ( '. $total_omzet .' ) * 100 ), 2 ) ';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",       "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%omzet_register%",     "SUM(A.omzet_register)", $total_conditions);
            $total_conditions = str_replace("%omzet_ro%",           "SUM(A.omzet_ro)", $total_conditions);
            $total_conditions = str_replace("%omzet_bv%",           "SUM(A.omzet_bv)", $total_conditions);
            $total_conditions = str_replace("%total_bonus%",        "SUM(A.bonus)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        $total_omzet, $total_conditions);
            $total_conditions = str_replace("%percent%",            $percent, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%month_omzet%",          "month_omzet", $order_by);
            $order_by   = str_replace("%omzet_register%",       "total_omzet_register", $order_by);
            $order_by   = str_replace("%omzet_ro%",             "total_omzet_ro", $order_by);
            $order_by   = str_replace("%omzet_bv%",             "total_omzet_bv", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_bonus%",          "total_bonus", $order_by);
            $order_by   = str_replace("%percent%",              "percent", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.omzet_register), 0) AS total_omzet_register,
                        IFNULL(SUM(A.omzet_reg_bv), 0) AS total_omzet_reg_bv,
                        IFNULL(SUM(A.omzet_ro), 0) AS total_omzet_ro,
                        IFNULL(SUM(A.omzet_ro_bv), 0) AS total_omzet_ro_bv,
                        IFNULL(SUM(A.bonus), 0) AS total_bonus,
                        ' . $total_omzet . ' AS total_omzet,
                        ' . $total_omzet_bv . ' AS total_omzet_bv,
                        ' . $percent . ' AS percent
                    FROM (
                        SELECT 
                            DATE_FORMAT(MREG.date, "%Y-%m") AS month_omzet,
                            MREG.amount AS omzet_register,
                            MREG.bv AS omzet_reg_bv,
                            0 AS omzet_ro,
                            0 AS omzet_ro_bv,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MREG
                        WHERE MREG.status = "register" AND MREG.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MO.date, "%Y-%m") AS month_omzet,
                            0 AS omzet_register,
                            0 AS omzet_reg_bv,
                            MO.amount AS omzet_ro,
                            MO.bv AS omzet_ro_bv,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MO
                        WHERE MO.status = "ro"
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(B.datecreated, "%Y-%m") AS month_omzet,
                            0 AS omzet_register,
                            0 AS omzet_reg_bv,
                            0 AS omzet_ro,
                            0 AS omzet_ro_bv,
                            B.amount AS bonus
                        FROM `' . $this->bonus . '` B
                    ) AS A ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

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
     * Retrieve all omzet monthly member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Decimal Result of Data List
     */
    function get_all_omzet_monthly_member($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "M.id", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%package%",          "M.package", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%month_omzet%",      'DATE_FORMAT(A.date, "%Y-%m")', $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "M.id", $order_by);
            $order_by   = str_replace("%username%",         "M.username", $order_by);
            $order_by   = str_replace("%name%",             "M.name", $order_by);
            $order_by   = str_replace("%package%",          "M.package", $order_by);
        }

        if ($total_conditions) {
            $total_conditions = str_replace("%total_omzet%",    "SUM(A.omzet)", $total_conditions);
            $total_conditions = str_replace("%total_amount%",   "SUM(A.amount)", $total_conditions);
            $total_conditions = str_replace("%total_pv%",       "SUM(A.pv)", $total_conditions);
            $total_conditions = str_replace("%total_unit%",     "SUM(A.unit)", $total_conditions);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    DATE_FORMAT(A.date, "%Y-%m") AS month_omzet,
                    M.id,
                    A.id_member,
                    M.username,
                    M.name,
                    M.package,
                    M.tree,
                    M.datecreated,
                    IFNULL(SUM(A.omzet),0) AS total_omzet,
                    IFNULL(SUM(A.amount),0) AS total_amount,
                    IFNULL(SUM(A.pv),0) AS total_pv,
                    IFNULL(SUM(A.unit),0) AS total_unit
                FROM ' . $this->member_omzet . ' AS A
                INNER JOIN ' . $this->member . ' AS M ON (M.id = A.id_member)
                WHERE M.type = ' . MEMBER . ' AND M.status = ' . ACTIVE . ' ' . $conditions . '
                GROUP BY 1, 2';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        } else {
            if (empty(trim($conditions))) {
                $sql .= ' HAVING SUM(A.omzet) >= 0 ';
            }
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' total_pv DESC, M.username ASC');
        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ($params && is_array($params)) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }


        if (!$query || !$query->num_rows()) return false;
        return $query->result();
    }

    /**
     * Retrieve all member baord data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_member_board_data($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "A.id", $conditions);
            $conditions = str_replace("%id_member%",            "A.id_member", $conditions);
            $conditions = str_replace("%id_sponsor%",           "A.sponsor", $conditions);
            $conditions = str_replace("%code%",                 "A.code", $conditions);
            $conditions = str_replace("%board%",                "A.board", $conditions);
            $conditions = str_replace("%level%",                "A.level", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%username%",             "B.username", $conditions);
            $conditions = str_replace("%name%",                 "B.name", $conditions);
            $conditions = str_replace("%email%",                "B.email", $conditions);
            $conditions = str_replace("%phone%",                "B.phone", $conditions);
            $conditions = str_replace("%tree%",                 "B.tree_sponsor", $conditions);
            $conditions = str_replace("%sponsor%",              "C.username", $conditions);
            $conditions = str_replace("%sponsor_name%",         "C.name", $conditions);
            $conditions = str_replace("%datecreated%",          "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "A.datemodified", $conditions);
            $conditions = str_replace("%dateactived%",          "A.dateactived", $conditions);
            $conditions = str_replace("%datequalified%",        "A.datequalified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "A.id",  $order_by);
            $order_by   = str_replace("%id_member%",            "A.id_member", $order_by);
            $order_by   = str_replace("%id_sponsor%",           "A.sponsor", $order_by);
            $order_by   = str_replace("%code%",                 "A.code", $order_by);
            $order_by   = str_replace("%board%",                "A.board", $order_by);
            $order_by   = str_replace("%level%",                "A.level", $order_by);
            $order_by   = str_replace("%status%",               "A.status", $order_by);
            $order_by   = str_replace("%username%",             "B.username", $order_by);
            $order_by   = str_replace("%name%",                 "B.name", $order_by);
            $order_by   = str_replace("%email%",                "B.email", $order_by);
            $order_by   = str_replace("%phone%",                "B.phone", $order_by);
            $order_by   = str_replace("%sponsor%",              "C.username", $order_by);
            $order_by   = str_replace("%sponsor_name%",         "C.name", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "A.datemodified", $order_by);
            $order_by   = str_replace("%dateactived%",          "A.dateactived", $order_by);
            $order_by   = str_replace("%datequalified%",        "A.datequalified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.* ,
                    B.name AS name,
                    C.username AS sponsor_username,
                    C.name AS sponsor_name
                FROM ' . $this->member_board . ' AS A 
                INNER JOIN ' . $this->member . ' AS B ON (B.id = A.id_member)
                LEFT JOIN ' . $this->member . ' AS C ON (C.id = A.sponsor) 
                WHERE B.type = '. MEMBER .' ';

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if (!$query || !$query->num_rows()) return false;

        if ($num_rows){
            return $query->num_rows();
        }

        return $query->result();
    }

    /**
     * Retrieve member Reward data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of Data List
     */
    function get_all_member_reward($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%id_reward%",        "A.id_reward", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "A.type", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%message%",          "A.message", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%datemodified%",     "DATE(A.datemodified)", $conditions);
        }

        if (!empty($order_by)) {
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%name%",               "M.name", $order_by);
            $order_by = str_replace("%type%",               "A.type", $order_by);
            $order_by = str_replace("%nominal%",            "A.nominal", $order_by);
            $order_by = str_replace("%status%",             "A.status", $order_by);
            $order_by = str_replace("%message%",            "A.message", $order_by);
            $order_by = str_replace("%datecreated%",        "A.datecreated", $order_by);
            $order_by = str_replace("%datemodified%",       "A.datemodified", $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS
                A.*,
                M.name,
                M.username,
                B.nama AS bank,
                B.kode AS code_bank
            FROM ' . $this->reward . ' AS A
            INNER JOIN ' . $this->member . ' AS M ON M.id = A.id_member 
            INNER JOIN ' . $this->bank . '  AS B ON B.id = M.bank 
            WHERE M.type = ? ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql, array(MEMBER));
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve member loan data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of Data List
     */
    function get_all_member_loan($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "A.type", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%description%",      "A.description", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",     "A.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%name%",               "M.name", $order_by);
            $order_by = str_replace("%type%",               "A.type", $order_by);
            $order_by = str_replace("%amount%",             "A.amount", $order_by);
            $order_by = str_replace("%status%",             "A.status", $order_by);
            $order_by = str_replace("%description%",        "A.description", $order_by);
            $order_by = str_replace("%datecreated%",        "A.datecreated", $order_by);
            $order_by = str_replace("%datemodified%",       "A.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    A.*,
                    M.name,
                    M.username
                FROM ' . $this->loan . ' AS A
                INNER JOIN ' . $this->member . ' AS M ON (M.id = A.id_member)
                WHERE M.type = '.MEMBER;

        if (!empty($conditions)) { $sql .= $conditions; }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' A.datecreated DESC');

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
     * Retrieve member deposite loan total
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Decimal Result of member Deposite total
     */
    function get_all_member_deposite_loan($limit=0, $offset=0, $conditions='', $order_by='', $total_conditions = '', $params = ''){
        if ( ! empty( $conditions ) ){
            $conditions = str_replace("%id%",               "M.id", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "M.type", $conditions);
            $conditions = str_replace("%status%",           "M.status", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "M.id",  $order_by);
            $order_by   = str_replace("%username%",         "M.username",  $order_by);
            $order_by   = str_replace("%name%",             "M.name",  $order_by);
            $order_by   = str_replace("%deposite%",         "total_deposite",  $order_by);
            $order_by   = str_replace("%withdraw%",         "total_withdraw",  $order_by);
            $order_by   = str_replace("%total%",            "total_loan",  $order_by);
        }

        $total_sql = 'SUM( IFNULL( C.deposite, 0 ) ) - SUM( IFNULL( C.withdraw, 0 ) )';

        if ( $total_conditions ) {
            $total_conditions = str_replace("%total%",      $total_sql, $total_conditions);
            $total_conditions = str_replace("%deposite%",   "SUM(C.deposite)", $total_conditions);
            $total_conditions = str_replace("%withdraw%",   "SUM(C.withdraw)", $total_conditions);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    M.id,
                    M.username,
                    M.name,
                    IFNULL(SUM(C.deposite),0) AS total_deposite,
                    IFNULL(SUM(C.withdraw),0) AS total_withdraw,
                    ' . $total_sql . ' AS total_loan
                FROM (
                    SELECT 
                        A.id_member,
                        A.amount AS deposite,
                        0 AS withdraw
                    FROM '.$this->loan.' AS A
                    WHERE A.type = "deposite"
                    UNION ALL
                    SELECT 
                        B.id_member,
                        0 AS deposite,
                        B.amount AS withdraw
                    FROM '.$this->loan.' AS B
                    WHERE B.type = "withdraw"
                ) AS C
                INNER JOIN '.$this->member.' AS M ON (M.id = C.id_member)
                WHERE M.type = '. MEMBER . $conditions . '
                GROUP BY 1';

        if ( $total_conditions ) {
            $sql .= ' HAVING ' . ltrim( $total_conditions, ' AND' );
        }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : ' total_loan DESC, M.id ASC');
        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ( $params && is_array($params) ) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }

        if ( !$query || !$query->num_rows() ) return false;
        return $query->result();
    }

    /**
     * Count data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function new_member($status = '')
    {
        $sql = 'SELECT * FROM ' . $this->member . ' M
                INNER JOIN ' . $this->member_confirm . ' MC ON (MC.id_downline = M.id AND MC.access != "ro")
                WHERE M.type = ?';
        if ($status == 'active') { $sql .= ' AND M.status = ' . ACTIVE; }
        $sql .= ' ORDER BY M.id DESC LIMIT 1';
        $qry = $this->db->query($sql, array(MEMBER));
        if (!$qry || !$qry->num_rows()) return false;
        return $qry->row();
    }

    /**
     * Count data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function count_data($status = '', $type_status = '')
    {
        $sql = 'SELECT COUNT(id) AS member_count FROM ' . $this->member . ' WHERE type = ?';
        if ( !empty($status) ) {
            if( $status == 'notactive' )    { $status_int = 0; }
            elseif( $status == 'active' )   { $status_int = 1; }
            elseif( $status == 'banned' )   { $status_int = 2; }
            $sql .= ' AND status = ' . $status_int;
        }
        if ($type_status){
            $sql .= ' AND type_status LIKE "' . $type_status . '" ';
        }
        $qry = $this->db->query($sql, array(MEMBER));
        $row = $qry->row();

        return $row->member_count;
    }

    /**
     * Count data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function count_member($condition = '')
    {
        $sql = 'SELECT COUNT(id) AS member_count FROM ' . $this->member . ' WHERE type = ?';
        if ( $condition ) {
            $sql .= $condition;
        }
        $qry = $this->db->query($sql, array(MEMBER));
        $row = $qry->row();

        return $row->member_count;
    }

    /**
     * Count All Member By Sponsor
     *
     * @author  Yuda
     * @param   Int     $sponsor    Sponsor Member
     * @return  Int of total count member
     */
    function count_by_sponsor($sponsor, $omzet = false)
    {
        if (!$sponsor) return 0;

        $sql = 'SELECT IFNULL(COUNT(id),0) AS member_count FROM ' . $this->member . ' WHERE status = ? AND type = ? AND sponsor = ? ';
        if ( $omzet ) { $sql .= ' AND total_omzet > 0 '; }
        $qry = $this->db->query($sql, array(ACTIVE, MEMBER, $sponsor));
        if (!$qry || !$qry->num_rows()) return 0;

        return $qry->row()->member_count;
    }

    /**
     * Count All Member Board
     *
     * @author  Yuda
     * @param   Int     $id_member    ID Member
     * @param   String  $condition    Condition of query
     * @return  Int of total data
     */
    function count_member_board($id_member, $condition = '')
    {
        if (!$id_member) return 0;

        $sql = 'SELECT IFNULL(COUNT(id),0) AS board_count FROM ' . $this->member_board . ' WHERE id_member = ? ';
        if ( $condition ) {
            $sql .= $condition;
        }
        $qry = $this->db->query($sql, array($id_member));
        if (!$qry || !$qry->num_rows()) return 0;

        return $qry->row()->board_count;
    }

    /**
     * Get Count Child
     *
     * @author  Yuda
     * @param   Int     $id (Required)  Member ID
     * @param   String  $position (Required)  Position Of Node, value ('kiri' or 'kanan')
     * @param   Boolean $tree (Optional)  Get Only Tree
     * @param   String  $cfg (Required)  Point Of Node, value ('all' or 'childs' or 'pairing')
     * @param   Date    $datecreated (Optional)  Date Join of member
     * @param   Boolean $equaldate (Optional)  Get Only Date Join of member
     * @return  Mixed   False on invalid member id, otherwise count or array of invest.
     */
    function count_childs($id_member, $position = POS_LEFT, $tree = true, $cfg = 'all', $datecreated = '', $equaldate = false)
    {
        if (!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if (!$id_member) return false;

        $pos = $position;
        if ($position == POS_LEFT) {
            $pos = POS_LEFT;
        }
        if ($position == POS_RIGHT) {
            $pos = POS_RIGHT;
        }

        $point  = 0;
        $result = array('total_downline' => $point, 'total_pairing' => $point, 'total_reward' => $point);

        $sql = 'SELECT A.id, A.username, B.id AS id_downline, B.tree AS tree_downline, B.position AS pos_downline FROM ' . $this->member . ' A
                JOIN ' . $this->member . ' B ON (A.id = B.parent)
                WHERE A.id=? AND B.position=?';
        $qry = $this->db->query($sql, array($id_member, $pos));

        if (!$qry || !$qry->num_rows()) {
            if ($tree) return $result;
            return $point;
        }

        $row = $qry->row();

        // Calculate Total Omzet Member
        $total_downline = 0;
        $condition      = '';

        // Calculate Total Pairing Point Member
        if ($cfg == 'all' || $cfg == 'childs') {
            $_sql   = 'SELECT COUNT(id) AS childs FROM ' . $this->member . ' WHERE (tree LIKE CONCAT(?, "-%") OR id = ?) ' . $condition;
            if ($datecreated) {
                if ( $equaldate ) {
                    $_sql .= ' AND DATE(datecreated) = "' . $datecreated . '" ';
                } else {
                    $_sql .= ' AND DATE(datecreated) <= "' . $datecreated . '" ';
                }
            }

            $_qry = $this->db->query($_sql, array($row->tree_downline, $row->id_downline));
            if ($_qry && $_qry->num_rows()) {
                $total_downline = $point = $_qry->row()->childs;;
            }

            if ($cfg == 'childs') {
                $point = $total_downline;
            }
            $result['total_downline'] = $total_downline;
        }

        // Calculate Total Pairing Point Member
        if ($cfg == 'all' || $cfg == 'pairing') {
            $date_omzet = $datecreated ? date('Y-m-d', strtotime($datecreated)) : date('Y-m-d');

            $_sql_to = 'SELECT IFNULL(SUM(P.pairing_point_up),0) AS total_pairing
                    FROM ' . $this->member . ' M
                    INNER JOIN ' . $this->package . ' P ON (P.package = M.package)
                    WHERE (M.tree LIKE CONCAT(?, "-%") OR M.id = ?) AND M.total_omzet > 0 ';
            if ( $equaldate ) {
                $_sql_to .= ' AND DATE(M.datecreated) = "' . $date_omzet . '"';
            } else {
                $_sql_to .= ' AND DATE(M.datecreated) <= "' . $date_omzet . '"';
            }

            $_qry_to = $this->db->query($_sql_to, array($row->tree_downline, $row->id_downline));

            if ($_qry_to && $_qry_to->num_rows()) {
                if ($cfg == 'pairing') {
                    $point = $_qry_to->row()->total_pairing;
                }
                $result['total_pairing'] = $_qry_to->row()->total_pairing;
            }
        }

        if ($tree) return $result;
        return $point;
    }

    /**
     * Get Count Pairing Qualified
     *
     * @author  Yuda
     * @param   Int $id (Required)  Member ID
     * @param   Int $status (Optional)  Status of investment
     * @return  Mixed  False on invalid member id, otherwise count or array of invest.
     */
    function count_pairing_qualified($id_member, $count_total = true, $datecreated = '', $equal = false)
    {
        if (!is_numeric($id_member)) return 0;

        $id_member = absint($id_member);
        if (!$id_member) return 0;

        $sql = 'SELECT 
                    IFNULL(SUM(`left`), 0) AS total_left, 
                    IFNULL(SUM(`right`), 0) AS total_right,
                    IFNULL(SUM(`qualified`), 0) AS total_qualified
                FROM ' . $this->pairing_qualified . ' WHERE id_member = ?';

        if (!empty($datecreated)) {
            if ($equal) {
                $sql .= ' AND DATE(datecreated) = "' . date('Y-m-d', strtotime($datecreated)) . '"';
            } else {
                $sql .= ' AND DATE(datecreated) <= "' . date('Y-m-d', strtotime($datecreated)) . '"';
            }
        }

        $qry = $this->db->query($sql, array($id_member));
        if (!$qry || !$qry->num_rows()) return 0;

        if ( $count_total ) {
            $qualified = $qry->row()->total_qualified;
            return $qualified;
        }

        $row = $qry->row();
        return $row;
    }

    /**
     * Retrieve Total Member Omzet
     *
     * @author  Yuda
     * @param   String  $conditions         Condition of query          default ''
     * @return  Object  Result of data total
     */
    function get_total_member_omzet($conditions = '')
    {
        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(`omzet`), 0 ) AS total_omzet,
                        IFNULL( SUM(`amount`), 0 ) AS total_amount,
                        IFNULL( SUM(`bv`), 0 ) AS total_bv
                    FROM `' . $this->member_omzet . '` WHERE id > 0 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $query  = $this->db->query($sql);

        if (!$query || !$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Retrieve member loan total
     *
     * @author  Yuda
     * @param   Int     $member_id      Member ID
     * @return  Decimal  Result of member Loan total
     */
    function get_loan_total($id_member, $type = ''){
        if ( !is_numeric($id_member) ) return 0;

        $id_member = absint($id_member);
        if ( !$id_member ) return 0;

        $type   = $type ? strtoupper($type) : '';

        $sql    = 'SELECT IFNULL(SUM(amount),0) total FROM '.$this->loan.' WHERE id_member = ? AND status = 1';
        if ( $type )    { $sql .= ' AND type = "' . $type . '" '; }

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total;
    }

    /**
     * Retrieve Deposite Loan Member
     *
     * @author  Yuda
     * @param   Int     $member_id      Member ID
     * @return  Decimal  Result of member Deposite Member
     */
    function get_loan_deposite($id_member){
        if ( !is_numeric($id_member) ) return 0;

        $id_member = absint($id_member);
        if ( !$id_member ) return 0;

        $sql    = 'SELECT 
                        id_member,
                        IFNULL(SUM(saldo_in),0) AS total_in,
                        IFNULL(SUM(saldo_out),0) AS total_out,
                        IFNULL(SUM(saldo_in),0) - IFNULL(SUM(saldo_out),0) AS total_deposite 
                    FROM ( 
                        SELECT
                            A.id_member,
                            A.amount AS saldo_in,
                            0 AS saldo_out
                        FROM '.$this->loan.' A
                        WHERE A.type = "deposite" AND A.status = 1
                        UNION ALL
                        SELECT
                            B.id_member,
                            0 AS saldo_in,
                            B.amount AS saldo_out
                        FROM '.$this->loan.' B
                        WHERE B.type = "withdraw" AND B.status = 1
                    ) DEP
                    WHERE DEP.id_member = ? 
                    GROUP BY 1 ';

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total_deposite;
    }

    /**
     * Save data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data($data)
    {
        if (empty($data)) return false;
        if ($id = $this->insert($data)) {
            return $id;
        };
        return false;
    }

    /**
     * Save data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_confirm($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->member_confirm, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of member omzet
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member omzet
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_member_omzet($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->member_omzet, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of member board
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member board
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_member_board($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->member_board, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of board tree
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of board tree
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_board_tree($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->board_tree, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data pairing qualified of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of upgrade
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_pair_qualified($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->pairing_qualified, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data ro of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of ro
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_ro($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->ro, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data loan of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of loan
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_loan($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->loan, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data upgrade of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of upgrade
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_upgrade($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->upgrade, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update data of member
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  Member ID
     * @param   Array   $data   (Required)  Array data of user
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data($id, $data)
    {
        if (empty($id) || empty($data)) return false;
        if ($this->update($id, $data))
            return true;

        return false;
    }

    function update_data_member($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member, $data))
            return true;

        return false;
    }

    function update_data_member_confirm($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member_confirm, $data))
            return true;

        return false;
    }

    function update_data_member_omzet($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member_omzet, $data))
            return $id;

        return false;
    }

    function update_data_member_board($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member_board, $data))
            return $id;

        return false;
    }

    function update_data_loan($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->loan, $data))
            return $id;

        return false;
    }

    function update_data_reward($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->reward, $data))
            return $id;

        return false;
    }

    /**
     * Delete data of member
     *
     * @author  Yuda
     * @param   Int     $id   (Required)  ID of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_data($id)
    {
        if (empty($id)) return false;
        if ($this->delete($id)) {
            return true;
        };
        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Member.php */
/* Location: ./application/models/Model_Member.php */