<?php
/**
 * Template Name: Checkout
 */

get_header();

if (!session_id()) {
    session_start();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name'])) {
    // Process order (mockup - in real app would save to database)
    
    // Clear cart
    unset($_SESSION['cart']);
    
    // Set success message
    $_SESSION['checkout_success'] = true;
    
    // Redirect to cart page
    wp_redirect(home_url('/cart/'));
    exit;
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total = 0;

if (!empty($cart_items)) {
    foreach ($cart_items as $cart_item_key => $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = get_post_meta($product_id, '_product_price', true);
        $total += ($price * $quantity);
    }
}
?>

<main class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Afrekenen</h1>
            <p>Vul je gegevens in om je bestelling af te ronden.</p>
        </div>

        <?php if (!empty($cart_items)) : ?>
            <div class="checkout-container">
                <div class="checkout-left">
                    <div class="checkout-form-section">
                        <h2>Verzendinformatie</h2>
                        <form id="checkout-form" action="" method="post">
                            <div class="form-group">
                                <label for="first_name">Voornaam *</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Achternaam *</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Adres *</label>
                                <input type="text" id="address" name="address" placeholder="Straatnaam en huisnummer" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">Stad *</label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="postcode">Postcode *</label>
                                    <input type="text" id="postcode" name="postcode" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mailadres *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="notes">Bestelnotities (optioneel)</label>
                                <textarea id="notes" name="notes" rows="4"></textarea>
                            </div>
                            
                            <div class="shipping-methods">
                                <h3>Verzendmethode</h3>
                                <div class="shipping-option">
                                    <input type="radio" id="standard_shipping" name="shipping_method" value="standard" checked>
                                    <label for="standard_shipping">Standaard verzending (3-5 werkdagen) - €5.00</label>
                                </div>
                                <div class="shipping-option">
                                    <input type="radio" id="express_shipping" name="shipping_method" value="express">
                                    <label for="express_shipping">Express verzending (1-2 werkdagen) - €10.00</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="checkout-right">
                    <div class="payment-section">
                        <h2>Betalingsinformatie</h2>
                        <div class="form-group">
                            <label for="card_number">Kaartnummer *</label>
                            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry">Vervaldatum *</label>
                                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                            </div>
                            <div class="form-group">
                                <label for="card_cvc">CVC *</label>
                                <input type="text" id="card_cvc" name="card_cvc" placeholder="123" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_name">Naam op kaart *</label>
                            <input type="text" id="card_name" name="card_name" placeholder="J. Naam" required>
                        </div>
                    </div>

                    <div class="order-summary">
                        <h2>Besteloverzicht</h2>
                        <div class="order-items">
                            <?php foreach ($cart_items as $cart_item_key => $item) : 
                                $product_id = $item['product_id'];
                                $quantity = $item['quantity'];
                                $size = $item['size'];
                                $color = $item['color'];
                                $product = get_post($product_id);
                                $price = get_post_meta($product_id, '_product_price', true);
                                ?>
                                <div class="order-item">
                                    <div class="order-item-info">
                                        <h4><?php echo esc_html($product->post_title); ?></h4>
                                        <p class="order-item-quantity">Aantal: <?php echo $quantity; ?></p>
                                        <?php if ($size || $color) : ?>
                                            <p class="order-item-variations">
                                                <?php 
                                                $meta_text = array();
                                                if ($size) $meta_text[] = 'Maat ' . esc_html($size);
                                                if ($color) $meta_text[] = 'Kleur ' . esc_html($color);
                                                echo implode(', ', $meta_text);
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="order-item-price">
                                        €<?php echo number_format($price * $quantity, 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotaal</span>
                                <span>€<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Verzending</span>
                                <span id="shipping-cost">€5.00</span>
                            </div>
                            <div class="total-row final-total">
                                <span>Totaal</span>
                                <span id="final-total">€<?php echo number_format($total + 5, 2); ?></span>
                            </div>
                        </div>
                        <button type="submit" form="checkout-form" class="place-order-btn">Bestelling plaatsen (Mockup)</button>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="empty-checkout">
                <p>Je kunt niet afrekenen met een lege winkelwagen.</p>
                <a href="<?php echo home_url('/shop/'); ?>" class="button primary">Terug naar de shop</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const shippingCostSpan = document.getElementById('shipping-cost');
    const finalTotalSpan = document.getElementById('final-total');
    const subtotal = <?php echo $total; ?>;
    
    function updateTotals() {
        let shippingCost = 5;
        if (document.getElementById('express_shipping').checked) {
            shippingCost = 10;
        }
        
        const finalTotal = subtotal + shippingCost;
        shippingCostSpan.textContent = '€' + shippingCost.toFixed(2);
        finalTotalSpan.textContent = '€' + finalTotal.toFixed(2);
    }
    
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', updateTotals);
    });
    
    // Initial update
    updateTotals();
});
</script>

<?php get_footer(); ?>
