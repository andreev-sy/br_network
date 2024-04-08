<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "source_message".
 *
 * @property int $id
 * @property string|null $category Категория перевода
 * @property string|null $message Текст
 *
 * @property Message[] $messages
 */
class SourceMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Категория перевода'),
            'message' => Yii::t('app', 'Текст'),
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }


    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $message = new Message();
            $message->id = $this->id;
            $message->language = 'pt-BR';
            $message->save();
        } 

        parent::afterSave($insert, $changedAttributes);
    }
}
