<?php

namespace common\models;

use yii\base\BaseObject;
use Yii;
use common\models\Pages;

class Seo extends BaseObject{

	public $seo;

	public function __construct($type, $page = 1, $count = 0, $item = false){
		$seo_obj = Pages::find()
			->where([
				'type' => $type,
			])
			->one();

		if($page == 1){
			$this->seo['title'] 		= $seo_obj->title;
			$this->seo['description'] 	= $seo_obj->description;
			$this->seo['keywords'] 		= $seo_obj->keywords;
			$this->seo['h1'] 			= $seo_obj->h1;
			$this->seo['text_top']		= $seo_obj->text_top;
			$this->seo['text_bottom']	= $seo_obj->text_bottom;
		}
		else{
			$this->seo['title'] 		= $seo_obj->title_pag;
			$this->seo['description'] 	= $seo_obj->description_pag;
			$this->seo['keywords'] 		= $seo_obj->keywords_pag;
			$this->seo['h1'] 			= $seo_obj->h1_pag;
			$this->seo['text_top']		= '';
			$this->seo['text_bottom']	= '';
		}

		if($type == 'item'){
			foreach ($this->seo as $key => $text) {
				if(!(strpos($text, '**') === false)){
					$this->seo[$key] = $this->seoRepalceItem($text, $item);
				}
			}
		}
		else{
			foreach ($this->seo as $key => $text) {
				if(!(strpos($text, '**') === false)){
					$this->seo[$key] = $this->seoRepalce($text, $count, $page);
				}
			}
		}			

		$this->seo['img_alt'] 		= $seo_obj->img_alt;
	}

	private function seoRepalce($text, $count = 0, $page){
		$count_rooms_ending = [
			'',
			'а',
			'ов'
		];
		$count_court_ending = [
			'а',
			'и',
			'ок'
		];
		$count_tent_ending = [
			'ер',
			'ра',
			'ров'
		];
		$text = str_replace('**count**', $count, $text);
		$text = str_replace('**count_rooms**', $count.' зал'.$this->get_num_ending($count, $count_rooms_ending), $text);
		$text = str_replace('**count_court**', $count.' площадк'.$this->get_num_ending($count, $count_court_ending), $text);
		$text = str_replace('**count_tent**', $count.' шат'.$this->get_num_ending($count, $count_tent_ending), $text);
		$text = str_replace('**page**', $page, $text);

		return $text;
	}

	private function seoRepalceItem($text, $item){
		$text = str_replace('**room_name**', $item->name, $text);
		$text = str_replace('**capacity**', $item->capacity, $text);
		$text = str_replace('**price**', $item->price, $text);
		$text = str_replace('**rest_name**', $item->restaurant_name, $text);
		if(!(strpos($text, '**area**') === false)){
			if($item->restaurant_district == 547 || $item->restaurant_parent_district == 547){
				$text = str_replace('**area**', 'в Подмосковье', $text);
			}
			else{
				$text = str_replace('**area**', 'в Москве', $text);
			}
		}

		return $text;
	}

	private function get_num_ending($number, $endingArray)
    {
        $number = $number % 100;
        if ($number>=11 && $number<=19) {
            $ending=$endingArray[2];
        } else {
            $i = $number % 10;
            switch ($i)
            {
                case (1): $ending = $endingArray[0]; break;
                case (2):
                case (3):
                case (4): $ending = $endingArray[1]; break;
                default: $ending=$endingArray[2];
            }
        }
        return $ending;
    }
}