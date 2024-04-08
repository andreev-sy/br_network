<?php

namespace backend\components;

use yii\i18n\MissingTranslationEvent;
use backend\models\Message;
use backend\models\SourceMessage;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event) {

        $translation = SourceMessage::findOne(['category' => $event->category, 'message' => $event->message]);

        if (!$translation) {
            $translation = new SourceMessage();
            $translation->category = $event->category;
            $translation->message = $event->message;
            $translation->save();
        }

        $event->translatedMessage = "@Пропущен перевод: {$event->category}.{$event->message} для {$event->language}@";
    }
}