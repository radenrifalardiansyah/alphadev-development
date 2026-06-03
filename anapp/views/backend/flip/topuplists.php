<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_flip') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_flip_topup'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-gradient-primary mb-3">
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
        <div class="col-md-6">
            <div class="card bg-gradient-info mb-3">
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
    </div>
    <div class="row">
        <div class="col">
            <div class="alert alert-success">
                <h4 class="alert-heading"><i class="fa fa-bell"></i> Prosedur TopUp Flip</h4>
                <p class="text-white">Silahkan Transfer ke Rekening BCA an. Fliptech Lentera, PT No. Rekening: <b>546-532-7020</b> sesuai dengan Angka Unik</p>
                <button id="btn-modal-flip-topup" type="button" class="btn btn-neutral my-2 text-default"><i class="fa fa-plus"></i>&nbsp;&nbsp;TOPUP FLIP</button>
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
                    <table class="table align-items-center table-flush" id="list_table_flip_topup" data-url="<?php echo base_url('flip/fliptopuplistdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('date'); ?></th>
                                <th scope="col" class="text-center">Topup ID</th>
                                <th scope="col" class="text-center"><?php echo lang('bank'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('nominal'); ?> Transfer</th>
                                <th scope="col" class="text-center"><?php echo lang('information'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
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
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_topup_id" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_bank" /></td>
                                <td class="px-1">
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_nominal_max" placeholder="Max" />
                                </td>
                                <td></td>
                                <td class="px-1">
                                    <select name="search_status" class="form-control form-filter">
                                        <option value=""><?php echo lang('status'); ?>...</option>
                                        <option value="done">DONE</option>
                                        <option value="pending">PENDING</option>
                                        <option value="cancelled">CANCELLED</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_flip_topup" title="Search"><i class="fa fa-search"></i></button>
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

<!-- Modal Form Topup -->
<div class="modal fade" id="modal-form-flip-topup" tabindex="-1" role="dialog" aria-labelledby="modal-form-flip-topup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-credit-card"></i> Withdraw Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('flip/topupsaldo'); ?>" id="form-flip-topup" class="form-horizontal">
                <div class="modal-body wrapper-form-flip-topup py-2">
                    <div class="form-group  row mb-2">
                        <label class="col-md-4 col-form-label form-control-label" for="nominal">Nominal Topup</label>
                        <div class="col-md-8">
                            <input type="text" id="nominal" name="nominal" class="form-control numbercurrency">
                        </div>
                    </div>
                    <hr class="mt-2">
                    <!-- Password -->
                    <div class="form-group row mb-2">
                        <label class="col-md-4 col-form-label form-control-label" for="password"><?php echo lang('reg_password'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="off" placeholder="Konfirmasi Password" />
                                <div class="input-group-append">
                                    <button class="btn btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang('back'); ?></button>
                    <button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
