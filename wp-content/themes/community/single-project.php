<?php
/**
 * Single Project Template
 * Description: Template for displaying individual project details
 */

get_header();
?>

<main class="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <!-- Project Hero Section -->
        <section class="single-project-hero">
            <div class="container">
                <div class="project-hero-content">
                    <h1 class="project-title"><?php the_title(); ?></h1>
                </div>
            </div>
        </section>

        <?php if (has_post_thumbnail()) : ?>
            <section class="project-image-section">
                <div class="container">
                    <div class="project-hero-image">
                        <?php the_post_thumbnail('large', array('class' => 'project-featured-image')); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Project Content Section -->
        <section class="project-content-section">
            <div class="container">
                <div class="project-content-wrapper">
                    <div class="project-main-content">
                        <?php 
                        // Get custom fields
                        $about_project = get_post_meta(get_the_ID(), '_project_about', true);
                        $tech_stack = get_post_meta(get_the_ID(), '_project_tech_stack', true);
                        $contribution = get_post_meta(get_the_ID(), '_project_contribution', true);
                        $github_repo = get_post_meta(get_the_ID(), '_project_github_repo', true);
                        
                        // Display about project if exists
                        if ($about_project) : ?>
                            <div class="project-detail-section">
                                <h3><?php _e('Over dit project', 'software-syndicate'); ?></h3>
                                <div class="project-description">
                                    <?php echo nl2br(esc_html($about_project)); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="project-detail-section">
                                <h3><?php _e('Over dit project', 'software-syndicate'); ?></h3>
                                <div class="project-description">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($tech_stack || $contribution || $github_repo) : ?>
                            <div class="project-details">
                                <?php if ($tech_stack) : ?>
                                    <div class="project-detail-section">
                                        <h3><?php _e('Tech Stack', 'software-syndicate'); ?></h3>
                                        <div class="tech-stack-list">
                                            <?php 
                                            // Split tech stack by comma and create badges
                                            $techs = array_map('trim', explode(',', $tech_stack));
                                            foreach ($techs as $tech) : ?>
                                                <span class="tech-badge">
                                                    <?php echo software_syndicate_get_tech_icon($tech); ?>
                                                    <?php echo esc_html($tech); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <?php if ($github_repo) : ?>
                                            <h3><?php _e('GitHub Intergratie', 'software-syndicate'); ?></h3>
                                            <div class="project-link-buttons">
                                                <a href="<?php echo esc_url($github_repo); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                                                    <?php _e('Bekijk op GitHub', 'software-syndicate'); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($contribution) : ?>
                                    <div class="project-detail-section">
                                        <h3><?php _e('Hoe kunnen lezers bijdragen aan dit project?', 'software-syndicate'); ?></h3>
                                        <div class="contribution-content">
                                            <?php echo nl2br(esc_html($contribution)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Project Navigation -->
        <section class="project-navigation">
            <div class="container">
                <nav class="project-nav">
                    <div class="nav-prev">
                        <?php previous_post_link('%link', '&larr; %title'); ?>
                    </div>
                    <div class="nav-back">
                        <a href="<?php echo get_post_type_archive_link('project'); ?>" class="btn btn-outline">
                            <?php _e('Alle Projecten', 'software-syndicate'); ?>
                        </a>
                    </div>
                    <div class="nav-next">
                        <?php next_post_link('%link', '%title &rarr;'); ?>
                    </div>
                </nav>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
