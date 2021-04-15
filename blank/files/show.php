<?php
$fileTitle = metadata('file', 'display_title');

if ($fileTitle != '') {
    $fileTitle = ': &quot;' . $fileTitle . '&quot; ';
} else {
    $fileTitle = '';
}
$fileTitle = __('%s', metadata('file', 'id')) . $fileTitle;
?>
<?php echo head(array('title' => $fileTitle, 'bodyclass' => 'files show primary-secondary')); ?>

<!-- Title -->
<div id="file-title">
    <h1><?php echo $fileTitle; ?></h1>
</div>

<!-- Primary Content -->
<div id="primary-content">

    <?php echo file_markup($file, array('imageSize' => 'fullsize')); ?>

    <?php echo '<div id="file-description">'.ob_dublin($file, 'Description').'</div>';?>

    <?php echo '<div id="appears-in"><span>'.__('Appears in %s', ob_item_label('singular')).
    ': </span>'.link_to_item(null, array(), 'show', $file->getItem()).'</div>'; ?>

</div>

<!-- All Metadata -->
<div id="full-metadata-record">

    <!-- Dublin Core -->
    <?php echo all_element_texts('file', array('show_element_set_headings'=>false)); ?>

    <!-- Format -->
    <div id="format-metadata">
        <div id="original-filename" class="element">
            <h3><?php echo __('Original Filename'); ?></h3>
            <div class="element-text"><?php echo metadata('file', 'Original Filename'); ?></div>
        </div>

        <div id="file-size" class="element">
            <h3><?php echo __('File Size'); ?></h3>
            <div class="element-text"><?php echo __('%s bytes', metadata('file', 'Size')); ?></div>
        </div>

        <div id="authentication" class="element">
            <h3><?php echo __('Authentication'); ?></h3>
            <div class="element-text"><?php echo metadata('file', 'Authentication'); ?></div>
        </div>
    </div>

    <!-- Type -->
    <div id="type-metadata">
        <div id="mime-type-browser" class="element">
            <h3><?php echo __('Mime Type'); ?></h3>
            <div class="element-text"><?php echo metadata('file', 'MIME Type'); ?></div>
        </div>
        <div id="file-type-os" class="element">
            <h3><?php echo __('File Type / OS'); ?></h3>
            <div class="element-text"><?php echo metadata('file', 'Type OS'); ?></div>
        </div>
    </div>

</div>

<?php echo foot();?>