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
function ob_embed_codes($item=null, $html=null)
{
    if ($urls = metadata($item, array('Item Type Metadata','URL'), array('all'=>true))) {
        foreach ($urls as $url) {
            $url = parse_url(strip_tags(trim($url)));
            if (isset($url['host']) && isset($url['query'])) {
                // YouTube
                if ($url['host'] == ('youtu.be' || 'www.youtube.com')) {
                    $html .= '<div class="item-file embed youtube"><iframe width="560" height="315" src="https://www.youtube.com/embed/'.str_replace('v=', '', $url['query']).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                }
                // @todo: add additional services
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
                $html .= '<a class="button" id="external-link-'.$index.'" href="'.$url.'" target="_blank">'.__('view @ %s', str_replace('www.', '', $url['host'])).'</a>';
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

        $navArray[] = array( // @todo: this should be a theme option
            'label' => ob_featured_item_label('plural'),
            'uri' => url('items/browse?featured=1'));

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
    }
}

// return sort links
function ob_sort_links($type='items', $html=null)
{
    if ($type='collections') {
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Date Added')] = 'added';
    } elseif ($type='exhibits') {
        // @todo
    } else {
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Creator')] = 'Dublin Core,Creator';
        $sortLinks[__('Date Added')] = 'added';
    }

    $html .= '<div id="sort-links">';
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
        if (metadata($item, 'has files')) {
            // default behavior if there is a file
            $html .= item_image();
        } else {
            // customize when there is no file and a thumbnail is desired
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
            $html .= text_to_paragraphs(metadata('collection', array('Dublin Core', 'Description'), array('snippet' => 150)));
            $html .= '</div>';
        }

        if ($collection->hasContributor()) {
            $html .= '<div class="collection-contributors">';
            $html .= '<p><strong>'.__('Contributors').':</strong>'.metadata('collection', array('Dublin Core', 'Contributor'), array('all' => true, 'delimiter' => ', ')).'</p>';
            $html .= '</div>';
        }

        $html .= link_to_items_browse(__('View the items in %s', metadata('collection', 'rich_title', array('no_escape' => true))), array('collection' => metadata('collection', 'id')), array('class' => 'view-items-link'));

        if ($view) {
            $html .= fire_plugin_hook('public_collections_browse_each', array('view' => $view, 'collection' => $collection));
        }

        $html .= '</div>';
    }
    return $html;
}

// returns exhibit metadata card for browse views, search results
// @todo: !!!
function ob_exhibit_card($exhibit=null, $view=null, $html=null)
{
    return $html;
}

// returns file metadata card for search results
// @todo: !!!
function ob_file_card($file=null, $view=null, $html=null)
{
    return $html;
}

// returns file metadata card for search results
// @todo: !!!
function ob_site_search_results($record=null, $view=null, $html=null)
{
    return $html;
}

// returns full metadata record with or without interactive toggle state
function ob_all_metadata($record =null, $show=1, $html=null)
{
    $html .= '<div data-button-label="'.__('View Additional Details').'" id="full-metadata-record" class="'.($show == 1 ? 'static' : 'interactive').'">';
    $html .= all_element_texts($record, array('show_element_set_headings'=>false));
    $html .= '</div>';
    return $html;
}
