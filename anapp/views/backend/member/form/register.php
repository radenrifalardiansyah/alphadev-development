<?php
    $url        = 'member/memberreg';
    $form       = 'member/form/registerform';
    $formid     = 'member_register';
    $lock       = config_item('lock');
    /*
    $pin_empty  = false;
    if ( !$is_admin && !$lock ) {
        $pin = an_member_pin($member->id, 'active', true);
        if ( !$pin ) {
            $lock = $pin_empty = true;
        }
    }
    */
?>

<!-- BEGIN REGISTER -->
<div class="header bg-white pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_new') ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="row justify-content-center">
                <div class="col-lg-12 card-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0"><?php echo lang('reg_member_formulir'); ?> </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-register pt-0">
                            <?php if( $lock ): ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php if( $pin_empty ): ?>
                                        <h4 class="alert-heading"><i class="fa fa-bell"></i> Informasi Pendaftaran</h4>
                                        <p class="mb-0">Anda tidak dapat mempunyai PIN Produk aktif. Silahkan Pesan PIN Produk terlebih dahulu !</p>
                                        <a class="btn btn-sm btn-neutral my-2 text-default" href="<?php echo base_url('shopping/shoplist') ?>"> Pesan PIN Produk</a>
                                    <?php else: ?>
                                        <h4 class="alert-heading"><i class="fa fa-bell"></i> This service is temporarily unavailabe</h4>
                                        <p class="mb-0">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <?php 
                                    $access         = ($is_admin) ? 'admin' : 'member'; 
                                    $cfg_reg_fee    = 0; 
                                    $saldo          = 0; 
                                ?>
                                <?php echo form_open( $url, array( 'id'=>$formid, 'role'=>'form', 'class'=>'form-horizontal', 'data-access'=>$access, 'data-id'=>an_encrypt($member->id), 'data-useruid'=>$member->username, 'data-regfee'=>$cfg_reg_fee, 'data-deposite'=>$saldo ) ); ?>
                                    <!-- Alert Message -->
                                    <div id="alert" class="alert display-hide"></div>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">

                                            <?php if ( $packagedata && count($packagedata) == 1 ) : ?>
                                                <?php $package_bv = $packagedata[0]->bv; ?>
                                                <div class="alert alert-primary mb-5" role="alert">
                                                    <h4 class="alert-heading"><i class="fa fa-bell"></i> Informasi Pendaftaran</h4>
                                                    <p class="mb-0">Untuk pendaftaran member baru dibutuhkan omzet minimal <b><?php echo an_accounting($package_bv); ?> BV.</b></p>
                                                </div>
                                            <?php endif; ?>

                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label" for="select_product"><?php echo lang('product'); ?> </label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <select class="form-control select_product" name="select_product" id="select_product" 
                                                        data-load="<?php echo ('pin/pinmemberproduct'); ?>" 
                                                        data-code="<?php echo an_encrypt($member->id); ?>" 
                                                        data-type="register" 
                                                        data-access="<?php echo ($is_admin ? 'admin' : 'member') ?>" 
                                                        data-form="<?php echo $formid; ?>">
                                                            <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('product'); ?> --</option>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-default" type="button" id="btn-add-pin"><i class="fa fa-plus"></i> <?php echo lang('select') ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="select_product_list"></div>
                                            <hr class="my-3">
                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label" for="total_bv">Total BV</label>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <input type="text" name="total_bv" id="total_bv" class="form-control numbercurrency" placeholder="0" readonly="" />
                                                        <span class="input-group-append"><span class="input-group-text">BV</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label" for="package"><?php echo lang('package') ?></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="package" id="package" class="form-control text-uppercase" placeholder="<?php echo lang('package') ?>" readonly="" />
                                                    <input type="hidden" name="reg_member_package" id="reg_member_package" class="form-control" />
                                                </div>
                                            </div>
                                            <hr class="mt-3">

                                            <h3><?php echo lang('reg_upline_information'); ?></h3>
                                            <hr class="mt-2 mb-3">

                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_upline_username'); ?> <span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                        </div>
                                                        <input type="text" name="reg_member_upline" id="reg_member_upline" class="form-control text-lowercase" placeholder="<?php echo lang('reg_upline_username'); ?>" autocomplete="off" />
                                                        <span class="input-group-append">
                                                            <button class="btn btn-default" type="button" id="btn_search_upline" data-url="<?php echo base_url('member/searchupline'); ?>"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="upline_info"></div>

                                            <hr class="mt-3">

                                            <?php $this->load->view(VIEW_BACK . $form); ?>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-5">
                                            <button type="submit" class="btn btn-default bg-gradient-default" id="btn-register"><?php echo lang('reg_register_member'); ?></button> 
                                            <button type="button" class="btn btn-danger bg-gradient-danger btn-register-reset"><?php echo lang('reset'); ?></button>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                                <?php include "registermodal.php"; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="term_condition_modal" tabindex="-1" role="dialog" aria-labelledby="term_condition_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ni ni-single-copy-04"></i> Term &amp; Condition <small class="text-warning"><?php echo COMPANY_NAME; ?></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <?php 
                    if ( $term_conditions = config_item('term_conditions') ) {
                        echo "<ul>";
                        foreach ($term_conditions as $key => $value) {
                            echo '<li>'.$value.'</li>';
                        }
                        echo "</ul>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>