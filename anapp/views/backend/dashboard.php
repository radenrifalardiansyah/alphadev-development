<?php
$currency       = config_item('currency');
$time           = date('H');
$hi             = '';
$name           = $member->name;
$dashboard_admin = true;

if ($staff = an_get_current_staff()) {
    $name   = $staff->name;
    if ( $staff->access == 'partial' ) {
        $dashboard_admin = false;
    }
}

if ($time >= '00' && $time <= '09') {
    $hi = lang('morning');
} elseif ($time > '09' && $time <= '14') {
    $hi = lang('daylight');
} elseif ($time > '14' && $time <= '18') {
    $hi = lang('afternoon');
} elseif ($time > '18' && $time <= '24') {
    $hi = lang('evening');
}

$referral_link_info     = '';
$referral_link_store    = '';
if ($is_admin) {
    $dataOmzet          = $this->Model_Member->get_total_member_omzet();
    $dataBonus          = $this->Model_Bonus->get_total_deposite_bonus();
    $condition          = ' AND `status` = 0 AND id_stockist = 0';
    $dataOrder          = $this->Model_Shop->get_total_shop_order($condition);

    $total_bonus        = isset($dataBonus->total_bonus) ? $dataBonus->total_bonus : 0;
    $total_omzet        = isset($dataOmzet->total_amount) ? $dataOmzet->total_amount : 0;
    $percentage         = 0;
    if ( $total_omzet ) {
        $percentage     = ($total_bonus / $total_omzet) * 100;
    }
} else {
    $cfg_max_level      = config_item('max_gen_level');
    $max_level          = $member->level + $cfg_max_level;

    $deposite           = $this->Model_Bonus->get_ewallet_deposite($member->id);
    $bonus              = $this->Model_Bonus->get_total_bonus_member($member->id);
    $condition          = ' AND id_member = ' . $member->id . ' AND `status` = 1 AND DATE_FORMAT(dateconfirmed, "%Y-%m") = "' . date('Y-m') . '"';
    $dataOrder          = $this->Model_Shop->get_total_shop_order($condition);
    $pin_active         = an_member_pin($member->id, 'active', true);
    
    $referral_link_info = SCHEMA . $member->username . '.'.DOMAIN_NAME_SHOP;
    $referral_link_store= SCHEMA . $member->username . '.'.DOMAIN_NAME_STORE;
}
?>
<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-12">
                    <?php
                    $welcome_text   = lang('welcome_text');
                    $welcome_text   = str_replace("%hi%", $hi, $welcome_text);
                    $welcome_text   = str_replace("%name%", ucwords(strtolower($name)), $welcome_text);
                    echo $welcome_text;
                    
                    if( !$is_admin ){
                        if( $member->package != PACKAGE_DROPSHIPPER ){
                            $cfg_rank   = config_item('member_rank');
                            $rank       = $cfg_rank[$member->rank];
                            $rank_text  = lang('rank_text');
                            $rank_text  = str_replace("%rank%", strtoupper($rank), $rank_text);
                            echo $rank_text;
                        }
                    }
                    ?>
                </div>
            </div>
            <?php if ( $is_admin ) { ?>
                <div class="row">
                    <?php if ( $dashboard_admin ) { ?>
                        <div class="col-xl-6 col-md-6 col-xs-12">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('reseller_total'); ?></h5>
                                            <span class="h5 font-weight-bold mb-0"><?php echo an_accounting($this->Model_Member->count_data('active',TYPE_STATUS_RESELLER)); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-circle-08"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="<?php echo base_url('member/lists'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-xs-12">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('dropshipper_total'); ?></h5>
                                            <span class="h5 font-weight-bold mb-0"><?php echo an_accounting($this->Model_Member->count_data('active',TYPE_STATUS_DROPSHIPPER)); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-circle-08"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="<?php echo base_url('member/lists'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-4 col-md-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('omzet_total'); ?></h5>
                                            <span class="h5 font-weight-bold mb-0"><?php echo isset($total_omzet) ? an_accounting($total_omzet) : 0; ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                <i class="ni ni-chart-bar-32"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="<?php echo base_url('report/omzet'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('bonus_total'); ?></h5>
                                            <span class="h5 font-weight-bold mb-0"><?php echo an_accounting($total_bonus); ?><br></span>
                                            <span class="h6 font-weight-bold text-info mb-0"><?php echo an_number($percentage); ?> %</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="ni ni-money-coins"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 mb-0 text-sm">
                                        <a href="<?php echo base_url('commission/bonus'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('order_total'); ?></h5>
                                            <span class="h5 font-weight-bold mb-0">
                                                <?php echo an_accounting($dataOrder->total_trx); ?> 
                                                <small class="text-muted ml-2">PENDING</small>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="ni ni-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <a href="<?php echo base_url('report/sales'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php if ( $staff ) { ?>
                            <div class="col-md-12">
                                <div class="card card-stats">
                                    <!-- Card body -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col pr-0">
                                                <h5 class="card-title text-uppercase mb-2"><?php echo lang('your_access'); ?></h5>
                                                <?php 
                                                    if ($staff->access == 'partial') { 
                                                        $access = array();
                                                        if ($staff->role) {
                                                            $access = maybe_unserialize($staff->role);
                                                        }
                                                        if (is_array($access)) {
                                                            array_walk($access, function (&$val) {
                                                                $config = config_item('staff_access_text');
                                                                $val = $config[$val];
                                                            });
                                                            echo "<ul>";
                                                            foreach ($access as $key => $value) {
                                                                echo '<li><span class="h5 mb-1">'. $value .'</span></li>';
                                                            }
                                                            echo "</ul>";
                                                        }
                                                    } 
                                                ?>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                    <i class="ni ni-badge"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('new_member'); ?></h5>
                                            <span class="h3 font-weight-bold mb-0">
                                                <?php 
                                                    $new_member = $this->Model_Member->new_member('active'); 
                                                    if ( $new_member ) {
                                                        echo $new_member->username;
                                                    } else {
                                                        echo "-";
                                                    }
                                                ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col pr-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('member_total'); ?></h5>
                                            <span class="h3 font-weight-bold mb-0"><?php echo an_accounting($this->Model_Member->count_data('active')); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                                <i class="ni ni-circle-08"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } elseif(!$is_admin) { ?>
                <div class="row">
                    <div class="col-xl-12 col-md-12 card-wrapper">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-0">Copy Reseller Link</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body alert-wrapper-copy-referral">
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-control-label">Rekomen Info</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-link"></i></span>
                                            </div>
                                            <input type="text" name="referral_link" id="referral_link" class="form-control text-lowercase" value="<?=$referral_link_info?>" 
                                            placeholder="Reseller Referral Link" autocomplete="off" />
                                            <span class="input-group-append">
                                                <button class="btn btn-default" type="button" id="btn_copy_referral_link"><i class="fa fa-copy"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-control-label">Rekomen Store</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-link"></i></span>
                                            </div>
                                            <input type="text" name="referral_link_store" id="referral_link_store" class="form-control text-lowercase" value="<?=$referral_link_store?>" 
                                            placeholder="Reseller Referral Link" autocomplete="off" />
                                            <span class="input-group-append">
                                                <button class="btn btn-default" type="button" id="btn_copy_referral_link_store"><i class="fa fa-copy"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('current_deposite'); ?></h5>
                                        <span class="h4 font-weight-bold mb-0"><?php echo an_accounting($deposite);  ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                            <i class="ni ni-credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('commission/deposite'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('bonus_total'); ?></h5>
                                        <span class="h4 font-weight-bold mb-0"><?php echo an_accounting($bonus); ?></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('commission/bonus'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('menu_report_buy'); ?></h5>
                                        <span class="h4 font-weight-bold mb-0">
                                            <small><?php echo $currency; ?></small> 
                                            <?php echo an_accounting($dataOrder->total_payment); ?>
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('shopping/shophistorylist'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0"><?php echo lang('product') . ' ' . lang('active'); ?></h5>
                                        <span class="h4 font-weight-bold mb-0"><?php echo an_accounting($pin_active); ?> <small><?=lang('product')?></small></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="ni ni-tag"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <a href="<?php echo base_url('pin/datalists'); ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if ($is_admin && $dashboard_admin ) :
    $sales_trx      = array(0);
    $sales_data     = array(0);
    $sales_label    = array(date('M'));
    $data_omzet     = $this->Model_Shop->get_all_omzet_shop_order_monthly(6, 0);
    if ($data_omzet) {
        if (count($data_omzet) > 1) {
            $sales_data     = $sales_trx = $sales_label = array();
        } else {
            $sales_label    = array(date('M', strtotime('-1 month')));
        }
        foreach ($data_omzet as $key => $row) {
            $sales_label[]  = date('M', strtotime($row->month_omzet));
            $sales_data[]   = $row->total_omzet / 1000;
            $sales_trx[]    = $row->total_trx;
        }
    }
    $sales_label = '"' . implode('","', $sales_label) . '"';
?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                                <h5 class="h3 mb-0">Sales Value</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <span class="d-none sales-light" data-toggle="chart" data-target="#chart-sales-light" data-prefix="" data-suffix="k" data-update='{
                                "data":{
                                    "labels": [<?php echo $sales_label; ?>],
                                    "datasets":[{
                                        "label": "Total Penjualan",
                                        "data":[<?php echo implode(',', $sales_data); ?>]
                                    }]
                                }
                            }'></span>
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales-light" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Overview</h6>
                                <h5 class="h3 mb-0">Total orders</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <span class="d-none sales-canvas" data-toggle="chart" data-target="#chart-bars" data-prefix=" " data-suffix="" data-update='{
                                "data":{
                                    "labels": [<?php echo $sales_label; ?>],
                                    "datasets":[{
                                        "label": "Total Penjualan",
                                        "data":[<?php echo implode(',', $sales_trx); ?>]
                                    }]
                                }
                            }'></span>
                            <canvas id="chart-bars" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php elseif (!$is_admin) : ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="h3 mb-0 text-capitalize">list <?php echo ( $member->as_stockist > 0 ) ? lang('menu_report_sales') : lang('menu_shopping'); ?></h5>
                                <h6 class="text-warning text-uppercase ls-1 mb-1">PENDING</h6>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="min-height: 240px">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center">Invoice</th>
                                    <th scope="col" class="text-center"><?php echo lang('total_payment'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                $condition  = ' AND %status% = 0 AND %dateexpired% >= "'. date('Y-m-d H:i:s') .'"';
                                if ( $member->as_stockist > 0 ) {
                                    $condition .= ' AND %id_stockist% = ' . $member->id;
                                    $link_pending = base_url('report/order');
                                } else {
                                    $condition .= ' AND %id_member% = ' . $member->id;
                                    $link_pending = base_url('shopping/shophistorylist');
                                }
                                $data_list  = $this->Model_Shop->get_all_shop_order_data(10, 0, $condition, '');
                                if ($data_list) {
                                    foreach ($data_list as $key => $row) {
                                        $id_order       = an_encrypt($row->id);
                                        $btn_invoice    = '<a href="' . base_url('invoice/' . $id_order) . '" 
                                                            class="btn btn-sm btn_block btn-outline-primary" 
                                                            target="_blank"><i class="fa fa-file"></i> ' . $row->invoice . '</a>';

                                        $btn_product    = '<a href="javascript:;" 
                                                            data-url="' . base_url('shopping/getshoporderdetail/' . $id_order) . '" 
                                                            data-invoice="' . $row->invoice . '"
                                                            class="btn btn-sm btn-block btn-outline-primary btn-shop-order-detail">
                                                            <i class="ni ni-bag-17 mr-1"></i> Detail Order</a>';

                                        $type         = '';
                                        // if ( $row->type == 'perdana' )  { $type = '<span class="badge badge-sm badge-warning">PERDANA</span>'; }
                                        // if ( $row->type == 'ro' )       { $type = '<span class="badge badge-sm badge-primary">REPEAT ORDER</span>'; }

                                        echo '
                                            <tr>
                                                <td class="text-center">' . date('j M y @H:i', strtotime($row->datecreated)) . '</td>
                                                <td class="text-center">' . $btn_invoice . '</td>
                                                <td class="text-right heading text-warning font-weight-bold">' . an_accounting($row->total_payment) . '</td>
                                                <td class="text-center">' . $btn_product . '</td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">Tidak ada data penjualan pending</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0 text-sm">
                            <a href="<?php echo $link_pending; ?>" class="text-nowrap text-default"><?php echo lang('see_more'); ?></a>
                        </p>
                    </div>
                </div>
                <!-- Modal Detail PO -->
                <div class="modal fade" id="modal-shop-order-detail" tabindex="-1" role="dialog" aria-labelledby="modal-shop-order-detail" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header pt-3 pb-1">
                                <h5 class="modal-title text-primary"><i class="ni ni-book-bookmark mr-1"></i> <span class="title-invoice font-weight-bold"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body px-4 py-3" style="background-color: #f8f9fe">
                                <div class="info-shop-order-detail"></div>
                            </div>
                            <div class="modal-footer py-2">
                                <button type="button" class="btn btn-sm btn-outline-warning" data-dismiss="modal"><?php echo lang('back'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-sm-6 col-md-12">
                        <div class="card bg-gradient-primary">
                            <?php
                            $bill_no    = '-';
                            $bill_name  = (!empty($member->bill_name) ? $member->bill_name : '-');
                            if ($member->bill) {
                                $bill_format = '';
                                $arr_bill    = str_split($member->bill, 4);
                                foreach ($arr_bill as $k => $no) {
                                    $bill_format .= $no . ' ';
                                }
                                $bill_no = $bill_format ? $bill_format : $bill_no;;
                            }

                            $bank_name  = '-';
                            if ($member->bank && $getBank = an_banks($member->bank)) {
                                $bank_name = $getBank->nama;
                            }
                            ?>
                            <!-- Card body -->
                            <div class="card-body pb-3">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <span class="text-white"><?php echo lang('reg_no_rekening'); ?></span>
                                    </div>
                                    <?php if ($bill_no == '-' || $bill_name == '-' || $bank_name == '-') { ?>
                                        <div class="col-auto">
                                            <a href="<?php echo base_url('profile'); ?>" class="text-nowrap badge badge-lg badge-primary">
                                                <i class="fa fa-edit"></i> <?php echo lang('edit'); ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="my-2">
                                    <span class="h6 surtitle text-light">
                                        Card number
                                    </span>
                                    <div class="card-serial-number h1 text-white">
                                        <div><?php echo $bill_no; ?></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <span class="h6 surtitle text-light"><?php echo lang('name'); ?></span>
                                        <span class="d-block h5 text-white"><?php echo $bill_name; ?></span>
                                    </div>
                                    <div class="col">
                                        <span class="h6 surtitle text-light">Bank</span>
                                        <span class="d-block h5 text-white"><?php echo $bank_name; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
    $change_password = false;
    if ( ! $is_admin && ! an_is_assuming() ) {
        $change_password = ( ! $member->change_password ) ? false : false;
    }
?>
<?php if( $change_password ) : ?>
    <!-- Modal Change Password -->
    <div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-labelledby="modal-change-password" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header pt-3 pb-1">
                    <h5 class="modal-title text-primary"><i class="ni ni-lock-circle-open mr-1"></i> Ganti Password</h5>
                </div>
                <form role="form" method="post" action="<?php echo base_url('member/changepassword'); ?>" id="cpassword" class="form-horizontal">
                    <div class="modal-body py-3 wrapper-cpassword" style="background-color: #f8f9fe">
                        <input type="hidden" name="pass_type" value="login" />
                        <p>Selamat datang dan selamat bergabung di keluarga Alpha Network.</p>
                        <hr class="mt-2">
                        <!-- Current Password  -->
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="cur_pass">Password saat ini <span class="required">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="cur_pass" id="cur_pass" class="form-control" placeholder="Password anda saat ini" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="new_pass">Password Baru <span class="required">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="new_pass" id="new_pass" class="form-control" placeholder="<?php echo lang('reg_valid_password'); ?>" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmed Password -->
                        <div class="form-group row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="cnew_pass">Konfirmasi Password Baru <span class="required">*</span></label>
                            <div class="col-md-8">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="cnew_pass" id="cnew_pass" class="form-control" placeholder="Konfirmasi Password" autocomplete="off" value="" />
                                    <div class="input-group-append">
                                        <button class="btn btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="save_cpassword" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i>  Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin akan mengubah dengan data password ini ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>