<?php
$sidebar        = '';
if (as_administrator($member)) {
    $sidebar    = array(
        array(
            'title' => lang('menu_dashboard'),
            'nav'   => 'dashboard',
            'link'  => base_url('dashboard'),
            'icon'  => 'ni ni-tv-2',
            'roles' => array(),
            'sub'   => false,
        ),
        array(
            'title' => lang('menu_member'),
            'nav'   => 'member',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-single-02',
            'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
            'sub'   => array(
                array(
                    'title' => lang('menu_member_new'),
                    'nav'   => 'new',
                    'link'  => base_url('member/new'),
                    'icon'  => 'fa-user-plus',
                    'roles' => array(STAFF_ACCESS2),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_member_list'),
                    'nav'   => 'lists',
                    'link'  => base_url('member/lists'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                    'sub'   => false,
                ),
                array(
                    'title' => 'List Sponsor',
                    'nav'   => 'sponsorlists',
                    'link'  => base_url('member/sponsorlists'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS1),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_member_generation'),
                    'nav'   => 'generation',
                    'link'  => base_url('member/generation'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                    'sub'   => false,
                ),
            ),
        ),
        array(
            'title' => lang('menu_financial'),
            'nav'   => 'commission',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-credit-card',
            'roles' => array(STAFF_ACCESS6, STAFF_ACCESS7),
            'sub'   => array(
                array(
                    'title' => lang('menu_financial_bonus'),
                    'nav'   => 'bonus',
                    'link'  => base_url('commission/bonus'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS6),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_financial_deposite'),
                    'nav'   => 'deposite',
                    'link'  => base_url('commission/deposite'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS6),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_financial_commission'),
                    'nav'   => 'commission',
                    'link'  => base_url('commission/commission'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS6),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_financial_withdraw'),
                    'nav'   => 'withdraw',
                    'link'  => base_url('commission/withdraw'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS7),
                    'sub'   => false,
                ),
            )
        ),
        array(
            'title' => lang('menu_faspay'),
            'nav'   => 'faspay',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-folder-17',
            'roles' => array(STAFF_ACCESS13),
            'sub'   => array(
                array(
                    'title' => lang('menu_faspay_trx'),
                    'nav'   => 'faspaytrx',
                    'link'  => base_url('faspay/faspaytrx'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS13),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_faspay_inquiry'),
                    'nav'   => 'faspayinquiry',
                    'link'  => base_url('faspay/faspayinquiry'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS13),
                    'sub'   => false,
                ),
            )
        ),
        array(
            'title' => lang('menu_report'),
            'nav'   => 'report',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-single-copy-04',
            'roles' => array(STAFF_ACCESS8, STAFF_ACCESS9, STAFF_ACCESS10, STAFF_ACCESS11),
            'sub'   => array(
                array(
                    'title' => lang('menu_report_register'),
                    'nav'   => 'registration',
                    'link'  => base_url('report/registration'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS8),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_report_ro'),
                    'nav'   => 'historyro',
                    'link'  => base_url('report/historyro'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS8),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_report_sales'),
                    'nav'   => 'sales',
                    'link'  => base_url('report/sales'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS9, STAFF_ACCESS10),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_report_omzet_posting'),
                    'nav'   => 'omzet',
                    'link'  => base_url('report/omzet'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS11),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_report_omzet_order'),
                    'nav'   => 'omzetorder',
                    'link'  => base_url('report/omzetorder'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS11),
                    'sub'   => false,
                ),
            )
        ),
        array(
            'title' => lang('menu_product'),
            'nav'   => 'productmanage',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-bag-17',
            'roles' => array(STAFF_ACCESS12),
            'sub'   => array(
                array(
                    'title' => lang('menu_product_list'),
                    'nav'   => 'productlist',
                    'link'  => base_url('productmanage/productlist'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS12),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_product_category'),
                    'nav'   => 'categorylist',
                    'link'  => base_url('productmanage/categorylist'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS12),
                    'sub'   => false,
                ),
                array(
                    'title' => 'Input Stock Pusat',
                    'nav'   => 'historystockin',
                    'link'  => base_url('productmanage/historystockin'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS12),
                    'sub'   => false,
                ),
                array(
                    'title' => 'Laporan Stock Pusat',
                    'nav'   => 'productstocklist',
                    'link'  => base_url('productmanage/productstocklist'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS12),
                    'sub'   => false,
                ),
            )
        ),
        array(
            'title' => lang('menu_pin'),
            'nav'   => 'productdatalists',
            'link'  => base_url('productdatalists'),
            'icon'  => 'ni ni-bag-17',
            'roles' => array(STAFF_ACCESS4, STAFF_ACCESS5),
            'sub'   => false,
        ),
        array(
            'title' => lang('menu_setting_staff'),
            'nav'   => 'staff',
            'link'  => base_url('staff'),
            'icon'  => 'ni-circle-08',
            'roles' => array(STAFF_ACCESS14),
            'sub'   => false,
        ),
        array(
            'title' => lang('menu_faq'),
            'nav'   => 'faq',
            'link'  => base_url('faq'),
            'icon'  => 'ni ni-bulb-61',
            'roles' => array(STAFF_ACCESS16),
            'sub'   => false,
        ),
        array(
            'title' => lang('menu_setting'),
            'nav'   => 'setting',
            'link'  => 'javascript:;',
            'icon'  => 'ni ni-palette',
            'roles' => array(STAFF_ACCESS15),
            'sub'   => array(
                array(
                    'title' => lang('menu_setting_general'),
                    'nav'   => 'general',
                    'link'  => base_url('setting/general'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS15),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_setting_notification'),
                    'nav'   => 'notification',
                    'link'  => base_url('setting/notification'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS15),
                    'sub'   => false,
                ),
                array(
                    'title' => lang('menu_setting_withdraw'),
                    'nav'   => 'withdraw',
                    'link'  => base_url('setting/withdraw'),
                    'icon'  => '',
                    'roles' => array(STAFF_ACCESS15),
                    'sub'   => false,
                )
            ),
        ),
    );
} else {
    $reseller       = ( $member->type_status == TYPE_STATUS_RESELLER ? true : false );
    $dropshipper    = ( $member->type_status == TYPE_STATUS_RESELLER ? true : false );
    
    if( $reseller ){
        $sidebar    = array(
            array(
                'title' => lang('menu_dashboard'),
                'nav'   => 'dashboard',
                'link'  => base_url('dashboard'),
                'icon'  => 'ni ni-tv-2',
                'roles' => array(),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_member'),
                'nav'   => 'member',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-single-02',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_member_new'),
                        'nav'   => 'new',
                        'link'  => base_url('member/new'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_tree'),
                        'nav'   => 'tree',
                        'link'  => base_url('member/tree'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_generation'),
                        'nav'   => 'generation',
                        'link'  => base_url('member/generation'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                ),
            ),
            array(
                'title' => lang('menu_pin'),
                'nav'   => 'productdatalists',
                'link'  => base_url('productdatalists'),
                'icon'  => 'ni ni-bag-17',
                'roles' => array(),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_shopping'),
                'nav'   => 'shopping',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-bag-17',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_shopping_shop'),
                        'nav'   => 'shoplist',
                        'link'  => DOMAIN_SHOP . '/auth?signature=' . an_encrypt($member->id . '-' . $member->username),
                        'icon'  => '',
                        'newtab'=> 'newtab',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_shopping_history'),
                        'nav'   => 'shophistorylist',
                        'link'  => base_url('shopping/shophistorylist'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_financial'),
                'nav'   => 'commission',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-credit-card',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_financial_bonus'),
                        'nav'   => 'bonus',
                        'link'  => base_url('commission/bonus'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_deposite'),
                        'nav'   => 'deposite',
                        'link'  => base_url('commission/deposite'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_withdraw'),
                        'nav'   => 'withdraw',
                        'link'  => base_url('commission/withdraw'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_report'),
                'nav'   => 'report',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-single-copy-04',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_report_register'),
                        'nav'   => 'registration',
                        'link'  => base_url('report/registration'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_report_sales'),
                        'nav'   => 'order',
                        'link'  => base_url('report/order'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                )
            )
        );
    }else{
        $sidebar    = array(
            array(
                'title' => lang('menu_dashboard'),
                'nav'   => 'dashboard',
                'link'  => base_url('dashboard'),
                'icon'  => 'ni ni-tv-2',
                'roles' => array(),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_financial'),
                'nav'   => 'commission',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-credit-card',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_financial_bonus'),
                        'nav'   => 'bonus',
                        'link'  => base_url('commission/bonus'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_deposite'),
                        'nav'   => 'deposite',
                        'link'  => base_url('commission/deposite'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_withdraw'),
                        'nav'   => 'withdraw',
                        'link'  => base_url('commission/withdraw'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_report'),
                'nav'   => 'report',
                'link'  => 'javascript:;',
                'icon'  => 'ni ni-single-copy-04',
                'roles' => array(),
                'sub'   => array(
                    array(
                        'title' => lang('menu_report_sales'),
                        'nav'   => 'order',
                        'link'  => base_url('report/order'),
                        'icon'  => '',
                        'roles' => array(),
                        'sub'   => false,
                    ),
                )
            )
        );
    }
}

$active_page    = ($this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : '');
$active_sub     = ($this->uri->segment(2, 0) ? $this->uri->segment(2, 0) : '');
