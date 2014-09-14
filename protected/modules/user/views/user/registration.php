<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Registration");

?>

<h1><?php echo UserModule::t("Registration"); ?></h1>

    <?php if (Yii::app()->user->hasFlash('registration')): ?>
    <div class="success">
    <?php echo Yii::app()->user->getFlash('registration'); ?>
    </div>
<?php else: ?>

    <div class="form">
        <?php
        $form = $this->beginWidget('UActiveForm', array(
            'id' => 'registration-form',
            'enableAjaxValidation' => true,
            'disableAjaxValidationAttributes' => array('RegistrationForm_verifyCode'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        ?>

        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo $form->errorSummary(array($model, $profile)); ?>


        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>

            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password'); ?>
    <?php echo $form->error($model, 'password'); ?>
        <p class="hint">
        <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
        </p>

        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <?php echo $form->passwordField($model, 'verifyPassword'); ?>
        <?php echo $form->error($model, 'verifyPassword'); ?>

    <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>


       
        
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => 'Register',
            ));
            ?>
        </div>
    <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php endif; ?>