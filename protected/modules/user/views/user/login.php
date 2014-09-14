<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
?>

<h1><?php echo UserModule::t("Login"); ?></h1>

<?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

    <div class="success">
        <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
    </div>

<?php endif; ?>

<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

<div class="form">
    <?php echo CHtml::beginForm(); ?>



    <?php echo CHtml::errorSummary($model); ?>


    <?php echo CHtml::activeLabelEx($model, 'username'); ?>
    <?php echo CHtml::activeTextField($model, 'username') ?>

    <?php echo CHtml::activeLabelEx($model, 'password'); ?>
    <?php echo CHtml::activePasswordField($model, 'password') ?>
    <div>
        <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl); ?>
    </div>

    <div>
        <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
        <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
    </div>

    <div class="form">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'success',
			'label'=>'Login',
		)); ?>
	</div>

    <?php echo CHtml::endForm(); ?>
</div><!-- form -->


<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),
    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
        ), $model);
?>