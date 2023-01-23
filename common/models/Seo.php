<?php

namespace common\models;

use yii\base\BaseObject;
use Yii;
use common\models\Pages;
use common\models\SubdomenPages;
use frontend\components\Declension;
use yii\web\NotFoundHttpException;
use common\models\RestaurantsModule;

/**
 * @property \common\models\Pages $seo_obj
 * @property \common\models\SubdomenPages $seo_subdomen_obj
 */
class Seo extends BaseObject
{

	public $seo;
	public $seo_obj;
	public $seo_subdomen_obj;
	public $rest_seo_obj;

	public function __construct($type, $page = 1, $count = 0, $item = false, $item_type = 'room', $rest_item = null, $min_price = false, $is_post = false, $post_seo = false, $autofill = false, $max_price = false)
	{
		if($is_post){
			$seoObj = $post_seo;
			$this->seo_obj = Pages::findWithRelations()->where(['type' => 'blog'])->one();
			$this->setSeo($seoObj, $page);
			foreach ($this->seo as $key => $text) {
				if (!(strpos($text, '**') === false)) {
					$this->seo[$key] = $this->seoRepalce($text, $count, $page, $min_price, $item_type);
				}
			}
		}
		else{
			if ($type == 'item' && !empty($item)) {
				$restAr = RestaurantsModule::findWithSeo()->where(['id' => $item->restaurant_gorko_id])->one();
				if (!empty($restAr) && !empty($restAr->seoObject) && $restAr->seoObject->active) {
					$this->rest_seo_obj = $restAr->seoObject;
				}
			}

			$this->seo_obj = Pages::findWithRelations()->where(['type' => $type])->one();

			if (empty($this->seo_obj) || empty($this->seo_obj->seoObject)) {
				throw new \yii\web\NotFoundHttpException();
			}
			if (isset(Yii::$app->params['subdomen_baseid'])) {
				$this->seo_subdomen_obj = SubdomenPages::findWithRelations()
					->where([
						'page_id' 		=> $this->seo_obj->id,
						'subdomen_id'   => Yii::$app->params['subdomen_baseid'],
					])
					->one();
			}

			$isSubdomenSeo = !empty($this->seo_subdomen_obj)
				&& !empty($this->seo_subdomen_obj->seoObject)
				&& $this->seo_subdomen_obj->seoObject->active;

			$seoObj = $isSubdomenSeo ? $this->seo_subdomen_obj->seoObject : $this->seo_obj->seoObject;

			$this->setSeo($seoObj, $page, $autofill);

			if ($type == 'item' || $type == 'room') {
				foreach ($this->seo as $key => $text) {
					if (!(strpos($text, '**') === false)) {
						if ($item_type == 'room') {
							$this->seo[$key] = $this->seoRepalceItem($text, $item, $rest_item, $min_price, $max_price);
						} else {
							$this->seo[$key] = $this->seoRepalceRest($text, $item);
						}
					}
				}
			} else {
				foreach ($this->seo as $key => $text) {
					if (!(strpos($text, '**') === false)) {
						$this->seo[$key] = $this->seoRepalce($text, $count, $page, $min_price, false, $this->seo_obj['name']);
					}
				}
			}
		}

		//$this->seo['img_alt'] 		= $this->seo_obj->img_alt;
	}

	private function setSeo($seoObj, $page, $autofill = false)
	{
		$site = \Yii::$app->params['siteAddress'];
		$isPagination = $page > 1;
		$paginationH1 = $this->seo_obj->name;
		$paginationTitle = "Страница №$page, раздел {$this->seo_obj->name}";
		$paginationDescription = "Вы смотрите страницу №$page из раздела «{$this->seo_obj->name}» на портале $site";
		
		$getSeoArray = function ($obj, $autofill = false) use ($isPagination, $paginationTitle, $paginationDescription, $paginationH1) {
			return [
				'title' 		=> $isPagination ? ($obj->pagination_title ?: $paginationTitle) : $obj->title,
				'description' 	=> $isPagination ? ($obj->pagination_description ?: $paginationDescription) : $obj->description,
				'keywords' 		=> $isPagination ? ($obj->pagination_keywords ?: $obj->keywords) : $obj->keywords,
				'h1' 			=> $isPagination ? ($obj->pagination_heading ?: $paginationH1) : $obj->heading,
				'h1_pag' 		=> $isPagination ? ($obj->pagination_heading ?: $paginationH1) : $obj->pagination_heading,
				'text_top'		=> ($isPagination || $autofill) ? '' : $obj->text1,
				'text_bottom'	=> ($isPagination || $autofill) ? '' : $obj->text2,
				'text_1'		=> ($isPagination || $autofill) ? '' : $obj->text1,
				'text_2'		=> ($isPagination || $autofill) ? '' : $obj->text2,
				'text_3'		=> ($isPagination || $autofill) ? '' : $obj->text3,
				'img_alt'		=> $obj->img_alt,
			];
		};
		$restSeoArr = empty($this->rest_seo_obj) ? [] : array_filter($getSeoArray($this->rest_seo_obj));

		$this->seo = array_merge($getSeoArray($this->seo_obj->seoObject, true), array_filter($getSeoArray($seoObj)), $restSeoArr);	
	}

	private function seoRepalce($text, $count = 0, $page, $min_price, $name = false, $page_name = false)
	{
		$text = str_replace('**count**', $count, $text);
		$text = str_replace('**year**', isset(Yii::$app->params['cur_year']) ? Yii::$app->params['cur_year'] : date('Y'), $text);
		$text = str_replace('**city**', isset(Yii::$app->params['subdomen_name']) ? Yii::$app->params['subdomen_name'] : '', $text);
		$text = str_replace('**city_dec**', isset(Yii::$app->params['subdomen_dec']) ? Yii::$app->params['subdomen_dec'] : '', $text);
		$text = str_replace('**city_rod**', isset(Yii::$app->params['subdomen_rod']) ? Yii::$app->params['subdomen_rod'] : '', $text);
		if (preg_match('/\*\*dec=(\w+)\*\*/u', $text, $matches)) {
			$text = preg_replace('/\*\*dec=(\w+)\*\*/u', Declension::get($count, $matches[1]), $text);
		}
		if (preg_match('/\*\*count_dec=(\w+)\*\*/u', $text, $matches)) {
			$text = preg_replace('/\*\*count_dec=(\w+)\*\*/u', Declension::get($count, $matches[1], true), $text);
		}
		$text = str_replace('**page**', $page, $text);
		if (strpos($text, '**price**') !== false) {
			$text = str_replace('**price**', $min_price, $text);
		}

		if($name){
			if (strpos($text, '**post_title**') !== false) {
				$text = str_replace('**post_title**', $name, $text);
			}
		}

		if ($page_name) {
			$page_name_lcase = mb_strtolower($page_name);
			$text = str_replace('**page_name**', $page_name, $text);
			$text = str_replace('**page_name_lcase**', $page_name_lcase, $text);
		}

		return $text;
	}

	private function seoRepalceItem($text, $item, $rest_item = null, $min_price = false, $max_price = false)
	{
		$text = str_replace('**year**', isset(Yii::$app->params['cur_year']) ? Yii::$app->params['cur_year'] : date('Y'), $text);
		$text = str_replace('**city**', isset(Yii::$app->params['subdomen_name']) ? Yii::$app->params['subdomen_name'] : '', $text);
		$text = str_replace('**city_dec**', isset(Yii::$app->params['subdomen_dec']) ? Yii::$app->params['subdomen_dec'] : '', $text);
		$text = str_replace('**city_rod**', isset(Yii::$app->params['subdomen_rod']) ? Yii::$app->params['subdomen_rod'] : '', $text);
		$text = str_replace('**room_name**', $item->name, $text);
		$text = str_replace('**capacity**', $item->capacity, $text);
		$text = str_replace('**price**', $item->price, $text);
		$text = str_replace('**rest_name**', $item->restaurant_name, $text);
		if ($rest_item) {
			$text = str_replace('**rest_address**', $rest_item->restaurant_address, $text);
			$text = str_replace('**rest_type**', $rest_item->restaurant_main_type, $text);
			$text = str_replace('**rest_type_lcase**', mb_strtolower($rest_item->restaurant_main_type), $text);
		} else {
			$text = str_replace('**rest_address**', $item->restaurant_address, $text);
		}
		if ($min_price) {
			$text = str_replace('**min_price**', $min_price, $text);
		}
		if ($max_price) {
			$text = str_replace('**max_price**', $max_price, $text);
		}

		if (!(strpos($text, '**area**') === false) && $rest_item) {
			if ($rest_item && ($rest_item->restaurant_district == 547 || $rest_item->restaurant_parent_district == 547)) {
				$text = str_replace('**area**', 'в Подмосковье', $text);
			} else {
				$text = str_replace('**area**', 'в Москве', $text);
			}
		} elseif (!(strpos($text, '**area**') === false)) {
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
		if(isset($item->restaurant_main_type)) {
			$text = str_replace('**rest_type**', $item->restaurant_main_type, $text);
			$text = str_replace('**rest_type_lcase**', mb_strtolower($item->restaurant_main_type), $text);
		}
		$text = str_replace('**rest_address**', $item->restaurant_address, $text);
		$text = str_replace('**capacity**', $item->restaurant_max_capacity, $text);
		$text = str_replace('**min_capacity**', $item->restaurant_min_capacity, $text);
		$text = str_replace('**max_capacity**', $item->restaurant_max_capacity, $text);
		if (!(strpos($text, '**capacity_full**') === false)) {
			if($item->restaurant_min_capacity == $item->restaurant_max_capacity){
				$text = str_replace('**capacity_full**', 'для '.$item->restaurant_max_capacity, $text);
			}else{
				$text = str_replace('**capacity_full**', 'от '.$item->restaurant_min_capacity.' до '.$item->restaurant_max_capacity, $text);
			}
		}
		if (!(strpos($text, '**capacity_full_hall**') === false)) {
			if($item->restaurant_min_capacity == $item->restaurant_max_capacity){
				$text = str_replace('**capacity_full_hall**', 'зала — '.$item->restaurant_max_capacity, $text);
			}else{
				$text = str_replace('**capacity_full_hall**', 'залов от '.$item->restaurant_min_capacity.' до '.$item->restaurant_max_capacity, $text);
			}
		}
		$text = str_replace('**rest_name**', $item->restaurant_name, $text);
		if (isset($item->restaurant_price)) {
			$text = str_replace('**price**', $item->restaurant_price, $text);
		}

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
