<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_report') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_report_register'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo lang('menu_report_register') .' '. lang('agent'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-container">
                        <div class="table-actions-wrapper table-group-actions text-right">
                            <button class="btn btn-sm btn-info text-white table-export-excel">
                                <i class="fa fa-share-square"></i> <span class="hidden-480">Export ke Excel</span>
                            </button>
                        </div>
                        <table class="table align-items-center table-flush" id="list_table_member" data-url="<?php echo base_url('member/registerlistdata'); ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('registrant'); ?></th>
                                    <th scope="col" class="text-center">Sponsor</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('wa'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('email'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                    <?php if ( $is_admin ) { ?>
                                        <th class="width15 text-center"><?php echo lang('registration_access'); ?></th>
                                    <?php } ?>
                                    <th scope="col" class="text-center"><?php echo lang('join_date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('confirm_date'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_member" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_sponsor" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_wa" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_email" /></td>
                                    <td>
                                        <select name="search_status" class="form-control form-control-sm form-filter">
                                            <option value=""><?php echo lang('select'); ?>...</option>
                                            <option value="pending">PENDING</option>
                                            <option value="confirmed">CONFIRMED</option>
                                            <option value="cancelled">CANCELLED</option>
                                        </select>
                                    </td>
                                    <?php if ( $is_admin ) { ?>
                                        <td>
                                            <select name="search_access" class="form-control form-filter input-sm">
                                                <option value=""><?php echo lang('select'); ?>...</option>
                                                <option value="admin">ADMIN</option>
                                                <option value="member">MEMBER</option>
                                            </select>
                                        </td>
                                    <?php } ?>
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
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_member" title="Search"><i class="fa fa-search"></i></button>
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
