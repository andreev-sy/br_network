<?php
use yii\helpers\Url;

?>

<div class="kv-expanded-row">
    <div class="kv-detail-content" style="overflow: hidden;">
        <h3><?= Yii::t('app', 'Детали зала') ?> <a href="<?= Url::to(['rooms/view', 'id'=>$model->id]) ?>" target="blank"><small><?= $model->param_name_alt ?></small></a></h3>
        <div class="row">
            <?php $image = $model->getImages()->orderBy(['sort'=>SORT_ASC])->one(); ?>
            <div class="col-sm-3">
                <?php if(!empty($image)): ?>
                    <a class="img-thumbnail img-rounded text-center" href="<?= $image->subpath ?>" data-lightbox="roadtrip">
                        <img src="<?= $image->subpath ?>" style="padding:2px;width:100%">
                        <div class="small text-muted"><?= Yii::t('app', 'Количество изображений') ?>: <?= $model->getImages()->count() ?></div>
                    </a>
                <?php else: ?>
                    <h4><?= Yii::t('app', 'Нет изображений') ?></h4>
                <?php endif; ?>
            </div>
            <div class="col-sm-3">
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Вместимость') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('min_capacity') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->min_capacity) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('max_capacity') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->max_capacity) ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Местоположение') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('venue.city_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->venue->city->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('venue.region_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->venue->region->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('venue.district_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->venue->district->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('venue.address') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->venue->address ?? null) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-3">
            <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Цены') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('param_minimum_rental_duration') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->param_minimum_rental_duration) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('param_min_price') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($model->param_min_price, 'BRL') ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('price_day') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($model->price_day, 'BRL') ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('price_person') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($model->price_person, 'BRL') ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('price_hour') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($model->price_hour, 'BRL') ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('price_day_ranges') ?></td>
                            <td><?= Yii::$app->formatter->asHtml($model->priceDayRangesList) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-3">
                <?php if(!empty($model->param_description)): ?>
                    <table class="table table-bordered table-condensed table-hover small">
                        <thead><th colspan="2"><?= $model->getAttributeLabel('param_description') ?></th></thead>
                        <tbody>
                            <tr>
                                <td colspan="2"><?= Yii::$app->formatter->asText($model->param_description) ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Метаданные') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('created_at') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->created_at) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('updated_at') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->updated_at) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>
