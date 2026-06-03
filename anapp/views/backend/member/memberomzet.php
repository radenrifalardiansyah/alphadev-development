<?php $member_other_id = an_encrypt($member_other->id); ?>

<div class="header bg-gradient-default pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('omzet'); ?></li>
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
                            <h3 class="mb-0"><?php echo 'List '. lang('omzet'); ?> </h3>
                            <?php if ( $is_admin ) { ?>
                                <h4><a href="<?php echo base_url('profile/' . $member_other_id); ?>" class="text-primary"><strong><?php echo $member_other->username . ' ('. $member_other->name .')'; ?></strong></a></h4>
                            <?php } ?>
                        </div>
                        <?php if ( $is_admin ) { ?>
                            <div class="col text-right">                 
                                <a href="<?php echo base_url('member/lists'); ?>" class="btn btn-sm btn-outline-warning"><i class="fa fa-step-backward"></i> <?php echo lang('back'); ?></a>
                            </div>
                        <?php } ?>

                    </div>
                </div>
                <div class="alert alert-info mb-0"><b><i class="fa fa-info"></i> INFORMATION!</b> Omset Sales dikalkulasi tiap malam hari.</div>
                <div class="table-container">
                    <table class="table align-items-center table-flush" id="list_table_member_omzet" data-url="<?php echo base_url('member/memberomzetlistsdata/'.$member_other_id); ?>">
                        <thead class="thead-light">
                            <tr role="row" class="heading">
                                <th scope="col" style="width: 10px">#</th>
                                <th scope="col" class="text-center"><?php echo lang('month'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('grade'); ?></th>
                                <th scope="col" class="text-center"><?php echo lang('omzet'); ?> PV</th>
                                <th scope="col" class="text-center"><?php echo lang('omzet') .' '. lang('group'); ?> PV</th>
                                <th scope="col" class="text-center"><?php echo lang('group') .' '. lang('active'); ?></th>
                                <th scope="col" class="text-center">Qualified</th>
                                <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                            </tr>
                            <tr role="row" class="filter" style="background-color: #f6f9fc">
                                <td></td>
                                <td>
                                    <div class="input-group input-group-sm date date-picker-month mb-1" data-date-format="yyyy-mm">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_min" placeholder="From" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                    <div class="input-group input-group-sm date date-picker-month" data-date-format="yyyy-mm">
                                        <input type="text" class="form-control form-control-sm form-filter" readonly name="search_datecreated_max" placeholder="To" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-white btn-flat" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <select name="search_package" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <?php 
                                            if ( $cfg_package = config_item('package') ) {
                                                foreach ($cfg_package as $key => $value) {
                                                    echo '<option value="'.$key.'">'. strtoupper($value) .'</option>';
                                                }
                                            } 
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_group_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_omzet_group_max" placeholder="Max" />
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_group_active_min" placeholder="Min" />
                                    </div>
                                    <input type="text" class="form-control form-control-sm form-filter text-center numbermask" name="search_group_active_max" placeholder="Max" />
                                </td>
                                <td>
                                    <select name="search_status" class="form-control form-control-sm form-filter">
                                        <option value=""><?php echo lang('select'); ?>...</option>
                                        <option value="qualified">QUALIFIED</option>
                                        <option value="notqualified">NOT QUALIFIED</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-sm btn-block btn-outline-default btn-tooltip filter-submit" id="btn_list_table_member_omzet" title="Search"><i class="fa fa-search"></i></button>
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
