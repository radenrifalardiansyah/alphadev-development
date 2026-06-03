<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tabs-wd_history-tab" data-toggle="tab" href="#tabs-wd_history" role="tab" aria-controls="tabs-wd_history" aria-selected="true"><i class="ni ni-time-alarm mr-2"></i><?php echo lang('wd_history'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tabs-wd_summary-tab" data-toggle="tab" href="#tabs-wd_summary" role="tab" aria-controls="tabs-wd_summary" aria-selected="false"><i class="ni ni-chart-bar-32 mr-2"></i><?php echo lang('wd_summary'); ?></a>
        </li>
    </ul>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="tab-content" id="WDListContent">
            <div class="tab-pane fade show active" id="tabs-wd_history" role="tabpanel" aria-labelledby="tabs-wd_history-tab">
                <div class="table-container">
                    <div class="table-actions-wrapper table-group-actions text-right">
                        <button class="btn btn-sm btn-info text-white table-export-excel">
    						<i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
    					</button>
                    </div>
                    <table class="table align-items-center table-flush" id="list_table_withdraw" data-url="<?php echo base_url('commission/withdrawlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('bank'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('bank_account'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('information'); ?></th>
                                <th scope="col" class="text-center">Trx ID</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('confirm_date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('confirm_by'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td>
                                    <select class="form-control form-control-sm form-filter" name="search_bank">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <?php
                                            if( $banks = an_banks() ){
                                                foreach($banks as $b){
                                                    echo '<option value="'.$b->id.'">'.$b->kode.' - '.$b->nama.'</option>';
                                                }
                                            }
                                        ?>    
                                    </select>
                                </td>
                                <td>
                                    <div class="bottom5">
                                        <input type="text" class="form-control form-control-sm form-filter numbermask" name="search_bill" placeholder="<?php echo lang('no_rekening'); ?>" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter" name="search_bill_name" placeholder="<?php echo lang('pemilik_rek'); ?>" />
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
                                        <option value="onprocess">ON PROCESS</option>
                                        <option value="transfered">TRANSFERED</option>
                                    </select>
                                </td>
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_flip_id" /></td>
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
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_confirm_by" /></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_withdraw" title="Search"><i class="fa fa-search"></i></button>
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
            
            <div class="tab-pane fade" id="tabs-wd_summary" role="tabpanel" aria-labelledby="tabs-wd_summary-tab">
                <div class="table-container">
                    <div class="table-actions-wrapper table-group-actions text-right">
                        <button class="btn btn-sm btn-info text-white table-export-excel">
    						<i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
    					</button>
                    </div>
                    <table class="table align-items-center table-flush" id="list_table_withdraw_summary" data-url="<?php echo base_url('commission/withdrawdailylistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('wd_total'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('admin_fee'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('transfer_fee'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td>
                                    <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_day_min" placeholder="From" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_day_max" placeholder="To" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_wd_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_wd_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_admin_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_admin_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_transfer_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_transfer_max" placeholder="Max" />
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_withdraw_summary" title="Search"><i class="fa fa-search"></i></button>
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