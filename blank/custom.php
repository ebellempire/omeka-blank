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
// data-attributes on container link are used for image viewer (see: javascripts/item-pswp.js)
// includes alt tags based on file metadata
function ob_img_markup($file, $size='fullsize', $index=0, $html=null)
{
    if (($url = $file->getWebPath($size))) {
        $title = ob_dublin($file, 'Title');
        $description = ob_dublin($file, 'Description');
        $record_url = '/files/show/'.$file->id;

        $html .= '<div class="item-file image '.$size.'" id="image-'.$index.'">';
        $html .= '<a href="'.$record_url.'" data-height="" data-width="" data-fullsize="'.$file->getWebPath('fullsize').'" data-title="'.strip_tags($title).'" data-description="'.strip_tags($description).'" data-id="'.$file->id.'" data-view-label="'.__('View File Details').'"><img alt="'.strip_tags(ob_dublin($file, 'Description', array('Title'))).'" src="'.$url.'"/></a>';
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
        $html .= __("Your browser does not support HTML &lt;audio&rt; tag");
        $html .= ' &rarr; <a href="'.$url.'">'.__('Download the file').'</a>';
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
        $html .= __("Your browser does not support HTML &lt;video&rt; tag");
        $html .= ' &rarr; <a href="'.$url.'">'.__('Download the file').'</a>';
        $html .= '</video>';
        $html .= '</div>';
    }
    return $html;
}

// looks for the URL metadata field and converts relevant links to embed codes
// uses responsive iframe markup when possible
// @todo: add additional services, e.g. twitter?
function ob_embed_codes($item=null, $html=null)
{
    if ($urls = metadata($item, array('Item Type Metadata','URL'), array('all'=>true))) {
        foreach ($urls as $url) {
            $url = parse_url(strip_tags(trim($url)));
            if (isset($url['host'])) {
                // YouTube
                if ($url['host'] == ('youtu.be' || 'www.youtube.com') && isset($url['query'])) {
                    $html .= '<div class="item-file embed youtube" style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://www.youtube.com/embed/'.str_replace('v=', '', $url['query']).'" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                }
                // Vimeo
                if ($url['host'] == 'vimeo.com' && isset($url['path'])) {
                    $html .= '<div class="item-file embed vimeo" style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video'.$url['path'].'" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Vimeo video player" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
                }
            }
        }
    }
    return $html;
}

// return custom markup for all files
// defaults to standard Omeka output when mime type is not on the whitelist (e.g. ob_isAudio($mime))
// set $gallery=true to use one fullsize image followed by thumbnail images
// If the URL metadata field is an embeddable link, we'll create an embed code and put it at the top
function ob_item_files($item=null, $gallery=false, $img_index=0, $audio_index=0, $video_index=0, $gallery_html = null, $html=null)
{
    if (get_theme_option('auto_embed_services') == 1) {
        $html .= ob_embed_codes($item);
    }

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
// not recommended if you plan to internationalize the text labels as that process requires static strings
function ob_item_label($type="singular")
{
    if ($type !== "singular") {
        $label = get_theme_option('items_label_p') ? get_theme_option('items_label_p') : __('Items');
    } else {
        $label = get_theme_option('items_label_s') ? get_theme_option('items_label_s') : __('Item');
    }
    return trim(html_escape($label));
}

// preferred label for featured items
// not recommended if you plan to internationalize the text labels as that process requires static strings
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
// esp. useful if you have a dedicated area for "main" text
// e.g. ob_dublin($record, 'Creator', array('Contributor','Publisher'))
function ob_dublin($record=null, $element=null, $fallbacks=array(), $html=null)
{
    if (element_exists('Dublin Core', $element) && ($el = metadata($record, array('Dublin Core',$element)))) {
        $html .= $el;
    } else {
        foreach ($fallbacks as $fallback) {
            if (element_exists('Dublin Core', $fallback) && ($alt = metadata($record, array('Dublin Core',$fallback)))) {
                return $alt;
            }
        }
    }
    return $html;
}

// get Item Type metadata with fallbacks
// fallbacks must be valid Item Type elements
// esp. useful if you have a dedicated area for "main" text
// e.g. ob_item_type($record, 'Abstract', array('Text','Transcription','Lesson Plan Text'))
function ob_item_type($item=null, $element=null, $fallbacks=array(), $html=null)
{
    if (element_exists('Item Type Metadata', $element) && ($element_text = metadata($item, array('Item Type Metadata',$element)))) {
        $html .= $element_text;
    } else {
        foreach ($fallbacks as $fallback) {
            if (element_exists('Item Type Metadata', $fallback) && ($alt = metadata($item, array('Item Type Metadata',$fallback)))) {
                return $alt;
            }
        }
    }
    return $html;
}

// return select metadata fields
// esp. useful if you plan to foreground certain metadata fields
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
        $html .= $showlink ? '<a href="/items/browse/?featured=1">'.__('View All %s', ob_featured_item_label('plural')).'</a>' : null;
        $html .= '</div>';
    }

    return $html;
}

// return a single recent item using the preferred label
// @todo: add req. theme options!!!
function ob_recent_item_block($showlink=true, $html=null)
{
    if (get_theme_option('display_recent_item') !== '0') {
        $html .= '<div id="recent-item">';
        $html .= '<h2>'.__('Recently Added %s', ob_item_label('singular')).'</h2>';
        $html .= recent_items(1, true);
        $html .= $showlink ? '<a href="/items/browse/">'.__('View All %s', ob_item_label('plural')).'</a>' : null;
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
        $html .= '<aside id="cta">';
        $html .= '<h2>'.$heading.'</h2>';
        $html .= '<p>'.$text.'</p>';
        $html .= '<a href="'.$url.'" '.$target.'>'.$label.'</a>';
        $html .= '</aside>';
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

// returns a styled button using the URL metadata field
// returns null if the URL field is used more than once per item (or if admin has disabled this option)
// this prevents UX issues that might be caused if admin is also using ob_embed_codes() for multiple URLs
// see also: ob_embed_codes()
function ob_item_url($item=null, $index=0, $html=null)
{
    if (get_theme_option('url_button') == 1) {
        if ($urls = metadata($item, array('Item Type Metadata','URL'), array('all'=>true))) {
            // skip if > 1
            if (count($urls) > 1) {
                return null;
            }
            // create the linked button
            $url = parse_url(strip_tags(trim($urls[0])));
            if (isset($url['host'])) {
                $html .= '<a class="button" id="external-link-'.$index.'" href="'.$urls[0].'" target="_blank">'.__('view @ %s', str_replace('www.', '', $url['host'])).'</a>';
            }
        }
    }

    return $html;
}

// return citation markup
function ob_citation($item=null, $html=null)
{
    if ($item) {
        $html .= '<div id="item-citation" class="element">';
        $html .= '<h3>'. __('Citation').'</h3>';
        $html .= '<div class="element-text">'.metadata($item, 'citation', array('no_escape' => true)).'</div>';
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
        $html .= '<div class="element-text">'.output_format_list(false).'</div>';
        $html .= '</div>';
    }
    return $html;
}

// return secondary nav for browse views
// primarily for items/browse but can also be used for similar menus elsewhere
function ob_secondary_nav($type='items', $collection_id=null)
{
    if ($type == 'items') {
        $navArray = array(array(
            'label' =>__('All %s', ob_item_label('plural')),
            'uri' => url('items/browse'),
        ));

        if (get_theme_option('featured_secondary_nav') == 1) {
            $navArray[] = array(
                 'label' => ob_featured_item_label('plural'),
                 'uri' => url('items/browse?featured=1'));
        }


        if (total_records('Tag')) {
            $navArray[] = array(
                'label' => __('%s Tags', ob_item_label()),
                'uri' => url('items/tags'));
        }

        if (plugin_is_active("Geolocation")) {
            $navArray[] = array(
                'label' => __('%s Map', ob_item_label()),
                'uri' => url('items/map'));
        }

        $navArray[] = array(
            'label' => __('%s Search', ob_item_label()),
            'uri' => url('items/search'));

        $navArray[] = array(
            'label' => __('Site Search'),
            'uri' => url('search'));

        return '<nav class="items-nav navigation secondary-nav">'.public_nav_items($navArray).'</nav>';
    } elseif (($type == 'collection') && (is_int($collection_id))) {
        $navArray = array(array(
            'label' =>__('Recent %s', ob_item_label('plural')),
            'uri' => url('collections/show/'.$collection_id),
            ));

        $navArray[]  = array(
            'label' =>__('All %s', ob_item_label('plural')),
            'uri' => url('items/browse?collection='.$collection_id),
            );

        return '<nav class="items-nav navigation secondary-nav">'.public_nav_items($navArray).'</nav>';
    } elseif ($type == 'collections') {
        $navArray = array(array(
            'label' =>__('All Collections'),
            'uri' => url('collections/browse/'),
            ));
        $navArray[]  = array(
            'label' =>__('Featured Collections'),
            'uri' => url('collections/browse?featured=1'),
            );
        return '<nav class="items-nav navigation secondary-nav">'.public_nav_items($navArray).'</nav>';
    } elseif ($type == 'exhibits') {
        $navArray = array(
            array(
                'label' => __('All Exhibits'),
                'uri' => url('exhibits')
            ),
            array(
                'label' => __('Exhibit Tags'),
                'uri' => url('exhibits/tags')
            )
        );
        return '<nav class="items-nav navigation secondary-nav">'.public_nav_items($navArray).'</nav>';
    } else {
        return null;
    }
}

// return item type selection dropdown
// @todo: this is going to return some empty results if other query params are set
// i.e. the totals only represent all items of a type, not items of a type within a subset of items being queried
function ob_item_type_selection($showcount = false, $options=null, $html=null)
{
    $totalRecords = intval(total_records('Item'));
    $withItemType = 0;
    $types = get_records('ItemType');
    foreach ($types as $type) {
        if ($type->totalItems()) {
            $withItemType += $type->totalItems();
            $count = $showcount ? '('.$type->totalItems().')' : null;
            $options .= '<option value="'.$type->id.'">'.$type->name.' '.$count.'</option>';
        }
    }
    $count = $showcount ? '('.($totalRecords - $withItemType).')' : null;
    $options .= '<option value="0">'.__("Other").' '.$count.'</option>';
    $html .= '<div id="item-type-selection">';
    $html .= '<select onchange="item_type_filter();">';
    $html .= '<option>'.__('Filter by Item Type').'</option>'.$options;
    $html .= '</select>';
    $html .= '</div>';
    return $html;
}

// return sort links
function ob_sort_links($type='items', $html=null)
{
    if ($type == 'collections') {
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Date Added')] = 'added';
    } elseif ($type == 'exhibits') {
        $sortLinks[__('Date Added')] = 'added';
    } elseif ($type == 'search') {
        $sortLinks[__('Type')] = 'record_type';
        $sortLinks[__('Title')] = 'title';
    } else {
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Creator')] = 'Dublin Core,Creator';
        $sortLinks[__('Date Added')] = 'added';
    }
    $html .= '<div id="sort-links" class="sort-'.$type.'">';
    $html .= '<span class="sort-label">'.__('Sort by: ').'</span>';
    $html .= browse_sort_links($sortLinks);
    $html .= '</div>';

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

// takes an array of texts and returns a formatted string
// e.g. ['Larry','Curly','Moe'] returns "Larry, Curly, & Moe"
// adapted from Omeka:Item->getCitation()
function ob_chicago_style($texts=array(), $text=null)
{
    switch (count($texts)) {
        case 1:
            $text = $texts[0];
            break;
        case 2:
            $text = __('%1$s and %2$s', $texts[0], $texts[1]);
            break;
        case 3:
            $text = __('%1$s, %2$s, &amp; %3$s', $texts[0], $texts[1], $texts[2]);
            break;
        default:
            $text = __('%s et al.', $texts[0]);
    }
    return $text;
}

// return a standard byline for an item or collection
function ob_byline($record=null, $separator=' | ', $creators=array(), $contributors=array(), $byline=array())
{
    if (get_theme_option('byline_creator')) {
        if ($all_creators = metadata($record, array('Dublin Core', 'Creator'), array('all'=>true))) {
            $byline[] = '<span class="byline-creators"><span class="byline-label">'.(count($all_creators) == 1 ? __('Creator') : __('Creators')).':</span><span> '.ob_chicago_style($all_creators).'</span></span>';
        }
    }

    if (get_theme_option('byline_contributor')) {
        if ($all_contributors = metadata($record, array('Dublin Core', 'Contributor'), array('all'=>true))) {
            $byline[] = '<span class="byline-contributors"><span class="byline-label">'.(count($all_contributors) == 1 ? __('Contributor') : __('Contributors')).':</span><span> '.ob_chicago_style($all_contributors).'</span></span>';
        }
    }

    if (get_theme_option('byline_date')) {
        if ($dates = metadata($record, array('Dublin Core', 'Date'), array('all'=>true))) {
            if (count($dates) > 1) {
                $date = __('%1s to %2s', $dates[0], $dates[(count($dates)-1)]);
            } else {
                $date = $dates[0];
            }
            $byline[] = '<span class="byline-date"><span class="byline-label">'.(count($dates) == 1 ? __('Date') : __('Dates')).':</span><span> '.$date.'</span></span>';
        }
    }

    return count($byline) ? '<div id="byline">'.implode('<span class="separator">'.$separator.'</span>', $byline).'</div>' : null;
}

// returns the item description with fallbacks
// set $snippet to true to return truncated text
function ob_item_description($item=null, $snippet=false, $length=250, $html=null)
{
    $html .= metadata($item, array('Dublin Core', 'Description'));
    if (!$html) {
        $html .= ob_item_type($item, 'Abstract', array('Text','Lesson Plan Text','Transcription'));
    }
    if (!$html && $snippet) {
        $html .= __('Preview text unavailable.');
    }
    return $snippet ? strip_tags(snippet($html, 0, $length, '&hellip;')) : $html;
}

// returns a fallback thumbnail image even if the record has no files
function ob_item_image($item = null, $html = null)
{
    if ($item) {
        if (metadata($item, 'has thumbnail')) {
            // if there is a thumbnail
            $html .= item_image();
        } elseif (metadata($item, 'has files')) {
            // if there is a file but no thumbnail (e.g. audio)
            $html .= item_image(); // e.g. images/fallback-audio.png
        } else {
            // if there is no file and a thumbnail is desired
            // does it have an assigned type?
            $type = get_record_by_id('ItemType', $item->item_type_id);
            if ($type && isset($type['name'])) {
                $typename = $type['name'];

                switch ($typename) {

                    case 'Oral History':
                    $src = img('fallback-audio.png');
                    break;

                    case 'Sound':
                    $src = img('fallback-audio.png');
                    break;

                    case 'Moving Image':
                    $src = img('fallback-video.png');
                    break;

                    case strripos($typename, 'Audio'):
                    // any (custom?) type containing the string 'audio'
                    $src = img('fallback-audio.png');
                    break;

                    case strripos($typename, 'Video'):
                    // any (custom?) type containing the string 'video'
                    $src = img('fallback-video.png');
                    break;

                    case strripos($typename, 'Image'):
                    // any (custom?) type containing the string 'image'
                    $src = img('fallback-image.png');
                    break;

                    default:
                    $src = img('fallback-file.png');

                }

                $html .= '<img class="placeholder-image" src="'.$src.'"/>';
            } else {
                // no item type and no file?
            }
        }
    }
    return $html;
}

// returns item metadata card for browse views, etc
function ob_item_card($item=null, $view=null, $html=null)
{
    if ($item) {
        $html .= '<div class="item hentry">';
        $html .= '<h2>'.link_to_item(null, array('class' => 'permalink')).'</h2>';
        $html .= '<div class="item-meta">';
        $html .= '<div class="item-img">';
        $html .= link_to_item(ob_item_image($item));
        $html .= '</div>';


        $html .= '<div class="item-description">';
        $html .= ob_item_description($item, 250);
        $html .= '</div>';

        if (metadata('item', 'has tags')) {
            $html .= '<div class="tags">';
            $html .= '<p><strong>'.__('Tags').':</strong> '.tag_string('items').'</p>';
            $html .= '</div>';
        }

        $html .= '</div>';
        if ($view) {
            fire_plugin_hook('public_items_browse_each', array('view' => $view, 'item' => $item));
        }
        $html .= '</div>';
    }
    return $html;
}

// returns collection metadata card for browse views, etc
function ob_collection_card($collection=null, $view=null, $html=null)
{
    if ($collection) {
        $html .= '<div class="collection hentry">';
        $html .= '<h2>'.link_to_collection().'</h2>';
        if ($collectionImage = record_image('collection')) {
            $html .= link_to_collection($collectionImage, array('class' => 'image'));
        }

        if (metadata('collection', array('Dublin Core', 'Description'))) {
            $html .= '<div class="collection-description">';
            $html .= metadata('collection', array('Dublin Core', 'Description'), array('snippet' => 250));
            $html .= '</div>';
        }

        if ($view) {
            $html .= fire_plugin_hook('public_collections_browse_each', array('view' => $view, 'collection' => $collection));
        }

        $html .= '</div>';
    }
    return $html;
}

// returns exhibit metadata card for browse views, etc.
function ob_exhibit_card($exhibit=null, $view=null, $html=null)
{
    $html .= '<div class="exhibit hentry">';
    $html .= '<h2>'.link_to_exhibit().'</h2>';
    if ($exhibitImage = record_image($exhibit)) {
        $html .= exhibit_builder_link_to_exhibit($exhibit, $exhibitImage, array('class' => 'image'));
    }
    if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))) {
        $html .= '<div class="exhibit-description">';
        $html .= $exhibitDescription;
        $html .= ' </div>';
    }
    if ($exhibitTags = tag_string('exhibit', 'exhibits')) {
        $html .= '<p class="tags">'.$exhibitTags.'</p>';
    }
    $html .= '</div>';

    return $html;
}

// returns record metadata card for site search results
// includes (fallback) images, text snippets, and add'l context whenever possible
function ob_search_record_card($searchText=null, $view=null, $html=null)
{
    $unCamel = new Zend_Filter_Word_CamelCaseToSeparator(' ');
    $dashCamel = new Zend_Filter_Word_CamelCaseToSeparator('-');
    $record = get_record_by_id($searchText['record_type'], $searchText['record_id']);
    $recordType = $searchText['record_type'];
    $typeClass = strtolower($dashCamel->filter($recordType));
    $typeLabel = $recordType == "Item" ? ob_item_label() : $unCamel->filter(str_replace('PagesPage', 'Page', $recordType));
    $recordTitle = $searchText['title'] ? $searchText['title'] : '['.__('Untitled').']';
    set_current_record($recordType, $record);

    switch ($recordType) {
        case 'Item':
        $recordImage = ob_item_image($record);
        $recordText = ob_item_description($record, true, 250);
        break;

        case 'Collection':
        $recordImage = record_image($recordType);
        $recordText = metadata($record, array('Dublin Core', 'Description'), array('snippet'=>250));
        break;

        case 'File':
        if (substr($recordTitle, 0, 4) === "http") {
            $recordTitle = '['.__('Untitled').']';
        }
        $recordImage = record_image($recordType);
        $recordText = __('Appears in %s', ob_item_label()).': '.link_to_item(null, array(), 'show', $record->getItem());
        break;

        case 'Exhibit':
        $recordImage = record_image($recordType);
        $recordText = metadata($record, 'description', array('no_escape' => true, 'snippet'=>250));
        break;

        case 'ExhibitPage':
        $parent = $record->getExhibit();
        $recordText = __('Appears in Exhibit').': <a href="/exhibits/show/'.$parent->slug.'">'.metadata($parent, 'title', array('no_escape' => true, 'snippet'=>250)).'</a>';
        break;

        case 'SimplePagesPage':
        $recordText = metadata('simple_pages_page', 'text', array('no_escape' => true, 'snippet'=>250));
        break;

        default:
        $recordImage = record_image($recordType);
    }

    $html .= '<div class="search hentry '.$typeClass.'">';
    $html .= '<div class="search-record-type">'.$typeLabel.'</div>';
    $html .= '<h2><a href="'.record_url($record, 'show').'">'.$recordTitle.'</a></h2>';
    $html .= $recordImage ? link_to($record, 'show', $recordImage, array('class' => 'search-image')) : null;
    $html .= '<div class="search-description">'.($recordText ? $recordText : __('Preview text unavailable.')).'</div>';
    $html .= '</div>';

    return $html;
}

// returns full metadata record with or without markup for interactive toggle state
function ob_all_metadata($record =null, $show=1, $html=null)
{
    $html .= '<div data-button-label="'.__('View Additional Details').'" data-button-label-hide="'.__('Hide Additional Details').'" id="full-metadata-record" class="'.($show == 1 ? 'static' : 'interactive').'"><div class="meta-container-inner">';
    $html .= all_element_texts($record, array('show_element_set_headings'=>false));
    $html .= ob_output_formats($record);
    $html .= '</div></div>';
    return $html;
}


// returns svg markup for icons
// https://ionicons.com/
// MIT License
function ob_svg_hamburger_icon($size=30)
{
    return "<span class='icon open-menu'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Menu')."</title><path d='M64 384h384v-42.67H64zm0-106.67h384v-42.66H64zM64 128v42.67h384V128z'/></svg></span>";
}

function ob_svg_search_icon($size=30)
{
    return "<span class='icon open-search'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Search')."</title><path d='M464 428L339.92 303.9a160.48 160.48 0 0030.72-94.58C370.64 120.37 298.27 48 209.32 48S48 120.37 48 209.32s72.37 161.32 161.32 161.32a160.48 160.48 0 0094.58-30.72L428 464zM209.32 319.69a110.38 110.38 0 11110.37-110.37 110.5 110.5 0 01-110.37 110.37z'/></svg></span>";
}

function ob_svg_facebook_icon($size=30)
{
    return "<span class='icon facebook'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Facebook')."</title><path d='M480 257.35c0-123.7-100.3-224-224-224s-224 100.3-224 224c0 111.8 81.9 204.47 189 221.29V322.12h-56.89v-64.77H221V208c0-56.13 33.45-87.16 84.61-87.16 24.51 0 50.15 4.38 50.15 4.38v55.13H327.5c-27.81 0-36.51 17.26-36.51 35v42h62.12l-9.92 64.77H291v156.54c107.1-16.81 189-109.48 189-221.31z' fill-rule='evenodd'/></svg></span>";
}

function ob_svg_twitter_icon($size=30)
{
    return "<span class='icon twitter'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Twitter')."</title><path d='M496 109.5a201.8 201.8 0 01-56.55 15.3 97.51 97.51 0 0043.33-53.6 197.74 197.74 0 01-62.56 23.5A99.14 99.14 0 00348.31 64c-54.42 0-98.46 43.4-98.46 96.9a93.21 93.21 0 002.54 22.1 280.7 280.7 0 01-203-101.3A95.69 95.69 0 0036 130.4c0 33.6 17.53 63.3 44 80.7A97.5 97.5 0 0135.22 199v1.2c0 47 34 86.1 79 95a100.76 100.76 0 01-25.94 3.4 94.38 94.38 0 01-18.51-1.8c12.51 38.5 48.92 66.5 92.05 67.3A199.59 199.59 0 0139.5 405.6a203 203 0 01-23.5-1.4A278.68 278.68 0 00166.74 448c181.36 0 280.44-147.7 280.44-275.8 0-4.2-.11-8.4-.31-12.5A198.48 198.48 0 00496 109.5z'/></svg></span>";
}

function ob_svg_youtube_icon($size=30)
{
    return "<span class='icon youtube'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('YouTube')."</title><path d='M508.64 148.79c0-45-33.1-81.2-74-81.2C379.24 65 322.74 64 265 64h-18c-57.6 0-114.2 1-169.6 3.6C36.6 67.6 3.5 104 3.5 149 1 184.59-.06 220.19 0 255.79q-.15 53.4 3.4 106.9c0 45 33.1 81.5 73.9 81.5 58.2 2.7 117.9 3.9 178.6 3.8q91.2.3 178.6-3.8c40.9 0 74-36.5 74-81.5 2.4-35.7 3.5-71.3 3.4-107q.34-53.4-3.26-106.9zM207 353.89v-196.5l145 98.2z'/></svg></span>";
}

function ob_svg_pinterest_icon($size=30)
{
    return "<span class='icon pinterest'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Pinterest')."</title><path d='M256.05 32c-123.7 0-224 100.3-224 224 0 91.7 55.2 170.5 134.1 205.2-.6-15.6-.1-34.4 3.9-51.4 4.3-18.2 28.8-122.1 28.8-122.1s-7.2-14.3-7.2-35.4c0-33.2 19.2-58 43.2-58 20.4 0 30.2 15.3 30.2 33.6 0 20.5-13.1 51.1-19.8 79.5-5.6 23.8 11.9 43.1 35.4 43.1 42.4 0 71-54.5 71-119.1 0-49.1-33.1-85.8-93.2-85.8-67.9 0-110.3 50.7-110.3 107.3 0 19.5 5.8 33.3 14.8 43.9 4.1 4.9 4.7 6.9 3.2 12.5-1.1 4.1-3.5 14-4.6 18-1.5 5.7-6.1 7.7-11.2 5.6-31.3-12.8-45.9-47-45.9-85.6 0-63.6 53.7-139.9 160.1-139.9 85.5 0 141.8 61.9 141.8 128.3 0 87.9-48.9 153.5-120.9 153.5-24.2 0-46.9-13.1-54.7-27.9 0 0-13 51.6-15.8 61.6-4.7 17.3-14 34.5-22.5 48a225.13 225.13 0 0063.5 9.2c123.7 0 224-100.3 224-224S379.75 32 256.05 32z'/></svg></span>";
}

function ob_svg_instagram_icon($size=30)
{
    return "<span class='icon instagram'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Instagram')."</title><path d='M349.33 69.33a93.62 93.62 0 0193.34 93.34v186.66a93.62 93.62 0 01-93.34 93.34H162.67a93.62 93.62 0 01-93.34-93.34V162.67a93.62 93.62 0 0193.34-93.34h186.66m0-37.33H162.67C90.8 32 32 90.8 32 162.67v186.66C32 421.2 90.8 480 162.67 480h186.66C421.2 480 480 421.2 480 349.33V162.67C480 90.8 421.2 32 349.33 32z'/><path d='M377.33 162.67a28 28 0 1128-28 27.94 27.94 0 01-28 28zM256 181.33A74.67 74.67 0 11181.33 256 74.75 74.75 0 01256 181.33m0-37.33a112 112 0 10112 112 112 112 0 00-112-112z'/></svg></span>";
}

function ob_svg_tiktok_icon($size=30)
{
    return "<span class='icon tiktok'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('TikTok')."</title><path d='M412.19 118.66a109.27 109.27 0 01-9.45-5.5 132.87 132.87 0 01-24.27-20.62c-18.1-20.71-24.86-41.72-27.35-56.43h.1C349.14 23.9 350 16 350.13 16h-82.44v318.78c0 4.28 0 8.51-.18 12.69 0 .52-.05 1-.08 1.56 0 .23 0 .47-.05.71v.18a70 70 0 01-35.22 55.56 68.8 68.8 0 01-34.11 9c-38.41 0-69.54-31.32-69.54-70s31.13-70 69.54-70a68.9 68.9 0 0121.41 3.39l.1-83.94a153.14 153.14 0 00-118 34.52 161.79 161.79 0 00-35.3 43.53c-3.48 6-16.61 30.11-18.2 69.24-1 22.21 5.67 45.22 8.85 54.73v.2c2 5.6 9.75 24.71 22.38 40.82A167.53 167.53 0 00115 470.66v-.2l.2.2c39.91 27.12 84.16 25.34 84.16 25.34 7.66-.31 33.32 0 62.46-13.81 32.32-15.31 50.72-38.12 50.72-38.12a158.46 158.46 0 0027.64-45.93c7.46-19.61 9.95-43.13 9.95-52.53V176.49c1 .6 14.32 9.41 14.32 9.41s19.19 12.3 49.13 20.31c21.48 5.7 50.42 6.9 50.42 6.9v-81.84c-10.14 1.1-30.73-2.1-51.81-12.61z'/></svg></span>";
}

function ob_svg_snapchat_icon($size=30)
{
    return "<span class='icon snapchat'><svg height='".$size."' width='".$size."' xmlns='http://www.w3.org/2000/svg' class='ionicon' viewBox='0 0 512 512'><title>".__('Snapchat')."</title><path d='M496 347.21a190.31 190.31 0 01-32.79-5.31c-27.28-6.63-54.84-24.26-68.12-52.43-6.9-14.63-2.64-18.59 11.86-24 14.18-5.27 29.8-7.72 36.86-23 5.89-12.76 1.13-27.76-10.41-35.49-15.71-10.53-30.35-.21-46.62 2.07 3.73-46.66 8.66-88.57-22.67-127.73C338.14 48.86 297.34 32 256.29 32s-81.86 16.86-107.81 49.33c-31.38 39.26-26.4 81.18-22.67 127.92-16.32-2.25-30.81-12.79-46.63-2.18-14.72 9.85-17 29.76-5.44 43s31.64 9.5 43.45 20.6c6.49 6.09 3.49 12.61-.35 20.14-14.48 28.4-39.26 45.74-69.84 51.56-4 .76-22.31 2.87-31 3.65 0 9.28.52 16.78 1.63 21.73 2.94 13.06 12.32 23.58 23.69 30.1 11.18 6.4 35.48 6.43 41.68 15.51 3 4.48 1.76 12.28 5.33 17.38a23.8 23.8 0 0015.37 9.75c18.61 3.61 37.32-7.2 56.42-2.1 14.85 3.95 26.52 15.87 39.26 24 15.51 9.85 32.34 16.42 50.83 17.49 38.1 2.21 59.93-18.91 90.58-36.42 19.5-11.14 38.15-3.86 58.88-2.68 20.1 1.15 23.53-9.25 29.62-24.88a27.37 27.37 0 001.54-4.85 10.52 10.52 0 002.28-1.47c2-1.57 10.55-2.34 12.76-2.86 10.28-2.44 20.34-5.15 29.17-11.2 11.31-7.76 17.65-18.5 19.58-32.64a93.73 93.73 0 001.38-15.67zM208 128c8.84 0 16 10.74 16 24s-7.16 24-16 24-16-10.74-16-24 7.16-24 16-24zm103.62 77.7c-15.25 15-35 23.3-55.62 23.3a78.37 78.37 0 01-55.66-23.34 8 8 0 0111.32-11.32A62.46 62.46 0 00256 213c16.39 0 32.15-6.64 44.39-18.7a8 8 0 0111.23 11.4zM304 176c-8.84 0-16-10.75-16-24s7.16-24 16-24 16 10.75 16 24-7.16 24-16 24z'/></svg></span>";
}

// returns markup for configured social media icons
// validation: just check to see if it's a valid URL
// @todo: twitch, discord, reddit, app store, google play, etc.?
function ob_social_links($html = null)
{
    if ($url=get_theme_option('social_facebook')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_facebook_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_instagram')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_instagram_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_twitter')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_twitter_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_youtube')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_youtube_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_pinterest')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_pinterest_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_tiktok')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_tiktok_icon().'</a>';
        }
    }
    if ($url=get_theme_option('social_snapchat')) {
        $test = parse_url($url);
        if (isset($test['host'])) {
            $html .= '<a href="'.$url.'" target="_blank">'.ob_svg_snapchat_icon().'</a>';
        }
    }

    return $html ? '<div id="social-media-links">'.$html.'</div>' : null;
}

// returns search container
// See also: globals.js
function ob_search_container($html=null)
{
    $html .= '<div class="search-container" role="search">';
    $html .= '<div class="'.(get_theme_option('advanced_header_search')==1 ? 'advanced' : 'simple').'-search-inner">';
    $html .= search_form(array('show_advanced' => (get_theme_option('advanced_header_search')==1)));
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

// returns hidden markup for side menu
function ob_mmenu_markup($html=null)
{
    $html .= '<div style="display:none;visibility:hidden;">';
    $html .= '<div id="mmenu-contents" data-title="'.__('Menu').'" data-theme="'.($theme = get_theme_option('menu_theme') ? $theme : 'light').'">';
    $html .= public_nav_main();
    $html .= '</div>';
    $html .='</div>';
    return $html;
}

// returns hidden markup for image viewer
function ob_photoswipe_markup($item=null, $html=null)
{
    if ($item && metadata($item, 'has thumbnail')) {
        $html.='<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                          <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div> 
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>';
    }

    return $html;
}

// returns site info markup
// theme info reads directly from theme.ini so make your changes there as desired
function ob_site_info($output=array(), $html=null)
{
    if (get_theme_option('omeka')) {
        $output[] .= '<p class="site-info cms">'.__('Proudly powered by %s', '<a href="http://omeka.org">Omeka</a>');
    }

    if (get_theme_option('omeka_theme')) {
        $output[] = __(
            'Theme: %1s by %2s',
            '<a href="'.Theme::getTheme(Theme::getCurrentThemeName('public'))->support_link.'" target="_blank">'.Theme::getTheme(Theme::getCurrentThemeName('public'))->title.'</a>',
            '<a href="'.Theme::getTheme(Theme::getCurrentThemeName('public'))->website.'" target="_blank">'.Theme::getTheme(Theme::getCurrentThemeName('public'))->author.'</a>'
        ).'</p>';
    }

    $html .= implode(' | ', $output);

    return $html;
}

// SEO/Social: eturns page description
function ob_seo_pagedesc($item=null, $file=null, $collection=null)
{
    if ($item != null) {
        $itemdesc=ob_item_description($item);
        return htmlspecialchars(strip_tags($itemdesc));
    } elseif ($collection != null) {
        $collectiondesc = metadata($collection, array('Dublin Core','Description'));
        return htmlspecialchars(strip_tags($collectiondesc));
    } elseif ($file != null) {
        $filedesc=ob_dublin($file, 'Description');
        return htmlspecialchars(strip_tags($filedesc));
    } else {
        return option('description') ? option('description') : option('site_title');
    }
}

// SEO/Social: returns page image
function ob_seo_pageimg($item=null, $file=null, $collection=null)
{
    if ($item != null) {
        if ($itemimg=record_image($item, 'fullsize')) {
            preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $itemimg, $result);
            $img=array_pop($result);
        }
    } elseif ($collection != null) {
        if ($collectionimg=record_image($collection, 'fullsize')) {
            preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $collectionimg, $result);
            $img=array_pop($result);
        }
    } elseif ($file != null) {
        if ($fileimg=record_image($file, 'fullsize')) {
            preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $fileimg, $result);
            $img=array_pop($result);
        }
    } elseif ($bg = get_theme_option('site_banner_image')) {
        $img=WEB_ROOT.'/files/theme_uploads/'.$bg;
    }
    return isset($img) ? $img : '';
}

// returns tags with corresponding images
// make sure tag-images directory is accessible!
// for now, you must manually add each image to the theme's images/tag-images directory
// could be made automatic/dynamic but that might be a big server load and unpredictable
function ob_tag_image($tagname=null, $html=null)
{
    $src = '/themes/'.Theme::getCurrentThemeName('public').'/images/tag-images/'.trim($tagname).'.jpg';

    $html .= '<div class="tag-image-container">';
    $html .= '<a class="tag-image" 
    style="background-image:url('.$src.')" 
    href="/items/browse/?tags='.urlencode($tagname).'" 
    title="'.__('View %1s tagged %2s', ob_item_label('plural'), $tagname).'">';
    $html .= '<div class="tag-title">'.$tagname.'</div>';
    $html .='</a>';
    $html .= '</div>';
    return $html;
}
