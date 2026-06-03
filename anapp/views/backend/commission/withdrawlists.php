<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_financial') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_financial_withdraw'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total<br />Withdraw</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_withdraw); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="ni ni-chart-bar-32"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total<br />Transfer WD</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_transfer); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                                        <i class="ni ni-curved-next"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total<br />Bonus</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_bonus); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                        <i class="ni ni-money-coins"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Deposite<br />Bonus</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo an_accounting($total_deposite); ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                        <i class="ni ni-credit-card"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <h3 class="mb-0"><?php echo lang('menu_financial_withdraw'); ?> </h3>
                        </div>
                        <?php if ( !$is_admin && $member->wd_status ) { ?>
                            <div class="col col-5 text-right">
                                <a href="javascript:;" class="btn btn-sm btn-primary" id="btn-modal-withdraw">Withdraw Manual</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php 
                        if ( $is_admin ) {
                            $this->load->view(VIEW_BACK . 'commission/withdraw/listadmin');
                        } else {
                            $this->load->view(VIEW_BACK . 'commission/withdraw/listmember');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ( !$is_admin && $member->wd_status ): ?>
    <!-- Modal Form Withdraw -->
    <div class="modal fade" id="modal-form-withdraw" tabindex="-1" role="dialog" aria-labelledby="modal-form-withdraw" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-credit-card"></i> Withdraw Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="post" action="<?php echo base_url('commission/withdrawmanualtransfer'); ?>" id="form-withdraw" class="form-horizontal">
                    <div class="modal-body wrapper-form-withdraw py-2">
                        <div class="form-group  row mb-2">
                            <label class="col-md-4 col-form-label form-control-label">Deposite Anda</label>
                            <div class="col-md-8">
                                <input type="text" name="deposite" class="form-control numbercurrency" value="<?php echo $total_deposite; ?>" disabled="">
                            </div>
                        </div>
                        <div class="form-group  row mb-2">
                            <label class="col-md-4 col-form-label form-control-label" for="nominal">Withdraw</label>
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
<?php endif; ?>
