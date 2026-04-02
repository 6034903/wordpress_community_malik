<?php
/*
Template Name: Check Status
Description: Check application status template
*/

get_header();

// Get customizer settings
$status_title = get_theme_mod('status_title', 'Check Application Status');
$status_subtitle = get_theme_mod('status_subtitle', 'Controleer de status van je community application.');

// Handle status check
$status_result = '';
if (isset($_POST['check_status']) && wp_verify_nonce($_POST['status_nonce'], 'check_status_nonce')) {
    $email = sanitize_email($_POST['email']);
    
    if (empty($email) || !is_email($email)) {
        $status_result = '<div class="error-message">Voer een geldig e-mailadres in.</div>';
    } else {
        // Search for application by email
        $applications = get_posts(array(
            'post_type'      => 'application',
            'posts_per_page' => -1,
            'meta_key'       => '_application_email',
            'meta_value'     => $email,
        ));
        
        if ($applications) {
            $status_result = '<div class="status-results">';
            foreach ($applications as $app) {
                $application_status = get_post_meta($app->ID, '_application_status', true);
                $application_name = get_post_meta($app->ID, '_application_name', true);
                $application_date = get_post_meta($app->ID, '_application_date', true);
                $admin_notes = get_post_meta($app->ID, '_admin_notes', true);
                
                $status_result .= '<div class="status-card">';
                $status_result .= '<h3>Application: ' . esc_html($application_name) . '</h3>';
                $status_result .= '<p><strong>Aanvraagdatum:</strong> ' . esc_html($application_date) . '</p>';
                
                $status_result .= '<div class="status-badge status-' . esc_attr($application_status) . '">';
                switch ($application_status) {
                    case 'pending':
                        $status_result .= 'In behandeling';
                        break;
                    case 'accepted':
                        $status_result .= 'Geaccepteerd';
                        break;
                    case 'denied':
                        $status_result .= 'Geweigerd';
                        break;
                    default:
                        $status_result .= 'Onbekend';
                }
                $status_result .= '</div>';
                
                if (!empty($admin_notes)) {
                    $status_result .= '<p><strong>Admin Notities:</strong> ' . esc_html($admin_notes) . '</p>';
                }
                
                $status_result .= '</div>';
            }
            $status_result .= '</div>';
        } else {
            $status_result = '<div class="error-message">Geen applicatie gevonden voor dit e-mailadres.</div>';
        }
    }
}
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="status-hero-section">
        <div class="container">
            <h1 class="status-title"><?php echo esc_html($status_title); ?></h1>
            <p class="status-subtitle"><?php echo esc_html($status_subtitle); ?></p>
        </div>
    </section>

    <!-- Status Check Section -->
    <section class="status-check-section">
        <div class="container">
            <div class="status-container">
                <h2>Controleer je Application Status</h2>
                <p>Voer je e-mailadres in om de status van je community application te bekijken.</p>
                
                <?php echo $status_result; ?>
                
                <form method="post" class="status-form">
                    <?php wp_nonce_field('check_status_nonce', 'status_nonce'); ?>
                    
                    <div class="form-group">
                        <label for="email">Email Adres</label>
                        <input type="email" id="email" name="email" placeholder="jouw@email.com" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="check_status" class="submit-button">Controleer Status</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
