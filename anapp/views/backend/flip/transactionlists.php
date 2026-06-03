<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_flip') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_flip_trx'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-primary">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col pr-0">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Saldo Flip</h5>
                            <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($saldo); ?></span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                <i class="ni ni-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-info">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col pr-0">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Topup</h5>
                            <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($total_topup); ?></span>
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
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-danger">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col pr-0">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Transfer WD</h5>
                            <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($total_trf); ?></span>
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
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-warning">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col pr-0">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Biaya Transfer</h5>
                            <span class="h2 font-weight-bold mb-0 text-white"><?php echo an_accounting($total_trf_fee); ?></span>
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
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $menu_title; ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_flip_trx" data-url="<?php echo base_url('flip/fliptrxlistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('transfer_date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?> WD</th>
                                <th scope="col" class="text-center">Flip ID</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('bank'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                <th scope="col" class="text-center">Bukti Transfer</th>
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
                                <td class="px-1">
                                    <div class="input-group input-group-sm date date-picker mb-1" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datewd_min" placeholder="From" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datewd_max" placeholder="To" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_flip_id" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter" name="search_bank" placeholder="Bank" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter" name="search_bill" placeholder="No. Rekening" />
                                </td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td class="px-1">
                                    <select name="search_status" class="form-control form-filter">
                                        <option value=""><?php echo lang('status'); ?>...</option>
                                        <option value="done">DONE</option>
                                        <option value="pending">PENDING</option>
                                        <option value="cancelled">CANCELLED</option>
                                    </select>
                                </td>
                                <td></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_flip_trx" title="Search"><i class="fa fa-search"></i></button>
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
