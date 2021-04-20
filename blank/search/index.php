<?php
$pageTitle = __('Site Search') . ' ' . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<div id="search-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<?php echo search_filters(); ?>

<?php echo ob_secondary_nav(); ?>

<?php echo ob_sort_links('search');?>

<div id="primary-content">
    <?php
    if ($total_results) {
        foreach (loop('search_texts') as $searchText) {
            echo ob_search_record_card($searchText);
        }
    } else {
        echo '<div id="no-results"><p>'.__('Your query returned no results.').'</p></div>';
    }?>
</div>

<?php echo foot(); ?>