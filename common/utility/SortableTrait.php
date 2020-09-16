<?php

namespace common\utility;

use himiklab\sortablegrid\SortableGridAction;
use himiklab\sortablegrid\SortableGridBehavior;

trait SortableTrait
{
    public function sortableModelBehavior()
    {
        return
            [
                'sort' => [
                    'class' => SortableGridBehavior::className(),
                    'sortableAttribute' => 'sort'
                ],
            ];
    }

    public function sortableControllerAction($modelClassname)
    {
        return [
            'sort' => [
				'class' => SortableGridAction::className(),
				'modelName' => $modelClassname,
			],
        ];
    }
}
