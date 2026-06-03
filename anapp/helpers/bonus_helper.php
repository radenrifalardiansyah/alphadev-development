<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// -------------------------------------------------------------------------
// Bonus General functions helper
// -------------------------------------------------------------------------

if (!function_exists('an_save_bonus')) {
    /**
     * Save bonus of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Object      $data           (Required)  Data of Bonus
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function an_save_bonus($id_member, $data, $debug = false)
    {
        if (!is_numeric($id_member)) return false;
        if (!$data) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $CI = &get_instance();

        // Set Required Variables
        $bonus_qualified        = TRUE;
        $bonus_amount           = isset($data['amount']) ? $data['amount'] : 0;
        $bonus_code             = isset($data['id_bonus']) ? $data['id_bonus'] : 'B' . date('YmdHis') . an_generate_rand_string(4, 'num');
        $type                   = isset($data['type']) ? $data['type'] : '';
        $description            = isset($data['desc']) ? $data['desc'] : '';
        $datetime               = isset($data['datecreated']) ? $data['datecreated'] : date('Y-m-d H:i:s');

        // Get Data member
        $memberdata             = an_get_memberdata_by_id($id_member);
        if (!$memberdata) return false;

        if ($memberdata->id == 1) {
            $bonus_qualified = false;
        }
        if ($memberdata->status != 1) return false;

        // Save Data Bonus
        // ---------------------------------------------------------
        if (!$debug && $bonus_qualified) {
            $bonus_id           = $CI->Model_Bonus->save_data_bonus($data);
            if (!$bonus_id) return false;

            // Set Data Ewallet
            $data_ewallet = array(
                'id_member'     => $memberdata->id,
                'id_source'     => $bonus_id,
                'amount'        => $bonus_amount,
                'source'        => 'bonus',
                'type'          => 'IN',
                'type_cat'      => EWALLET_TYPE_CASH,
                'status'        => 1,
                'description'   => $description,
                'datecreated'   => $datetime
            );

            if (!$ewallet_id = $CI->Model_Bonus->save_data_ewallet($data_ewallet)) return false;
            return $bonus_id;
        }

        return true;
    }
}

if (!function_exists('an_calculate_bonus_referral')) {
    /**
     * Count bonus referral of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Datetime    $datetime       (Optional)  Datetime
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function an_calculate_bonus_referral($id_member, $datetime = '', $debug = false)
    {
        if ( !is_numeric($id_member) ) return false;

        $id_member  = absint($id_member);
        if ( !$id_member ) return false;

        $CI = &get_instance();
        
        $datetime               = $datetime ? $datetime : date('Y-m-d H:i:s');

        $memberdata             = an_get_memberdata_by_id($id_member);
        if ( !$memberdata ) return false;
        if ( !$memberdata->package ) return false;

        $memberconfirm          = an_get_memberconfirm_by_downline($memberdata->id);
        if ( !$memberconfirm ) return false;
        $mempackagedata         = an_packages($memberdata->package, false);
        if ( !$mempackagedata ) return false;
        $memberbv               = an_isset($mempackagedata->bv, 0);
        
        $sponsordata            = an_get_memberdata_by_id($memberdata->sponsor);
        if ( !$sponsordata ) return false;
        $sponpackagedata        = an_packages($sponsordata->package, false);
        if ( !$sponpackagedata ) return false;

        $sponsor_percentage_max = 30;
        $sponsor_percentage     = an_isset($sponpackagedata->bonus_referral_percentage, 0);
        $percentage_left        = $sponsor_percentage_max - $sponsor_percentage;
        
        $sponsor_nominal        = ( $sponsor_percentage / 100 ) * $memberbv;
        if ( !$sponsor_nominal ) return false;
        $sponsor_nominal        = $sponsor_nominal * 1000;
        
        $sponancestry           = an_ancestry_sponsor($sponsordata->id);
        if(!$sponancestry) return false; 
        $sponancestry           = explode(',', $sponancestry);  

        $description            = 'Pendaftaran '.($memberdata->type_status == TYPE_STATUS_DROPSHIPPER ? 'Dropshipper' : 'Reseller');

        if ( $debug ) {
            echo 'Username Member : ' . $sponsordata->username . ' get Bonus ' . an_accounting($sponsor_nominal, 'Rp') . br();
        } else {
            $data_bonus = array(
                'id_bonus'      => 'B' . date('YmdHis') . an_generate_rand_string(4, 'num'),
                'id_member'     => $sponsordata->id,
                'amount'        => $sponsor_nominal,
                'type'          => BONUS_REFERRAL,
                'desc'          => $description . $memberdata->username,
                'status'        => 1,
                'datecreated'   => $datetime
            );
            $save_bonus         = an_save_bonus($sponsordata->id, $data_bonus);
            if ($save_bonus) {
                
            }
        }
        
        // Changed to Weekly Process
        /*
        if( $percentage_left > 0 ){
            if( $sponancestry ){
                $latest_percentage  = $sponsor_percentage;
                $total_percentage   = $sponsor_percentage;
                
                foreach($sponancestry as $spon_up_id){
                    if( $spon_up_id == 1 ) break;
                    if( $percentage_left == 0 ) break;
                    
                    $spon_up_data           = an_get_memberdata_by_id($spon_up_id);
                    if( !$spon_up_data ) continue;
                    $spon_up_packagedata    = an_packages($spon_up_data->package, false);
                    if( !$spon_up_packagedata ) continue;
                    $spon_up_percentage     = an_isset($spon_up_packagedata->bonus_referral_percentage, 0);
                    if( $spon_up_percentage <= $latest_percentage ) continue;
                    
                    $spon_up_get_percentage = $spon_up_percentage - $latest_percentage;
                    $spon_up_nominal        = ( $spon_up_get_percentage / 100 ) * $memberbv;
                    
                    if ( $debug ) {
                        echo 'Username Member : ' . $spon_up_data->username . ' get Bonus ' . an_accounting($spon_up_nominal, 'Rp') . br();
                    } else {
                        $data_bonus_spon_up = array(
                            'id_bonus'      => 'B' . date('YmdHis') . an_generate_rand_string(4, 'num'),
                            'id_member'     => $spon_up_data->id,
                            'amount'        => $spon_up_nominal,
                            'type'          => BONUS_PASSUP,
                            'desc'          => $description . $memberdata->username,
                            'status'        => 1,
                            'datecreated'   => $datetime
                        );
                        $save_bonus         = an_save_bonus($spon_up_data->id, $data_bonus_spon_up);
                    }
                    
                    $latest_percentage      = $spon_up_percentage;
                    $total_percentage       = $total_percentage + $spon_up_get_percentage;
                    $percentage_left        = $percentage_left - $total_percentage;
                }
            }
        }
        */

        return true;
    }
}

if (!function_exists('an_calculate_bonus_sales')) {
    /**
     * Count bonus sales of member
     * @author  Yuda
     * @param   Int         $id_reseller    (Required)  Reseller ID
     * @param   Int         $sales_nominal  (Required)  Sales Nominal
     * @param   String      $for            (Required)  Bonus for Self/Sponsor
     * @param   Datetime    $datetime       (Optional)  Datetime
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function an_calculate_bonus_sales($id_reseller, $sales_nominal, $for='sponsor', $datetime = '', $debug = false)
    {
        if ( !is_numeric($id_reseller) ) return false;
        if ( !is_numeric($sales_nominal) ) return false;

        $id_reseller  = absint($id_reseller);
        if ( !$id_reseller ) return false;
        
        $sales_nominal  = absint($sales_nominal);
        if ( !$sales_nominal ) return false;

        $CI = &get_instance();
        
        $datetime               = $datetime ? $datetime : date('Y-m-d H:i:s');

        $resellerdata           = an_get_memberdata_by_id($id_reseller);
        if ( !$resellerdata ) return false;
        if ( !$resellerdata->package ) return false;
        
        if( $for == 'sponsor' ){
            $sponsordata            = an_get_memberdata_by_id($resellerdata->sponsor);
            if ( !$sponsordata ) return false;
            if ( $sponsordata->id == 1 ) return false;
            
            $receiver           = $sponsordata;
        }else{
            $receiver           = $resellerdata;
        }
        
        $receiverpackagedata    = an_packages($receiver->package, false);
        if ( !$receiverpackagedata ) return false;

        $sales_percentage       = an_isset($receiverpackagedata->bonus_sales_percentage, 0);
        $bonus_nominal          = ( $sales_percentage / 100 ) * $sales_nominal;
        if ( !$bonus_nominal ) return false;

        $description            = 'RO Member ';

        if ( $debug ) {
            echo 'Username Member : ' . $receiver->username . ' get Bonus Penjualan ' . an_accounting($bonus_nominal, 'Rp') . br();
        } else {
            $data_bonus = array(
                'id_bonus'      => 'B' . date('YmdHis') . an_generate_rand_string(4, 'num'),
                'id_member'     => $receiver->id,
                'amount'        => $bonus_nominal,
                'type'          => BONUS_SALES,
                'desc'          => $description . $receiver->username,
                'status'        => 1,
                'datecreated'   => $datetime
            );

            $save_bonus         = an_save_bonus($receiver->id, $data_bonus);
            if ($save_bonus) {
                // Action Log
            }
        }

        return true;
    }
}

if ( !function_exists('an_calculate_pairing_bonus') )
{
    /**
     * Count pair bonus of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Date        $datetime       (Optional)  Date Of Bonus
     * @return  Boolean
     */
    function an_calculate_pairing_bonus($id_member, $datetime='', $debug=false, $count_ancestry=false) {
        if ( !is_numeric($id_member) ) return false;

        $id_member  = absint($id_member);
        if ( !$id_member ) return false;

        $CI =& get_instance();
        
        $memberdata             = an_get_memberdata_by_id($id_member);
        if ( !$memberdata ) return false;
        if ( !$memberdata->package ) return false;

        $is_admin               = as_administrator($memberdata);
        if ( $is_admin ) return false;

        $cfg_package            = an_packages($memberdata->package, false);
        if ( ! $cfg_package ) return false;
        
        // We count the ancestry first since although this member has no pair, the ancestry may have
        if ( $count_ancestry ) {
            $ancestry = explode('-', $memberdata->tree);
            $ancestry = array_diff($ancestry, array($memberdata->id));
            rsort($ancestry);
            // check if ancestry available for this member
            if ( $ancestry ) {
                // ancestry is returned in coma delimited
                foreach($ancestry as $id_ancestry) {
                    $id_ancestry = absint($id_ancestry);
                    an_calculate_pairing_bonus($id_ancestry, $datetime, $debug, FALSE);
                }
            }
        }

        $datetime               = $datetime ? $datetime : date('Y-m-d 23:59:s');
        $start_calculation      = config_item('start_calculation');
        if ( date('Ymd', strtotime($datetime)) <= date('Ymd', strtotime($start_calculation)) ) {
            $datetime           = date('Y-m-d 23:59:s');
        }

        $datecreated            = date('Y-m-d', strtotime($datetime));
        $cfg_grace_period       = config_item('pair_grace_period');
        $cfg_qualified_period   = config_item('pair_qualified_period');
        $grace_period           = date('Y-m-d', strtotime('+'. $cfg_grace_period . ' day' , strtotime($memberdata->datecreated)));
        $qualified_period       = date('Y-m-d', strtotime('+'. $cfg_qualified_period . ' day' , strtotime($memberdata->datecreated)));
        $ro_period              = '';
        $activation_ro          = false;

        // Set Pairing Bonus Required Variables
        $cfg_pair_nominal       = $cfg_package->pairing_nominal;
        $cfg_pair_max           = $cfg_package->pairing_max;
        $cfg_pair_max_ro        = $cfg_package->pairing_max_ro;
        $pair_max_today         = $cfg_pair_max;

        if ( strtotime($grace_period) >= strtotime($datecreated) ) {
            $pair_max_today     = $cfg_pair_max_ro;
        } else {
            if ( strtotime($datecreated) > strtotime($qualified_period) ) {
                $pair_max_today = 0;
            }

            $ro_year            = date('Y', strtotime($datecreated));
            $ro_month           = date('n', strtotime($datecreated));
            if ( $ro_month == 1 ) {
                $be_month       = 12;
                $ro_year        = $ro_year - 1;
            } else {
                $be_month       = $ro_month - 1;
            }
            $be_month           = ( strlen($be_month) == 1 ) ? '0'.$be_month : $be_month;
            $ro_period          = $ro_year .'-'. $be_month;

            // Check Data RO
            $condition          = ' AND %id_member% = ? AND DATE_FORMAT(%datecreated%, "%Y-%m") = ?'; 
            $params             = array($id_member, $ro_period);
            $data_ro            = $CI->Model_Member->get_all_member_ro(0, 0, $condition, '', $params);
            if ( $data_ro ) {
                $pair_max_today = $cfg_pair_max_ro;
                $activation_ro  = true;
            }
        }

        // Get Toral Point Pairing per-Day
        if ( $pair_max_today ) {
            $pair_point_today   = an_count_pairing_qualified($id_member, true, $datecreated, true);
            if ( $pair_point_today ) {
                $pair_max_today = $pair_max_today - $pair_point_today;
                $pair_max_today = max(0, $pair_max_today);
            }
        }

        if ( $debug ) {
            echo '----------------------------------------------------'. br();
            echo '   Calculate Pairing'. br();
            echo '----------------------------------------------------'. br();
            echo '   ID Member       : ' . $id_member .br();
            echo '   Username        : ' . $memberdata->username .br();
            echo '   Name            : ' . $memberdata->name .br();
            echo '   Package         : ' . $memberdata->package .br();
            echo '----------------------------------------------------'. br();
            echo '   Date Join       : ' . $memberdata->datecreated .br();
            echo '   Grace Period    : ' . $grace_period .br();
            echo '----------------------------------------------------'. br();
            echo '   RO Activation   : ' . ($activation_ro ? 'Ya' : 'No') .br();
            echo '   RO Period       : ' . $ro_period .br();
            echo '----------------------------------------------------'. br();
            echo '   Pairing Nominal : ' . an_accounting($cfg_pair_nominal) .br();
            echo '   Pairing Max     : ' . an_accounting($pair_max_today) .br();
            echo '   Pairing Today   : ' . an_accounting($pair_point_today) .br();
            echo '----------------------------------------------------'. br();
            echo '   Max Today       : ' . an_accounting($pair_max_today) .br();
            echo '----------------------------------------------------'. br();
        }
        
        // Count Pairing Point
        $point_left             = an_count_pairing($id_member, POS_LEFT, $datecreated); 
        $point_right            = an_count_pairing($id_member, POS_RIGHT, $datecreated);

        // Skip if there is empty downline
        if ( $debug ) {
            echo '   Point LEFT      : ' . an_accounting($point_left) .br();
            echo '   Point RIGHT     : ' . an_accounting($point_right) .br();
            echo '----------------------------------------------------'. br();
        }

        if ( $point_left == 0 || $point_right == 0 ) {
            if ( $debug ) { echo br(2); }
            return false;
        }

        // Find minimum pv node
        $minimum                = min($point_left, $point_right);

        // Check minimum for pairing qualified
        if ( !$minimum ) return false;

        // we calculate how many pairs qualified for pairing
        $pair_point             = min($minimum, $pair_max_today);
        $pair_bonus             = $cfg_pair_nominal * $pair_point;
        
        if ($debug) {
            echo '   Point QUALIFIED : ' . an_accounting($pair_point) .br();
            echo '   Pairing BONUS   : ' . an_accounting($pair_bonus) .br();
            echo '----------------------------------------------------'. br(2);
        } else {
            if ( $pair_bonus ) {
                // Set data and save bonus sponsorship
                $data_bonus         = array(
                    'id_bonus'      => 'B' . date('YmdHis') . an_generate_rand_string(4,'num'),
                    'id_member'     => $id_member,
                    'type'          => BONUS_PAIRING,
                    'desc'          => 'Bonus Pairing ('. an_accounting($pair_point) . ' Poin)',
                    'amount'        => $pair_bonus,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                $bonus_id = an_save_bonus($id_member, $data_bonus, $debug);
            }

            if ( $minimum ) {
                // find carry forward
                $carry_left         = ( $point_left > $minimum ) ? ( $point_left - $minimum ) : 0;  
                $carry_right        = ( $point_right > $minimum ) ? ( $point_right - $minimum ) : 0;

                // Insert Pairing Qualified
                $data_pair_qualified = array(
                    'id_member'     => $id_member,
                    'left'          => $point_left,
                    'right'         => $point_right,
                    'qualified'     => $minimum,
                    'carry_left'    => $carry_left,
                    'carry_right'   => $carry_right,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime,
                );

                an_save_pair_qualified($data_pair_qualified);
            }
        }

        return true;
    }
}
