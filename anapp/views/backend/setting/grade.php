<div class="header bg-gradient-info pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_setting_grade'); ?></li>
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
                <div class="card-body wrapper-setting-grade">
                    <div class="accordion" id="accordionGeneralGrade">
                        <div class="card mb-2">
                            <div class="card-header py-3 bg-gradient-info" id="headUpdateGrade" data-toggle="collapse" data-target="#updateGrade" aria-expanded="false" aria-controls="updateGrade">
                                <h5 class="text-white mb-0">Kenaikan Peringkat</h5>
                            </div>
                            <div id="updateGrade" class="collapse show" aria-labelledby="headUpdateGrade" data-parent="#accordionGeneralGrade">
                                <?php $this->load->view(VIEW_BACK . 'setting/formgrade/upgrade'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>