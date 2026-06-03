<?php $member_other_id = an_encrypt($member->id); ?>

<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
                </div>

                <?php if ( $is_admin && empty($member_other) ): ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="javascript:;" class="btn btn-sm btn-outline-default" id="btn-modal-loan-deposite">Deposite <?php echo $menu_title; ?></a>
                        <a href="javascript:;" class="btn btn-sm btn-outline-warning" id="btn-modal-loan-withdraw">Withdraw <?php echo $menu_title; ?></a>
                    </div>
                <?php endif; ?>
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
                            <h3 class="mb-0"><?php echo 'List '. $menu_title; ?> </h3>
                        </div>

                    </div>
                </div>
                <?php 
                    if ( $is_admin ) {
                        if( !empty($member_other) ) {
                            $this->load->view(VIEW_BACK . 'member/memberloanlist/listmemberother');
                        } else {
                            $this->load->view(VIEW_BACK . 'member/memberloanlist/listadmin');
                        }
                    } else {
                        $this->load->view(VIEW_BACK . 'member/memberloanlist/listmember');
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php if ( $is_admin && empty($member_other) ): ?>
    <!-- Modal Form Deposite Loan -->
    <div class="modal fade" id="modal-form-loan-deposite" tabindex="-1" role="dialog" aria-labelledby="modal-form-loan-deposite" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-credit-card"></i> Deposite Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="post" action="<?php echo base_url('member/memberloan/'.an_encrypt('deposite')); ?>" id="form-loan-deposite" class="form-horizontal">
                    <div class="modal-body wrapper-form-loan-deposite py-2">
                        <div class="form-group mb-2">
                            <label class="form-control-label" for="username"><?php echo lang('username'); ?> <span class="required">*</span></label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" name="username" id="username_deposite" class="form-control text-lowercase search_member" placeholder="<?php echo lang('username'); ?>" autocomplete="off" />
                                <span class="input-group-append">
                                    <button class="btn btn-default btn_search_member" type="button" data-url="<?php echo base_url('member/searchmember'); ?>" data-type="data" data-form="member_loan"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" name="member_name" id="member_name_deposite" class="form-control text-uppercase" placeholder="<?php echo lang('name'); ?>" disabled="" />
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="form-group">
                            <label class="form-control-label" for="amount">Deposite</label>
                            <input type="text" id="amount_deposite" name="amount" class="form-control numbercurrency">
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

    <!-- Modal Form Withdraw Loan -->
    <div class="modal fade" id="modal-form-loan-withdraw" tabindex="-1" role="dialog" aria-labelledby="modal-form-loan-withdraw" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-credit-card"></i> Withdraw Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="post" action="<?php echo base_url('member/memberloan/'.an_encrypt('withdraw')); ?>" id="form-loan-withdraw" class="form-horizontal">
                    <div class="modal-body wrapper-form-loan-withdraw py-2">
                        <div class="form-group mb-2">
                            <label class="form-control-label" for="username"><?php echo lang('username'); ?> <span class="required">*</span></label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" name="username" id="username_withdraw" class="form-control text-lowercase search_member" placeholder="<?php echo lang('username'); ?>" autocomplete="off" />
                                <span class="input-group-append">
                                    <button class="btn btn-default btn_search_member" type="button" data-url="<?php echo base_url('member/searchmember'); ?>" data-type="data" data-form="member_loan" data-inputid="username_withdraw"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" name="member_name" id="member_name_withdraw" class="form-control text-uppercase" placeholder="<?php echo lang('name'); ?>" disabled="" />
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="form-group">
                            <label class="form-control-label">Deposite</label>
                            <input type="text" name="deposite" class="form-control numbercurrency" disabled="">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="amount">Withdraw</label>
                            <input type="text" id="amount_withdraw" name="amount" class="form-control numbercurrency">
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
