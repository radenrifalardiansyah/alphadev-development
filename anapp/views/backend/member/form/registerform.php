<?php 
    $file_no_image  = ASSET_PATH . 'backend/img/no_image.jpg'; 
    $select_country = false; 
?>

<h3><?php echo lang('reg_sponsor_information'); ?></h3>
<hr class="mt-2 mb-3">

<!-- Username Sponsor -->
<?php if( $is_admin ): ?>
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_sponsor_username'); ?> <span class="required">*</span></label>
        <div class="col-md-9">
            <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                </div>
                <input type="hidden" name="reg_member_sponsor_admin" id="reg_member_sponsor_admin" value="admin" />
                <input type="text" name="reg_member_sponsor" id="reg_member_sponsor" class="form-control text-lowercase" placeholder="<?php echo lang('reg_sponsor_username'); ?>" autocomplete="off" />
                <span class="input-group-append">
                    <button class="btn btn-default" type="button" id="btn_search_sponsor" data-url="<?php echo base_url('member/searchsponsor'); ?>"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="form-group row mb-2">
        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_sponsor_username'); ?> <span class="required">*</span></label>
        <div class="col-md-9">
            <input type="hidden" name="current_member_username" value="<?php echo $member->username; ?>" />
            <input type="hidden" name="current_member_name" value="<?php echo $member->name; ?>" />
            <div class="btn-group" data-toggle="buttons">
                <label id="other_sponsor" class="btn spon active">
                    <input name="sponsored" class="toggle sponsored d-none" type="radio" value="other_sponsor" checked="checked" />Sponsor
                </label>
                <label id="as_sponsor" class="btn spon">
                    <input name="sponsored" class="toggle sponsored d-none" type="radio" value="as_sponsor" /><?php echo lang('reg_saya_sponsor'); ?>
                </label>
            </div>
            <div id="sponsor_form" style="margin-top: 5px;">
                <div class="input-group">
                    <input type="text" name="reg_member_sponsor" id="reg_member_sponsor" class="form-control text-lowercase" placeholder="<?php echo lang('reg_sponsor_username'); ?>" autocomplete="off" />
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btn_search_sponsor" 
                        data-url="<?php echo base_url('member/searchsponsor'); ?>" 
                        data-form="newmember"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<div id="sponsor_info"></div>

<hr class="mt-3">

<h3><?php echo lang('reg_member_information'); ?></h3>
<hr class="mt-2 mb-3">

<!-- Username -->
<?php if ( $input_username = true ) : ?>
<div class="form-group row mb-2">
	<label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
            </div>
            <input type="text" name="reg_member_username" id="reg_member_username" class="form-control text-lowercase" placeholder="<?php echo lang('reg_username_ex'); ?>" autocomplete="off" data-url="<?php echo base_url('member/checkusername'); ?>" />
        </div>
    </div>
</div>
<?php else: ?>
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="alert alert-info mb-0"><b><?php echo lang('username'); ?></b> will be created by system</div>
    </div>
</div>
<?php endif; ?>

<!-- Password -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_password'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" name="reg_member_password" id="reg_member_password" class="form-control" placeholder="<?php echo lang('reg_valid_password'); ?>" autocomplete="off" value="" />
            <div class="input-group-append">
                <button class="btn btn-default pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmed Password -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label">Confirm password <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="password" name="reg_member_password_confirm" id="reg_member_password_confirm" class="form-control" placeholder="Konfirmasi Password" autocomplete="off" value="" />
            <div class="input-group-append">
                <button class="btn btn-default pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>
    </div>
</div>

<hr class="my-3">

<div id="cloning-data" class="form-group row mb-2" data-url="<?php echo base_url('member/cloning/'.an_encrypt('newmember')); ?>" >
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_data_new_old'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="btn-group" data-toggle="buttons">
            <label id="new_data" class="btn clone active">
                <input name="cloning" class="cloning d-none" type="radio" value="new" checked="checked" /><?php echo lang('reg_data_baru'); ?>
            </label>
            <label id="clone_data" class="btn clone">
                <input name="cloning" class="cloning d-none" type="radio" value="clone" /><?php echo ( $is_admin ) ? lang('reg_clone_data') : lang('reg_my_data_clone'); ?>
            </label>
        </div>
        <?php if ( $is_admin ) : ?>
            <div id="clone_form" style="margin-top: 5px; display: none;">
                <div class="input-group">
                    <input type="text" name="reg_member_clone" id="reg_member_clone" class="form-control text-lowercase" placeholder="<?php echo lang('username'); ?>" autocomplete="off" />
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btn_search_clone"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<hr class="my-3">

<!-- Nama -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="reg_member_name" id="reg_member_name" class="form-control text-uppercase" placeholder="<?php echo lang('reg_fullname'); ?>" autocomplete="off" value="" />
        </div>
    </div>
</div> 

<!-- Email -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_email'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            </div>
            <input type="text" name="reg_member_email" id="reg_member_email" class="form-control text-lowercase" placeholder="<?php echo lang('reg_email'); ?>" data-url="<?php echo base_url('member/checkemail'); ?>" />
        </div>
    </div>
</div>

<!-- No. HP/WA -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_hp'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">+62</span>
            </div>
            <input type="text" name="reg_member_phone" id="reg_member_phone" class="form-control numbermask phonenumber" placeholder="<?php echo lang('reg_no_hp'); ?>" data-url="<?php echo base_url('member/checkphone'); ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-mobile"></i></span>
            </div>
        </div>
    </div>
</div> 

<!-- No. Telp -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_telp'); ?> (Home) </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
            </div>
            <input type="text" name="reg_member_phone_home" id="reg_member_phone_home" class="form-control numbermask" placeholder="<?php echo lang('reg_no_telp'); ?> (Home)" />
        </div>
    </div>
</div>  

<!-- No. Telp -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_telp'); ?> (Office) </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
            </div>
            <input type="text" name="reg_member_phone_office" id="reg_member_phone_office" class="form-control numbermask" placeholder="<?php echo lang('reg_no_telp'); ?> (Office)"  />
        </div>
    </div>
</div>  

<!-- Place of Birth -->
<?php $pob = false; ?>
<?php if ( $pob ) { ?>
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_tempat_lahir'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <input type="text" name="reg_member_pob" id="reg_member_pob" class="form-control text-uppercase" placeholder="<?php echo lang('reg_tempat_lahir'); ?>" autocomplete="off" value="" />
    </div>
</div> 
<?php } ?>

<!-- Date of Birth -->
<?php $dob = false; ?>
<?php if ( $dob ) { ?>
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_tgl_lahir'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-4">
                <select class="form-control" name="reg_member_dob_date" id="reg_member_dob_date" >
                    <option value=""><?php echo lang('select') .' '. lang('date'); ?></option>
                    <?php
                        for($i=1; $i<=31; $i++){
                            $dob = str_pad($i, 2, '0', STR_PAD_LEFT);
                            echo '<option value="'. $dob .'">'. $dob .'</option>';
                        }
                    ?> 
                </select>
            </div>
            <div class="col-4">
                <select class="form-control" name="reg_member_dob_month" id="reg_member_dob_month" >
                    <option value=""><?php echo lang('select') .' '. lang('month'); ?></option>
                    <?php
                    $cfg_month  = config_item('month');
                        for($i=1; $i<=12; $i++){
                            $mob = str_pad($i, 2, '0', STR_PAD_LEFT);
                            echo '<option value="'. $mob .'">'. ( isset($cfg_month[$i]) ? $cfg_month[$i] : '' ) .'</option>';
                        }
                    ?> 
                </select>
            </div>
            <div class="col-4">
                <select class="form-control" name="reg_member_dob_year" id="reg_member_dob_year" >
                    <option value=""><?php echo lang('select') .' '. lang('year'); ?></option>
                    <?php
                        $current_year = date('Y');
                        for($i=($current_year-17); $i>=($current_year-75); $i--){
                            echo '<option value="'. $i .'">'. $i .'</option>';
                        }
                    ?> 
                </select>
            </div>
        </div>
    </div>
</div> 
<?php } ?>

<!-- Gender -->
<?php $gender = false; ?>
<?php if ( $gender ) { ?>
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_gender'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="row pt-2 custom-radio-inline">
            <?php
                $cfg_gender = config_item('gender');
                if( !empty($cfg_gender) ){
                    foreach($cfg_gender as $k => $v){
                        echo '
                            <div class="col-md-3 col-5">
                                <div class="custom-control custom-radio">
                                    <input name="reg_member_gender" class="custom-control-input" id="reg_member_gender_'.$k.'" type="radio" value="'.$k.'">
                                    <label class="custom-control-label" for="reg_member_gender_'.$k.'">'. lang($v) .'</label>
                                </div>
                            </div>
                        ';
                    }
                }
            ?> 
        </div>
    </div>
</div>  
<?php } ?>

<!-- Marital Status -->
<?php $marital = false; ?>
<?php if ( $marital ) { ?>
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_marital'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="row pt-2 custom-radio-inline">
            <?php
                $cfg_marital = config_item('marital');
                if( !empty($cfg_marital) ){
                    foreach($cfg_marital as $k => $v){
                        echo '
                            <div class="col-md-3 col-5">
                                <div class="custom-control custom-radio">
                                    <input name="reg_member_marital" class="custom-control-input" id="reg_member_marital_'.$k.'" type="radio" value="'.$k.'">
                                    <label class="custom-control-label" for="reg_member_marital_'.$k.'">'. lang($v) .'</label>
                                </div>
                            </div>
                        ';
                    }
                }
            ?> 
        </div>
    </div>
</div>  
<?php } ?>

<!-- Personal Document -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_personal_doc'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control" name="reg_member_idcard_type" id="reg_member_idcard_type">
            <?php
                $personal_doc = config_item('personal_docoment');
                if( !empty($personal_doc) ){
                    foreach($personal_doc as $k => $v){
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>  

<!-- ID Card -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_ktp'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-badge"></i></span>
            </div>
            <input type="text" name="reg_member_idcard" id="reg_member_idcard" class="form-control numbermask" placeholder="<?php echo lang('reg_no_ktp'); ?>" data-url="<?php echo base_url('member/checkidcard'); ?>" />
        </div>
    </div>
</div>  

<!-- NPWP -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label">NPWP </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
            </div>
            <input type="text" class="form-control npwp" name="reg_member_npwp" id="reg_member_npwp" >
        </div>
    </div>
</div>

<hr class="mt-3">

<h3><?php echo lang('reg_address_information'); ?></h3>
<hr class="mt-2 mb-3">

<!-- Province -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control select_province" name="reg_member_province" id="reg_member_province" data-form="register" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
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
        <select class="form-control select_district" name="reg_member_district" id="reg_member_district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
        </select>
    </div>
</div>  

<!-- District -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control select_subdistrict" name="reg_member_subdistrict" id="reg_member_subdistrict" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
        </select>
    </div>
</div>  

<!-- Village -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_desa'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <input type="text" name="reg_member_village" id="reg_member_village" class="form-control text-capitalize" placeholder="<?php echo lang('reg_desa'); ?>" />
    </div>
</div> 

<!-- Alamat -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
            </div>
            <input type="text" name="reg_member_address" id="reg_member_address" class="form-control text-capitalize" placeholder="<?php echo lang('reg_alamat'); ?> " />
        </div>
    </div>
</div>

<!-- Kode POS -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_postcode'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="ni ni-square-pin"></i></span>
            </div>
            <input type="text" name="reg_member_postcode" id="reg_member_postcode" class="form-control text-capitalize" placeholder="<?php echo lang('reg_postcode'); ?> " />
        </div>
    </div>
</div>

<hr class="mt-3">

<h3><?php echo lang('reg_bank_information'); ?></h3>
<hr class="mt-2 mb-3">

<!-- Bank -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_bank'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <select class="form-control" name="reg_member_bank" id="reg_member_bank" data-toggle="select2">
            <option value=""><?php echo lang('reg_pilih_bank'); ?></option>
            <?php
                $banks = an_banks();
                if( !empty($banks) ){
                    foreach($banks as $b){
                        echo '<option value="'.$b->id.'">'.$b->kode.' - '.$b->nama.'</option>';
                    }
                }
            ?>
        </select>
    </div>
</div>

<!-- Bill Number -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_rekening'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
            </div>
            <input type="text" class="form-control numbermask" name="reg_member_bill" id="reg_member_bill" placeholder="<?php echo lang('reg_no_rekening'); ?>" data-url="<?php echo base_url('member/checkbill'); ?>" >
        </div>
    </div>
</div>

<!-- Bill Owner -->
<div class="form-group row">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_pemilik_rek'); ?> <span class="required">*</span></label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" class="form-control text-uppercase" name="reg_member_bill_name" id="reg_member_bill_name" placeholder="<?php echo lang('reg_pemilik_rek'); ?>" readonly="readonly" >
        </div>
    </div>
</div>

<hr class="mt-3">

<h3><?php echo lang('reg_emergency_contact'); ?></h3>
<hr class="mt-2 mb-3">

<!-- Nama -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="reg_member_emergency_name" id="reg_member_emergency_name" class="form-control text-uppercase" placeholder="<?php echo lang('name'); ?>" autocomplete="off" value="" />
        </div>
    </div>
</div> 

<!-- Relationship -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_relationship'); ?> </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="reg_member_emergency_relationship" id="reg_member_emergency_relationship" class="form-control text-uppercase" placeholder="<?php echo lang('reg_relationship'); ?>" autocomplete="off" value="" />
        </div>
    </div>
</div> 

<!-- No. HP/WA -->
<div class="form-group row mb-2">
    <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_no_hp'); ?> </label>
    <div class="col-md-9">
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">+62</span>
            </div>
            <input type="text" name="reg_member_emergency_phone" id="reg_member_emergency_phone" class="form-control numbermask phonenumber" placeholder="<?php echo lang('reg_no_hp'); ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-mobile"></i></span>
            </div>
        </div>
    </div>
</div> 

<hr class="mt-3">

<div class="form-group row mb-1">
    <label class="col-md-3 col-form-label form-control-label">&nbsp;</label>
    <div class="col-md-9">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="reg_member_term" id="reg_member_term" value="1" <?php echo set_checkbox( 'reg_member_term', '1' ); ?>>
            <label class="custom-control-label" for="reg_member_term" style="vertical-align: unset;">Saya Setuju Dengan Persayaratan Dan Kondisi Pendaftaran.</label>
            <a href="javascript:;" class="term_condition text-warning">Term &amp; Condition</a>
        </div>
    </div>
</div>
