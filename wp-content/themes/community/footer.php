<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-links">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                    'fallback_cb'    => 'software_syndicate_footer_fallback_menu',
                ));
                ?>
            </div>
            
            <div class="social-icons">
                <a href="#" aria-label="Facebook">
                    <?php include get_template_directory() . '/icons/facebook.php'; ?>
                </a>
                <a href="#" aria-label="Twitter">
                    <?php include get_template_directory() . '/icons/twitter.php'; ?>
                </a>
                <a href="#" aria-label="Instagram">
                    <?php include get_template_directory() . '/icons/instagram.php'; ?>
                </a>
            </div>
            
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> The Software Syndicate</p>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

<?php
// Footer fallback menu function
function software_syndicate_footer_fallback_menu() {
    echo '<a href="' . esc_url(home_url('/contact')) . '">Contact</a>';
    echo '<a href="' . esc_url(home_url('/privacy')) . '">Privacybeleid</a>';
    echo '<a href="' . esc_url(home_url('/terms')) . '">Voorwaarden</a>';
}
?>
