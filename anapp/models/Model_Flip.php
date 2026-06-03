<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('AN_Model.php');

class Model_Flip extends AN_Model
{
    /**
     * For AN_Model
     */
    public $_table              = 'flip';

    /**
     * Initialize table
     */
    var $member                 = TBL_PREFIX . "member";
    var $flip                   = TBL_PREFIX . "flip";
    var $flip_in                = TBL_PREFIX . "flip_in";
    var $flip_inquiry           = TBL_PREFIX . "flip_inquiry";
    var $flip_out               = TBL_PREFIX . "flip_out";
    var $flip_log_flip          = TBL_PREFIX . "log_flip";
    var $wd                     = TBL_PREFIX . "withdraw";

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
     * GET all flip in data
     *
     * @author  Yuda
     * @param   Int $limit Limit of member                  default 0
     * @param   Int $offset Offset ot member                default 0
     * @param   String $conditions Condition of query       default ''
     * @param   String $order_by Column that make to order  default ''
     * @return  Object  Result of member flip in list
     */
    function get_all_flip_in($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%topup_id%",         "A.topup_id", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%code%",             "A.code", $conditions);
            $conditions = str_replace("%bank%",             "A.bank", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%topup_id%",         "A.topup_id",  $order_by);
            $order_by   = str_replace("%amount%",           "A.amount",  $order_by);
            $order_by   = str_replace("%code%",             "A.code",  $order_by);
            $order_by   = str_replace("%bank%",             "A.bank",  $order_by);
            $order_by   = str_replace("%status%",           "A.status",  $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS A.* FROM ' . $this->flip_in . ' AS A ';

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
     * GET all flip out data
     *
     * @author  Yuda
     * @param   Int $limit Limit of member                  default 0
     * @param   Int $offset Offset ot member                default 0
     * @param   String $conditions Condition of query       default ''
     * @param   String $order_by Column that make to order  default ''
     * @return  Object  Result of member flip out list
     */
    function get_all_flip_out($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%flip_id%",          "A.flip_id", $conditions);
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
            $order_by   = str_replace("%flip_id%",          "A.flip_id",  $order_by);
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
            FROM ' . $this->flip_out . ' AS A
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
     * GET all flip inquiry data
     *
     * @author  Yuda
     * @param   Int $limit Limit of member                  default 0
     * @param   Int $offset Offset ot member                default 0
     * @param   String $conditions Condition of query       default ''
     * @param   String $order_by Column that make to order  default ''
     * @return  Object  Result of member flip inquiry list
     */
    function get_all_flip_inquiry($limit = 0, $offset = 0, $conditions = '', $order_by = '')
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

        $sql = 'SELECT SQL_CALC_FOUND_ROWS A.* FROM ' . $this->flip_inquiry . ' AS A ';

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
            LEFT JOIN `' . $this->flip_inquiry . '` AS F ON F.account_number = M.bill
            WHERE M.type = 2 
            ORDER BY F.account_number ASC';
        $query  = $this->db->query($sql);

        if (!$query->num_rows()) return false;
        return $query->result();
    }

    /**
     * Get Detail Flip Inquiry By Account Number
     *
     * @author  Yuda
     * @param   Integer     $account_number     (Required)  Account Number
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function get_detail_flip_inquiry_by_account($account_number, $bank_code = '')
    {
        if (!$account_number) return false;

        $sql    = 'SELECT * FROM ' . $this->flip_inquiry . ' WHERE account_number = "' . trim($account_number) . '"';
        if ($bank_code) {
            $sql .= ' AND bank_code = "' . trim($bank_code) . '"';
        }
        $query  = $this->db->query($sql);

        if (!$query->num_rows()) return false;
        return $query->row();
    }

    /**
     * Get Detail Flip Transaction By ID
     *
     * @author  Yuda
     * @param   Integer     $flip_id        (Optional)  Flip Transaction ID
     * @param   Integer     $data_row       (Optional)  Get one row
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function get_flip_trx_id($flip_id = 0, $data_row = false)
    {
        $sql    = 'SELECT * FROM ' . $this->flip . ' WHERE flip_id = ' . $flip_id . ' LIMIT 1';
        $query  = $this->db->query($sql);

        if ($data_row) {
            if (!$query->num_rows()) return false;
            return $query->row();
        }
        return $query->num_rows();
    }

    /**
     * Get withdraw by flip id
     */
    function get_withdraw_by_flip($id)
    {
        if (!$id) return false;
        $sql = 'SELECT * FROM ' . $this->wd . '  WHERE flip_id = ? ';
        $query = $this->db->query($sql, array($id));
        if (!$query || !$query->num_rows()) return false;

        return $query->row();
    }

    /**
     * Save data of Flip
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of flip
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_flip($data)
    {
        if (empty($data)) return false;

        $data_id = 0;
        if ($this->db->insert($this->flip, $data)) {
            $data_id = $this->db->insert_id();
        } else {
            bo_log("ERROR_FLIP", 1, $this->db->last_query());
        }
        return $data_id;
    }

    /**
     * Save data of Flip In
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of flip in
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_flip_in($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->flip_in, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of Flip Inquiry
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of flip inquiry
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_flip_inquiry($data)
    {
        if (empty($data)) return false;

        $data_id = 0;
        if ($this->db->insert($this->flip_inquiry, $data)) {
            $data_id = $this->db->insert_id();
        }
        return $data_id;
    }

    /**
     * Save data of Flip Out
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of flip out
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_flip_out($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->flip_out, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of Flip Log
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of flip wd
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_flip_log($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->flip_log_flip, $data)) {
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
     * Update data of withdraw flip
     *
     * @author  Yuda
     * @param   Int $id (Required)  Flip ID
     * @param   Array $data (Required)  Array data of withdraw flip
     * @param   Array $condition (Optional)  Array data of withdraw condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_withdraw_flip($id, $data, $condition = array())
    {
        if (empty($id) || empty($data))
            return false;

        $this->db->where('flip_id', $id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->wd, $data))
            return true;

        return false;
    }

    /**
     * Update data of flip
     *
     * @author  Yuda
     * @param   Int     $flip_id    (Required)  Flip ID
     * @param   Array   $data       (Required)  Array data of flip
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_flip_data($flip_id, $data)
    {
        if (empty($flip_id) || empty($data))
            return false;

        $this->db->where('flip_id', $flip_id);
        if ($this->db->update($this->flip, $data))
            return true;

        return false;
    }

    /**
     * Update data of flip in
     *
     * @author  Yuda
     * @param   Int $id (Required)  Flip ID
     * @param   Array $data (Required)  Array data of flip in
     * @param   Array $condition (Optional)  Array data of flip in condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_flip_in_data($id, $data, $condition = array())
    {
        if (empty($id) || empty($data))
            return false;

        $this->db->where('topup_id', $id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->flip_in, $data))
            return true;

        return false;
    }

    /**
     * Update data of flip out
     *
     * @author  Yuda
     * @param   Int $id (Required)  Flip ID
     * @param   Array $data (Required)  Array data of flip out
     * @param   Array $condition (Optional)  Array data of flip out condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_flip_out_data_by_flip($id, $data, $condition = array())
    {
        if (empty($id) || empty($data))
            return false;

        $this->db->where('flip_id', $id);
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if ($this->db->update($this->flip_out, $data))
            return true;

        return false;
    }

    /**
     * Update data of flip inquiry
     *
     * @author  Yuda
     * @param   Int     $account_number     (Required)  Account Number
     * @param   String  $bank_code          (Required)  Bank Code
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_flip_inquiry_data($account_number, $bank_code = '', $data)
    {
        if (!$account_number) return false;
        // if( !$bank_code ) return false;
        if (empty($data)) return false;

        $this->db->where('account_number', $account_number);
        if ($bank_code) {
            $this->db->where('bank_code', $bank_code);
        }
        if ($this->db->update($this->flip_inquiry, $data))
            return true;

        return false;
    }

    /**
     * Count Total Topup
     *
     * @author  Yuda
     * @return  Int of total topup
     */
    function count_total_topup()
    {
        $sql = 'SELECT IFNULL(SUM(A.real_amount),0) AS total_topup FROM ' . $this->flip_in . ' A WHERE A.status = ?';
        $qry = $this->db->query($sql, array(1));
        $row = $qry->row();

        return $row->total_topup;
    }

    /**
     * Count Total Transaction Done
     *
     * @author  Yuda
     * @return  Int of total transaction done
     */
    function count_total_trx_done()
    {
        $sql = 'SELECT
                    A.*,
                    (A.total_fee + A.total_amount) AS total_out,
                    (A.total_amount) AS total_out_manual,
                    (A.fee_trx + A.total_amount) AS total_out_flip
                FROM (
                    SELECT
                        IFNULL(COUNT(id), 0) AS total_trx,
                        IFNULL(SUM(fee), 0) AS total_fee,
                        IFNULL(SUM(fee_manual), 0) AS fee_manual,
                        IFNULL(SUM(amount), 0) AS total_amount,
                        (SELECT IFNULL(SUM(F2.fee_manual), 0) AS fee_manual FROM ' . $this->flip . ' F2 WHERE F2.`status` = "done") AS fee_trx
                    FROM ' . $this->flip . ' 
                    WHERE `status` = "done"
                ) A';

        $query  = $this->db->query($sql);

        if (!$query || !$query->num_rows())
            return false;

        return $query->row()->total_out_manual;
    }

    /**
     * Count Total Transaction Pending
     *
     * @author  Yuda
     * @return  Int of total transaction pending
     */
    function count_total_trx_pending()
    {
        $sql = 'SELECT
                    A.*,
                    (A.total_pending + A.total_fee) AS total_amount_pending
                FROM (
                    SELECT
                        IFNULL(COUNT(id), 0) AS total_trx,
                        IFNULL(SUM(nominal), 0) AS total_pending,
                        (IFNULL(COUNT(id), 0) * 5000) AS total_fee
                    FROM `' . $this->flip_out . '` O
                    WHERE O.status = 0
                ) AS A';

        $query  = $this->db->query($sql);

        if (!$query || !$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Count Total Transfer
     *
     * @author  Yuda
     * @return  Int of total transfer
     */
    function count_total_trx_fee()
    {
        $sql = 'SELECT IFNULL(SUM(A.fee_manual),0) AS total_trx_fee FROM ' . $this->flip . ' A WHERE A.status = ?';
        $qry = $this->db->query($sql, array('DONE'));
        $row = $qry->row();

        return $row->total_trx_fee;
    }

    /**
     * Delete FLIP Inquiry
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  FLIP Inquiry ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_inquiry($id)
    {
        if (empty($id))
            return false;

        $this->db->where($this->primary, $id);
        if ($this->db->delete($this->flip_inquiry))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Flip.php */
/* Location: ./application/models/Model_Flip.php */
