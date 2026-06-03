<div class="card-body pt-0">
    <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tabs-total_deposite-tab" data-toggle="tab" href="#tabs-total_deposite" role="tab" aria-controls="tabs-total_deposite" aria-selected="true"><i class="ni ni-credit-card mr-2"></i> Total Deposite Loan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabs-history_loan-tab" data-toggle="tab" href="#tabs-history_loan" role="tab" aria-controls="tabs-history_loan" aria-selected="false"><i class="ni ni-time-alarm mr-2"></i> History Stockist Loan</a>
            </li>
        </ul>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="tab-content" id="bonusListContent">
                <div class="tab-pane fade show active" id="tabs-total_deposite" role="tabpanel" aria-labelledby="tabs-total_deposite-tab">
                    <div class="table-container">
                        <table class="table align-items-center table-flush" id="list_table_member_deposite_loan" data-url="<?php echo base_url('member/memberloandepositelistsdata'); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('total'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td  class="px-1">
                                        <div class="mb-1">
                                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_min" placeholder="Min" />
                                        </div>
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_max" placeholder="Max" />
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_member_deposite_loan" title="Search"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <!-- Data Will Be Placed Here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="tabs-history_loan" role="tabpanel" aria-labelledby="tabs-history_loan-tab">
                    <div class="table-container">
                        <table class="table align-items-center table-flush" id="list_table_member_loan" data-url="<?php echo base_url('member/memberloanlistsdata'); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('name'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('type'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
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
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td class="px-1">
                                        <select name="search_type" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="deposite">DEPOSITE</option>
                                            <option value="withdraw">WITHDRAW</option>
                                        </select>
                                    </td>
                                    <td  class="px-1">
                                        <div class="mb-1">
                                            <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_min" placeholder="Min" />
                                        </div>
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_total_max" placeholder="Max" />
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_member_loan" title="Search"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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