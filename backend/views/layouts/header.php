<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;

/* @var $this \yii\web\View */
/* @var $content string */


?>

<header class="main-header" style="position: relative;">
    <?= Html::a(
        Html::tag('span', Yii::$app->name, ['class'=>'logo-lg']).
        Html::tag('span', Yii::$app->name, ['class'=>'logo-mini']), 
        Yii::$app->homeUrl, 
        ['class' => 'logo']
    ) ?>
    <nav class="navbar" role="navigation" style="border: 0;max-height: 50px;">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="display: none">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-language"></i>
                    </a>
                    <div class="dropdown-menu">
                        <div><?= Html::a( 'Português (BR)', ['/site/language', 'language' => 'pt-BR'], ['class' => 'btn btn-flat']) ?></div>
                        <div><?= Html::a( 'Русский (RU)', ['/site/language', 'language' => 'ru-RU'], ['class' => 'btn btn-flat']) ?></div>
                    </div>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?php if(!empty(Yii::$app->user->identity->photo_path)): ?>
                            <img src="<?= Yii::$app->user->identity->photo_path ?>" class="user-image" alt="User Image">
                        <?php endif; ?>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->fullname ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?php if(!empty(Yii::$app->user->identity->photo_path)): ?>
                                <img src="<?= Yii::$app->user->identity->photo_path ?>" class="img-circle" alt="User Image">
                            <?php endif; ?>
                            <p>
                                <?= Yii::$app->user->identity->fullname ?> - <?= Yii::$app->user->identity->role ?>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app', 'Профиль'),
                                    ['/site/profile'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('app', 'Выйти'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu" style="display:none"><button class="btn btn-primary" data-toggle="control-sidebar">Toggle Right Sidebar</button></li>

            </ul>
        </div>
    </nav>
</header>