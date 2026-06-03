<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setting Controller.
 *
 * @class     Setting
 * @version   1.0.0
 */
class Setting extends Admin_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // SETTING PAGE
    // =============================================================================================

    /**
     * Setting General function.
     */
    function general()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'SelectChange.init();',
            'GeneralSetting.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_general');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/general';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Grade function.
     */
    function grade()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'GeneralSetting.initGrade();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_grade');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['cfg_packages']   = an_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/grade';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Notification function.
     */
    public function notification()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'ckeditor/ckeditor.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'GeneralSetting.init();',
            'FV_Notification.init();',
            'TableAjaxNotifList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_notification');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/notifications';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Reward function.
     */
    public function reward($form = '', $id = '')
    {
        auth_redirect();

        $dataform               = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($form) {
            if ($form != 'create' && $form != 'edit') {
                redirect(base_url('setting/reward'), 'refresh');
            }
            if ($form == 'create' && $id) {
                redirect(base_url('setting/reward'), 'refresh');
            }
            if ($form == 'edit' && !$id) {
                redirect(base_url('setting/reward'), 'refresh');
            }

            $id = an_decrypt($id);
            if ($form == 'edit' && $id) {
                if (!$dataform = $this->Model_Option->get_reward_by('id', $id)) {
                    redirect(base_url('setting/reward'), 'refresh');
                }
            }

            $main_content           = 'setting/form/reward';
            $headstyles             = an_headstyles(array(
                // Default CSS Plugin
            ));
            $loadscripts            = an_scripts(array(
                // Default JS Plugin
                BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
                // Always placed at bottom
                BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
                BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
            ));
            $scripts_init           = an_scripts_init(array(
                'InputMask.init();',
                'HandleDatepicker.init();',
                'GeneralSetting.initReward();'
            ));
        } else {
            $main_content           = 'setting/reward';
            $headstyles             = an_headstyles(array(
                // Default CSS Plugin
                BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
            ));
            $loadscripts            = an_scripts(array(
                // Default JS Plugin
                BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
                // Always placed at bottom
                BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
                BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
            ));
            $scripts_init           = an_scripts_init(array(
                'TableAjaxSettingRewardList.init();'
            ));
        }

        $alert_msg              = '';
        if ($this->session->userdata('alert_msg')) {
            $alert_msg          = $this->session->userdata('alert_msg');
            $this->session->unset_userdata('alert_msg');
        }

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_reward');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['form']           = $form;
        $data['dataform']       = $dataform;
        $data['alert_msg']      = $alert_msg;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = '';
        $data['main_content']   = $main_content;

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting withdraw function.
     */
    function withdraw()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));

        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'FV_SettingWithdraw.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_withdraw');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/withdraw';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Bonus
     */
    /**
     * Setting Grade function.
     */
    function bonus()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'GeneralSetting.initBonus();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') . ' ' . lang('menu_setting_grade');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['cfg_packages']   = an_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/bonus';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // LIST DATA SETTING
    // =============================================================================================

    /**
     * Promo Code List Data function.
     */
    function promocodelistdata($type = '')
    {
        $member_data        = '';
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if ($type == 'global') {
            $condition      = ' AND (products IS NULL OR products = "") ';
        }
        if ($type == 'products') {
            $condition      = ' AND products IS NOT NULL AND products != ""';
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_code             = $this->input->post('search_code');
        $s_code             = an_isset($s_code, '');
        $s_agent_type       = $this->input->post('search_type_agent');
        $s_agent_type       = an_isset($s_agent_type, '');
        $s_agent_min        = $this->input->post('search_discount_agent_min');
        $s_agent_min        = an_isset($s_agent_min, '');
        $s_agent_max        = $this->input->post('search_discount_agent_max');
        $s_agent_max        = an_isset($s_agent_max, '');
        $s_customer_type    = $this->input->post('search_type_customer');
        $s_customer_type    = an_isset($s_customer_type, '');
        $s_customer_min     = $this->input->post('search_discount_customer_min');
        $s_customer_min     = an_isset($s_customer_min, '');
        $s_customer_max     = $this->input->post('search_discount_customer_max');
        $s_customer_max     = an_isset($s_customer_max, '');
        $s_datecreated_min  = $this->input->post('search_datecreated_min');
        $s_datecreated_min  = an_isset($s_datecreated_min, '');
        $s_datecreated_max  = $this->input->post('search_datecreated_max');
        $s_datecreated_max  = an_isset($s_datecreated_max, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_code)) {
            $condition .= str_replace('%s%', $s_code, ' AND %promo_code% LIKE "%%s%%"');
        }
        if (!empty($s_agent_type)) {
            $condition .= str_replace('%s%', $s_agent_type, ' AND discount_agent_type = "%s%"');
        }
        if (!empty($s_customer_type)) {
            $condition .= str_replace('%s%', $s_customer_type, ' AND discount_customer_type = "%s%"');
        }
        if (!empty($s_agent_min)) {
            $condition .= str_replace('%s%', $s_agent_min, ' AND discount_agent >= %s%');
        }
        if (!empty($s_agent_max)) {
            $condition .= str_replace('%s%', $s_agent_max, ' AND discount_agent <= %s%');
        }
        if (!empty($s_customer_min)) {
            $condition .= str_replace('%s%', $s_customer_min, ' AND discount_customer >= %s%');
        }
        if (!empty($s_customer_max)) {
            $condition .= str_replace('%s%', $s_customer_max, ' AND discount_customer <= %s%');
        }
        if (!empty($s_datecreated_min)) {
            $condition .= str_replace('%s%', $s_datecreated_min, ' AND DATE(datecreated) >= "%s%"');
        }
        if (!empty($s_datecreated_max)) {
            $condition .= str_replace('%s%', $s_datecreated_max, ' AND DATE(datecreated) <= "%s%"');
        }
        if (!empty($s_status)) {
            $s_status   = ($s_status == 'active') ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND status = %s%');
        }

        if ($column == 1) {
            $order_by .= '%promo_code% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= 'discount_agent_type ' . $sort;
        } elseif ($column == 3) {
            $order_by .= 'discount_agent ' . $sort;
        } elseif ($column == 4) {
            $order_by .= 'status ' . $sort;
        } elseif ($column == 5) {
            $order_by .= 'datecreated ' . $sort;
        }

        $data_list          = $this->Model_Option->get_all_promo_code($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $discount_type  = config_item('discount_type');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = an_encrypt($row->id);
                $promo_code     = an_strong(ucwords($row->promo_code));

                if ($row->status == 1) {
                    $status     = '<a href="' . base_url('promocode/promocodestatus/' . $id) . '" class="btn btn-sm btn-outline-success btn-status-promo" data-promo="' . $row->promo_code . '" data-status="' . $row->status . '"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="' . base_url('promocode/promocodestatus/' . $id) . '" class="btn btn-sm btn-outline-danger btn-status-promo" data-promo="' . $row->promo_code . '" data-status="' . $row->status . '"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $agent_type     = isset($discount_type[$row->discount_agent_type]) ? $discount_type[$row->discount_agent_type] : '';
                $customer_type  = isset($discount_type[$row->discount_customer_type]) ? $discount_type[$row->discount_customer_type] : '';
                if ($row->discount_agent_type == 'nominal') {
                    $discount_agent = an_accounting($row->discount_agent, '', true);
                } else {
                    $discount_agent = an_right(an_accounting($row->discount_agent) . ' %');
                }
                if ($row->discount_customer_type == 'nominal') {
                    $discount_customer = an_accounting($row->discount_customer, '', true);
                } else {
                    $discount_customer = an_right(an_accounting($row->discount_customer) . ' %');
                }

                $btn_edit   = '<a class="btn btn-sm btn-default btn-tooltip btn-edit-promo" 
                                href="' . base_url('promocode/savepromocode/' . $id) . '" 
                                data-code="' . $id . '"
                                data-promo="' . $row->promo_code . '"
                                data-agent_type="' . $row->discount_agent_type . '"
                                data-agent_discount="' . ($row->discount_agent + 0) . '"
                                data-customer_type="' . $row->discount_customer_type . '"
                                data-customer_discount="' . ($row->discount_customer + 0) . '"
                                data-products=\'' . $row->products . '\'"
                                title="Edit Promo" ><i class="fa fa-edit"></i></a>';
                $btn_delete = '<a class="btn btn-sm btn-warning btn-tooltip btn-delete-promo" data-promo="' . $row->promo_code . '" title="Hapus Promo" href="' . base_url('setting/deletepromocode/' . $id) . '"><i class="fa fa-times"></i></a>';

                $records["aaData"][]    = array(
                    an_center($i),
                    $promo_code,
                    an_center($agent_type),
                    $discount_agent,
                    an_center($status),
                    an_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                    an_center($btn_edit . $btn_delete),
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

    /**
     * Setting Notification List Data function.
     */
    function notificationlistdata()
    {
        $member_data        = '';
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

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = an_isset($s_type, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_type)) {
            $condition .= str_replace('%s%', $s_type, ' AND %type% = "%s%"');
        }
        if (!empty($s_status)) {
            $s_status   = ($s_status == 'active') ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND %status% = %s%');
        }

        if ($column == 1) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%type% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%status% ' . $sort;
        }

        $data_list          = $this->Model_Option->get_all_notification_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $lbl_class  = 'default';
                if ($row->type == 'email') {
                    $lbl_class = 'primary';
                }
                if ($row->type == 'whatsapp') {
                    $lbl_class = 'success';
                }
                $type       = '<span class="badge badge-sm badge-' . $lbl_class . '">' . strtoupper($row->type) . '</span>';

                $status     = '<span class="badge badge-sm badge-danger">TIDAK AKTIF</span>';
                if ($row->status > 0) {
                    $status = '<span class="badge badge-sm badge-success">AKTIF</span>';
                }

                $btn_edit   = '<a class="btn btn-sm btn-tooltip btn-primary notifdata" title="Edit" href="' . base_url('setting/notifdata/' . $row->id . '/edit') . '"><i class="fa fa-edit"></i></a>';
                $btn_view   = '<a class="btn btn-sm btn-tooltip btn-secondary notifdata" title="View" href="' . base_url('setting/notifdata/' . $row->id . '/view') . '"><i class="fa fa-eye"></i></a>';

                $records["aaData"][]    = array(
                    an_center($i),
                    $row->name,
                    an_center($type),
                    an_center($status),
                    an_center($btn_edit . $btn_view),
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

    /**
     * Setting Reward List Data function.
     */
    function rewardlistdata()
    {
        $member_data        = '';
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

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_name             = $this->input->post('search_name');
        $s_name             = an_isset($s_name, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = an_isset($s_type, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = an_isset($s_status, '');

        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_type)) {
            $condition .= str_replace('%s%', $s_type, ' AND %type% = "%s%"');
        }
        if (!empty($s_status)) {
            $s_status   = ($s_status == 'active') ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND %status% = %s%');
        }

        if ($column == 1) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%type% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%status% ' . $sort;
        }

        $data_list          = $this->Model_Option->get_all_reward_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = an_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = an_encrypt($row->id);

                $period     = '<span class="badge badge-sm badge-danger d-block mb-2">Periode Reward</span>';
                $period    .= 'Start : ' . date('d-M-Y', strtotime($row->start_date)) . br();
                $period    .= 'End : ' . date('d-M-Y', strtotime($row->end_date));
                if ($row->is_lifetime > 0) {
                    $period = '<span class="badge badge-sm badge-success">Lifetime Reward</span>';
                }

                if ($row->is_active == 1) {
                    $status = '<a href="' . base_url('setting/rewardstatus/' . $id) . '" 
                                class="btn btn-sm btn-outline-success btn-status-setting-reward" 
                                data-reward="' . $row->reward . '" 
                                data-status="' . $row->is_active . '"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status = '<a href="' . base_url('setting/rewardstatus/' . $id) . '" 
                                class="btn btn-sm btn-outline-danger btn-status-setting-reward" 
                                data-reward="' . $row->reward . '" 
                                data-status="' . $row->is_active . '"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit   = '<a class="btn btn-sm btn-tooltip btn-primary" title="Edit" href="' . base_url('setting/reward/edit/' . $id) . '"><i class="fa fa-edit"></i></a>';
                $btn_delete = '<a class="btn btn-sm btn-tooltip btn-warning" title="Hapus" href="' . base_url('setting/reward/delete/' . $id) . '"><i class="fa fa-times"></i></a>';

                $records["aaData"][]    = array(
                    an_center($i),
                    $row->reward,
                    an_accounting($row->nominal, '', true),
                    an_accounting($row->point, '', true),
                    an_center($period),
                    an_center($status),
                    an_center($btn_edit . $btn_delete),
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

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // ACTIONS SETTING
    // =============================================================================================

    /**
     * Get Data Notification function.
     */
    function notifdata($id = '', $action = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/notification'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // ID Data 
        if (!$id) {
            $data = array('status' => 'error', 'message' => 'ID Notification tidak dikenali !');
            die(json_encode($data));
        }

        // Get Data Notification 
        if (!$notification = $this->Model_Option->get_notification_by('id', $id)) {
            $data = array('status' => 'error', 'message' => 'Data Notification tidak ditemukan !');
            die(json_encode($data));
        }

        $action     = $action ? $action : 'view';

        if ($action == 'view') {
            if ($notification->type == 'email') {
                $notification->content = an_notification_email_template($notification->content, $notification->title);
            } else {
                $notification->content = '<div style="padding: 0px 15px"><pre>' . $notification->content . '</pre></div>';
            }
        } else {
            if ($notification->type != 'email') {
                $notification->content = strip_tags($notification->content);
            }
        }

        $data = array('status' => 'success', 'process' => $action, 'notification' => $notification, 'message' => 'Data Notification ditemukan.');
        die(json_encode($data));
    }

    /**
     * Update Setting General function.
     */
    function updatesetting($field = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/general'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // Get Data Field 
        if (!$field) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Data Setting tidak ditemukan !');
            die(json_encode($data));
        }

        // Get Data Form
        $value              = $this->input->post('value');
        $value              = an_isset($value, '');

        if ($field == 'register_fee') {
            $value          = str_replace('.', '', $value);
        }

        // Update Setting
        $newvalue           = maybe_serialize($value);
        $data               = array('value' => $newvalue);
        $this->db->where('name', $field);

        // Get Data Field 
        if (!$result = $this->db->update(TBL_OPTIONS, $data)) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Terjadi kesalahan pada proses transaksi !');
            die(json_encode($data));
        }

        // Update Setting Success
        $data = array('status' => 'success', 'message' => 'Update Setting berhasil.');
        die(json_encode($data));
    }

    /**
     * Update All Setting General function.
     */
    function updateallsetting($field = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/general'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // Get Data Field 
        if (!$field = $this->input->post('field')) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Data Setting tidak ditemukan !');
            die(json_encode($data));
        }

        foreach ($field as $key => $value) {
            // Update Data Setting
            $newvalue   = maybe_serialize($value);
            $data       = array('value' => $newvalue);
            if (!$update_data = $this->db->where('name', $key)->update(TBL_OPTIONS, $data)) {
                $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Terjadi kesalahan pada proses transaksi !');
                die(json_encode($data));
            }
        }

        // Update Setting Success
        $data = array('status' => 'success', 'message' => 'Update Setting berhasil.');
        die(json_encode($data));
    }

    /**
     * Update Data Company function.
     */
    function updatecompany()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/general'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $company_name       = $this->input->post('company_name');
        $company_name       = an_isset($company_name, '');
        $company_phone      = $this->input->post('company_phone');
        $company_phone      = an_isset($company_phone, '');
        $company_email      = $this->input->post('company_email');
        $company_email      = an_isset($company_email, '');
        $company_province   = $this->input->post('company_province');
        $company_province   = an_isset($company_province, '');
        $company_city       = $this->input->post('company_city');
        $company_city       = an_isset($company_city, '');
        $company_subdistrict= $this->input->post('company_subdistrict');
        $company_subdistrict= an_isset($company_subdistrict, '');
        $company_address   = $this->input->post('company_address');
        $company_address   = an_isset($company_address, '');

        $this->form_validation->set_rules('company_name', 'Nama Perusahaan', 'required');
        $this->form_validation->set_rules('company_phone', 'No. Telp Perusahaan', 'required');
        $this->form_validation->set_rules('company_email', 'Email Perusahaan', 'required');
        $this->form_validation->set_rules('company_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('company_city', 'Kota/Kabupaten', 'required');
        $this->form_validation->set_rules('company_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('company_address', 'Alamat Perusahaan', 'required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $data = array('status' => 'error', 'message' => validation_errors());
            die(json_encode($data));
        }

        // Update Data Company Name
        if (!$update_data = $this->db->where('name', 'company_name')->update(TBL_OPTIONS, array('value' => $company_name))) {
            $data = array('status' => 'error', 'message' => 'Nama Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        if (substr($company_phone, 0, 1) != '0') {
            $company_phone  = '0' . $company_phone;
        }

        // Update Data Company Phone
        if (!$update_data = $this->db->where('name', 'company_phone')->update(TBL_OPTIONS, array('value' => $company_phone))) {
            $data = array('status' => 'error', 'message' => 'No. Telp Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Phone
        if (!$update_data = $this->db->where('name', 'company_email')->update(TBL_OPTIONS, array('value' => $company_email))) {
            $data = array('status' => 'error', 'message' => 'Email Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Province
        if (!$update_data = $this->db->where('name', 'company_province')->update(TBL_OPTIONS, array('value' => $company_province))) {
            $data = array('status' => 'error', 'message' => 'Provinsi tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data City
        if (!$update_data = $this->db->where('name', 'company_city')->update(TBL_OPTIONS, array('value' => $company_city))) {
            $data = array('status' => 'error', 'message' => 'Kota/Kabupaten tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Subdistrict
        if (!$update_data = $this->db->where('name', 'company_subdistrict')->update(TBL_OPTIONS, array('value' => $company_subdistrict))) {
            $data = array('status' => 'error', 'message' => 'Kecamatan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Address
        if (!$update_data = $this->db->where('name', 'company_address')->update(TBL_OPTIONS, array('value' => $company_address))) {
            $data = array('status' => 'error', 'message' => 'Alamat Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status' => 'success', 'message' => 'Informasi Perusahaan berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Update Data Company Billing function.
     */
    function updatecompanybilling()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/general'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $company_bank       = $this->input->post('company_bank');
        $company_bank       = an_isset($company_bank, '');
        $company_bill       = $this->input->post('company_bill');
        $company_bill       = an_isset($company_bill, '');
        $company_bill_name  = $this->input->post('company_bill_name');
        $company_bill_name  = an_isset($company_bill_name, '');

        $this->form_validation->set_rules('company_bank', 'Bank Perusahaan', 'required');
        $this->form_validation->set_rules('company_bill', 'Nomor Rekening Perusahaan', 'required');
        $this->form_validation->set_rules('company_bill_name', 'Nama Pemilik Rekening', 'required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $data = array('status' => 'error', 'message' => validation_errors());
            die(json_encode($data));
        }

        // Update Data Company Bank
        if (!$update_data = $this->db->where('name', 'company_bank')->update(TBL_OPTIONS, array('value' => $company_bank))) {
            $data = array('status' => 'error', 'message' => 'Bank Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Bill
        if (!$update_data = $this->db->where('name', 'company_bill')->update(TBL_OPTIONS, array('value' => $company_bill))) {
            $data = array('status' => 'error', 'message' => 'No. Rekening Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Bill Name
        if (!$update_data = $this->db->where('name', 'company_bill_name')->update(TBL_OPTIONS, array('value' => $company_bill_name))) {
            $data = array('status' => 'error', 'message' => 'Nama Pemilik Rekening Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status' => 'success', 'message' => 'Informasi Bank Perusahaan berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Update Data Stockist Order function.
     */
    function updatestockistorder()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/general'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $minimal_qty        = $this->input->post('minimal_qty');
        $minimal_qty        = an_isset($minimal_qty, 0, 0, false);
        $minimal_nominal    = $this->input->post('minimal_nominal');
        $minimal_nominal    = an_isset($minimal_nominal, 0, 0, false);

        $this->form_validation->set_rules('minimal_qty', 'Minimal Qty', 'required');
        $this->form_validation->set_rules('minimal_nominal', 'Minimal Nominal', 'required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('* ', br());

        if ($this->form_validation->run() == FALSE) {
            $data = array('status' => 'error', 'message' => validation_errors());
            die(json_encode($data));
        }

        // Update Data Minimal Qty

        if ( substr($minimal_qty, 0, 1) == '0' ) {
            $minimal_qty    = substr($minimal_qty, 1);
        }
        $data_minimal_qty   = array('value' => str_replace('.', '', $minimal_qty));
        $this->db->where('name', 'cfg_stockist_minimal_order_qty');
        if (!$up_wd_min = $this->db->update(TBL_OPTIONS, $data_minimal_qty)) {
            $data = array('status' => 'error', 'message' => 'Minimal Qty produk tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Minimal Nominal
        $data_min_nominal   = array('value' => str_replace('.', '', $minimal_nominal));
        $this->db->where('name', 'cfg_stockist_minimal_order_nominal');
        if (!$up_wd_min = $this->db->update(TBL_OPTIONS, $data_min_nominal)) {
            $data = array('status' => 'error', 'message' => 'Minimal Nominal tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status' => 'success', 'message' => 'Minimal Stockist Order berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Update Data Notification function.
     */
    function updatenotification()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/notification'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $notif_id       = $this->input->post('notif_id');
        $notif_id       = an_isset($notif_id, '');
        $notif_type     = $this->input->post('notif_type');
        $notif_type     = an_isset($notif_type, '');
        $notif_title    = $this->input->post('notif_title');
        $notif_title    = an_isset($notif_title, '');
        $notif_status   = $this->input->post('notif_status');
        $notif_status   = an_isset($notif_status, '');
        $content_email  = $this->input->post('content_email');
        $content_email  = an_isset($content_email, '', '', false, false);
        $content_plain  = $this->input->post('content_plain');
        $content_plain  = an_isset($content_plain, '', '', false, false);

        // Get Data Notification 
        if (!$notification = $this->Model_Option->get_notification_by('id', $notif_id)) {
            $data = array('status' => 'error', 'message' => 'Update Notifikasi tidak berhasil. Data Notification tidak ditemukan !');
            die(json_encode($data));
        }

        $content        = (strtolower($notif_type) == 'email') ? $content_email : $content_plain;

        // Set and Update Data Notification
        $data_notif     = array('title' => $notif_title, 'content' => $content, 'status' => $notif_status);
        if (!$update_notif = $this->Model_Option->update_data_notification($notification->id, $data_notif)) {
            $data = array('status' => 'error', 'message' => 'Update Notifikasi tidak berhasil. Terjasi kesalahan pada proses transaksi.');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status' => 'success', 'message' => 'Update Notifikasi berhasil.');
        die(json_encode($data));
    }

    /**
     * Update Grade Upgrade function.
     */
    function updategradeupgrade()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/grade'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Kenaikan Peringkat tidak berhasil disimpan.');

        // POST Input Form
        $post_upgrade   = $this->input->post('upgrade');
        $post_upgrade   = an_isset($post_upgrade, '', '', false, false);
        $datetime       = date('Y-m-d H:i:s');

        if (!$post_upgrade || !is_array($post_upgrade)) {
            $return['message'] = 'Data Kenaikan Peringkat tidak berhasil disimpan. Silahkan lengkapi form Kenaikan Peringkat !';
            die(json_encode($return)); // JSON encode data
        }

        $data       = array();
        foreach ($post_upgrade as $key => $input) {
            $pack_id        = an_decrypt($key);
            $personal_pv    = an_isset($input['personal'], 0, 0, false, false);
            $group_pv       = an_isset($input['group'], 0, 0, false, false);
            $group_active   = an_isset($input['group_active'], 0, 0, false, false);
            $period_min     = an_isset($input['period_min'], 0, 0, false, false);
            $period         = an_isset($input['period'], 0, 0, false, false);

            $data_grade     = array(
                'upgrade_personal_pv'   => str_replace('.', '', $personal_pv),
                'upgrade_group_pv'      => str_replace('.', '', $group_pv),
                'upgrade_group_active'  => str_replace('.', '', $group_active),
                'upgrade_period_min'    => str_replace('.', '', $period_min),
                'upgrade_period'        => str_replace('.', '', $period),
                'datemodified'          => $datetime,
            );

            $data[$pack_id] = $data_grade;

            if (!$update_grade = $this->Model_Option->update_data_package($pack_id, $data_grade)) {
                $return['message'] = 'Update Data Kenaikan Peringkat tidak berhasil. Terjasi kesalahan pada proses transaksi update.';
                die(json_encode($return));
            }
        }

        // Update Success
        $return['status']   = 'success';
        $return['data']   = $data;
        $return['message']  = 'Update Data Kenaikan Peringkat berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Grade Maintain function.
     */
    function updategrademaintain()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/grade'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Mempertahankan Peringkat tidak berhasil disimpan.');

        // POST Input Form
        $post_maintain  = $this->input->post('maintain');
        $post_maintain  = an_isset($post_maintain, '', '', false, false);
        $datetime       = date('Y-m-d H:i:s');

        if (!$post_maintain || !is_array($post_maintain)) {
            $return['message'] = 'Data Mempertahankan Peringkat tidak berhasil disimpan. Silahkan lengkapi form Mempertahankan Peringkat !';
            die(json_encode($return)); // JSON encode data
        }

        $data       = array();
        foreach ($post_maintain as $key => $input) {
            $pack_id        = an_decrypt($key);
            $personal_pv    = an_isset($input['personal'], 0, 0, false, false);
            $group_pv       = an_isset($input['group'], 0, 0, false, false);
            $period         = an_isset($input['period'], 0, 0, false, false);

            $data_grade     = array(
                'maintain_personal_pv'  => str_replace('.', '', $personal_pv),
                'maintain_group_pv'     => str_replace('.', '', $group_pv),
                'maintain_period'       => str_replace('.', '', $period),
                'datemodified'          => $datetime,
            );

            $data[$pack_id] = $data_grade;

            if (!$update_grade = $this->Model_Option->update_data_package($pack_id, $data_grade)) {
                $return['message'] = 'Update Data Mempertahankan Peringkat tidak berhasil. Terjasi kesalahan pada proses transaksi update.';
                die(json_encode($return));
            }
        }

        // Update Success
        $return['status']   = 'success';
        $return['data']   = $data;
        $return['message']  = 'Update Data Mempertahankan Peringkat berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Personal Sales Setting
     */
    function updatepersolansalesbonus()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Bonus Personal tidak berhasil disimpan.');

        // POST Input Form
        $post_personal  = $this->input->post('bonus');
        $post_personal  = an_isset($post_personal, '', '', false, false);
        $datetime       = date('Y-m-d H:i:s');

        if (!$post_personal || !is_array($post_personal)) {
            $return['message'] = 'Data Bonus Personal tidak berhasil disimpan. Silahkan lengkapi form Personal Bonus !';
            die(json_encode($return)); // JSON encode data
        }
        $data       = array();
        $dataRow = array();

        foreach ($post_personal as $key => $personal) {
            $omzet = isset($personal['value']) ? ((int) trim(str_replace('.', '', $personal['value']))) : '';
            $key = isset($personal['omzet']) ? ((int)trim(str_replace('%', '', $personal['omzet']))) : '';

            $dataRow[$omzet] = $key;
        }

        ksort($dataRow);

        update_option('cfg_personal_bonus', $dataRow);

        // Update Success
        $return['status']   = 'success';
        $return['data']   = $dataRow;
        $return['message']  = 'Update Data Bonus Personal Sales berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Incentive Bonus
     */
    function updateincentivebonus()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token      = $this->security->get_csrf_hash();
        $return         = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Bonus Incentive tidak berhasil disimpan.');

        // POST Input Form
        $post_incentive  = $this->input->post('bonus');
        $post_incentive  = an_isset($post_incentive, '', '', false, false);
        $datetime       = date('Y-m-d H:i:s');

        if (!$post_incentive || !is_array($post_incentive)) {
            $return['message'] = 'Data Bonus Incentive tidak berhasil disimpan. Silahkan lengkapi form Incentive Bonus !';
            die(json_encode($return)); // JSON encode data
        }
        $data       = array();
        $dataRow = array();

        foreach ($post_incentive as $key => $personal) {
            $omzet = isset($personal['value']) ? ((int)trim(str_replace('.', '', $personal['value']))) : '';
            $key = isset($personal['omzet']) ? ((int)trim(str_replace('%', '', $personal['omzet']))) : '';

            $dataRow[$omzet] = $key;
        }

        ksort($dataRow);

        update_option('cfg_incentive_bonus', $dataRow);

        // Update Success
        $return['status']   = 'success';
        $return['data']   = $dataRow;
        $return['message']  = 'Update Data Incentive Sales berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Incentive Group Condition
     */
    function updateincentivegroupcondition()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data                               = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token                              = $this->security->get_csrf_hash();
        $return                                 = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Incentive Group Condition tidak berhasil disimpan.');

        // POST Input Form
        $post_condition                         = $this->input->post('condition');
        $post_condition                         = an_isset($post_condition, '', '', false, false);

        if (!$post_condition || !is_array($post_condition)) {
            $return['message']                  = 'Data Incentive Group Condition tidak berhasil disimpan. Silahkan lengkapi form Incentive Group Condition !';
            die(json_encode($return)); // JSON encode data
        }

        $data = array();

        foreach ($post_condition as $key => $allowance) {
            $package                            = an_decrypt($key);
            $allowance['personal_sales']        = isset($allowance['personal_sales']) ? trim(str_replace('.', '', $allowance['personal_sales'])) : 0;
            $allowance['sales_agent']           = isset($allowance['sales_agent']) ? trim(str_replace('.', '', $allowance['sales_agent'])) : 0;
            $allowance['percent_no_qualified']  = isset($allowance['percent_no_qualified']) ? trim(str_replace('.', '', $allowance['percent_no_qualified'])) : 0;
            $data[$package]                     = $allowance;
        }
        // var_dump($data);
        update_option('cfg_incentive_group_condition', $data);

        // Update Success
        $return['status']                       = 'success';
        $return['data']                         = $data;
        $return['message']                      = 'Update Data Incentive Group Condition berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Incentive Group Bonus
     */
    function updateincentivegroupbonus()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token                  = $this->security->get_csrf_hash();
        $return                     = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Bonus Incentive Group tidak berhasil disimpan.');

        // POST Input Form
        $post_incentive_group       = $this->input->post('bonus');
        $post_incentive_group       = an_isset($post_incentive_group, '', '', false, false);
        $datetime                   = date('Y-m-d H:i:s');

        if (!$post_incentive_group || !is_array($post_incentive_group)) {
            $return['message']      = 'Data Bonus Incentive Group tidak berhasil disimpan. Silahkan lengkapi form Incentive Group Bonus !';
            die(json_encode($return)); // JSON encode data
        }
        $data                       = array();
        $dataRow                    = array();

        foreach ($post_incentive_group as $key => $personal) {
            $omzet                  = isset($personal['value']) ? ((int) trim(str_replace('.', '', $personal['value']))) : '';
            $key                    = isset($personal['omzet']) ? ((int) trim(str_replace('.', '', $personal['omzet']))) : '';

            $dataRow[$omzet]        = $key;
        }

        ksort($dataRow);

        update_option('cfg_incentive_group_bonus', $dataRow);

        // Update Success
        $return['status']           = 'success';
        $return['data']             = $dataRow;
        $return['message']          = 'Update Data Bonus Incentive Group berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Settlement Bonus
     */
    function updatesettlementbonus()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data                               = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token                              = $this->security->get_csrf_hash();
        $return                                 = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Bonus Allowance tidak berhasil disimpan.');

        // POST Input Form
        $post_settlement                        = $this->input->post('bonus');
        $post_settlement                        = an_isset($post_settlement, '', '', false, false);

        if (!$post_settlement || !is_array($post_settlement)) {
            $return['message']                  = 'Data Bonus Allowance tidak berhasil disimpan. Silahkan lengkapi form Allowance Bonus !';
            die(json_encode($return)); // JSON encode data
        }

        $post_settlement['amount']              = isset($post_settlement['amount']) ? ((int)trim(str_replace('.', '', $post_settlement['amount']))) : '';
        $post_settlement['min_omzet']           = isset($post_settlement['min_omzet']) ? ((int)trim(str_replace('.', '', $post_settlement['min_omzet']))) : '';
        $post_settlement['max_date_register']   = isset($post_settlement['max_date_register']) ? ((int)trim(str_replace('.', '', $post_settlement['max_date_register']))) : '';

        update_option('cfg_settlement_bonus', $post_settlement);

        // Update Success
        $return['status']                       = 'success';
        $return['data']                         = $post_settlement;
        $return['message']                      = 'Update Data Bonus Allowance berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Allowance Bonus
     */
    function updateallowancebonus()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/bonus'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data                               = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $an_token                              = $this->security->get_csrf_hash();
        $return                                 = array('status' => 'error', 'token' => $an_token, 'message' => 'Update Data Bonus Settlement tidak berhasil disimpan.');

        // POST Input Form
        $post_allowance                        = $this->input->post('bonus');
        $post_allowance                        = an_isset($post_allowance, '', '', false, false);

        if (!$post_allowance || !is_array($post_allowance)) {
            $return['message']                  = 'Data Bonus Settlement tidak berhasil disimpan. Silahkan lengkapi form Settlement Bonus !';
            die(json_encode($return)); // JSON encode data
        }

        $data = array();
        foreach ($post_allowance as $key => $allowance) {
            $package     = an_decrypt($key);

            $allowance['pm']                    = isset($allowance['pm']) ? ((float) trim(str_replace('.', '', $allowance['pm']))) : 0;
            $allowance['gs']                    = isset($allowance['gs']) ? ((float) trim(str_replace('.', '', $allowance['gs']))) : 0;
            // $allowance['auto']                  = isset($allowance['auto']) ? ((float) trim(str_replace('.', '', $allowance['auto']))) : 0;
            $allowance['amount']                = isset($allowance['amount']) ? ((float) trim(str_replace('.', '', $allowance['amount']))) : 0;
            $data[$package]                     = $allowance;
        }
        update_option('cfg_allowance_bonus', $data);

        // Update Success
        $return['status']                       = 'success';
        $return['data']                         = $data;
        $return['message']                      = 'Update Data Bonus Allowance berhasil.';
        die(json_encode($return));
    }

    /**
     * Update Data Setting Withdraw function.
     */
    function updatewithdraw()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/notification'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $wd_min         = $this->input->post('wd_min');
        $wd_min         = an_isset($wd_min, 0);
        $wd_fee         = $this->input->post('wd_fee');
        $wd_fee         = an_isset($wd_fee, 0);
        $wd_tax         = $this->input->post('wd_tax');
        $wd_tax         = an_isset($wd_tax, 0);
        $wd_tax_npwp    = $this->input->post('wd_tax_npwp');
        $wd_tax_npwp    = an_isset($wd_tax_npwp, 0);

        $this->form_validation->set_rules('wd_min', 'Withdraw Minimal', 'required');
        $this->form_validation->set_rules('wd_fee', 'Biaya Transfer', 'required');
        $this->form_validation->set_rules('wd_tax','Pajak Non NPWP','required');
        $this->form_validation->set_rules('wd_tax_npwp','Pajak NPWP','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $data = array('status' => 'error', 'message' => 'Setting Withdraw tidak berhasil di ubah. ' . validation_errors());
            die(json_encode($data));
        }

        // Update Data Withdraw Minimal
        $data_wd_min        = array('value' => str_replace('.', '', $wd_min));
        $this->db->where('name', 'setting_withdraw_minimal');
        if (!$up_wd_min = $this->db->update(TBL_OPTIONS, $data_wd_min)) {
            $data = array('status' => 'error', 'message' => 'Withdraw Minimal tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Biaya Transfer
        $data_wd_fee        = array('value' => str_replace('.', '', $wd_fee));
        $this->db->where('name', 'setting_withdraw_fee');
        if (!$up_wd_fee = $this->db->update(TBL_OPTIONS, $data_wd_fee)) {
            $data = array('status' => 'error', 'message' => 'Biaya Transfer tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Pajak NON NPWP WD
        $wd_tax             = str_replace(',', '.', $wd_tax);
        $wd_tax             = str_replace('%', '', $wd_tax);
        $wd_tax             = trim($wd_tax);
        $data_wd_tax        = array('value' => $wd_tax);
        $this->db->where('name', 'setting_withdraw_tax');
        if (!$up_wd_tax = $this->db->update(TBL_OPTIONS, $data_wd_tax)) {
            $data = array('status' => 'error', 'message' => 'Pajak tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Pajak NPWP WD
        $wd_tax_npwp        = str_replace(',', '.', $wd_tax_npwp);
        $wd_tax_npwp        = str_replace('%', '', $wd_tax_npwp);
        $wd_tax_npwp        = trim($wd_tax_npwp);
        $data_wd_tax_npwp   = array('value' => $wd_tax_npwp);
        $this->db->where('name', 'setting_withdraw_tax_npwp');
        if (!$up_wd_tax = $this->db->update(TBL_OPTIONS, $data_wd_tax_npwp)) {
            $data = array('status' => 'error', 'message' => 'Pajak tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status' => 'success', 'message' => 'Setting Withdraw berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Save Promo Code function.
     */
    function savepromocode($id = '')
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('promocode/global'), 'location');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = an_get_current_member();
        $datetime           = date('Y-m-d H:i:s');
        $created_by         = $current_member->username;
        if ($staff = an_get_current_staff()) {
            $created_by     = $staff->username;
        }

        $promo_id           = '';
        if ($id) {
            $id = an_decrypt($id);
            if (!$data_promo = $this->Model_Option->get_promo_codes($id)) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. ID Kode Promo tidak ditemukan !');
                die(json_encode($data));
            }
            $promo_id       = $data_promo->id;
        }

        // POST Input Form
        $promo_code             = trim($this->input->post('promo_code'));
        $promo_code             = an_isset($promo_code, '');
        $discount_agent_type    = trim($this->input->post('discount_agent_type'));
        $discount_agent_type    = an_isset($discount_agent_type, '');
        $discount_agent         = trim($this->input->post('discount_agent'));
        $discount_agent         = an_isset($discount_agent, '');
        $discount_customer_type = trim($this->input->post('discount_customer_type'));
        $discount_customer_type = an_isset($discount_customer_type, '');
        $discount_customer      = trim($this->input->post('discount_customer'));
        $discount_customer      = an_isset($discount_customer, '');
        $form_input             = trim($this->input->post('form_input'));
        $form_input             = an_isset($form_input, 'global');
        $product_ids            = '';

        if (!$promo_code) {
            $data = array('status' => 'error', 'message' => 'Kode Promo harus di isi !');
            die(json_encode($data));
        }

        $discount_agent         = str_replace('.', '', $discount_agent);
        $discount_customer      = str_replace('.', '', $discount_customer);

        if (!$discount_agent && !$discount_customer) {
            $data = array('status' => 'error', 'message' => 'Salah atu diskon (Diskon Agen atau Diskon Konsumen) harus di isi !');
            die(json_encode($data));
        }

        if ($form_input == 'products') {
            if (!$products = $this->input->post('products')) {
                $data = array('status' => 'error', 'message' => 'Produk belum di pilih !');
                die(json_encode($data));
            }
            foreach ($products as $key => $value) {
                $product_ids[] = $value;
            }
        }

        $user_type              = 'all';
        if ($discount_agent && !$discount_customer) {
            $user_type          = 'agent';
        }
        if (!$discount_agent && $discount_customer) {
            $user_type          = 'customer';
        }

        $data = array(
            'promo_code'            => strtoupper($promo_code),
            'discount_agent_type'   => $discount_agent_type,
            'discount_agent'        => $discount_agent,
            'discount_customer_type' => $discount_customer_type,
            'discount_customer'     => $discount_customer,
            'usertype'              => $user_type,
            'datecreated'           => $datetime,
            'datemodified'          => $datetime,
        );

        if ($form_input == 'products' && $product_ids) {
            $data['products']       = json_encode($product_ids);
        }

        if ($id) {
            unset($data['datecreated']);
            $data['modified_by'] = $created_by;
            if (!$update_data = $this->Model_Option->update_data_promo_code($id, $data)) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. Silahkan cek form Kode Promo !');
                die(json_encode($data));
            }
        } else {
            $data['status']     = 1;
            $data['created_by'] = $created_by;
            if (!$saved_data = $this->Model_Option->save_data_promo_code($data)) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. Silahkan cek form Kode Promo !');
                die(json_encode($data));
            }
            $id = $saved_data;
        }

        $data = array('status' => 'success', 'message' => 'Data Kode Promo berhasil disimpan.');
        die(json_encode($data));
    }

    /**
     * Status Promo Code Function
     */
    function promocodestatus($id = 0)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('productmanage/categorylist'), 'refresh');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if (!$id) {
            $data = array('status' => 'error', 'message' => 'Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }
        $id = an_decrypt($id);
        if (!$data_promo = $this->Model_Option->get_promo_codes($id)) {
            $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ($data_promo->status == 1) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ($staff = an_get_current_staff()) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if (!$update_data = $this->Model_Option->update_data_promo_code($id, $data)) {
            $data = array('status' => 'error', 'message' => 'Status Kode Promo tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status' => 'success', 'message' => 'Status Kode Promo berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Save Reward Function
     */
    function savereward($id = 0)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('setting/reward'), 'refresh');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member     = an_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $reward             = trim($this->input->post('reward'));
        $reward             = an_isset($reward, '');
        $nominal            = $this->input->post('nominal');
        $nominal            = an_isset($nominal, 0);
        $point              = trim($this->input->post('point'));
        $point              = an_isset($point, 0);
        $message            = $this->input->post('message');
        $message            = an_isset($message, '');
        $is_active          = trim($this->input->post('is_active'));
        $is_active          = an_isset($is_active, 0);
        $is_lifetime        = trim($this->input->post('is_lifetime'));
        $is_lifetime        = an_isset($is_lifetime, 0);
        $period_start       = $this->input->post('period_start');
        $period_start       = an_isset($period_start, '');
        $period_end         = $this->input->post('period_end');
        $period_end         = an_isset($period_end, '');

        $this->form_validation->set_rules('reward', 'Nama Reward', 'required');
        $this->form_validation->set_rules('nominal', 'Nominal Reward (Rp)', 'required');
        $this->form_validation->set_rules('point', 'Nilai Poin', 'required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. ' . validation_errors());
            die(json_encode($data));
        } else {
            $data = array(
                'reward'            => ucwords(strtolower($reward)),
                'nominal'           => str_replace('.', '', $nominal),
                'point'             => str_replace('.', '', $point),
                'message'           => $message,
                'start_date'        => $is_lifetime ? null : $period_start,
                'end_date'          => $is_lifetime ? null : $period_end,
                'is_lifetime'       => $is_lifetime,
                'is_active'         => $is_active,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ($id) {
                $id = an_decrypt($id);
                unset($data['datecreated']);
                if (!$datareward = $this->Model_Option->get_reward_by('id', $id)) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
                if (!$update_data = $this->Model_Option->update_data_reward_config($id, $data)) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
            } else {
                if (!$saved_data = $this->Model_Option->save_data_reward_config($data)) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
            }

            // Save Success
            $this->session->set_userdata('alert_msg', 'Reward berhasil disimpan.');
            $data = array('status' => 'success', 'message' => 'Setting Reward berhasil disimpan.', 'url' => base_url('setting/reward'));
            die(json_encode($data));
        }
    }

    /**
     * Delete Promo Code (Discount) Function
     */
    function deletepromocode($id = 0)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('promocode/global'), 'refresh');
        }

        if (!$id) {
            $data = array('status' => 'error', 'message' => 'ID Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = an_decrypt($id);
        $promodata  = $this->Model_Option->get_promo_code_by('id', $id);
        if (!$promodata) {
            $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }

        if (!$delete_data = $this->Model_Option->delete_data_promo_code($id)) {
            $data = array('status' => 'error', 'message' => 'Kode Promo tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status' => 'success', 'message' => 'Kode Promo berhasil dihapus.');
        die(json_encode($data));
    }

    /**
     * Check Promo Code function.
     */
    function checkpromocode()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('promocode/global'), 'location');
        }

        $code       = $this->input->post('code');
        $code       = trim(an_isset($code, ''));
        $promo_code = $this->input->post('promo_code');
        $promo_code = trim(an_isset($promo_code, ''));
        $an_token  = $this->security->get_csrf_hash();

        if (!empty($promo_code)) {
            $promodata = $this->Model_Option->get_promo_code_by('promo_code', $promo_code);
            if ($promodata) {
                if ($code) {
                    $code = an_encrypt($code, 'decrypt');
                    if ($code != $promodata->id) {
                        die(json_encode(array('status' => false, 'token' => $an_token)));
                    }
                } else {
                    die(json_encode(array('status' => false, 'token' => $an_token)));
                }
            }
        }
        die(json_encode(array('status' => true, 'token' => $an_token)));
    }
}

/* End of file Setting.php */
/* Location: ./application/controllers/Setting.php */
