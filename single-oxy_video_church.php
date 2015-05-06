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
                <?php
                $mp4_download_url = get_field('mp4_download_video_church', $post->ID);
                if (!empty($mp4_download_url)) {
                    echo "<a href='" . $mp4_download_url . "' target='_self' class='icon-download-alt pf-alignright hb_margin-left_10 cursor' download=''></a>";
                }
                ?>
                <?php the_post(); ?>
                <div class="span12">
                    <?php echo hb_get_jw_player_for_video_church($post); ?>
                </div>
                <div class="span12">
                    <?php if ($allow_comments == 'posts' || $allow_comments == 'all') comments_template('', true); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
