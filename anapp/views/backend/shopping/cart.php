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
                    </div>
                </div>
                <?php if ( $message ) { ?>
                    <div class="px-3">
                        <div class="alert alert-warning"><?php echo $message; ?></div>
                    </div>
                <?php } ?>
                <?php if ( ($cfg_min_order_qty && $cfg_min_order_qty > 1) || $cfg_min_order_nominal ) : ?>
                    <div class="px-3">
                        <div class="alert alert-primary" role="alert">
                            <h4 class="alert-heading"><i class="fa fa-bell mr-2"></i> Informasi Minimal Pemesanan produk</h4>
                            <?php if ( $cfg_min_order_qty > 1 ) : ?>
                                <p class="mb-0">Minimal qty pembelanjaan <strong><?php echo an_accounting($cfg_min_order_qty); ?> Produk</strong>.</p>
                            <?php endif; ?>
                            <?php if ( $cfg_min_order_nominal  ) : ?>
                                <p class="mb-0">Minimal belanja sebesar <strong><?php echo an_accounting($cfg_min_order_nominal, config_item('currency')); ?></strong>.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $cart_content ) : $num = 1; ?>
                    <div class="table-container table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr role="row" class="heading">
                                    <th scope="col" style="width: 10px">#</th>
                                    <th scope="col" class="text-center"><?php echo lang('product'); ?></th>
                                    <th scope="col" class="text-center" style="min-width: 100px !important; width: 200px !important;"><?php echo lang('price'); ?></th>
                                    <th scope="col" class="text-center" style="min-width: 150px !important; width: 170px !important;">Qty</th>
                                    <th scope="col" class="text-center" style="min-width: 100px !important; width: 230px !important;">Subtotal</th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php foreach ($cart_content['data'] as $key => $row) : ?>
                                    <?php 
                                        $product_id     = an_encrypt($row['id']);
                                        $cart_price     = an_accounting($row['cart_price'], '', true);
                                        $cart_subtotal  = an_accounting($row['cart_subtotal'], '', true);
                                    ?>
                                    <tr class="cart_item">
                                        <td class="text-center">*</td>
                                        <th scope="row">
                                            <div class="media align-items-center" style="white-space: normal">
                                                <a href="#" class="avatar mr-3">
                                                    <img alt="Image placeholder" src="<?php echo $row['product_image']; ?>">
                                                </a>
                                                <div class="media-body">
                                                    <span class="name mb-0 text-sm"><?php echo $row['product_name']; ?></span>
                                                </div>
                                            </div>
                                        </th>
                                        <td class="budget">
                                            <span class="heading font-weight-bold cart-item-price">
                                                <?php echo $cart_price; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="input-group product-quantity">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-sm btn-outline-default btn-cart-minus-qty" type="button" data-step="1">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input class="form-control form-control-sm text-center numbermask cart-item-qty" 
                                                    type="text" 
                                                    data-rowid="<?php echo $row['rowid']; ?>" 
                                                    data-productid="<?php echo $product_id; ?>" 
                                                    data-num="<?php echo $num; ?>" 
                                                    data-price="<?php echo $row['cart_price']; ?>" 
                                                    data-weight="<?php echo $row['weight']; ?>" 
                                                    data-url="<?php echo base_url('shopping/updateQtyCart'); ?>" 
                                                    value="<?php echo $row['qty']; ?>" 
                                                    step="1" 
                                                    name="products[<?php echo ($product_id) ?>][qty]"
                                                    title="Qty" pattern="[0-9]*" inputmode="numeric"
                                                    style="background-color: transparent !important; border-color: #172b4d; border-left: none;" />
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-outline-default btn-cart-plus-qty" type="button" data-step="1">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="budget">
                                            <span class="heading font-weight-bold cart-item-subtotal">
                                                <?php echo $cart_subtotal; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('shopping/deleteCart/'.$row['rowid']); ?>" class="btn btn-sm btn-outline-danger btn-product-cart-delete" title="Hapus Produk">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php $num++; endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f6f9fc">
                                    <td colspan="3"></td>
                                    <th class="py-4">
                                        <span class="h2 text-warning">Total</span>
                                    </th>
                                    <th class="text-right py-4">
                                        <?php 
                                            $total_payment  = $this->cart->total();
                                            $cart_payment   = an_accounting($total_payment, '', true);
                                        ?>
                                        <span class="h2 text-warning cart-total-paymnet"><?php echo $cart_payment; ?></span>
                                    </th>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer py-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-12 text-center">
                                <a href="<?php echo base_url('shopping/emptyCart'); ?>" class="btn btn-neutral btn-cart-empty" title="Hapus Produk">
                                    <i class="fa fa-times" aria-hidden="true"></i> Empty Cart
                                </a>
                                <?php if ( $member->as_stockist == 0 ) { ?>
                                    <!--
                                    <a href="<?php echo base_url('find-agency') ?>" class="btn btn-outline-primary">
                                        <i class="ni ni-single-02" aria-hidden="true"></i> <?php echo lang('select') .' Stockist'; ?>
                                    </a> 
                                    -->
                                <?php } ?>
                                <a href="<?php echo base_url('checkout') ?>" class="btn btn-default bg-gradient-default">
                                     Checkout <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                </a> 
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 text-center pt-5 pb-7">
                                <h3 class="heading m-4">Your cart is currently empty</h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>