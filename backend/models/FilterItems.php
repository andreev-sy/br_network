<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "filter_items".
 *
 * @property int $id
 * @property int $filter_id
 * @property string $value
 * @property string $text
 * @property string $api_arr
 */
class FilterItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filter_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filter_id', 'value', 'text', 'api_arr'], 'required'],
            [['filter_id'], 'integer'],
            [['value', 'text', 'api_arr'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filter_id' => 'Filter ID',
            'value' => 'Value',
            'text' => 'Text',
            'api_arr' => 'Api Arr',
        ];
    }

    public function getFilter()
    {
        return $this->hasOne(Filter::className(), ['id' => 'filter_id']);
    }

    public function getItemsFilterArray()
    {
        $items_filter_arr = [];
        $items = self::find()->where(['active' => 1])->orderBy(['filter_id'=>SORT_ASC])->all();

        foreach ($items as $key => $item) {
            $items_filter_arr[$item->filter->name][$item->id] = $item->text;
        }

        return $items_filter_arr;
    }

    public function getItemsFilterVia($params)
    {
        $json = json_decode($params, true);
        $items_filter_arr = [];
        if (!empty($json))
            foreach ($json as $filter_alias => $filter_item_value) {
                $ex = explode(',', $filter_item_value);
                $items = [];
                foreach ($ex as $arr) {
                    $filter_model = Filter::findOne(['alias' => $filter_alias]);
                    $filter_item_model = self::findOne(['filter_id' => $filter_model->id, 'value' => $arr]);
                    $items[] = $filter_item_model->id;
                }
                $items_filter_arr = array_merge($items_filter_arr, $items);
            }

        return $items_filter_arr;
    }

}