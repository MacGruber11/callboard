<?php
$this->menu = array(
    array('label' => UserModule::t('Create User'), 'url' => array('create')),
    array('label' => UserModule::t('List User'), 'url' => array('/user')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});	
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1><?php echo UserModule::t("Manage Users"); ?></h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
        ),
        array(
            'name' => 'username',
            'type' => 'raw',
            'value' => 'CHtml::link(UHtml::markSearch($data,"username"),array("admin/view","id"=>$data->id))',
        ),
        array(
            'name' => 'email',
            'type' => 'raw',
            'value' => 'CHtml::link(UHtml::markSearch($data,"email"), "mailto:".$data->email)',
        ),
        'create_at',
        'lastvisit_at',
        array(
            'name' => 'superuser',
            'value' => 'User::itemAlias("AdminStatus",$data->superuser)',
            'filter' => User::itemAlias("AdminStatus"),
        ),
        array(
            'name' => 'status',
            'value' => 'User::itemAlias("UserStatus",$data->status)',
            'filter' => User::itemAlias("UserStatus"),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ),
    ),
));
?>
