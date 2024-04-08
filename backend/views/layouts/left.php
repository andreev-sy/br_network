<?php
use yii\helpers\Url;
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <br />
        <?php 
        $items = [
            [
                'label' => Yii::t('app', 'Заведения'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('app', 'Список'), 'url' => ['/venues/index']],
                    ['label' => Yii::t('app', 'Обработанные'), 'url' => ['/venues/proccessed']],
                    ['label' => Yii::t('app', 'Заведения без залов (дубли)'), 'url' => ['/venues/empty']],
                    [
                        'label' => Yii::t('app', 'Справочники'),
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Изображения'), 'url' => ['/images/venues']],
                            ['label' => Yii::t('app', 'Правила украшения'), 'url' => ['/venues-decor-policy/index']],
                            ['label' => Yii::t('app', 'Сервисы за отдельную плату'), 'url' => ['/venues-extra-services/index']],
                            ['label' => Yii::t('app', 'Кухня'), 'url' => ['/venues-kitchen-type/index']],
                            ['label' => Yii::t('app', 'Расположение'), 'url' => ['/venues-location/index']],
                            ['label' => Yii::t('app', 'Можно свой алкоголь'), 'url' => ['/venues-own-alcohol/index']],
                            ['label' => Yii::t('app', 'Парковка'), 'url' => ['/venues-parking-type/index']],
                            ['label' => Yii::t('app', 'Способы оплаты'), 'url' => ['/venues-payment/index']],
                            ['label' => Yii::t('app', 'Расстановка столов'), 'url' => ['/venues-seating-arrangement/index']],
                            ['label' => Yii::t('app', 'Сайты источники'), 'url' => ['/venues-sites/index']],
                            ['label' => Yii::t('app', 'События'), 'url' => ['/venues-spec/index']],
                            ['label' => Yii::t('app', 'Особенности'), 'url' => ['/venues-special/index']],
                            ['label' => Yii::t('app', 'Статусы'), 'url' => ['/venues-status/index']],
                            ['label' => Yii::t('app', 'Тип площадки'), 'url' => ['/venues-type/index']],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Визиты'),
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Список'), 'url' => ['/venues-visit/index']],
                            ['label' => Yii::t('app', 'Статусы'), 'url' => ['/venues-visit-status/index']],
                        ],
                    ],
                ],
            ],

            [
                'label' => Yii::t('app', 'Залы'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('app', 'Список'), 'url' => ['/rooms/index']],
                    ['label' => Yii::t('app', 'Привязка залов к заведениям'), 'url' => ['/rooms/set-venue']],
                    [
                        'label' => Yii::t('app', 'Справочники'),
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Изображения'), 'url' => ['/images/rooms']],
                            ['label' => Yii::t('app', 'Особенности'), 'url' => ['/rooms-features/index']],
                            ['label' => Yii::t('app', 'Расположения'), 'url' => ['/rooms-location/index']],
                            ['label' => Yii::t('app', 'Схемы оплаты'), 'url' => ['/rooms-payment-model/index']],
                            ['label' => Yii::t('app', 'Функциональные зоны'), 'url' => ['/rooms-zones/index']],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Лофт'),
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Цвета'), 'url' => ['/rooms-loft-color/index']],
                            ['label' => Yii::t('app', 'Входы и выходы'), 'url' => ['/rooms-loft-entrance/index']],
                            ['label' => Yii::t('app', 'Мебель'), 'url' => ['/rooms-loft-equipment-furniture/index']],
                            ['label' => Yii::t('app', 'Игры'), 'url' => ['/rooms-loft-equipment-games/index']],
                            ['label' => Yii::t('app', 'Предметы интерьера'), 'url' => ['/rooms-loft-equipment-interior/index']],
                            ['label' => Yii::t('app', 'Техника и другое оборудование'), 'url' => ['/rooms-loft-equipment1/index']],
                            ['label' => Yii::t('app', 'Принадлежности для еды и напитков'), 'url' => ['/rooms-loft-equipment2/index']],
                            ['label' => Yii::t('app', 'Профессиональное оборудование'), 'url' => ['/rooms-loft-equipment3/index']],
                            ['label' => Yii::t('app', 'Особенности интерьера'), 'url' => ['/rooms-loft-interior/index']],
                            ['label' => Yii::t('app', 'Освещение'), 'url' => ['/rooms-loft-light/index']],
                            ['label' => Yii::t('app', 'Персонал'), 'url' => ['/rooms-loft-staff/index']],
                            ['label' => Yii::t('app', 'Стили'), 'url' => ['/rooms-loft-style/index']],
                        ],
                    ],
                ],
            ],

            [
                'label' => Yii::t('app', 'Общие справочники'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('app', 'Агломерации'), 'url' => ['/agglomeration/index']],
                    ['label' => Yii::t('app', 'Города'), 'url' => ['/cities/index']],
                    ['label' => Yii::t('app', 'Округа'), 'url' => ['/region/index']],
                    ['label' => Yii::t('app', 'Районы'), 'url' => ['/district/index']],
                    ['label' => Yii::t('app', 'Изображения'), 'url' => ['/images/index']],
                ],
            ],

            [
                'label' => Yii::t('app', 'Заявки с форм'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('app', 'Список'), 'url' => ['/form-request/index']],
                    ['label' => Yii::t('app', 'От клиентов'), 'url' => ['/form-request/client']],
                    ['label' => Yii::t('app', 'От заведений'), 'url' => ['/form-request/rest']],
                    ['label' => Yii::t('app', 'Данные от заведений'), 'url' => ['/form-request/partners']],
                ],
            ],

            [
                'label' => Yii::t('app', 'Подборки'),
                'url' => '#',
                'items' => [
                    ['label' => Yii::t('app', 'Список'), 'url' => ['/collection/index']],
                    [
                        'label' => Yii::t('app', 'Справочники'),
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'События'), 'url' => ['/collection-spec/index']],
                            ['label' => Yii::t('app', 'Гости'), 'url' => ['/collection-guest/index']],
                            ['label' => Yii::t('app', 'Цены'), 'url' => ['/collection-price-person/index']],
                            ['label' => Yii::t('app', 'Контакты'), 'url' => ['/collection-contact-type/index']],
                        ],
                    ],
                ],
            ],
        ];

        if(Yii::$app->user->can('Полный доступ')){
            $items[] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['/user/index']];
            $items[] = [
                'label' => 'Tools',
                'url' => '#',
                'items' => [
                    ['label' => 'Translations', 'url' => ['/message/index']],
                    ['label' => 'Move DB', 'url' => ['/import/index']],
                    ['label' => 'Gii', 'url' => ['/gii/default/index']],
                    ['label' => 'Debug', 'url' => ['/debug/default/index']],
                    [
                        'label' => 'Rbac',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Маршруты', 'url' => ['/rbac/route/index']],
                            ['label' => 'Правила', 'url' => ['/rbac/rule/index']],
                            ['label' => 'Разрешения', 'url' => ['/rbac/permission/index']],
                            ['label' => 'Роли', 'url' => ['/rbac/role/index']],
                            ['label' => 'Назначения', 'url' => ['/rbac/assignment/index']],
                        ],
                    ],
                ],
            ];
        }

        ?>
        <?= backend\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'items' => $items
            ]
        ) ?>
    </section>
</aside>