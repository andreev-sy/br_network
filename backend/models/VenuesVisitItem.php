<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "venues_visit_item".
 *
 * @property int $id
 * @property int $venue_visit_id Визит
 * @property string|null $date Дата
 * @property string|null $comment Комментарий
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 *
 * @property VenuesVisit $venueVisit
 */
class VenuesVisitItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venues_visit_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['venue_visit_id'], 'required'],
            [['venue_visit_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['date'], 'string', 'max' => 10],
            [['venue_visit_id'], 'exist', 'skipOnError' => true, 'targetClass' => VenuesVisit::className(), 'targetAttribute' => ['venue_visit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'venue_visit_id' => Yii::t('app', 'Визит'),
            'date' => Yii::t('app', 'Дата'),
            'comment' => Yii::t('app', 'Комментарий'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[VenueVisit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVenueVisit()
    {
        return $this->hasOne(VenuesVisit::className(), ['id' => 'venue_visit_id']);
    }
}
