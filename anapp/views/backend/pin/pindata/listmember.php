<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card bg-gradient-default">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white"><?php echo lang('pin_total'); ?></h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($pin_total); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-cloud-download-95"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="col-xl-4 col-md-6">
        <div class="card bg-gradient-info">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white"><?php echo lang('pin_active'); ?></h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($pin_active); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-gradient-danger">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0 text-white"><?php echo lang('pin_used'); ?></h5>
                        <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($pin_used); ?></span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                            <i class="ni ni-cloud-upload-96"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
</div>

<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tabs-pin-member-tab" data-toggle="tab" href="#tabs-pin-member" role="tab" aria-controls="tabs-pin-member" aria-selected="true"><i class="ni ni-chart-bar-32 mr-2"></i>List <?php echo lang('product'); ?></a>
        </li>
        <!--
        <li class="nav-item">
            <a class="nav-link" id="tabs-pin-used-tab" data-toggle="tab" href="#tabs-pin-used" role="tab" aria-controls="tabs-pin-used" aria-selected="false"><i class="ni ni-folder-17 mr-2"></i><?php echo lang('pin_used'); ?></a>
        </li>
        -->
    </ul>
</div>
<div class="card shadow">
    <div class="card-body px-0">
        <div class="tab-content" id="bonusListContent">
            <div class="tab-pane fade show active" id="tabs-pin-member" role="tabpanel" aria-labelledby="tabs-pin-member-tab">
                <div class="table-container">
                    <div class="table-actions-wrapper table-group-actions text-right">
                        <button class="btn btn-sm btn-info text-white table-export-excel">
                            <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                        </button>
                        <?php echo '&nbsp'; ?>
                    </div>
                    <table class="table align-items-center table-flush" id="list_table_pin_member" data-url="<?php echo base_url('pin/pinmemberlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center">ID <?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('sender'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('transfer_date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_id_pin" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_sender" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_product" /></td>
                                <td class="px-1">
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value="">Select...</option>
                                        <option value="active">ACTIVE</option>
                                        <option value="used">USED</option>
                                    </select>
                                </td>
                                <td class="px-1">
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
                                <td class="px-1">
                                    <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datetransfer_min" placeholder="From" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datetransfer_max" placeholder="To" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-block btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_pin_member" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-block btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <!-- Data Will Be Placed Here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!--
            <div class="tab-pane fade" id="tabs-pin-used" role="tabpanel" aria-labelledby="tabs-pin-used-tab">
                <div class="table-container">
                    <div class="table-actions-wrapper table-group-actions text-right">
                        <button class="btn btn-sm btn-info text-white table-export-excel">
                            <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                        </button>
                    </div>
                    <table class="table align-items-center table-flush" id="list_table_pin_used" data-url="<?php echo base_url('pin/pinusedlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center">ID <?php echo lang('product'); ?></th>
                                <th class="width10 text-center"><?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('registrant'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('information'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td class="px-2"><input type="text" class="form-control form-control-sm form-filter text-uppercase" name="search_id_pin" /></td>
                                <td class="px-2"><input type="text" class="form-control form-control-sm form-filter text-uppercase" name="search_product" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_register" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <select name="search_source" class="form-control form-filter input-sm">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="register">Register</option>
                                        <option value="ro">RO</option>
                                    </select>
                                </td>
                                <td class="px-2">
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
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_pin_used" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list"></tbody>
                    </table>
                </div>
            </div>
            -->
        </div>
    </div>
</div>
