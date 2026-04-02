<?php
/*
Template Name: Team
Description: Custom team page template
*/

get_header();

// Get customizer settings
$team_title = get_theme_mod('team_title', 'Ons Team');
$team_subtitle = get_theme_mod('team_subtitle', 'Ontmoet de getalenteerde mensen achter onze community.');
$team_hero_image = get_theme_mod('team_hero_image', '');

// Get team members
$team_members = new WP_Query(array(
    'post_type' => 'team_member',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
));
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="team-hero-section">
        <div class="container">
            <h1 class="team-title"><?php echo esc_html($team_title); ?></h1>
            <p class="team-subtitle"><?php echo esc_html($team_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($team_hero_image)) : ?>
    <section class="team-hero-image-section">
        <div class="container">
            <div class="team-hero-image-wrapper">
                <img src="<?php echo esc_url($team_hero_image); ?>" alt="<?php echo esc_attr($team_title); ?>" class="team-hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Team Grid Section -->
    <section class="team-grid-section">
        <div class="container">
            <?php if ($team_members->have_posts()) : ?>
                <div class="team-grid">
                    <?php while ($team_members->have_posts()) : $team_members->the_post(); ?>
                        <article class="team-member-card">
                            <div class="team-member-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'team-member-photo')); ?>
                                <?php else : ?>
                                    <div class="team-member-placeholder-image">
                                        <span class="dashicons dashicons-admin-users"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="team-member-info">
                                <h3 class="team-member-name"><?php the_title(); ?></h3>
                                
                                <?php 
                                $team_role = get_post_meta(get_the_ID(), '_team_role', true);
                                if ($team_role) : ?>
                                    <div class="team-member-role">
                                        <?php echo esc_html($team_role); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php 
                                $team_bio = get_post_meta(get_the_ID(), '_team_bio', true);
                                if ($team_bio) : ?>
                                    <div class="team-member-bio">
                                        <?php echo esc_html($team_bio); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-member-socials">
                                    <?php 
                                    $team_email = get_post_meta(get_the_ID(), '_team_email', true);
                                    $team_linkedin = get_post_meta(get_the_ID(), '_team_linkedin', true);
                                    $team_twitter = get_post_meta(get_the_ID(), '_team_twitter', true);
                                    $team_github = get_post_meta(get_the_ID(), '_team_github', true);
                                    ?>
                                    
                                    <?php if ($team_email) : ?>
                                        <a href="mailto:<?php echo esc_attr($team_email); ?>" class="social-link email-link" title="Email">
                                            <span class="dashicons dashicons-email-alt"></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($team_linkedin) : ?>
                                        <a href="<?php echo esc_url($team_linkedin); ?>" class="social-link linkedin-link" title="LinkedIn" target="_blank">
                                            <span class="dashicons dashicons-linkedin"></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($team_twitter) : ?>
                                        <a href="<?php echo esc_url($team_twitter); ?>" class="social-link twitter-link" title="Twitter" target="_blank">
                                            <span class="dashicons dashicons-twitter"></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($team_github) : ?>
                                        <a href="<?php echo esc_url($team_github); ?>" class="social-link github-link" title="GitHub" target="_blank">
                                            <span class="dashicons dashicons-admin-generic"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-team-members">
                    <h2><?php _e('Geen teamleden gevonden', 'software-syndicate'); ?></h2>
                    <p><?php _e('Er zijn nog geen teamleden toegevoegd. Voeg teamleden toe via de WordPress admin.', 'software-syndicate'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
