<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pages".
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
class Pages extends \yii\db\ActiveRecord
{

    public $breadcrumbs;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title', 'description', 'h1'], 'required'],
            [['name', 'title', 'description', 'keywords', 'h1', 'text_top', 'text_bottom', 'img_alt', 'title_pag', 'description_pag', 'keywords_pag', 'h1_pag', 'type'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
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
}
