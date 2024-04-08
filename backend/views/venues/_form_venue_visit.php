<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
use backend\models\VenuesVisit;
use backend\models\VenuesVisitItem;
use backend\models\VenuesVisitStatus;
use backend\models\ViaHelper;

$js = <<<JS
    $("document").ready(function(){
        $("#new_visit").on("pjax:end", function() {
            $.pjax.reload({container:"#visit_list"}); 
        });
        $("body").on("click", "[data-visit-show]", function(){
            $(this).addClass("hidden");
            $(".visit_wrap.hidden").removeClass("hidden");
        });
        $("body").on("click", "[data-visit-del]", function(){
            $(this).closest("form").find("[name='del']").val(1);
            $(this).closest("form").find("[type='submit']").trigger("click");
        });
    });
JS;
$this->registerJs($js);
?>
<div class="venues_visit">

<?php Pjax::begin(['id' => 'vanues_visit']); ?>

<?php $venues_visit = VenuesVisit::findOne(['venue_id' => $model->id]) ?? new VenuesVisit(); ?>
<?php $form = ActiveForm::begin([
    'action'=>Url::to(['venues/view', 'id'=>$model->id]),
    'options' => [
        'data-pjax' => true, 
        'id' => 'form_venues_visit', 
    ]
]); ?>
    <h4><?= Yii::t('app', 'Данные от заведения') ?></h4>
    <div class="row visit_wrap">
        <div class="col-md-6">
            <?= $form->field($venues_visit, 'venue_id')->hiddenInput(['value' => $model->id]) ?>
            <?= $form->field($venues_visit, 'person')->textInput() ?>
            <?= $form->field($venues_visit, 'phone')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999', 'options' => ['id' => 'phone_venue_visit']]) ?>
            <?= $form->field($venues_visit, 'phone_wa')->widget(MaskedInput::class, ['mask' => '+55 99 99999 9999', 'options' => ['id' => 'phone_wa_venue_visit']]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($venues_visit, 'status_id')->dropDownList(ViaHelper::getTableMap(VenuesVisitStatus::className())) ?>
            <?= $form->field($venues_visit, 'count_banquets')->textInput() ?>
            <?= $form->field($venues_visit, 'amount_commission')->textInput() ?>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'id' => 'btn_restaurant_visit']) ?>
            </div>
        </div>
    </div>
    <hr>
<?php
ActiveForm::end();
Pjax::end();
?>

<?php
Pjax::begin(['id' => 'visit_list']);
$visit_all = VenuesVisitItem::findAll(['venue_visit_id' => $venues_visit->id]);
?>
<?php if (!empty($visit_all)): ?>
    <?php foreach ($visit_all as $key => $visit): ?>
        <?php $form = ActiveForm::begin([
            'action'=>Url::to(['venues/view', 'id'=>$model->id]),
            'options' => [
                'data-pjax' => true, 
                'class' => 'visit_wrap', 
                'id' => 'form_visit' . $key, 
            ]
        ]); ?>
        <h4><?= Yii::t('app', 'Визит') ?> <?= $key+1 ?></h4>
        <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="del" value="0">
                <?= $form->field($visit, 'id')->hiddenInput() ?>
                <?= $form->field($visit, 'venue_visit_id')->hiddenInput() ?>
                <?= $form->field($visit, 'date')->widget(DatePicker::classname(), [
                    'name' => 'date',
                    'options' => ['id' => 'date_visit' . $key],
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => ['autoclose' => true, 'format' => 'dd/mm/yyyy']
                ]) ?>
                <?= $form->field($visit, 'comment')->textarea(['rows' => 5]) ?>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success', 'id' => 'btn_visit' . $key]) ?>
                </div>
                <div class="form-group">
                    <?= Html::button(Yii::t('app', 'Удалить'), ['class' => 'btn btn-danger', 'data-visit-del' => $visit->id, 'id' => 'del_visit' . $key]) ?>
                </div>
            </div>
        </div>
        <hr>
        <?php ActiveForm::end(); ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php
Pjax::end();
Pjax::begin(['id' => 'new_visit']);
$new_visit = new VenuesVisitItem();
?>

<div class="btn-wrap" data-visit-show>
    <div class="btn btn-primary" role="button"><i class="fa fa-plus"></i></div>
</div>
<?php $form = ActiveForm::begin([
    'action'=>Url::to(['venues/view', 'id'=>$model->id]),
    'options' => [
        'data-pjax' => true,
        'class' => 'visit_wrap hidden', 
        'id' => 'form_new_visit', 
    ]
]); ?>
<h4><?= Yii::t('app', 'Новый визит') ?></h4>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($new_visit, 'venue_visit_id')->hiddenInput(['value' => $venues_visit->id]) ?>
        <?= $form->field($new_visit, 'date')->widget(DatePicker::classname(), [
            'name' => 'date',
            'options' => ['id' => 'date_new_visit'],
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => ['autoclose' => true, 'format' => 'dd/mm/yyyy']
        ]) ?>
        <?= $form->field($new_visit, 'comment')->textarea(['rows' => 5]) ?>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-primary', 'id' => 'btn_visit']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>
<?php Pjax::end(); ?>
</div>
