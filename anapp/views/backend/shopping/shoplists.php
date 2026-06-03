<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-8">
                    <nav aria-label="breadcrumb" class="ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_shopping'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $menu_title; ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-4 d-sm-none text-right">
                    <a class="btn-tooltip text-white" title="<?php echo lang('search') .' '. lang('product'); ?>" href="#" data-action="search-show" data-target="#navbar-search-main"><i class="fas fa-search"></i></a>
                </div>
                <div class="col-lg-6">
                    <form class="navbar-search navbar-search-light form-inline" id="form-search-shopping-product" data-url="<?php echo $search_url; ?>" style="float: right;">
                        <div class="form-group mb-0">
                            <div class="input-group input-group-alternative input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control search_product" id="search_shopping_product" placeholder="<?php echo lang('search') .' '. lang('product'); ?> ..." value="<?php echo trim($s_product); ?>" data-product="<?php echo trim($s_product); ?>">
                            </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </form>
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
                            <h3 class="mb-0">
                                <?php echo $menu_title; ?> 
                            </h3>
                        </div>
                        <div class="col text-right">
                            <?php if ( $type == 'shop' ) { ?>
                                <div class="form-group mb-0">
                                    <div class="input-group">
                                        <select class="form-control search_category" name="product_category" id="product_category">
                                            <option value="" selected="">-- Semua Kategori --</option>
                                            <?php
                                                if ( $product_category = an_product_category(0, true) ) {
                                                    foreach ($product_category as $key => $row) {
                                                        $selected   = '';
                                                        if ( $s_category == $row->slug ) {
                                                            $selected = 'selected=""';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        echo '<option value="'. $row->slug .'" '.$selected.'>'. ucwords($row->name) .'</option>';
                                                    }   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" class="d-none search_category">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="product-shop-list" data-url="<?php echo base_url('shopping/productshoppinglistdata/'.$type) ?>"></div>                    
                    <div class="row shopping-see-more" style="display: none;">
                        <div class="col-lg-12 text-center mb-4">
                            <button type="button" class="btn btn-neutral px-5" id="btn-shopping-see-more">
                                <span class="shopping-see-more-loading mr-2" style="display: none;"><img src="<?php echo BE_IMG_PATH; ?>loading-spinner-blue.gif"></span>
                                See More
                            </button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Product -->
<div class="modal fade" id="modal-shopping-product-detail" tabindex="-1" role="dialog" aria-labelledby="modal-shopping-product-detail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pt-3 pb-1">
                <h5 class="modal-title text-primary"><i class="ni ni-box-2 mr-1"></i> <span class="title-product font-weight-bold"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4 py-3" style="background-color: #f8f9fe">
                <div class="info-shopping-product-detail"></div>
            </div>
        </div>
    </div>
</div>