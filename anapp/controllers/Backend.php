<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Backend Controller.
 *
 * @class     Backend
 * @version   1.0.0
 */
class Backend extends Member_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // DASHBOARD
    // =============================================================================================

    /**
     * Dashboard function.
     */
    public function index()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'chart.js/dist/Chart.min.js',
            BE_PLUGIN_PATH . 'chart.js/dist/Chart.extension.js',
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'pages/dashboard.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));

        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'ShopOrderManage.init();',
            'Profile.init();',
            'FV_Profile.init();'
        ));
        $scripts_add            = '';

        $data_omzet             = $this->Model_Shop->get_all_omzet_shop_order_monthly(6, 0);

        $data['title']          = TITLE . 'Dashboard';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['data_omzet']     = $data_omzet;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'dashboard';

        // log for dashboard
        if (!$this->session->userdata('log_dashboard')) {
            $this->session->set_userdata('log_dashboard', true);
            an_log('DASHBOARD', an_get_current_ip(), maybe_serialize(array('current_member' => $current_member, 'cookie' => $_COOKIE)));
        }

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // MEMBER PAGE
    // =============================================================================================

    /**
     * Member New function.
     */
    public function membernew()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/register.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'GetProduct.init();',
            'SearchAction.init();',
            'SelectChange.init();',
            'RegisterMember.init();'
        ));
        $scripts_add            = '';

        $packagedata            = an_packages();

        $data['title']          = TITLE . lang('menu_member_new');
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> ' . lang('menu_member_new');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packagedata']    = $packagedata;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/register';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member RO function.
     */
    public function memberro()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/ro.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'GetPINProduct.init();',
            'SearchAction.init();',
            'ROMember.init();'
        ));
        $scripts_add            = '';

        $packagedata            = an_packages();

        $data['title']          = TITLE . lang('menu_member_ro');
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> ' . lang('menu_member_ro');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packagedata']    = $packagedata;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/ro';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member List function.
     */
    public function memberlist()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'SelectChange.init();',
            'ButtonAction.init();',
            'TableAjaxMemberList.init();',
            'FV_AsStockist.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_member_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/memberlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Sponsor List function.
     */
    public function sponsorlist()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'SelectChange.init();',
            'ButtonAction.init();',
            'TableAjaxMemberList.init();',
            'FV_AsStockist.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'List Sponsor';

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/sponsorlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member Tree function.
     */
    public function membertree($id_member = '')
    {
        auth_redirect();
        $this->load->helper('shop_helper');

        $memberdata             = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_down                = false;
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if ($id_member) {
            $id_member          = an_decrypt($id_member);
            $memberdata         = an_get_memberdata_by_id($id_member);
            if ( !$memberdata ) {
                redirect(base_url('member/tree'), 'location');
            }
            if( !$is_admin ) {
                $is_down = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
                if( !$is_down ){
                    redirect(base_url('member/tree'), 'location');
                }
            } else {
                $is_down = true;
                if ( $memberdata->id <= 8 ) {
                    if ( $staff = an_get_current_staff() ) {
                        if ( $staff->access == 'partial') {
                            redirect(base_url('member/tree'), 'location');
                        }
                    }
                }
            }
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_CSS_PATH . 'tree.css?ver=' . CSS_VER_MAIN,
            // BE_PLUGIN_PATH . 'jquery-ui/jquery-ui-1.8.13.custom.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/register.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'GetProduct.init();',
            'SearchAction.init();',
            'SelectChange.init();',
            'RegisterMember.init();'
        ));
        $scripts_add            = '';

        $packagedata            = an_packages();

        $data['title']          = TITLE . lang('menu_member_tree');
        $data['title_page']     = '<i class="fa fa-sitemap mr-1"></i> ' . lang('menu_member_tree');
        $data['member']         = $current_member;
        $data['member_other']   = $memberdata;
        $data['is_admin']       = $is_admin;
        $data['is_down']        = $is_down;
        $data['packagedata']    = $packagedata;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/tree';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member Generation function.
     */
    public function membergeneration($username = '')
    {
        auth_redirect();

        $memberdata             = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if ( $username && $is_admin ) {
            $username           = trim(strtolower($username));
            $memberdata         = $this->Model_Member->get_member_by('login', $username);
            if ( !$memberdata ) {
                redirect(base_url('member/generation'), 'location');
            }

            if ( $memberdata->id <= 8 ) {
                if ( $staff = an_get_current_staff() ) {
                    if ( $staff->access == 'partial') {
                        redirect(base_url('member/generation'), 'location');
                    }
                }
            }
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'jstree/dist/themes/default/style.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'SearchAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member_generation');
        $data['title_page']     = '<i class="fa fa-sitemap mr-1"></i> ' . lang('menu_member_generation');
        $data['member']         = $current_member;
        $data['member_other']   = $memberdata;
        $data['is_admin']       = $is_admin;
        $data['packages']       = an_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/generation';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member Omzet function.
     */
    public function memberomzet($username = '')
    {
        auth_redirect();

        $memberdata             = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($is_admin) {
            if ($username) {
                $username       = trim(strtolower($username));
                $memberdata     = $this->Model_Member->get_member_by('login', $username);
            }
        } else {
            $memberdata         = $current_member;
        }

        if (!$memberdata) {
            redirect(base_url('member/lists'), 'refresh');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'SearchAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member') . ' ' . lang('omzet');
        $data['title_page']     = '<i class="fa fa-chart-line mr-1"></i> ' . lang('menu_member') . ' ' . lang('omzet');
        $data['member']         = $current_member;
        $data['member_other']   = $memberdata;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/memberomzet';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member Loan function.
     */
    public function memberloan($id = '')
    {
        $this->auth(true);

        $member_data            = '';
        $deposite_saldo         = $deposite_in = $deposite_out = 0;
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            $id = an_encrypt($id, 'decrypt');
            if ($is_admin) {
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('commission/deposite'), 'location');
                }
            }
        }

        if (!$is_admin) {
            $member_data        = $current_member;
        }

        if ($member_data) {
            $deposite_in        = $this->Model_Member->get_loan_total($member_data->id, 'deposite');
            $deposite_out       = $this->Model_Member->get_loan_total($member_data->id, 'withdraw');
            $deposite_saldo     = $deposite_in - $deposite_out;
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'SearchAction.init();',
            'TableAjaxMemberLoanList.init();',
            'FV_MemberLoan.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_member_loan');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-credit-card mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['member_other']   = $member_data;
        $data['is_admin']       = $is_admin;
        $data['deposite_in']    = $deposite_in;
        $data['deposite_out']   = $deposite_out;
        $data['deposite_saldo'] = $deposite_saldo;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/memberloanlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    // =============================================================================================
    // BOARD PAGE
    // =============================================================================================

    /**
     * Board List function.
     */
    public function boardlist()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxBoardList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_board_member_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_board_member_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'board/boardlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Board Tree function.
     */
    public function boardtree($board = 1, $id_member = '', $id_board = '')
    {
        auth_redirect();

        if ( !$board ) { redirect(base_url('board/tree1'), 'location'); }
        if ( !is_numeric($board) ) { redirect(base_url('board/tree1'), 'location'); }
        if ( $board > 3 ) { redirect(base_url('board/tree1'), 'location'); }

        $memberdata             = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $board                  = $board ? $board : 1;
        $board                  = is_numeric($board) ? $board : 1;
        $is_down                = false;
        $link_board_tree        = base_url('board/tree'.$board);
        $condition              = array('board' => $board);
        if ( $id_board ) {
            $id_board           = an_decrypt($id_board);
            $condition['id']    = $id_board; 
        }
        $memberboard            = an_get_memberboard_by('id_member', $current_member->id, $condition);

        if ( $id_member && $is_admin ) {
            $id_member          = an_decrypt($id_member);
            $memberdata         = an_get_memberdata_by_id($id_member);
            if ( !$memberdata ) {
                redirect($link_board_tree, 'location');
            }
            $memberboard        = an_get_memberboard_by('id_member', $memberdata->id, $condition);
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_CSS_PATH . 'tree.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'SearchAction.init();',
        ));
        $scripts_add            = '';

        $boardcode              = isset($memberboard->code) ? ' ('. $memberboard->code. ')' : '';
        $menu_title             = lang('menu_board_tree') .' '. $board;

        // var_dump($memberboard);
        // die();

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-sitemap mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title . $boardcode;
        $data['member']         = $current_member;
        $data['member_other']   = $memberdata;
        $data['memberboard']    = $memberboard;
        $data['is_admin']       = $is_admin;
        $data['is_down']        = $is_down;
        $data['board']          = $board;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'board/tree';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    // =============================================================================================
    // PIN PAGE
    // =============================================================================================

    /**
     * Generate PIN function.
     */
    public function pingenerate()
    {
        $this->auth(true);
        
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if ( ! $is_admin ) {
            redirect(base_url('pin/order'), 'refresh');
        }

        $headstyles             = an_headstyles(array(
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SearchAction.init();',
            'SelectChange.init();',
            'FV_PINGenerate.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_pin_create');
        $data['title_page']     = '<i class="fa fa-cart-plus mr-1"></i> '. lang('menu_pin_create');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/generate';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * PIN Order function.
     */
    public function pinorder()
    {
        auth_redirect();
        
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if ( $is_admin ) {
            redirect(base_url('pin/generate'), 'refresh');
        }

        $headstyles             = an_headstyles(array(
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'FV_PINOrder.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_pin_order');
        $data['title_page']     = '<i class="fa fa-cart-plus mr-1"></i> '. lang('menu_pin_order');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/pinorder';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Generate PIN function.
     */
    public function pintransfer()
    {
        auth_redirect();
        
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if ( $is_admin ) {
            redirect(base_url('pin/datalists'), 'refresh');
        }

        $headstyles             = an_headstyles(array(
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'SearchAction.init();',
            'GetProduct.init();',
            'FV_PINTransfer.init();'
        ));
        $scripts_add            = '';

        $pin_active             = an_member_pin($current_member->id, 'active', true);

        $data['title']          = TITLE . lang('menu_pin_transfer');
        $data['title_page']     = '<i class="fa fa-cart-plus mr-1"></i> '. lang('menu_pin_transfer');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['pin_active']     = $pin_active;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/transfer';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * PIN List function.
     */
    public function pinlist( $id = '' )
    {
        auth_redirect();

        $member_data            = '';
        $pin_active             = $pin_used = $pin_total = 0;
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        if( $id ) {
            $id = an_encrypt($id, 'decrypt');
            if( $is_admin ) {
                if ( ! $member_data = an_get_memberdata_by_id($id) ) {
                    redirect(base_url('pin/datalists'), 'location');
                }
            }
        }

        if( ! $is_admin ) {
            $member_data        = $current_member;
        }

        if ( $member_data ) {
            $pin_active         = an_member_pin($member_data->id, 'active', true);
            $pin_used           = an_member_pin($member_data->id, 'used', true);
            $pin_total          = $pin_active + $pin_used;
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'TableAjaxPINList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_pin_list');
        $data['title_page']     = '<i class="ni ni-tag mr-1"></i> '. lang('menu_pin_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['pin_active']     = $pin_active;
        $data['pin_used']       = $pin_used;
        $data['pin_total']      = $pin_total;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/pinlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * PIN Order List function.
     */
    public function pinorderlist()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',
            'TableAjaxPINOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_pin_order_list');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/orderlists';
        $data['type_content']   = 'agent';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * PIN History List function.
     */
    public function pinhistorylist()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'TableAjaxPINOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_pin_history');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'pin/historylists';
        $data['type_content']   = 'agent';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    // =============================================================================================
    // SHOPPING PAGE
    // =============================================================================================

    /**
     * Shopping Shop function.
     */
    public function shoppingshop()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_CSS_PATH . 'shop.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/shopping.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShoppingProduct.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_pin_order');

        // Get Search Get
        $s_product              = $this->input->get('product');
        $s_product              = an_isset($s_product, '', '', true);
        $s_category             = $this->input->get('category');
        $s_category             = an_isset($s_category, '', '', true);
        $s_sortby               = $this->input->get('sortby');
        $s_sortby               = an_isset($s_sortby, '', '', true);
        $s_orderby              = $this->input->get('orderby');
        $s_orderby              = an_isset($s_orderby, '', '', true);

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['s_product']      = $s_product;
        $data['s_category']     = $s_category;
        $data['s_sortby']       = $s_sortby;
        $data['s_orderby']      = $s_orderby;
        $data['type']           = 'shop';
        $data['search_url']     = base_url('shopping/shoplist');
        $data['main_content']   = 'shopping/shoplists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * History Shop Order List function.
     */
    public function shoppingshophistory($type_order = '')
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $type_order             = 'shophistory';

        if ( $is_admin ) {
            redirect( base_url('report/sales'), 'location' );
        }
        
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',
            'TableAjaxShopOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_parent            = lang('menu_shopping');
        $menu_title             = lang('menu_shopping_history');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_parent']    = $menu_parent;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['type_content']   = $type_order;
        $data['main_content']   = 'shopping/shoporderlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Shopping Cart function.
     */
    public function shoppingcart()
    {
        auth_redirect();
        $this->load->helper('shop_helper');

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $is_dropshipper         = as_dropshipper($current_member);
        if( $is_dropshipper ) redirect(base_url('dashboard'), 'location');

        // Check Message
        $message                = $this->session->userdata('message_checkout');
        if ( $message ) {
            $this->session->unset_userdata('message_checkout');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/shopping.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShoppingCart.init();'
        ));
        $scripts_add            = '';

        $cfg_min_order_qty      = 0;
        $cfg_min_order_nominal  = 0;
        if ( $current_member->as_stockist > 0 ) {
            $cfg_min_order_qty      = get_option('cfg_stockist_minimal_order_qty');
            $cfg_min_order_qty      = is_numeric($cfg_min_order_qty) ? $cfg_min_order_qty : 0;
            $cfg_min_order_nominal  = get_option('cfg_stockist_minimal_order_nominal');
            $cfg_min_order_nominal  = is_numeric($cfg_min_order_nominal) ? $cfg_min_order_nominal : 0;
        }

        $menu_title             = lang('menu_shopping_cart');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['message']        = $message;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['cart_content']   = an_cart_contents();
        $data['currency']       = config_item('currency');
        $data['main_content']   = 'shopping/cart';
        $data['cfg_min_order_qty']      = $cfg_min_order_qty;
        $data['cfg_min_order_nominal']  = $cfg_min_order_nominal;

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Shopping Checkout function.
     */
    public function shoppingcheckout()
    {
        auth_redirect();
        $this->load->helper('shop_helper');

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        $checkStockist          = '';
        $saldo                  = 0;
        
        $cfg_min_order_qty      = 0;
        $cfg_min_order_nominal  = 0;
        if ( $current_member->as_stockist > 0 ) {
            $cfg_min_order_qty      = get_option('cfg_stockist_minimal_order_qty');
            $cfg_min_order_qty      = is_numeric($cfg_min_order_qty) ? $cfg_min_order_qty : 0;
            $cfg_min_order_nominal  = get_option('cfg_stockist_minimal_order_nominal');
            $cfg_min_order_nominal  = is_numeric($cfg_min_order_nominal) ? $cfg_min_order_nominal : 0;
        }

        if ( $is_admin ) {
            redirect(base_url('dashboard'), 'refresh');
        }

        // Check Cart Content
        $cart_content           = an_cart_contents();
        if ( !$cart_content ) {
            redirect(base_url('cart'), 'refresh');
        }

        if ( $cart_content['has_error'] ) {
            redirect(base_url('cart'), 'refresh');
        }

        if ( $current_member->as_stockist > 0 ) {
            if ( $cfg_min_order_qty || $cfg_min_order_nominal ) {
                if ( $cfg_min_order_qty > $this->cart->total_items() ) {
                    $this->session->set_userdata('message_checkout', 'Anda tidak dapat checkout. Silahkan pesan dengan syarat minimal qty order.');
                    redirect(base_url('shopping/cart'), 'refresh');
                }
                if ( $cfg_min_order_nominal > $this->cart->total() ) {
                    $this->session->set_userdata('message_checkout', 'Anda tidak dapat checkout. Silahkan pesan dengan syarat minimal belanja.');
                    redirect(base_url('shopping/cart'), 'refresh');
                }
            }
        } else {
            // Check Apply Stockist
            /*
            $checkStockist = an_check_agent(true);
            if ( $current_member->as_stockist == 0 ) {
                if ( ! $checkStockist ) {
                    $this->session->set_userdata('message_find_seller', 'Anda belum pilih <b>Stockist</b>. Silahkan pilih <b>Stockist</b> terlebih dahulu untuk memesan produk !');
                    redirect(base_url('find-agency'), 'refresh');
                }
            }
            */
        }

        // $deposite_in        = $this->Model_Bonus->get_saldo_shop_total($current_member->id, 'IN'); 
        // $deposite_out       = $this->Model_Bonus->get_saldo_shop_total($current_member->id, 'OUT');
        $deposite_in        = 0; 
        $deposite_out       = 0;
        $saldo              = $deposite_in - $deposite_out;
        $saldo              = max(0, $saldo);

        if ( $this->session->userdata('checkout_code') ) {
            $this->session->unset_userdata('checkout_code');
        }
        
        $checkout_code          = $cart_content['product_type'] . $current_member->id;
        $this->session->set_userdata('checkout_code', $checkout_code);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/shopping.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SelectChange.init();',
            'SelectChange.initCourier();',
            'ShoppingCheckout.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'Checkout';

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-bag-17 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['saldo']          = $saldo;
        $data['seller']         = $checkStockist;
        $data['cart_content']   = an_cart_contents();
        $data['currency']       = config_item('currency');
        $data['main_content']   = 'shopping/checkout';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Shopping find-agent function.
     */
    public function shoppingfindagent()
    {
        auth_redirect();
        $this->load->helper('shop_helper');

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ( $is_admin ) {
            redirect(base_url('dashboard'), 'refresh');
        }

        $cart_content           = an_cart_contents();
        if ( !$cart_content ) {
            redirect(base_url('cart'), 'refresh');
        }

        if ( $cart_content['has_error']) {
            redirect(base_url('cart'), 'refresh');
        }

        // Check Message
        $message                = $this->session->userdata('message_find_seller');
        if ( $message ) {
            $this->session->unset_userdata('message_find_seller');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SelectChange.init();',
            'TableAjaxFindAgent.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'Find Sub/Agency';

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['message']        = $message;
        $data['main_content']   = 'shopping/findagent';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // PRODUCT MANAGE PAGE
    // =============================================================================================

    /**
     * Product New function.
     */
    public function productnew()
    {
        $this->auth(true);

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_new');
        $data['title_page']     = '<i class="fa fa-plus mr-1 mr-1"></i> ' . lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['form_page']      = 'new';
        $data['form_title']     = '<i class="fa fa-plus mr-1 mr-1"></i> ' . lang('menu_product_new');
        $data['main_content']   = 'product/productform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Edit function.
     */
    public function productedit($id = 0)
    {
        $this->auth(true);
        if (!$id) {
            redirect(base_url('productmanage/productlist'), 'refresh');
        }

        $id_product     = an_decrypt($id);
        if (!$data_product = an_products($id_product)) {
            redirect(base_url('productmanage/productlist'), 'refresh');
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_edit');
        $data['title_page']     = '<i class="fa fa-edit mr-1 mr-1"></i> ' . lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['data_product']   = $data_product;
        $data['form_page']      = 'edit';
        $data['form_title']     = '<i class="fa fa-edit mr-1 mr-1"></i> ' . lang('menu_product_edit');
        $data['main_content']   = 'product/productform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Stock Form function.
     */
    public function productstockform($form = '', $id = 0)
    {
        $this->auth(true);
        $data_product           = '';
        
        if ( ! $form ) {
            redirect(base_url('productmanage/historystockin'), 'refresh');
        }

        if ( $form != 'new' && $form != 'edit' ) {
            redirect(base_url('productmanage/historystockin'), 'refresh');
        }

        if ( $form == 'edit' ) {
            if ( ! $id ) {
                redirect(base_url('productmanage/historystockin'), 'refresh');
            }

            $id_product     = an_decrypt($id);
            if ( ! $data_product = $this->Model_Product->get_product_stock_by('id', $id_product) ) {
                redirect(base_url('productmanage/historystockin'), 'refresh');
            }
        }

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'ProductManage.init();',
        ));
        $scripts_add            = '';

        $menu_title             = ( $form == 'edit' ) ? 'Edit Stok Produk' : 'Tambah Stok Produk';
        $title_page             = ( $form == 'edit' ) ? '<i class="fa fa-edit mr-1 mr-1"></i> ' : '<i class="fa fa-plus mr-1 mr-1"></i> ';
        $title_page             = $title_page . $menu_title;

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = $title_page;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['data_product']   = $data_product;
        $data['form_page']      = ( $form == 'edit' ) ? 'edit' : 'new';
        $data['form_title']     = $title_page;
        $data['main_content']   = 'product/productstockform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product List function.
     */
    public function productlist()
    {
        $this->auth(true);

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_product_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/productlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Category List function.
     */
    public function categorylist()
    {
        $this->auth(true);

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ProductManage.initCategory();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_category');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_product_category');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/categorylists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product In List function.
     */
    public function productinlist()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'ProductManage.init();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'Input Stock In';

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/productinlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Stock List function.
     */
    public function productstocklist($id = '')
    {
        $this->auth(true);

        $productdata            = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            $id = an_encrypt($id, 'decrypt');
            if ($is_admin) {
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('commission/bonus'), 'location');
                }
            }
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'Stock '. lang('product');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/productstocklists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // PROMO CODE PAGE
    // =============================================================================================

    /**
     * Promo Code Global function.
     */
    public function promocodeglobal()
    {
        $this->auth(true);

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'PromoCodeManage.init();',
            'TableAjaxPromoCodeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_promo_global');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_promo_code');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'promocode/global';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Promo Code Spesific function.
     */
    public function promocodespesific()
    {
        $this->auth(true);

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'PromoCodeManage.init();',
            'TableAjaxPromoCodeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_promo_spesific');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_promo_code');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'promocode/spesific';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // COMMISSION PAGE
    // =============================================================================================

    /**
     * Bonus function.
     */
    public function bonus($id = '')
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            $id = an_encrypt($id, 'decrypt');
            if ($is_admin) {
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('commission/bonus'), 'location');
                }
            }
        }

        if ($is_admin) {
            $dataBonus          = $this->Model_Bonus->get_total_deposite_bonus();
            $bonus_total        = isset($dataBonus->total_bonus) ? $dataBonus->total_bonus : 0;
        } else {
            $member_data        = $current_member;
        }

        if ($member_data) {
            $bonus_total        = $this->Model_Bonus->get_total_bonus_member($member_data->id);
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'TableAjaxCommissionList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_bonus');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['bonus_total']    = $bonus_total;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/bonuslists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Bonus function.
     */
    public function deposite($id = '')
    {
        auth_redirect();

        $member_data            = '';
        $deposite_in            = $deposite_out = $deposite_saldo = 0;
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            $id = an_encrypt($id, 'decrypt');
            if ($is_admin) {
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('commission/deposite'), 'location');
                }
            }
        }

        if (!$is_admin) {
            $member_data        = $current_member;
        }

        if ($member_data) {
            $deposite_in        = $this->Model_Bonus->get_ewallet_total($member_data->id, 'IN');
            $deposite_out       = $this->Model_Bonus->get_ewallet_total($member_data->id, 'OUT');
            $deposite_saldo     = $deposite_in - $deposite_out;
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'TableAjaxDepositeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_deposite');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['deposite_in']    = $deposite_in;
        $data['deposite_out']   = $deposite_out;
        $data['deposite_saldo'] = $deposite_saldo;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/depositelists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Commission function.
     */
    public function commission($id = '')
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            $id = an_encrypt($id, 'decrypt');
            if ($is_admin) {
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('commission/commission'), 'location');
                }
            }
        }

        if (!$is_admin) {
            $member_data        = $current_member;
        }

        $start_date             = date('Y-m-01');
        $end_date               = date('Y-m-d');
        $s_daterange            = $this->input->get('daterange');
        if( !empty($s_daterange) ) {
            $daterange          = explode('|', $s_daterange);
            if ( $daterange ) {
                $start_date     = isset($daterange[0]) ? $daterange[0] : date('Y-m-01');
                $end_date       = isset($daterange[1]) ? $daterange[1] : date('Y-m-d');
            }
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'HandleDatepicker.init();',
            'TableAjaxCommissionssList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_commission');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['start_date']     = $start_date;
        $data['end_date']       = $end_date;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/commissionlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Withdraw function.
     */
    public function withdraw()
    {
        auth_redirect();

        $id_member              = 0;
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            $id_member          = $current_member->id;
        }

        $total_withdraw         = $total_transfer = $total_bonus = $total_deposite = 0;
        if ($data_deposite = $this->Model_Bonus->get_total_deposite_bonus($id_member)) {
            $total_bonus        = $data_deposite->total_bonus;
            $total_withdraw     = $data_deposite->total_wd;
            $total_transfer     = $data_deposite->total_wd_receipt;
            $total_deposite     = $data_deposite->total_deposite;
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxWithdrawList.init();',
            'FV_Withdraw.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_withdraw');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['total_bonus']    = $total_bonus;
        $data['total_withdraw'] = $total_withdraw;
        $data['total_transfer'] = $total_transfer;
        $data['total_deposite'] = $total_deposite;
        $data['currency']       = config_item('currency');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/withdrawlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // FLIP PAGE
    // =============================================================================================

    /**
     * Flip Transaction function.
     */
    public function fliptrx()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            redirect(base_url('dashboard'), 'location');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxFlipList.init();'
        ));
        $scripts_add            = '';

        $total_topup            = $this->Model_Flip->count_total_topup();
        $total_trf              = $this->Model_Flip->count_total_trx_done();
        $total_trf_fee          = $this->Model_Flip->count_total_trx_fee();
        if ( $trf_pending = $this->Model_Flip->count_total_trx_pending() ) {
            $total_trf          += $trf_pending->total_pending;
            $total_trf_fee      += $trf_pending->total_fee;
        }

        $saldo                  = $total_topup - $total_trf - $total_trf_fee;
        $saldo                  = $saldo;

        $menu_title             = '['. lang('menu_flip') .'] '. lang('menu_flip_trx');
        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['total_topup']    = $total_topup;
        $data['total_trf']      = $total_trf;
        $data['total_trf_fee']  = $total_trf_fee;
        $data['saldo']          = $saldo;
        $data['currency']       = config_item('currency');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'flip/transactionlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Flip Topup function.
     */
    public function fliptopup()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            redirect(base_url('dashboard'), 'location');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxFlipList.init();',
            'FV_Flip.init();'
        ));
        $scripts_add            = '';

        $total_topup            = $this->Model_Flip->count_total_topup();
        $total_trf              = $this->Model_Flip->count_total_trx_done();
        $total_trf_fee          = $this->Model_Flip->count_total_trx_fee();
        if ($trf_pending       = $this->Model_Flip->count_total_trx_pending()) {
            $total_trf          += $trf_pending->total_pending;
            $total_trf_fee      += $trf_pending->total_fee;
        }

        $saldo                  = $total_topup - $total_trf - $total_trf_fee;
        $saldo                  = $saldo;

        $menu_title             = '['. lang('menu_flip') .'] '. lang('menu_flip_topup');
        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['total_topup']    = $total_topup;
        $data['total_trf']      = $total_trf;
        $data['total_trf_fee']  = $total_trf_fee;
        $data['saldo']          = $saldo;
        $data['currency']       = config_item('currency');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'flip/topuplists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Flip Inquiry function.
     */
    public function flipinquiry()
    {
        auth_redirect();
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            redirect(base_url('dashboard'), 'location');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxFlipList.init();'
        ));
        $scripts_add            = '';

        $menu_title             = '['. lang('menu_flip') .'] '. lang('menu_flip_inquiry');
        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'flip/inquirylists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    // =============================================================================================
    // FASPAY PAGE
    // =============================================================================================

    /**
     * Faspay Transaction function.
     */
    public function faspaytrx()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            redirect(base_url('dashboard'), 'location');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxFaspayList.init();'
        ));
        $scripts_add            = '';
        
        include FASPAY_SENDME_LIB;
        
        $fp_env                 = get_option('fp_env');
        $sendme                 = new SendMe();
        if( $fp_env == "prod" ){ $sendme->enableProd(); }
        $response               = $sendme->balance_inquiry();
        $response               = (object) $response;
        $faspay_balance         = 0;
        if( $response->response_code == "00" ){
            if( $response->status == 2 ){
                $faspay_balance = $response->available_balance;
            }
        }

        $menu_title             = '['. lang('menu_faspay') .'] '. lang('menu_faspay_trx');
        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['currency']       = config_item('currency');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['faspay_balance'] = $faspay_balance;
        $data['main_content']   = 'faspay/transactionlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Faspay Inquiry function.
     */
    public function faspayinquiry()
    {
        auth_redirect();
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            redirect(base_url('dashboard'), 'location');
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxFaspayList.init();'
        ));
        $scripts_add            = '';

        $menu_title             = '['. lang('menu_faspay') .'] '. lang('menu_faspay_inquiry');
        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'faspay/inquirylists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // REPORT GROUP
    // =============================================================================================

    /**
     * Member Registrations function.
     */
    public function registration()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_report_register');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_report_register') . ' ' . lang('agent');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/registrationlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member RO function.
     */
    public function historyro()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_report_ro');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> ' . lang('menu_report_ro');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/rolists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Sales function.
     */
    public function sales()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',  
            'TableAjaxShopOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_sales');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/saleslists';
        $data['type_content']   = 'admin';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Sales Stockist function.
     */
    public function salesstockist()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',
            'TableAjaxShopOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = ($is_admin ? lang('sales') .' (Stockist)' : lang('menu_report_sales'));

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/saleslists';
        $data['type_content']   = 'stockist';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Omzet Posting function.
     */
    public function omzet()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxOmzetList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_omzet_posting');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/omzetlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Omzet Product Order function.
     */
    public function omzetorder()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxOmzetList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_omzet_order');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/omzetorderlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Reward function.
     */
    public function reward()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxQualificationList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_reward');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-gift mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/rewardlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Rank Qualification 
     */
    public function rankqualification()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxRewardList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_rank_qualification');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-gift mr-1"></i> ' . $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/qualificationlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * FAQ function.
     */
    function faq()
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

        $data['title']          = TITLE . lang('menu_faq');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'faq';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // PROFILE, ERROR, COMINGSOON PAGE
    // =============================================================================================

    /**
     * Profile Page function.
     */
    public function profile($id = 0)
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id) {
            if ($is_admin) {
                $id             = an_decrypt($id);
                if (!$member_data = an_get_memberdata_by_id($id)) {
                    redirect(base_url('profile'), 'location');
                }
            } else {
                redirect(base_url('profile'), 'location');
            }
        }

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'jquery-ui/jquery-ui-1.8.13.custom.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = an_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SelectChange.init();',
            'Profile.init();',
            'FV_Profile.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . 'Profil Member';
        $data['member']         = $current_member;
        $data['member_other']   = $member_data;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/profile';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Error 404 Page function.
     */
    public function invoice($id = '')
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = '';
        $scripts_add            = '';

        $invoice                = '';
        $shop_order             = false;
        $order_id               = $id ? an_decrypt($id) : false;
        if ($order_id) {
            $shop_order         = $this->Model_Shop->get_shop_orders($order_id);
            if ( !$is_admin && ( $current_member->id !== $shop_order->id_member && $current_member->id !== $shop_order->id_stockist ) ) {
                $shop_order     = false;
            }
        }

        if ($shop_order) {
            $invoice            = $shop_order->invoice;
        }

        $data['title']          = TITLE . 'Invoice ' . $invoice;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['shop_order']     = $shop_order;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;

        if ($shop_order) {
            $data['main_content'] = an_notification_shop_template($shop_order, 'Informasi Pemesanan Produk', 'member', false);
            $this->load->view(VIEW_BACK . 'invoice', $data);
        } else {
            $data['main_content'] = 'error_404';
            $this->load->view(VIEW_BACK . 'template_index', $data);
        }
    }

    /**
     * Error 404 Page function.
     */
    public function error_404()
    {
        // This is for AJAX request
        if ( $this->input->is_ajax_request() ) {
            // Set JSON data
            $data = array('success' => false, 'status' => 'error', 'message' => 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.', 'data' => '');
            die(json_encode($data));
        }

        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = an_headstyles(array(
            // Default CSS Plugin
        ));

        $loadscripts            = an_scripts(array(
            // Default JS Plugin
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = '';
        $scripts_add            = '';

        $data['title']          = TITLE . '404 Page Not Found';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'error_404';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // ---------------------------------------------------------------------------------------------

    /**
     * Coming Soon View function.
     */
    function comingsoon()
    {
        auth_redirect();

        $current_member         = an_get_current_member();
        $is_admin               = as_administrator($current_member);

        $data['title']          = TITLE . 'Coming Soon';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['main_content']   = 'pages/comingsoon';

        $this->load->view(VIEW_BACK . 'template', $data);
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // ASSUME AND REVERT ACCOUNT
    // =============================================================================================

    /**
     * Assume to member account
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $member_id. Member ID.
     * @author Iqbal
     */
    function assume($member_id)
    {
        $this->auth(true);
        $current_member = an_get_current_member();
        $uid            = $current_member->username;
        $type           = 'admin';
        if ($staff = an_get_current_staff()) {
            $uid        = $staff->username;
            $type       = 'staff';
        }
        $id         = an_encrypt($member_id, 'decrypt');
        $log_desc   = array('cookie' => $_COOKIE, 'type' => $type);
        an_log_action('ASSUME', $id, $uid, json_encode($log_desc));
        an_assume($id);
    }

    /**
     * Revert account
     *
     * @since 1.0.0
     * @access public
     *
     * @author ahmad
     */
    function revert()
    {
        an_revert();
    }

    /**
     * Switch Language function.
     */
    function switchlang($lang = '')
    {
        if ($this->input->is_ajax_request()) {
            die('true');
        } else {
            $url  = $this->uri->uri_string();
            if ($url == 'switchlang') {
                redirect(base_url('dashboard'), 'refresh');
            } else {
                redirect($url);
            }
        }
    }

    // Ubah foto profile
    function ubah_foto_profile()
    {
        auth_redirect();
        $current_member = an_get_current_member();
        $file = upload_file('file', 'images', ASSET_FOLDER . '/upload/profile_picture/');
        if ($file != 'error_upload' && $file != 'error_extension' && $file != 'error' && $file != 'empty') {
            $data_member = array(
                'id' => $current_member->id,
                'photo' => $file,
                'datemodified' => date('Y-m-d H:i:s')
            );
            $this->Model_Member->update_member($data_member);
            //set flashdata
            $this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Upload Foto Profile Berhasil!</div>');
            redirect(base_url('profile'));
        } else {
            //set flashdata
            $this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Upload Foto Profile Gagal!</div>');
            redirect(base_url('profile'));
        }
    }
    // ---------------------------------------------------------------------------------------------
}

/* End of file Backend.php */
/* Location: ./application/controllers/Backend.php */
