<?php
$title = __('Browse Exhibits');
echo head(array('title' => $title, 'bodyclass' => 'exhibits browse'));
?>
<div id="browse-title">
    <h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>
</div>

<?php echo ob_secondary_nav('exhibits');?>

<div id="primary-content">
    <?php if (count($exhibits) > 0): ?>
    <?php $exhibitCount = 0; ?>

    <?php foreach (loop('exhibit') as $exhibit): ?>
    <div class="exhibit">
        <?php $exhibitCount++; ?>
        <h2><?php echo link_to_exhibit(); ?></h2>
        <?php if ($exhibitImage = record_image($exhibit)): ?>
        <?php echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage, array('class' => 'image')); ?>
        <?php endif; ?>
        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
        <div class=" description">
            <?php echo $exhibitDescription; ?>
        </div>
        <?php endif; ?>
        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
        <p class="tags"><?php echo $exhibitTags; ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php else: ?>
    <p><?php echo __('There are no exhibits available yet.'); ?></p>
    <?php endif; ?>

</div>

<?php echo pagination_links(); ?>

<?php echo foot(); ?>