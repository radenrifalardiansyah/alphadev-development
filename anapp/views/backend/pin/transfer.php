<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_pin'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_pin_transfer'); ?></li>
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
                                    <h3 class="mb-0">Form <?php echo lang('menu_pin_transfer'); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-pin-transfer">
                            <form role="form" method="post" action="<?php echo base_url('pin/savetransfer'); ?>" id="form-pin-transfer" class="form-horizontal" data-id="<?php echo an_encrypt($member->id) ?>" >
                                <div class="row justify-content-center">
                                    <div class="col-md-10 col-sm-12">
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                    </div>
                                                    <input type="text" name="username" id="username" class="form-control text-lowercase search_member" placeholder="<?php echo lang('username'); ?>" autocomplete="off" />
                                                    <span class="input-group-append">
                                                        <button class="btn btn-default" type="button" id="btn_search_member" data-url="<?php echo base_url('member/searchmember'); ?>" data-type="html" data-form="transfer"><i class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="member_info"></div>
                                        <div class="form-group row mb-2">
                                            <label class="col-md-3 col-form-label form-control-label" for="select_product"><?php echo lang('product'); ?> </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <select class="form-control select_product" name="select_product" id="select_product" data-load="<?php echo ('pin/pinmemberproduct'); ?>" data-code="<?php echo an_encrypt($member->id); ?>" data-type="pintransfer" data-access="member" data-form="form-pin-transfer">
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
                                            <label class="col-md-3 col-form-label form-control-label">Password <span class="required">*</span></label>
                                            <div class="col-md-9">
                                                <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                                    </div>
                                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Password Konfirmasi" autocomplete="off" value="" />
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-default pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">
                                        <i class="fa fa-cart-plus mr-2"></i> 
                                        <?php echo lang('menu_pin_transfer'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
