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
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    if (!isset($title) && get_theme_option('site_subheading')) {
        $titleParts[] = get_theme_option('site_subheading');
    }
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view' => $this)); ?>

    <!-- Stylesheets -->
    <?php
    queue_css_file('normalize');
    queue_css_file('style');
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
        <div id="nav-container" class="top">
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