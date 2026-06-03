<?php
    $total_payment  = $this->cart->total();
    $product_type   = strtolower($cart_content['product_type']);

    $id_province    = $member->province;
    $id_district    = $member->district;
    $id_subdistrict = $member->subdistrict;
    $village        = $member->village;
    $address        = $member->address;

    if ( $member->as_stockist >= 1 ) {
        $id_province    = ($member->province_stockist) ? $member->province_stockist : $id_province;
        $id_district    = ($member->district_stockist) ? $member->district_stockist : $id_district;
        $id_subdistrict = ($member->subdistrict_stockist) ? $member->subdistrict_stockist : $id_subdistrict;
    }
?>

<div class="header bg-white pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb" class="ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_shopping'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-8 card-wrapper">
            <div class="card mb-2">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-1"><?php echo $menu_title; ?> </h3>
                            <h6 class="text-primary text-uppercase ls-1 mb-0" style="font-size: 0.75rem;">
                                <i class="ni ni-bag-17 mr-1"></i>
                                <?php echo lang('menu_shopping'); ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ( $member->as_stockist == 0 ) { ?>
                <?php if ( $seller ) { ?>
                    <div class="card mb-3">
                        <div class="card-header py-2">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3 class="mb-1">Informasi Stockist</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?php echo base_url('find-agency') ?>" class="btn btn-sm btn-outline-primary"><span class="fa fa-user"></span> Ganti Stockist</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar avatar-xl rounded-circle">
                                        <img alt="Image placeholder" src="<?php echo BE_IMG_PATH; ?>icons/avatar.png">
                                    </a>
                                </div>
                                <div class="col">
                                    <h4 class="mb-2"><a href="#!"><?php echo $seller->username; ?></a></h4>
                                    <p class="text-sm text-muted mb-1">
                                        <i class="ni ni-single-02 mr-1"></i> <?php echo $seller->name; ?>
                                    </p>
                                    <p class="text-sm text-muted mb-1">
                                        <i class="fa fa-phone-alt mr-1"></i> <?php echo $seller->phone; ?>
                                    </p>
                                    <p class="text-sm text-muted mb-1">
                                        <i class="ni ni ni-square-pin mr-1"></i> Alamat :<br>
                                        <?php echo $seller->address; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php if ( $member->as_stockist == 1 ) { ?>
                        <div class="alert alert-primary" role="alert">
                            <h4 class="alert-heading"><i class="fa fa-bell"></i> Informasi Pesanan</h4>
                            <p class="mb-0">
                                Saat ini anda belanja ke <strong>PUSAT</strong>. Apakah anda ingin order ke Agency ? 
                                 <a href="<?php echo base_url('find-agency') ?>" class="btn btn-sm btn-neutral text-primary ml-2"><span class="fa fa-user"></span> Pilih Agency</a>
                            </p>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

            <div class="card mb-3">
                <div class="card-header py-3">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="mb-1">Ringkasan Order</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <?php if ( isset($cart_content['data']) && !empty($cart_content['data']) ) : $num = 1; ?>
                        <table class="table">
                        <?php foreach ($cart_content['data'] as $key => $row) : ?>
                            <?php 
                                $cart_price     = an_accounting($row['cart_price']);
                                $cart_subtotal  = an_accounting($row['cart_subtotal']);
                            ?>
                            <tr>
                                <td class="text-capitalize px-1 pl-2 py-2">
                                    <span class="text-primary font-weight-bold" style="white-space: normal"><?php echo $row['product_name']; ?></span><br>
                                    <span class="small">
                                        Qty : <span class="font-weight-bold mr-1"><?php echo an_accounting($row['qty']); ?></span>
                                        ( <?php echo $cart_price; ?> )
                                    </span>
                                </td>
                                <td class="text-right px-1 pr-2 pt-2">
                                    <?php echo $cart_subtotal; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <hr class="mt-0 mb-0">
                    <?php $cart_total = an_accounting($total_payment); ?>
                    <div class="px-2 py-2">
                        <div class="row">
                            <div class="col-7"><span class="font-weight-bold">Subtotal</span></div>
                            <div class="col-5 text-right"><span class="font-weight-bold"><?php echo $cart_total; ?></span></div>
                        </div>
                        <div class="row">
                            <div class="col-7"><span class="font-weight-bold">Total Berat</span></div>
                            <div class="col-5 text-right"><span class="font-weight-bold"><?php echo $cart_total; ?></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header py-3">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h4 class="mb-1"><?php echo lang('payment_method') .' & '. lang('shipping_method');; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-1 wrapper-form-shopping-checkout">
                    <form role="form" method="post" action="<?php echo base_url('shopping/checkout'); ?>" id="form-shopping-checkout" class="form-horizontal" data-id="<?php echo an_encrypt($member->id) ?>" data-deposite="<?php echo $saldo; ?>" data-subtotal="<?php echo $total_payment; ?>" >
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('payment_method'); ?> <span class="required">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="payment_method" id="payment_method">
                                    <?php
                                        $payment_method = config_item('payment_method');
                                        if( !empty($payment_method) ){
                                            foreach($payment_method as $k => $val){
                                                echo '<option value="'.$k.'">'.$val.'</option>';
                                            }
                                        }
                                    ?> 
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('shipping_method'); ?> <span class="required">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="shipping_method" id="shipping_method">
                                    <?php
                                        $shipping_method = config_item('shipping_method');
                                        if( !empty($shipping_method) ){
                                            foreach($shipping_method as $k => $val){
                                                echo '<option value="'.$k.'">'.$val.'</option>';
                                            }
                                        }
                                    ?> 
                                </select>
                            </div>
                        </div>  

                        <div class="info_shipping_method" style="display: none;">
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" class="form-control text-uppercase" placeholder="<?php echo lang('fullname'); ?>" autocomplete="off" value="<?php echo $member->name; ?>" />
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_hp'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><b>+62</b></span>
                                        </div>
                                        <input type="text" name="phone" id="phone" class="form-control numbermask phonenumber" placeholder="8xxxxxxxxx" value="<?php echo $member->phone; ?>" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-mobile"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_email'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="text" name="email" id="email" class="form-control text-lowercase" placeholder="<?php echo lang('reg_email'); ?>" value="<?php echo $member->email; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <!-- Province -->
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control select_province" name="province" id="province" data-form="pin_generate" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
                                        <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
                                        <?php
                                            $province = an_provinces();
                                            if( !empty($province) ){
                                                foreach($province as $p){
                                                    $selected = ''; 
                                                    if ( $p->id == $id_province ) {
                                                        $selected = 'selected=""'; 
                                                    }
                                                    echo '<option value="'.$p->id.'" '.$selected.'>'.$p->province_name.'</option>';
                                                }
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>  
                            <!-- City -->
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kota'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control select_district" name="district" id="district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
                                        <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
                                        <?php
                                            $cities = an_districts_by_province($id_province);
                                            if( !empty($cities) ){
                                                foreach($cities as $c){
                                                    $city_name  = $c->district_type .' '. $c->district_name; 
                                                    $selected   = ''; 
                                                    if ( $c->id == $id_district ) {
                                                        $selected = 'selected=""'; 
                                                    }
                                                    echo '<option value="'.$c->id.'" '.$selected.'>'.$city_name.'</option>';
                                                }
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>  
                            <!-- District -->
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control select_subdistrict" name="subdistrict" id="subdistrict" data-toggle="select2">
                                        <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                                        <?php
                                            $subdistricts = an_subdistricts_by_district($id_district);
                                            if( !empty($subdistricts) ){
                                                foreach($subdistricts as $s){
                                                    $selected   = ''; 
                                                    if ( $s->id == $id_subdistrict ) {
                                                        $selected = 'selected=""'; 
                                                    }
                                                    echo '<option value="'.$s->id.'" '.$selected.'>'.$s->subdistrict_name.'</option>';
                                                }
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>  
                            <!-- Village  -->
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_desa'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" name="village" id="village" class="form-control text-capitalize" placeholder="<?php echo lang('reg_desa'); ?>" value="<?php echo $member->village; ?>" />
                                </div>
                            </div>
                            <!-- Alamat  -->
                            <div class="form-group row mb-2">
                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
                                <div class="col-md-9">
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
                                        </div>
                                        <input type="text" name="address" id="address" class="form-control text-capitalize" placeholder="Alamat Lengkap" value="<?php echo $member->address; ?>" />
                                    </div>
                                </div>
                            </div>

                            <?php if ( $member->as_stockist == 0 ): ?>
                                <hr class="my-3">
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="select_courier"><?php echo lang('courier'); ?> </label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-truck"></i></span>
                                                    </div>
                                                    <select class="form-control" name="select_courier" id="select_courier" data-url="<?php echo base_url('address/selectcourier'); ?>">
                                                        <option value="" selected="">-- <?php echo lang('select').' '.lang('courier'); ?> --</option>
                                                        <?php
                                                            if ( $get_couriers = config_item('courier') ) {
                                                                foreach ($get_couriers as $key => $row) {
                                                                    echo '<option value="'. $row['code'] .'" >'. $row['name'] .'</option>';
                                                                }   
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <select class="form-control" name="select_service" id="select_service">
                                                    <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('service'); ?> --</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="text" name="courier_cost" id="courier_cost" class="form-control numbermask" placeholder="Ongkir" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <?php 
                        $cart_payment   = an_accounting($total_payment, $currency);
                        $cart_saldo     = an_accounting($saldo, $currency);
                    ?>
                    <?php if ( $member->as_stockist == 0 ): ?>
                        <div class="row align-items-center mb-2">
                            <div class="col-7"><span class="heading-small text-capitalize font-weight-bold">Subtotal</span></div>
                            <div class="col-5 text-right">
                                <span class="text-capitalize text-primary font-weight-bold"><?php echo $cart_total; ?></span>
                            </div>
                        </div>
                        <div class="row align-items-center mb-2">
                            <div class="col-7"><span class="heading-small text-capitalize font-weight-bold"><?php echo lang('shipping_fee'); ?></span></div>
                            <div class="col-5 text-right">
                                <span class="text-capitalize text-primary font-weight-bold shipping_fee">0</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row pb-3 align-items-center">
                        <div class="col-7"><span class="heading-small text-capitalize font-weight-bold"><?php echo lang('total_payment'); ?></span></div>
                        <div class="col-5 text-right">
                            <span class="heading text-capitalize text-warning font-weight-bold total_payment"><?php echo $cart_payment; ?></span>
                        </div>
                    </div>
                    <hr class="mt-0 mb-4">
                    <div class="card-footer pb-3">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <a href="<?php echo base_url('cart'); ?>" class="btn btn-outline-warning">
                                    <i class="fa fa-arrow-left mr-1" aria-hidden="true"></i> <?php echo lang('back'); ?>
                                </a>
                                <a href="javascript:;" class="btn btn-default btn-shopping-checkout">
                                     <i class="fa fa-check mr-1" aria-hidden="true"></i>  Konfirmasi Checkout
                                </a >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>