1 меняем в console конфиг на нужную бд, накатываем миграции
2 composer install
3 в конфиг модуля main.php и на фронте и на бэке добавляем строчку

$params = array_merge(
    require __DIR__ . '/../../../common/config/params.php',
    require __DIR__ . '/../../../common/config/params-local.php',
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-local.php',
    \common\utility\SiteParamsHelper::getParamsForModule(%название папки модуля%)
);

глобально в \Yii::$app->params становятся доступны 'siteAddress'  = %название_хоста_без_поддомена%.ru:880 и 'siteProtocol'

поэтому switch($host) в index.php можно заменить на switch($config['params']['siteAddress'])

4 если нужно добавить к сущности картинки и сео
- наследуемся ей от common\models\siteobject\BaseSiteObject (по аналогии с Pages)
- создаем(или расширяем) frontend/modules/%модуль%/models файл класса MediaEnum с примерно следующим содержимым

<?php
namespace frontend\modules\pmnbd\models;

use common\models\Pages;
use common\models\siteobject\BaseMediaEnum;
use common\models\SubdomenPages;

class MediaEnum extends BaseMediaEnum
{
    const HEADER_IMAGE = 'header-image'; //alias по которому будут доступны картинки
    const ADVANTAGES = 'advantages'; //alias по которому будут доступны картинки

    const LABEL_MAP = [
        self::HEADER_IMAGE => 'Изображения шапки', //подпись инпута в админке
        self::ADVANTAGES => 'Изображения преимуществ', //подпись инпута в админке
    ];

    public static function getMediaTypes() //обязательный метод в котором для названия класса накидываем какие нужны типы картинок
    {
        return [
            SubdomenPages::class => [self::HEADER_IMAGE, self::ADVANTAGES],
            Pages::class => [self::HEADER_IMAGE, self::ADVANTAGES],
        ];
    }
}

- добавить в соответствующую вьюху админки(_form) рендер инпутов и сео по аналогии backend/views/blog-tag/_form.php
там есть уже отдельные вьюхи для отображения всей этой херовины. В инпутах у каждой картинки есть свой инпут в который можно вбить альт, и не забыть нажать дискетку. сортировка также в комплекте
- аккуратно скопировать содержимое backend/_site.js в backend/web/js/site.js. Чтобы убрать инстазагрузку файлов убираем блок из site.js под комментом //insta upload
 
- на фронте в контроллере вызываем на сущности например Pages::getFilesData(MediaEnum::HEADER_IMAGE) и получаем объекты картинок. сигнатуры и выхлоп этого метода смотреть в common\models\siteobject\BaseSiteObject, вкратце на выхлопе объекты у которых два поля src и alt. Есть метод получения только объекта самой первой картинки getFileData(MediaEnum::HEADER_IMAGE)

5 в backend/config/%name%/main.php в 'urlManager' суем 'suffix' => '/' (тут скорее всего в некоторых местах по коду придется добавлять этот суффикс к вручную вписанным урлам админки, но у меня слишком уж много где в этой системе вручную навписано урлов с этим суффиксом поэтому вот, да). и в 'rules' суем

'rules' => [
                ['pattern' => '/update', 'route' => 'update/update'],
                'media/<id:\d+>/resort/<sort:\d+>' => 'media/resort',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'blog-blocks',
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'blog-post-blocks',
                    'extraPatterns' => [
                        'POST sort' => 'sort',
                    ]
                ],
                //нащет того что ниже не уверен, вроде по дефолту также работает, хз
                '<controller>' => '<controller>/index',
                '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
                '<controller>/<action>' => '<controller>/<action>',
            ],

6 недоделано
- backend/modules/%name%/controlles/SubdomenPagesController если надо чтоб сеошка по новой системе была, то в actionCreate надо добавить 
if($model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
}
вместо
return $this->render('create', [
            'model' => $model,
        ]);
- моделька Seo костыльно конфигурируется чтобы подсасывать файлы для Pages и SubdomenPages через withMedia
пример в контроллере $seo = (new Seo($type, $page, $count))->withMedia([MediaEnum::HEADER_IMAGE, MediaEnum::ADVANTAGES]);
в twig <img src="{{seo.media["header-image"]|first.src}}" alt="{{seo.media['header-image']|first.alt}}">

===============================================
для блога
1 в backend/blog-constructor npm install && npm run build. в backend/web/js появляется constructor.js. Он сам во вьюхе подключается через backend\assets\Constructor
2 в backend/modules/%name%/views/layouts/left.php добавляем

['label' => 'Блог', 'options' => ['class' => 'header']],
['label' => 'Посты', 'icon' => 'pencil-square-o', 'url' => ['/blog-post']],
['label' => 'Тэги', 'icon' => 'tags', 'url' => ['/blog-tag']],
['label' => 'Блоки', 'icon' => 'indent', 'url' => ['/blog-block']]

3 по функционалу конструктора https://liderpoiska.ru/upload/howto1.png и https://liderpoiska.ru/upload/howto2.png

4 по написанию блоков

- в Inputs пишем json вида
{
    "text": [{"title":"заголовок","slug":"h","heading":1}],
    "html": [{"title":"текст","slug":"t"},{"title":"ищо текст","slug":"t2"}]
    "image":[{"title":"Изображения","slug":"pic"}], 
    "settings": [
        {
            "title":"Цветовая тема",
            "slug":"theme",
            "type":"select",
            "variants":[
                { "value": "default", "label": "Обычная" },
                { "value": "white", "label": "Светлая" }
            ]
            
        },
    ]
}
где "text" это массив с объектами, описывающими ПРОСТЫЕ текстовые инпуты, которые на выходе дают чистый текст.
"html" это массив с объектами, описывающими инпуты с полноценными html редакторами, которые на выходе дают html.
"image" это массив с объектами, описывающими файловые инпуты.
в которых
"title" - название инпута
"slug" - уникальный для инпута постфикс, который будет нужен в шаблоне
"heading"(не обяз.) - уровень заголовка, это если надо будет вывести оглавление, отдельно обьясню

также свои настройки блока "settings" - массив объектов , описывающих инпут настройки блока
"type" - пока только "select" вроде бы
"variants" - "value" = че будет на выходе при выбранном селекте, "label" название опции

- в Template пишем темплейт
<div id="{{paragraph.alias}}" class="post-block post-block_color_{{setting_color}} post-block_margin_{{setting_margin}}">
  <div class="wrapper wrapper_size_{{setting_size}}">
    <h2 class="post-block__h2">{{text_h}}</h2>
    <div class="post-block__text">{{html_t}}</div>
    <div class="post-block__text">{{html_t2}}</div>
    {{# image_pic }}
                    <div class="swiper-slide">
                        <img class="" src="{{src}}" alt="{{alt}}" />
                        <div class="swiper-slide__caption txt-format_color_{{setting_theme}}">
                            {{alt}}
                        </div>
                    </div>
    {{/ image_pic }}
  </div>
</div>
-для рендера используется https://github.com/bobthecow/mustache.php/wiki/Mustache-Tags
сам рендер если интересно в BlogBlock render()
-в {{}} выводится контент из инпутов
например {{html_t2}} выводит html из инпута описанного вторым объектом(со slug t2) определнного ранее массива "html": [{"title":"текст","slug":"t"},{"title":"ищо текст","slug":"t2"}]
в конструкции
    {{# image_pic }}
    {{/ image_pic }}
доступны поля src и alt для вывода ссылки и альта соотв-но
-setting_color, setting_margin, setting_size дефолтные настройки, описанные сейчас вначале в constructor.jsx. 
Поидее они вообще не нужны, но были запилены в самом начале еще до внедрения кастомных настроек
соотв-но setting_theme это значение из выбранного инпута, описанного в 



