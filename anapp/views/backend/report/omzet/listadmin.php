<?php 
    $get_omzet_register     = $this->Model_Member->get_total_member_omzet(' AND `status` = "register"');
    $reg_bv                 = isset($get_omzet_register->total_bv) ? $get_omzet_register->total_bv : 0;
    $reg_nominal            = isset($get_omzet_register->total_amount) ? $get_omzet_register->total_amount : 0;

    $get_omzet_ro           = $this->Model_Member->get_total_member_omzet(' AND `status` = "ro"');
    $ro_bv                  = isset($get_omzet_ro->total_bv) ? $get_omzet_ro->total_bv : 0;
    $ro_nominal             = isset($get_omzet_ro->total_amount) ? $get_omzet_ro->total_amount : 0;

    $total_omzet_bv         = $reg_bv + $ro_bv;
    $total_omzet_nominal    = $reg_nominal + $ro_nominal;
?>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0"><?php echo lang('omzet_total'); ?></h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_omzet_nominal); ?></span>
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
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0"><?php echo lang('omzet_total'); ?> BV</h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_omzet_bv); ?></span>
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
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0">Omzet RO</h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($ro_nominal); ?></span>
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
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase mb-0">Omzet <?php echo lang('register'); ?></h5>
                        <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($reg_nominal); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                            <i class="fa fa-user-plus"></i>
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
                <table class="table align-items-center table-flush" id="list_table_omzet_daily" data-url="<?php echo base_url('member/omzetdailylistdata'); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" rowspan="2" style="width: 10px">#</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('date'); ?></th>
                            <th scope="col" colspan="2" class="text-center">Nominal <?php echo lang('omzet'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?> BV</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('bonus_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('percent'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="heading">
                            <th scope="col" class="text-center"><?php echo lang('register'); ?></th>
                            <th scope="col" class="text-center">RO</th>
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
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_register_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_register_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_so_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_so_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bonus_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bonus_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_percent_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_percent_max" placeholder="Max" />
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_omzet_daily" title="Search"><i class="fa fa-search"></i></button>
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
                <table class="table align-items-center table-flush" id="list_table_omzet_monthly" data-url="<?php echo base_url('member/omzetmonthlylistdata'); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" rowspan="2" style="width: 10px">#</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('month'); ?></th>
                            <th scope="col" colspan="2" class="text-center">Nominal <?php echo lang('omzet'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('omzet_total'); ?> BV</th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('bonus_total'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('percent'); ?></th>
                            <th scope="col" rowspan="2" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="heading">
                            <th scope="col" class="text-center"><?php echo lang('register'); ?></th>
                            <th scope="col" class="text-center">RO</th>
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
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_register_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_register_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_so_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_so_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bonus_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bonus_max" placeholder="Max" />
                            </td>
                            <td>
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_percent_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_percent_max" placeholder="Max" />
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_omzet_monthly" title="Search"><i class="fa fa-search"></i></button>
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
