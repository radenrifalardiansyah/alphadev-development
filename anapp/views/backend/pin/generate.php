<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_pin'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_pin_create'); ?></li>
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
                                    <h3 class="mb-0">Form <?php echo lang('menu_pin_create'); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body wrapper-form-pin-generate">
                            <form role="form" method="post" action="<?php echo base_url('pin/savegenerate'); ?>" id="form-pin-generate" class="form-horizontal" data-id="<?php echo an_encrypt($member->id) ?>" >
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
                                                        <button class="btn btn-default" type="button" id="btn_search_member" data-url="<?php echo base_url('member/searchmember'); ?>" data-type="html" data-form="pin_generate"><i class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="member_info"></div>
                                        <hr class="mb-3">
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label form-control-label" for="select_product"><?php echo lang('product'); ?> </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <select class="form-control" name="select_product" id="select_product">
                                                        <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('product'); ?> --</option>
                                                        <?php
                                                            if ( $get_products = an_products(0, true) ) {
                                                                foreach ($get_products as $key => $row) {
                                                                    $id_product = an_encrypt($row->id);
                                                                    $img_src    = an_product_image($row->image, true); 
                                                                    echo '<option value="'. $id_product .'" 
                                                                            data-price="'. $row->price .'"
                                                                            data-weight="'. $row->weight .'"
                                                                            data-image="'. $img_src .'">'. ucwords($row->name) .'</option>';
                                                                }   
                                                            }
                                                        ?>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-default" type="button" id="btn-add-pin-item"><i class="fa fa-plus"></i> <?php echo lang('select') ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-container table-responsive mb-4">
                                            <table class="table align-items-center table-flush" id="list_table_product_pin_generate">
                                                <thead class="thead-light">
                                                    <tr role="row" class="heading">
                                                        <th scope="col" style="width: 10px">#</th>
                                                        <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                                        <th scope="col" class="text-center" style="min-width: 100px !important; width: 200px !important;"><?php echo lang('price'); ?></th>
                                                        <th scope="col" class="text-center" style="min-width: 150px !important; width: 170px !important;">Qty</th>
                                                        <th scope="col" class="text-right" style="min-width: 100px !important; width: 230px !important;">Subtotal</th>
                                                        <th scope="col" class="text-center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list">
                                                    <tr class="pin_item_empty">
                                                        <td colspan="6" class="text-center">No data available in table</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr style="background-color: #f6f9fc">
                                                        <td colspan="3"></td>
                                                        <th class="py-2"><span class="h3 text-warning">Total</span></th>
                                                        <th class="text-right py-2"><span class="h3 text-warning pin-total-paymnet">0</span></th>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <h3><?php echo lang('payment_method') .' & '. lang('shipping_method'); ?></h3>
                                        <hr class="mt-2 mb-3">
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
                                        <div class="form-group row">
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
                                        <div class="accordion info_shipping_method" id="accordionShipping" style="display: none;">
                                            <div class="card" style="border: 1px solid #172b4d !important;">
                                                <div class="card-header py-3" id="headShipping" data-toggle="collapse" data-target="#shippingInfo" aria-expanded="false" aria-controls="shippingInfo">
                                                    <h5 class="mb-0"><?php echo lang('shipping_address'); ?></h5>
                                                </div>
                                                <div id="shippingInfo" class="collapse show" aria-labelledby="headShipping" data-parent="#accordionShipping">
                                                    <div class="card-body px-5">
                                                        <div class="form-group row mb-2">
                                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="name" id="name" class="form-control text-uppercase" placeholder="<?php echo lang('fullname'); ?>" autocomplete="off" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-2">
                                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_hp'); ?> <span class="required">*</span></label>
                                                            <div class="col-md-9">
                                                                <div class="input-group input-group-merge">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><b>+62</b></span>
                                                                    </div>
                                                                    <input type="text" name="phone" id="phone" class="form-control numbermask phonenumber" placeholder="8xxxxxxxxx" />
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
                                                                    <input type="text" name="email" id="email" class="form-control text-lowercase" placeholder="<?php echo lang('reg_email'); ?>"/>
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
                                                                                echo '<option value="'.$p->id.'">'.$p->province_name.'</option>';
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
                                                                <select class="form-control select_district" name="district" id="district" disabled="disabled" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
                                                                    <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>  

                                                        <!-- District -->
                                                        <div class="form-group row mb-2">
                                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
                                                            <div class="col-md-9">
                                                                <select class="form-control select_subdistrict" name="subdistrict" id="subdistrict" disabled="disabled" data-toggle="select2">
                                                                    <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>  

                                                        <!-- Village  -->
                                                        <div class="form-group row mb-2">
                                                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_desa'); ?> <span class="required">*</span></label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="village" id="village" class="form-control text-capitalize" placeholder="<?php echo lang('reg_desa'); ?>" />
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
                                                                    <input type="text" name="address" id="address" class="form-control text-capitalize" placeholder="Alamat Lengkap" />
                                                                </div>
                                                            </div>
                                                        </div>
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
                                        <?php echo lang('menu_pin_create'); ?>
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
