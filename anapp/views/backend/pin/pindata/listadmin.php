<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tabs-pin-member-tab" data-toggle="tab" href="#tabs-pin-member" role="tab" aria-controls="tabs-pin-member" aria-selected="true"><i class="ni ni-chart-bar-32 mr-2"></i><?php echo lang('pin_member'); ?></a>
        </li>
        <!--
        <li class="nav-item">
            <a class="nav-link" id="tabs-pin-used-tab" data-toggle="tab" href="#tabs-pin-used" role="tab" aria-controls="tabs-pin-used" aria-selected="false"><i class="ni ni-folder-17 mr-2"></i><?php echo lang('pin_used'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tabs-pin-status-tab" data-toggle="tab" href="#tabs-pin-status" role="tab" aria-controls="tabs-pin-status" aria-selected="false"><i class="ni ni-bag-17 mr-2"></i><?php echo lang('pin_total'); ?></a>
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
                    </div>
                    <table class="table align-items-center table-flush" id="list_table_pin_member_active" data-url="<?php echo base_url('pin/depositepinlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('total') .' '. lang('active'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_active_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_active_max" placeholder="Max" />
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_pin_member_active" title="Search"><i class="fa fa-search"></i></button>
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
            <div class="tab-pane fade" id="tabs-pin-status" role="tabpanel" aria-labelledby="tabs-pin-status-tab">
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_pin_status" data-url="<?php echo base_url('pin/pinstatuslistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?> BV</th>
                                <th scope="col" class="text-center"><?php echo lang('price'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('pin_total'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('pin_active'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('pin_used'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_max" placeholder="Max" />
                                </td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_price_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_price_max" placeholder="Max" />
                                </td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_max" placeholder="Max" />
                                </td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_in_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_in_max" placeholder="Max" />
                                </td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_out_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_out_max" placeholder="Max" />
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_pin_status" title="Search"><i class="fa fa-search"></i></button>
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