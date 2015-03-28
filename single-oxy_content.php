<?php
/* Displays a single post of type oxy_text */
get_header();
global $post;
oxy_create_hero_section(hb_get_post_banner_image($post), $post->post_title);
$allow_comments = oxy_get_option('site_comments');
?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <a align="right" class="icon-print pf-alignright hb_margin-left_10" href="<?php echo get_permalink() . '?pfstyle=wp'; ?>" rel="nofollow"></a>
                <span id="audioSwitch" class="icon-volume-up pf-alignright hb_margin-left_10 cursor"></span>
                <div id="showAudio" class="hidden">An diese Stelle wird später Player für die Audiodatei integriert. </div>
                <?php echo hb_get_assigned_taxonomy_terms($post); ?>
                <?php the_post(); ?>
                <div class="span12" style = "color:#FFA500;">
                    <?php echo get_field('quote'); ?>
                </div>
                <?php get_template_part('partials/content-text', get_post_format()); ?>
                <?php oxy_wp_link_pages(array('before' => '<div class="pagination pagination-centered">', 'after' => '</div>')); ?>
                <?php echo hb_ui_related_posts(get_the_ID()); ?>
                <?php if ($allow_comments == 'posts' || $allow_comments == 'all') comments_template('', true); ?>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();