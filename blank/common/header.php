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
    if ($typekit_id = trim(get_theme_option('typekit'))) {
        queue_css_url('https://use.typekit.net/'.$typekit_id.'.css');
    }
    echo head_css();
    ?>

    <!-- JavaScripts -->
    <?php echo head_js(); ?>
</head>

<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
<a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
<?php fire_plugin_hook('public_body', array('view' => $this)); ?>
<div id="wrap">

    <header role="banner">

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
            <nav class="center" id="top-nav" role="navigation">
                <?php echo public_nav_main(); ?>
            </nav>
        </div>
        <div class="search-container" role="search">
            <!-- @todo: theme option for site search (simple/advanced) vs. items search -->
            <?php echo search_form(array('show_advanced' => true)); ?>
        </div>

    </header>

    <article id="content" role="main">

        <?php fire_plugin_hook('public_content_top', array('view' => $this)); ?>