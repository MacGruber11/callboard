
<h2>
    <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id)); ?>
</h2>

<p style="color:#778899"><span class="glyphicon glyphicon-time"></span> Posted on <?php
    $time = CHtml::encode($data->date);
    echo date("F j, Y, g:i a", $time);
    ?></p>
<hr>
<img src="<?php echo '/images/main/' . $data->id . '.jpg' ?>">
<hr>
<p><h3>Price: <b><?php
        echo $data->price;
        ?></b></h3></p>

<a href="<?php echo Yii::app()->homeUrl . 'user/user/view?id=' . $data->user_id; ?>">
    <?php echo CHtml::encode($data->getAuthor($data->user_id)); ?></a>
<hr>
