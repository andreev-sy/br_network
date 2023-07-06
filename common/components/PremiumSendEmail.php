<?php

namespace common\components;

use Yii;
use yii\base\BaseObject;

class PremiumSendEmail extends BaseObject{

	public static function sendEmail() {
        $to = 'zadrotstvo@gmail.com';
        $subj = 'Test';
        $msg = 'test test';
        function sendMail($to, $subj, $msg)
        {
            $message = Yii::$app->mailer->compose()
                ->setFrom(['info@arendazala.net' => 'Аренда залов'])
                ->setTo($to)
                ->setSubject($subj)
                ->setCharset('utf-8')
                ->setHtmlBody($msg);
            print_r($message);
            return $message->send();
        }

        sendMail($to, $subj, $msg);

        
    }

    

}