<?php


// @var $this yii\web\View 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = \Yii::t('app', 'Профиль');

?>


<div class="site-profile box box-primary">

    <div class="box-body">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="box-profile">
                        <div class="user_avatar profile-user-img img-circle"><img src="<?= Yii::$app->user->identity->photo_path ?>"></div>
                        <h3 class="profile-username text-center">
                            <?= Yii::$app->user->identity->fullname ?>
                        </h3>
                        <p class="text-muted text-center">
                            <?= Yii::$app->user->identity->role ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="box-body table-responsive">
                        <?= $form->field($model, 'fullname')->textInput() ?>
                        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [ 'mask' => '+55 99 99999 9999' ]); ?>
                        <?= $form->field($model, 'email')->textInput() ?>
                        <?= $form->field($model, 'password')->textInput() ?>
                        <?= $form->field($model, 'files')->fileInput(['accept' => 'image/*']) ?>
                    </div>
                    <div class="box-footer">
                        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>



</div>