<?php
/*
Template Name: Contact Page
*/
get_header(); ?>

<main class="site-main">
    <?php
    // Display contact form messages
    if (function_exists('software_syndicate_contact_messages')) {
        add_action('software_syndicate_before_contact_form', 'software_syndicate_contact_messages');
    }
    do_action('software_syndicate_before_contact_form');
    ?>
    
    <section class="contact-hero-section">
        <div class="container">
            <?php
            // Get customizer settings
            $contact_title = get_theme_mod('contact_title', 'Neem Contact Op');
            $contact_subtitle = get_theme_mod('contact_subtitle', 'Heb je vragen, suggesties of wil je samenwerken? Vul het onderstaande formulier in en we nemen zo snel mogelijk contact met je op.');
            $contact_form_email = get_theme_mod('contact_form_email', get_option('admin_email'));
            
            // Form labels and placeholders
            $name_label = get_theme_mod('contact_name_label', 'Naam:');
            $name_placeholder = get_theme_mod('contact_name_placeholder', 'Jouw naam');
            $email_label = get_theme_mod('contact_email_label', 'E-mail:');
            $email_placeholder = get_theme_mod('contact_email_placeholder', 'jouw@email.com');
            $subject_label = get_theme_mod('contact_subject_label', 'Onderwerp:');
            $subject_placeholder = get_theme_mod('contact_subject_placeholder', 'Waar gaat je vraag over?');
            $phone_label = get_theme_mod('contact_phone_label', 'Telefoon:');
            $phone_placeholder = get_theme_mod('contact_phone_placeholder', 'Jouw telefoonnummer');
            $company_label = get_theme_mod('contact_company_label', 'Bedrijf/Organisatie:');
            $company_placeholder = get_theme_mod('contact_company_placeholder', 'Naam van je bedrijf of organisatie');
            $referral_label = get_theme_mod('contact_referral_label', 'Hoe heb je ons gevonden?');
            $referral_placeholder = get_theme_mod('contact_referral_placeholder', 'bijv. Google, social media, via een vriend');
            $message_label = get_theme_mod('contact_message_label', 'Bericht:');
            $message_placeholder = get_theme_mod('contact_message_placeholder', 'Typ hier je bericht...');
            $submit_button_text = get_theme_mod('contact_submit_button_text', 'Verstuur Bericht');
            ?>
            
            <h1 class="contact-title"><?php echo esc_html($contact_title); ?></h1>
            <p class="contact-subtitle"><?php echo esc_html($contact_subtitle); ?></p>
        </div>
    </section>
    
    <section class="contact-form-section">
        <div class="container">
            <div class="contact-form-container">
                <?php
                // Check if Contact Form 7 plugin is active
                if (function_exists('wpcf7_contact_form')) {
                    $contact_form_id = get_theme_mod('contact_form_id', '');
                    if ($contact_form_id) {
                        echo do_shortcode('[contact-form-7 id="' . esc_attr($contact_form_id) . '" title="Contact"]');
                    }
                } else {
                    // Fallback to built-in contact form
                    ?>
                    <form class="custom-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="submit_contact_form">
                        <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
                        
                        <div class="form-group">
                            <label for="contact-name"><?php echo esc_html($name_label); ?> *</label>
                            <input type="text" id="contact-name" name="contact_name" required placeholder="<?php echo esc_attr($name_placeholder); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-email"><?php echo esc_html($email_label); ?> *</label>
                            <input type="email" id="contact-email" name="contact_email" required placeholder="<?php echo esc_attr($email_placeholder); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-subject"><?php echo esc_html($subject_label); ?> *</label>
                            <input type="text" id="contact-subject" name="contact_subject" required placeholder="<?php echo esc_attr($subject_placeholder); ?>">
                        </div>
                        
                        <?php if (!empty($phone_label)) : ?>
                        <div class="form-group">
                            <label for="contact-phone"><?php echo esc_html($phone_label); ?></label>
                            <input type="tel" id="contact-phone" name="contact_phone" placeholder="<?php echo esc_attr($phone_placeholder); ?>">
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($company_label)) : ?>
                        <div class="form-group">
                            <label for="contact-company"><?php echo esc_html($company_label); ?></label>
                            <input type="text" id="contact-company" name="contact_company" placeholder="<?php echo esc_attr($company_placeholder); ?>">
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($referral_label)) : ?>
                        <div class="form-group">
                            <label for="contact-referral"><?php echo esc_html($referral_label); ?></label>
                            <input type="text" id="contact-referral" name="contact_referral" placeholder="<?php echo esc_attr($referral_placeholder); ?>">
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="contact-message"><?php echo esc_html($message_label); ?> *</label>
                            <textarea id="contact-message" name="contact_message" required placeholder="<?php echo esc_attr($message_placeholder); ?>" rows="6"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="submit-btn"><?php echo esc_html($submit_button_text); ?></button>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
    
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (get_the_content()) : ?>
                <section class="page-content-section">
                    <div class="container">
                        <div class="page-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
