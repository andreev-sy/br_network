<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "widget_main".
 *
 * @property int $id
 * @property int $slice_id
 * @property string $title
 * @property string $subtitle
 * @property string $text
 * @property string $link_text
 * @property string $img_alt
 */
class WidgetMain extends \yii\db\ActiveRecord
{
    public $items;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'widget_main';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slice_id', 'title', 'subtitle', 'text', 'link_text'], 'required'],
            [['slice_id'], 'integer'],
            [['title', 'subtitle', 'text', 'link_text', 'img_alt'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slice_id' => 'Slice ID',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'text' => 'Text',
            'link_text' => 'Link Text',
        ];
    }

    public function getSlice()
    {
        return $this->hasOne(Slices::className(), ['id' => 'slice_id']);
    }
}
