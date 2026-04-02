<?php
/*
Template Name: Tutorials
Description: Custom tutorials overview page template
*/

get_header();

// Get customizer settings
$tutorials_title = get_theme_mod('tutorials_title', 'Tutorials');
$tutorials_subtitle = get_theme_mod('tutorials_subtitle', 'Leer nieuwe vaardigheden met onze stapsgewijze tutorials en gidsen.');
$tutorials_hero_image = get_theme_mod('tutorials_hero_image', '');

// Handle filtering
$difficulty_filter = isset($_GET['difficulty']) ? sanitize_text_field($_GET['difficulty']) : '';
$time_filter = isset($_GET['time']) ? sanitize_text_field($_GET['time']) : '';
$sort_filter = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_desc';

// Build query args
$args = array(
    'post_type' => 'tutorial',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
);

// Add difficulty filter
if (!empty($difficulty_filter)) {
    $args['meta_query'] = array(
        array(
            'key' => '_tutorial_difficulty',
            'value' => $difficulty_filter,
            'compare' => '='
        )
    );
}

// Add sorting
switch ($sort_filter) {
    case 'date_asc':
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
        break;
    case 'date_desc':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'title_asc':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
    case 'title_desc':
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
        break;
}

// Get tutorials
$tutorials = new WP_Query($args);

// Filter tutorials by time after getting results (since time is stored as text)
if (!empty($time_filter)) {
    $filtered_tutorials = array();
    while ($tutorials->have_posts()) {
        $tutorials->the_post();
        $estimated_time = get_post_meta(get_the_ID(), '_tutorial_estimated_time', true);
        
        // Simple time comparison
        $time_match = false;
        switch ($time_filter) {
            case 'short':
                $time_match = (stripos($estimated_time, '15') !== false || stripos($estimated_time, '30') !== false);
                break;
            case 'medium':
                $time_match = (stripos($estimated_time, '45') !== false || stripos($estimated_time, '60') !== false);
                break;
            case 'long':
                $time_match = (stripos($estimated_time, '90') !== false || stripos($estimated_time, '2') !== false || stripos($estimated_time, '3') !== false);
                break;
        }
        
        if ($time_match) {
            $filtered_tutorials[] = $post;
        }
    }
    
    // Reset and create new query with filtered results
    wp_reset_postdata();
    $tutorials = new WP_Query(array(
        'post_type' => 'tutorial',
        'post__in' => wp_list_pluck($filtered_tutorials, 'ID'),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
}
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="tutorials-hero-section">
        <div class="container">
            <h1 class="tutorials-title"><?php echo esc_html($tutorials_title); ?></h1>
            <p class="tutorials-subtitle"><?php echo esc_html($tutorials_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($tutorials_hero_image)) : ?>
    <section class="tutorials-hero-image-section">
        <div class="container">
            <div class="tutorials-hero-image-wrapper">
                <img src="<?php echo esc_url($tutorials_hero_image); ?>" alt="<?php echo esc_attr($tutorials_title); ?>" class="tutorials-hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Filter Section -->
    <section class="tutorials-filter-section">
        <div class="container">
            <div class="filter-controls">
                <form method="GET" action="<?php echo esc_url(remove_query_arg(array('difficulty', 'time', 'sort'))); ?>" class="filter-form">
                    <div class="filter-group">
                        <label for="difficulty"><?php _e('Moeilijkheidsgraad:', 'software-syndicate'); ?></label>
                        <select name="difficulty" id="difficulty">
                            <option value=""><?php _e('Alle niveaus', 'software-syndicate'); ?></option>
                            <option value="Beginner" <?php selected($difficulty_filter, 'Beginner'); ?>><?php _e('Beginner', 'software-syndicate'); ?></option>
                            <option value="Gevorderd" <?php selected($difficulty_filter, 'Gevorderd'); ?>><?php _e('Gevorderd', 'software-syndicate'); ?></option>
                            <option value="Expert" <?php selected($difficulty_filter, 'Expert'); ?>><?php _e('Expert', 'software-syndicate'); ?></option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="time"><?php _e('Duur:', 'software-syndicate'); ?></label>
                        <select name="time" id="time">
                            <option value=""><?php _e('Alle duur', 'software-syndicate'); ?></option>
                            <option value="short" <?php selected($time_filter, 'short'); ?>><?php _e('Kort (15-30 min)', 'software-syndicate'); ?></option>
                            <option value="medium" <?php selected($time_filter, 'medium'); ?>><?php _e('Medium (45-60 min)', 'software-syndicate'); ?></option>
                            <option value="long" <?php selected($time_filter, 'long'); ?>><?php _e('Lang (90+ min)', 'software-syndicate'); ?></option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sort"><?php _e('Sorteren:', 'software-syndicate'); ?></label>
                        <select name="sort" id="sort">
                            <option value="date_desc" <?php selected($sort_filter, 'date_desc'); ?>><?php _e('Nieuwste eerst', 'software-syndicate'); ?></option>
                            <option value="date_asc" <?php selected($sort_filter, 'date_asc'); ?>><?php _e('Oudste eerst', 'software-syndicate'); ?></option>
                            <option value="title_asc" <?php selected($sort_filter, 'title_asc'); ?>><?php _e('Titel A-Z', 'software-syndicate'); ?></option>
                            <option value="title_desc" <?php selected($sort_filter, 'title_desc'); ?>><?php _e('Titel Z-A', 'software-syndicate'); ?></option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary"><?php _e('Filteren', 'software-syndicate'); ?></button>
                        <a href="<?php echo esc_url(remove_query_arg(array('difficulty', 'time', 'sort'))); ?>" class="btn btn-outline"><?php _e('Reset', 'software-syndicate'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Tutorials Grid Section -->
    <section class="tutorials-grid-section">
        <div class="container">
            <?php if ($tutorials->have_posts()) : ?>
                <div class="tutorials-grid">
                    <?php while ($tutorials->have_posts()) : $tutorials->the_post(); ?>
                        <article class="tutorial-card">
                            <div class="tutorial-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', array('class' => 'tutorial-thumbnail')); ?>
                                    </a>
                                <?php else : ?>
                                    <div class="tutorial-placeholder-image">
                                        <span class="dashicons dashicons-welcome-learn-more"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="tutorial-info">
                                <h3 class="tutorial-name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="tutorial-description">
                                    <?php 
                                    $tutorial_content = get_post_meta(get_the_ID(), '_tutorial_content', true);
                                    if (!empty($tutorial_content)) {
                                        echo wp_trim_words(esc_html($tutorial_content), 20, '...');
                                    } elseif (has_excerpt()) {
                                        echo wp_trim_words(get_the_excerpt(), 20, '...');
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>
                                
                                <div class="tutorial-meta">
                                    <div class="tutorial-date">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                    
                                    <?php 
                                    $difficulty = get_post_meta(get_the_ID(), '_tutorial_difficulty', true);
                                    if ($difficulty) : ?>
                                        <div class="tutorial-difficulty">
                                            <span class="difficulty-badge difficulty-<?php echo esc_attr(strtolower($difficulty)); ?>">
                                                <?php echo esc_html($difficulty); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="tutorial-time">
                                    <?php 
                                    $estimated_time = get_post_meta(get_the_ID(), '_tutorial_estimated_time', true);
                                    if ($estimated_time) : ?>
                                        <div class="time-display">
                                            <span class="dashicons dashicons-clock"></span>
                                            <?php echo esc_html($estimated_time); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="tutorial-action">
                                    <a href="<?php the_permalink(); ?>" class="tutorial-link">
                                        <?php _e('Start Tutorial →', 'software-syndicate'); ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-tutorials">
                    <h2><?php _e('Geen tutorials gevonden', 'software-syndicate'); ?></h2>
                    <p><?php _e('Er zijn geen tutorials gevonden met de geselecteerde filters. Probeer andere filters!', 'software-syndicate'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
