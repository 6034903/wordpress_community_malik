<?php
/**
 * The Software Syndicate Theme Functions
 */

// Include application admin interface
require_once get_template_directory() . '/application-admin.php';

// Include import pages functionality
require_once get_template_directory() . '/import-all-pages.php';

// Theme setup
function software_syndicate_setup() {
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 150,
        'width'       => 150,
        'flex-height' => false,
        'flex-width'  => false,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'software-syndicate'),
        'footer' => __('Footer Menu', 'software-syndicate'),
    ));
    
    // Add theme support for HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'software_syndicate_setup');

// Register Custom Post Type for Projects
function software_syndicate_register_project_post_type() {
    $labels = array(
        'name'                  => _x('Projects', 'Post Type General Name', 'software-syndicate'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'software-syndicate'),
        'menu_name'             => __('Projects', 'software-syndicate'),
        'name_admin_bar'        => __('Project', 'software-syndicate'),
        'archives'              => __('Project Archives', 'software-syndicate'),
        'attributes'            => __('Project Attributes', 'software-syndicate'),
        'parent_item_colon'     => __('Parent Project:', 'software-syndicate'),
        'all_items'             => __('All Projects', 'software-syndicate'),
        'add_new_item'          => __('Add New Project', 'software-syndicate'),
        'add_new'               => __('Add New', 'software-syndicate'),
        'new_item'              => __('New Project', 'software-syndicate'),
        'edit_item'             => __('Edit Project', 'software-syndicate'),
        'update_item'           => __('Update Project', 'software-syndicate'),
        'view_item'             => __('View Project', 'software-syndicate'),
        'view_items'            => __('View Projects', 'software-syndicate'),
        'search_items'          => __('Search Project', 'software-syndicate'),
        'not_found'             => __('Not found', 'software-syndicate'),
        'not_found_in_trash'    => __('Not found in Trash', 'software-syndicate'),
        'featured_image'        => __('Project Image', 'software-syndicate'),
        'set_featured_image'    => __('Set project image', 'software-syndicate'),
        'remove_featured_image' => __('Remove project image', 'software-syndicate'),
        'use_featured_image'    => __('Use as project image', 'software-syndicate'),
        'insert_into_item'      => __('Insert into project', 'software-syndicate'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'software-syndicate'),
        'items_list'            => __('Projects list', 'software-syndicate'),
        'items_list_navigation' => __('Projects list navigation', 'software-syndicate'),
        'filter_items_list'     => __('Filter projects list', 'software-syndicate'),
    );
    
    $args = array(
        'label'                 => __('Project', 'software-syndicate'),
        'description'           => __('Custom post type for projects', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'projecten-items'),
    );
    
    register_post_type('project', $args);
}
add_action('init', 'software_syndicate_register_project_post_type');

// Remove default custom fields meta box from projects
function software_syndicate_remove_project_meta_boxes() {
    remove_meta_box('postcustom', 'project', 'normal');
}
add_action('add_meta_boxes', 'software_syndicate_remove_project_meta_boxes');

// Force template assignment for projecten page
function software_syndicate_force_projecten_template($template) {
    if (is_page('projecten')) {
        $new_template = locate_template(array('page-projecten.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'software_syndicate_force_projecten_template');

// Force tutorials template
function software_syndicate_force_tutorials_template($template) {
    if (is_page('tutorials')) {
        $new_template = locate_template(array('page-tutorials.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'software_syndicate_force_tutorials_template');

// Flush rewrite rules on theme activation
function software_syndicate_flush_rewrite_rules() {
    software_syndicate_register_project_post_type();
    software_syndicate_register_tutorial_post_type();
    software_syndicate_register_event_post_type();
    software_syndicate_register_team_member_post_type();
    software_syndicate_register_applications_post_type();
    software_syndicate_register_product_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'software_syndicate_flush_rewrite_rules');

// Register Product Custom Post Type
function software_syndicate_register_product_post_type() {
    $labels = array(
        'name'                  => _x('Producten', 'Post type general name', 'software-syndicate'),
        'singular_name'         => _x('Product', 'Post type singular name', 'software-syndicate'),
        'menu_name'             => _x('Shop', 'Admin Menu text', 'software-syndicate'),
        'name_admin_bar'        => _x('Product', 'Add New on Toolbar', 'software-syndicate'),
        'add_new'               => __('Nieuw product', 'software-syndicate'),
        'add_new_item'          => __('Nieuw product toevoegen', 'software-syndicate'),
        'new_item'              => __('Nieuw product', 'software-syndicate'),
        'edit_item'             => __('Product bewerken', 'software-syndicate'),
        'view_item'             => __('Product bekijken', 'software-syndicate'),
        'all_items'             => __('Alle producten', 'software-syndicate'),
        'search_items'          => __('Producten zoeken', 'software-syndicate'),
        'parent_item_colon'     => __('Parent product:', 'software-syndicate'),
        'not_found'             => __('Geen producten gevonden.', 'software-syndicate'),
        'not_found_in_trash'    => __('Geen producten gevonden in prullenbak.', 'software-syndicate'),
    );

    $args = array(
        'label'                 => __('Product', 'software-syndicate'),
        'description'           => __('Shop producten voor onze community', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 35,
        'menu_icon'             => 'dashicons-cart',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'shop'),
    );
    
    register_post_type('product', $args);

    // Hide product categories meta box
    add_action('admin_init', function() {
        remove_meta_box('product_catdiv', 'product', 'side');
    });
}
add_action('init', 'software_syndicate_register_product_post_type');

// Register Tutorial Custom Post Type
function software_syndicate_register_tutorial_post_type() {
    $labels = array(
        'name'                  => _x('Tutorials', 'Post type general name', 'software-syndicate'),
        'singular_name'         => _x('Tutorial', 'Post type singular name', 'software-syndicate'),
        'menu_name'             => _x('Tutorials', 'Admin Menu text', 'software-syndicate'),
        'name_admin_bar'        => _x('Tutorial', 'Add New on Toolbar', 'software-syndicate'),
        'add_new'               => __('Nieuwe toevoegen', 'software-syndicate'),
        'add_new_item'          => __('Nieuwe tutorial toevoegen', 'software-syndicate'),
        'new_item'              => __('Nieuwe tutorial', 'software-syndicate'),
        'edit_item'             => __('Tutorial bewerken', 'software-syndicate'),
        'view_item'             => __('Tutorial bekijken', 'software-syndicate'),
        'all_items'             => __('Alle tutorials', 'software-syndicate'),
        'search_items'          => __('Tutorials doorzoeken', 'software-syndicate'),
        'parent_item_colon'     => __('Parent tutorial:', 'software-syndicate'),
        'not_found'             => __('Geen tutorials gevonden.', 'software-syndicate'),
        'not_found_in_trash'    => __('Geen tutorials gevonden in prullenbak.', 'software-syndicate'),
        'featured_image'        => _x('Tutorial cover image', 'Overrides the "Featured Image" phrase for this post type.', 'software-syndicate'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'software-syndicate'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'software-syndicate'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'software-syndicate'),
        'archives'              => _x('Tutorial archief', 'The post type archive label used in nav menus.', 'software-syndicate'),
        'insert_into_item'      => _x('Invoegen in tutorial', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media).', 'software-syndicate'),
        'uploaded_to_this_item' => _x('Geüpload naar deze tutorial', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'software-syndicate'),
        'filter_items_list'     => _x('Tutorial lijst filteren', 'Screen reader text for the filter links heading on the post type listing screen.', 'software-syndicate'),
        'items_list_navigation' => _x('Tutorial lijst navigatie', 'Screen reader text for the pagination heading on the post type listing screen.', 'software-syndicate'),
        'items_list'            => _x('Tutorial lijst', 'Screen reader text for the items list heading on the post type listing screen.', 'software-syndicate'),
    );

    $args = array(
        'label'                 => __('Tutorial', 'software-syndicate'),
        'description'           => __('Tutorial posts voor onze community', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'tutorials'),
    );
    
    register_post_type('tutorial', $args);
}
add_action('init', 'software_syndicate_register_tutorial_post_type');

// Register Event Custom Post Type (combined with hackathons)
function software_syndicate_register_event_post_type() {
    $labels = array(
        'name'                  => _x('Events', 'Post type general name', 'software-syndicate'),
        'singular_name'         => _x('Event', 'Post type singular name', 'software-syndicate'),
        'menu_name'             => _x('Events', 'Admin Menu text', 'software-syndicate'),
        'name_admin_bar'        => _x('Event', 'Add New on Toolbar', 'software-syndicate'),
        'add_new'               => __('Nieuw event toevoegen', 'software-syndicate'),
        'add_new_item'          => __('Nieuw event toevoegen', 'software-syndicate'),
        'new_item'              => __('Nieuw event', 'software-syndicate'),
        'edit_item'             => __('Event bewerken', 'software-syndicate'),
        'view_item'             => __('Event bekijken', 'software-syndicate'),
        'all_items'             => __('Alle events', 'software-syndicate'),
        'search_items'          => __('Events zoeken', 'software-syndicate'),
        'parent_item_colon'     => __('Parent event:', 'software-syndicate'),
        'not_found'             => __('Geen events gevonden.', 'software-syndicate'),
        'not_found_in_trash'    => __('Geen events gevonden in prullenbak.', 'software-syndicate'),
        'featured_image'        => _x('Event cover image', 'Overrides the "Featured Image" phrase for this post type.', 'software-syndicate'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'software-syndicate'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'software-syndicate'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'software-syndicate'),
        'archives'              => _x('Event archief', 'The post type archive label used in nav menus.', 'software-syndicate'),
        'insert_into_item'      => _x('Invoegen in event', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media).', 'software-syndicate'),
        'uploaded_to_this_item' => _x('Geüpload naar dit event', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'software-syndicate'),
        'filter_items_list'     => _x('Event lijst filteren', 'Screen reader text for the filter links heading on the post type listing screen.', 'software-syndicate'),
        'items_list_navigation' => _x('Event lijst navigatie', 'Screen reader text for the pagination heading on the post type listing screen.', 'software-syndicate'),
        'items_list'            => _x('Event lijst', 'Screen reader text for the items list heading on the post type listing screen.', 'software-syndicate'),
    );

    $args = array(
        'label'                 => __('Event', 'software-syndicate'),
        'description'           => __('Event posts voor onze community', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'custom-fields'), // Removed 'editor'
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 27,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'events'),
    );
    
    register_post_type('event', $args);
}
add_action('init', 'software_syndicate_register_event_post_type');

// Register Team Member Custom Post Type
function software_syndicate_register_team_member_post_type() {
    $labels = array(
        'name'                  => _x('Team Members', 'Post type general name', 'software-syndicate'),
        'singular_name'         => _x('Team Member', 'Post type singular name', 'software-syndicate'),
        'menu_name'             => _x('Team', 'Admin Menu text', 'software-syndicate'),
        'name_admin_bar'        => _x('Team Member', 'Add New on Toolbar', 'software-syndicate'),
        'add_new'               => __('Nieuw teamlid toevoegen', 'software-syndicate'),
        'add_new_item'          => __('Nieuw teamlid toevoegen', 'software-syndicate'),
        'new_item'              => __('Nieuw teamlid', 'software-syndicate'),
        'edit_item'             => __('Teamlid bewerken', 'software-syndicate'),
        'view_item'             => __('Teamlid bekijken', 'software-syndicate'),
        'all_items'             => __('Alle teamleden', 'software-syndicate'),
        'search_items'          => __('Teamleden zoeken', 'software-syndicate'),
        'parent_item_colon'     => __('Parent teamlid:', 'software-syndicate'),
        'not_found'             => __('Geen teamleden gevonden.', 'software-syndicate'),
        'not_found_in_trash'    => __('Geen teamleden gevonden in prullenbak.', 'software-syndicate'),
        'featured_image'        => _x('Teamlid photo', 'Overrides the "Featured Image" phrase for this post type.', 'software-syndicate'),
        'set_featured_image'    => _x('Set photo', 'Overrides the "Set featured image" phrase for this post type.', 'software-syndicate'),
        'remove_featured_image' => _x('Remove photo', 'Overrides the "Remove featured image" phrase for this post type.', 'software-syndicate'),
        'use_featured_image'    => _x('Use as photo', 'Overrides the "Use as featured image" phrase for this post type.', 'software-syndicate'),
        'archives'              => _x('Team archief', 'The post type archive label used in nav menus.', 'software-syndicate'),
        'insert_into_item'      => _x('Invoegen in teamlid', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media).', 'software-syndicate'),
        'uploaded_to_this_item' => _x('Geüpload naar dit teamlid', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'software-syndicate'),
        'filter_items_list'     => _x('Team lijst filteren', 'Screen reader text for the filter links heading on the post type listing screen.', 'software-syndicate'),
        'items_list_navigation' => _x('Team lijst navigatie', 'Screen reader text for the pagination heading on the post type listing screen.', 'software-syndicate'),
        'items_list'            => _x('Team lijst', 'Screen reader text for the items list heading on the post type listing screen.', 'software-syndicate'),
    );

    $args = array(
        'label'                 => __('Team Member', 'software-syndicate'),
        'description'           => __('Team member posts voor onze community', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'page-attributes'), // Added page-attributes for menu_order
        'hierarchical'          => false,
        'public'                => false, // Not publicly queryable
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 29,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false, // Not in nav menus
        'can_export'            => true,
        'has_archive'           => false, // No archive
        'exclude_from_search'   => true, // Exclude from search
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'rewrite'               => false, // No rewrite rules
    );
    
    register_post_type('team_member', $args);
}
add_action('init', 'software_syndicate_register_team_member_post_type');

// Register Community Application Custom Post Type
function software_syndicate_register_applications_post_type() {
    $labels = array(
        'name'                  => _x('Applications', 'Post type general name', 'software-syndicate'),
        'singular_name'         => _x('Application', 'Post type singular name', 'software-syndicate'),
        'menu_name'             => _x('Applications', 'Admin Menu text', 'software-syndicate'),
        'name_admin_bar'        => _x('Application', 'Add New on Toolbar', 'software-syndicate'),
        'add_new'               => __('Nieuwe applicatie', 'software-syndicate'),
        'add_new_item'          => __('Nieuwe applicatie toevoegen', 'software-syndicate'),
        'new_item'              => __('Nieuwe applicatie', 'software-syndicate'),
        'edit_item'             => __('Applicatie bewerken', 'software-syndicate'),
        'view_item'             => __('Applicatie bekijken', 'software-syndicate'),
        'all_items'             => __('Alle applicaties', 'software-syndicate'),
        'search_items'          => __('Applicaties zoeken', 'software-syndicate'),
        'parent_item_colon'     => __('Parent applicatie:', 'software-syndicate'),
        'not_found'             => __('Geen applicaties gevonden.', 'software-syndicate'),
        'not_found_in_trash'    => __('Geen applicaties gevonden in prullenbak.', 'software-syndicate'),
        'archives'              => _x('Applicatie archief', 'The post type archive label used in nav menus.', 'software-syndicate'),
        'insert_into_item'      => _x('Invoegen in applicatie', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media).', 'software-syndicate'),
        'uploaded_to_this_item' => _x('Geüpload naar deze applicatie', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'software-syndicate'),
        'filter_items_list'     => _x('Applicatie lijst filteren', 'Screen reader text for filter links heading on the post type listing screen.', 'software-syndicate'),
        'items_list_navigation' => _x('Applicatie lijst navigatie', 'Screen reader text for pagination heading on the post type listing screen.', 'software-syndicate'),
        'items_list'            => _x('Applicatie lijst', 'Screen reader text for the items list heading on the post type listing screen.', 'software-syndicate'),
    );

    $args = array(
        'label'                 => __('Application', 'software-syndicate'),
        'description'           => __('Community applications voor onze syndicate', 'software-syndicate'),
        'labels'                => $labels,
        'supports'              => array('title', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 30,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'application', 'with_front' => false),
        'map_meta_cap'          => true,
    );
    
    register_post_type('application', $args);
    
    // Hide from admin menu - we'll use custom interface
    add_action('admin_menu', function() {
        remove_menu_page('edit.php?post_type=application');
    });
    
    // Remove add new capability
    add_filter('user_has_cap', function($allcaps, $cap, $args) {
        if (!empty($cap) && $cap[0] === 'create_posts' && isset($args[2]) && $args[2] === 'application') {
            $allcaps['create_posts'] = false;
        }
        return $allcaps;
    }, 10, 3);
}
add_action('init', 'software_syndicate_register_applications_post_type');

// Add custom rewrite rule for join-community
function software_syndicate_join_community_rewrite() {
    add_rewrite_rule(
        '^join-community/?$',
        'index.php?join_community=1',
        'top'
    );
}
add_action('init', 'software_syndicate_join_community_rewrite');

// Add query var for join community
function software_syndicate_join_community_query_vars($query_vars) {
    $query_vars[] = 'join_community';
    return $query_vars;
}
add_filter('query_vars', 'software_syndicate_join_community_query_vars');

// Handle join community template
function software_syndicate_join_community_template($template) {
    global $wp_query;
    
    // Check if we're on the join-community page
    if (is_page() && $wp_query->queried_object && $wp_query->queried_object->post_name === 'join-community') {
        return get_template_directory() . '/page-join-community.php';
    }
    
    // Also check custom query var
    if (get_query_var('join_community')) {
        return get_template_directory() . '/page-join-community.php';
    }
    
    return $template;
}
add_filter('template_include', 'software_syndicate_join_community_template');

// Add meta boxes for team members
function software_syndicate_add_team_member_meta_boxes() {
    add_meta_box(
        'team_member_details_meta_box',
        __('Teamlid Details', 'software-syndicate'),
        'software_syndicate_team_member_details_callback',
        'team_member',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_team_member_meta_boxes');

// Team member details callback
function software_syndicate_team_member_details_callback($post) {
    wp_nonce_field('software_syndicate_team_member_meta', 'team_member_meta_nonce');
    
    $team_role = get_post_meta($post->ID, '_team_role', true);
    $team_bio = get_post_meta($post->ID, '_team_bio', true);
    $team_email = get_post_meta($post->ID, '_team_email', true);
    $team_linkedin = get_post_meta($post->ID, '_team_linkedin', true);
    $team_twitter = get_post_meta($post->ID, '_team_twitter', true);
    $team_github = get_post_meta($post->ID, '_team_github', true);
    
    echo '<div class="team-member-meta-fields">';
    
    echo '<p><label for="team_role">' . __('Functie/Rol:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="team_role" name="team_role" value="' . esc_attr($team_role) . '" class="widefat" placeholder="bv. Frontend Developer, Community Manager"></p>';
    
    echo '<p><label for="team_bio">' . __('Bio:', 'software-syndicate') . '</label>';
    echo '<textarea id="team_bio" name="team_bio" class="widefat" rows="4" placeholder="Korte beschrijving van het teamlid...">' . esc_textarea($team_bio) . '</textarea></p>';
    
    echo '<p><label for="team_email">' . __('Email:', 'software-syndicate') . '</label>';
    echo '<input type="email" id="team_email" name="team_email" value="' . esc_attr($team_email) . '" class="widefat" placeholder="email@voorbeeld.com"></p>';
    
    echo '<p><label for="team_linkedin">' . __('LinkedIn:', 'software-syndicate') . '</label>';
    echo '<input type="url" id="team_linkedin" name="team_linkedin" value="' . esc_attr($team_linkedin) . '" class="widefat" placeholder="https://linkedin.com/in/username"></p>';
    
    echo '<p><label for="team_twitter">' . __('Twitter:', 'software-syndicate') . '</label>';
    echo '<input type="url" id="team_twitter" name="team_twitter" value="' . esc_attr($team_twitter) . '" class="widefat" placeholder="https://twitter.com/username"></p>';
    
    echo '<p><label for="team_github">' . __('GitHub:', 'software-syndicate') . '</label>';
    echo '<input type="url" id="team_github" name="team_github" value="' . esc_attr($team_github) . '" class="widefat" placeholder="https://github.com/username"></p>';
    
    echo '</div>';
}

// Save team member meta
function software_syndicate_save_team_member_meta($post_id) {
    if (!isset($_POST['team_member_meta_nonce']) || !wp_verify_nonce($_POST['team_member_meta_nonce'], 'software_syndicate_team_member_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (get_post_type($post_id) !== 'team_member') {
        return;
    }
    
    if (current_user_can('edit_post', $post_id)) {
        update_post_meta($post_id, '_team_role', sanitize_text_field($_POST['team_role']));
        update_post_meta($post_id, '_team_bio', sanitize_textarea_field($_POST['team_bio']));
        update_post_meta($post_id, '_team_email', sanitize_email($_POST['team_email']));
        update_post_meta($post_id, '_team_linkedin', esc_url_raw($_POST['team_linkedin']));
        update_post_meta($post_id, '_team_twitter', esc_url_raw($_POST['team_twitter']));
        update_post_meta($post_id, '_team_github', esc_url_raw($_POST['team_github']));
    }
}
add_action('save_post', 'software_syndicate_save_team_member_meta');

// Add meta boxes for community applications
function software_syndicate_add_application_meta_boxes() {
    add_meta_box(
        'application_details_meta_box',
        __('Application Details', 'software-syndicate'),
        'software_syndicate_application_details_callback',
        'application',
        'normal',
        'high'
    );
    
    add_meta_box(
        'application_status_meta_box',
        __('Application Status', 'software-syndicate'),
        'software_syndicate_application_status_callback',
        'application',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_application_meta_boxes');

// Add meta boxes for products
function software_syndicate_add_product_meta_boxes() {
    add_meta_box(
        'product_details_meta_box',
        __('Product Details', 'software-syndicate'),
        'software_syndicate_product_details_callback',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_product_meta_boxes');

// Product details callback
function software_syndicate_product_details_callback($post) {
    wp_nonce_field('software_syndicate_product_meta', 'product_meta_nonce');
    
    $price = get_post_meta($post->ID, '_product_price', true);
    $sku = get_post_meta($post->ID, '_product_sku', true);
    $stock_status = get_post_meta($post->ID, '_product_stock_status', true);
    $description = get_post_meta($post->ID, '_product_description', true);
    $sizes = get_post_meta($post->ID, '_product_sizes', true);
    $colors = get_post_meta($post->ID, '_product_colors', true);
    
    echo '<div class="product-variation-fields" style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px;">';
    echo '<h4 style="margin-top:0;">' . __('Product Variaties', 'software-syndicate') . '</h4>';

    echo '<p><label for="product_sizes_input" style="display:block; font-weight:bold; margin-bottom:5px;">' . __('Beschikbare Maten (komma gescheiden, bijv: S, M, L):', 'software-syndicate') . '</label>';
    echo '<input type="text" id="product_sizes_input" name="product_sizes_input" value="' . esc_attr($sizes) . '" class="widefat" placeholder="bijv. S, M, L"></p>';

    echo '<p><label for="product_colors_input" style="display:block; font-weight:bold; margin-bottom:5px;">' . __('Beschikbare Kleuren (komma gescheiden, bijv: Rood, Blauw, Zwart):', 'software-syndicate') . '</label>';
    echo '<input type="text" id="product_colors_input" name="product_colors_input" value="' . esc_attr($colors) . '" class="widefat" placeholder="bijv. Zwart, Wit, Blauw"></p>';
    echo '</div>';

    echo '<p><label for="product_price">' . __('Prijs (€):', 'software-syndicate') . '</label>';
    echo '<input type="number" step="0.01" id="product_price" name="product_price" value="' . esc_attr($price) . '" class="widefat"></p>';
    
    echo '<p><label for="product_sku">' . __('SKU:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="product_sku" name="product_sku" value="' . esc_attr($sku) . '" class="widefat"></p>';
    
    echo '<p><label for="product_stock_status">' . __('Voorraad Status:', 'software-syndicate') . '</label>';
    echo '<select id="product_stock_status" name="product_stock_status" class="widefat">';
    echo '<option value="instock" ' . selected($stock_status, 'instock', false) . '>' . __('Op voorraad', 'software-syndicate') . '</option>';
    echo '<option value="outofstock" ' . selected($stock_status, 'outofstock', false) . '>' . __('Niet op voorraad', 'software-syndicate') . '</option>';
    echo '</select></p>';
    
    echo '<p><label for="product_description">' . __('Product Beschrijving:', 'software-syndicate') . '</label>';
    wp_editor($description, 'product_description', array(
        'textarea_name' => 'product_description',
        'media_buttons' => false,
        'textarea_rows' => 10,
        'teeny'         => true,
        'quicktags'     => false
    ));
    echo '</p>';
}

// Save product meta
function software_syndicate_save_product_meta($post_id) {
    if (!isset($_POST['product_meta_nonce']) || !wp_verify_nonce($_POST['product_meta_nonce'], 'software_syndicate_product_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    if (current_user_can('edit_post', $post_id)) {
        update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
        update_post_meta($post_id, '_product_sku', sanitize_text_field($_POST['product_sku']));
        update_post_meta($post_id, '_product_stock_status', sanitize_text_field($_POST['product_stock_status']));
        update_post_meta($post_id, '_product_description', wp_kses_post($_POST['product_description']));
        
        if (isset($_POST['product_sizes_input'])) {
            update_post_meta($post_id, '_product_sizes', sanitize_text_field($_POST['product_sizes_input']));
        }
        if (isset($_POST['product_colors_input'])) {
            update_post_meta($post_id, '_product_colors', sanitize_text_field($_POST['product_colors_input']));
        }
    }
}
add_action('save_post', 'software_syndicate_save_product_meta');

// Add meta boxes for applications details callback
function software_syndicate_application_details_callback($post) {
    wp_nonce_field('software_syndicate_application_meta', 'application_meta_nonce');
    
    $application_name = get_post_meta($post->ID, '_application_name', true);
    $application_email = get_post_meta($post->ID, '_application_email', true);
    $application_phone = get_post_meta($post->ID, '_application_phone', true);
    $application_age = get_post_meta($post->ID, '_application_age', true);
    $application_location = get_post_meta($post->ID, '_application_location', true);
    $application_experience = get_post_meta($post->ID, '_application_experience', true);
    $application_motivation = get_post_meta($post->ID, '_application_motivation', true);
    $application_skills = get_post_meta($post->ID, '_application_skills', true);
    $application_availability = get_post_meta($post->ID, '_application_availability', true);
    $application_date = get_post_meta($post->ID, '_application_date', true);
    
    echo '<div class="application-meta-fields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">';
    
    echo '<div>';
    echo '<p><label for="application_name">' . __('Volledige Naam:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="application_name" name="application_name" value="' . esc_attr($application_name) . '" class="widefat" readonly></p>';
    
    echo '<p><label for="application_email">' . __('Email:', 'software-syndicate') . '</label>';
    echo '<input type="email" id="application_email" name="application_email" value="' . esc_attr($application_email) . '" class="widefat" readonly></p>';
    
    echo '<p><label for="application_phone">' . __('Telefoon:', 'software-syndicate') . '</label>';
    echo '<input type="tel" id="application_phone" name="application_phone" value="' . esc_attr($application_phone) . '" class="widefat" readonly></p>';
    
    echo '<p><label for="application_age">' . __('Leeftijd:', 'software-syndicate') . '</label>';
    echo '<input type="number" id="application_age" name="application_age" value="' . esc_attr($application_age) . '" class="widefat" readonly></p>';
    
    echo '<p><label for="application_location">' . __('Locatie:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="application_location" name="application_location" value="' . esc_attr($application_location) . '" class="widefat" readonly></p>';
    
    echo '<p><label for="application_date">' . __('Aanvraagdatum:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="application_date" name="application_date" value="' . esc_attr($application_date) . '" class="widefat" readonly></p>';
    echo '</div>';
    
    echo '<div>';
    echo '<p><label for="application_experience">' . __('Ervaring:', 'software-syndicate') . '</label>';
    echo '<textarea id="application_experience" name="application_experience" class="widefat" rows="4" readonly>' . esc_textarea($application_experience) . '</textarea></p>';
    
    echo '<p><label for="application_motivation">' . __('Motivatie:', 'software-syndicate') . '</label>';
    echo '<textarea id="application_motivation" name="application_motivation" class="widefat" rows="4" readonly>' . esc_textarea($application_motivation) . '</textarea></p>';
    
    echo '<p><label for="application_skills">' . __('Vaardigheden:', 'software-syndicate') . '</label>';
    echo '<textarea id="application_skills" name="application_skills" class="widefat" rows="4" readonly>' . esc_textarea($application_skills) . '</textarea></p>';
    
    echo '<p><label for="application_availability">' . __('Beschikbaarheid:', 'software-syndicate') . '</label>';
    echo '<textarea id="application_availability" name="application_availability" class="widefat" rows="4" readonly>' . esc_textarea($application_availability) . '</textarea></p>';
    echo '</div>';
    
    echo '</div>';
}

// Application status callback
function software_syndicate_application_status_callback($post) {
    $application_status = get_post_meta($post->ID, '_application_status', true);
    $admin_notes = get_post_meta($post->ID, '_admin_notes', true);
    
    echo '<div class="application-status-fields">';
    
    echo '<p><label for="application_status">' . __('Status:', 'software-syndicate') . '</label>';
    echo '<select id="application_status" name="application_status" class="widefat">';
    echo '<option value="pending" ' . selected($application_status, 'pending', false) . '>' . __('In behandeling', 'software-syndicate') . '</option>';
    echo '<option value="accepted" ' . selected($application_status, 'accepted', false) . '>' . __('Geaccepteerd', 'software-syndicate') . '</option>';
    echo '<option value="denied" ' . selected($application_status, 'denied', false) . '>' . __('Geweigerd', 'software-syndicate') . '</option>';
    echo '</select></p>';
    
    echo '<p><label for="admin_notes">' . __('Admin Notities:', 'software-syndicate') . '</label>';
    echo '<textarea id="admin_notes" name="admin_notes" class="widefat" rows="4" placeholder="Interne notities over deze applicatie...">' . esc_textarea($admin_notes) . '</textarea></p>';
    
    echo '</div>';
}

// Save community application meta
function software_syndicate_save_application_meta($post_id) {
    if (!isset($_POST['application_meta_nonce']) || !wp_verify_nonce($_POST['application_meta_nonce'], 'software_syndicate_application_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (get_post_type($post_id) !== 'application') {
        return;
    }
    
    if (current_user_can('edit_post', $post_id)) {
        // Save status and admin notes (only these are editable by admin)
        update_post_meta($post_id, '_application_status', sanitize_text_field($_POST['application_status']));
        update_post_meta($post_id, '_admin_notes', sanitize_textarea_field($_POST['admin_notes']));
    }
}
add_action('save_post', 'software_syndicate_save_application_meta');

// Remove default custom fields meta box from tutorials and events
function software_syndicate_remove_custom_meta_boxes() {
    remove_meta_box('postcustom', 'tutorial', 'normal');
    remove_meta_box('postcustom', 'event', 'normal');
    remove_meta_box('slugdiv', 'event', 'normal'); // Remove permalink box
}
add_action('add_meta_boxes', 'software_syndicate_remove_custom_meta_boxes');

// Hide permalink box for events with CSS
function software_syndicate_hide_permalink_box() {
    global $post_type;
    if ($post_type === 'event') {
        echo '<style>
            #edit-slug-box {
                display: none !important;
            }
        </style>';
    }
}
add_action('admin_head', 'software_syndicate_hide_permalink_box');

// Add meta boxes for events
function software_syndicate_add_event_meta_boxes() {
    add_meta_box(
        'event_details_meta_box',
        __('Event Details', 'software-syndicate'),
        'software_syndicate_event_details_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_event_meta_boxes');

// Event details callback (combined for events and hackathons)
function software_syndicate_event_details_callback($post) {
    wp_nonce_field('software_syndicate_event_meta', 'event_meta_nonce');
    
    $event_type = get_post_meta($post->ID, '_event_type', true);
    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    $event_status = get_post_meta($post->ID, '_event_status', true);
    $event_description = get_post_meta($post->ID, '_event_description', true);
    $event_duration = get_post_meta($post->ID, '_event_duration', true);
    $event_prizes = get_post_meta($post->ID, '_event_prizes', true);
    
    echo '<div class="event-meta-fields">';
    
    // Event Type Selector
    echo '<p><label for="event_type">' . __('Type:', 'software-syndicate') . '</label>';
    echo '<select id="event_type" name="event_type" class="widefat">';
    echo '<option value="event" ' . selected($event_type, 'event', false) . '>' . __('Event', 'software-syndicate') . '</option>';
    echo '<option value="hackathon" ' . selected($event_type, 'hackathon', false) . '>' . __('Hackathon', 'software-syndicate') . '</option>';
    echo '</select></p>';
    
    // Common fields
    echo '<p><label for="event_date">' . __('Datum:', 'software-syndicate') . '</label>';
    echo '<input type="datetime-local" id="event_date" name="event_date" value="' . esc_attr($event_date) . '" class="widefat"></p>';
    
    echo '<p><label for="event_location">' . __('Locatie:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="event_location" name="event_location" value="' . esc_attr($event_location) . '" class="widefat"></p>';
    
    echo '<p><label for="event_status">' . __('Status:', 'software-syndicate') . '</label>';
    echo '<select id="event_status" name="event_status" class="widefat">';
    echo '<option value="upcoming" ' . selected($event_status, 'upcoming', false) . '>' . __('Aankomend', 'software-syndicate') . '</option>';
    echo '<option value="ongoing" ' . selected($event_status, 'ongoing', false) . '>' . __('Bezig', 'software-syndicate') . '</option>';
    echo '<option value="completed" ' . selected($event_status, 'completed', false) . '>' . __('Afgerond', 'software-syndicate') . '</option>';
    echo '</select></p>';
    
    echo '<p><label for="event_description">' . __('Beschrijving:', 'software-syndicate') . '</label>';
    echo '<textarea id="event_description" name="event_description" class="widefat" rows="4">' . esc_textarea($event_description) . '</textarea></p>';
    
    // Hackathon specific fields (shown with JavaScript)
    echo '<div id="hackathon_fields" style="display: ' . ($event_type === 'hackathon' ? 'block' : 'none') . ';">';
    echo '<p><label for="event_duration">' . __('Duur:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="event_duration" name="event_duration" value="' . esc_attr($event_duration) . '" class="widefat" placeholder="bv. 24 uur, 2 dagen"></p>';
    
    echo '<p><label for="event_prizes">' . __('Prijzen:', 'software-syndicate') . '</label>';
    echo '<input type="text" id="event_prizes" name="event_prizes" value="' . esc_attr($event_prizes) . '" class="widefat" placeholder="bv. €1000, Laptop, Stage"></p>';
    echo '</div>';
    
    echo '</div>';
    
    // JavaScript to show/hide hackathon fields
    ?>
    <script>
    jQuery(document).ready(function($) {
        function toggleHackathonFields() {
            var eventType = $('#event_type').val();
            if (eventType === 'hackathon') {
                $('#hackathon_fields').show();
            } else {
                $('#hackathon_fields').hide();
            }
        }
        
        $('#event_type').on('change', toggleHackathonFields);
        toggleHackathonFields();
    });
    </script>
    <?php
}

// Save event meta
function software_syndicate_save_event_meta($post_id) {
    if (!isset($_POST['event_meta_nonce']) || !wp_verify_nonce($_POST['event_meta_nonce'], 'software_syndicate_event_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (get_post_type($post_id) !== 'event') {
        return;
    }
    
    if (current_user_can('edit_post', $post_id)) {
        update_post_meta($post_id, '_event_type', sanitize_text_field($_POST['event_type']));
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
        update_post_meta($post_id, '_event_status', sanitize_text_field($_POST['event_status']));
        update_post_meta($post_id, '_event_description', sanitize_textarea_field($_POST['event_description']));
        update_post_meta($post_id, '_event_duration', sanitize_text_field($_POST['event_duration']));
        update_post_meta($post_id, '_event_prizes', sanitize_text_field($_POST['event_prizes']));
    }
}
add_action('save_post', 'software_syndicate_save_event_meta');

// Add custom rewrite rules for tutorials
function software_syndicate_tutorial_rewrite_rules() {
    // Add rule for single tutorials (but not for the base /tutorials/ page)
    add_rewrite_rule(
        '^tutorials/([^/]+)/?$',
        'index.php?post_type=tutorial&name=$matches[1]',
        'top'
    );
    
    // Disable the default archive for /tutorials/ to let the page take precedence
    add_filter('rewrite_rules_array', function($rules) {
        unset($rules['tutorials/?$']);
        unset($rules['tutorials/feed/(feed|rdf|rss|rss2|atom)/?$']);
        unset($rules['tutorials/(feed|rdf|rss|rss2|atom)/?$']);
        unset($rules['tutorials/page/([0-9]{1,})/?$']);
        return $rules;
    });
}
add_action('init', 'software_syndicate_tutorial_rewrite_rules');

// Enqueue scripts and styles
function software_syndicate_scripts() {
    wp_enqueue_style('software-syndicate-style', get_stylesheet_uri());
    
    wp_enqueue_style('software-syndicate-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=array');
    
    wp_enqueue_script('software-syndicate-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'software_syndicate_scripts');

// Hide rich editor for project post type
function software_syndicate_hide_editor_for_projects() {
    global $post_type;
    
    if ($post_type === 'project') {
        ?>
        <style>
        #postdivrich {
            display: none !important;
        }
        
        /* Hide other unnecessary editor elements for projects */
        .postarea-wrap #content-html,
        .postarea-wrap #content-tmce {
            display: none !important;
        }
        
        /* Hide editor toggle buttons */
        .wp-editor-tabs {
            display: none !important;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'software_syndicate_hide_editor_for_projects');

// Add custom meta boxes for projects
function software_syndicate_add_project_meta_boxes() {
    add_meta_box(
        'project_about_meta_box',
        __('Project Details', 'software-syndicate'),
        'software_syndicate_project_about_callback',
        'project',
        'normal',
        'high'
    );
    
    add_meta_box(
        'project_links_meta_box',
        __('GitHub Intergratie', 'software-syndicate'),
        'software_syndicate_project_links_callback',
        'project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_project_meta_boxes');

// Add custom meta boxes for tutorials
function software_syndicate_add_tutorial_meta_boxes() {
    add_meta_box(
        'tutorial_details_meta_box',
        __('Tutorial Details', 'software-syndicate'),
        'software_syndicate_tutorial_details_callback',
        'tutorial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'software_syndicate_add_tutorial_meta_boxes');

// Meta box callback function for tutorial details
function software_syndicate_tutorial_details_callback($post) {
    wp_nonce_field('software_syndicate_save_tutorial_details', 'tutorial_details_nonce');
    
    // Get current values
    $tutorial_content = get_post_meta($post->ID, '_tutorial_content', true);
    $difficulty = get_post_meta($post->ID, '_tutorial_difficulty', true);
    $estimated_time = get_post_meta($post->ID, '_tutorial_estimated_time', true);
    $prerequisites = get_post_meta($post->ID, '_tutorial_prerequisites', true);
    
    ?>
    <div class="tutorial-meta-fields">
        <div class="form-field">
            <label for="tutorial_content"><?php _e('Over deze tutorial', 'software-syndicate'); ?></label>
            <textarea id="tutorial_content" name="tutorial_content" rows="4" class="large-text" placeholder="Beschrijf wat de gebruiker zal leren in deze tutorial..."><?php echo esc_textarea($tutorial_content); ?></textarea>
            <p class="description"><?php _e('Beschrijf wat de gebruiker zal leren in deze tutorial...', 'software-syndicate'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="tutorial_difficulty"><?php _e('Moeilijkheidsgraad', 'software-syndicate'); ?></label>
            <select id="tutorial_difficulty" name="tutorial_difficulty" class="regular-text">
                <option value="" <?php selected($difficulty, ''); ?>><?php _e('Selecteer moeilijkheidsgraad', 'software-syndicate'); ?></option>
                <option value="Beginner" <?php selected($difficulty, 'Beginner'); ?>><?php _e('Beginner', 'software-syndicate'); ?></option>
                <option value="Gevorderd" <?php selected($difficulty, 'Gevorderd'); ?>><?php _e('Gevorderd', 'software-syndicate'); ?></option>
                <option value="Expert" <?php selected($difficulty, 'Expert'); ?>><?php _e('Expert', 'software-syndicate'); ?></option>
            </select>
            <p class="description"><?php _e('Selecteer de moeilijkheidsgraad van deze tutorial.', 'software-syndicate'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="tutorial_estimated_time"><?php _e('Geschatte tijd', 'software-syndicate'); ?></label>
            <input type="text" id="tutorial_estimated_time" name="tutorial_estimated_time" value="<?php echo esc_attr($estimated_time); ?>" placeholder="30 minuten" />
            <p class="description"><?php _e('Hoe lang duurt het om deze tutorial te voltooien?', 'software-syndicate'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="tutorial_prerequisites"><?php _e('Vereisten', 'software-syndicate'); ?></label>
            <textarea id="tutorial_prerequisites" name="tutorial_prerequisites" rows="3" class="large-text" placeholder="Basiskennis van HTML en CSS..."><?php echo esc_textarea($prerequisites); ?></textarea>
            <p class="description"><?php _e('Welke kennis of vaardigheden zijn nodig voordat men deze tutorial start?', 'software-syndicate'); ?></p>
        </div>
    </div>
    
    <style>
    .tutorial-meta-fields .form-field {
        margin-bottom: 20px;
    }
    .tutorial-meta-fields label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .tutorial-meta-fields .description {
        font-style: italic;
        color: #666;
        margin-top: 5px;
    }
    .tutorial-meta-fields input,
    .tutorial-meta-fields textarea,
    .tutorial-meta-fields select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    </style>
    <?php
}

// Save tutorial details data
function software_syndicate_save_tutorial_details($post_id) {
    if (!isset($_POST['tutorial_details_nonce']) || !wp_verify_nonce($_POST['tutorial_details_nonce'], 'software_syndicate_save_tutorial_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['tutorial_content'])) {
        update_post_meta($post_id, '_tutorial_content', sanitize_textarea_field($_POST['tutorial_content']));
    }
    
    if (isset($_POST['tutorial_difficulty'])) {
        update_post_meta($post_id, '_tutorial_difficulty', sanitize_text_field($_POST['tutorial_difficulty']));
    }
    
    if (isset($_POST['tutorial_estimated_time'])) {
        update_post_meta($post_id, '_tutorial_estimated_time', sanitize_text_field($_POST['tutorial_estimated_time']));
    }
    
    if (isset($_POST['tutorial_prerequisites'])) {
        update_post_meta($post_id, '_tutorial_prerequisites', sanitize_textarea_field($_POST['tutorial_prerequisites']));
    }
}
add_action('save_post', 'software_syndicate_save_tutorial_details');

// Meta box callback function for project about
function software_syndicate_project_about_callback($post) {
    wp_nonce_field('software_syndicate_save_project_about', 'project_about_nonce');
    
    // Get current values
    $about_project = get_post_meta($post->ID, '_project_about', true);
    $tech_stack = get_post_meta($post->ID, '_project_tech_stack', true);
    $contribution = get_post_meta($post->ID, '_project_contribution', true);
    
    // Default values
    $default_about = 'Beschrijf hier je project...';
    $default_contribution = "1. Fork de repository\n2. Maak een nieuwe branch voor je feature\n3. Commit je wijzigingen\n4. Push naar je branch\n5. Maak een Pull Request";
    
    ?>
    <div class="project-meta-fields">
        <div class="form-field">
            <label for="project_about"><?php _e('Over dit project', 'software-syndicate'); ?></label>
            <textarea id="project_about" name="project_about" rows="4" class="large-text" placeholder="<?php echo esc_attr($default_about); ?>"><?php echo esc_textarea($about_project); ?></textarea>
            <p class="description"><?php _e('Beschrijf je project hier...', 'software-syndicate'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="project_tech_stack"><?php _e('Tech Stack', 'software-syndicate'); ?></label>
            <input type="text" id="project_tech_stack" name="project_tech_stack" value="<?php echo esc_attr($tech_stack); ?>" placeholder="React, Node.js, MongoDB" />
            <p class="description"><?php _e('Voeg technologieën toe gescheiden door komma. Bijvoorbeeld: React, Node.js, MongoDB', 'software-syndicate'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="project_contribution"><?php _e('Hoe kunnen lezers bijdragen aan dit project?', 'software-syndicate'); ?></label>
            <textarea id="project_contribution" name="project_contribution" rows="6" class="large-text" placeholder="<?php echo esc_attr($default_contribution); ?>"><?php echo esc_textarea($contribution); ?></textarea>
            <p class="description"><?php _e('Laat leeg voor standaard bijdrage instructies.', 'software-syndicate'); ?></p>
        </div>
    </div>
    
    <style>
    .project-meta-fields .form-field {
        margin-bottom: 20px;
    }
    .project-meta-fields label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .project-meta-fields .description {
        font-style: italic;
        color: #666;
        margin-top: 5px;
    }
    .project-meta-fields input,
    .project-meta-fields textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    </style>
    <?php
}

// Meta box callback function for project links
function software_syndicate_project_links_callback($post) {
    wp_nonce_field('software_syndicate_save_project_links', 'project_links_nonce');
    
    // Get current values
    $github_repo = get_post_meta($post->ID, '_project_github_repo', true);
    
    ?>
    <div class="project-meta-fields">
        <div class="form-field">
            <label for="project_github_repo"><?php _e('GitHub Repository Link', 'software-syndicate'); ?></label>
            <input type="url" id="project_github_repo" name="project_github_repo" value="<?php echo esc_url($github_repo); ?>" placeholder="https://github.com/username/repository" />
            <p class="description"><?php _e('Voeg de GitHub repository link toe. Als ingevuld, wordt er een GitHub knop getoond.', 'software-syndicate'); ?></p>
        </div>
    </div>
    
    <style>
    .project-meta-fields .form-field {
        margin-bottom: 20px;
    }
    .project-meta-fields label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .project-meta-fields .description {
        font-style: italic;
        color: #666;
        margin-top: 5px;
    }
    .project-meta-fields input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    </style>
    <?php
}

// Save project about data
function software_syndicate_save_project_about($post_id) {
    if (!isset($_POST['project_about_nonce']) || !wp_verify_nonce($_POST['project_about_nonce'], 'software_syndicate_save_project_about')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if ('project' !== $_POST['post_type']) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save about project
    if (isset($_POST['project_about'])) {
        update_post_meta($post_id, '_project_about', sanitize_textarea_field($_POST['project_about']));
    }
    
    // Save tech stack
    if (isset($_POST['project_tech_stack'])) {
        update_post_meta($post_id, '_project_tech_stack', sanitize_text_field($_POST['project_tech_stack']));
    }
    
    // Save contribution with default if empty
    $default_contribution = "1. Fork de repository\n2. Maak een nieuwe branch voor je feature\n3. Commit je wijzigingen\n4. Push naar je branch\n5. Maak een Pull Request";
    if (isset($_POST['project_contribution'])) {
        $contribution = sanitize_textarea_field($_POST['project_contribution']);
        if (empty($contribution)) {
            $contribution = $default_contribution;
        }
        update_post_meta($post_id, '_project_contribution', $contribution);
    }
}
add_action('save_post', 'software_syndicate_save_project_about');

// Save project links data
function software_syndicate_save_project_links($post_id) {
    if (!isset($_POST['project_links_nonce']) || !wp_verify_nonce($_POST['project_links_nonce'], 'software_syndicate_save_project_links')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if ('project' !== $_POST['post_type']) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save GitHub repository
    if (isset($_POST['project_github_repo'])) {
        update_post_meta($post_id, '_project_github_repo', esc_url_raw($_POST['project_github_repo']));
    }
}
add_action('save_post', 'software_syndicate_save_project_links');

// Get tech stack icon function
function software_syndicate_get_tech_icon($tech) {
    $tech = strtolower(trim($tech));
    
    $icons = array(
        'react' => '<span class="tech-icon">⚛️</span>',
        'vue' => '<span class="tech-icon">🟢</span>',
        'angular' => '<span class="tech-icon">🅰️</span>',
        'node.js' => '<span class="tech-icon">🟢</span>',
        'nodejs' => '<span class="tech-icon">🟢</span>',
        'javascript' => '<span class="tech-icon">📜</span>',
        'js' => '<span class="tech-icon">📜</span>',
        'typescript' => '<span class="tech-icon">🔷</span>',
        'php' => '<span class="tech-icon">🐘</span>',
        'python' => '<span class="tech-icon">🐍</span>',
        'java' => '<span class="tech-icon">☕</span>',
        'html' => '<span class="tech-icon">🌐</span>',
        'css' => '<span class="tech-icon">🎨</span>',
        'sass' => '<span class="tech-icon">🎨</span>',
        'scss' => '<span class="tech-icon">🎨</span>',
        'wordpress' => '<span class="tech-icon">📝</span>',
        'laravel' => '<span class="tech-icon">🔪</span>',
        'symfony' => '<span class="tech-icon">🎼</span>',
        'mongodb' => '<span class="tech-icon">🍃</span>',
        'mysql' => '<span class="tech-icon">🐬</span>',
        'postgresql' => '<span class="tech-icon">🐘</span>',
        'docker' => '<span class="tech-icon">🐳</span>',
        'kubernetes' => '<span class="tech-icon">☸️</span>',
        'aws' => '<span class="tech-icon">☁️</span>',
        'git' => '<span class="tech-icon">📦</span>',
        'github' => '<span class="tech-icon">🐙</span>',
        'api' => '<span class="tech-icon">🔌</span>',
        'rest' => '<span class="tech-icon">🔌</span>',
        'graphql' => '<span class="tech-icon">🔮</span>',
        'json' => '<span class="tech-icon">📄</span>',
        'xml' => '<span class="tech-icon">📄</span>',
        'nginx' => '<span class="tech-icon">🌍</span>',
        'apache' => '<span class="tech-icon">🌍</span>',
        'redis' => '<span class="tech-icon">🔴</span>',
        'tailwind' => '<span class="tech-icon">🌊</span>',
        'bootstrap' => '<span class="tech-icon">🅱️</span>',
        'jquery' => '<span class="tech-icon">📜</span>',
        'webpack' => '<span class="tech-icon">📦</span>',
        'vite' => '<span class="tech-icon">⚡</span>',
        'next.js' => '<span class="tech-icon">▲</span>',
        'nextjs' => '<span class="tech-icon">▲</span>',
        'nuxt.js' => '<span class="tech-icon">🟢</span>',
        'nuxtjs' => '<span class="tech-icon">🟢</span>',
    );
    
    // Return icon if found, otherwise default
    return isset($icons[$tech]) ? $icons[$tech] : '<span class="tech-icon">🔧</span>';
}

// Register widget areas
function software_syndicate_widgets_init() {
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'software-syndicate'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'software-syndicate'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'software-syndicate'),
        'id'            => 'footer-widgets',
        'description'   => __('Add widgets here to appear in your footer.', 'software-syndicate'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'software_syndicate_widgets_init');

// Customizer settings
function software_syndicate_customize_register($wp_customize) {
    // Hero Section Settings
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'software-syndicate'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('hero_title', array(
        'default'           => 'The Software Syndicate',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label'    => __('Hero Title', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Where Developers Build, Learn & Conspire',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('primary_button_text', array(
        'default'           => 'JOIN THE SYNDICATE',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('primary_button_text', array(
        'label'    => __('Primary Button Text', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('primary_button_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('primary_button_url', array(
        'label'    => __('Primary Button URL', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('secondary_button_text', array(
        'default'           => 'EXPLORE PROJECTS',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('secondary_button_text', array(
        'label'    => __('Secondary Button Text', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('secondary_button_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('secondary_button_url', array(
        'label'    => __('Secondary Button URL', 'software-syndicate'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));

    // Contact Page Settings
    $wp_customize->add_section('contact_section', array(
        'title'    => __('Contact Page', 'software-syndicate'),
        'priority' => 31,
    ));
    
    $wp_customize->add_setting('contact_title', array(
        'default'           => 'Neem Contact Op',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_title', array(
        'label'    => __('Contact Title', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_subtitle', array(
        'default'           => 'Heb je vragen, suggesties of wil je samenwerken? Vul het onderstaande formulier in en we nemen zo snel mogelijk contact met je op.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('contact_subtitle', array(
        'label'    => __('Contact Subtitle', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'textarea',
    ));
    
    $wp_customize->add_setting('contact_form_email', array(
        'default'           => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('contact_form_email', array(
        'label'    => __('Contact Form Email', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
        'description' => __('Email address where contact form submissions will be sent.', 'software-syndicate'),
    ));
    
    // Contact Form Field Labels
    $wp_customize->add_setting('contact_name_label', array(
        'default'           => 'Naam:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_name_label', array(
        'label'    => __('Name Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_name_placeholder', array(
        'default'           => 'Jouw naam',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_name_placeholder', array(
        'label'    => __('Name Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_email_label', array(
        'default'           => 'E-mail:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_email_label', array(
        'label'    => __('Email Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_email_placeholder', array(
        'default'           => 'jouw@email.com',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_email_placeholder', array(
        'label'    => __('Email Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_subject_label', array(
        'default'           => 'Onderwerp:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_subject_label', array(
        'label'    => __('Subject Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_subject_placeholder', array(
        'default'           => 'Waar gaat je vraag over?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_subject_placeholder', array(
        'label'    => __('Subject Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_message_label', array(
        'default'           => 'Bericht:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_message_label', array(
        'label'    => __('Message Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_message_placeholder', array(
        'default'           => 'Typ hier je bericht...',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_message_placeholder', array(
        'label'    => __('Message Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'textarea',
    ));
    
    $wp_customize->add_setting('contact_submit_button_text', array(
        'default'           => 'Verstuur Bericht',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_submit_button_text', array(
        'label'    => __('Submit Button Text', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    // Additional Form Fields
    $wp_customize->add_setting('contact_phone_label', array(
        'default'           => 'Telefoon:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_phone_label', array(
        'label'    => __('Phone Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
        'description' => __('Leave empty to hide this field.', 'software-syndicate'),
    ));
    
    $wp_customize->add_setting('contact_phone_placeholder', array(
        'default'           => 'Jouw telefoonnummer',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_phone_placeholder', array(
        'label'    => __('Phone Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_company_label', array(
        'default'           => 'Bedrijf/Organisatie:',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_company_label', array(
        'label'    => __('Company Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
        'description' => __('Leave empty to hide this field.', 'software-syndicate'),
    ));
    
    $wp_customize->add_setting('contact_company_placeholder', array(
        'default'           => 'Naam van je bedrijf of organisatie',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_company_placeholder', array(
        'label'    => __('Company Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('contact_referral_label', array(
        'default'           => 'Hoe heb je ons gevonden?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_referral_label', array(
        'label'    => __('Referral Field Label', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
        'description' => __('Leave empty to hide this field.', 'software-syndicate'),
    ));
    
    $wp_customize->add_setting('contact_referral_placeholder', array(
        'default'           => 'bijv. Google, social media, via een vriend',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('contact_referral_placeholder', array(
        'label'    => __('Referral Field Placeholder', 'software-syndicate'),
        'section'  => 'contact_section',
        'type'     => 'text',
    ));
    
    // About Us Page Settings
    $wp_customize->add_section('about_section', array(
        'title'    => __('About Us Page', 'software-syndicate'),
        'priority' => 32,
    ));

    $wp_customize->add_setting('about_title', array(
        'default'           => 'Over Ons',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('about_title', array(
        'label'    => __('About Us Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('about_subtitle', array(
        'default'           => 'Welkom bij The Software Syndicate, waar innovatie en samenwerking samenkomen. Wij zijn een community van ontwikkelaars, denkers en makers die geloven in de kracht van collectieve intelligentie.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('about_subtitle', array(
        'label'    => __('About Us Subtitle', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // Manifest Section
    $wp_customize->add_setting('manifest_title', array(
        'default'           => 'Ons Manifest',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('manifest_title', array(
        'label'    => __('Manifest Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    // Feature 1: Samenwerking
    $wp_customize->add_setting('feature1_title', array(
        'default'           => 'Samenwerking',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feature1_title', array(
        'label'    => __('Feature 1 Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('feature1_description', array(
        'default'           => 'Wij geloven dat de grootste doorbraken ontstaan wanneer we samenwerken. Kennis delen en elkaar ondersteunen is onze kern.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feature1_description', array(
        'label'    => __('Feature 1 Description', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // Feature 2: Continue Groei
    $wp_customize->add_setting('feature2_title', array(
        'default'           => 'Continue Groei',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feature2_title', array(
        'label'    => __('Feature 2 Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('feature2_description', array(
        'default'           => 'De wereld van software staat nooit stil, en wij ook niet. We omarmen levenslang leren en experimenteren.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feature2_description', array(
        'label'    => __('Feature 2 Description', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // Feature 3: Innovatie
    $wp_customize->add_setting('feature3_title', array(
        'default'           => 'Innovatie',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feature3_title', array(
        'label'    => __('Feature 3 Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('feature3_description', array(
        'default'           => 'Constante innovatie is de drijvende kracht achter onze community. We zoeken altijd naar nieuwe en betere manieren.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feature3_description', array(
        'label'    => __('Feature 3 Description', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // Feature 4: Impact Maken
    $wp_customize->add_setting('feature4_title', array(
        'default'           => 'Impact Maken',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feature4_title', array(
        'label'    => __('Feature 4 Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('feature4_description', array(
        'default'           => 'Onze projecten zijn meer dan alleen code; ze zijn bedoeld om een positieve impact te hebben op de wereld.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feature4_description', array(
        'label'    => __('Feature 4 Description', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // Vision Section
    $wp_customize->add_setting('vision_title', array(
        'default'           => 'Onze Visie',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('vision_title', array(
        'label'    => __('Vision Title', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('vision_description', array(
        'default'           => 'Wij streven ernaar een wereld te creëren waarin elke ontwikkelaar de middelen en de gemeenschap heeft om hun ideeën tot leven te brengen en de toekomst van technologie vorm te geven.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('vision_description', array(
        'label'    => __('Vision Description', 'software-syndicate'),
        'section'  => 'about_section',
        'type'     => 'textarea',
    ));

    // About Us Images
    $wp_customize->add_setting('about_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_hero_image', array(
        'label'    => __('Hero Section Image', 'software-syndicate'),
        'section'  => 'about_section',
        'settings' => 'about_hero_image',
        'description' => __('Upload an image to display in the hero section.', 'software-syndicate'),
    )));

    $wp_customize->add_setting('about_vision_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_vision_image', array(
        'label'    => __('Vision Section Image', 'software-syndicate'),
        'section'  => 'about_section',
        'settings' => 'about_vision_image',
        'description' => __('Upload an image to display in the vision section.', 'software-syndicate'),
    )));

    // Projects Page Settings
    $wp_customize->add_section('projects_section', array(
        'title'    => __('Projects Page', 'software-syndicate'),
        'priority' => 33,
    ));

    $wp_customize->add_setting('projects_title', array(
        'default'           => 'Projecten',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('projects_title', array(
        'label'    => __('Projects Page Title', 'software-syndicate'),
        'section'  => 'projects_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('projects_subtitle', array(
        'default'           => 'Ontdek onze innovatieve projecten en oplossingen die we hebben ontwikkeld voor onze klanten en community.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('projects_subtitle', array(
        'label'    => __('Projects Page Subtitle', 'software-syndicate'),
        'section'  => 'projects_section',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('projects_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'projects_hero_image', array(
        'label'    => __('Projects Hero Image', 'software-syndicate'),
        'section'  => 'projects_section',
        'settings' => 'projects_hero_image',
        'description' => __('Upload an image to display in the projects hero section.', 'software-syndicate'),
    )));

    // Check if Contact Form 7 is active
    if (function_exists('wpcf7_contact_form')) {
        $wp_customize->add_setting('contact_form_id', array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_control('contact_form_id', array(
            'label'    => __('Contact Form 7 ID', 'software-syndicate'),
            'section'  => 'contact_section',
            'type'     => 'text',
            'description' => __('Enter Contact Form 7 form ID. Leave empty to use built-in form.', 'software-syndicate'),
        ));
    }
    
    // Tutorials Page Settings
    $wp_customize->add_section('tutorials_section', array(
        'title'    => __('Tutorials Page', 'software-syndicate'),
        'priority' => 33,
    ));

    $wp_customize->add_setting('tutorials_title', array(
        'default'           => 'Tutorials',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('tutorials_title', array(
        'label'    => __('Tutorials Title', 'software-syndicate'),
        'section'  => 'tutorials_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('tutorials_subtitle', array(
        'default'           => 'Leer nieuwe vaardigheden met onze stapsgewijze tutorials en gidsen.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('tutorials_subtitle', array(
        'label'    => __('Tutorials Subtitle', 'software-syndicate'),
        'section'  => 'tutorials_section',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('tutorials_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'software_syndicate_sanitize_image',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'tutorials_hero_image', array(
        'label'          => __('Tutorials Hero Image', 'software-syndicate'),
        'description'    => __('Upload a hero image for the tutorials page', 'software-syndicate'),
        'section'        => 'tutorials_section',
        'mime_type'      => 'image',
    )));
    
    // Join Community Page Settings
    $wp_customize->add_section('join_section', array(
        'title'    => __('Join Community Page', 'software-syndicate'),
        'priority' => 36,
    ));

    $wp_customize->add_setting('events_title', array(
        'default'           => 'Events & Hackathon',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('events_title', array(
        'label'    => __('Events & Hackathon Title', 'software-syndicate'),
        'section'  => 'events_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('events_subtitle', array(
        'default'           => 'Neem deel aan onze community events en hackathons om te leren en te netwerken.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('events_subtitle', array(
        'label'    => __('Events & Hackathon Subtitle', 'software-syndicate'),
        'section'  => 'events_section',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('events_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'software_syndicate_sanitize_image',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'events_hero_image', array(
        'label'          => __('Events & Hackathon Hero Image', 'software-syndicate'),
        'description'    => __('Upload a hero image for the events & hackathon page', 'software-syndicate'),
        'section'        => 'events_section',
        'mime_type'      => 'image',
    )));
    
    // Team Page Settings
    $wp_customize->add_section('team_section', array(
        'title'    => __('Team Page', 'software-syndicate'),
        'priority' => 35,
    ));

    $wp_customize->add_setting('team_title', array(
        'default'           => 'Ons Team',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('team_title', array(
        'label'    => __('Team Title', 'software-syndicate'),
        'section'  => 'team_section',
        'type'     => 'text',
    ));

    $wp_customize->add_setting('team_subtitle', array(
        'default'           => 'Ontmoet de getalenteerde mensen achter onze community.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('team_subtitle', array(
        'label'    => __('Team Subtitle', 'software-syndicate'),
        'section'  => 'team_section',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting('team_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('team_hero_image', array(
        'label'          => __('Team Hero Image', 'software-syndicate'),
        'description'    => __('Upload an image to display in the team hero section.', 'software-syndicate'),
        'section'        => 'team_section',
        'type'          => 'url',
    ));

    // Logo Upload with simple URL input
    $wp_customize->add_setting('site_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('site_logo', array(
        'label'          => __('Site Logo', 'software-syndicate'),
        'description'    => __('Voeg de URL van je logo toe. Upload via Media → Nieuwe media toevoegen, dan kopieer de URL.', 'software-syndicate'),
        'section'        => 'title_tagline',
        'priority'       => 1,
        'type'          => 'text',
    ));
}

// Custom image sanitization function
function software_syndicate_sanitize_image($image, $setting) {
    // Check if image is valid
    if (empty($image)) {
        return '';
    }
    
    // Get attachment ID if it's a URL
    if (is_numeric($image)) {
        $image = wp_get_attachment_url($image);
    }
    
    // Validate URL
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        return '';
    }
    
    // Check if it's an image
    $image_path = parse_url($image, PHP_URL_PATH);
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowed_extensions)) {
        return '';
    }
    
    return esc_url_raw($image);
}

add_action('customize_register', 'software_syndicate_customize_register');

// Handle contact form submission
function software_syndicate_handle_contact_form() {
    if (!isset($_POST['action']) || $_POST['action'] !== 'submit_contact_form') {
        return;
    }
    
    if (!wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
        wp_die(__('Security check failed.', 'software-syndicate'));
    }
    
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $company = sanitize_text_field($_POST['contact_company']);
    $referral = sanitize_text_field($_POST['contact_referral']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }
    
    if (!is_email($email)) {
        wp_redirect(add_query_arg('contact', 'invalid-email', wp_get_referer()));
        exit;
    }
    
    // Get recipient email from customizer or admin email
    $to = get_theme_mod('contact_form_email', get_option('admin_email'));
    
    // Email subject
    $email_subject = sprintf(__('Contact Form Submission: %s', 'software-syndicate'), $subject);
    
    // Email body
    $email_body = sprintf(
        __('Name: %s\nEmail: %s\nSubject: %s', 'software-syndicate'),
        $name,
        $email,
        $subject
    );
    
    if (!empty($phone)) {
        $email_body .= sprintf(__('\nPhone: %s', 'software-syndicate'), $phone);
    }
    
    if (!empty($company)) {
        $email_body .= sprintf(__('\nCompany/Organization: %s', 'software-syndicate'), $company);
    }
    
    if (!empty($referral)) {
        $email_body .= sprintf(__('\nHow did you find us: %s', 'software-syndicate'), $referral);
    }
    
    $email_body .= sprintf(__('\n\nMessage:\n%s', 'software-syndicate'), $message);
    
    // Headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email
    );
    
    // Send email
    $sent = wp_mail($to, $email_subject, $email_body, $headers);
    
    if ($sent) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_submit_contact_form', 'software_syndicate_handle_contact_form');

// Display contact form messages
function software_syndicate_contact_messages() {
    if (isset($_GET['contact'])) {
        $message = '';
        $class = '';
        
        switch ($_GET['contact']) {
            case 'success':
                $message = __('Your message has been sent successfully. We will contact you soon!', 'software-syndicate');
                $class = 'success';
                break;
            case 'error':
                $message = __('There was an error sending your message. Please try again.', 'software-syndicate');
                $class = 'error';
                break;
            case 'invalid-email':
                $message = __('Please enter a valid email address.', 'software-syndicate');
                $class = 'error';
                break;
        }
        
        if ($message) {
            printf('<div class="contact-message %s">%s</div>', esc_attr($class), esc_html($message));
        }
    }
}
add_action('wp_head', function() {
    if (isset($_GET['contact'])) {
        remove_action('wp_head', 'wp_no_robots');
    }
});
?>
