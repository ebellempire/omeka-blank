<?php
$pageTitle = __('Browse %s Tags', ob_item_label());
echo head(array('title' => $pageTitle, 'bodyclass' => 'items tags'));
?>

<div id="tags-title">
    <h1><?php echo $pageTitle; ?></h1>
</div>

<?php echo ob_secondary_nav(); ?>

<div id="primary-content">
    <?php
    if (get_theme_option('tag_images')==1) {
        echo '<div id="tag-image-gallery">';
        foreach ($tags as $tag) {
            echo ob_tag_image($tag->name);
        }
        echo '</div>';
    } else {
        echo tag_cloud($tags, 'items/browse');
    }
    ?>
</div>

<?php echo foot(); ?>