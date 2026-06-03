<?php 
    $type_content =  isset($type_content) ? $type_content : ''; 
?>

<div class="header bg-secondary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-light">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo $menu_parent; ?></a></li>
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
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $menu_title; ?> </h3>
                        </div>
                    </div>
                </div>
                <?php if ( $type_content != 'shophistory' ) { echo '<div class="table-container">'; } ?>

                    <?php 
                        if ( $is_admin ) {
                            if ( isset($type_content) && $type_content == 'mitra' ) {
                                $this->load->view(VIEW_BACK . 'shopping/shoporderlist/listadminstockist');
                            } else {;
                                $this->load->view(VIEW_BACK . 'shopping/shoporderlist/listadmin');
                            }
                        } else {
                            if ( isset($type_content) && $type_content == 'shophistory' ) {
                                $this->load->view(VIEW_BACK . 'shopping/shoporderlist/listmember');
                            } else {
                                $this->load->view(VIEW_BACK . 'shopping/shoporderlist/listsalesstockist');
                            }
                        }
                    ?>
                    
                <?php if ( $type_content != 'shophistory' ) { echo '</div'; } ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail PO -->
<div class="modal fade" id="modal-shop-order-detail" tabindex="-1" role="dialog" aria-labelledby="modal-shop-order-detail" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pt-3 pb-1">
                <h5 class="modal-title text-primary"><i class="ni ni-book-bookmark mr-1"></i> <span class="title-invoice font-weight-bold"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4 py-3" style="background-color: #f8f9fe">
                <div class="info-shop-order-detail"></div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-outline-warning" data-dismiss="modal"><?php echo lang('back'); ?></button>
            </div>
        </div>
    </div>
</div>
