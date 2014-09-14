<?php
$this->menu = array(
    array('label' => 'List Item', 'url' => array('index')),
    array('label' => 'My Items', 'url' => array('admin')),
);
?>

<h1>Create Item</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>