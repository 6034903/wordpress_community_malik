<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="container">
        <div class="header-content">
            <div class="site-branding">
                <?php
                $site_logo = get_theme_mod('site_logo');
                if (!empty($site_logo)) {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                        <img src="<?php echo esc_url($site_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="custom-logo">
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
            
            <nav class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'software_syndicate_fallback_menu',
                ));
                ?>
            </nav>
            
            <div class="header-actions">
                <a href="<?php echo esc_url(home_url('/cart/')); ?>" class="cart-icon">
                    <?php include get_template_directory() . '/icons/cart.php'; ?>
                </a>
            </div>
        </div>
    </div>
</header>

<?php
// Fallback menu function
function software_syndicate_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/projects')) . '">Projecten</a></li>';
    echo '<li><a href="' . esc_url(home_url('/tutorials')) . '">Tutorials</a></li>';
    echo '<li><a href="' . esc_url(home_url('/store')) . '">Store</a></li>';
    echo '<li><a href="' . esc_url(home_url('/community')) . '">Community</a></li>';
    echo '</ul>';
}
?>
