<?php


// @var $this yii\web\View 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('app', 'Профиль');

?>


<div class="site-profile box box-primary">

    <div class="box-body">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="box-profile">
                        <img class="profile-user-img img-responsive img-circle"
                            src="<?= Yii::$app->user->identity->photo_path ?>" alt="User profile picture">
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

                        <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'status')->textInput() ?>

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