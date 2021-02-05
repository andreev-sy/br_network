<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property string $alias
 * @property int $active
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $img_alt
 * @property string $h1
 * @property string $text_top
 * @property string $text_bottom
 */
class RayoniSlice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rayoni_slice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id', 'city_id', 'line_id'], 'required'],
            [['alias', 'title', 'description', 'keywords', 'img_alt', 'h1', 'text_top', 'text_bottom'], 'string'],
            [['id', 'active'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

        ];
    }

    public function getListForIndex()
    {
        $rayoniList = RayoniSlice::find()
            ->where(['active' => '1'])
            ->limit(21)
            ->asArray()
            ->all();

        return array_chunk($rayoniList, 7);
    }
}