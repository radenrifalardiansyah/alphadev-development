<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// -------------------------------------------------------------------------
// Member functions helper
// -------------------------------------------------------------------------

if (!function_exists('an_get_memberdata_by_id')) {
    /**
     * Get member data by id
     *
     * @param integer $id Member ID
     * @return (object) member data
     */
    function an_get_memberdata_by_id($id)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_memberdata($id);
    }
}

if (!function_exists('an_get_memberconfirm_by_downline')) {
    /**
     * Get Member Confirm data by id downline
     *
     * @param integer $id ID Downline
     * @return (object) member confirm data
     */
    function an_get_memberconfirm_by_downline($id)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_member_confirm_by_downline($id);
    }
}

if (!function_exists('as_active_member')) {
    /**
     *
     * Is current member is active member
     * @param Object $member
     * @return bool
     */
    function as_active_member($member)
    {
        if (!empty($member)) {
            return ($member->status == 1);
        }
        return false;
    }
}

if (!function_exists('as_administrator')) {
    /**
     *
     * Is current member is SuperAdmin
     * @param Object $member
     * @return bool
     */
    function as_administrator($member)
    {
        if (!$member) return false;

        $CI = &get_instance();
        $member = $CI->an_member->member($member->id);

        return (($member->type == ADMINISTRATOR));
    }
}

if (!function_exists('as_member')) {
    /**
     *
     * Is current user is member
     * @param Object $member
     * @return bool
     */
    function as_member($member)
    {
        if (!$member) return false;

        $CI = &get_instance();
        $member = $CI->an_member->member($member->id);

        return (($member->type == MEMBER));
    }
}

if (!function_exists('as_reseller')) {
    /**
     *
     * Is current user is reseller
     * @param Object $member
     * @return bool
     */
    function as_reseller($member)
    {
        if (!$member) return false;
        return (($member->type_status == TYPE_STATUS_RESELLER));
    }
}

if (!function_exists('as_dropshipper')) {
    /**
     *
     * Is current user is dropshipper
     * @param Object $member
     * @return bool
     */
    function as_dropshipper($member)
    {
        if (!$member) return false;
        return (($member->type_status == TYPE_STATUS_DROPSHIPPER));
    }
}

if (!function_exists('an_generate_password')) {
    /**
     * Generate password for member
     * @author  Yuda
     * @param   int     $length     Random String Length
     * @param   boolean $cap        Capital (Default FALSE)
     * @return  String
     */
    function an_generate_password($length = 0, $cap = false)
    {
        $characters     = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        if ($cap) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $randomString   = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('an_is_username_blacklisted')) {
    /**
     * Is ID member blacklisted
     * @param  string $username ID Member
     * @return boolean           blacklisted
     */
    function an_is_username_blacklisted($username)
    {
        if (!$blacklist = get_option('blacklist'))
            return false;

        return in_array($username, an_isset($blacklist['usernames'], array()));
    }
}

if (!function_exists('an_is_email_blacklisted')) {
    /**
     * Is email blacklisted
     * @param  string $email Email
     * @return boolean        blacklisted
     */
    function an_is_email_blacklisted($email)
    {
        if (!$blacklist = get_option('blacklist'))
            return false;

        return in_array($email, an_isset($blacklist['emails'], array()));
    }
}

if (!function_exists('an_unset_current_member_human')) {
    /**
     * @since 1.0.0
     * @access public
     * @author Yuda
     */
    function an_unset_current_member_human()
    {
        $CI = &get_instance();
        return $CI->session->unset_userdata('is_human');
    }
}
if (!function_exists('an_unset_clone_member_data')) {
    /**
     * @since 1.0.0
     * @access public
     * @author Yuda
     * @param  Object $memberdata Object member data
     */
    function an_unset_clone_member_data($member, $unset_id = false)
    {
        if (!$member) return false;

        $CI = &get_instance();

        if (is_array($member)) $member = (object)$member;

        $memberdata = new stdClass();
        $memberdata->name                   = $member->name;
        $memberdata->email                  = $member->email;
        $memberdata->phone                  = $member->phone;
        $memberdata->phone_home             = $member->phone_home;
        $memberdata->phone_office           = $member->phone_office;
        $memberdata->pob                    = $member->pob;
        $memberdata->dob                    = $member->dob;
        $memberdata->gender                 = $member->gender;
        $memberdata->marital                = $member->marital;
        $memberdata->idcard_type            = $member->idcard_type;
        $memberdata->idcard                 = $member->idcard;
        $memberdata->npwp                   = $member->npwp;
        $memberdata->country                = $member->country;
        $memberdata->province               = $member->province;
        $memberdata->district               = $member->district;
        $memberdata->subdistrict            = $member->subdistrict;
        $memberdata->village                = $member->village;
        $memberdata->address                = $member->address;
        $memberdata->bank                   = $member->bank;
        $memberdata->bill                   = $member->bill;
        $memberdata->bill_name              = $member->bill_name;
        $memberdata->emergency_name         = $member->emergency_name;
        $memberdata->emergency_relationship = $member->emergency_relationship;
        $memberdata->emergency_phone        = $member->emergency_phone;

        return $memberdata;
    }
}

if (!function_exists('an_generate_tree')) {
    /**
     * Generate tree for member
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID
     * @param   int     $up_tree    (Required)  Upline Tree
     * @return  String
     */
    function an_generate_tree($id_member, $up_tree)
    {
        if (!$up_tree) return false;

        if (!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $tree = $up_tree . '-' . $id_member;

        return $tree;
    }
}

if (!function_exists('an_generate_tree_sponsor')) {
    /**
     * Generate tree for member
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID
     * @param   int     $spon_tree  (Required)  Sponsor Tree
     * @return  String
     */
    function an_generate_tree_sponsor($id_member, $spon_tree)
    {
        if (!$spon_tree) return false;

        if (!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $tree = $spon_tree . '-' . $id_member;

        return $tree;
    }
}

if (!function_exists('an_ancestry')) {
    /**
     * Get ancestry data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function an_ancestry($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_ancestry($id_member);
    }
}

if (!function_exists('an_ancestry_sponsor')) {
    /**
     * Get ancestry sponsor data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function an_ancestry_sponsor($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_ancestry_sponsor($id_member);
    }
}

if (!function_exists('an_upline_available')) {
    /**
     * Get upline available data of member
     * @author  Yuda
     * @param   Int     $id_member      (Optional)  Member ID
     * @return  Object parent data
     */
    function an_upline_available($id_member = '', $firstrow = true, $date = '', $level_qualified = '', $level_equal = false)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_upline_available_position($id_member, $firstrow, $date, $level_qualified, $level_equal);
    }
}

if (!function_exists('an_downline')) {
    /**
     * Get downline of member
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID (Parent)
     * @param   String  $position   (Optional)  Position of downline, value ('kiri' or 'kanan')
     * @param   String  $status     (Optional)  Status of Downline, value ('active' or 'pending')
     * @param   Boolean $count      (Optional)  Get Count of Downline
     * @return  Mixed,  Boolean if wrong data of id member, otherwise data or count of downline
     */
    function an_downline($id_member, $position = '', $status = '', $count = false)
    {
        if(!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if(!$id_member) return false;

        $CI =& get_instance();
        $member = $CI->Model_Member->get_downline($id_member, $position, $status, $count);

        return $member;
    }
}

if (!function_exists('an_my_gen_sponsor')) {
    /**
     * Get ancestry sponsor data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function an_my_gen_sponsor($id_member)
    {
        $CI = &get_instance();
        $ancestry_sponsor = $CI->Model_Member->get_ancestry_sponsor($id_member);
        if (!$ancestry_sponsor) return 0;
        $ids_sponsor = explode(',', $ancestry_sponsor);
        return count($ids_sponsor);
    }
}

if (!function_exists('an_position_sponsor')) {
    /**
     * Check your position of sponsor
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return Mixed, Boolean false if invalid member id, otherwise of position sponsor 
     */
    function an_position_sponsor($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_position_sponsor($id_member);
    }
}

if ( !function_exists('an_position_upline') )
{
    /**
     * Check your position of upline
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return Mixed, Boolean false if invalid member id, otherwise of position upline 
     */
    function an_position_upline($id_member) {
        $CI =& get_instance();
        return $CI->Model_Member->get_position_upline($id_member);
    }
}

if (!function_exists('an_check_username')) {
    /**
     *
     * Check username available
     * @param   Int     $username      Username
     * @return Mixed, Boolean false if invalid username, otherwise array of phone available
     */
    function an_check_username($username)
    {
        if (!$username) return false;
        $CI = &get_instance();

        $username_exist = false;
        $condition      = ' WHERE %username% LIKE "' . $username . '" ';
        $data           = $CI->Model_Auth->get_all_user_data(1, 0, $condition, '');
        if ($data) {
            $username_exist = $data[0];
        }

        if (!$username_exist) {
            $staff = $CI->Model_Staff->get_by('username', $username);
            if ($staff) {
                $username_exist = $staff;
            }
        }

        return $username_exist;
    }
}

if (!function_exists('an_generate_username_unique')) {
    /**
     * Generate Username unique number
     * @author  Yuda
     * @return  String
     */
    function an_generate_username_unique($username, $plus = 0, $add_char = '')
    {
        if (!$username) return false;
        $CI = &get_instance();

        $username_exist = false;
        $number         = 0;
        $condition      = ' WHERE %username% LIKE "' . $username . '%"';
        $data           = $CI->Model_Auth->get_all_user_data(1, 0, $condition, 'username DESC');
        if ($data) {
            $username_exist = isset($data[0]->username) ? $data[0]->username : false;
        }

        if ($username_exist) {
            $len        = strlen($username);
            $number     = substr($username_exist, 0, $len);
            $number     = is_numeric($number) ? $number : 0;
        }

        $number         = intval($number);
        $number         = $number + $plus;
        // $unique_number  = str_pad($number + 1, 4, '0', STR_PAD_LEFT);
        $unique_user    = strtolower($username);
        if ( $number ) {
            $unique_user .= $add_char . $number;
        }

        $check_exist    = an_check_username($unique_user);
        if ($check_exist) {
            $next       = $plus + 1;
            return an_generate_username_unique($username, $next, $add_char);
        } else {
            return $unique_user;
        }
    }
}

if ( !function_exists('kb_activation_ro') )
{
    /**
     * Activation RO
     * @param  object   $registrant     Data of Registrant
     * @param  object   $memberdata     Data of Member
     * @param  date     $datetime       Datetime
     * @param  string   &$message       Message of status upgrade (reference variable)
     * @return boolean
     */
    function kb_activation_ro( $registrant, $memberdata = '', $pin_id = '', $datetime = '', &$message = '' ) {
        $CI =& get_instance();

        $datetime       = $datetime ? $datetime : date( 'Y-m-d H:i:s' );
        $registrant     = $registrant ? $registrant : an_get_current_member();
        $pin_id         = $pin_id ? an_decrypt($pin_id) : false;

        if ( !$registrant ){
            $message = 'Pendaftar tidak valid!';
            return false;
        }

        if ( !$memberdata ){
            $message = 'Member tidak valid!';
            return false;
        }

        if ( !$pin_id ){
            $message = 'Data PIN tidak valid!';
            return false;
        }

        if ( !is_object($registrant) ){
            $message = 'Pendaftar tidak valid!';
            return false;
        }

        if ( !is_object($memberdata) ){
            $message = 'Member tidak valid!';
            return false;
        }

        $pindata                = $CI->Model_Shop->get_pin_by('id', $pin_id);
        if ( ! $pindata ) {
            $message = 'Data PIN tidak valid! ';
            return false;
        }


        if ( $pindata->status != 1 ) {
            $message = 'Status PIN tidak aktif!';
            return false;
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $CI->db->trans_begin();

        // Set Data RO
        $data_ro                = array(
            'id_activator'      => $registrant->id,
            'id_member'         => $memberdata->id,
            'omzet'             => $pindata->bv,
            'amount'            => $pindata->amount,
            'pins'              => $pindata->id,
            'desc'              => '',
            'date'              => date('Y-m-d', strtotime($datetime)),
            'datecreated'       => $datetime,
            'datemodified'      => $datetime
        );

        // Save Data RO
        if ( ! $save_ro_id = $CI->Model_Member->save_data_ro($data_ro) ) {
            // Rollback Transaction
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada transaksi !';
            return false;
        }

        // Update Data PIN
        $data_update_pin = array(
            'id_member_register'    => $registrant->id,
            'id_member_registered'  => $memberdata->id,
            'status'                => 2,
            'used'                  => 'ro',
            'dateused'              => $datetime
        );

        // update PIN status to 2 => used
        if ( ! $update_pin = $CI->Model_Shop->update_pin( $pindata->id, $data_update_pin) ) {
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan data update data PIN Produk !';
            return false;
        }

        // Set Data Omzet RO
        $total_price            = $pindata->amount;
        $total_omzet_bv         = $pindata->bv;

        $data_member_omzet      = array(
            'id_member'         => $memberdata->id,
            'bv'                => $total_omzet_bv,
            'omzet'             => $total_omzet_bv,
            'amount'            => $total_price,
            'status'            => 'ro',
            'desc'              => 'RO',
            'date'              => date('Y-m-d', strtotime($datetime)),
            'datecreated'       => $datetime,
            'datemodified'      => $datetime
        );

        // Save Data Omzet RO
        if ( ! $insert_member_omzet = $CI->Model_Member->save_data_member_omzet($data_member_omzet) ) {
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi simpan data omzet ro !';
            return false;
        }

        // -------------------------------------------------
        // Clone Member
        // -------------------------------------------------
        // Set Sponsor Data
        $sponsordata            = $memberdata;
        $sponsor_id             = $memberdata->id;

        // Get Upline Data
        $uplinedata             = an_upline_available($sponsor_id);
        if ( !$uplinedata ) {
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada transaksi clone data member (data upline) !';
            return false;
        }

        $upline_id              = $uplinedata->id;
        $position_node          = an_check_node($upline_id);
        if ( $position_node ) {
            $position           = ( count($position_node) > 1 ) ? POS_LEFT : $position_node[0];
        } else {
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada transaksi clone data member (position downline) !';
            return false;
        }

        // Set General Data Member
        $username               = $sponsordata->username .'_ro';
        $generate_username      = an_generate_username_unique($username, 1);
        $password               = an_decrypt($sponsordata->password_pin);
        $m_status               = 1;

        // Data Member
        $data_member            = array(
            'username'              => $generate_username,
            'password'              => $sponsordata->password,
            'password_pin'          => $sponsordata->password_pin,
            'type'                  => MEMBER,
            'package'               => $sponsordata->package,
            'sponsor'               => $sponsor_id,
            'parent'                => $upline_id,
            'position'              => $position,
            'name'                  => strtoupper($sponsordata->name),
            'pob'                   => $sponsordata->pob,
            'dob'                   => $sponsordata->dob,
            'gender'                => $sponsordata->gender,
            'marital'               => $sponsordata->marital,
            'idcard_type'           => $sponsordata->idcard_type,
            'idcard'                => $sponsordata->idcard,
            'npwp'                  => $sponsordata->npwp,
            'country'               => $sponsordata->country,
            'province'              => $sponsordata->province,
            'district'              => $sponsordata->district,
            'subdistrict'           => $sponsordata->subdistrict,
            'village'               => $sponsordata->village,
            'address'               => $sponsordata->address,
            'email'                 => $sponsordata->email,
            'phone'                 => $sponsordata->phone,
            'phone_home'            => $sponsordata->phone_home,
            'phone_office'          => $sponsordata->phone_office,
            'bank'                  => $sponsordata->bank,
            'bill'                  => $sponsordata->bill,
            'bill_name'             => $sponsordata->bill_name,
            'emergency_name'        => $sponsordata->emergency_name,
            'emergency_relationship'=> $sponsordata->emergency_relationship,
            'emergency_phone'       => $sponsordata->emergency_phone,
            'status'                => $m_status,
            'total_omzet'           => $total_omzet_bv,
            'uniquecode'            => 0,
            'datecreated'           => $datetime,
        );

        // -------------------------------------------------
        // Save Member
        // -------------------------------------------------
        if ( !$member_save_id = $CI->Model_Member->save_data($data_member) ) {
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi clone data member !';
            return false;
        }

        if ($m_status == 1) {
            // Update Member Tree
            // -------------------------------------------------
            $gen                = $sponsordata->gen + 1;
            $level              = $uplinedata->level + 1;
            $tree               = an_generate_tree($member_save_id, $uplinedata->tree);
            $tree_sponsor       = an_generate_tree_sponsor($member_save_id, $sponsordata->tree_sponsor);
            $data_tree          = array('gen' => $gen, 'level' => $level, 'tree' => $tree, 'tree_sponsor' => $tree_sponsor);
            if ( !$update_tree = $CI->Model_Member->update_data_member($member_save_id, $data_tree) ) {
                $CI->db->trans_rollback();
                $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi clone data member !';
                return false;
            }

            // -------------------------------------------------
            // Generate Key Member
            // -------------------------------------------------
            $generate_key = an_generate_key();
            an_generate_key_insert($generate_key, ['id_member' => $member_save_id, 'name' => $sponsordata->name]);
        }

        if ( !$downline = an_get_memberdata_by_id($member_save_id) ) {
            // Rollback Transaction
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi clone data member !';
            return false;
        }

        $data_member_confirm    = array(
            'id_member'         => $registrant->id,
            'member'            => $registrant->username,
            'id_sponsor'        => $sponsordata->id,
            'sponsor'           => $sponsordata->username,
            'id_downline'       => $downline->id,
            'downline'          => $downline->username,
            'package'           => $downline->package,
            'status'            => $m_status,
            'access'            => 'ro',
            'omzet'             => 0,
            'uniquecode'        => 0,
            'nominal'           => 0,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        $insert_member_confirm  = $CI->Model_Member->save_data_confirm($data_member_confirm);
        if (!$insert_member_confirm) {
            // Rollback Transaction
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi clone data member confirm !';
            return false;
        }

        if ( $total_omzet_bv ) {
            // -------------------------------------------------
            // calculate bonus referral
            // -------------------------------------------------
            $bonus_referral     = an_calculate_bonus_referral($downline->id, $datetime);
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($CI->db->trans_status() === FALSE) {
            // Rollback Transaction
            $CI->db->trans_rollback();
            $message = 'RO tidak berhasil. Terjadi kesalahan pada proses transaksi !';
            return false;
        }

        // Commit Transaction
        $CI->db->trans_commit();
        // Complete Transaction
        $CI->db->trans_complete();

        an_log_action('MEMBER_RO', $sponsordata->username, $registrant->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS')));
        an_log_action('MEMBER_REG_RO', $username, $registrant->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'form' => 'RO')));

        // Send Notif Email
        // $CI->an_email->send_email_new_member($downline, $sponsordata, $password);
        $CI->an_email->send_email_sponsor($downline, $sponsordata);

        $sponsorname    = $sponsordata->username . ' / ' . $sponsordata->name;
        $message        = '
            <div class="row">
                <h5 class="heading-small">Data New Member :</h5>
                <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('username') . '</small></div>
                <div class="col-sm-9"><small class="text-lowecase font-weight-bold">' . $username . '</small></div>
            </div>
            <div class="row">
                <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('name') . '</small></div>
                <div class="col-sm-9"><small class="text-uppercase font-weight-bold">' . $sponsordata->name . '</small></div>
            </div>
            <hr class="mt-2 mb-2">
            <div class="row">
                <div class="col-sm-3"><small class="text-capitalize text-muted">Sponsor</small></div>
                <div class="col-sm-9"><small class="font-weight-bold">' . $sponsorname . '</small></div>
            </div>';

        return true;
    }
}

if (!function_exists('an_check_node')) {
    /**
     *
     * Check your first node available
     * @param   Int $id_member Member ID
     * @param   String $position Position of Member
     * @return Mixed, Boolean false if invalid member id, otherwise array of node available
     */
    function an_check_node($id_member, $position = '')
    {
        if(!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if(!$id_member) return false;

        $CI =& get_instance();

        if(!empty($position)) {
            $nodedata = $CI->Model_Member->get_node_available($id_member, FALSE, $position);
            if(!empty($nodedata)) {
                return $nodedata[0];
            }
            return false;
        } else {
            $nodedata   = $CI->Model_Member->get_node_available($id_member);
            $rows       = count($nodedata);
            $node       = array();

            if($rows == 0) {
                $node   = array();
                $node[] = POS_LEFT;
                $node[] = POS_RIGHT;
            } elseif($rows == 1) {
                $node   = array();
                if($nodedata[0]->position == POS_LEFT) $node[] = POS_RIGHT;
                else $node[] = POS_LEFT;
            } else {
                $node   = '';
            }
            return $node;
        }
    }
}

if (!function_exists('an_avatar')) {
    /**
     * Get node
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @param   Boolean $new (Optional)  New Member
     * @return  Mixed, Boolean if wrong data of id member, otherwise data or node
     */
    function an_avatar($id_member, $photo_me = '', $id_sponsor = 0, $showct = TRUE)
    {
        $avatar         = '';
        if(!is_numeric($id_member)) return $avatar;
        $id_member      = absint($id_member);
        if(!$id_member) return $avatar;

        $member         = an_get_memberdata_by_id($id_member);
        if(!$member || empty($member)) return $avatar;

        $is_admin       = as_administrator($member);

        $sponsordata    = $id_sponsor ? an_get_memberdata_by_id($id_sponsor) : 0;

        // AVATAR IMAGE
        $avt_img        = 'user.jpg';
        if ( $member->status != 1 ) {
            $avt_img    = 'user_notactive.jpg';
        }
        $avt_img        = $is_admin ? 'user.jpg' : $avt_img;
        // END AVATAR IMAGE

        $avatar         = '<div class="photo-wrapper '.$photo_me.'">';
        $avatar         = $is_admin ? '<div class="photo-wrapper photo-me">' : $avatar;
        if ( $sponsordata ) {
            $sponsored  = 'SPONSOR : ' . $sponsordata->username . '<br/>' . strtoupper($sponsordata->name);
            $avatar     = '<div class="photo-wrapper" title="' . $sponsored . '<br/>Join : '.date('d-M-y', strtotime($member->datecreated)).'">';
        }
        $avatar        .= '<div class="photo-content">';
        $avatar        .= '<div class="photo-image">';
        $avatar        .= '<img src="' . BE_TREE_PATH . $avt_img . '" />';
        $avatar        .= '</div>';
        $avatar        .= '</div>';
        $avatar        .= $is_admin ? '<div class="photo-name admin">' : '<div class="photo-name ' . $member->package . '">';
        $avatar        .= $member->username;
        $avatar        .= '</div>';
        $avatar        .= '<div class="photo-name2"><span>' . $member->name . '</span></div>';

        $avatar        .= an_node($member->id, false, $showct);
        $avatar        .= '</div>';

        return $avatar;
    }
}

if (!function_exists('an_node')) {
    /**
     * Get node
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @param   Boolean $new (Optional)  New Member
     * @return  Mixed, Boolean if wrong data of id member, otherwise data or node
     */
    function an_node($id_member, $new = false, $showct = false)
    {
        if(!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if(!$id_member) return false;

        $member     = an_get_memberdata_by_id($id_member);
        if(!$member || empty($member)) return false;

        if($new == true) {
            $node = '
            <div class="phone-node row">
            <div class="col-6 node-one" style="padding:0px !important">-</div>
            <div class="col-6 node-two" style="padding:0px !important">-</div>
            </div>
            ';
        } else {

            if ( $showct && $member->type == MEMBER ) {
                $tree_left          = an_count_childs($id_member, POS_LEFT, TRUE);                              // Count Result Tree Left 
                $count_left         = isset($tree_left['total_downline']) ? $tree_left['total_downline'] : 0;   // Count Downline Left 
                $pair_left          = isset($tree_left['total_pairing']) ? $tree_left['total_pairing'] : 0;     // Count Pairing Left 
                
                $tree_right         = an_count_childs($id_member, POS_RIGHT, TRUE);                             // Count Result Tree Right
                $count_right        = isset($tree_right['total_downline']) ? $tree_right['total_downline'] : 0; // Count Downline Right
                $pair_right         = isset($tree_right['total_pairing']) ? $tree_right['total_pairing'] : 0;   // Count Pairing Right

                if ( $pair_qualified = an_count_pairing_qualified($id_member) ) {
                    $pair_left      = $pair_left - $pair_qualified;
                    $pair_left      = ($pair_left < 0 ? 0 : $pair_left);
                    $pair_right     = $pair_right - $pair_qualified;
                    $pair_right     = ($pair_right < 0 ? 0 : $pair_right);
                }
                
                $node = '
                    <div class="phone-node row" style="padding:0px">
                        <div class="col-6 node-one"  style="padding:0px !important">
                            <b>L</b>: ' . $count_left . '<br />
                        </div>
                        <div class="col-6 node-two"  style="padding:0px !important">
                            <b>R</b>: ' . $count_right . '<br />
                        </div>
                    </div>
                    <div class="phone-node row" style="padding:0px">
                        <div class="col-6 node-one"  style="padding:0px !important">
                            <b>P</b> : ' . $pair_left . '<br />
                        </div>
                        <div class="col-6 node-two"  style="padding:0px !important">
                            <b>P</b> : ' . $pair_right . '<br />
                        </div>
                    </div>'; 

            } else {
                $child_left     = an_count_childs($id_member, POS_LEFT, FALSE, 'childs');   
                $child_right    = an_count_childs($id_member, POS_RIGHT, FALSE, 'childs');   

                $node = '
                    <div class="phone-node row">
                        <div class="col-6 node-one"  style="padding:0px !important">
                            L: ' . $child_left . '<br />
                        </div>
                        <div class="col-6 node-two"  style="padding:0px !important">
                            R: ' . $child_right . '<br />
                        </div>
                    </div>';
            }

        }

        return $node;
    }
}

if(!function_exists('an_count_childs')) {
    /**
     * Counts childs of member
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @param   String $position (Required)  Position Of Node, value ('kiri' or 'kanan')
     * @param   Boolean $tree (Optional)  Get Only Tree
     * @param   String $cfg (Required)  Point Of Node, value ('all' or 'childs' or 'pairing')
     * @param   Date $datecreated (Optional)  Date Join of member
     * @return  Int of child number
     */
    function an_count_childs($id_member, $position = '', $tree = true, $cfg = 'all', $datecreated = '', $equaldate = false)
    {
        $CI =& get_instance();
        return $CI->Model_Member->count_childs($id_member, $position, $tree, $cfg, $datecreated, $equaldate);
    }
}

if(!function_exists('an_count_pairing')) {
    /**
     * Counts Point Pairing of member
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @param   String $position (Required)  Position Of Node, value ('kiri' or 'kanan')
     * @param   Date $datecreated (Optional)  Date Join of member
     * @param   Boolean $equaldate (Optional)  Get Only One Day
     * @return  Int of child number
     */
    function an_count_pairing($id_member, $position = POS_LEFT, $datecreated = '', $equaldate = false)
    {
        $CI =& get_instance();
        $pair_point     = an_count_childs($id_member, $position, false, 'pairing', $datecreated, $equaldate);

        if ( $pair_qualified = an_count_pairing_qualified($id_member) ) {
            $pair_point = $pair_point - $pair_qualified;
            $pair_point = ($pair_point < 0 ? 0 : $pair_point);
        }
        return $pair_point;
    }
}

if(!function_exists('an_count_pairing_qualified')) {
    /**
     * Counts Pairing Qualified of member
     * @author  Yuda
     * @param   Int $id_member (Required)  Member ID
     * @return  Int of child number
     */
    function an_count_pairing_qualified($id_member, $count_total = true, $datecreated = '', $equal = false)
    {
        $CI =& get_instance();
        return $CI->Model_Member->count_pairing_qualified($id_member, $count_total, $datecreated, $equal);
    }
}

if ( !function_exists('an_save_pair_qualified') )
{
    /**
     * Save Point Pair of member
     * @author  Yuda
     * @param   Array   $data      (Required)  Data Pair Qualified Member
     * @return  Boolean False or True 
     */
    function an_save_pair_qualified($data) {
        $CI =& get_instance();
        return $CI->Model_Member->save_pair_qualified($data);
    }
}

if (!function_exists('an_calc_tax')) {
    /**
     * Calculate Pajak
     */
    function an_calc_tax($nominal, $npwp = '')
    {
        if (!$nominal || !is_numeric($nominal)) return 0;

        $tax_npwp       = 0;
        $tax_non_npwp   = 0;

        if ($_tax_npwp = get_option('setting_withdraw_tax_npwp')) {
            $tax_npwp   = $_tax_npwp;
        }

        if ($_tax_non_npwp = get_option('setting_withdraw_tax')) {
            $tax_non_npwp   = $_tax_non_npwp;
        }

        if (!$tax_npwp && !$tax_non_npwp) {
            return 0;
        }

        if ($npwp == '__.___.___._-___.___') {
            $npwp = '';
        }

        $npwp   = trim($npwp);
        $tax    = $tax_non_npwp;

        if (!empty($npwp)) {
            $tax = $tax_npwp;
        }

        $calc_tax = ($nominal * $tax) / 100;
        return round($calc_tax);
    }
}

if (!function_exists('an_member_pin')){
    /**
     *
     * Get member pin
     * @param   Int     $id_member  (Required)  Member ID
     * @param   String  $status     (Optional)  Status of Pin, default 'all'
     * @param   Boolean $count      (Optional)  Count PIN, default 'false'
     * @param   String  $product    (Optional)  Product of Pin, default ''
     * @return Mixed, Boolean false if invalid member id, otherwise array of member pin
     */
    function an_member_pin($id_member, $status='all', $count=true, $product=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member  = absint($id_member);
        if ( !$id_member ) return false;

        $CI =& get_instance();

        $pins    = $CI->Model_Shop->get_pins($id_member, $status, $count, $product);

        return $pins;
    }
}

if (!function_exists('an_use_pin')){
    /**
     * Use PIN
     *
     * @since 1.0.0
     * @access public
     *
     * @param int       $id_member  ID Member of PIN owner
     * @param int       $qty        Quantity PIN used.
     * @param string    $product    Product ID
     * @return boolean
     */
    function an_use_pin( $id_member, $qty, $product, $id_member_registered = 0, $datetime = '', $used_for = 'register' ) {
        if ( empty( $id_member ) || empty( $qty ) || empty( $product ) )
            return false;

        $CI =& get_instance();

        // get active pins
        // lock row of pin used so they are not used by another DB connection
        if ( ! $pin_active = $CI->Model_Shop->get_pins_with_lock( $id_member, $product, 1, $qty ) )
            return false;

        // check if active pin is sufficient
        if ( count( $pin_active ) < $qty )
            return false;

        $used_pin       = array_slice( $pin_active, 0, $qty );
        $used_pin_ids   = array();
        foreach( $used_pin as $pin ){
            $used_pin_ids[] = $pin->id;
        }

        $datetime       = $datetime ? $datetime : date('Y-m-d H:i:s');

        // update PIN status to 2 => used
        return $CI->Model_Shop->update_pin( $used_pin_ids, array(
            'id_member_register'    => $id_member,
            'id_member_registered'  => $id_member_registered,
            'status'                => 2,
            'used'                  => $used_for,
            'dateused'              => $datetime
        ));

        // trans complete
        $CI->db->trans_complete();
        return true;
    }
}


if (!function_exists('an_notification_email_template')) {
    /**
     * Get notification template
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $message
     * @return array 
     * 
     * @author Yuda
     */
    function an_notification_email_template($message = "", $title = "")
    {
        $company_name       = get_option('company_name');
        $company_name       = $company_name ? $company_name : COMPANY_NAME;
        $template_open      = '
        <style>
            pre{ background-color: transparent; color: #FFFFFF; border:none; padding: 0px 10px 10px; }
        </style>
        <body class="clean-body" style="margin: 0; padding: 20px 0px; -webkit-text-size-adjust: 100%; background-color: #F5F5F5; font-family:Roboto,Arial,Helvetica,sans-serif;">
            <div style="background-color:transparent; margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">';

        $template_header = '
            <div style="background-color:#FFFFFF; display: block;">
                <div style="width:100% !important;">
                    <div style="border:0px solid transparent; padding: 25px 10px;">
                        <div style="padding: 0px; text-align: center;">
                            <!--<img src="' . BE_IMG_PATH . 'logo.png" alt="' . $company_name . '" width="20%">-->
                            <h1 style="margin: 0px; margin-top: 10px; font-size:18px; font-weight:bold; color:#5e72e4">' . $company_name . '</h1>
                        </div>
                    </div>
                </div>
            </div>';

        $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background: linear-gradient(87deg,#17acc7 0,#1171ef 100%)!important; padding: 20px; color: #FFFFFF;">
                    ' . (empty($title) ? '' : '<div style="text-align: center;"><h3 style="font-size:18px; color:white">' . $title . '</h3><hr/></div>') . '
                    ' . (empty($message) ? '<div style="text-align: center;">Email Notifikasi ini tidak memiliki pesan</div>' : $message) . '
                </div>
            </div>';

        $template_footer = '
            <div style="background-color:#FFFFFF;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 650px; display: table-cell; vertical-align: top; width: 650px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 60%; border-top: 1px dotted #C4C4C4; height: 0px;" valign="top" width="60%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#5F5F5F; line-height:120%; padding: 10px;">
                                        <div style="font-size: 12px; line-height: 14px; color: #5F5F5F;">
                                            <p style="font-size: 12px; line-height: 16px; text-align: center; margin: 0;">
                                                <strong>' . COMPANY_NAME . ' &copy; 2021. All Right Reserved</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        $template_close = '
            <div>
        </body>';

        $template           = $template_open . $template_header . $template_body . $template_footer . $template_close;
        return $template;
    }
}

if (!function_exists('an_notification_shop_template')) {
    /**
     * Get notification template
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $message
     * @return array 
     * 
     * @author Yuda
     */
    function an_notification_shop_template($shop_order = "", $subject = "", $usertype = '', $member = null)
    {
        $CI = &get_instance();
        $CI->load->helper('shop_helper');
        $fp_active          = get_option('fp_active');
        $currency           = config_item('currency');
        $server_name        = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : DOMAIN_NAME;
        $company_name       = get_option('company_name');
        $company_name       = !empty($company_name) ? $company_name : COMPANY_NAME;
        $dateorder          = !empty($shop_order->datecreated) ? date_indo($shop_order->datecreated, 'datetime') : '';

        $title              = '
            <div style="text-align: center;">
                <p style="font-size:18px;">' . (empty($subject) ? 'Informasi Pesanan Produk' : $subject) . '</p>
                <hr>
                <p style="margin: 10px 0px 0px;">
                    <span style="font-size: 16px; font-weight:600; color: #fff;">Invoice </span>
                    <span style="font-size: 16px; font-weight:600; color: #ff8d2b;">' . $shop_order->invoice . '</span>
                </p>
                <p style="font-size: 12px; margin-top: 0px; color:#ddd">' . $dateorder . '</p>
                <hr/><br>
            </div>';

        $message            = '';
        $notif              = '';

        if ($usertype == 'stockist') {
            $text_message   = '';
            if ($shop_order->status == 0) {
                $notif = $CI->Model_Option->get_notification_by('slug', 'notification-new-order-stockist', 'email');
                $text_message   = '<p style="margin: 3px 0px;">Selamat. Ada pesanan produk dari member <b>' . $company_name . '</b>. Berikut informasinya :</p>';
            }
            if ($shop_order->status == 1) {
                $notif = $CI->Model_Option->get_notification_by('slug', 'notification-confirmation-order-stockist', 'email');
                $text_message   = '<p style="margin: 3px 0px;">Pesanan produk dari member telah di konfirmasi. Berikut informasi data pesanan member :</p>';
            }
            if ($shop_order->status == 4) {
                $notif = $CI->Model_Option->get_notification_by('slug', 'notification-cancelation-order-stockist', 'email');
                $text_message   = '<p style="margin: 3px 0px;">Pesanan member telah dibatalkan. Berikut informasi data pesanan member :</p>';
            }
            $message        = '<p style="line-height: 1.2;">Halo <b>' . $shop_order->name . '</b></p>' . $text_message;
        } else {
            if ($member) {
                $text_message   = '';
                if ($shop_order->status == 0) {
                    $notif = $CI->Model_Option->get_notification_by('slug', 'notification-new-order-member', 'email');
                    $text_message   = '<p style="margin: 3px 0px;">Terima kasih sudah berbelanja di <b>' . $company_name . '</b>. Sebagai konfirmasi, berikut data Pesanan anda :</p>';
                }
                if ($shop_order->status == 1) {
                    $notif = $CI->Model_Option->get_notification_by('slug', 'notification-confirmation-order-member', 'email');
                    $text_message   = '<p style="margin: 3px 0px;">Informasi. Pembayaran Anda atas pesanan produk berikut telah di terima.</p>';
                }
                if ($shop_order->status == 4) {
                    $notif = $CI->Model_Option->get_notification_by('slug', 'notification-cancelation-order-member', 'email');
                    $text_message   = '<p style="margin: 3px 0px;">Pesanan telah dibatalkan. Berikut informasi data pesanan anda :</p>';
                }
                
                if( $shop_order->as_reseller == 1 ){
                    $message        = '<p style="line-height: 1.2;">Halo <b>' . $member->name . ' (' . $member->username . ')</b></p>' . $text_message;
                }else{
                    $message        = '<p style="line-height: 1.2;">Halo <b>' . $shop_order->name_consumer . '</b></p>' . $text_message;
                }
            }
        }

        $product_detail     = '
        <table class="table no-wrap table-responsive" style="margin-bottom: 30px;width: 100%;line-height: inherit;text-align: left;">
            <thead>
                <tr class="heading">
                    <th colspan="2" style="background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Produk</th>
                    <th style="width:30%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>';

        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $idMaster       = $row['id'];
                $image          = '';
                if ($get_data_product = an_products($idMaster)) {
                    $image      = $get_data_product->image;
                }
                $img_src        = an_product_image($image);

                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;

                if ($price > $price_cart) {
                    //$price_prod = '( <s style="font-size: 11px">' . an_accounting($price) . '</s> <span style="color:#fb6340;font-size: 11px">' . an_accounting($price_cart, $currency) . '</span> )';
                    $price_prod = an_accounting($price_cart, $currency);
                } else {
                    $price_prod = an_accounting($price_cart, $currency);
                }

                $product_detail     .= '
                    <tr>
                        <td style="width:70px; vertical-align:top; padding-top:5px">
                            <img src="' . $img_src . '" style="width: 70px;float: left;">
                        </td>
                        <td style="text-align: left;text-transform: capitalize;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                            <span style="font-size: 12px;font-weight:600; margin-bottom: 3px;display: block;">' . $product_name . '</span>
                            <span style="font-size: 10px;display:block;margin-bottom:2px">
                                BV: ' . an_accounting($bv) . '
                            </span>
                            <span style="font-size: 10px;display:block;margin-bottom:2px">
                                Harga: ' . $price_prod . '
                            </span>
                            <span style="font-size: 10px; font-weight:600;">Qty: ' . $qty . '</span>
                        </td>
                        <td class="text-center" style="text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . an_accounting($subtotal)  . '
                        </td>
                    </tr>
                ';
            }
        }

        $uniquecode         = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);
        $cfg_pay_method     = config_item('payment_method');
        $payment_method     = isset($cfg_pay_method[$shop_order->payment_method]) ? $cfg_pay_method[$shop_order->payment_method] : $shop_order->payment_method;
        $product_detail     .= '
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Subtotal</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . an_accounting($shop_order->subtotal) . '
                    </td>
                </tr>
                ' . (($shop_order->unique) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Kode Unik</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . ($uniquecode) . '
                        </td>
                    </tr>' : '') . '
                ' . (($shop_order->shipping) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Biaya Pengiriman</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . an_accounting($shop_order->shipping) . '
                        </td>
                    </tr>' : '') . '
                ' . (($shop_order->discount) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">
                            ' . lang('discount') . ' ' . ($shop_order->voucher ? ' (<span style="font-size:10px; color:#5e72e4">' . $shop_order->voucher . '</span>)' : '') . '
                        </td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . an_accounting($shop_order->discount) . '
                        </td>
                    </tr>' : '') . '
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">' . lang('payment_method') . '</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . $payment_method . '
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:bold; color:#666; font-size:15px">
                        ' . lang('total_payment') . '
                    </td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#fb6340; font-size:15px">
                        ' . an_accounting($shop_order->total_payment, $currency) . '
                    </td>
                </tr>
            </tbody>
        </table>';

        // Information Shipping Address
        $address            = ucwords(strtolower($shop_order->address_consumer)) . ', ' . $shop_order->village_consumer . br();
        $address           .= 'Kec'. $shop_order->subdistrict_consumer . ' ' . $shop_order->district_consumer .br();
        $address           .= $shop_order->province_consumer;
        $address           .= ($shop_order->postcode_consumer) ? ' (' . $shop_order->postcode_consumer . ')' : '';

        // shipping method
        $shipping_title     = 'Alamat Pengiriman';
        $_shipping          = '';
        if ( $shop_order->shipping_method == 'ekspedisi' ) {
            $_shipping  = 'Jasa Ekspedisi / Pengiriman';
            if ( $shop_order->courier ) {
                $_shipping  = strtoupper($shop_order->courier);
                if ( $shop_order->service ) {
                    $_shipping  .= ' (' . strtoupper($shop_order->service) .')';
                }
            }
        }
        if ( $shop_order->shipping_method == 'pickup' ) {
            $shipping_title = 'Alamat Penagihan';
            $_shipping      = 'Pickup';
        }

        $username_member    = '';
        if ( $usertype == 'stockist' && $shop_order->id_stockist > 0 ) {
            if ( $memberdata = an_get_memberdata_by_id($shop_order->id_member) ) {
                $username_member = '
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("username") . '</td>
                    <td style="width: 2%x;">:</td>
                    <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . strtolower($memberdata->username) . '
                    </td>
                </tr>';
            }
        }

        $shipping_detail    = '
        <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
            <tr class="heading">
                <td colspan="3" style="background: #eee;border-bottom: 1px solid #ddd;padding: 10px;"><b>Metode Pengiriman </b> : '. $_shipping .'</td>
            </tr>
            <tr class="heading">
                <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">' . $shipping_title . '</th>
            </tr>
            '.$username_member.'
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . ucwords(strtolower($shop_order->name_consumer)) . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_hp") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->phone_consumer . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->email_consumer . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_alamat") . '</td>
                <td style="width: 2%x;vertical-align: top;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $address . '
                </td>
            </tr>
        </table>';

        $info_stockist      = '';
        $view_stockist      = ( $shop_order->type_order == 'member_order' ) ? true : false;
        if ( $usertype == 'member' && $view_stockist && $shop_order->id_stockist ) {
            if ( $stockistdata = an_get_memberdata_by_id($shop_order->id_stockist) ) {
                $info_stockist = '
                <table class="table" style="margin-top: 30px;margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                    <tr class="heading">
                        <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Information Stockist</th>
                    </tr>
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                        <td style="width: 2%x;">:</td>
                        <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . ucwords(strtolower($stockistdata->name)) . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_hp") . '</td>
                        <td style="width: 2%x;">:</td>
                        <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . $stockistdata->phone . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                        <td style="width: 2%x;">:</td>
                        <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . $stockistdata->email . '
                        </td>
                    </tr>
                </table>';
            }
        }

        // Information Billing Account
        $billing_detail     = '';
        if ( $shop_order->status == 0 && $shop_order->id_stockist == 0) {
            $bill_bank          = '';
            $bill_no            = get_option('company_bill');
            $bill_name          = get_option('company_bill_name');
            if ($company_bank = get_option('company_bank')) {
                if ($getBank = an_banks($company_bank)) {
                    $bill_bank = $getBank->nama;
                }
            }

            if ($bill_no) {
                $bill_format = '';
                $arr_bill    = str_split($bill_no, 4);
                foreach ($arr_bill as $no) {
                    $bill_format .= $no . ' ';
                }
                $bill_no = $bill_format ? $bill_format : $bill_no;;
            }
            
            if( $fp_active == 1 ){
                // Get Faspay Trx
                $trx_id = 0;
                $payment_redirect_url = '';
                $faspay_trx = $CI->Model_Faspay->get_by(array('shop_order_id' => $shop_order->id));
                if( $faspay_trx || !empty($faspay_trx) ){
                    $trx_id = $faspay_trx->trx_id;
                    $trx_response = ( !empty($faspay_trx->response) ? json_decode($faspay_trx->response) : '' );
                    $payment_redirect_url = $trx_response->redirect_url;
                }
                
                $payment_channel_txt = 'TIDAK TERDAFTAR';
                if( $shop_order->payment_method == 'transfer' ){
                    // VA Channel
                    if( $shop_order->payment_channel == 800 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA BRI'; }
                    if( $shop_order->payment_channel == 801 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA BNI'; }
                    if( $shop_order->payment_channel == 702 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA BCA'; }
                    if( $shop_order->payment_channel == 825 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA CIMB Niaga'; }
                    if( $shop_order->payment_channel == 708 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA Danamon'; }
                    if( $shop_order->payment_channel == 802 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA Mandiri'; }
                    if( $shop_order->payment_channel == 408 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA Maybank'; }
                    if( $shop_order->payment_channel == 402 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA Permata'; }
                    if( $shop_order->payment_channel == 818 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'VA Sinarmas'; }
                    
                    // Internet Banking Channel
                    if( $shop_order->payment_channel == 405 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'BCA Klikpay'; }
                    if( $shop_order->payment_channel == 701 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'Danamon Online'; }
                    if( $shop_order->payment_channel == 814 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'Maybank2U'; }
                    if( $shop_order->payment_channel == 402 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'Permata Net'; }
                    
                    // E-Money
                    if( $shop_order->payment_channel == 302 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'Linkaja'; }
                    if( $shop_order->payment_channel == 812 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'OVO'; }
                    
                    // Online Credit
                    if( $shop_order->payment_channel == 807 && $shop_order->payment_channel_type == 1 ){ $payment_channel_txt = 'Akulaku'; }
                    
                    // Retail Payment
                    if( $shop_order->payment_channel == 707 && $shop_order->payment_channel_type == 2 ){ $payment_channel_txt = 'Alfamart'; }
                    
                    $billing_detail     = '
                    <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                        <tr class="heading">
                            <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Channel Pembayaran</th>
                        </tr>
                        <tr class="item">
                            <td style="width: 30%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">Channel</td>
                            <td style="width: 2%px;">:</td>
                            <td style="width: 68%; padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . $payment_channel_txt . '
                            </td>
                        </tr>
                        <tr class="item">
                            <td style="width: 30%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">No. Transaksi Faspay</td>
                            <td style="width: 2%px;">:</td>
                            <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . $trx_id . '
                            </td>
                        </tr>
                    </table>';
                    
                    $xpired_payment = date('Y-m-d H:i:s', strtotime ($shop_order->datecreated . "+1 hour"));
                    if( strtotime(date('Y-m-d H:i:s')) > strtotime($xpired_payment) ){
                        $billing_detail    .= '
                        <div class="info-box" style="padding: 20px;margin: auto;background: #f5365c;color: white; text-align: center;">
                            Status Invoice ini <strong>EXPIRED</strong> karena sudah melewati batas waktu pembayaran
                        </div>';
                    }else{
                        $billing_detail    .= '
                        <div class="info-box" style="padding: 20px;margin: auto;background: #c6a457;color: white;">
                            Silahkan lakukan <strong>Pembayaran sebesar ' . an_accounting($shop_order->total_payment, $currency) . '</strong> melalui link di bawah!.<br />
                            Waktu pembayaran akan berakhir pada '.$xpired_payment.'<br />
                            <a href="'.$payment_redirect_url.'">Lakukan Pembayaran ATM / Virtual Account</a>
                        </div>';
                    }
                    
                }else{
                    $billing_detail    .= '
                    <div class="info-box" style="padding: 20px;margin: auto;background: #c6a457;color: white;">
                        Silahkan lakukan <strong>Pembayaran sebesar ' . an_accounting($shop_order->total_payment, $currency) . '</strong> secara TUNAI ke Perusahaan!
                    </div>';
                }
            }else{
                $billing_detail     = '
                <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                    <tr class="heading">
                        <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Rekening Perusahaan</th>
                    </tr>
    
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">Bank</td>
                        <td style="width: 2%px;">:</td>
                        <td style="width: 78%; padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . strtoupper($bill_bank) . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">No. Rekening</td>
                        <td style="width: 2%px;">:</td>
                        <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . $bill_no . '
                        </td>
                    </tr>
                    <tr class="item">
                        <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">Nama Rekening</td>
                        <td style="width: 2%px;">:</td>
                        <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . ucwords(strtolower($bill_name)) . '
                        </td>
                    </tr>
                </table>';
                
                if ($shop_order->status == 0) {
                    if( $shop_order->payment_method == 'transfer' ){
                        $billing_detail    .= '
                        <div class="info-box" style="padding: 20px;margin: auto;background: #2e6694;color: white;">
                            Silahkan Transfer <strong>Pembayaran sebasar ' . an_accounting($shop_order->total_payment, $currency) . '</strong> Ke Rekening Perusahaan.
                        </div>';
                    }else{
                        $billing_detail    .= '
                        <div class="info-box" style="padding: 20px;margin: auto;background: #2e6694;color: white;">
                            Silahkan Transfer <strong>Pembayaran sebasar ' . an_accounting($shop_order->total_payment, $currency) . '</strong> secara TUNAI ke Perusahaan!
                        </div>';
                    }
                }
            }
        }
        
        // Reseller Detail
        $reseller_detail = '';
        if( $shop_order->status == 1 ){
            if ( $memberdata = an_get_memberdata_by_id($shop_order->id_member) ) {
                $reseller_detail = '
                <div style="padding: 10px 20px; color: #333;">
                    <p>Akun Reseller Anda sudah aktif dan Anda dapat melanjutkan ke halaman Reseller Area. Berikut detail akun Reseller Anda :</p>
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                        <tr class="heading">
                            <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Akun Reseller</th>
                        </tr>
                        <tr class="item">
                            <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                            <td style="width: 2%x;">:</td>
                            <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . ucwords(strtolower($memberdata->name)) . '
                            </td>
                        </tr>
                        <tr class="item">
                            <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("username") . '</td>
                            <td style="width: 2%x;">:</td>
                            <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . strtolower($memberdata->username) . '
                            </td>
                        </tr>
                        <tr class="item">
                            <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_hp") . '</td>
                            <td style="width: 2%x;">:</td>
                            <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . $memberdata->phone . '
                            </td>
                        </tr>
                        <tr class="item">
                            <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                            <td style="width: 2%x;">:</td>
                            <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                                ' . $memberdata->email . '
                            </td>
                        </tr>
                    </table>
                </div>';
            }
        }

        $template_style     = '
        <style>
            * { font-size: 14px; }

            @media only screen and (max-width:480px) {
                table td.mobile-center {
                    width: 100% !important;
                    display: block !important;
                    text-align: left !important;
                }

                table td.title.mobile-center {
                    text-align: center !important
                }

                .mobile-hide {
                    display: none;
                }

                .mobile-text-left {
                    text-align: left !important;
                }
            }

            .no-padding {
                padding: unset !important;
            }

            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }

            table.no-wrap td {
                white-space: unset;
            }

            table.table tr.item td {
                font-size: 13px;
            }
            pre{ background-color: transparent; color: #FFFFFF; border:none; padding: 0px 10px 10px; }
        </style>';

        $template_open      = '
        <body class="clean-body" style="margin: 0; padding: 20px 0px; -webkit-text-size-adjust: 100%; background-color: #F5F5F5; font-family:Roboto,Arial,Helvetica,sans-serif;">
            <div style="background-color:transparent; margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">';

        $template_header = '
            <div style="background-color:#FFFFFF; display: block;">
                <div style="width:100% !important;">
                    <div style="border:0px solid transparent; padding: 25px 10px 10px;">
                        <div style="padding: 0px; text-align: center;">
                            <!-- <img src="' . BE_IMG_PATH . 'logo.png" alt="' . $company_name . '" width="20%"> -->
                            <h1 style="margin: 0px; margin-top: 10px; font-size:18px; font-weight:bold; color:#5e72e4">' . $company_name . '</h1>
                        </div>
                    </div>
                </div>
            </div>';

        $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background: linear-gradient(87deg,#17acc7 0,#1171ef 100%)!important; padding: 20px 20px 0px; color: #FFFFFF;">
                    ' . $title . '
                </div>
                <div style="padding: 10px 20px; margin-top: 30px; color: #333;">
                    ' . $message . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $product_detail . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $shipping_detail . '
                    ' . $info_stockist . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $billing_detail . '
                </div>
                '.$reseller_detail.'
            </div>';

        $template_footer = '
            <div style="background-color:#FFFFFF;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 650px; display: table-cell; vertical-align: top; width: 650px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 60%; border-top: 1px dotted #C4C4C4; height: 0px;" valign="top" width="60%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#5F5F5F; line-height:120%; padding: 10px;">
                                        <div style="font-size: 12px; line-height: 14px; color: #5F5F5F;">
                                            <p style="font-size: 12px; line-height: 16px; text-align: center; margin: 0; font-weight:600;">
                                                ' . COMPANY_NAME . ' &copy; 2021. All Right Reserved
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        $template_close = '
            <div>
        </body>';

        $template           = $template_style . $template_open . $template_header . $template_body . $template_footer . $template_close;

        if ($notif) {
            $content = $notif->content;
            $content = str_replace("%name%",                    ($member ? $member->name : ''), $content);
            $content = str_replace("%memberuid%",               ($member ? $member->username : ''), $content);
            $content = str_replace('%customer_name%',           ($shop_order ? $shop_order->name : ''), $content);
            $content = str_replace("%order_detail%",            $product_detail, $content);
            $content = str_replace("%shipping_detail%",         $shipping_detail, $content);
            $content = str_replace("%stockist_information%",    $info_stockist, $content);

            if ( $shop_order->id_stockist == 0 ) {
                $content = str_replace("%billing_detail%",      $billing_detail, $content);
            } else {
                $content = str_replace("%billing_detail%",      $info_stockist, $content);
            }

            $total_transfer     = $shop_order->total_payment;
            $content = str_replace("%total_transfer%", ($total_transfer ? an_accounting($total_transfer, $currency) : ''), $content);

            $title              = '
            <div style="text-align: center;">
                <p style="font-size:18px;">' . (empty($subject) ? 'Informasi Pesanan Produk' : $subject) . '</p>
                <hr>
                <p style="margin: 10px 0px 0px;">
                    <span style="font-size: 16px; font-weight:600; color: #fff;">Invoice </span>
                    <span style="font-size: 16px; font-weight:600; color: #ff8d2b;">' . $shop_order->invoice . '</span>
                </p>
                <p style="font-size: 12px; margin-top: 0px; color:#ddd">' . $dateorder . '</p>
                <hr/><br>
            </div>';

            $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background: linear-gradient(87deg,#17acc7 0,#1171ef 100%)!important; padding: 20px 20px 0px; color: #FFFFFF;">
                    ' . $title . '
                </div>
                <div style="padding: 10px 20px; margin-top: 30px; color: #333;">
                    ' . $content . '
                </div>
            </div>';

            $template           = $template_style . $template_open . $template_header . $template_body . $template_footer . $template_close;
        }
        return $template;
    }
}

if (!function_exists('an_notification_confirm_shipping_template')) {
    /**
     * Get notification template
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $message
     * @return array 
     * 
     * @author Yuda
     */
    function an_notification_confirm_shipping_template($shop_order = "", $subject = "")
    {
        $CI = &get_instance();
        $CI->load->helper('shop_helper');
        $currency           = config_item('currency');
        $server_name        = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : DOMAIN_NAME;
        $company_name       = get_option('company_name');
        $company_name       = !empty($company_name) ? $company_name : COMPANY_NAME;
        $dateorder          = !empty($shop_order->datecreated) ? date_indo($shop_order->datecreated, 'datetime') : '';

        $title              = '
            <div style="text-align: center;">
                <p style="font-size:18px;">' . (empty($subject) ? 'Informasi Konfirmasi Pengiriman Produk' : $subject) . '</p>
                <hr>
                <p style="margin: 10px 0px 0px;">
                    <span style="font-size: 16px; font-weight:600; color: #fff;">Invoice </span>
                    <span style="font-size: 16px; font-weight:600; color: #ff8d2b;">' . $shop_order->invoice . '</span>
                </p>
                <p style="font-size: 12px; margin-top: 0px; color:#ddd">' . $dateorder . '</p>
                <hr/><br>
            </div>';

        $message            = '';
        $notif              = '';
        
        

        $text_message   = '';
        if ($shop_order->status == 1) {
            $notif = $CI->Model_Option->get_notification_by('slug', 'notification-confirmation-order-member', 'email');
            $text_message   = '<p style="margin: 3px 0px;">Pesanan Produk Anda telah dikirimkan. Berikut informasi data Pesanan anda :</p>';
        }
        
        if( $shop_order->as_reseller == 1 ){
            if ( $memberdata = an_get_memberdata_by_id($shop_order->id_member) ){
                $message    = '<p style="line-height: 1.2;">Halo <b>' . $memberdata->name . ' (' . $memberdata->username . ')</b></p>' . $text_message;
            }else{
                $message        = '<p style="line-height: 1.2;">Halo <b>' . $shop_order->name_consumer . '</b></p>' . $text_message;
            }
        }else{
            $message        = '<p style="line-height: 1.2;">Halo <b>' . $shop_order->name_consumer . '</b></p>' . $text_message;
        }

        $product_detail     = '
        <table class="table no-wrap table-responsive" style="margin-bottom: 30px;width: 100%;line-height: inherit;text-align: left;">
            <thead>
                <tr class="heading">
                    <th colspan="2" style="background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Produk</th>
                    <th style="width:30%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>';

        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $idMaster       = $row['id'];
                $image          = '';
                if ($get_data_product = an_products($idMaster)) {
                    $image      = $get_data_product->image;
                }
                $img_src        = an_product_image($image);

                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $bv             = isset($row['bv']) ? $row['bv'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_cart     = isset($row['price_cart']) ? $row['price_cart'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price_cart;

                if ($price > $price_cart) {
                    $price_prod = '( <s style="font-size: 11px">' . an_accounting($price) . '</s> <span style="color:#fb6340;font-size: 11px">' . an_accounting($price_cart, $currency) . '</span> )';
                } else {
                    $price_prod = an_accounting($price_cart, $currency);
                }

                $product_detail     .= '
                    <tr>
                        <td style="width:70px; vertical-align:top; padding-top:5px">
                            <img src="' . $img_src . '" style="width: 70px;float: left;">
                        </td>
                        <td style="text-align: left;text-transform: capitalize;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                            <span style="font-size: 12px;font-weight:600; margin-bottom: 3px;display: block;">' . $product_name . '</span>
                            <span style="font-size: 10px;display:block;margin-bottom:2px">
                                BV: ' . an_accounting($bv) . '
                            </span>
                            <span style="font-size: 10px;display:block;margin-bottom:2px">
                                Harga: ' . $price_prod . '
                            </span>
                            <span style="font-size: 10px; font-weight:600;">Qty: ' . $qty . '</span>
                        </td>
                        <td class="text-center" style="text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . an_accounting($subtotal)  . '
                        </td>
                    </tr>
                ';
            }
        }

        $uniquecode         = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);
        $cfg_pay_method     = config_item('payment_method');
        $payment_method     = isset($cfg_pay_method[$shop_order->payment_method]) ? $cfg_pay_method[$shop_order->payment_method] : $shop_order->payment_method;
        $product_detail     .= '
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Subtotal</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . an_accounting($shop_order->subtotal) . '
                    </td>
                </tr>
                ' . (($shop_order->unique) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Kode Unik</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . ($uniquecode) . '
                        </td>
                    </tr>' : '') . '
                ' . (($shop_order->shipping) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Biaya Pengiriman</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . an_accounting($shop_order->shipping) . '
                        </td>
                    </tr>' : '') . '
                ' . (($shop_order->discount) ? '
                    <tr>
                        <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">
                            ' . lang('discount') . ' ' . ($shop_order->voucher ? ' (<span style="font-size:10px; color:#5e72e4">' . $shop_order->voucher . '</span>)' : '') . '
                        </td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . an_accounting($shop_order->discount) . '
                        </td>
                    </tr>' : '') . '
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">' . lang('payment_method') . '</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . $payment_method . '
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:bold; color:#666; font-size:15px">
                        ' . lang('total_payment') . '
                    </td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#fb6340; font-size:15px">
                        ' . an_accounting($shop_order->total_payment, $currency) . '
                    </td>
                </tr>
            </tbody>
        </table>';

        // Information Shipping Address
        $address            = ucwords(strtolower($shop_order->address)) . ', ' . $shop_order->village . br();
        $address           .= 'Kec'. $shop_order->subdistrict . ' ' . $shop_order->district .br();
        $address           .= $shop_order->province;
        $address           .= ($shop_order->postcode) ? ' (' . $shop_order->postcode . ')' : '';

        // shipping method
        $shipping_title     = 'Alamat Pengiriman';
        $_shipping          = '';
        if ( $shop_order->shipping_method == 'ekspedisi' ) {
            $_shipping  = 'Jasa Ekspedisi / Pengiriman';
            if ( $shop_order->courier ) {
                $_shipping  = strtoupper($shop_order->courier);
                if ( $shop_order->service ) {
                    $_shipping  .= ' (' . strtoupper($shop_order->service) .')';
                }
            }
        }
        if ( $shop_order->shipping_method == 'pickup' ) {
            $shipping_title = 'Alamat Penagihan';
            $_shipping      = 'Pickup';
        }

        $shipping_detail    = '
        <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
            <tr class="heading">
                <td colspan="3" style="background: #eee;border-bottom: 1px solid #ddd;padding: 10px;"><b>Metode Pengiriman </b> : '. $_shipping .'</td>
            </tr>
            <tr class="heading">
                <td colspan="3" style="background: #eee;border-bottom: 1px solid #ddd;padding: 10px;"><b>Nomor RESI </b> : <span style="color:#fb6340;">'. $shop_order->resi .'</span></td>
            </tr>
            <tr class="heading">
                <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">' . $shipping_title . '</th>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . ucwords(strtolower($shop_order->name)) . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_hp") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->phone . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->email . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_alamat") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $address . '
                </td>
            </tr>
        </table>';

        // Information Billing Account
        $billing_detail     = '';
        if ( $shop_order->status == 0 && $shop_order->id_stockist == 0) {
            $bill_bank          = '';
            $bill_no            = get_option('company_bill');
            $bill_name          = get_option('company_bill_name');
            if ($company_bank = get_option('company_bank')) {
                if ($getBank = an_banks($company_bank)) {
                    $bill_bank = $getBank->nama;
                }
            }

            if ($bill_no) {
                $bill_format = '';
                $arr_bill    = str_split($bill_no, 4);
                foreach ($arr_bill as $no) {
                    $bill_format .= $no . ' ';
                }
                $bill_no = $bill_format ? $bill_format : $bill_no;;
            }

            $billing_detail     = '
            <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                <tr class="heading">
                    <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Rekening Perusahaan</th>
                </tr>

                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">Bank</td>
                    <td style="width: 2%px;">:</td>
                    <td style="width: 78%; padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . strtoupper($bill_bank) . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">No. Rekening</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . $bill_no . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">Nama Rekening</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . ucwords(strtolower($bill_name)) . '
                    </td>
                </tr>
            </table>';

            if ($shop_order->status == 0) {
                $billing_detail    .= '
                <div class="info-box" style="padding: 20px;margin: auto;background: #2e6694;color: white;">
                    Silahkan Transfer <strong>Pembayaran sebasar ' . an_accounting($shop_order->total_payment, $currency) . '</strong> Ke Rekening Perusahaan.
                </div>';
            }
        }
        
        $template_style     = '
        <style>
            * { font-size: 14px; }

            @media only screen and (max-width:480px) {
                table td.mobile-center {
                    width: 100% !important;
                    display: block !important;
                    text-align: left !important;
                }

                table td.title.mobile-center {
                    text-align: center !important
                }

                .mobile-hide {
                    display: none;
                }

                .mobile-text-left {
                    text-align: left !important;
                }
            }

            .no-padding {
                padding: unset !important;
            }

            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }

            table.no-wrap td {
                white-space: unset;
            }

            table.table tr.item td {
                font-size: 13px;
            }
            pre{ background-color: transparent; color: #FFFFFF; border:none; padding: 0px 10px 10px; }
        </style>';

        $template_open      = '
        <body class="clean-body" style="margin: 0; padding: 20px 0px; -webkit-text-size-adjust: 100%; background-color: #F5F5F5; font-family:Roboto,Arial,Helvetica,sans-serif;">
            <div style="background-color:transparent; margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">';

        $template_header = '
            <div style="background-color:#FFFFFF; display: block;">
                <div style="width:100% !important;">
                    <div style="border:0px solid transparent; padding: 25px 10px 10px;">
                        <div style="padding: 0px; text-align: center;">
                            <!-- <img src="' . BE_IMG_PATH . 'logo.png" alt="' . $company_name . '" width="20%"> -->
                            <h1 style="margin: 0px; margin-top: 10px; font-size:18px; font-weight:bold; color:#5e72e4">' . $company_name . '</h1>
                        </div>
                    </div>
                </div>
            </div>';

        $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background: linear-gradient(87deg,#17acc7 0,#1171ef 100%)!important; padding: 20px 20px 0px; color: #FFFFFF;">
                    ' . $title . '
                </div>
                <div style="padding: 10px 20px; margin-top: 30px; color: #333;">
                    ' . $message . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $product_detail . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $shipping_detail . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $billing_detail . '
                </div>
            </div>';

        $template_footer = '
            <div style="background-color:#FFFFFF;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 650px; display: table-cell; vertical-align: top; width: 650px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 60%; border-top: 1px dotted #C4C4C4; height: 0px;" valign="top" width="60%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#5F5F5F; line-height:120%; padding: 10px;">
                                        <div style="font-size: 12px; line-height: 14px; color: #5F5F5F;">
                                            <p style="font-size: 12px; line-height: 16px; text-align: center; margin: 0; font-weight:600;">
                                                ' . COMPANY_NAME . ' &copy; 2021. All Right Reserved
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        $template_close = '
            <div>
        </body>';

        $template           = $template_style . $template_open . $template_header . $template_body . $template_footer . $template_close;

        if ($notif) {
            $content = $notif->content;
            $content = str_replace("%name%",                    ($member ? $member->name : ''), $content);
            $content = str_replace("%memberuid%",               ($member ? $member->username : ''), $content);
            $content = str_replace('%customer_name%',           ($shop_order ? $shop_order->name : ''), $content);
            $content = str_replace("%order_detail%",            $product_detail, $content);
            $content = str_replace("%shipping_detail%",         $shipping_detail, $content);
            $content = str_replace("%stockist_information%",    $info_stockist, $content);

            if ( $shop_order->id_stockist == 0 ) {
                $content = str_replace("%billing_detail%",      $billing_detail, $content);
            } else {
                $content = str_replace("%billing_detail%",      $info_stockist, $content);
            }

            $total_transfer     = $shop_order->total_payment;
            $content = str_replace("%total_transfer%", ($total_transfer ? an_accounting($total_transfer, $currency) : ''), $content);

            $title              = '
            <div style="text-align: center;">
                <p style="font-size:18px;">' . (empty($subject) ? 'Informasi Pesanan Produk' : $subject) . '</p>
                <hr>
                <p style="margin: 10px 0px 0px;">
                    <span style="font-size: 16px; font-weight:600; color: #fff;">Invoice </span>
                    <span style="font-size: 16px; font-weight:600; color: #ff8d2b;">' . $shop_order->invoice . '</span>
                </p>
                <p style="font-size: 12px; margin-top: 0px; color:#ddd">' . $dateorder . '</p>
                <hr/><br>
            </div>';

            $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background: linear-gradient(87deg,#17acc7 0,#1171ef 100%)!important; padding: 20px 20px 0px; color: #FFFFFF;">
                    ' . $title . '
                </div>
                <div style="padding: 10px 20px; margin-top: 30px; color: #333;">
                    ' . $content . '
                </div>
            </div>';

            $template           = $template_style . $template_open . $template_header . $template_body . $template_footer . $template_close;
        }
        return $template;
    }
}

// Function for Generate Fake Member
if (!function_exists('an_generate_member')) {
    /**
     * Generate Fake Member
     * @author  Saddam
     * @param   Int     $sponsor_id         (Required)  ID Sponsor
     * @param   Int     $length             (Required)  Generate Amount
     * @return selft function 
     */
    function an_generate_member($sponsor_id, $length = 1, $debug = false, $username = '')
    {
        include APPPATH . '/third_party/faker/autoload.php';
        $CI = &get_instance();

        $faker = Faker\Factory::create('id_ID');
        $faker->seed($length);

        foreach (range(1, $length, 1) as $number) {
            $data_address                           = array();
            $gender                                 = $faker->randomElement(array('M', 'F'));
            // -------------------------------------------------------
            // Get Country Data
            // -------------------------------------------------------
            $country                                = 'IDN';
            $data_address['country']                = $country;
            // -------------------------------------------------------
            // Get Province Data
            // -------------------------------------------------------
            $list_province                          = an_provinces();
            $province_change                        = $faker->randomElement($list_province);
            $province_code                          = isset($province_change->province_code) ? $province_change->province_code : $province_change->id;
            $data_address['province']               = $province_change;

            // -------------------------------------------------------
            // Get District Data
            // ------------------------------------------------------- 
            $list_district                          = an_districts_by_province($province_change->id, '');
            $district_change                        = $faker->randomElement($list_district);
            $district_name                          = $district_change->district_type . ' ' . $district_change->district_name;
            $district_code                          = isset($district_change->district_code) ? $district_change->district_code : $district_change->id;
            $data_address['district']               = $district_change;


            // -------------------------------------------------------
            // Get Sub District Data
            // ------------------------------------------------------- 
            $list_subdistrict                       = an_subdistricts_by_district($district_change->id);
            $subdistrict_change                     = $faker->randomElement($list_subdistrict);
            $data_address['subdistrict']            = $subdistrict_change;

            // -------------------------------------------------------
            // Get Village Data
            // ------------------------------------------------------- 
            $village                                = strtoupper($subdistrict_change->subdistrict_name);
            $data_address['village']                = $village;

            // -------------------------------------------------------
            // Get Bank Data
            // ------------------------------------------------------- 
            $list_bank                              = an_banks();
            $bank_change                            = $faker->randomElement($list_bank);
            $bank                                   = $bank_change->nama;

            // -------------------------------------------------------
            // Get Sponsor Data
            // ------------------------------------------------------- 
            $sponsordata                            = $CI->Model_Member->get_memberdata($sponsor_id);

            // -------------------------------------------------------
            // Get Upline Data
            // ------------------------------------------------------- 
            $uplinedata                             = an_upline_available($sponsor_id);

            // -------------------------------------------------------
            // Get Position
            // ------------------------------------------------------- 
            $uplinedata                             = an_upline_available($sponsor_id);
            if ( !$uplinedata ) {
                continue;
            }
            $upline_id                              = $uplinedata->id;
            $position_node                          = an_check_node($upline_id);
            if ( $position_node ) {
                $position                           = ( count($position_node) > 1 ) ? POS_LEFT : $position_node[0];
            } else {
                continue;
            }

            // -------------------------------------------------------
            // Get General Data
            // ------------------------------------------------------- 
            $name                   = $faker->name($gender == 'M' ? 'male' : 'female');
            $pob                    = $faker->city;
            $dateofbirth            = date('Y-m-d', strtotime('1992-01-04'));
            $marital                = $faker->randomElement(array('married', 'single'));
            $idcard_type            = 'KTP';
            $idcard                 = $faker->nik();
            $npwp                   = null;
            $address                = $faker->address;
            $email                  = $faker->unique()->safeEmail;
            $phone                  = $faker->unique()->phoneNumber;
            $phone_home             = null;
            $phone_office           = null;
            $bill                   = $faker->randomNumber(5, false);
            $bill_name              = strtoupper($name);
            $datetime               = date('Y-m-d H:i:s');

            $emergency_name         = $faker->name($gender == 'M' ? 'male' : 'female');
            $emergency_phone        = $faker->unique()->phoneNumber;
            $emergency_relationship = $faker->randomElement(array('Kaka', 'Adik', 'Saudara Perempuan', 'Saurdara Laki-Laki', 'Ayah', 'Ibu'));

            $username               = $username ? $username : $sponsordata->username;
            $username               = $username;
            $generate_username      = an_generate_username_unique($username, 0, '_');
            $package                = MEMBER_BASIC;
            $m_status               = 1;

            // Data Member
            $data_member            = array(
                'username'              => $generate_username,
                'password'              => an_password_hash('123qwe'),
                'password_pin'          => an_encrypt('123qwe'),
                'type'                  => MEMBER,
                'package'               => $package,
                'sponsor'               => $sponsor_id,
                'parent'                => $upline_id,
                'position'              => $position,
                'name'                  => $name,
                'pob'                   => $pob,
                'dob'                   => $dateofbirth,
                'gender'                => $gender,
                'marital'               => $marital,
                'idcard_type'           => $idcard_type,
                'idcard'                => $idcard,
                'npwp'                  => $npwp,
                'country'               => $country,
                'province'              => $province_change->id,
                'district'              => $district_change->id,
                'subdistrict'           => $subdistrict_change->id,
                'village'               => $subdistrict_change->subdistrict_name,
                'address'               => $address,
                'email'                 => $email,
                'phone'                 => $phone,
                'phone_home'            => $phone_home,
                'phone_office'          => $phone_office,
                'bank'                  => $bank_change->id,
                'bill'                  => $bill,
                'bill_name'             => $bill_name,
                'emergency_name'        => $emergency_name,
                'emergency_relationship'=> $emergency_relationship,
                'emergency_phone'       => $emergency_phone,
                'status'                => $m_status,
                'total_omzet'           => 0,
                'uniquecode'            => 0,
                'datecreated'           => $datetime,
            );

            // Data Member Confirm
            $data_member_confirm    = array(
                'id_member'         => $sponsordata->id,
                'member'            => $sponsordata->username,
                'id_sponsor'        => $sponsordata->id,
                'sponsor'           => $sponsordata->username,
                'status'            => $m_status,
                'access'            => 'admin',
                'package'           => $package,
                'omzet'             => 0,
                'uniquecode'        => 0,
                'nominal'           => 0,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            // if not Debug, execute to database
            if ( !$debug ) {
                $member_save_id = $CI->Model_Member->save_data($data_member);
                if ( !$member_save_id ) {
                    continue;
                }

                // -------------------------------------------------
                // Update Member Tree
                // -------------------------------------------------
                $gen                = $sponsordata->gen + 1;
                $level              = $uplinedata->level + 1;
                $tree               = an_generate_tree($member_save_id, $uplinedata->tree);
                $tree_sponsor       = an_generate_tree_sponsor($member_save_id, $sponsordata->tree_sponsor);
                $data_tree          = array('gen' => $gen, 'level' => $level, 'tree' => $tree, 'tree_sponsor' => $tree_sponsor);

                // Update Data Member
                $update_tree        = $CI->Model_Member->update_data_member($member_save_id, $data_tree);

                // -------------------------------------------------
                // Save Member Confirm
                // -------------------------------------------------
                $data_member_confirm = array_merge($data_member_confirm, array(
                    'id_downline'       => $member_save_id,
                    'downline'          => $generate_username,
                ));
                $insert_member_confirm  = $CI->Model_Member->save_data_confirm($data_member_confirm);

                $downline               = an_get_memberdata_by_id($member_save_id);
                if ( !$downline ) {
                    continue;
                }

                $bonus_sponsor          = an_calculate_bonus_sponsor($downline->id, $datetime);
                $saved_member_board     = kb_saved_member_board($downline, 1, $datetime);
                $check_member_board     = kb_check_member_board($sponsordata, 1, $datetime);

            } else {
                echo '<pre style="color:#333">';
                $data_res = array(
                    'member'            => $data_member,
                    'member_confirm'    => $data_member_confirm
                );
                echo '----------------------------------------------------' . br();
                echo ' Sponsor ID     : ' . $sponsordata->username . br();
                echo ' Username       : ' . $sponsordata->username . br();
                echo '----------------------------------------------------' . br();
                echo ' Upline ID      : ' . $uplinedata->id . br();
                echo ' Username       : ' . $uplinedata->username . br();
                echo ' Position       : ' . $position . br();
                echo '----------------------------------------------------' . br(2);
                echo ' Execute Member : ' . $number . br();
                echo '----------------------------------------------------' . br(2);
                echo var_dump($data_res);
                echo "</pre>";
            }
        }
    }
}


/*
CHANGELOG
---------
Insert new changelog at the top of the list.
-----------------------------------------------
Version YYYY/MM/DD  Person Name     Description
-----------------------------------------------
1.0.0   2016/06/01  Yuda           - Create this changelog.
*/
