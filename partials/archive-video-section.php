<?php
/* * Generates first of all a single video item html section, 
 * used for showing all video items by hb_loop_video, but also used for:  
 * 1 taxonomy request (e.g. url='.../blog/teaching_topics/golgota/?post_type=oxy_video')
 * 2 search request (e.g. url='.../?s=Ибо+слово+о+кресте&post_type=oxy_video'); in this case relevanssi_the_excerpt will be used
 */
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>    
    <div class="span12">
        <div class="span12 post-body">
            <div class="post-head">
                <h2 class="small-screen-center">
                    <?php if (is_single()) : ?>
                        <?php the_title(); ?>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', THEME_FRONT_TD), the_title_attribute('echo=0'))); ?>" rel="bookmark">
                            <?php the_title(); ?>
                        </a>
                    <?php endif;  ?>
                </h2>
            </div>
            <div class="entry-content">
                <?php
                if (is_search()):
                    $content = relevanssi_the_excerpt();
                else:
                    $content = get_the_content();
                endif;
                $video_shortcode = get_field('video_shortcode', $post->ID);
                if(empty($video_shortcode))
                    $output = '<div>' . hb_get_jw_player_for_video_church($post) . '</div>';
                else 
                    $output = '<div>' . hb_create_videowrapper_div($video_shortcode, $span = "span12", "1250", "703") . '</div>';
                $output .= '<div class="span12" style="margin-top: 25px;">' . $content . '</div>';
                echo $output;
                ?>
            </div>
        </div>
</article>

