<?php

namespace common\models;

use Yii;

class SlicesExtra extends \yii\db\ActiveRecord
{
  public static function tableName()
  {
    return 'slices_extra';
  }

  public function rules()
  {
    return [
      // [['id', 'name'], 'required'],
      // [['type', 'groupe', 'alias', 'name'], 'string'],
      [['id', 'slices_id', 'restaurant_count'], 'integer']
    ];
  }

  public function attributeLabels()
  {
    return [
    ];
  }

  public function getSlices()
  {
    return $this->hasOne(Slices::className(), ['id' => 'slices_id']);
  }

}
