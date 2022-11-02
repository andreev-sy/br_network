<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property string $alias
 * @property string $h1
 * @property string $title
 * @property string $description
 * @property string $params
 * @property string $img_alt
 */
class RestaurantsPremium extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurants_premium';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gorko_id', 'start', 'finish'], 'required'],
            [['gorko_id', 'start', 'finish'], 'integer']
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
}