<?php
/*
Template Name: Join Community
Description: Join community application form template
*/

get_header();

// Get customizer settings
$join_title = get_theme_mod('join_title', 'Join The Syndicate');
$join_subtitle = get_theme_mod('join_subtitle', 'Word lid van onze community en bouw samen met ons aan geweldige projecten.');
$join_hero_image = get_theme_mod('join_hero_image', '');

// Handle form submission
$submission_message = '';
if (isset($_POST['submit_application']) && wp_verify_nonce($_POST['application_nonce'], 'join_community_nonce')) {
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $age = intval($_POST['age']);
    $location = sanitize_text_field($_POST['location']);
    $experience = sanitize_textarea_field($_POST['experience']);
    $motivation = sanitize_textarea_field($_POST['motivation']);
    $skills = sanitize_textarea_field($_POST['skills']);
    $availability = sanitize_textarea_field($_POST['availability']);
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($location) || empty($experience) || empty($motivation)) {
        $submission_message = '<div class="error-message">Vul alle verplichte velden in.</div>';
    } elseif (!is_email($email)) {
        $submission_message = '<div class="error-message">Voer een geldig e-mailadres in.</div>';
    } elseif ($age < 16 || $age > 100) {
        $submission_message = '<div class="error-message">Leeftijd moet tussen 16 en 100 jaar zijn.</div>';
    } else {
        // Create application post
        $application_post = array(
            'post_title'    => 'Application: ' . $name,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'application',
        );
        
        $post_id = wp_insert_post($application_post, true); // Add error handling
        
        if ($post_id && !is_wp_error($post_id)) {
            // Save meta fields
            update_post_meta($post_id, '_application_name', $name);
            update_post_meta($post_id, '_application_email', $email);
            update_post_meta($post_id, '_application_phone', $phone);
            update_post_meta($post_id, '_application_age', $age);
            update_post_meta($post_id, '_application_location', $location);
            update_post_meta($post_id, '_application_experience', $experience);
            update_post_meta($post_id, '_application_motivation', $motivation);
            update_post_meta($post_id, '_application_skills', $skills);
            update_post_meta($post_id, '_application_availability', $availability);
            update_post_meta($post_id, '_application_date', current_time('Y-m-d H:i:s'));
            update_post_meta($post_id, '_application_status', 'pending');
            
            // Send notification email to admin
            $to = get_option('admin_email');
            $subject = 'Nieuwe Community Application: ' . $name;
            $message = "Er is een nieuwe community application ontvangen:\n\n";
            $message .= "Naam: $name\n";
            $message .= "Email: $email\n";
            $message .= "Telefoon: $phone\n";
            $message .= "Leeftijd: $age\n";
            $message .= "Locatie: $location\n";
            $message .= "Aanvraagdatum: " . current_time('Y-m-d H:i:s') . "\n\n";
            $message .= "Bekijk de volledige applicatie in de WordPress admin onder Applications.";
            
            wp_mail($to, $subject, $message);
            
            $submission_message = '<div class="success-message">Je applicatie is succesvol ingediend! We nemen zo snel mogelijk contact met je op.</div>';
        } else {
            // Debug: Show error if post creation failed
            $error_message = is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error';
            $submission_message = '<div class="error-message">Er is een fout opgetreden bij het indienen van je applicatie. Fout: ' . esc_html($error_message) . '</div>';
        }
    }
} // Removed the extra closing bracket here

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
    <section class="join-hero-section">
        <div class="container">
            <h1 class="join-title"><?php echo esc_html($join_title); ?></h1>
            <p class="join-subtitle"><?php echo esc_html($join_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($join_hero_image)) : ?>
    <section class="join-hero-image-section">
        <div class="container">
            <div class="join-hero-image-wrapper">
                <img src="<?php echo esc_url($join_hero_image); ?>" alt="<?php echo esc_attr($join_title); ?>" class="join-hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Application Form Section -->
    <section class="application-form-section">
        <div class="container">
            <div class="form-container">
                <h2>Community Application Form</h2>
                <p>Vul het onderstaande formulier in om lid te worden van onze community. We nemen je applicatie serieus en nemen zo snel mogelijk contact met je op.</p>
                
                <?php echo $submission_message; ?>
                
                <form method="post" class="application-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                    <?php wp_nonce_field('join_community_nonce', 'application_nonce'); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Volledige Naam *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Telefoon *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="age">Leeftijd *</label>
                            <input type="number" id="age" name="age" min="16" max="100" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Locatie *</label>
                        <input type="text" id="location" name="location" placeholder="Stad, Land" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="experience">Ervaring *</label>
                        <textarea id="experience" name="experience" rows="4" placeholder="Beschrijf je ervaring met programmeren, design, of andere relevante vaardigheden..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="motivation">Motivatie *</label>
                        <textarea id="motivation" name="motivation" rows="4" placeholder="Waarom wil je lid worden van onze community? Wat hoop je te bereiken?" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="skills">Vaardigheden</label>
                        <textarea id="skills" name="skills" rows="4" placeholder="Welke programmeertalen, tools, of vaardigheden beheers je? (bv. HTML, CSS, JavaScript, PHP, Python, Figma, etc.)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="availability">Beschikbaarheid</label>
                        <textarea id="availability" name="availability" rows="4" placeholder="Hoeveel tijd kun je besteden aan community projecten? (bv. 5 uur per week, alleen weekends, etc.)"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="submit_application" class="submit-button">Indienen Application</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Status Check Section -->
    <section class="status-check-section">
        <div class="container">
            <div class="status-container">
                <h2>Controleer je Application Status</h2>
                <p>Voer je e-mailadres in om de status van je community application te bekijken.</p>
                
                <?php echo $status_result; ?>
                
                <form method="post" class="status-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
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
