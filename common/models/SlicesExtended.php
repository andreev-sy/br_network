<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slices".
 *
 * @property int $id
 * @property int $active
 * @property int $restaurants_count
 * @property string $type
 * @property string $groupe
 * @property string $alias
 * @property string $name
 */
class SlicesExtended extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slices_extended';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id', 'name'], 'required'],
            [['type', 'groupe', 'alias', 'name'], 'string'],
            [['id', 'active', 'restaurants_count'], 'integer']
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

    public static function getSlicesByGroupName($groupName, $limit = 9999){
        return SlicesExtended::find()->where(['groupe' => $groupName])->limit($limit)->all();
    }

    public static function getPopularBlocks($groupNameList){
        $popularBlocks = [];

        foreach ($groupNameList as $groupName){
            $block = SlicesExtended::getSlicesByGroupName($groupName, 2);
            $popularBlocks[$groupName]['isMoreSlices'] = count(SlicesExtended::getSlicesByGroupName($groupName)) > 2 ? true : false;
            $popularBlocks[$groupName]['block'] = $block;
        }

        return $popularBlocks;
    }
}