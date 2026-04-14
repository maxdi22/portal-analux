<?php
global $product;
?>

<div class="product-image">
<?php echo woocommerce_get_product_thumbnail('full');?>
</div>
<div class="product-contetn">
<?php
echo '<h6 class="product-title">' . get_the_title() . '</h6>';
    
// Output product price
echo '<span class="product-price">' . $product->get_price_html() . '</span>';
?>
</div>