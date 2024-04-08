<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_visit".
 *
 * @property int $id
 * @property int $venue_id Заведение
 * @property string|null $person Имя лица принимающего решения
 * @property string|null $phone Телефон
 * @property string|null $phone_wa Телефон для WA
 * @property int|null $status_id Статус клиента
 * @property int|null $count_banquets Количество проведенных банкетов
 * @property string|null $amount_commission Сумма комиссий с банкетов
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property VenuesVisitStatus $status
 * @property Venues $venue
 * @property VenuesVisitItem[] $venuesVisitItems
 */
class VenuesVisit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_visit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_id'], 'required'],
            [['venue_id', 'status_id', 'count_banquets'], 'integer'],
            [['amount_commission'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['person'], 'string', 'max' => 255],
            [['phone', 'phone_wa'], 'string', 'max' => 50],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesVisitStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['venue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venue_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'venue_id' => Yii::t('app', 'Заведение'),
            'person' => Yii::t('app', 'Имя лица принимающего решения'),
            'phone' => Yii::t('app', 'Телефон'),
            'phone_wa' => Yii::t('app', 'Телефон для WA'),
            'status_id' => Yii::t('app', 'Статус клиента'),
            'count_banquets' => Yii::t('app', 'Количество проведенных банкетов'),
            'amount_commission' => Yii::t('app', 'Сумма комиссий с банкетов'),
            'venuesVisitItemsList' => Yii::t('app', 'Результаты визитов'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(VenuesVisitStatus::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Venue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venue_id']);
    }

    /**
     * Gets query for [[VenuesVisitItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesVisitItems()
    {
        return $this->hasMany(VenuesVisitItem::className(), ['venue_visit_id' => 'id']);
    }

    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        VenuesVisitItem::deleteAll(['venue_visit_id' => $this->id]);

        return true;
    }

    public function beforeSave($insert) 
    {
        $this->phone = rtrim($this->phone, '_');

        return parent::beforeSave($insert);
    }


    public function getVenuesVisitItemsList()
    {
        return implode('', array_map(function($item) {
            return '<p><b>'.$item->date.'</b><br>'.$item->comment.'</p>';
        }, $this->venuesVisitItems));
    }
}
