<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "filter".
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $type
 * @property string $source
 * @property int $sort
 */
class Domainsbaba extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'domainsbabac';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain', 'trim_domain', 'link'], 'string'],
        ];
    }
}
