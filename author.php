<?php
/**
 * Displays a excerpts of blogs by author in timeline layout
*/

get_header();
oxy_page_header();
// get the author name
if (get_query_var('author')) {
    $author = get_userdata(get_query_var('author'));
    $author_name = $author->display_name;
}
//use header image of blogs page for all single author pages
$page_blogs = get_page_by_path('blogs');
$page_id = $page_blogs->ID;
$custom_fields = get_post_custom($page_id);

//verify whether super hero image(custom field "..._thickbox") is set, if yes take it
if (isset($custom_fields[THEME_SHORT . '_thickbox'])) {
    $img = wp_get_attachment_image_src($custom_fields[THEME_SHORT . '_thickbox'][0], 'full');
    oxy_create_hero_section($img[0], '<span class="lighter">' . $author_name . '</span>');
} else {
    oxy_create_hero_section(null, '<span class="lighter">' . $author_name . '</span>');
}
?>
<section class="section section-padded section-alt">
    <div class="container-fluid">
        <div class="row-fluid">
            <ol id="timeline">
                <?php 
                $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'author' => $author->ID, 
                'posts_per_page' => 500,
                'paged' => false);
                $my_query = new wp_query($args);
                while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
                    <?php get_template_part( 'partials/timeline/content-timeline-excerpt' ); ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </ol>
        </div>
    </div>
</section>
<?php get_footer();