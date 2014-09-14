<?php
$this->layout = '//layouts/column2';
$this->menu = array(
    array('label' => UserModule::t('List User'), 'url' => array('index')),
);
?>
<h1><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?></h1>
<?php
// For all users
$attributes = array(
    'username',
);


array_push($attributes, 'create_at', array(
    'name' => 'lastvisit_at',
    'value' => (($model->lastvisit_at != '0000-00-00 00:00:00') ? $model->lastvisit_at : UserModule::t('Not visited')),
        )
);

$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => $attributes,
));
?>
