<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property string $name
 */
class RestaurantsSpecial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurants_specials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'value', 'text'], 'required'],
            [['text'], 'string'],
            [['id', 'value'], 'integer']
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