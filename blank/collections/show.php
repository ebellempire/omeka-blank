<?php
$collectionTitle = metadata('collection', 'display_title');
$total = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<!-- Title -->
<div id="collection-title">
    <h1><?php echo metadata('collection', 'rich_title', array('no_escape' => true)); ?></h1>
</div>

<!-- Primary Content -->
<div id="primary-content">
    <!-- Description -->
    <?php echo metadata('collection', array('Dublin Core','Description'));?>

    <?php echo ob_secondary_nav('collection', $collection->id);?>

    <!-- Items from the collection -->
    <?php
     if ($total > 0) {
         foreach (loop('items') as $item) {
             echo ob_item_card($item, get_view());
         }
     } else {
         echo '<div>'.__("There are currently no items within this collection.").'</div>';
     }
    ?>
</div>

<!-- All Metadata -->
<div id="full-metadata-record">
    <?php echo all_element_texts('collection', array('show_element_set_headings'=>false)); ?>
</div>

<?php echo foot(); ?>