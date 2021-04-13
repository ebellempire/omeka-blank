<?php

function item_label($type="singular")
{
    if ($type="plural") {
        $label = get_theme_option('items_label_p') ? trim(get_theme_option('items_label_p')) : __('Items');
    } else {
        $label = get_theme_option('items_label_s') ? trim(get_theme_option('items_label_s')) : __('Item');
    }
    return $label;
}

function featured_item_label($type="singular")
{
    if ($type="plural") {
        $label = get_theme_option('featured_label_p') ? trim(get_theme_option('featured_label_p')) : __('Featured Items');
    } else {
        $label = get_theme_option('featured_label_s') ? trim(get_theme_option('featured_label_s')) : __('Featured Item');
    }
    return $label;
}

function featured_item_block($showlink=true, $html=null)
{
    if (get_theme_option('display_featured_item') !== '0') {
        $html .= '<div id="featured-item">';
        $html .= '<h2>'.featured_item_label('singular').'</h2>';
        $html .= random_featured_items(1, true);
        $html .= $showlink ? '<a href="/items/browse/?featured=1">View All '.featured_item_label('plural').'</a>' : null;
        $html .= '</div>';
    }

    return $html;
}
function donation_block($html = null)
{
    $url = get_theme_option('donate_link');
    $heading = get_theme_option('donate_heading');
    $text = get_theme_option('donate_text');
    $label = get_theme_option('donate_button_label');
    $target = get_theme_option('donate_target') ? 'target="_blank"' : null;

    if ($url) {
        $html .= '<div id="donate">';
        $html .= '<h2>'.($heading ? $heading : "Support Our Work").'</h2>';
        $html .= '<p>'.$text.'</p>';
        $html .= '<a href="'.$url.'" '.$target.'>'.($label ? $label : "Donate Now").'</a>';
        $html .= '</div>';
    }

    return $html;
}

function homepage_text_block_1($heading=null, $img=null, $html = null)
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


function homepage_text_block_2($heading=null, $img = null, $html = null)
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
