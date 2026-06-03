<?php
    $min_qty        = get_option('cfg_stockist_minimal_order_qty');
    $min_qty        = $min_qty ? $min_qty : 0;
    $min_nominal    = get_option('cfg_stockist_minimal_order_nominal');
    $min_nominal    = $min_nominal ? $min_nominal : 0;
?>


<form role="form" method="post" action="<?php echo base_url('setting/updatestockistorder'); ?>" id="form-setting-stockist-order" class="form-horizontal">
    <div class="card-body wrapper-setting-stockist-order">

        <!-- Minimmal Qty -->
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">Minimmal Qty <?php echo lang('product'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control numbermask" name="minimal_qty" id="minimal_qty" value="<?php echo $min_qty; ?>" >
            </div>
        </div>

        <!-- Bill Owner -->
        <div class="form-group row mb-0">
            <label class="col-md-3 col-form-label form-control-label">Minimal <?php echo lang('nominal'); ?> <span class="required">*</span></label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo config_item('currency'); ?></span>
                    </div>
                    <input type="text" class="form-control numbercurrency" name="minimal_nominal" id="minimal_nominal" value="<?php echo $min_nominal; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer my-0">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <button type="submit" class="btn btn-info my-0"><?php echo lang('save') . ' ' . lang('menu_setting'); ?></button>
            </div>
        </div>
    </div>
</form>