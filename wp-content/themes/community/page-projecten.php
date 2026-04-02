<?php
/*
Template Name: Projecten
Description: Custom projects overview page template
*/

// Debug: Ensure this template is loaded
// error_log('Loading page-projecten.php template for page: ' . get_queried_object()->post_name);

get_header();

// Get customizer settings
$projects_title = get_theme_mod('projects_title', 'Projecten');
$projects_subtitle = get_theme_mod('projects_subtitle', 'Ontdek onze innovatieve projecten en oplossingen die we hebben ontwikkeld voor onze klanten en community.');
$projects_hero_image = get_theme_mod('projects_hero_image', '');

// Get projects
$projects = new WP_Query(array(
    'post_type' => 'project',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="projects-hero-section">
        <div class="container">
            <h1 class="projects-title"><?php echo esc_html($projects_title); ?></h1>
            <p class="projects-subtitle"><?php echo esc_html($projects_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($projects_hero_image)) : ?>
    <section class="projects-hero-image-section">
        <div class="container">
            <div class="projects-hero-image-wrapper">
                <img src="<?php echo esc_url($projects_hero_image); ?>" alt="<?php echo esc_attr($projects_title); ?>" class="projects-hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Projects Grid Section -->
    <section class="projects-grid-section">
        <div class="container">
            <?php if ($projects->have_posts()) : ?>
                <div class="projects-grid">
                    <?php while ($projects->have_posts()) : $projects->the_post(); ?>
                        <article class="project-card">
                            <div class="project-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', array('class' => 'project-thumbnail')); ?>
                                    </a>
                                <?php else : ?>
                                    <div class="project-placeholder-image">
                                        <span class="dashicons dashicons-portfolio"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="project-info">
                                <h3 class="project-name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="project-description">
                                    <?php 
                                    if (has_excerpt()) {
                                        echo wp_trim_words(get_the_excerpt(), 20, '...');
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>
                                
                                <div class="project-meta">
                                    <div class="project-date">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                                
                                <div class="project-action">
                                    <a href="<?php the_permalink(); ?>" class="project-link">
                                        <?php _e('Bekijk Project →', 'software-syndicate'); ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-projects">
                    <h2><?php _e('Nog geen projecten', 'software-syndicate'); ?></h2>
                    <p><?php _e('Er zijn momenteel geen projecten beschikbaar. Kom later terug!', 'software-syndicate'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
