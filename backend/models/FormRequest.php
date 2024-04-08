<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "form_request".
 *
 * @property int $id
 * @property string $text Текст
 * @property string|null $text_ru Текст (ру)
 * @property string|null $text_full Полный текст
 * @property string $date Дата
 * @property string|null $type Тип
 * @property string|null $utm Utm метки
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class FormRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'date'], 'required'],
            [['text', 'text_ru', 'text_full'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['utm'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Текст'),
            'text_ru' => Yii::t('app', 'Текст (ру)'),
            'text_full' => Yii::t('app', 'Полный текст'),
            'date' => Yii::t('app', 'Дата'),
            'type' => Yii::t('app', 'Тип'),
            'utm' => Yii::t('app', 'Utm метки'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }


    public function getName()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['f9eae8de8']['value'])) {
            $text = $json['data']['form_data']['f9eae8de8']['value'];
            return $text . " ($this->date)";
        }

        return '';
    }

    public function getNameRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['f9eae8de8']['value'])) {
            return ucwords(trim($json['data']['form_data']['f9eae8de8']['value']));
        }

        return '';
    }

    public function getDateRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['fa1763dc7']['value'])) {
            return $json['data']['form_data']['fa1763dc7']['value'];
        }

        return '';
    }

    public function getPhoneRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['f400830ed']['value'])) {
            return $json['data']['form_data']['f400830ed']['value'];
        }

        return '';
    }

    public function getSpecRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['123553']['value'])) {
            $text = $json['data']['form_data']['123553']['value'];
            $model = CollectionSpec::findOne(['text' => $text]);
            if (!empty ($model))
                return $model->id;
        }

        return null;
    }


    public function getRegionRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['fd415376f']['value'])) {
            $text = $json['data']['form_data']['fd415376f']['value'];
            $model = Region::findOne(['name' => $text]);
            if (!empty ($model))
                return $model->id;
        }

        return null;
    }

    public function getGuestRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty($json['data']['form_data']['fa19c3a4e']['value'])) {
            $text = $json['data']['form_data']['fa19c3a4e']['value'];
            $model = CollectionGuest::find()->where(['<=', 'min', $text])->andWhere(['>=', 'max', $text])->one();
            if (!empty ($model))
                return $model->id;
        }

        return '';
    }

    public function getPricePersonRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty($json['data']['form_data']['f2159bc9e']['value'])) {
            $text = $json['data']['form_data']['f2159bc9e']['value'];
            $model = CollectionPricePerson::find()->where(['<=', 'min', $text])->andWhere(['>=', 'max', $text])->one();
            if (!empty ($model))
                return $model->id;
        }

        return '';
    }

    public function getContactTypeRequest()
    {
        $json = (array) json_decode($this->text_full, true);
        if (!empty ($json['data']['form_data']['fb9e5a06b']['value'])) {
            $text = $json['data']['form_data']['fb9e5a06b']['value'];
            $model = CollectionContactType::findOne(['text' => $text]);
            if (!empty ($model))
                return $model->id;
        }

        return '';
    }


    public function getMap()
    {
        $result = [];
        $arr = self::find()->where(['type' => 'client'])->orderBy(['date' => SORT_DESC])->all();

        foreach ($arr as $a) {
            $json = (array) json_decode($a->text_full, true);
            if (!empty ($json['data']['form_data']['f9eae8de8']['value'])) {
                $name = $json['data']['form_data']['f9eae8de8']['value'];
                $result[$a->id] = $name . " ($a->date)";
            }
        }

        return $result;
    }

}
