<?php
    $flip_active    = get_option('flip_active');
    $flip_active    = $flip_active ? $flip_active : 0;
?>

<form role="form" class="form-horizontal">
    <div class="card-body wrapper-setting-flip">
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">Flip Serve </label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <input type="text" class="form-control" id="input-copy-to-clipboard" value="<?php echo base_url('flip/flip_serve'); ?>" readonly="readonly" />
                    <span class="input-group-append">
                        <button class="btn bg-blue text-white copy-to-clipboard" type="button"><i class="ni ni-single-copy-04"></i> Copy Text</button>
                    </span>
                </div>
                <p class="text-muted mt-1 mb-0" style="font-size: 0.7rem">
                    <b>Link Callback Flip ke Sistem <?php echo get_option('company_name'); ?></b>
                </p>
            </div>
        </div>
        <hr class="my-3">
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">Token <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control flip" name="field[flip_token]" id="field_flip_token" placeholder="Token Flip" value="<?php echo get_option('flip_token'); ?>" />
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">Secret Key <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control flip" name="field[flip_secret]" id="field_flip_secret" placeholder="Secret Key" value="<?php echo get_option('flip_secret'); ?>" />
            </div>
        </div>
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label form-control-label">Status <span class="required">*</span></label>
            <div class="col-md-9">
                <select class="form-control flip" name="field[flip_active]" id="field_flip_active">
                    <option value="1" <?php echo ($flip_active ? 'selected=""' : ''); ?>>Aktif</option>
                    <option value="0" <?php echo ($flip_active == 0) ? 'selected=""' : ''; ?>>Tidak Aktif</option>
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
                    data-type="flip" 
                    data-id="be_dashboard_member" 
                    data-wraper="text_dashboard_wraper" 
                    data-url="<?php echo base_url('setting/updateallsetting'); ?>">
                    <?php echo lang('save') . ' ' . lang('menu_setting'); ?>
                </button>
            </div>
        </div>
    </div>
</form>