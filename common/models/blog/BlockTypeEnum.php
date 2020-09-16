<?php

namespace common\models\blog;

use common\utility\BaseEnum;

abstract class BlockTypeEnum extends BaseEnum
{

    const Text = 'text';
    const Media = 'media';
    const Layout = 'layout';

    const LABEL_MAP = [
        self::Text => 'Текстовые блоки',
        self::Media => 'Блоки с файлами',
        self::Layout => 'Разметка'
    ];


    public static function getLabel($type)
    {
        if (self::isValidValue($type)) {
            return self::LABEL_MAP[$type];
        } else return 'Название не определено';
    }

}
