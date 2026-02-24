<?php get_header(); ?>

<main class="site-main">
    <section class="hero-section">
        <div class="container">
            <?php
            // Get customizer settings
            $hero_title = get_theme_mod('hero_title', 'The Software Syndicate');
            $hero_subtitle = get_theme_mod('hero_subtitle', 'Where Developers Build, Learn & Conspire');
            $primary_button_text = get_theme_mod('primary_button_text', 'JOIN THE SYNDICATE');
            $primary_button_url = get_theme_mod('primary_button_url', '#');
            $secondary_button_text = get_theme_mod('secondary_button_text', 'EXPLORE PROJECTS');
            $secondary_button_url = get_theme_mod('secondary_button_url', '#');
            ?>
            
            <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
            
            <div class="hero-buttons">
                <a href="<?php echo esc_url($primary_button_url); ?>" class="btn-primary">
                    <?php echo esc_html($primary_button_text); ?>
                </a>
                <a href="<?php echo esc_url($secondary_button_url); ?>" class="btn-secondary">
                    <?php echo esc_html($secondary_button_text); ?>
                </a>
            </div>
        </div>
    </section>
    
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (get_the_content() || has_post_thumbnail()) : ?>
                <section class="page-content-section">
                    <div class="container">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="page-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="page-content">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'software-syndicate'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
