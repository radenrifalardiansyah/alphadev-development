<div class="card-header border-0">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0"><?php echo $menu_title; ?></h3>
        </div>
    </div>
</div>

<div class="table-container">
    <table class="table align-items-center table-flush" id="list_table_shop_history" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_stockist')); ?>">
        <thead class="thead-light">
            <tr role="row" class="heading">
                <th scope="col" style="width: 10px" rowspan="2">#</th>
                <th scope="col" class="text-center" rowspan="2">Invoice</th>
                <th scope="col" class="text-center" colspan="2">Member</th>
                <th scope="col" class="text-center" rowspan="2">Stockist</th>
                <th scope="col" class="text-center" rowspan="2"><?php echo lang('product'); ?></th>
                <th scope="col" class="text-center" rowspan="2">Total Qty</th>
                <th scope="col" class="text-center" rowspan="2">Total BV</th>
                <th scope="col" class="text-center" rowspan="2"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo lang('status'); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo lang('shipping_method'); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo lang('date'); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                <th scope="col" class="text-center" rowspan="2"><?php echo lang('actions'); ?></th>
            </tr>
            <tr role="row" class="heading">
                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                <th scope="col"><?php echo lang('name'); ?></th>
            </tr>
            <tr role="row" class="filter" style="background-color: #f6f9fc">
                <td></td>
                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                <td class="px-1">
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter" name="search_stockist" placeholder="<?php echo lang('username') ?>" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter" name="search_stockist_name" placeholder="<?php echo lang('name') ?>"/>
                </td>
                <td></td>
                <td class="px-1">
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                </td>
                <td class="px-1">
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_max" placeholder="Max" />
                </td>
                <td class="px-1">
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                </td>
                <td class="px-1">
                    <select name="search_status" class="form-control form-control-sm form-filter">
                        <option value=""><?php echo lang('select'); ?>...</option>
                        <option value="pending">PENDING</option>
                        <option value="confirmed">CONFIRMED</option>
                        <option value="done">DONE</option>
                        <option value="cancelled">CANCELLED</option>
                    </select>
                </td>
                <td class="px-1">
                    <div class="mb-1">
                        <select name="search_shipping" class="form-control form-control-sm form-filter">
                            <option value=""><?php echo lang('select'); ?>...</option>
                            <?php
                                $shipping_method = config_item('shipping_method');
                                if( !empty($shipping_method) ){
                                    foreach($shipping_method as $k => $val){
                                        echo '<option value="'.$k.'">'.$val.'</option>';
                                    }
                                }
                            ?> 
                        </select>
                    </div>
                    <input type="text" class="form-control form-control-sm form-filter" name="search_resi" placeholder="No. Resi" />
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
                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datemodified_min" placeholder="From" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                        </span>
                    </div>
                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datemodified_max" placeholder="To" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                        </span>
                    </div>
                </td>
                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                <td style="text-align: center;">
                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_history" title="Search"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </thead>
        <tbody class="list">
            <!-- Data Will Be Placed Here -->
        </tbody>
    </table>
</div>