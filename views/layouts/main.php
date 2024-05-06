<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="/web/css/style.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => HTML::img('@web/image/med.png', ['class'=>'logo', 'alt'=>'Логотип']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            Yii::$app->user->isGuest
            ? ['label' => 'Регистрация', 'url' => ['/site/register']]
            :['label' => 'Заявления', 'url' => ['/report/index']],

            !Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()
            ? ['label' => 'Создать заявление', 'url' => ['/report/create']]
            : '',
            
            Yii::$app->user->isGuest
                ? ['label' => 'Вход', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->login . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">
                <p>Контактная информация</p>
                <ul>
                    <li>номер телефона</li>
                    <li>почта</li>
                </ul>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <?php if (Yii::$app->user->isGuest) : ?>
                    <ul>
                        <li>
                            <a class="nav-link" href="/site/index">Главная</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/site/login">Вход</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/site/register">Регистрация</a>
                        </li>
                    </ul>
                    <?php elseif (Yii::$app->user->identity->isAdmin()) : ?>
                        <ul>
                        <li>
                            <a class="nav-link" href="/site/index">Главная</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/report/index">Заявления</a>
                        </li>
                    </ul>
                        <?php else : ?>
                            <li>
                            <a class="nav-link" href="/site/index">Главная</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/report/index">Мои Заявления</a>
                        </li>
                        <li>
                            <a class="nav-link" href="/report/create">Создать заявление</a>
                        </li>
                            <?php endif ?>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
