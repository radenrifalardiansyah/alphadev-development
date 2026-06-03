<?php $cfg_member_type_status = config_item('member_type_status'); ?>

<div class="header bg-white pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_list'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin) { ?>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="<?php echo base_url('member/new') ?>" class="btn btn-sm btn-outline-default"><i class="fa fa-plus mr-1"></i> <?php echo lang('menu_member_new'); ?></a>
                    </div>
                <?php } ?>
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
                            <h3 class="mb-0"><?php echo lang('menu_member_list') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_member" data-url="<?php echo base_url('member/memberlistsdata'); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center">Sponsor</th>
                                <th scope="col" class="text-center">Upline</th>
                                <th scope="col" class="text-center"><?php echo lang('status'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('join_date'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('last_login'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_sponsor" /></td>
                                <td class="px-1"><input type="text" class="form-control form-control-sm form-filter" name="search_upline" /></td>
                                <td class="px-1">
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <?php 
                                            if ( $cfg_member_type_status ) {
                                                foreach ($cfg_member_type_status as $key => $value) {
                                                    echo '<option value="'.$key.'">'. strtoupper($value) .'</option>';
                                                }
                                            } 
                                        ?>
                                    </select>
                                </td>
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
                                <td></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_member" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-block btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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

<!-- Modal Stockist -->
<div class="modal fade" id="modal_select_stockist" tabindex="-1" role="dialog" aria-labelledby="modal_select_stockist" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ni ni-book-bookmark"></i> <?php echo lang('menu_promo_code'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" action="<?php echo base_url('member/memberstatus'); ?>" id="form-stockist" class="form-horizontal">
                <input type="hidden" name="asmember" id="asmember" class="d-none change-stockist" value="" />
                <div class="modal-body wrapper-form-stockist py-2">
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                </div>
                                <input type="text" class="form-control text-lowercase change-stockist change-stockist-username" disabled="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('name'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control text-lowercase change-stockist change-stockist-name" disabled="" />
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label">Status Anggota <span class="required">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control" name="stockist_status" id="stockist_status">
                                <?php 
                                    /*
                                    if ( $cfg_member_type ) {
                                        foreach ($cfg_member_type as $key => $value) {
                                            echo '<option value="'.$key.'">'. strtoupper($value) .'</option>';
                                        }
                                    } 
                                    */
                                ?>
                            </select>
                        </div>
                    </div>
                    -->

                    <!-- Province -->
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_provinsi'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select_province" name="stockist_province" id="stockist_province" data-form="stockist" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
                                <option value=""><?php echo lang('reg_pilih_provinsi'); ?></option>
                                <?php
                                    $province = an_provinces();
                                    if( !empty($province) ){
                                        foreach($province as $p){
                                            $province_name  = ucwords(strtolower($p->province_name));
                                            $province_name  = str_replace('Dki ', 'DKI ', $province_name);
                                            $province_name  = str_replace('Di ', 'DI ', $province_name);
                                            echo '<option value="'.$p->id.'">'. $province_name .'</option>';
                                        }
                                    }
                                ?> 
                            </select>
                        </div>
                    </div>   

                    <!-- District -->
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kota'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select_district" name="stockist_district" id="stockist_district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
                                <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
                            </select>
                        </div>
                    </div>  
                    
                    <!-- Sub-District -->
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_kecamatan'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <select class="form-control select_subdistrict" name="stockist_subdistrict" id="stockist_subdistrict" data-url="<?php echo base_url('address/selectsubdistrict'); ?>" data-toggle="select2">
                                <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Village -->
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_desa'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="stockist_village" id="stockist_village" class="form-control text-capitalize" placeholder="<?php echo lang('reg_desa'); ?>" />
                        </div>
                    </div>  

                    <!-- Address -->
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label form-control-label"><?php echo lang('reg_alamat'); ?> <span class="required">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="stockist_address" id="stockist_address" class="form-control text-capitalize" placeholder="<?php echo lang('reg_desa'); ?>" />
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
