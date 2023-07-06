<?php

namespace common\models\premium;

use Yii;
use common\models\premium\Channels;
use common\models\premium\UniqueUsers;
use common\models\premium\PhoneClicks;

class PremiumRest extends \yii\db\ActiveRecord
{
    public $channel_name,
           $channel_mail,
           $channel_desc;

    public static function tableName()
    {
        return 'premium_rest';
    }

    public function rules()
    {
        return [
            [['gorko_id', 'channel', 'active'], 'integer'],
        ];
    }

    public function getChannelInfo(){
        return $this->hasOne(Channels::className(), ['id' => 'channel']);
    }

    public function getUniqueUsers($period = 0){
        $users_req = UniqueUsers::find()
            ->where(['premium_rest_id' => $this->id]);

        switch ($period) {
            case 30:
                $users_req->andWhere('date>now() - interval 1 month');
                break;

            case 7:
                $users_req->andWhere('date>now() - interval 1 week');
                break;

            case 365:
                $users_req->andWhere('date>now() - interval 1 year');
                break;
            
            default:
                # code...
                break;
        }

        $users = $users_req->sum('count');
        return $users ? $users : 0;
    }

    public function getPhoneClicks($period = 0){
        $clicks_req = PhoneClicks::find()
            ->where(['premium_rest_id' => $this->id]);

            switch ($period) {
                case 30:
                    $clicks_req->andWhere('date>now() - interval 1 month');
                    break;
    
                case 7:
                    $clicks_req->andWhere('date>now() - interval 1 week');
                    break;
    
                case 365:
                    $clicks_req->andWhere('date>now() - interval 1 year');
                    break;
                
                default:
                    # code...
                    break;
            }
    
            $clicks = $clicks_req->sum('count');
            return $clicks ? $clicks : 0;
    }
}
