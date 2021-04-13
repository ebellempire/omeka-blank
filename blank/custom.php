<?php

// test: is this a web-friendly image?
// used by: ob_item_files()
function ob_isImg($mime=null)
{
    $valid_img = array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif','image/webp');
    $test=in_array($mime, $valid_img);
    return $test;
}

// test: is this a web-friendly audio file?
// used by: ob_item_files()
function ob_isAudio($mime=null)
{
    $valid_audio = array('audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-mpegaudio','audio/x-mpg','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-mpegaudio','audio/x-mpg');
    $test=in_array($mime, $valid_audio);
    return $test;
}

// test: is this a web-friendly video file?
// used by: ob_item_files()
function ob_isVideo($mime=null)
{
    $valid_video = array('video/mp4','video/mpeg','video/ogg','video/quicktime','video/webm');
    $test=in_array($mime, $valid_video);
    return $test;
}

// return img markup
// used by: ob_item_files()
// data-attributes on container link can be used for lightbox-style image viewer
function ob_img_markup($file, $size='fullsize', $index=0, $html=null)
{
    if (($url = $file->getWebPath($size))) {
        $title = ob_dublin($file, 'Title');
        $description = ob_dublin($file, 'Description');
        $record_url = '/files/show/'.$file->id;

        $html .= '<div class="item-file image '.$size.'" id="image-'.$index.'">';
        $html .= '<a href="'.$record_url.'" data-original="'.$file->getWebPath('original').'" data-title="'.strip_tags($title).'" data-description="'.strip_tags($description).'" data-id="'.$file->id.'"><img alt="'.strip_tags(ob_dublin($file, 'Description', array('Title'))).'" src="'.$url.'"/></a>';
        $html .= '</div>';
    }
    return $html;
}

// return audio markup
// used by: ob_item_files()
function ob_audio_markup($file, $mime, $index=0, $html=null)
{
    if (($url = file_display_url($file, 'original')) && ($mime)) {
        $html .= '<div class="item-file audio" id="audio-'.$index.'">';
        $html .= '<audio class="htmlaudio" controls preload="auto">';
        $html .= '<source src="'.$url.'" type="'.$mime.'"/>';
        $html .= "Your browser doesn't support HTML &lt;audio&rt;";
        $html .= '<a href="'.$url.'">Download the file</a>.';
        $html .= '</audio>';
        $html .= '</div>';
    }
    return $html;
}

// return video markup
// used by: ob_item_files()
function ob_video_markup($file, $mime, $index=0, $html=null)
{
    if (($url = file_display_url($file, 'original')) && ($mime)) {
        $html .= '<div class="item-file video" id="video-'.$index.'">';
        $html .= '<video class="htmlvideo" controls playsinline preload="auto">';
        $html .= '<source src="'.$url.'" type="'.$mime.'"/>';
        $html .= "Your browser doesn't support HTML &lt;video&rt;";
        $html .= '<a href="'.$url.'">Download the file</a>.';
        $html .= '</video>';
        $html .= '</div>';
    }
    return $html;
}

// looks for the URL metadata field and converts relevant links to embed codes
function ob_embed_codes($item=null, $html=null)
{
    if ($url = parse_url(trim(metadata($item, array('Item Type Metadata','URL'))))) {
        if (isset($url['host']) && isset($url['query'])) {
            if ($url['host'] == ('youtu.be' || 'www.youtube.com')) { // YouTube
                $html .= '<div class="item-file embed youtube"><iframe width="560" height="315" src="https://www.youtube.com/embed/'.str_replace('v=', '', $url['query']).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }
        }
    }
    return $html;
}

// return custom markup for all files
// defaults to standard Omeka output when mime type is not on the whitelist
// set $gallery=true to use one fullsize image followed by thumbnail images
// If the URL metadata field is a youtube link, we'll create an embed code and put it at the top
function ob_item_files($item=null, $gallery=false, $img_index=0, $audio_index=0, $video_index=0, $gallery_html = null, $html=null)
{
    $html .= ob_embed_codes($item);
    if (metadata($item, 'has files')) {
        foreach (loop('files', $item->Files) as $file) {
            $mime = metadata($file, 'mime_type');
            if (ob_isImg($mime)) {
                $img_index++;
                $size='fullsize';
                if ($gallery==true) {
                    if ($img_index > 1) {
                        $size='square_thumbnail';
                    }
                    $gallery_html .= ob_img_markup($file, $size, $img_index);
                } else {
                    $html .= ob_img_markup($file, $size, $img_index);
                }
            } elseif (ob_isAudio($mime)) {
                $audio_index++;
                $html .= ob_audio_markup($file, $mime, $audio_index);
            } elseif (ob_isVideo($mime)) {
                $video_index++;
                $html .= ob_video_markup($file, $mime, $video_index);
            } else {
                // Omeka default display (fullsize if img)
                $html .= '<div class="item-file">'.file_markup($file, array('imageSize' => 'fullsize')).'</div>';
            }
        }
    }
    return '<div id="item-files" class="element">'.($gallery_html ? '<div id="image-gallery">'.$gallery_html.'</div>'.$html : $html).'</div>';
}

// preferred label for items
function ob_item_label($type="singular")
{
    if ($type="plural") {
        $label = get_theme_option('items_label_p') ? get_theme_option('items_label_p') : __('Items');
    } else {
        $label = get_theme_option('items_label_s') ? get_theme_option('items_label_s') : __('Item');
    }
    return trim(html_escape($label));
}

// preferred label for featured items
function ob_featured_item_label($type="singular")
{
    if ($type="plural") {
        $label = get_theme_option('featured_label_p') ? get_theme_option('featured_label_p') : __('Featured Items');
    } else {
        $label = get_theme_option('featured_label_s') ? get_theme_option('featured_label_s') : __('Featured Item');
    }
    return trim(html_escape($label));
}

// get Dublin Core metadata with fallbacks
// fallbacks must be valid Dublin Core elements
// can be used with files, e.g. to create alt text in ob_img_markup()
function ob_dublin($record=null, $element=null, $fallbacks=array(), $html=null)
{
    if (element_exists('Dublin Core', $element) && ($el = metadata($record, array('Dublin Core',$element)))) {
        $html .= '<p>'.$el.'</p>';
    } else {
        foreach ($fallbacks as $fallback) {
            if (element_exists('Dublin Core', $fallback) && ($alt = metadata($record, array('Dublin Core',$fallback)))) {
                return '<p>'.$alt.'</p>';
            }
        }
    }
    return $html;
}

// get Item Type metadata with fallbacks
// fallbacks must be valid Item Type elements
function ob_item_type($item=null, $element=null, $fallbacks=array(), $html=null)
{
    if (element_exists('Item Type Metadata', $element) && ($element_text = metadata($item, array('Item Type Metadata',$element)))) {
        $html .= '<p>'.$element_text.'</p>';
    } else {
        foreach ($fallbacks as $fallback) {
            if (element_exists('Item Type Metadata', $fallback) && ($alt = metadata($item, array('Item Type Metadata',$fallback)))) {
                return '<p>'.$alt.'</p>';
            }
        }
    }
    return $html;
}

// return select metadata fields
function ob_select_metadata($item=null, $dc_elements=array(), $itemtype_elements=array(), $html=null)
{
    if (count($dc_elements)) {
        foreach ($dc_elements as $el) {
            if (element_exists('Dublin Core', $el) && ($element_texts = metadata($item, array('Dublin Core',$el), array('all'=>true)))) {
                $html .= '<div class="element">';
                $html .= '<h3>'.$el.'</h3>';
                foreach ($element_texts as $text) {
                    $html .= '<div class="element-text">'.$text.'</div>';
                }
                $html .= '</div>';
            }
        }
    }
    if (count($itemtype_elements)) {
        foreach ($itemtype_elements as $el) {
            if (element_exists('Item Type Metadata', $el) && ($element_texts = metadata($item, array('Item Type Metadata',$el), array('all'=>true)))) {
                $html .= '<div class="element">';
                $html .= '<h3>'.$el.'</h3>';
                foreach ($element_texts as $text) {
                    $html .= '<div class="element-text">'.$text.'</div>';
                }
                $html .= '</div>';
            }
        }
    }
    return $html;
}

// return a single featured item using the preferred label
function ob_featured_item_block($showlink=true, $html=null)
{
    if (get_theme_option('display_featured_item') !== '0') {
        $html .= '<div id="featured-item">';
        $html .= '<h2>'.ob_featured_item_label('singular').'</h2>';
        $html .= random_featured_items(1, true);
        $html .= $showlink ? '<a href="/items/browse/?featured=1">View All '.ob_featured_item_label('plural').'</a>' : null;
        $html .= '</div>';
    }

    return $html;
}

// return custom call to action block
function ob_cta_block($html = null)
{
    $url = get_theme_option('cta_link');
    $heading = get_theme_option('cta_heading');
    $text = get_theme_option('cta_text');
    $label = get_theme_option('cta_button_label');
    $target = get_theme_option('cta_target') ? 'target="_blank"' : null;

    if ($url && $label) {
        $html .= '<div id="cta">';
        $html .= '<h2>'.$heading.'</h2>';
        $html .= '<p>'.$text.'</p>';
        $html .= '<a href="'.$url.'" '.$target.'>'.$label.'</a>';
        $html .= '</div>';
    }

    return $html;
}

// return custom homepage text block #1
function ob_homepage_text_block_1($heading=null, $img=null, $html = null)
{
    if (get_theme_option('homepage_block_1_text')) {
        $html .= '<div id="home-text">';
        $html .= $heading ? '<h2>'.html_escape(trim($heading)).'</h2>' : null;
        $html .= $img ? '<img src="/files//theme_uploads/'.$img.'"/>' : null;
        $html .= '<p>'.get_theme_option('homepage_block_1_text').'</p>';
        $html .= '</div>';
    }

    return $html;
}

// return custom homepage text block #2
function ob_homepage_text_block_2($heading=null, $img = null, $html = null)
{
    if (get_theme_option('homepage_block_2_text')) {
        $html .= '<div id="home-text">';
        $html .= $heading ? '<h2>'.html_escape(trim($heading)).'</h2>' : null;
        $html .= $img ? '<img src="/files//theme_uploads/'.$img.'"/>' : null;
        $html .= '<p>'.get_theme_option('homepage_block_2_text').'</p>';
        $html .= '</div>';
    }

    return $html;
}

function ob_item_url($item=null, $html=null)
{
    if ($url = parse_url(trim(metadata($item, array('Item Type Metadata','URL'))))) {
        $html .= '<a href="'.$url.'" target="_blank">'.__('View @ %s', str_replace('www.', '', $url['host'])).'</a>';
    }
    return $html;
}

// return citation markup
function ob_citation($item=null, $html=null)
{
    if ($item) {
        $html .= '<div id="item-citation" class="element">';
        $html .= '<h3>'. __('Citation').'</h3>';
        $html .= '<div class="element-text">'.metadata('item', 'citation', array('no_escape' => true)).'</div>';
        $html .= '</div>';
    }
    return $html;
}

// return tags for item
function ob_tags($item=null, $html=null)
{
    if (metadata($item, 'has tags')) {
        $html .= '<div id="item-tags" class="element">';
        $html .= '<h3>'.__('Tags').'</h3>';
        $html .= '<div class="element-text">'.tag_string($item).'</div>';
        $html .= '</div>';
    }
    return $html;
}

// return collection for item
function ob_item_collection($item=null, $html=null)
{
    if (metadata($item, 'Collection Name')) {
        $html .= '<div id="collection" class="element">';
        $html .= '<h3>'.__('Collection').'</h3>';
        $html .= '<div class="element-text">';
        $html .= '<p>'.link_to_collection_for_item().'</p>';
        $html .= '</div>';
        $html .= '</div>';
    }
    return $html;
}

// return output formats list
function ob_output_formats($item=null, $html=null)
{
    if ($item) {
        $html .= '<div id="item-output-formats" class="element">';
        $html .= '<h3>'.__('Output Formats').'</h3>';
        $html .= '<div class="element-text">'.output_format_list().'</div>';
        $html .= '</div>';
    }
    return $html;
}

// return item pagination
function ob_item_pagination($item=null, $html=null)
{
    if ($item) {
        $html .= '<nav>';
        $html .= '<ul class="item-pagination navigation">';
        $html .= '<li id="previous-item" class="previous">'.link_to_previous_item_show().'</li>';
        $html .= '<li id="next-item" class="next">'.link_to_next_item_show().'</li>';
        $html .= '</ul>';
        $html .= '</nav>';
    }
    return $html;
}
