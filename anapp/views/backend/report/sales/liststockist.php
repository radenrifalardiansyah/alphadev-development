<table class="table align-items-center table-flush" id="list_table_shop_order" data-url="<?php echo base_url('productorder/salesorderlistsdata'); ?>">
    <thead class="thead-light">
        <tr role="row" class="heading">
            <th scope="col" style="width: 10px" rowspan="2">#</th>
            <th scope="col" class="text-center" rowspan="2">Invoice</th>
            <th scope="col" class="text-center" colspan="2">Data Agent</th>
            <th scope="col" class="text-center" colspan="2">Data Konsumen</th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('total_payment'); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('total'); ?> PV</th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('product'); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('payment'); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo str_replace(' ', br(), lang('payment_method')); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('status'); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('date'); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('date') . br() . 'Approved'; ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('date') . br() . 'Pelunasan'; ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('date') . br() . 'Instalasi'; ?></th>
            <th scope="col" class="text-center" rowspan="2"><?php echo lang('actions'); ?></th>
        </tr>
        <tr role="row" class="heading">
            <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
            <th scope="col" class="text-center"><?php echo lang('phone'); ?></th>
        </tr>
        <tr role="row" class="filter" style="background-color: #f6f9fc">
            <td></td>
            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_customer" /></td>
            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_phone" /></td>
            <td class="px-1">
                <div class="mb-1">
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                </div>
                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
            </td>
            <td class="px-1">
                <div class="mb-1">
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_min" placeholder="Min" />
                </div>
                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_pv_max" placeholder="Max" />
            </td>
            <td></td>
            <td class="px-1">
                <select name="search_payment_type" class="form-control form-control-sm form-filter">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <?php
                    if ($cfg_payment_type = config_item('payment_type')) {
                        foreach ($cfg_payment_type as $key => $row) {
                            echo '<option value="' . $key . '">' . $row . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="px-1">
                <select name="search_payment_method" class="form-control form-control-sm form-filter">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <?php
                    if ($cfg_payment_method = config_item('payment_method')) {
                        foreach ($cfg_payment_method as $key => $row) {
                            echo '<option value="' . $key . '">' . $row . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
            <td class="px-1">
                <select name="search_status" class="form-control form-control-sm form-filter d-none">
                    <option value=""><?php echo lang('select'); ?>...</option>
                    <option value="pending">PENDING</option>
                    <option value="confirmed">DP CONFIRMED</option>
                    <option value="approved">SURVEY APPROVED</option>
                    <option value="paid">INVOICE PAID</option>
                    <option value="done">INSTALLATION DONE</option>
                    <option value="cancelled">CANCELLED</option>
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
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateconfirm_min" placeholder="From" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
                <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateconfirm_max" placeholder="To" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
            </td>
            <td class="px-1">
                <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateapproved_min" placeholder="From" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
                <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateapproved_max" placeholder="To" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
            </td>
            <td class="px-1">
                <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datepaid_min" placeholder="From" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
                <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datepaid_max" placeholder="To" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
            </td>
            <td class="px-1">
                <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datedone_min" placeholder="From" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
                <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datedone_max" placeholder="To" />
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                    </span>
                </div>
            </td>
            <td style="text-align: center;">
                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order" title="Search"><i class="fa fa-search"></i></button>
                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
            </td>
        </tr>
    </thead>
    <tbody class="list">
        <!-- Data Will Be Placed Here -->
    </tbody>
</table>