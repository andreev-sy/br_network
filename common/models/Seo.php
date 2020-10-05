<?php

namespace common\models;

use yii\base\BaseObject;
use Yii;
use common\models\Pages;
use common\models\SubdomenPages;

/**
 * @property \common\models\Pages $seo_obj
 * @property \common\models\SubdomenPages $seo_subdomen_obj
 */
class Seo extends BaseObject
{

	public $seo;
	public $seo_obj;
	public $seo_subdomen_obj;

	public function __construct($type, $page = 1, $count = 0, $item = false, $item_type = 'room')
	{
		$this->seo_obj = Pages::find()
			->where([
				'type' => $type,
			])
			->one();
		if(empty($this->seo_obj)) {
			throw new \yii\web\NotFoundHttpException();
		}	
		if (isset(Yii::$app->params['subdomen_baseid'])) {
			$this->seo_subdomen_obj = SubdomenPages::find()
				->where([
					'page_id' 		=> $this->seo_obj->id,
					'subdomen_id'   => Yii::$app->params['subdomen_baseid'],
				])
				->one();
		}

		if (!empty($this->seo_subdomen_obj)) {
			$this->setSeo($this->seo_subdomen_obj, $page);
		} else {
			$this->setSeo($this->seo_obj, $page);
		}

		if ($type == 'item') {
			foreach ($this->seo as $key => $text) {
				if (!(strpos($text, '**') === false)) {
					if ($item_type == 'room') {
						$this->seo[$key] = $this->seoRepalceItem($text, $item);
					} else {
						$this->seo[$key] = $this->seoRepalceRest($text, $item);
					}
				}
			}
		} else {
			foreach ($this->seo as $key => $text) {
				if (!(strpos($text, '**') === false)) {
					$this->seo[$key] = $this->seoRepalce($text, $count, $page);
				}
			}
		}

		$this->seo['img_alt'] 		= $this->seo_obj->img_alt;
	}

	private function setSeo($seoObj, $page)
	{

		if ($page == 1) {
			$this->seo['title'] 		= $seoObj->title;
			$this->seo['description'] 	= $seoObj->description;
			$this->seo['keywords'] 		= $seoObj->keywords;
			$this->seo['h1'] 			= $seoObj->h1;
			$this->seo['h1_pag'] 		= $seoObj->h1_pag;
			$this->seo['text_top']		= $seoObj->text_top;
			$this->seo['text_bottom']	= $seoObj->text_bottom;
		} else {
			$this->seo['title'] 		= $seoObj->title_pag;
			$this->seo['description'] 	= $seoObj->description_pag;
			$this->seo['keywords'] 		= $seoObj->keywords_pag;
			$this->seo['h1'] 			= $seoObj->h1_pag;
			$this->seo['h1_pag'] 		= $seoObj->h1_pag;
			$this->seo['text_top']		= '';
			$this->seo['text_bottom']	= '';
		}
	}

	private function seoRepalce($text, $count = 0, $page)
	{
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
		$text = str_replace('**year**', date("Y")+1, $text);
		$text = str_replace('**city**', isset(Yii::$app->params['subdomen_name']) ? Yii::$app->params['subdomen_name'] : '' , $text);
		$text = str_replace('**city_dec**', isset(Yii::$app->params['subdomen_dec']) ? Yii::$app->params['subdomen_dec'] : '' , $text);
		$text = str_replace('**city_rod**', isset(Yii::$app->params['subdomen_rod']) ? Yii::$app->params['subdomen_rod'] : '' , $text);
		$text = str_replace('**count_rooms**', $count.' зал'.$this->get_num_ending($count, $count_rooms_ending), $text);
		$text = str_replace('**count_court**', $count.' площадк'.$this->get_num_ending($count, $count_court_ending), $text);
		$text = str_replace('**count_tent**', $count.' шат'.$this->get_num_ending($count, $count_tent_ending), $text);
		$text = str_replace('**page**', $page, $text);

		return $text;
	}

	private function seoRepalceItem($text, $item)
	{
		$text = str_replace('**year**', date("Y") + 1, $text);
		$text = str_replace('**city**', isset(Yii::$app->params['subdomen_name']) ? Yii::$app->params['subdomen_name'] : '', $text);
		$text = str_replace('**city_dec**', isset(Yii::$app->params['subdomen_dec']) ? Yii::$app->params['subdomen_dec'] : '', $text);
		$text = str_replace('**city_rod**', isset(Yii::$app->params['subdomen_rod']) ? Yii::$app->params['subdomen_rod'] : '', $text);
		$text = str_replace('**room_name**', $item->name, $text);
		$text = str_replace('**capacity**', $item->capacity, $text);
		$text = str_replace('**price**', $item->price, $text);
		$text = str_replace('**rest_name**', $item->restaurant_name, $text);
		if (!(strpos($text, '**area**') === false)) {
			if ($item->restaurant_district == 547 || $item->restaurant_parent_district == 547) {
				$text = str_replace('**area**', 'в Подмосковье', $text);
			} else {
				$text = str_replace('**area**', 'в Москве', $text);
			}
		}

		return $text;
	}

	private function seoRepalceRest($text, $item)
	{
		$text = str_replace('**year**', date("Y") + 1, $text);
		$text = str_replace('**city**', isset(Yii::$app->params['subdomen_name']) ? Yii::$app->params['subdomen_name'] : '', $text);
		$text = str_replace('**city_dec**', isset(Yii::$app->params['subdomen_dec']) ? Yii::$app->params['subdomen_dec'] : '', $text);
		$text = str_replace('**city_rod**', isset(Yii::$app->params['subdomen_rod']) ? Yii::$app->params['subdomen_rod'] : '', $text);
		$text = str_replace('**room_name**', $item->restaurant_name, $text);
		$text = str_replace('**capacity**', $item->restaurant_max_capacity, $text);
		$text = str_replace('**min_capacity**', $item->restaurant_min_capacity, $text);
		$text = str_replace('**max_capacity**', $item->restaurant_max_capacity, $text);
		$text = str_replace('**rest_name**', $item->restaurant_name, $text);
		$text = str_replace('**price**', $item->restaurant_price, $text);
		if (!(strpos($text, '**area**') === false)) {
			if ($item->restaurant_district == 547 || $item->restaurant_parent_district == 547) {
				$text = str_replace('**area**', 'в Подмосковье', $text);
			} else {
				$text = str_replace('**area**', 'в Москве', $text);
			}
		}

		return $text;
	}

	private function get_num_ending($number, $endingArray)
	{
		$number = $number % 100;
		if ($number >= 11 && $number <= 19) {
			$ending = $endingArray[2];
		} else {
			$i = $number % 10;
			switch ($i) {
				case (1):
					$ending = $endingArray[0];
					break;
				case (2):
				case (3):
				case (4):
					$ending = $endingArray[1];
					break;
				default:
					$ending = $endingArray[2];
			}
		}
		return $ending;
	}

	public function withMedia($mediaTargetTypes)
	{
		foreach ($mediaTargetTypes as $mediaTargetType) {
			if ($this->seo_subdomen_obj && $subdomenMedia = $this->seo_subdomen_obj->getFilesData($mediaTargetType)) {
				$this->seo['media'][$mediaTargetType] = $subdomenMedia;
			} else {
				//если нет у поддомена берем общую
				$this->seo['media'][$mediaTargetType] = $this->seo_obj->getFilesData($mediaTargetType);
			}
		}
		return $this;
	}
}
