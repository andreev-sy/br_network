<?php

namespace frontend\models;

use yii\base\Model;

class FormHelp extends Model
{
    public $name;
    public $phone;
    public $date;
    public $count;
    public $water;
    public $tent;
    public $country;
    public $incity;

    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
        ];
    }
}