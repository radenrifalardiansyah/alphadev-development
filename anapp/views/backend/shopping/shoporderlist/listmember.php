<?php 
    $tab_stockist   = ( $member->as_stockist > 0 ) ? 'active' : '';
    $list_stockist  = ( $member->as_stockist > 0 ) ? 'show active' : '';
    $tab_member     = ( $member->as_stockist == 0 ) ? 'active' : '';
    $list_member    = ( $member->as_stockist == 0 ) ? 'show active' : '';
?>

<div class="card-body pt-0">
    <!--
    <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item mb-3">
                <a class="nav-link <?php echo $tab_stockist; ?>" id="tabs-shop-center-shop-tab" data-toggle="tab" href="#tabs-shop-center-shop" role="tab" aria-controls="tabs-shop-center-shop" aria-selected="true"><i class="ni ni-bag-17 mr-2"></i>Belanja Ke Pusat</a>
            </li>
            <li class="nav-item mb-3">
                <a class="nav-link <?php echo $tab_member; ?>" id="tabs-shop-agency-tab" data-toggle="tab" href="#tabs-shop-agency" role="tab" aria-controls="tabs-shop-agency" aria-selected="false"><i class="ni ni-basket mr-2"></i>Belanja Ke Stockist</a>
            </li>
        </ul>
    </div>
    -->
    <div class="card-body px-0">
        <div class="table-container">
            <div class="table-actions-wrapper table-group-actions text-right">
                <button class="btn btn-sm btn-info text-white table-export-excel">
                    <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                </button>
            </div>
            <table class="table align-items-center table-flush" id="list_table_shop_history" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('me_to_admin')); ?>">
                <thead class="thead-light">
                    <tr role="row" class="heading">
                        <th scope="col" style="width: 10px">#</th>
                        <th scope="col" class="text-center">Invoice</th>
                        <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                        <th scope="col" class="text-center">Total Qty</th>
                        <th scope="col" class="text-center">Total BV</th>
                        <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                        <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                        <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                        <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                        <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                        <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                        <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                    </tr>
                    <tr role="row" class="filter" style="background-color: #f6f9fc">
                        <td></td>
                        <td><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                        <td></td>
                        <td>
                            <div class="mb-1">
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                            </div>
                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                        </td>
                        <td>
                            <div class="mb-1">
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_min" placeholder="Min" />
                            </div>
                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_max" placeholder="Max" />
                        </td>
                        <td>
                            <div class="mb-1">
                                <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                            </div>
                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                        </td>
                        <td>
                            <select name="search_status" class="form-control form-control-sm form-filter">
                                <option value=""><?php echo lang('select'); ?>...</option>
                                <option value="pending">PENDING</option>
                                <option value="confirmed">CONFIRMED</option>
                                <option value="cancelled">CANCELLED</option>
                            </select>
                        </td>
                        <td>

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
                        <td></td>
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
        </div>
    </div>
    
        <!--
        <div class="tab-content" id="shopHistoryListContent">
            <div class="tab-pane fade  <?php echo $list_stockist; ?>" id="tabs-shop-center-shop" role="tabpanel" aria-labelledby="tabs-shop-center-shop-tab">
                <h3 class="heading-small px-3"><i class="ni ni-bag-17 mr-2"></i>Belanja Ke Pusat</h3>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_shop_history" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('me_to_admin')); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center">Invoice</th>
                                <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                <th scope="col" class="text-center">Total Qty</th>
                                <th scope="col" class="text-center">Total BV</th>
                                <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('total_payment')); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('shipping_method'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo str_replace(' ', br(), lang('confirm_date')); ?></th>
                                <th class="width15 text-center"><?php echo str_replace(' ', br(), lang('confirm_by')); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_invoice" /></td>
                                <td></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="pending">PENDING</option>
                                        <option value="confirmed">CONFIRMED</option>
                                        <option value="cancelled">CANCELLED</option>
                                    </select>
                                </td>
                                <td>

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
                                <td></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list"></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade  <?php echo $list_member; ?>" id="tabs-shop-agency" role="tabpanel" aria-labelledby="tabs-shop-agency-tab">
                <h3 class="heading-small px-3"><i class="ni ni-basket mr-2"></i>Belanja Ke Stockist</h3>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_shop_stockist" data-url="<?php echo base_url('shopping/shoporderlistsdata/'.an_encrypt('me_to_stockist')); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px" rowspan="2">#</th>
                                <th scope="col" class="text-center" rowspan="2">Invoice</th>
                                <th scope="col" class="text-center" colspan="2">Stockist</th>
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
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_stockist" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_stockist_name" /></td>
                                <td></td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_qty_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_bv_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="pending">PENDING</option>
                                        <option value="confirmed">CONFIRMED</option>
                                        <option value="cancelled">CANCELLED</option>
                                    </select>
                                </td>
                                <td>

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
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_shop_order_stockist" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="list"></tbody>
                    </table>
                </div>
            </div>
        </div>
        -->
        
    </div>
</div>
