<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'language', 'index', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionLanguage($language)
    {
        Yii::$app->language = $language;

        $languageCookie = new yii\web\Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
        ]);
        Yii::$app->response->cookies->add($languageCookie);

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionProfile()
    { 
        $model = User::findOne(Yii::$app->user->id);

        if (!empty(Yii::$app->request->post()) and $model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('User');
            if(!empty($post['password'])) $model->setPassword($post['password']);
            if(!$model->save()) {
                Yii::$app->session->setFlash('kv-detail-warning', Yii::t('app', 'Не удалсоь сохранить элемент, проверьте правильность заполнения полей'));
            }else{
                Yii::$app->session->setFlash('kv-detail-success', Yii::t('app', 'Данные сохранены'));
                return $this->redirect(['profile']);
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

}
