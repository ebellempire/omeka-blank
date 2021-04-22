<?php
$collectionTitle = metadata('collection', 'display_title');
$total = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show','collection'=>$collection)); ?>

<!-- Title -->
<div id="collection-title">
    <h1><?php echo metadata($collection, 'rich_title', array('no_escape' => true)); ?></h1>
    <?php echo ob_byline($collection);?>
</div>

<!-- Primary Content -->
<div id="primary-content">
    <!-- Description -->
    <div class="main-text">
        <?php echo metadata($collection, array('Dublin Core','Description'));?>
    </div>

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
<?php echo ob_all_metadata($collection, get_theme_option('collections_full_record'));?>

<?php echo foot(); ?>