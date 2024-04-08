<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


class ViaHelper
{
    public static function setRelatedLink( $insert, $changedAttributes, $model, $attributeName, $modelRelated )
    {
        if (
            (
                isset($changedAttributes[$attributeName]) 
                and $changedAttributes[$attributeName] !== $model->getAttribute($attributeName)
            ) or (
                $insert 
                and !empty($model->getAttribute($attributeName))
            )
        ) {
            $modelRelatedAttr = $modelRelated::attributes();
            $modelRelated::deleteAll([ $modelRelatedAttr[1] => $model->id ]);
            $via_ids = explode(',', $model->getAttribute($attributeName));

            if(!empty($via_ids)){
                foreach ($via_ids as $via_id) {
                    $via = new $modelRelated();
                    $via->setAttributes([
                        $modelRelatedAttr[1] => $model->getAttribute('id'),
                        $modelRelatedAttr[2] => $via_id
                    ]);
                    $via->save();
                }
            }
        }
    }

    public static function getTableMap( $model )
    {
        return ArrayHelper::map($model::find()->all(), 'id', Yii::$app->params['ru'] ? 'text_ru' : 'text');
    }

    public static function getText( $model )
    {
        if(empty($model)) return null;
        
        return Yii::$app->params['ru'] ? $model->text_ru : $model->text;
    }

    public static function getArrayText( $items, $table, $delimiter = '' )
    {
        // $field = Yii::$app->params['ru'] ? 'text_ru' : 'text';
        // $result = ArrayHelper::getColumn($items, "$table.$field");
      
        // return $delimiter ? implode($delimiter, $result) : $result;

        $items = $items->with($table)->all();
        
        if(empty($items)) return null;
        
        $result = ArrayHelper::map($items, 'id', function($item) use($table) {
            return Yii::$app->params['ru'] ? $item->$table->text_ru : $item->$table->text;
        });
        
        return $delimiter ? implode($delimiter, $result) : $result;
    }

    public static function getIntIcon( $value )
    {
        $class = (int)$value === 1 ? 'fa fa-check text-success' : 'fa fa-close text-danger';

        return Html::tag('i', '', ['class' => $class]);
    }

}
