<div class="nav-wrapper px-3">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="shop-redemption-tabs" data-toggle="tab" href="#tabs-shop-redemption" role="tab" aria-controls="tabs-shop-redemption" aria-selected="true"><?php echo lang('product'); ?> Redemption</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="shop-product-tab" data-toggle="tab" href="#tabs-shop-product" role="tab" aria-controls="tabs-shop-product" aria-selected="false"><?php echo lang('product'); ?> Belanja</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="shop-multivoucher-tab" data-toggle="tab" href="#tabs-shop-multivoucher" role="tab" aria-controls="tabs-shop-multivoucher" aria-selected="false"><?php echo lang('product'); ?> Multivoucher</a>
        </li>
    </ul>
</div>
<div class="card shadow">
    <div class="card-body px-0">
        <div class="tab-content" id="shoppingListContent">
            <div class="tab-pane fade show active" id="tabs-shop-redemption" role="tabpanel" aria-labelledby="tabs-shop-redemption">
                <h3 class="px-3"><?php echo lang('product'); ?> Redemption</h3>
                <table class="table align-items-center table-flush" id="list_table_shop_order_redemption" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('redemption')); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" style="width: 10px">#</th>
                            <th scope="col" class="text-center">Invoice</th>
                            <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                            <th scope="col"><?php echo lang('name'); ?></th>
                            <th scope="col" class="text-center">Total Qty</th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                            <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="filter" style="background-color: #f6f9fc">
                            <td></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                            </td>
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                            </td>
                            <td></td>
                            <td class="px-1">
                                <select name="search_status" class="form-control form-control-sm form-filter">
                                    <option value=""><?php echo lang('select'); ?>...</option>
                                    <option value="pending">PENDING</option>
                                    <option value="confirmed">CONFIRMED</option>
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
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order_redemption" title="Search"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <!-- Data Will Be Placed Here -->
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tabs-shop-product" role="tabpanel" aria-labelledby="tabs-shop-product">
                <h3 class="px-3"><?php echo lang('product'); ?> Belanja</h3>
                <table class="table align-items-center table-flush" id="list_table_shop_order" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('shop')); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" style="width: 10px">#</th>
                            <th scope="col" class="text-center">Invoice</th>
                            <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                            <th scope="col"><?php echo lang('name'); ?></th>
                            <th scope="col" class="text-center">Total Qty</th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                            <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="filter" style="background-color: #f6f9fc">
                            <td></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>\
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                            </td>
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                            </td>
                            <td></td>
                            <td class="px-1">
                                <select name="search_status" class="form-control form-control-sm form-filter">
                                    <option value=""><?php echo lang('select'); ?>...</option>
                                    <option value="pending">PENDING</option>
                                    <option value="confirmed">CONFIRMED</option>
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
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order_shop" title="Search"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <!-- Data Will Be Placed Here -->
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tabs-shop-multivoucher" role="tabpanel" aria-labelledby="tabs-shop-multivoucher">
                <h3 class="px-3"><?php echo lang('product'); ?> Multivoucher</h3>
                <table class="table align-items-center table-flush" id="list_table_shop_order_multivoucher" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('member_to_admin') .'/'. an_encrypt('multivoucher')); ?>">
                    <thead class="thead-light">
                        <tr role="row" class="heading">
                            <th scope="col" style="width: 10px">#</th>
                            <th scope="col" class="text-center">Invoice</th>
                            <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                            <th scope="col"><?php echo lang('name'); ?></th>
                            <th scope="col" class="text-center">Total Qty</th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                            <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                            <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                            <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                        </tr>
                        <tr role="row" class="filter" style="background-color: #f6f9fc">
                            <td></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                            </td>
                            <td class="px-1">
                                <div class="mb-1">
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                </div>
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                            </td>
                            <td></td>
                            <td class="px-1">
                                <select name="search_status" class="form-control form-control-sm form-filter">
                                    <option value=""><?php echo lang('select'); ?>...</option>
                                    <option value="pending">PENDING</option>
                                    <option value="confirmed">CONFIRMED</option>
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
                            <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order_multivoucher" title="Search"><i class="fa fa-search"></i></button>
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
