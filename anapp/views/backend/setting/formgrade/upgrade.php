<form role="form" method="post" action="<?php echo base_url('setting/updategradeupgrade'); ?>" id="form-setting-grade-upgrade" class="form-horizontal">
    <div class="card-body wrapper-setting-grade-upgrade px-0 pt-0">
        <div class="table-responsive table-container">
            <table class="table align-items-center table-flush" id="list_table_grade_upgrade">
                <thead class="thead-light">
                    <tr role="row" class="heading">
                        <th scope="col" style="width: 10px">#</th>
                        <th scope="col" class="text-center"><?php echo lang('grade'); ?></th>
                        <th scope="col" class="text-center">Personal PV<br>/ Bulan</th>
                        <th scope="col" class="text-center">Group PV<br>/ Bulan</th>
                        <th scope="col" class="text-center">Group Aktif<br>/ Bulan</th>
                        <th scope="col" class="text-center">Periode Qualifikasi</th>
                        <th scope="col" class="text-center">Lama Periode</th>
                    </tr>
                </thead>
                <tbody class="list">
                <?php
                    if ( $cfg_packages ) {
                        $num = 1;
                        foreach ($cfg_packages as $key => $row) {
                            $upg_id     = adv_encrypt($row->package);
                            if ( !$row->package_next ) { continue; }
                            $package_next = adv_packages($row->package_next);
                            if ( !$package_next ) { continue; }

                            echo '
                            <tr>
                                <td>'. $num .'</td>
                                <td class="text-center">
                                    '. $row->package_name . br(). '
                                    <i class="ni ni-bold-down"></i>'. br() .'
                                    <b>'. $package_next->package_name .'</b>
                                </td>
                                <td>
                                    <input type="text" name="upgrade['.$upg_id.'][personal]" class="form-control form-control-sm numbercurrency text-right" value="'. $row->upgrade_personal_pv .'" />
                                </td>
                                <td>
                                    <input type="text" name="upgrade['.$upg_id.'][group]" class="form-control form-control-sm numbercurrency text-right" value="'. $row->upgrade_group_pv .'" />
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="upgrade['.$upg_id.'][group_active]" class="form-control form-control-sm numbercurrency text-center" value="'. $row->upgrade_group_active .'" />
                                        <div class="input-group-append">
                                            <span class="input-group-text py-0"><i class="ni ni-single-02 mr-2"></i> <small>'. $row->package .'</small></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="upgrade['.$upg_id.'][period_min]" class="form-control form-control-sm numbercurrency text-center" value="'. $row->upgrade_period_min .'" />
                                </td>
                                <td>
                                    <input type="text" name="upgrade['.$upg_id.'][period]" class="form-control form-control-sm numbercurrency text-center" value="'. $row->upgrade_period .'" />
                                </td>
                            </tr>';
                            $num++;
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer my-0">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-info my-0"><?php echo lang('save') . ' ' . lang('menu_setting'); ?></button>
            </div>
        </div>
    </div>
</form>