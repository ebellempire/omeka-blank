<?php
$pageTitle = __('Browse %s', ob_item_label('plural'));
echo head(array('title' => $pageTitle, 'bodyclass' => 'items browse'));
?>

<!-- Title -->
<div id="browse-title">
    <h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>
</div>

<?php echo item_search_filters(); ?>

<?php echo ob_secondary_nav(); ?>

<?php echo ob_sort_links();?>

<?php echo ob_item_type_selection();?>

<div id="primary-content">
    <?php
    if (count($items) > 0) {
        foreach (loop('items') as $item) {
            echo ob_item_card($item, get_view());
        }
    } else {
        echo __('There are no %s matching your request.', ob_item_label('plural'));
    } ?>
</div>

<?php echo pagination_links(); ?>

<div id="outputs">
    <span class="outputs-label"><?php echo __('Output Formats'); ?></span>
    <?php echo output_format_list(false); ?>
</div>

<?php fire_plugin_hook('public_items_browse', array('items' => $items, 'view' => $this)); ?>

<?php echo foot(); ?>