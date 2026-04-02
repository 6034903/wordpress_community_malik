<?php
/**
 * Import All Pages Script - WordPress Admin Version
 * Access via: WordPress Admin > Appearance > Import All Pages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu item
function import_pages_menu() {
    add_theme_page('Import All Pages', 'Import All Pages', 'manage_options', 'import-all-pages', 'import_pages_page');
}
add_action('admin_menu', 'import_pages_menu');

// Import pages function
function import_pages_page() {
    ?>
    <div class="wrap">
        <h1>Import All Theme Pages</h1>
        <p>This will import or update all custom pages for The Software Syndicate theme.</p>
        
        <?php
        if (isset($_POST['import_pages'])) {
            echo "<div id='message' class='notice notice-success'><h3>Import Results:</h3>";
            
            // Define pages to import
            $pages_to_import = [
                ['title' => 'Shop', 'slug' => 'shop', 'template' => 'page-shop.php'],
                ['title' => 'Cart', 'slug' => 'cart', 'template' => 'page-cart.php'],
                ['title' => 'Checkout', 'slug' => 'checkout', 'template' => 'page-checkout.php'],
                ['title' => 'Contact', 'slug' => 'contact', 'template' => 'page-contact.php'],
                ['title' => 'Events & Hackathons', 'slug' => 'events-hackathon', 'template' => 'page-events-hackathon.php'],
                ['title' => 'Join Community', 'slug' => 'join-community', 'template' => 'page-join-community.php'],
                ['title' => 'Projecten', 'slug' => 'projecten', 'template' => 'page-projecten.php'],
                ['title' => 'Team', 'slug' => 'team', 'template' => 'page-team.php'],
                ['title' => 'Tutorials', 'slug' => 'tutorials', 'template' => 'page-tutorials.php'],
                ['title' => 'Over Ons', 'slug' => 'over-ons', 'template' => 'page-over-ons.php'],
                ['title' => 'Check Status', 'slug' => 'check-status', 'template' => 'page-check-status.php']
            ];
            
            $created = 0;
            $updated = 0;
            
            foreach ($pages_to_import as $page_data) {
                $existing_page = get_page_by_path($page_data['slug'], OBJECT, 'page');
                
                $page_args = [
                    'post_title'    => $page_data['title'],
                    'post_name'     => $page_data['slug'],
                    'post_content'  => '<!-- This page uses a custom template -->',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_template' => $page_data['template']
                ];
                
                if ($existing_page) {
                    $page_args['ID'] = $existing_page->ID;
                    wp_update_post($page_args);
                    echo "<p>✅ Updated: {$page_data['title']}</p>";
                    $updated++;
                } else {
                    wp_insert_post($page_args);
                    echo "<p>✅ Created: {$page_data['title']}</p>";
                    $created++;
                }
            }
            
            echo "<h4>Summary: {$created} created, {$updated} updated</h4>";
            echo "</div>";
        }
        ?>
        
        <form method="post">
            <?php wp_nonce_field('import_pages_nonce'); ?>
            <p>
                <input type="submit" name="import_pages" class="button button-primary" value="Import All Pages">
            </p>
        </form>
        
        <h3>Pages that will be imported:</h3>
        <ul>
            <li>Shop (page-shop.php)</li>
            <li>Cart (page-cart.php)</li>
            <li>Checkout (page-checkout.php)</li>
            <li>Contact (page-contact.php)</li>
            <li>Events & Hackathons (page-events-hackathon.php)</li>
            <li>Join Community (page-join-community.php)</li>
            <li>Projecten (page-projecten.php)</li>
            <li>Team (page-team.php)</li>
            <li>Tutorials (page-tutorials.php)</li>
            <li>Over Ons (page-over-ons.php)</li>
            <li>Check Status (page-check-status.php)</li>
        </ul>
    </div>
    <?php
}
?>
