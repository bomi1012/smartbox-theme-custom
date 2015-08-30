<?php
/**
 * Displays archive for videos. This page is used for requests:
 * 1 Search reguest of videos(e.g. url =".../?s=searched_keaword&post_type=oxy_video"
 * 2 Taxonomy request of videos (e.g. url = ".../blog/teaching_topics/golgota/?post_type=oxy_video")
 * 3 Archive request of videos (e.g. url = ".../blog/2014/10/?post_type=oxy_video")
 * 4 Display all videos (e.g url = "...?post_type=oxy_video")
 * @package Smartbox
 * @subpackage Frontend
 * @since 0.1
 *
 * @copyright (c) 2013 Oxygenna.com
 * @license http://wiki.envato.com/support/legal-terms/licensing-terms/
 * @version 1.5
 */
get_header();
if(is_page())
    oxy_page_header();

?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span9">
                <?php get_template_part('partials/hb_loop_video'); ?>
            </div>
            <aside class="span3 sidebar">
                <?php dynamic_sidebar('sidebar-videos'); ?>
            </aside>
        </div>        
    </div>
</section>
<?php get_footer();


            