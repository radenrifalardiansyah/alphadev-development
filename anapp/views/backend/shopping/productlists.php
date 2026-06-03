<?php 
    $cart_contents  = $this->cart->contents();
    $shopping_lock  = config_item('shopping_lock');
    $currency       = config_item('currency');
    $type_product   = '';
?>

<?php if ( $data ) : ?>
    <?php foreach ($data as $row) : ?>
        <?php
            $product_id     = an_encrypt($row->id);
            $img_src        = an_product_image($row->image, false); 
            $price          = ( $type_member >= 1 ) ? $row->price : $row->price_member;

            $in_cart        = false;
            if ( $cart_contents ) {
                foreach ($cart_contents as $item) {
                    $cart_product_id = isset($item['id']) ? $item['id'] : 'none';
                    if ( $cart_product_id == $row->id ) {
                        $in_cart = true;
                    }
                }
            }
        ?>

        <div class="col-lg-3 col-md-4 col-sm-6 col-6">
            <div class="product-grid4 mb-5 btn-product-detail" data-url="<?php echo base_url('shopping/getproductdetailshopping/'.$product_id); ?>" data-product="<?php echo $row->name; ?>">
                <div class="product-image4">
                    <a href="#">
                        <img class="pic-1" src="<?php echo $img_src; ?>">
                        <img class="pic-2" src="<?php echo $img_src; ?>">
                    </a>
                </div>
                <div class="product-content">
                    <h3 class="title"><a href="#"><?php echo $row->name; ?></a></h3>
                    <div class="text-center">
                        <div class="price">
                            <?php 
                                $view_price = an_accounting($price, $currency);
                                echo $view_price;
                            ?>
                            <!-- Diskon <span>$16.00</span> -->
                        </div>
                        <?php if ( ! $shopping_lock ) { ?>
                            <?php if ( $in_cart ) { ?>
                                <a class="add-to-cart text-uppercase" href="<?php echo base_url('shopping/cart'); ?>" data-type="cart">GO TO CART</a>
                            <?php } else { ?>
                                <a class="add-to-cart text-uppercase" href="<?php echo base_url('shopping/addToCart'); ?>" data-type="addcart" data-cart="<?php echo $product_id; ?>">
                                    <span class="shopping-cart-loading mr-2" style="display:none"><img src="<?php echo BE_IMG_PATH; ?>loading-spinner-blue.gif"></span>
                                    ADD TO CART
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-lg-12 text-center">
        <div class="px-2 py-4"><h3 class="heading mt-5">No More Result Found</h3></div>
    </div>
<?php endif; ?>

