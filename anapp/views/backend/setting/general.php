<div class="header bg-gradient-info pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_general'); ?></li>
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
                <div class="card-body wrapper-setting-general">
                    <div class="accordion" id="accordionGeneralSetting">
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headCompanyInfo" data-toggle="collapse" data-target="#companyInfo" aria-expanded="false" aria-controls="companyInfo">
                                <h5 class="text-white mb-0">Informasi Perusahaan</h5>
                            </div>
                            <div id="companyInfo" class="collapse show" aria-labelledby="headCompanyInfo" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/company'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headCompanyBilling" data-toggle="collapse" data-target="#companyBilling" aria-expanded="false" aria-controls="companyBilling">
                                <h5 class="text-white mb-0">Informasi Bank Perusahaan</h5>
                            </div>
                            <div id="companyBilling" class="collapse" aria-labelledby="headCompanyBilling" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/companybilling'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headStockistOrder" data-toggle="collapse" data-target="#stockistOrder" aria-expanded="false" aria-controls="stockistOrder">
                                <h5 class="text-white mb-0">Minimal Stockist Order</h5>
                            </div>
                            <div id="stockistOrder" class="collapse" aria-labelledby="headStockistOrder" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/stockistorder'); ?>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headWaNotif" data-toggle="collapse" data-target="#waNotif" aria-expanded="false" aria-controls="waNotif">
                                <h5 class="text-white mb-0">WA-Notif</h5>
                            </div>
                            <div id="waNotif" class="collapse" aria-labelledby="headWaNotif" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/wanotif'); ?>
                            </div>
                        </div>
                        <!--
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headFlip" data-toggle="collapse" data-target="#flip" aria-expanded="false" aria-controls="flip">
                                <h5 class="text-white mb-0">Flip</h5>
                            </div>
                            <div id="flip" class="collapse" aria-labelledby="headFlip" data-parent="#accordionGeneralSetting">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgeneral/flip'); ?>
                            </div>
                        </div>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>