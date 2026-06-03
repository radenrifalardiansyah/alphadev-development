<?php 
    $cond_total_omzet       = ' AND `id_stockist` = 0 AND `status` IN (1,2)';
    $get_total_omzet        = $this->Model_Shop->get_total_shop_order($cond_total_omzet);
    $total_omzet            = isset($get_total_omzet->total_omzet) ? $get_total_omzet->total_omzet : 0;

    $cond_monthly_omzet     = $cond_total_omzet .' AND DATE_FORMAT(dateconfirmed, "%Y-%m") = "'. date('Y-m') .'"';
    $get_monthly_omzet      = $this->Model_Shop->get_total_shop_order($cond_monthly_omzet);
    $monthly_omzet          = isset($get_monthly_omzet->total_omzet) ? $get_monthly_omzet->total_omzet : 0;

    $cond_daily_omzet       = $cond_total_omzet .' AND DATE_FORMAT(dateconfirmed, "%Y-%m-%d") = "'. date('Y-m-d') .'"';
    $get_daily_omzet        = $this->Model_Shop->get_total_shop_order($cond_daily_omzet);
    $daily_omzet            = isset($get_daily_omzet->total_omzet) ? $get_daily_omzet->total_omzet : 0;
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0"><?php echo lang('omzet_total'); ?></h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_omzet); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                            <i class="ni ni-chart-bar-32"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0"><?php echo lang('omzet_this_month'); ?></h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($monthly_omzet); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                            <i class="ni ni-chart-bar-32"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0"><?php echo lang('omzet_today'); ?></h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($daily_omzet); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                            <i class="ni ni-chart-bar-32"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="accordion" id="accordionOmzetDaily">
    <div class="card mb-3">
        <div class="card-header collapsed" id="headOmzetDaily" data-toggle="collapse" data-target="#collapseOmzetDaily" aria-expanded="true" aria-controls="collapseOmzetDaily">
            <h4 class="mb-0"><?php echo lang('omzet_daily'); ?></h4>
        </div>
        <div id="collapseOmzetDaily" class="collapse show" aria-labelledby="headOmzetDaily" data-parent="#accordionOmzetDaily">
            <div class="table-container">
                <?php echo '&nbsp'; ?>
                <div class="table-actions-wrapper table-group-actions text-right">
                    <button class="btn btn-sm btn-info text-white table-export-excel">
                        <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                    </button>
                    <?php echo '&nbsp'; ?>
                </div>
                <table class="table align-items-center table-flush" id="list_table_omzet_order_daily" data-url="<?php echo base_url('shopping/omzetorderdailylistdata'); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" rowspan="2" style="width: 10px">#</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('date'); ?></th>
                            <th scope="col" colspan="2" class="text-center"><?php echo lang('type') .' '. lang('omzet'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="heading">
                            <th scope="col" class="text-center">Admin Generate</th>
                            <th scope="col" class="text-center">Stockist Order</th>
                        </tr>
                        <tr role="row" class="filter" style="background-color: #f6f9fc">
                            <td></td>
                            <td>
                                <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_min" placeholder="From" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_max" placeholder="To" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_generate_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_generate_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_order_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_order_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_max" placeholder="Max" />
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_omzet_order_daily" title="Search"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <!-- Data Will Be Placed Here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="accordion" id="accordionOmzetMonthly">
    <div class="card mb-3">
        <div class="card-header" id="headOmzetMonthly" data-toggle="collapse" data-target="#collapseOmzetMonthly" aria-expanded="true" aria-controls="collapseOmzetMonthly">
            <h4 class="mb-0"><?php echo lang('omzet_monthly'); ?></h4>
        </div>
        <div id="collapseOmzetMonthly" class="collapse show" aria-labelledby="headOmzetMonthly" data-parent="#accordionOmzetMonthly">
            <div class="table-container">
                <?php echo '&nbsp'; ?>
                <div class="table-actions-wrapper table-group-actions text-right">
                    <button class="btn btn-sm btn-info text-white table-export-excel">
                        <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                    </button>
                    <?php echo '&nbsp'; ?>
                </div>
                <table class="table align-items-center table-flush" id="list_table_omzet_order_monthly" data-url="<?php echo base_url('shopping/omzetordermonthlylistdata'); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" rowspan="2" style="width: 10px">#</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('month'); ?></th>
                            <th scope="col" colspan="2" class="text-center"><?php echo lang('type') .' '. lang('omzet'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="heading">
                            <th scope="col" class="text-center">Admin Generate</th>
                            <th scope="col" class="text-center">Stockist Order</th>
                        </tr>
                        <tr role="row" class="filter" style="background-color: #f6f9fc">
                            <td></td>
                            <td>
                                <div class="input-group input-group-sm date date-picker-month mb-1" data-date-format="yyyy-mm">
                                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_min" placeholder="From" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                    </span>
                                </div>
                                <div class="input-group input-group-sm date date-picker-month" data-date-format="yyyy-mm">
                                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_max" placeholder="To" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_generate_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_generate_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_order_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_order_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_max" placeholder="Max" />
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_omzet_order_monthly" title="Search"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <!-- Data Will Be Placed Here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
