<?php
namespace xoa\home\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
	public function behaviors() {
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'user' => 'worker',
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index'],
						'roles' => ['@'],
					],
				],
			],
		];
	}
	
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? '121212' : null,
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
		return $this->renderFile('@webPath/home.html');
    }
}