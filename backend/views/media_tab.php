<?php

/** @var  \common\models\siteobject\SiteObjectMediaTarget */
foreach ($model->mediaTargets as $mediaTarget) :
    $medias = $mediaTarget->getMediaArray();
?>
    <br>
    <div class="form-group" id="<?= $model->id ?>">
        <div class="bg-light-blue disabled color-palette box-header with-border mb-20">
            <span class="label" style="font-size:14px"><?= \Yii::$app->params['mediaEnumClass']::getLabel($mediaTarget->type) ?></span>
        </div>
        <div class="row">
                <?php foreach ($medias as $media) : ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 border border-primary">
                        <img class="img-thumbnail" src="<?= $media->getWebFileLink(['width' => 200, 'height' => 200], false) ?>" class="img-responsive">
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
    <br>
<?php endforeach; ?>