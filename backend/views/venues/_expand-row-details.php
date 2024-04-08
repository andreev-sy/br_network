<?php
use yii\helpers\Url;
?>

<div class="kv-expanded-row">
    <div class="kv-detail-content" style="overflow: hidden;">
        <h3><?= Yii::t('app', 'Детали заведения') ?> <a href="<?= Url::to(['venues/view', 'id'=>$model->id]) ?>" target="blank"><small><?= $model->name ?></small></a></h3>
        <div class="row">
            <?php $image = $model->getImages()->orderBy(['sort'=>SORT_ASC])->one(); ?>

            <div class="col-md-3">
                <?php if(!empty($image)): ?>
                    <a class="img-thumbnail img-rounded text-center img-wrapper" href="<?= $image->subpath ?>" data-lightbox="roadtrip">
                        <img src="<?= $image->subpath ?>" style="padding:2px;width:100%">
                        <div class="small text-muted"><?= Yii::t('app', 'Количество изображений') ?>: <?= $model->getImages()->count() ?></div>
                    </a>
                <?php else: ?>
                    <h4><?= Yii::t('app', 'Нет изображений') ?></h4>
                <?php endif; ?>
            </div>

            <div class="col-md-3">
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Контакты') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('phone') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->phone) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('phone2') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->phone2) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('phone_wa') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->phone_wa) ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Местоположение') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('agglomeration_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->agglomeration->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('city_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->city->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('region_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->region->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('district_id') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->district->name ?? null) ?></td>
                        </tr>
                        <tr>
                            <td><?= $model->getAttributeLabel('address') ?></td>
                            <td><?= Yii::$app->formatter->asText($model->address ?? null) ?></td>
                        </tr>
                    </tbody>
                </table>
               
            </div>

            <div class="col-md-3">
                <table class="table table-bordered table-condensed table-hover small">
                    <thead><th colspan="2"><?= Yii::t('app', 'Цены') ?></th></thead>
                    <tbody>
                        <tr>
                            <td><?= $model->getAttributeLabel('param_advanced_payment') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($model->param_advanced_payment, 'BRL') ?></td>
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
            </div>

            <div class="col-md-3">
                <?php if(!empty($model->comment)): ?>
                    <table class="table table-bordered table-condensed table-hover small">
                        <thead><th colspan="2"><?= $model->getAttributeLabel('comment') ?></th></thead>
                        <tbody>
                            <tr>
                                <td colspan="2"><?= Yii::$app->formatter->asText($model->comment) ?></td>
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
