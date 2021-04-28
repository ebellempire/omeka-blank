<?php
$pageTitle = __('Browse Collections');
echo head(array('title' => $pageTitle, 'bodyclass' => 'collections browse'));
?>

<div id="browse-title">
    <h1><?php echo $pageTitle; ?> <?php echo __('(%s total)', $total_results); ?></h1>
</div>

<?php echo item_search_filters(); ?>

<?php echo ob_secondary_nav('collections'); ?>

<?php echo ob_sort_links('collections');?>

<div id="primary-content">
    <?php
    if ($total_results) {
        foreach (loop('collections') as $collection) {
            echo ob_collection_card($collection, get_view());
        }
    } else {
        echo __('There are no Collections matching your request.');
    }
   ?>
</div>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_collections_browse', array('collections' => $collections, 'view' => $this)); ?>

<?php echo foot(); ?>