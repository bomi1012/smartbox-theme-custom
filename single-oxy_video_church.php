<?php
/**
 * Displays a single post of type oxy_video_church
 */
get_header();
global $post;
oxy_create_hero_section(hb_get_post_banner_image($post), $post->post_title);
$allow_comments = oxy_get_option('site_comments');
?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <?php the_post(); ?>
                <div class="span12" style = "color:#FFA500;">
                    <?php echo get_field('quote'); ?>
                </div>
                <?php echo hb_get_jw_player_for_video_curch($post); ?>
                <?php if ($allow_comments == 'posts' || $allow_comments == 'all') comments_template('', true); ?>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
