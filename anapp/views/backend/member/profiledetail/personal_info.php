<!-- Personal Info -->
<?php if( $as_member ): ?>

    <?php echo form_open( 'member/personalinfo', array( 'id'=>'personal', 'class'=>'form-horizontal', 'role'=>'form', 'enctype'=>'multipart/form-data' ) ); ?>

        <?php if( !empty($member_other) && $member_other->type == MEMBER ): ?>
            <input type="hidden" name="member_id" value="<?php echo $member_other->id; ?>" />
        <?php endif ?>

        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                <div class="col-md-9">
                    <input type="text" class="form-control text-uppercase" placeholder="Username" value="<?php echo $the_member->username; ?>" disabled="disabled">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_name"><?php echo lang('name'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $the_member->name; ?>">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_email"><?php echo lang('reg_email'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="email" name="member_email" id="member_email" class="form-control text-lowercase" value="<?php echo $the_member->email; ?>">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_phone"><?php echo lang('reg_no_hp'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+62</span>
                        </div>
                        <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $the_member->phone; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_phone_home"><?php echo lang('reg_no_telp'); ?> (Home)</label>
                <div class="col-md-9">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+62</span>
                        </div>
                        <input type="text" name="member_phone_home" id="member_phone_home" class="form-control numbermask phonenumber" value="<?php echo $the_member->phone_home; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_phone_office"><?php echo lang('reg_no_telp'); ?>  (Office)</label>
                <div class="col-md-9">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+62</span>
                        </div>
                        <input type="text" name="member_phone_office" id="member_phone_office" class="form-control numbermask phonenumber" value="<?php echo $the_member->phone_office; ?>">
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-3" />
        <div class="pl-lg-4">
            <!-- Place of Birth -->
            <?php $pob = false; ?>
            <?php if ( $pob ) { ?>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_tempat_lahir'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="member_pob" id="member_pob" class="form-control text-uppercase" placeholder="<?php echo lang('reg_tempat_lahir'); ?>" autocomplete="off" value="<?php echo $the_member->pob; ?>" />
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
                            <select class="form-control" name="member_dob_date" id="member_dob_date" >
                                <option value="" disabled=""><?php echo lang('select') .' '. lang('date'); ?></option>
                                <?php
                                    for($i=1; $i<=31; $i++){
                                        $dob        = str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $selected   = ( date('d', strtotime($the_member->dob)) == $dob ) ? 'selected' : '';
                                        echo '<option value="'. $dob .'" '. $selected .'>'. $dob .'</option>';
                                    }
                                ?> 
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-control" name="member_dob_month" id="member_dob_month" >
                                <option value="" disabled=""><?php echo lang('select') .' '. lang('month'); ?></option>
                                <?php
                                $cfg_month  = config_item('month');
                                    for($i=1; $i<=12; $i++){
                                        $mob = str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $selected   = ( date('m', strtotime($the_member->dob)) == $mob ) ? 'selected' : '';
                                        echo '<option value="'. $mob .'"'. $selected .'>'. ( isset($cfg_month[$i]) ? $cfg_month[$i] : '' ) .'</option>';
                                    }
                                ?> 
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="form-control" name="member_dob_year" id="member_dob_year" >
                                <option value="" disabled=""><?php echo lang('select') .' '. lang('year'); ?></option>
                                <?php
                                    $current_year = date('Y');
                                    for($i=($current_year-17); $i>=($current_year-75); $i--){
                                        $selected   = ( date('Y', strtotime($the_member->dob)) == $i ) ? 'selected' : '';
                                        echo '<option value="'. $i .'"'. $selected .'>'. $i .'</option>';
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
                                    $checked = ( $k == $the_member->gender ) ? 'checked' : '';
                                    echo '
                                        <div class="col-md-3 col-5">
                                            <div class="custom-control custom-radio">
                                                <input name="member_gender" class="custom-control-input" id="member_gender_'.$k.'" type="radio" value="'.$k.'" '.$checked.'>
                                                <label class="custom-control-label" for="member_gender_'.$k.'">'. lang($v) .'</label>
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
                                    $checked = ( $k == $the_member->marital ) ? 'checked' : '';
                                    echo '
                                        <div class="col-md-3 col-5">
                                            <div class="custom-control custom-radio">
                                                <input name="member_marital" class="custom-control-input" id="member_marital_'.$k.'" type="radio" value="'.$k.'" '.$checked.'>
                                                <label class="custom-control-label" for="member_marital_'.$k.'">'. lang($v) .'</label>
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
                    <select class="form-control" name="member_idcard_type" id="member_idcard_type">
                        <?php
                            $personal_doc = config_item('personal_docoment');
                            if( !empty($personal_doc) ){
                                foreach($personal_doc as $k => $v){
                                    $selected = ( $k == $the_member->idcard_type ) ? 'selected=""' : '';
                                    echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
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
                        <input type="text" name="member_idcard" id="member_idcard" class="form-control numbermask" placeholder="<?php echo lang('reg_no_ktp'); ?>" value="<?php echo $the_member->idcard; ?>" />
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
                        <input type="text" class="form-control npwp" name="member_npwp" id="member_npwp" value="<?php echo $the_member->npwp; ?>">
                    </div>
                </div>
            </div>

        </div>
        <hr class="my-4" />

        <!-- Current Address -->
        <h6 class="heading-small text-muted mb-4"><?php echo lang('reg_address_information'); ?></h6>
        <div class="pl-lg-4">
            <?php
                $member_province    = $the_member->province;
                $provinces          = an_provinces();
            ?>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_province"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control select_province" name="member_province" id="member_province" data-form="profile" data-url="<?php echo base_url('address/selectprovince'); ?>" data-el="select_current" data-toggle="select2">
                        <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
                        <?php
                            if( !empty($provinces) ){
                                foreach($provinces as $p){
                                    $selected = ''; 
                                    if ( $p->id == $member_province ) {
                                        $selected = 'selected=""'; 
                                    }
                                    echo '<option value="'.$p->id.'" '.$selected.'>'.$p->province_name.'</option>';
                                }
                            }
                        ?> 
                    </select>
                </div>
            </div>
            <?php
                $member_city        = $the_member->district;
                $cities             = an_districts_by_province($member_province);
            ?>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_district"><?php echo lang('reg_kota'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control select_district" name="member_district" id="member_district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-el="select_current" data-toggle="select2" >
                        <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                        <?php
                            if( !empty($cities) ){
                                foreach($cities as $c){
                                    $city_name  = $c->district_type .' '. $c->district_name; 
                                    $selected = ''; 
                                    if ( $c->id == $member_city ) {
                                        $selected = 'selected=""'; 
                                    }
                                    echo '<option value="'.$c->id.'" '.$selected.'>'.$city_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <?php
                $member_subdistrict = $the_member->subdistrict;
                $subdistricts       = an_subdistricts_by_district($member_city);
            ?>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_subdistrict"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control select_subdistrict" name="member_subdistrict" id="member_subdistrict" data-el="select_current" data-toggle="select2">
                        <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                        <?php
                            if( !empty($subdistricts) ){
                                foreach($subdistricts as $s){
                                    $selected = ''; 
                                    if ( $s->id == $member_subdistrict ) {
                                        $selected = 'selected=""'; 
                                    }
                                    echo '<option value="'.$s->id.'" '.$selected.'>'.$s->subdistrict_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_village"><?php echo lang('reg_desa'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="member_village" id="member_village" class="form-control" value="<?php echo $the_member->village; ?>">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_address"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="member_address" id="member_address" class="form-control" value="<?php echo $the_member->address; ?>">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_address"><?php echo lang('reg_postcode'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="member_postcode" id="member_postcode" class="form-control" value="<?php echo $the_member->postcode; ?>">
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <!-- Description -->
        <h6 class="heading-small text-muted mb-4">Informasi Akun Bank</h6>
        <?php 
            $member_bank    = $the_member->bank; 
            $banks          = an_banks();
            $region_codes   = an_region_codes();
        ?>
        <div class="pl-lg-4">
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_bank"><?php echo lang('reg_bank'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control" name="member_bank" id="member_bank" data-toggle="select2">
                        <option value=""><?php echo lang('reg_pilih_bank'); ?></option>
                        <?php
                            if( !empty($banks) ){
                                foreach($banks as $b){
                                    $selected   = ( $b->id == $member_bank ) ? 'selected=""' : ''; 
                                    echo '<option value="'.$b->id.'" '.$selected.'>'.$b->kode.' - '.$b->nama.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_bill"><?php echo lang('reg_no_rekening'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control numbermask" name="member_bill" id="member_bill" placeholder="<?php echo lang('reg_no_rekening'); ?>"  value="<?php echo $the_member->bill; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_bill_name"><?php echo lang('reg_pemilik_rek'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_bill_name" id="member_bill_name" placeholder="<?php echo lang('reg_pemilik_rek'); ?>" value="<?php echo $the_member->bill_name; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_bank_branch"><?php echo lang('reg_cabang_bank'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_bank_branch" id="member_bank_branch" placeholder="<?php echo lang('reg_cabang_bank'); ?>" value="<?php echo $the_member->branch; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_city_code"><?php echo lang('reg_kode_kota'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control" name="member_city_code" id="member_city_code" data-toggle="select2">
                        <option value=""><?php echo lang('reg_pilih_kode_kota'); ?></option>
                        <?php
                            if( !empty($region_codes) ){
                                foreach($region_codes as $rc){
                                    $selected   = ( $rc->region_code == $the_member->city_code ) ? 'selected=""' : ''; 
                                    echo '<option value="'.$rc->region_code.'" '.$selected.'>'.$rc->region_code.' - '.$rc->region_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_bill_name">Withdraw Status <span class="required">*</span></label>
                <div class="col-md-9">
                    <select class="form-control" name="member_wd_status" id="member_wd_status">
                        <option value="0" <?php echo ($the_member->wd_status == 0 ? 'selected=""' : ''); ?>>Withdraw Otomatis Harian</option>
                        <option value="1" <?php echo ($the_member->wd_status == 1 ? 'selected=""' : ''); ?>>Withdraw Manual</option>
                    </select>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <h6 class="heading-small text-muted mb-4"><?php echo lang('reg_emergency_contact'); ?></h6>
        <div class="pl-lg-4">
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_emergency_name"><?php echo lang('name'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_emergency_name" id="member_emergency_name" placeholder="<?php echo lang('name'); ?>" value="<?php echo $the_member->emergency_name; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_emergency_relationship"><?php echo lang('reg_relationship'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_emergency_relationship" id="member_emergency_relationship" placeholder="<?php echo lang('reg_relationship'); ?>"  value="<?php echo $the_member->emergency_relationship; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_emergency_phone"><?php echo lang('reg_no_hp'); ?> <span class="required">*</span></label>
                <div class="col-md-9">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+62</span>
                        </div>
                        <input type="text" name="member_emergency_phone" id="member_emergency_phone" class="form-control numbermask phonenumber" value="<?php echo $the_member->emergency_phone; ?>">
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <h6 class="heading-small text-muted mb-4">Social Media</h6>
        <div class="pl-lg-4">
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_facebook_url">Facebook URL</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_facebook_url" id="member_facebook_url" placeholder="Facebook URL" value="<?php echo $the_member->facebook_url; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_twitter_url">Twitter URL</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_twitter_url" id="member_twitter_url" placeholder="Twitter URL" value="<?php echo $the_member->twitter_url; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_instagram_url">Instagram URL</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_instagram_url" id="member_instagram_url" placeholder="Instagram URL" value="<?php echo $the_member->instagram_url; ?>" >
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-md-3 col-form-label form-control-label" for="member_tiktok_url">Tiktok URL</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="member_tiktok_url" id="member_tiktok_url" placeholder="Tiktok URL" value="<?php echo $the_member->tiktok_url; ?>" >
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?></button>
        </div>
    <?php echo form_close(); ?>

<?php elseif($as_staff): ?>

    <?php echo form_open( 'member/staffinfo', array( 'id'=>'personal', 'class'=>'form-horizontal mb-4', 'role'=>'form' ) ); ?>
        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                        <input type="text" name="member_username" id="member_username" class="form-control text-lowercase" placeholder="Username" value="<?php echo $staff->username; ?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_name"><?php echo lang('name'); ?></label>
                        <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $staff->name; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_email"><?php echo lang('reg_email'); ?></label>
                        <input type="email" name="member_email" id="member_email" class="form-control" value="<?php echo $staff->email; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_phone"><?php echo lang('reg_no_telp'); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $staff->phone; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?> Profile</button>
        </div>
    <?php echo form_close(); ?>

    <div class="accordion" id="accordionChangePassword">
        <div class="card mb-3">
            <div class="card-header bg-gradient-info" id="headChangePassword" data-toggle="collapse" data-target="#collapseChangePassword" aria-expanded="false" aria-controls="collapseChangePassword">
                <h5 class="text-white mb-0">Ganti Password</h5>
            </div>
            <div id="collapseChangePassword" class="collapse" aria-labelledby="headChangePassword" data-parent="#accordionChangePassword">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <?php echo form_open( 'member/changepasswordstaff', array( 'id'=>'cpassword', 'role'=>'form' ) ); ?>
                                <div class="form-group">
                                    <label class="control-label">Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cur_pass" id="cur_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_pass" id="new_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cnew_pass" id="cnew_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">Ganti Password</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="save_cpassword" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i>  Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin akan mengubah password ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <?php echo form_open( 'member/admininfo', array( 'id'=>'personal', 'class'=>'form-horizontal mb-4', 'role'=>'form' ) ); ?>
        <h6 class="heading-small text-muted mb-4">Informasi Akun</h6>
        <div class="pl-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_username"><?php echo lang('username'); ?></label>
                        <input type="text" name="member_username" id="member_username" class="form-control text-lowercase" placeholder="Username" value="<?php echo $member->username; ?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_name"><?php echo lang('name'); ?></label>
                        <input type="text" name="member_name" id="member_name" class="form-control text-uppercase" value="<?php echo $member->name; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_email"><?php echo lang('reg_email'); ?></label>
                        <input type="email" name="member_email" id="member_email" class="form-control" value="<?php echo $member->email; ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-control-label" for="member_phone"><?php echo lang('reg_no_telp'); ?></label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="member_phone" id="member_phone" class="form-control numbermask phonenumber" value="<?php echo $member->phone; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <div class="text-center">
            <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?> Profile</button>
        </div>
    <?php echo form_close(); ?>

    <div class="accordion" id="accordionChangePassword">
        <div class="card mb-3">
            <div class="card-header bg-gradient-info" id="headChangePassword" data-toggle="collapse" data-target="#collapseChangePassword" aria-expanded="false" aria-controls="collapseChangePassword">
                <h5 class="text-white mb-0">Ganti Password</h5>
            </div>
            <div id="collapseChangePassword" class="collapse" aria-labelledby="headChangePassword" data-parent="#accordionChangePassword">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <?php echo form_open( 'member/changepassword', array( 'id'=>'cpassword', 'role'=>'form' ) ); ?>
                                <div class="form-group">
                                    <label class="control-label">Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cur_pass" id="cur_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="new_pass" id="new_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cnew_pass" id="cnew_pass" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-flat btn-white pass-show-hide" type="button"><i class="fa fa-eye-slash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <hr class="my-4" />
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2">Ganti Password</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="save_cpassword" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i>  Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin akan mengubah password ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-default" id="do_save_cpassword" data-form="cpassword">Lanjut</button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<!-- BEGIN CONFIRMATION MODAL -->
<div class="modal fade" id="save_profile" tabindex="-1" role="dialog" aria-labelledby="modalsave_profile" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-edit"></i>  Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah profil <?php echo ( !empty($member_other) && $member_other->type == MEMBER ? 'anggota <strong>' . $member_other->username . '</strong>' : 'Anda' ); ?> sudah benar ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="do_save_profile">Lanjut</button>
            </div>
        </div>
    </div>
</div>
<!-- END CONFIRMATION MODAL -->