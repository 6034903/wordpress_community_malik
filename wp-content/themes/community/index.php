<?php get_header(); ?>

<main class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <?php
                if (is_home() && !is_front_page()) {
                    ?>
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php
                } elseif (is_archive()) {
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                } elseif (is_search()) {
                    ?>
                    <h1 class="page-title">
                        <?php
                        printf(
                            esc_html__('Search Results for: %s', 'software-syndicate'),
                            '<span>' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                    <?php
                } elseif (is_404()) {
                    ?>
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'software-syndicate'); ?></h1>
                    <?php
                }
                ?>
            </header>

            <div class="posts-container">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="post-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <header class="entry-header">
                                    <?php
                                    if (is_singular()) :
                                        the_title('<h1 class="entry-title">', '</h1>');
                                    else :
                                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                    endif;
                                    ?>
                                    
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </span>
                                        <span class="byline">
                                            <span class="author vcard">
                                                <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                    <?php echo esc_html(get_the_author()); ?>
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                </header>
                                
                                <div class="entry-content">
                                    <?php
                                    if (is_singular()) {
                                        the_content();
                                        
                                        wp_link_pages(array(
                                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'software-syndicate'),
                                            'after'  => '</div>',
                                        ));
                                    } else {
                                        the_excerpt();
                                        ?>
                                        <a href="<?php the_permalink(); ?>" class="read-more">
                                            <?php esc_html_e('Read More &rarr;', 'software-syndicate'); ?>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <?php if (has_tag()) : ?>
                                        <div class="tag-links">
                                            <?php
                                            the_tags('', ' ', '');
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </footer>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'software-syndicate'),
                    'next_text' => __('Next &raquo;', 'software-syndicate'),
                ));
                ?>
            </div>

        <?php else : ?>
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'software-syndicate'); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php
                    if (is_home() && current_user_can('publish_posts')) :
                        ?>
                        <p>
                            <?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'software-syndicate'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                        <?php
                    elseif (is_search()) :
                        ?>
                        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'software-syndicate'); ?></p>
                        <?php
                        get_search_form();
                    else :
                        ?>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'software-syndicate'); ?></p>
                        <?php
                        get_search_form();
                    endif;
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>
