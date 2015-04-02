 <?php
/**
 * @description get banner image value which is stored in custom field of taxonomy
 * @param string $taxonomy_name <i>name of taxonomy</i>
 * @param string $taxonomy_slug <i>taxonomy slug</i>
 * @param hb_enum_taxonomy_image $image_type<i>image type, coulb be image, banner image or background video image</i>
 * @return string
 */
function hb_get_taxonomy_image($taxonomy_name, $taxonomy_slug, $image_type) {
    // custom field depends of image type
    switch ($image_type) {
        case hb_enum_taxonomy_image_type::banner_image:
            $field_name = 'taxonomy_banner_image';
            $taxonomy_default_image = CUSTOM_IMAGES_DIR . 'banner_thema_default.jpg';
            break;
        case hb_enum_taxonomy_image_type::image:
            $field_name = 'taxonomy_image';
            $taxonomy_default_image = null;
            break;
        case hb_enum_taxonomy_image_type::video_background_image:
            $field_name = 'taxonomy_video_background';
            $taxonomy_default_image = CUSTOM_IMAGES_DIR . 'background_video_default.jpg';
            break;
    }

    $term_details = term_exists($taxonomy_slug, $taxonomy_name);
    if (is_array($term_details)) {
        //in order to get custom field 'taxonomy_image' from taxonomy we have 
        //to call advanced custom fields plugin api and provide id of post which 
        //is combination of taxonomy name and id of term e.g. term 'god' => id = 39
        $image = get_field($field_name, 'teaching_topics_' . $term_details['term_id']);
        if (!empty($image[url])) {
            global $wp_embed;
            return $image[url];
        } 
    }
    return $taxonomy_default_image;
}

/**
 * @description get banner image for post from custom field of post or from corresponding taxonomy term of post 
 * @param post $post <i>post</i>
 * @param string $taxonomy_term optional<i>taxonomy term</i>
 * @return string
 */
function hb_get_post_banner_image($post, $taxonomy_term = 'teaching_topics') {
    //in order to get custom field 'banner_image' from taxonomy we have 
    //to call advanced custom fields plugin api and provide id of post
    switch ($post->post_type) {
        case 'oxy_video': $image = get_field('video_banner_image', $post->ID);
            break;
        case 'oxy_audio': $image = get_field('audio_banner_image', $post->ID);
            break;
        case 'oxy_content': $image = get_field('content_banner_image', $post->ID);
            break;
        default :
            break;
    }
    //image found on post level return it
    if (!empty($image[url])) {
        global $wp_embed;
        return $image[url];
    }
    //try to get image from taxonomy topic assigned to post 
    //Returns All Term Items for taxonomy
    $term_list = wp_get_post_terms($post->ID, $taxonomy_term, array("fields" => "all"));
    foreach ($term_list as $term) {
        $image = get_field('taxonomy_banner_image', 'teaching_topics_' . $term->term_id);
        if (!empty($image[url]))
            return $image[url];
    }
    return CUSTOM_IMAGES_DIR . 'banner_thema_default.jpg';
}

/**
 * @description teaching topic can occur in url query as term or just a topic try to get this term
 * @return string
 */
function hb_get_teaching_topic_from_query() {
    
    $teaching_topic = get_query_var('term');
    if (empty($teaching_topic)) {
        $teaching_topic = get_query_var('teaching_topics');
    }
    return $teaching_topic;
}

/**
 * @description provides true if theme is running locally on localhost and false if on server 
 * @return boolean
 */
function hb_is_local_environment() {
    $server_name = $_SERVER['SERVER_NAME'];
    if (isset($server_name) && $server_name == "localhost")
        return true;
    return false;
}
/**
 * @description get path to script library of jw player
 * @return string
 */
function hb_get_host_jw_player_script() {
    if (is_ssl() && !hb_is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (is_ssl() && hb_is_local_environment())
        return 'https://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else if (!hb_is_local_environment())
        return 'https://ssl.jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
    else
        return 'http://jwpsrv.com/library/2vQezLOEEeOy_CIACi0I_Q.js';
}

/**
 * @description do limit string by word count
 * @param string $string <i>string to be limited</i>
 * @param int $word_limit <i>amount of words for limit</i>
 * @param bool $add_punkts <i>whether to add punkts at the end </i>
 * @return string
 */
function hb_limit_string($string, $word_limit, $add_punkts = false) {
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }

    if ($add_punkts)
        return implode(' ', $words) . ' ...';
    return implode(' ', $words);
}

/**
 * @description get taxonomy term summary, used for example on topic page 
 * @param taxonomy $taxonomy <i>post instance of text post</i>
  * @return string
 */
function hb_get_taxonomy_term_summary_mini($taxonomy) {
    $summary = get_field('taxonomy_summary', 'teaching_topics_' . $taxonomy->term_id);
    if (empty($taxonomy)) {
        $summary = $taxonomy->description . " ";
        $summary = oxy_limit_excerpt($summary, 40);
    }
    return $summary;
}

/**
 * @description function to create more text string
 * @param string $post_type <i> post type of post </i>
 * @return more text string
 */
function hb_get_more_text($post_type) {
    $more_text = __('Read more', THEME_FRONT_TD);
    switch ($post_type) {
        case 'oxy_video':
            $more_text = __('Goto video', THEME_FRONT_TD);
            break;
        case 'oxy_audio':
            $more_text = __('Goto audio', THEME_FRONT_TD);;
            break;
        default :
            break;
    }
    return $more_text;
}

/**
 * @package HTML_HELPER
 * @description function to generate a attributes for html-elements
 * @param string $id
 * @param string $class
 * @return string
 */
function hb_set_attributes($id=NULL, $class=NULL) {
    $string = ' ';
    if (hb_is_element_not_empty($id)) {
        $string .= 'id="' . $id . '" ';
    }
    if (hb_is_element_not_empty($class)) {
        $string .= 'class="' . $class . '" ';
    }
    return $string;
}

/**
 * @package HELPER
 * @description function to check if a element exists
 * @param string $element
 * @return boolean only <b>TRUE</b> or <b>FALSE</b>. Not NULL.
 */
function hb_is_element_not_empty($element) {
    if ($element != NULL && $element != '') {
        return TRUE;
    }
    return FALSE;
}
/*
 * @description function to check if a element exists  
 */
class hb_enum_taxonomy_image_type {
    const image = 'image';
    const banner_image = 'banner_image';
    const video_background_image = 'video_background_image';
}

/**
 * @package HELPER
 * @description get to back <b>externa link</b> or <b>permalink</b>
 * @param string $post_format
 * @return string
 */
function hb_get_linkformat($post_format) {
    if (post_format == 'link') {
        return oxy_get_external_link();
    } else {
        return get_permalink();
    }
}


/**
 * @package HELPER
 * @description provides script for jw player for video church post
 * @param post of type oxy_video_church $post
 * @return string
 */
function hb_get_jw_player_for_video_church($post){
    $mp3_url = get_field('content_audio_shortcode', $post->ID);
    $rtmp_url = get_field('rtmp_url', $post->ID);
    $smil_url = get_field('smil_url', $post->ID);
    $m3u8_url = get_field('m3u8_url', $post->ID);
    $android_url = get_field('android_url', $post->ID);
    
    $player_id = guid();
    $output = "<script src=\"http://holybunch.com/jwplayer/jwplayer.js\" type=\"text/javascript\"></script>";
    if(!empty($android_url)){
        $content = do_shortcode("[button icon=\"icon-film\" type=\"primary\" size=\"btn-medium\" label=\"Play on android\" link=\"$android_url\"]");
        $output .= $content;   
    }
    
    //Detect special conditions devices
    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");
    
    if(empty($mp3_url))$div_class = "videoWrapper";
    
    $output .= "<div class='".$div_class."'><div id=\"'.$player_id.'\">Loading the player...please</div>&nbsp;</div>";
    $output .= "<script type=\"text/javascript\">jwplayer(\"'.$player_id.'\").setup({";
    //$output .= "],";
    $add_comma = false;    
    $output .= "playlist: [{ title: \"Play Video\", sources: [ ";
    if(!empty($smil_url)){
        //if($add_comma) $output .= ",";
        $output .= "{ file: \"".$smil_url."\" }";
        $add_comma = true;
    }
    
    if(!empty($m3u8_url)){
        if($add_comma) $output .= ",";
        $output .= "{ file: \"".$m3u8_url."\"}";
        $add_comma = true;
    }

    if(!empty($rtmp_url)){
        if($add_comma) $output .= ",";
        $output .= "{ file: \"".$rtmp_url."\"}";
        $add_comma = true;
    }   

    if(!empty($mp3_url)){
        if($add_comma) $output .= ",";
        $output .= "{ file: \"".$mp3_url."\"}";
        $add_comma = true;
    }   
    
    $output .= " ]}],";
    
    if (!empty($mp3_url))
        $output .= "height: 30, width: 600} );</script>";
    else if ($iPad) 
        $output .= "height: 360, width: 600} );</script>";
    else if ($iPod || $iPhone)
        $output .= "height: 120, width: 200} );</script>";
    else
        $output .= "width: '100%', aspectratio:'16:9', primary: \"html5\"} );</script>";

    
    return $output;  
}
 /**
 * @package HELPER
 * @description provides categories (taxonomy topics) for slugs entered by user
 * @param there is an attribute "topics" of the shortcode latest_taxonomy_topics. Each entered topic is a taxonomy_slug for the query.
 *      $topics. 
 * @return a list of taxonomy topics or nothing
 */
function hb_get_categorien_for_entered_topics ($topics){
    $topicArray = split("\s?[,;]", $topics);
    $categories = array();
    $count = count($topicArray);
    for ($i = 0; $i < $count; $i++) {
        $args = array(
        'hide_empty' => 1,
        'taxonomy' => 'teaching_topics',
        'pad_counts' => 1,
        'hierarchical' => 0,
        'slug' => $topicArray[$i],
    );
  //  $categories = get_categories($args);
    $categories = array_merge($categories , get_categories($args));
    }
    return $categories;
}

/**
 * @package HELPER
 * @description generate guid
 * @return string
 */
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        return $uuid;
    }
}
?>
