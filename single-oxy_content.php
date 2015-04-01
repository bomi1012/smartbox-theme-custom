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
                <!--add print friendly(pdf, email) button-->
                <a align="right" class="icon-print pf-alignright hb_margin-left_10" href="<?php echo get_permalink() . '?pfstyle=wp'; ?>" rel="nofollow"></a>
                <!--add audio button if audio file assigned to post-->
                <?php
                    $audio_url = get_field('content_audio_shortcode', $post->ID);
                    if (!empty($audio_url)) {
                        //echo " <a align='right' class='icon-download-alt hb_margin-left_10' href='".$audio_url."' type='application/octet-stream'></a>";
                        echo "<span id='audioSwitch' class='icon-volume-up pf-alignright hb_margin-left_10 cursor'></span>";
                        echo "<div id='showAudio' class='hidden'>". hb_get_jw_player_for_video_church($post) ."</div>";
                        //echo "<div id='showAudio' class='hidden'>". do_shortcode('[audio src="'. $audio_url . '"]') ."</div>";
                    }
                ?>

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