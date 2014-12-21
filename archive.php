<?php
/**
 * Displays a tag archive
*/

get_header();
if( is_day() ) {
    $title = __( 'Day', THEME_FRONT_TD );
    $sub = get_the_date( 'j M Y' );
}
elseif( is_month() ) {
    $title = __( 'Month', THEME_FRONT_TD );
    $sub = get_the_date( 'F Y' );
}
elseif( is_year() ) {
    $title = __( 'Year', THEME_FRONT_TD );
    $sub = get_the_date( 'Y' );
}
else {
    $title = __( 'Blog', THEME_FRONT_TD );
    $sub = 'Archives';
}
?>
<?php oxy_create_hero_section( null, ' <span class="lighter">' . $sub . '</span>' ); ?>
<section class="section section-padded">
    <div class="container-fluid">
        <div class="row-fluid">
            <?php get_template_part( 'partials/hb_loop_blog' ); ?>
        </div>
    </div>
</section>
 <?php get_footer();