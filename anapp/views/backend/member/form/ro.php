<?php
    $url    = 'member/memberro';
    $formid = 'member_ro';
    $lock   = config_item('lock');
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
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_ro') ?></li>
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
                                    <h3 class="mb-0"><?php echo lang('menu_member_ro'); ?> </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-ro pt-0">
                            <?php if( $lock ): ?>
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading"><i class="fa fa-bell"></i> This service is temporarily unavailabe</h4>
                                    <p class="mb-0">We are currently performing scheduled maintenance. Normal service will be restored soon. Thank you.</p>
                                </div>
                            <?php else: ?>
                                <?php 
                                    $access         = ($is_admin) ? 'admin' : 'member'; 
                                    $cfg_reg_fee    = 0; 
                                    $saldo          = 0; 
                                ?>
                                <?php echo form_open( $url, array( 'id'=>$formid, 'role'=>'form', 'class'=>'form-horizontal', 'data-access'=>$access, 'data-id'=>an_encrypt($member->id), 'data-username'=>$member->username, 'data-name'=>$member->username ) ); ?>
                                    <!-- Alert Message -->
                                    <div id="alert" class="alert display-hide"></div>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-10">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label form-control-label"></label>
                                                <div class="col-md-9">
                                                    <input type="hidden" name="current_member_username" value="<?php echo $member->username; ?>" />
                                                    <div class="btn-group" data-toggle="buttons">
                                                        <label id="as_ro" class="btn optro active">
                                                            <input name="optionro" class="toggle optionro d-none" type="radio" value="me" checked="checked" />Aktivasi RO Saya
                                                        </label>
                                                        <label id="other_ro" class="btn optro">
                                                            <input name="optionro" class="toggle optionro d-none" type="radio" value="other" /> Aktivasi RO Member Lain
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-2 option-ro-username">
                                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                        </div>
                                                        <input type="text" name="username" id="username" class="form-control text-lowercase search_member" placeholder="<?php echo lang('username'); ?>" autocomplete="off" disabled="disabled" value="<?php echo $member->username; ?>" />
                                                        <span class="input-group-append btn-search-member" style="display: none;">
                                                            <button class="btn btn-default" type="button" id="btn_search_member" data-url="<?php echo base_url('member/searchmember'); ?>" data-type="data" data-form="ro"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> </label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                        </div>
                                                        <input type="text" name="member_name" id="name" class="form-control text-uppercase" placeholder="<?php echo lang('name'); ?>" autocomplete="off" disabled="disabled" value="<?php echo $member->name; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-3">
                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label" for="select_pin_product"><?php echo lang('product'); ?> </label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ni ni-box-2"></i></span>
                                                        </div>
                                                        <select class="form-control select_pin_product" name="select_pin_product" id="select_pin_product" data-load="<?php echo ('pin/pinmemberproduct'); ?>" data-code="<?php echo an_encrypt($member->id); ?>" data-type="ro" data-access="member" data-form="<?php echo $formid; ?>">
                                                            <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('product'); ?> --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label" for="select_pin">PIN <?php echo lang('product'); ?> <span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ni ni-tag"></i></span>
                                                        </div>
                                                        <select class="form-control select_pin" name="select_pin" id="select_pin">
                                                            <option value="" disabled="" selected="">-- <?php echo lang('select').' PIN '.lang('product'); ?> --</option>
                                                        </select>
                                                        <span class="input-group-append select-pin-load" style="border: 1px solid #dee2e6; display: none;">
                                                            <button class="btn" type="button" disabled="">
                                                              <span class="spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="select_pin_list"></div>
                                            <hr class="mt-3">
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-5">
                                            <button type="submit" class="btn btn-default bg-gradient-default" id="btn-register">Aktivasi RO</button> 
                                            <button type="button" class="btn btn-danger bg-gradient-danger btn-register-reset"><?php echo lang('reset'); ?></button>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>