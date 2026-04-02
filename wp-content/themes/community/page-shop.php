<?php
/**
 * Template Name: Shop
 */

get_header();

// Get customizer settings
$shop_title = get_theme_mod('shop_title', 'Shop');
$shop_subtitle = get_theme_mod('shop_subtitle', 'Ontdek onze merchandise en community producten.');

?>

<main class="shop-page">
    <section class="shop-hero">
        <div class="container">
            <h1><?php echo esc_html($shop_title); ?></h1>
            <p><?php echo esc_html($shop_subtitle); ?></p>
        </div>
    </section>

    <section class="shop-products">
        <div class="container">
            <div class="product-grid">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 12,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $products_query = new WP_Query($args);

                if ($products_query->have_posts()) :
                    while ($products_query->have_posts()) : $products_query->the_post();
                        $price = get_post_meta(get_the_ID(), '_product_price', true);
                        $stock_status = get_post_meta(get_the_ID(), '_product_stock_status', true);
                        ?>
                        <div class="product-card">
                            <a href="<?php the_permalink(); ?>" class="product-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <div class="placeholder-image">Geen afbeelding</div>
                                <?php endif; ?>
                            </a>
                            <div class="product-info">
                                <h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="product-price">€<?php echo esc_html($price); ?></div>
                                <div class="product-actions">
                                    <?php if ($stock_status !== 'outofstock') : ?>
                                        <a href="<?php the_permalink(); ?>" class="view-product-btn">Bekijk product</a>
                                    <?php else : ?>
                                        <span class="out-of-stock">Uitverkocht</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>Geen producten gevonden.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
