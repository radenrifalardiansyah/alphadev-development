<?php 
    $data_product   = isset($data_product) ? $data_product : false; 
    $discount_type  = config_item('discount_type');
?>

<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_product') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12">
            <div class="row justify-content-center">
                <div class="col-lg-8 card-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-1"><?php echo $form_title; ?> </h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="<?php echo base_url('productmanage/historystockin'); ?>" class="btn btn-sm btn-danger"><span class="fa fa-history"></span> Kembali</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $form_action = base_url('productmanage/saveproductstock');
                            if ( $form_page == 'edit' ) { 
                                $form_action .= '/'. an_encrypt($data_product->id);
                            }
                        ?>
                        <form role="form" method="post" action="<?php echo $form_action; ?>" id="form-product-stock" class="form-horizontal">
                            <div class="card-body wrapper-form-product-stock">
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="product"><?php echo lang('product'); ?> <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="product" id="product" data-toggle="select2">
                                            <option value="" disabled="" selected="">-- <?php echo lang('select').' '.lang('product'); ?> --</option>
                                            <?php
                                                if ( $get_products = an_products() ) {
                                                    foreach ($get_products as $key => $row) {
                                                        $selected = '';
                                                        if ( $data_product ) {
                                                            if ( $data_product->product_id == $row->id ) {
                                                                $selected = 'selected=""';
                                                            }
                                                        }
                                                        echo '<option value="'. $row->id .'" '.$selected.'>'. ucwords($row->name) .'</option>';
                                                    }   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="qty">Qty <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="qty" name="qty" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->qty : '0'); ?>">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="price"><?php echo lang('price') ?> <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="price" name="price" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->price : '0'); ?>">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="total">Total <?php echo lang('price') ?> <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="total" name="total" class="form-control numbercurrency" placeholder="0" value="<?php echo ( $data_product ? $data_product->total : '0'); ?>">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="supplier">Supplier <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" id="supplier" name="supplier" class="form-control text-capitalize" value="<?php echo ( $data_product ? $data_product->supplier_name : ''); ?>">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-md-3 col-form-label form-control-label" for="description">Deskripsi <span class="required">*</span></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control text-capitalize" id="description" name="description"><?php echo ( $data_product ? $data_product->description : ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary bg-gradient-default my-2"><?php echo lang('save'); ?> Data Stok Produk</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>