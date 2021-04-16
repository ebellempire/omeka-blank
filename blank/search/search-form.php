<?php echo $this->form('search-form', $options['form_attributes']); ?>

<!-- Query Input -->
<?php echo $this->formText('query', $filters['query'], array('title' => __('Search'))); ?>

<?php if ($options['show_advanced']): ?>
<!-- Advanced Search Form -->
<div id="advanced-form">
    <fieldset id="query-types">
        <legend><?php echo __('Search using this query type:'); ?></legend>
        <?php echo $this->formRadio('query_type', $filters['query_type'], null, $query_types); ?>
    </fieldset>
    <?php if ($record_types): ?>
    <fieldset id="record-types">
        <legend><?php echo __('Search only these record types:'); ?></legend>
        <?php foreach ($record_types as $key => $value): ?>

        <?php if ($value=='Item'):?>
        <?php $value = ob_item_label(); //tweak to use custom label...?>
        <?php endif;?>

        <?php echo $this->formCheckbox('record_types[]', $key, array('checked' => in_array($key, $filters['record_types']), 'id' => 'record_types-' . $key)); ?> <?php echo $this->formLabel('record_types-' . $key, $value);?><br>
        <?php endforeach; ?>
    </fieldset>
    <?php endif; ?>
    <p><?php echo link_to_item_search(__('... or go to Advanced %s Search', ob_item_label('plural'))); ?></p>
</div>
<?php else: ?>
<!-- Simple Search Form Attributes (Hidden) -->
<div hidden>
    <?php echo $this->formHidden('query_type', $filters['query_type']); ?>
    <?php foreach ($filters['record_types'] as $type): ?>
    <?php echo $this->formHidden('record_types[]', $type); ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Submit -->
<?php echo $this->formButton('submit_search', $options['submit_value'], array('type' => 'submit')); ?>
</form>