<?php

$this->menu = array(
    array('label' => 'List Item', 'url' => array('index')),
    array('label' => 'Create Item', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('item-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'item-grid',
    'dataProvider' => $model->getItemById(Yii::app()->user->id),
    'filter' => $model,
    'columns' => array(
        'name' => array(
            'name' => 'name',
            'type' => 'html',
            'value' => 'CHtml::link(CHtml::encode($data->name),
                         array("item/update","id" => $data->id))',
        ),
        array('name' => 'image',
            'type' => 'image',
            'value' => '"/images/small/".$data->id.".jpg"'
        ),
        array(
            'name' => 'date',
            'value' => 'date("Y-m-d H:i:s", $data->date)',
        ),
        'price',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete} {update}',
        ),
    ),
));
?>
