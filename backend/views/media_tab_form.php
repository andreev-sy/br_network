<?php

use backend\widgets\MediaUpload;
use common\helpers\enum\MediaEnum;
use common\models\siteobject\BaseMediaEnum;
use common\models\siteobject\Media;
use common\models\siteobject\SiteObjectMediaTarget;

$mediaEnumclass = \Yii::$app->params['mediaEnumClass'] ?? BaseMediaEnum::class;
/** @var SiteObjectMediaTarget */
if (!$model->isNewRecord) {
    foreach ($model->mediaTargets as $mediaTarget) {
        echo MediaUpload::widget([
            'label' => $mediaEnumclass::getLabel($mediaTarget->type),
            'multiple' => true,
            'hidden' => false,
            'model' => $mediaTarget,
            'lastMedia' => Media::find()->limit(5)->orderBy('id DESC')->all()
        ]);
    }
} else {
    echo '<div class="callout callout-warning mt-20">Сначала нужно сохранить объект!</div>';
}