<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_financial') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_financial_commission'); ?></li>
                        </ol>
                    </nav>
                </div>
                <?php if ( $is_admin && !empty($member_other) ) { ?>
                    <div class="col-lg-6 col-5 text-right">                 
                        <a href="<?php echo base_url('commission/commission'); ?>" class="btn btn-sm btn-outline-warning"><i class="fa fa-step-backward"></i> <?php echo lang('back'); ?></a>
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
                            <h3 class="mb-0"><?php echo lang('menu_financial_commission'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php 
                        if ( $is_admin ) {
                            if( !empty($member_other) ) {
                                $this->load->view(VIEW_BACK . 'commission/commission/listmemberother');
                            } else {
                                $this->load->view(VIEW_BACK . 'commission/commission/listadmin');
                            }
                        } else {
                            $this->load->view(VIEW_BACK . 'commission/commission/listmember');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
