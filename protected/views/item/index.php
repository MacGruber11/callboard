<?php
if (!Yii::app()->user->isGuest) {
    $this->menu = array(
        array('label' => 'Create Item', 'url' => array('create')),
        array('label' => 'My Items', 'url' => array('admin')),
    );
}
?>

<h1>Items</h1>

<?php
$item = new Item();
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $item->getAllItems(),
    'itemView' => '_view',
));
?>
