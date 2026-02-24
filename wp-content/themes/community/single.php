<?php get_header(); ?>

<main class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
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
                        <?php if (has_category()) : ?>
                            <span class="cat-links">
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail-single">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'software-syndicate'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <footer class="entry-footer">
                    <?php if (has_tag()) : ?>
                        <div class="tag-links">
                            <span><?php esc_html_e('Tags:', 'software-syndicate'); ?></span>
                            <?php the_tags('', ' ', ''); ?>
                        </div>
                    <?php endif; ?>
                </footer>
            </article>
            
            <nav class="navigation post-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'software-syndicate'); ?></h2>
                <div class="nav-links">
                    <?php
                    previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">' . esc_html__('Previous:', 'software-syndicate') . '</span> %title');
                    next_post_link('<div class="nav-next">%link</div>', '<span class="meta-nav">' . esc_html__('Next:', 'software-syndicate') . '</span> %title');
                    ?>
                </div>
            </nav>
            
            <?php if (comments_open() || get_comments_number()) : ?>
                <div class="comments-section">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
