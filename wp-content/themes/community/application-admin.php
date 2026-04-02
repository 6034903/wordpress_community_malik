<?php
/*
Plugin Name: Application Admin Interface
Description: Custom admin interface for community applications
*/

// Add admin menu page for applications
function software_syndicate_add_applications_admin_page() {
    add_menu_page(
        'Applications Management',
        'Applications',
        'manage_options',
        'applications-management',
        'software_syndicate_applications_admin_page',
        'dashicons-groups',
        30
    );
}
add_action('admin_menu', 'software_syndicate_add_applications_admin_page');

// Applications admin page callback
function software_syndicate_applications_admin_page() {
    ?>
    <div class="wrap">
        <h1>Community Applications</h1>
        
        <?php
        // Handle status update
        if (isset($_POST['action']) && isset($_POST['application_id']) && check_admin_referer('update_application_status')) {
            $application_id = intval($_POST['application_id']);
            $new_status = sanitize_text_field($_POST['new_status']);
            $admin_notes = sanitize_textarea_field($_POST['admin_notes']);
            
            if (in_array($new_status, ['accepted', 'denied'])) {
                update_post_meta($application_id, '_application_status', $new_status);
                if (!empty($admin_notes)) {
                    update_post_meta($application_id, '_admin_notes', $admin_notes);
                }
                
                // Send email to applicant
                $application_name = get_post_meta($application_id, '_application_name', true);
                $application_email = get_post_meta($application_id, '_application_email', true);
                
                $subject = $new_status === 'accepted' ? 'Je applicatie is geaccepteerd!' : 'Je applicatie is geweigerd';
                $message = $new_status === 'accepted' 
                    ? "Beste $application_name,\n\nGefeliciteerd! Je applicatie voor onze community is geaccepteerd. We nemen zo snel mogelijk contact met je op voor de volgende stappen.\n\nMet vriendelijke groet,\nThe Syndicate Team"
                    : "Beste $application_name,\n\nHelaas is je applicatie voor onze community op dit moment niet geaccepteerd. We bedanken je voor je interesse.\n\nMet vriendelijke groet,\nThe Syndicate Team";
                
                wp_mail($application_email, $subject, $message);
                
                echo '<div class="notice notice-success is-dismissible"><p>Application status bijgewerkt en email verstuurd!</p></div>';
            }
        }
        
        // Get all applications
        $applications = get_posts(array(
            'post_type'      => 'application',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        ));
        
        if ($applications) {
            echo '<div class="applications-grid">';
            
            foreach ($applications as $app) {
                $application_name = get_post_meta($app->ID, '_application_name', true);
                $application_email = get_post_meta($app->ID, '_application_email', true);
                $application_phone = get_post_meta($app->ID, '_application_phone', true);
                $application_age = get_post_meta($app->ID, '_application_age', true);
                $application_location = get_post_meta($app->ID, '_application_location', true);
                $application_experience = get_post_meta($app->ID, '_application_experience', true);
                $application_motivation = get_post_meta($app->ID, '_application_motivation', true);
                $application_skills = get_post_meta($app->ID, '_application_skills', true);
                $application_availability = get_post_meta($app->ID, '_application_availability', true);
                $application_date = get_post_meta($app->ID, '_application_date', true);
                $application_status = get_post_meta($app->ID, '_application_status', true);
                $admin_notes = get_post_meta($app->ID, '_admin_notes', true);
                
                $status_class = $application_status === 'accepted' ? 'accepted' : ($application_status === 'denied' ? 'denied' : 'pending');
                $status_text = $application_status === 'accepted' ? 'Geaccepteerd' : ($application_status === 'denied' ? 'Geweigerd' : 'In behandeling');
                
                echo '<div class="application-card ' . $status_class . '">';
                echo '<div class="application-header">';
                echo '<h3>' . esc_html($application_name) . '</h3>';
                echo '<span class="status-badge">' . esc_html($status_text) . '</span>';
                echo '</div>';
                
                echo '<div class="application-details">';
                echo '<p><strong>Email:</strong> ' . esc_html($application_email) . '</p>';
                echo '<p><strong>Telefoon:</strong> ' . esc_html($application_phone) . '</p>';
                echo '<p><strong>Leeftijd:</strong> ' . esc_html($application_age) . '</p>';
                echo '<p><strong>Locatie:</strong> ' . esc_html($application_location) . '</p>';
                echo '<p><strong>Aanvraagdatum:</strong> ' . esc_html($application_date) . '</p>';
                
                if (!empty($application_experience)) {
                    echo '<div class="detail-section">';
                    echo '<h4>Ervaring</h4>';
                    echo '<p>' . esc_html($application_experience) . '</p>';
                    echo '</div>';
                }
                
                if (!empty($application_motivation)) {
                    echo '<div class="detail-section">';
                    echo '<h4>Motivatie</h4>';
                    echo '<p>' . esc_html($application_motivation) . '</p>';
                    echo '</div>';
                }
                
                if (!empty($application_skills)) {
                    echo '<div class="detail-section">';
                    echo '<h4>Vaardigheden</h4>';
                    echo '<p>' . esc_html($application_skills) . '</p>';
                    echo '</div>';
                }
                
                if (!empty($application_availability)) {
                    echo '<div class="detail-section">';
                    echo '<h4>Beschikbaarheid</h4>';
                    echo '<p>' . esc_html($application_availability) . '</p>';
                    echo '</div>';
                }
                
                if (!empty($admin_notes)) {
                    echo '<div class="detail-section">';
                    echo '<h4>Admin Notities</h4>';
                    echo '<p>' . esc_html($admin_notes) . '</p>';
                    echo '</div>';
                }
                
                echo '</div>';
                
                if ($application_status === 'pending') {
                    echo '<div class="application-actions">';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="application_id" value="' . $app->ID . '">';
                    echo '<input type="hidden" name="new_status" value="accepted">';
                    echo '<input type="hidden" name="admin_notes" value="">';
                    wp_nonce_field('update_application_status');
                    echo '<button type="submit" name="action" class="button button-primary accept-btn">Accept</button>';
                    echo '</form>';
                    
                    echo '<form method="post">';
                    echo '<input type="hidden" name="application_id" value="' . $app->ID . '">';
                    echo '<input type="hidden" name="new_status" value="denied">';
                    echo '<textarea name="admin_notes" placeholder="Reden van weigering..." style="width: 100%; margin-bottom: 10px;"></textarea>';
                    wp_nonce_field('update_application_status');
                    echo '<button type="submit" name="action" class="button button-secondary deny-btn">Deny</button>';
                    echo '</form>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo '<p>Geen applicaties gevonden.</p>';
        }
        ?>
    </div>
    
    <style>
        .applications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .application-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .application-card.pending {
            border-left: 4px solid #f59e0b;
        }
        
        .application-card.accepted {
            border-left: 4px solid #10b981;
        }
        
        .application-card.denied {
            border-left: 4px solid #ef4444;
        }
        
        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .application-header h3 {
            margin: 0;
            font-size: 18px;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .pending .status-badge {
            background: #f59e0b;
            color: white;
        }
        
        .accepted .status-badge {
            background: #10b981;
            color: white;
        }
        
        .denied .status-badge {
            background: #ef4444;
            color: white;
        }
        
        .application-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .detail-section {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .detail-section h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }
        
        .application-actions {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }
        
        .application-actions form {
            flex: 1;
        }
        
        .accept-btn {
            background: #10b981 !important;
            border-color: #10b981 !important;
            width: 100%;
        }
        
        .deny-btn {
            background: #ef4444 !important;
            border-color: #ef4444 !important;
            width: 100%;
        }
    </style>
    <?php
}

// Hide the original applications menu
function software_syndicate_hide_original_applications_menu() {
    remove_submenu_page('edit.php?post_type=application', 'post-new.php?post_type=application');
}
add_action('admin_menu', 'software_syndicate_hide_original_applications_menu', 999);
?>
