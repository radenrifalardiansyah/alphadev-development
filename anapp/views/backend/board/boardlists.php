<div class="header bg-white pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_board') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_board_member_list'); ?></li>
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
                            <h3 class="mb-0"><?php echo lang('menu_board_member_list'); ?> </h3>
                        </div>
                    </div>
                </div>
                <?php 
                    if ( $is_admin ) {
                        $this->load->view(VIEW_BACK . 'board/boardlist/listadmin');
                    } else {
                        $this->load->view(VIEW_BACK . 'board/boardlist/listmember');
                    }
                ?>
            </div>
        </div>
    </div>
</div>
