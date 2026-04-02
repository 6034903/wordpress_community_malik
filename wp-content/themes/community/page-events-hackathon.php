<?php
/*
Template Name: Events & Hackathon
Description: Custom events and hackathon overview page template
*/

get_header();

// Get customizer settings
$events_title = get_theme_mod('events_title', 'Events & Hackathon');
$events_subtitle = get_theme_mod('events_subtitle', 'Neem deel aan onze community events en hackathons om te leren en te netwerken.');
$events_hero_image = get_theme_mod('events_hero_image', '');

// Get events (combined with hackathons)
$events = new WP_Query(array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
));

// Handle filtering
$type_filter = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$sort_filter = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_desc';

// Build query args
$args = array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
);

// Add type filter (using meta)
if (!empty($type_filter)) {
    $args['meta_query'] = array(
        array(
            'key' => '_event_type',
            'value' => $type_filter,
            'compare' => '='
        )
    );
}

// Add status filter
if (!empty($status_filter)) {
    if (!isset($args['meta_query'])) {
        $args['meta_query'] = array();
    }
    $args['meta_query'][] = array(
        'key' => '_event_status',
        'value' => $status_filter,
        'compare' => '='
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

// Get filtered events
$events = new WP_Query($args);
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="events-hero-section">
        <div class="container">
            <h1 class="events-title"><?php echo esc_html($events_title); ?></h1>
            <p class="events-subtitle"><?php echo esc_html($events_subtitle); ?></p>
        </div>
    </section>

    <!-- Hero Image Section -->
    <?php if (!empty($events_hero_image)) : ?>
    <section class="events-hero-image-section">
        <div class="container">
            <div class="events-hero-image-wrapper">
                <img src="<?php echo esc_url($events_hero_image); ?>" alt="<?php echo esc_attr($events_title); ?>" class="events-hero-image">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Filter Section -->
    <section class="events-filter-section">
        <div class="container">
            <div class="filter-controls">
                <form method="GET" action="<?php echo esc_url(remove_query_arg(array('type', 'status', 'sort'))); ?>" class="filter-form">
                    <div class="filter-group">
                        <label for="type"><?php _e('Type:', 'software-syndicate'); ?></label>
                        <select name="type" id="type">
                            <option value=""><?php _e('Alle types', 'software-syndicate'); ?></option>
                            <option value="event" <?php selected($type_filter, 'event'); ?>><?php _e('Events', 'software-syndicate'); ?></option>
                            <option value="hackathon" <?php selected($type_filter, 'hackathon'); ?>><?php _e('Hackathons', 'software-syndicate'); ?></option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status"><?php _e('Status:', 'software-syndicate'); ?></label>
                        <select name="status" id="status">
                            <option value=""><?php _e('Alle status', 'software-syndicate'); ?></option>
                            <option value="upcoming" <?php selected($status_filter, 'upcoming'); ?>><?php _e('Aankomend', 'software-syndicate'); ?></option>
                            <option value="ongoing" <?php selected($status_filter, 'ongoing'); ?>><?php _e('Bezig', 'software-syndicate'); ?></option>
                            <option value="completed" <?php selected($status_filter, 'completed'); ?>><?php _e('Afgerond', 'software-syndicate'); ?></option>
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
                        <a href="<?php echo esc_url(remove_query_arg(array('type', 'status', 'sort'))); ?>" class="btn btn-outline"><?php _e('Reset', 'software-syndicate'); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Events Grid Section -->
    <section class="events-grid-section">
        <div class="container">
            <?php if ($events->have_posts()) : ?>
                <div class="events-grid">
                    <?php while ($events->have_posts()) : $events->the_post(); ?>
                        <article class="event-card">
                            <div class="event-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'event-thumbnail')); ?>
                                <?php else : ?>
                                    <div class="event-placeholder-image">
                                        <span class="dashicons dashicons-calendar-alt"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="event-info">
                                <div class="event-type-badge">
                                    <?php 
                                    $event_type = get_post_meta(get_the_ID(), '_event_type', true);
                                    if ($event_type === 'hackathon') {
                                        echo '<span class="badge badge-hackathon">Hackathon</span>';
                                    } else {
                                        echo '<span class="badge badge-event">Event</span>';
                                    }
                                    ?>
                                </div>
                                
                                <h3 class="event-name">
                                    <?php the_title(); ?>
                                </h3>
                                
                                <div class="event-description">
                                    <?php 
                                    $event_description = get_post_meta(get_the_ID(), '_event_description', true);
                                    if (!empty($event_description)) {
                                        echo esc_html($event_description);
                                    } elseif (has_excerpt()) {
                                        echo wp_trim_words(get_the_excerpt(), 20, '...');
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>
                                
                                <div class="event-meta">
                                    <div class="event-date">
                                        <span class="dashicons dashicons-calendar"></span>
                                        <?php echo get_the_date(); ?>
                                    </div>
                                    
                                    <?php 
                                    $event_status = get_post_meta(get_the_ID(), '_event_status', true);
                                    if ($event_status) : ?>
                                        <div class="event-status">
                                            <span class="status-badge status-<?php echo esc_attr(strtolower($event_status)); ?>">
                                                <?php 
                                                switch ($event_status) {
                                                    case 'upcoming':
                                                        echo 'Aankomend';
                                                        break;
                                                    case 'ongoing':
                                                        echo 'Bezig';
                                                        break;
                                                    case 'completed':
                                                        echo 'Afgerond';
                                                        break;
                                                    default:
                                                        echo esc_html($event_status);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php 
                                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                                $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                                $event_duration = get_post_meta(get_the_ID(), '_event_duration', true);
                                $event_prizes = get_post_meta(get_the_ID(), '_event_prizes', true);
                                $event_type = get_post_meta(get_the_ID(), '_event_type', true);
                                ?>
                                
                                <?php if ($event_date || $event_location || ($event_duration && $event_type === 'hackathon') || ($event_prizes && $event_type === 'hackathon')) : ?>
                                    <div class="event-details-row">
                                        <?php if ($event_date) : ?>
                                            <div class="event-detail-item">
                                                <span class="dashicons dashicons-clock"></span>
                                                <?php 
                                                // Format datetime-local to show only time
                                                $datetime = new DateTime($event_date);
                                                echo $datetime->format('H:i');
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($event_location) : ?>
                                            <div class="event-detail-item">
                                                <span class="dashicons dashicons-location"></span>
                                                <?php echo esc_html($event_location); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($event_duration && $event_type === 'hackathon') : ?>
                                            <div class="event-detail-item">
                                                <span class="dashicons dashicons-clock"></span>
                                                <?php echo esc_html($event_duration); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($event_prizes && $event_type === 'hackathon') : ?>
                                            <div class="event-detail-item">
                                                <span class="dashicons dashicons-star-filled"></span>
                                                <?php echo esc_html($event_prizes); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="no-events">
                    <h2><?php _e('Geen events gevonden', 'software-syndicate'); ?></h2>
                    <p><?php _e('Er zijn geen events of hackathons gevonden met de geselecteerde filters. Probeer andere filters!', 'software-syndicate'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
