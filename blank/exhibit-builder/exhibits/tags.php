<?php
$title = __('Browse Exhibits by Tag');
echo head(array('title' => $title, 'bodyclass' => 'exhibits tags'));
?>

<div id="tags-title">
    <h1><?php echo $title; ?></h1>
</div>

<?php echo ob_secondary_nav('exhibits');?>

<?php echo tag_cloud($tags, 'exhibits/browse'); ?>

<?php echo foot(); ?>