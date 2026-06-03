<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('AN_Model.php');

class Model_Faspay extends AN_Model
{
    /**
     * For AN_Model
     */
    public $_table              = 'fastpay';

    /**
     * Initialize table
     */
    var $member                 = TBL_PREFIX . "member";
    var $faspay                 = TBL_PREFIX . "fastpay";
    var $faspay_inquiry         = TBL_PREFIX . "fastpay_inquiry";
    var $faspay_out             = TBL_PREFIX . "fastpay_out";
    var $faspay_trx             = TBL_PREFIX . "fastpay_trx";
    var $faspay_log             = TBL_PREFIX . "log_fastpay";
    var $wd                     = TBL_PREFIX . "withdraw";
    var $bank                   = TBL_PREFIX . "banks";

    /**
     * Initialize primary field
     */
    var $primary                = "id";

    /**
     * Constructor - Sets up the object properties.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * GET all faspay out data
     *
     * @author  Yuda
     * @param   Int $limit Limit of member                  default 0
     * @param   Int $offset Offset ot member                default 0
     * @param   String $conditions Condition of query       default ''
     * @param   String $order_by Column that make to order  default ''
     * @return  Object  Result of member faspay out list
     */
    function get_all_faspay_out($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%trx_id%",           "A.trx_id", $conditions);
            $conditions = str_replace("%id_withdraw%",      "A.id_withdraw", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%bank_code%",        "A.bank_code", $conditions);
            $conditions = str_replace("%bill%",             "A.bill", $conditions);
            $conditions = str_replace("%bill_name%",        "A.bill_name", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%datewd%",           "A.date_withdraw", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%trx_id%",           "A.trx_id",  $order_by);
            $order_by   = str_replace("%id_withdraw%",      "A.id_withdraw", $order_by);
            $order_by   = str_replace("%id_member%",        "A.id_member", $order_by);
            $order_by   = str_replace("%bank_code%",        "A.bank_code", $order_by);
            $order_by   = str_replace("%bill%",             "A.bill", $order_by);
            $order_by   = str_replace("%bill_name%",        "A.bill_name", $order_by);
            $order_by   = str_replace("%nominal%",          "A.nominal", $order_by);
            $order_by   = str_replace("%status%",           "A.status", $order_by);
            $order_by   = str_replace("%username%",         "B.username", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%datewd%",           "A.date_withdraw",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS 
                A.*, 
                B.username, 
                B.name 
            FROM ' . $this->faspay_out . ' AS A
            LEFT JOIN ' . $this->member . ' AS B ON (B.id = A.id_member) ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * GET all faspay inquiry data
     *
     * @author  Yuda
     * @param   Int $limit Limit of member                  default 0
     * @param   Int $offset Offset ot member                default 0
     * @param   String $conditions Condition of query       default ''
     * @param   String $order_by Column that make to order  default ''
     * @return  Object  Result of member faspay inquiry list
     */
    function get_all_faspay_inquiry($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%bank_code%",        "A.bank_code", $conditions);
            $conditions = str_replace("%account_number%",   "A.account_number", $conditions);
            $conditions = str_replace("%account_holder%",   "A.account_holder", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "A.id", $order_by);
            $order_by   = str_replace("%bank_code%",        "A.bank_code", $order_by);
            $order_by   = str_replace("%account_number%",   "A.account_number", $order_by);
            $order_by   = str_replace("%account_holder%",   "A.account_holder", $order_by);
            $order_by   = str_replace("%status%",           "A.status", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }

        $sql = '
        SELECT SQL_CALC_FOUND_ROWS A.*, B.nama as bank_name FROM ' . $this->faspay_inquiry . ' AS A 
        LEFT JOIN ' . $this->bank . ' AS B ON B.kode = A.bank_code ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Get Member Inquiry
     *
     * @author  Yuda
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function get_member_inquiry()
    {
        $sql    = '
            SELECT M.*, F.account_number
            FROM `' . $this->member . '` AS M
            LEFT JOIN `' . $this->faspay_inquiry . '` AS F ON F.account_number = M.bill
            WHERE M.type = 2 
            ORDER BY F.account_number ASC';
        $query  = $this->db->query($sql);

        if (!$query->num_rows()) return false;
        return $query->result();
    }

    /**
     * Get Detail Faspay Inquiry By Account Number
     *
     * @author  Yuda
     * @param   Integer     $account_number     (Required)  Account Number
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function get_detail_faspay_inquiry_by_account($account_number, $bank_code = '')
    {
        if (!$account_number) return false;

        $sql    = 'SELECT * FROM ' . $this->faspay_inquiry . ' WHERE account_number = "' . trim($account_number) . '"';
        if ($bank_code) {
            $sql .= ' AND bank_code = "' . trim($bank_code) . '"';
        }
        $query  = $this->db->query($sql);

        if (!$query->num_rows()) return false;
        return $query->row();
    }

    /**
     * Get Detail Faspay Transaction By ID
     *
     * @author  Yuda
     * @param   Integer     $trx_id         (Optional)  Faspay Transaction ID
     * @param   Integer     $data_row       (Optional)  Get one row
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function get_faspay_trx_id($trx_id = 0, $data_row = false)
    {
        $sql    = 'SELECT * FROM ' . $this->faspay_trx . ' WHERE trx_id = ' . $trx_id . ' LIMIT 1';
        $query  = $this->db->query($sql);

        if ($data_row) {
            if (!$query->num_rows()) return false;
            return $query->row();
        }
        return $query->num_rows();
    }

    /**
     * Get withdraw by faspay trx id
     */
    function get_withdraw_by_faspay($trx_id)
    {
        if (!$trx_id) return false;
        $sql = 'SELECT * FROM ' . $this->wd . '  WHERE trx_id = ? ';
        $query = $this->db->query($sql, array($trx_id));
        if (!$query || !$query->num_rows()) return false;

        return $query->row();
    }

    /**
     * Save data of Faspay
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of faspay
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_faspay($data)
    {
        if (empty($data)) return false;

        $data_id = 0;
        if ($this->db->insert($this->faspay, $data)) {
            $data_id = $this->db->insert_id();
        } else {
            an_log_fastpay("ERROR_FASPAY", 1, $this->db->last_query());
        }
        return $data_id;
    }
    
    /**
     * Save data of Faspay Trx
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of faspay
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_faspay_trx($data)
    {
        if (empty($data)) return false;

        $data_id = 0;
        if ($this->db->insert($this->faspay_trx, $data)) {
            $data_id = $this->db->insert_id();
        } else {
            an_log_fastpay("ERROR_FASPAY_TRX", 1, $this->db->last_query());
        }
        return $data_id;
    }

    /**
     * Save data of Faspay Inquiry
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of faspay inquiry
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_faspay_inquiry($data)
    {
        if (empty($data)) return false;

        $data_id = 0;
        if ($this->db->insert($this->faspay_inquiry, $data)) {
            $data_id = $this->db->insert_id();
        }
        return $data_id;
    }

    /**
     * Save data of Faspay Out
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of faspay out
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_faspay_out($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->faspay_out, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of Faspay Log
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of faspay log
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_faspay_log($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->faspay_log, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update data of withdraw
     *
     * @author  Yuda
     * @param   Int $id (Required)  Withdraw ID
     * @param   Array $data (Required)  Array data of withdraw
     * @param   Array $condition (Optional)  Array data of withdraw condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_withdraw($id, $data, $condition = array())
    {
        if (empty($id) || empty($data))
            return false;

        $this->db->where($this->primary, $id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->wd, $data))
            return true;

        return false;
    }

    /**
     * Update data of withdraw faspay
     *
     * @author  Yuda
     * @param   Int $id (Required)  Faspay Trx ID
     * @param   Array $data (Required)  Array data of withdraw faspay
     * @param   Array $condition (Optional)  Array data of withdraw condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_withdraw_faspay($id, $data, $condition = array())
    {
        if (empty($id) || empty($data))
            return false;

        $this->db->where('trx_id', $id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->wd, $data))
            return true;

        return false;
    }

    /**
     * Update data of faspay
     *
     * @author  Yuda
     * @param   Int     $trx_id     (Required)  Faspay Trx ID
     * @param   Array   $data       (Required)  Array data of faspay
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_faspay_data($trx_id, $data)
    {
        if (empty($trx_id) || empty($data))
            return false;

        $this->db->where('trx_id', $trx_id);
        if ($this->db->update($this->faspay, $data))
            return true;

        return false;
    }
    
    /**
     * Update data of faspay trx
     *
     * @author  Yuda
     * @param   Int     $trx_id     (Required)  Faspay Trx ID
     * @param   Array   $data       (Required)  Array data of faspay
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_faspay_trx_data($trx_id, $data)
    {
        if (empty($trx_id) || empty($data))
            return false;

        $this->db->where('trx_id', $trx_id);
        if ($this->db->update($this->faspay_trx, $data))
            return true;

        return false;
    }

    /**
     * Update data of faspay out
     *
     * @author  Yuda
     * @param   Int $trx_id (Required)  Faspay Trx ID
     * @param   Array $data (Required)  Array data of faspay out
     * @param   Array $condition (Optional)  Array data of faspay out condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_faspay_out_data_by_trx_id($trx_id, $data, $condition = array())
    {
        if (empty($trx_id) || empty($data))
            return false;

        $this->db->where('trx_id', $trx_id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->faspay_out, $data))
            return true;

        return false;
    }

    /**
     * Update data of faspay inquiry
     *
     * @author  Yuda
     * @param   Int     $account_number     (Required)  Account Number
     * @param   String  $bank_code          (Required)  Bank Code
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_faspay_inquiry_data($account_number, $bank_code = '', $data)
    {
        if (!$account_number) return false;
        if (empty($data)) return false;

        $this->db->where('account_number', $account_number);
        if ($bank_code) {
            $this->db->where('bank_code', $bank_code);
        }
        if ($this->db->update($this->faspay_inquiry, $data))
            return true;

        return false;
    }

    /**
     * Delete Faspay Inquiry
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  Faspay Inquiry ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_inquiry($id)
    {
        if (empty($id))
            return false;

        $this->db->where($this->primary, $id);
        if ($this->db->delete($this->faspay_inquiry))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Faspay.php */
/* Location: ./application/models/Model_Faspay.php */
