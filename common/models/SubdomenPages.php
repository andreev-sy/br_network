<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subdomen_pages".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $h1
 * @property string $text_top
 * @property string $text_bottom
 * @property string $img_alt
 */
class SubdomenPages extends \yii\db\ActiveRecord
{

    public $breadcrumbs;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subdomen_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'h1'], 'required'],
            [['page_id', 'subdomen_id'], 'integer'],
            [['title', 'description', 'keywords', 'h1', 'text_top', 'text_bottom', 'img_alt', 'title_pag', 'description_pag', 'keywords_pag', 'h1_pag'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_pag' => 'Title для пагинации',
            'description' => 'Description',
            'description_pag' => 'Description для пагинации',
            'keywords' => 'Keywords',
            'keywords_pag' => 'Keywords для пагинации',
            'h1' => 'H1',
            'h1_pag' => 'H1 для пагинации',
            'text_top' => 'Верхний текст',
            'text_bottom' => 'Нижний текст',
        ];
    }

    public function getSubdomen(){
        return $this->hasOne(Subdomen::className(), ['id' => 'subdomen_id']);
    }

    public function getPage(){
        return $this->hasOne(Pages::className(), ['id' => 'page_id']);
    }
}
