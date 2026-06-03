<?php
    $wanotif_active     = get_option('wanotif_active');
    $wanotif_active     = $wanotif_active ? $wanotif_active : 0;
?>


<form role="form" class="form-horizontal">
    <div class="card-body wrapper-setting-wanotif">
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">Token / API Key <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control wanotif" name="field[wanotif_token]" id="field_wanotif_token" placeholder="Token / API Key" value="<?php echo get_option('wanotif_token'); ?>" />
            </div>
        </div>
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label form-control-label">Status <span class="required">*</span></label>
            <div class="col-md-9">
                <select class="form-control wanotif" name="field[wanotif_active]" id="field_wanotif_active">
                    <option value="1" <?php echo ($wanotif_active ? 'selected=""' : ''); ?>>Aktif</option>
                    <option value="0" <?php echo ($wanotif_active == 0) ? 'selected=""' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer my-0">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <button 
                    type="button" 
                    class="btn btn-info general-setting-each" 
                    data-type="wanotif" 
                    data-id="be_dashboard_member" 
                    data-wraper="text_dashboard_wraper" 
                    data-url="<?php echo base_url('setting/updateallsetting'); ?>">
                    <?php echo lang('save') . ' ' . lang('menu_setting'); ?>
                </button>
            </div>
        </div>
    </div>
</form>