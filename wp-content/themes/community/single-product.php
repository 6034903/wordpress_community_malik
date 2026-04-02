<?php
get_header();

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Handle add to cart action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
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
    
    // Redirect to cart page after adding
    wp_redirect(home_url('/cart/'));
    exit;
}

while (have_posts()) : the_post();
    $price = get_post_meta(get_the_ID(), '_product_price', true);
    $sku = get_post_meta(get_the_ID(), '_product_sku', true);
    $stock_status = get_post_meta(get_the_ID(), '_product_stock_status', true);
    $sizes = get_post_meta(get_the_ID(), '_product_sizes', true);
    $colors = get_post_meta(get_the_ID(), '_product_colors', true);
    $description = get_post_meta(get_the_ID(), '_product_description', true);

    $sizes_array = !empty($sizes) ? array_filter(array_map('trim', explode(',', $sizes))) : array();
    $colors_array = !empty($colors) ? array_filter(array_map('trim', explode(',', $colors))) : array();
    ?>

    <main class="single-product-page">
        <div class="container">
            <div class="product-container">
                <div class="product-gallery">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="main-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-details">
                    <nav class="breadcrumb">
                        <a href="<?php echo home_url('/shop/'); ?>">Shop</a> &raquo; <?php the_title(); ?>
                    </nav>

                    <h1 class="product-title"><?php the_title(); ?></h1>
                    
                    <div class="product-meta-row">
                        <span class="product-price">€<?php echo esc_html($price); ?></span>
                        <?php if ($sku) : ?>
                            <span class="product-sku">SKU: <?php echo esc_html($sku); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="product-stock <?php echo esc_attr($stock_status); ?>">
                        <?php echo $stock_status === 'instock' ? 'Op voorraad' : 'Niet op voorraad'; ?>
                    </div>

                    <div class="product-description">
                        <?php echo apply_filters('the_content', $description); ?>
                    </div>

                    <?php if ($stock_status !== 'outofstock') : ?>
                        <form class="add-to-cart-form" method="post">
                            <input type="hidden" name="product_id" value="<?php the_ID(); ?>">
                            
                            <div class="variation-fields-wrapper">
                                <?php if (!empty($sizes_array)) : ?>
                                    <div class="variation-field">
                                        <label>Maat:</label>
                                        <div class="visual-selector size-selector">
                                            <?php foreach ($sizes_array as $size) : ?>
                                                <div class="selector-option" data-value="<?php echo esc_attr($size); ?>">
                                                    <?php echo esc_html($size); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="product_size" id="selected_size" required>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($colors_array)) : ?>
                                    <div class="variation-field">
                                        <label>Kleur:</label>
                                        <div class="visual-selector color-selector">
                                            <?php foreach ($colors_array as $color) : ?>
                                                <div class="selector-option" data-value="<?php echo esc_attr($color); ?>">
                                                    <?php echo esc_html($color); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <input type="hidden" name="product_color" id="selected_color" required>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('.add-to-cart-form');
                                const submitBtn = document.querySelector('.add-to-cart-btn');
                                const sizeOptions = document.querySelectorAll('.size-selector .selector-option');
                                const colorOptions = document.querySelectorAll('.color-selector .selector-option');
                                const selectedSizeInput = document.getElementById('selected_size');
                                const selectedColorInput = document.getElementById('selected_color');
                                
                                // Function to check if all required variations are selected
                                function validateVariations() {
                                    let isValid = true;
                                    
                                    // Check if size is required and selected
                                    if (sizeOptions.length > 0 && !selectedSizeInput.value) {
                                        isValid = false;
                                    }
                                    
                                    // Check if color is required and selected
                                    if (colorOptions.length > 0 && !selectedColorInput.value) {
                                        isValid = false;
                                    }
                                    
                                    // Enable/disable submit button
                                    submitBtn.disabled = !isValid;
                                    
                                    if (!isValid) {
                                        submitBtn.style.opacity = '0.5';
                                        submitBtn.style.cursor = 'not-allowed';
                                    } else {
                                        submitBtn.style.opacity = '1';
                                        submitBtn.style.cursor = 'pointer';
                                    }
                                }
                                
                                // Initial validation
                                validateVariations();
                                
                                document.querySelectorAll('.visual-selector .selector-option').forEach(option => {
                                    option.addEventListener('click', function() {
                                        const parent = this.parentElement;
                                        const input = parent.nextElementSibling;
                                        
                                        // Remove active class from siblings
                                        parent.querySelectorAll('.selector-option').forEach(opt => opt.classList.remove('active'));
                                        
                                        // Add active class to clicked option
                                        this.classList.add('active');
                                        
                                        // Update hidden input
                                        input.value = this.getAttribute('data-value');
                                        
                                        // Validate after selection
                                        validateVariations();
                                    });
                                });
                            });
                            </script>

                            <div class="quantity-input">
                                <label for="quantity">Aantal:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1">
                            </div>
                            <button type="submit" name="add_to_cart" class="add-to-cart-btn">In winkelwagen</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

<?php
endwhile;

get_footer();
?>
