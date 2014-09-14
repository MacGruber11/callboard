<?php
if (!Yii::app()->user->isGuest) {
    $this->menu = array(
        array('label' => 'List Item', 'url' => array('index')),
        array('label' => 'Create Item', 'url' => array('create')),
        array('label' => 'My Items', 'url' => array('admin')),
    );
}
?>

<div class="col-lg-3">
    <h1><?php echo $model->name; ?></h1>
    <hr>
    <p style="color:#778899"><span class="glyphicon glyphicon-time"></span> Posted on <?php
        $time = $model->date;
        echo date("F j, Y, g:i a", $time);
        ?></p>
    <hr>
    <?php if ($model->image): ?>
        <img src="<?php echo '/images/orig/' . $model->id . '.jpg' ?>">
        <hr>
    <?php endif; ?>
    <p><h3>Price: <b><?php
            echo $model->price;
            ?></b></h3></p>
<a href="<?php echo Yii::app()->homeUrl . 'user/user/view?id=' . $model->user_id; ?>">
    <?php echo CHtml::encode($model->getAuthor($model->user_id)); ?></a>
<hr>
</div>