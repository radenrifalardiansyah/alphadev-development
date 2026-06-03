<div class="header bg-white pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_board') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin ) { ?>
                    <div class="col-lg-6">
                        <form class="navbar-search navbar-search-light form-inline" id="form-search-member-board-tree" data-url="<?php echo base_url('member/searchboardtree/'.$board); ?>" style="float: right;">
                            <div class="form-group mb-0">
                                <div class="input-group input-group-alternative input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input class="form-control" id="search_member_board_tree" placeholder="<?php echo lang('search_member_username'); ?> ..." type="text">
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

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="row justify-content-center">
                <div class="col-lg-12 card-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0"><?php echo $menu_title; ?> </h3>
                                    <?php if ( $is_admin && !empty($member_other) ) { ?>
                                        <h4>
                                            <a href="<?php echo base_url('profile/' . kd_encrypt($member_other->id)); ?>" class="text-primary">
                                                <strong><?php echo $member_other->username . ' ('. $member_other->name .')'; ?></strong>
                                            </a>
                                        </h4>
                                    <?php } ?>
                                </div>
                                <?php if ( $is_admin ) { ?>
                                    <div class="col text-right d-sm-none">
                                        <a class="btn-tooltip" title="<?php echo lang('search_member_username'); ?>" href="#" data-action="search-show" data-target="#navbar-search-main">
                                            <i class="ni ni-zoom-split-in"></i>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-body pt-0 px-1">
                            <?php include "treediagram.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>