<div class="card-body pt-0">
    <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item mb-3">
                <a class="nav-link active btn_shop_order_status" id="tabs-shop-pending-tab" data-status="pending" data-toggle="tab" href="#tabs-shop-pending" role="tab" aria-controls="tabs-shop-pending-tab" aria-selected="true"><i class="ni ni-time-alarm"></i> New (Pending)</a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link btn_shop_order_status" id="tabs-shop-confirmed-tab" data-status="confirmed" data-toggle="tab" href="#tabs-shop-confirmed" role="tab" aria-controls="tabs-shop-confirmed-tab" aria-selected="false"><i class="fa fa-check"></i> Payment Confirmed</a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link btn_shop_order_status" id="tabs-shop-done-tab" data-status="done" data-toggle="tab" href="#tabs-shop-done" role="tab" aria-controls="tabs-shop-done-tab" aria-selected="false"><i class="fa fa-truck"></i> Delivery Done</a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link btn_shop_order_status" id="tabs-shop-cancelled-tab" data-status="cancelled" data-toggle="tab" href="#tabs-shop-cancelled" role="tab" aria-controls="tabs-shop-cancelled-tab" aria-selected="false"><i class="fa fa-times"></i> Cancelled</a>
            </li>
        </ul>
    </div>
    <div class="card shadow">
        <div class="card-body px-0">
            <div class="tab-content" id="shopOrderContent">
                <!-- Table Product Order Pending -->
                <div class="tab-pane fade show active" id="tabs-shop-pending" role="tabpanel" aria-labelledby="tabs-shop-pending-tab">
                    <h3 class="heading-small px-3"><i class="ni ni-bag-17 mr-2"></i> <?php echo lang('menu_shopping'); ?> (PENDING)</h3>
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                            <?php echo '&nbsp'; ?>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_shop_pending" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('pending')); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center">Invoice</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col">Status Pembeli</th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" class="text-center">Total Qty</th>
                                    <th scope="col" class="text-center">Total BV</th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('expired_date')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td class="px-1">
                                        <select name="search_access_order" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="self">RESELLER</option>
                                            <option value="customer">KONSUMEN</option>
                                        </select>
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
                                            <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateexpired_min" placeholder="From" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                            </span>
                                        </div>
                                        <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                            <input type="text" class="form-control form-control-sm form-filter" readonly name="search_dateexpired_max" placeholder="To" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-1">
                                        <select name="search_confirm" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="manual">MANUAL</option>
                                            <option value="auto">AUTO</option>
                                        </select>
                                    </td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_pending" title="Search"><i class="fa fa-search"></i></button>
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

                <!-- Table Product Order Confirmed -->
                <div class="tab-pane fade" id="tabs-shop-confirmed" role="tabpanel" aria-labelledby="tabs-shop-confirmed-tab">
                    <h3 class="heading-small px-3"><i class="fa fa-check mr-2"></i> <?php echo lang('menu_shopping'); ?> (CONFIRMED)</h3>
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                            <?php echo '&nbsp'; ?>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_shop_confirmed" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('confirmed')); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center">Invoice</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col">Status Pembeli</th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" class="text-center">Total Qty</th>
                                    <th scope="col" class="text-center">Total BV</th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                                    <th class="width15 text-center">Label</th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td class="px-1">
                                        <select name="search_access_order" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="self">RESELLER</option>
                                            <option value="customer">KONSUMEN</option>
                                        </select>
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
                                        <select name="search_confirm" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="manual">MANUAL</option>
                                            <option value="auto">AUTO</option>
                                        </select>
                                    </td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                                    <td></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_confirmed" title="Search"><i class="fa fa-search"></i></button>
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

                <!-- Table Product Order Doned -->
                <div class="tab-pane fade" id="tabs-shop-done" role="tabpanel" aria-labelledby="tabs-shop-done-tab">
                    <h3 class="heading-small px-3"><i class="fa fa-check mr-2"></i> <?php echo lang('menu_shopping'); ?> (done)</h3>
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                            <?php echo '&nbsp'; ?>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_shop_done" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('done')); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center">Invoice</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col">Status Pembeli</th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" class="text-center">Total Qty</th>
                                    <th scope="col" class="text-center">Total BV</th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td class="px-1">
                                        <select name="search_access_order" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="self">RESELLER</option>
                                            <option value="customer">KONSUMEN</option>
                                        </select>
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
                                        <select name="search_confirm" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="manual">MANUAL</option>
                                            <option value="auto">AUTO</option>
                                        </select>
                                    </td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_done" title="Search"><i class="fa fa-search"></i></button>
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

                <!-- Table Product Order Cancelled -->
                <div class="tab-pane fade" id="tabs-shop-cancelled" role="tabpanel" aria-labelledby="tabs-shop-cancelled-tab">
                    <h3 class="heading-small px-3"><i class="fa fa-times mr-2"></i> <?php echo lang('menu_shopping'); ?> (CANCELLED)</h3>
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                            <?php echo '&nbsp'; ?>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_shop_cancelled" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('cancelled')); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center">Invoice</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col">Status Pembeli</th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" class="text-center">Total Qty</th>
                                    <th scope="col" class="text-center">Total BV</th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('cancel_date')); ?></th>
                                    <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('cancel_by')); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td class="px-1">
                                        <select name="search_access_order" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="self">RESELLER</option>
                                            <option value="customer">KONSUMEN</option>
                                        </select>
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
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_cancelled" title="Search"><i class="fa fa-search"></i></button>
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
    </div>
</div>
