<?php
/**
 * Displays a single blog
 */
get_header();
global $post;
//use header image of blogs side for all single blogs
$page_blogs = get_page_by_path( 'blogs' );
$page_id = $page_blogs->ID;
$custom_fields = get_post_custom($page_id);
//verify whether super hero image(custom field "..._thickbox" is set, if yes take it)
if (isset($custom_fields[THEME_SHORT . '_thickbox'])) {
    $img = wp_get_attachment_image_src($custom_fields[THEME_SHORT . '_thickbox'][0], 'full');
    oxy_create_hero_section($img[0], $post->post_title);
} else {
    oxy_create_hero_section(null, $post->post_title);
}

$allow_comments = oxy_get_option('site_comments');
?>

<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <?php if (oxy_get_option('blog_layout') == 'sidebar-left'): ?>
                <aside class="span3 sidebar">
                    <?php get_sidebar(); ?>
                </aside>
            <?php endif; ?>
            <div class="<?php echo oxy_get_option('blog_layout') == 'full-width' ? 'span12' : 'span9'; ?>">
                <a align="right" class="icon-print pf-alignright" href="<?php echo get_permalink() . '?pfstyle=wp'; ?>" rel="nofollow"></a>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('partials/content', get_post_format()); ?>
                    <?php if ($allow_comments == 'posts' || $allow_comments == 'all') comments_template('', true); ?>
                <?php endwhile; ?>
            </div>
            <?php if (oxy_get_option('blog_layout') == 'sidebar-right'): ?>
                <aside class="span3 sidebar">
                    <?php get_sidebar(); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
get_footer();