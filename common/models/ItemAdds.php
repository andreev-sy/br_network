<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property int $item_id
 * @property int $item_type
 * @property string $title
 * @property string $value_type
 * @property string $value
 */
class ItemAdds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_adds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value_type', 'value'], 'string'],
            [['item_id', 'item_type'], 'integer']
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