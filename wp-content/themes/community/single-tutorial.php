<?php
/**
 * Single Tutorial Template
 * Description: Template for displaying individual tutorial details
 */

get_header();
?>

<main class="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <!-- Tutorial Hero Section -->
        <section class="single-tutorial-hero">
            <div class="container">
                <div class="tutorial-hero-content">
                    <h1 class="tutorial-title"><?php the_title(); ?></h1>
                </div>
            </div>
        </section>

        <?php if (has_post_thumbnail()) : ?>
            <section class="tutorial-image-section">
                <div class="container">
                    <div class="tutorial-hero-image">
                        <?php the_post_thumbnail('large', array('class' => 'tutorial-featured-image')); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Tutorial Content Section -->
        <section class="tutorial-content-section">
            <div class="container">
                <div class="tutorial-content-wrapper">
                    <div class="tutorial-main-content">
                        <?php 
                        // Get custom fields
                        $tutorial_content = get_post_meta(get_the_ID(), '_tutorial_content', true);
                        $difficulty = get_post_meta(get_the_ID(), '_tutorial_difficulty', true);
                        $estimated_time = get_post_meta(get_the_ID(), '_tutorial_estimated_time', true);
                        $prerequisites = get_post_meta(get_the_ID(), '_tutorial_prerequisites', true);
                        
                        // Display tutorial content if exists
                        if ($tutorial_content) : ?>
                            <div class="tutorial-detail-section">
                                <h3><?php _e('Over deze tutorial', 'software-syndicate'); ?></h3>
                                <div class="tutorial-description">
                                    <?php echo wpautop(esc_html($tutorial_content)); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="tutorial-detail-section">
                                <h3><?php _e('Over deze tutorial', 'software-syndicate'); ?></h3>
                                <div class="tutorial-description">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($difficulty || $estimated_time || $prerequisites) : ?>
                            <div class="tutorial-details">
                                <?php if ($difficulty) : ?>
                                    <div class="tutorial-detail-section">
                                        <h3><?php _e('Moeilijkheidsgraad', 'software-syndicate'); ?></h3>
                                        <div class="difficulty-display">
                                            <span class="difficulty-badge difficulty-<?php echo esc_attr(strtolower($difficulty)); ?>">
                                                <?php echo esc_html($difficulty); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($estimated_time) : ?>
                                    <div class="tutorial-detail-section">
                                        <h3><?php _e('Geschatte tijd', 'software-syndicate'); ?></h3>
                                        <div class="estimated-time">
                                            <span class="time-badge">
                                                <span class="dashicons dashicons-clock"></span>
                                                <?php echo esc_html($estimated_time); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($prerequisites) : ?>
                                    <div class="tutorial-detail-section">
                                        <h3><?php _e('Vereisten', 'software-syndicate'); ?></h3>
                                        <div class="prerequisites-content">
                                            <?php echo wpautop(esc_html($prerequisites)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Tutorial Steps -->
                        <div class="tutorial-detail-section">
                            <h3><?php _e('Tutorial Stappen', 'software-syndicate'); ?></h3>
                            <div class="tutorial-steps">
                                <?php 
                                // Get raw content without WordPress filters
                                $content = get_the_content();
                                
                                // Check for nextpage tags (both raw and HTML entities)
                                if (strpos($content, '<!--nextpage-->') !== false || strpos($content, '&lt;!--nextpage--&gt;') !== false) {
                                    // Convert HTML entities back to raw tags
                                    $content = str_replace('&lt;!--nextpage--&gt;', '<!--nextpage-->', $content);
                                    
                                    // Split by nextpage tags
                                    $steps = explode('<!--nextpage-->', $content);
                                    
                                    foreach ($steps as $index => $step_content) {
                                        $step_content = trim($step_content);
                                        
                                        if (!empty($step_content)) {
                                            echo '<div class="tutorial-step">';
                                            echo '<div class="step-header">';
                                            echo '<span class="step-number">Stap ' . ($index + 1) . '</span>';
                                            echo '</div>';
                                            echo '<div class="step-content">' . wpautop($step_content) . '</div>';
                                            echo '</div>';
                                        }
                                    }
                                } else {
                                    // Check for headings to create steps
                                    if (preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/', $content, $matches, PREG_SET_ORDER)) {
                                        $step_count = 0;
                                        $parts = preg_split('/(<h[1-6][^>]*>.*?<\/h[1-6]>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                                        
                                        foreach ($parts as $i => $part) {
                                            if (preg_match('/<h([1-6])[^>]*>(.*?)<\/h\1>/', $part, $heading_match)) {
                                                if ($step_count > 0) {
                                                    echo '</div></div>';
                                                }
                                                $step_count++;
                                                echo '<div class="tutorial-step">';
                                                echo '<div class="step-header">';
                                                echo '<span class="step-number">Stap ' . $step_count . '</span>';
                                                echo '<h4 class="step-title">' . strip_tags($heading_match[2]) . '</h4>';
                                                echo '</div>';
                                                echo '<div class="step-content">';
                                            } elseif ($step_count > 0) {
                                                $part = trim($part);
                                                if (!empty($part)) {
                                                    echo wpautop($part);
                                                }
                                            }
                                        }
                                        
                                        if ($step_count > 0) {
                                            echo '</div></div>';
                                        } else {
                                            // Fallback: single step
                                            echo '<div class="tutorial-step">';
                                            echo '<div class="step-header">';
                                            echo '<span class="step-number">Stap 1</span>';
                                            echo '</div>';
                                            echo '<div class="step-content">' . wpautop($content) . '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        // Split into logical steps by paragraphs
                                        $paragraphs = preg_split('/\n\s*\n/', $content);
                                        $step_count = 0;
                                        $current_step = '';
                                        
                                        foreach ($paragraphs as $paragraph) {
                                            $paragraph = trim($paragraph);
                                            if (!empty($paragraph)) {
                                                if (strlen($current_step) > 300 || $step_count == 0) {
                                                    // Start new step
                                                    if (!empty($current_step)) {
                                                        echo '</div></div>';
                                                    }
                                                    $step_count++;
                                                    echo '<div class="tutorial-step">';
                                                    echo '<div class="step-header">';
                                                    echo '<span class="step-number">Stap ' . $step_count . '</span>';
                                                    echo '</div>';
                                                    echo '<div class="step-content">' . wpautop($current_step) . '</div>';
                                                    echo '</div>';
                                                    $current_step = $paragraph;
                                                } else {
                                                    $current_step .= "\n\n" . $paragraph;
                                                }
                                            }
                                        }
                                        
                                        // Output last step
                                        if (!empty($current_step)) {
                                            $step_count++;
                                            echo '<div class="tutorial-step">';
                                            echo '<div class="step-header">';
                                            echo '<span class="step-number">Stap ' . $step_count . '</span>';
                                            echo '</div>';
                                            echo '<div class="step-content">' . wpautop($current_step) . '</div>';
                                            echo '</div>';
                                        }
                                        
                                        // If no steps were created, create one
                                        if ($step_count == 0) {
                                            echo '<div class="tutorial-step">';
                                            echo '<div class="step-header">';
                                            echo '<span class="step-number">Stap 1</span>';
                                            echo '</div>';
                                            echo '<div class="step-content">' . wpautop($content) . '</div>';
                                            echo '</div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tutorial Navigation -->
        <section class="tutorial-navigation">
            <div class="container">
                <nav class="tutorial-nav">
                    <div class="nav-prev">
                        <?php previous_post_link('%link', '&larr; Vorige tutorial'); ?>
                    </div>
                    <div class="nav-back">
                        <a href="<?php echo get_post_type_archive_link('tutorial'); ?>" class="btn btn-outline">
                            <?php _e('Alle Tutorials', 'software-syndicate'); ?>
                        </a>
                    </div>
                    <div class="nav-next">
                        <?php next_post_link('%link', 'Volgende tutorial &rarr;'); ?>
                    </div>
                </nav>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
