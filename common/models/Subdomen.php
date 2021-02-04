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
class Subdomen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subdomen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'alias', 'city_id'], 'required'],
            [['alias', 'name', 'name_dec', 'name_rod'], 'string'],
            [['id', 'city_id', 'active'], 'integer'],
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