<?php
/**
 * Template Name: Shopping Cart
 */

get_header();

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Check for checkout success and show popup
$show_success_popup = false;
if (isset($_SESSION['checkout_success']) && $_SESSION['checkout_success']) {
    $show_success_popup = true;
    unset($_SESSION['checkout_success']); // Clear after showing
}

// Handle cart actions (Add, Remove, Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $size = isset($_POST['product_size']) ? sanitize_text_field($_POST['product_size']) : '';
        $color = isset($_POST['product_color']) ? sanitize_text_field($_POST['product_color']) : '';
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Create a unique key for the cart item based on product ID and variations
        $cart_item_key = $product_id;
        if ($size || $color) {
            $cart_item_key .= '_' . md5($size . $color);
        }
        
        if (isset($_SESSION['cart'][$cart_item_key])) {
            $_SESSION['cart'][$cart_item_key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cart_item_key] = array(
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'size'       => $size,
                'color'      => $color
            );
        }
    }
    
    if (isset($_POST['remove_from_cart'])) {
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        if (isset($_SESSION['cart'][$cart_item_key])) {
            unset($_SESSION['cart'][$cart_item_key]);
        }
        // Redirect to same page to refresh cart
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
    
    // Handle quantity changes from input fields
    if (isset($_POST['cart_qty'])) {
        foreach ($_POST['cart_qty'] as $cart_item_key => $qty) {
            $qty = intval($qty);
            if ($qty > 0) {
                if (isset($_SESSION['cart'][$cart_item_key])) {
                    $_SESSION['cart'][$cart_item_key]['quantity'] = $qty;
                }
            } else {
                unset($_SESSION['cart'][$cart_item_key]);
            }
        }
    }
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>

<main class="cart-page">
    <div class="container">
        <div class="cart-header">
            <h1>Jouw Winkelmandje</h1>
            <p>Een overzicht van de producten die je hebt geselecteerd. Controleer je bestelling voordat je afrekent.</p>
        </div>

        <?php if (!empty($cart_items)) : ?>
            <div class="cart-content">
                <div class="cart-items-list">
                    <?php
                    $total = 0;
                    foreach ($cart_items as $cart_item_key => $item) :
                        $product_id = $item['product_id'];
                        $qty = $item['quantity'];
                        $size = $item['size'];
                        $color = $item['color'];
                        
                        $product = get_post($product_id);
                        $price = get_post_meta($product_id, '_product_price', true);
                        $subtotal = $price * $qty;
                        $total += $subtotal;
                        ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <?php if (has_post_thumbnail($product_id)) : ?>
                                    <?php echo get_the_post_thumbnail($product_id, 'medium'); ?>
                                <?php else : ?>
                                    <div class="placeholder">
                                        <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                                            <line x1="0" y1="0" x2="100" y2="100" stroke="#374151" stroke-width="1" />
                                            <line x1="100" y1="0" x2="0" y2="100" stroke="#374151" stroke-width="1" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cart-item-details">
                                <h3 class="cart-item-title"><?php echo esc_html($product->post_title); ?></h3>
                                <?php if ($size || $color) : ?>
                                    <p class="cart-item-meta">
                                        <?php 
                                        $meta_text = array();
                                        if ($size) $meta_text[] = 'Maat ' . esc_html($size);
                                        if ($color) $meta_text[] = 'Kleur: ' . esc_html($color);
                                        echo implode(', ', $meta_text);
                                        ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="cart-item-controls">
                                <form method="post" action="" class="qty-form">
                                    <div class="qty-selector">
                                        <button type="button" class="qty-btn" onclick="var input = this.nextElementSibling; if(input.value > 1) { input.value--; input.form.submit(); }">-</button>
                                        <input type="number" name="cart_qty[<?php echo $cart_item_key; ?>]" value="<?php echo $qty; ?>" min="1" class="qty-input" onchange="this.form.submit()">
                                        <button type="button" class="qty-btn" onclick="var input = this.previousElementSibling; input.value++; input.form.submit();">+</button>
                                    </div>
                                </form>
                                
                                <div class="cart-item-price">
                                    €<?php echo number_format($price, 2, ',', '.'); ?>
                                </div>

                                <form method="post" action="" class="remove-form">
                                    <input type="hidden" name="cart_item_key" value="<?php echo $cart_item_key; ?>">
                                    <button type="submit" name="remove_from_cart" value="1" class="remove-btn">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-footer">
                    <div class="cart-subtotal">
                        <span class="subtotal-label">Subtotaal:</span>
                        <span class="subtotal-value">€<?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                    <p class="cart-tax-info">verzendkosten worden berekend bij het afrekenen.</p>
                    
                    <div class="cart-actions">
                        <a href="<?php echo home_url('/checkout/'); ?>" class="checkout-btn">verder naar afrekenen</a>
                    </div>
                </div>
            </div>
            <div class="continue-shopping">
                <a href="<?php echo home_url('/shop/'); ?>">verder winkelen</a>
            </div>
        <?php else : ?>
            <div class="empty-cart">
                <p>Je winkelwagen is leeg.</p>
                <a href="<?php echo home_url('/shop/'); ?>" class="button primary">Terug naar de shop</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php if ($show_success_popup): ?>
<div id="success-popup" class="success-popup">
    <div class="popup-content">
        <div class="popup-icon">✓</div>
        <h3>Betaling geaccepteerd</h3>
        <p>Je bestelling is succesvol geplaatst!</p>
        <button onclick="closePopup()" class="popup-btn">OK</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show popup immediately
    const popup = document.getElementById('success-popup');
    popup.style.display = 'flex';
    
    // Auto close after 3 seconds
    setTimeout(function() {
        closePopup();
    }, 10000);
});

function closePopup() {
    const popup = document.getElementById('success-popup');
    popup.style.display = 'none';
}
</script>
<?php endif; ?>

<?php get_footer(); ?>
