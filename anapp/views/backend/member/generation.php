<?php
    $member_name    = ucwords(strtolower($member->name)) .' ('. strtolower($member->username) .')';
    $id_sponsor     = $member->id;
    $url_gentree    = base_url('member/generationtree/'.$member->username);
    $url_gentdata   = base_url('member/generationdata/'.$member->username);
    $member_link    = base_url('profile');
    if ( $is_admin && $member_other ) {
        $member_name    = ucwords(strtolower($member_other->name)) .' ('. strtolower($member_other->username) .')';
        $id_sponsor     = $member_other->id;
        $url_gentree    = base_url('member/generationtree/'.$member_other->username);
        $url_gentdata   = base_url('member/generationdata/'.$member_other->username);
        $member_link    = base_url('profile') .'/'. an_encrypt($id_sponsor);
    }

    $staff_view = false;
    if ($staff = an_get_current_staff()) {
        if ( $staff->access == 'partial' && empty($member_other) ) {
            $staff_view = true;
        }
    }
?>

<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_generation'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin && !$staff_view ) { ?>
                    <div class="col-lg-6">
                        <form class="navbar-search navbar-search-light form-inline" id="form-search-generation-member" data-url="<?php echo base_url('member/generation'); ?>" style="float: right;">
                            <div class="form-group mb-0">
                                <div class="input-group input-group-alternative input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input class="form-control" id="search_generation_member" placeholder="<?php echo lang('search_member_username'); ?> ..." type="text">
                                </div>
                            </div>
                            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ( $staff_view ) : ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12">
                <div class="row justify-content-center">
                    <div class="col-lg-12 card-wrapper">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-0"><?php echo lang('menu_member_generation'); ?> </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-5">
                                <form role="form" id="form-search-generation-member" class="form-horizontal" data-url="<?php echo base_url('member/generation'); ?>" >
                                    <div class="row justify-content-center">
                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group row mb-2">
                                                <label class="col-md-3 col-form-label form-control-label"><?php echo lang('username'); ?> <span class="required">*</span></label>
                                                <div class="col-md-9">
                                                    <div class="input-group input-group-merge">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                                        </div>
                                                        <input type="text" id="search_generation_member" class="form-control text-lowercase" placeholder="<?php echo lang('search_member_username'); ?>" autocomplete="off" />
                                                        <span class="input-group-append">
                                                            <button class="btn btn-default" type="button" id="btn_search_generation_member"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0"><?php echo lang('list_member_generation') ?> </h3>
                                <h6 class="text-primary text-uppercase ls-1 mb-0" style="font-size: 0.75rem;"><i class="ni ni-single-02 mr-1"></i><?php echo $member_name; ?></h6>
                            </div>
                            <?php if ( $is_admin) { ?>
                                <div class="col text-right d-sm-none">
                                    <a class="btn-tooltip" title="<?php echo lang('search_member_username'); ?>" href="#" data-action="search-show" data-target="#navbar-search-main">
                                        <i class="ni ni-zoom-split-in"></i>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="table align-items-center table-flush" id="list_table_generation_member" data-url="<?php echo $url_gentdata; ?>">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('username'); ?></th>
                                    <th scope="col"><?php echo lang('name'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('grade'); ?></th>
                                    <th scope="col" class="text-center">Sponsor</th>
                                    <th scope="col" class="text-center"><?php echo lang('generation'); ?></th>
                                    <th scope="col" class="text-center"><?php echo lang('actions'); ?></th>
                                </tr>
                                <tr role="row" class="filter" style="background-color: #f6f9fc">
                                    <td></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_username" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_name" /></td>
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
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_sponsor" /></td>
                                    <td><input type="text" class="form-control form-control-sm form-filter" name="search_generation" /></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-outline-default btn-tooltip filter-submit" id="btn_list_table_generation_member" title="Search"><i class="fa fa-search"></i></button>
                                        <button class="btn btn-sm btn-outline-warning btn-tooltip filter-cancel" title="Reset"><i class="fa fa-times"></i></button>
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
<?php endif; ?>