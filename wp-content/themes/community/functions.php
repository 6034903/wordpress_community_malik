<?php
/**
 * The Software Syndicate Theme Functions
 */

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

// Enqueue scripts and styles
function software_syndicate_scripts() {
    wp_enqueue_style('software-syndicate-style', get_stylesheet_uri());
    
    wp_enqueue_style('software-syndicate-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=array');
    
    wp_enqueue_script('software-syndicate-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'software_syndicate_scripts');

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
