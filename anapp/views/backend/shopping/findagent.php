<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb" class="ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_shopping'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
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
                            <h3 class="mb-0">
                                <i class="fas fa-search"></i> <?php echo $menu_title; ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ( $message ) { ?>
                        <div class="alert alert-warning"><?php echo $message; ?></div>
                    <?php } ?>

                    <div class="form-group row mb-0">
                        <div class="col-md-3 mb-1">
                            <select class="form-control select_province" name="member_province" id="member_province" data-form="findagency" data-url="<?php echo base_url('address/selectprovince'); ?>" data-toggle="select2">
                                <option value="" selected="">-- Semua Provinsi --</option>
                                <?php
                                    if ( $provinces = an_provinces() ) {
                                        foreach ($provinces as $key => $row) {
                                            $province_name  = ucwords(strtolower($row->province_name));
                                            $province_name  = str_replace('Dki ', 'DKI ', $province_name);
                                            $province_name  = str_replace('Di ', 'DI ', $province_name);
                                            echo '<option value="'. $row->id .'">'. $province_name .'</option>';
                                        }   
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
                            <select class="form-control select_district" name="member_district" id="member_district" data-url="<?php echo base_url('address/selectdistrict'); ?>" data-toggle="select2">
                                <option value=""><?php echo lang('reg_pilih_kota'); ?></option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-1">
                            <select class="form-control select_subdistrict" name="member_subdistrict" id="member_subdistrict" data-url="<?php echo base_url('address/selectsubdistrict'); ?>" data-toggle="select2">
                                <option value=""><?php echo lang('reg_pilih_kecamatan'); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1 text-center">
                            <button type="button" class="btn btn-outline-default" id="btn-find-agent" title="Search"><i class="fa fa-search"></i> <?php echo lang('search'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_find_agent" data-url="<?php echo base_url('member/stockistlistsdata/'. an_encrypt('find_agent')); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                <th scope="col"><?php echo lang('name'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('phone'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('reg_alamat'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
                                <td><input type="text" class="form-control form-control-sm form-filter numbermask" name="search_phone" /></td>
                                <td></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit d-none" id="btn_list_table_find_agent" title="Search"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm btn-outline-warning btn-tooltip filter-clear d-none" title="Reset"><i class="fa fa-times"></i></button>
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