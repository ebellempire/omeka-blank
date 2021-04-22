<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">

<head>
    <meta charset="utf-8">
    <?php if ($author = option('author')): ?>
    <meta name="author" content="<?php echo $author; ?>" />
    <?php endif; ?>
    <?php if ($copyright = option('copyright')): ?>
    <meta name="copyright" content="<?php echo $copyright; ?>" />
    <?php endif; ?>
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting(trim($title));
    }
    $titleParts[] = trim(option('site_title'));
    if (!isset($title) && get_theme_option('site_subheading')) {
        $titleParts[] = trim(
            get_theme_option('site_subheading')
        );
    }
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php
    $item = (isset($item)) ? $item : null;
    $file = (isset($file)) ? $file : null;
    $collection = (isset($collection)) ? $collection : null;
    ?>

    <!-- FB Open Graph stuff: see also robots.txt -->
    <meta property="og:title" content="<?php echo trim(implode(' | ', $titleParts)); ?>" />
    <meta property="og:image" content="<?php echo ob_seo_pageimg($item, $file, $collection);?>" />
    <meta property="og:site_name" content="<?php echo trim(option('site_title'));?>" />
    <meta property="og:description" content="<?php echo ob_seo_pagedesc($item, $file, $collection);?>" />

    <!-- Twitter Card stuff: see also robots.txt -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo trim(implode(' | ', $titleParts)); ?>">
    <meta name="twitter:description" content="<?php echo ob_seo_pagedesc($item, $file, $collection);?>">
    <meta name="twitter:image" content="<?php echo ob_seo_pageimg($item, $file, $collection);?>">

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view' => $this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file('normalize');
    queue_css_file('default');
    queue_css_file('custom');
    queue_css_file('mmenu', 'all', false, 'javascripts/mmenu');
    if ($typekit_id = trim(get_theme_option('typekit'))) {
        queue_css_url('https://use.typekit.net/'.$typekit_id.'.css');
    }
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php queue_js_file('globals'); ?>
    <?php queue_js_file('mmenu', 'javascripts/mmenu'); ?>
    <?php echo head_js(); ?>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
<a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
<?php fire_plugin_hook('public_body', array('view' => $this)); ?>
<div id="wrap">

    <header role="banner" style="background-image:url(<?php echo ($bg = get_theme_option('site_banner_image')) ? '/files/theme_uploads/'.$bg : '';?>);">

        <?php fire_plugin_hook('public_header', array('view' => $this)); ?>

        <div id="banner-container">
            <div id="site-title">
                <h1><?php echo link_to_home_page(theme_logo()); ?></h1>
                <?php if ($sh = get_theme_option('site_subheading')):?>
                <?php echo '<h2>'.$sh.'</h2>';?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Header Nav -->
        <div id="nav-container" class="top <?php echo get_theme_option('always_show_menu') ? 'always-show-menu' : 'sometimes-show-menu';?>">
            <nav id="top-nav" role="navigation">
                <?php echo public_nav_main(); ?>
                <div class="menu-icons">
                    <a href="/items/search" id="search-button"><?php echo ob_svg_search_icon();?></a>
                    <a href="/#" id="menu-button"><?php echo ob_svg_hamburger_icon();?></a>
                </div>
            </nav>
        </div>
        <?php echo ob_search_container();?>

    </header>

    <article id="content" role="main">

        <?php fire_plugin_hook('public_content_top', array('view' => $this)); ?>