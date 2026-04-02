<?php
/**
 * Sidebar template
 */
if (!is_active_sidebar('sidebar-1') || is_page('projecten')) {
    return;
}
?>

<aside class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>
