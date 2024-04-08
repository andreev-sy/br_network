<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ActiveMultiSearch extends Widget
{
    public $model;
    public $form;
    public $search;
    public $fields_list = [];
    public $promt = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('active-multi-search', [
            'model' => $this->model,
            'form' => $this->form,
            'search' => $this->search,
            'fields_list' => $this->fields_list,
            'promt' => $this->promt,
        ]);
    }
}
