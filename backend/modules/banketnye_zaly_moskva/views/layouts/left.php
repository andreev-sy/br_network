<aside class="main-sidebar">

    <section class="sidebar">

        <br/>

        <?php
            //$role = array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
            //if($role == 'admin'){
                echo dmstr\widgets\Menu::widget([
                        'options' => ['class' => 'sidebar-menu', 'data-widget'=> 'tree'],
                        'items' => [
                                ['label' => 'Рестораны', 'url' => ['/restaurants']],
                                ['label' => 'Залы', 'url' => ['/rooms']],
                                ['label' => ''],
                                // ['label' => 'Фильтр', 'url' => ['/filter']],
                                ['label' => 'Срезы', 'url' => ['/slices']],
                                ['label' => 'Страницы', 'url' => ['/pages']],
                                // ['label' => 'Виджеты главной', 'url' => ['/widget-main']],
                                ['label' => 'Блог', 'options' => ['class' => 'header']],
                                ['label' => 'Посты', 'icon' => 'pencil-square-o', 'url' => ['/blog-post']],
                                ['label' => 'Тэги', 'icon' => 'tags', 'url' => ['/blog-tag']],
                                ['label' => 'Блоки', 'icon' => 'indent', 'url' => ['/blog-block']]
                            ],
                        ]
                    );
            //}
            //else{
            //    echo dmstr\widgets\Menu::widget([
            //            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            //            'items' => [
            //                    ['label' => 'Квартиры', 'url' => ['/kvartiry']],
            //                    ['label' => 'Дома', 'url' => ['/doma']]
            //                ],
            //            ]
            //        );
            //}
                

        ?>

    </section>

</aside>
