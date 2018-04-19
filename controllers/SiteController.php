<?php

namespace app\controllers;

use app\models\Countries;
use app\models\forms\CarModelsForm;
use app\models\LanguagesMultiple;
use app\models\search\CarsSearch;
use app\models\search\ModelsSearch;
use app\services\ModelService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * Основной контроллер приложения
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
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Главная страница.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница с формой для загрузки и обновления данных по авто
     *
     * @return string
     */
    public function actionFormRequest()
    {
        $carModelsForm = new CarModelsForm();
        $countries = new Countries();
        $data = $countries->getListCountries();

        if(\Yii::$app->request->post()) {
            $modelService = new ModelService();
            $modelService->insertModels();
        }

        return $this->render('form_send', [
            'carModelsForm' => $carModelsForm,
            'data'          => $data
        ]);
    }

    /**
     * Получение списка доступных языков
     *
     * @return Response
     */
    public function actionGetLanguages()
    {
        $country = Countries::findOne(['name' => $_POST['countryName']]);
        if($country->is_multiple_lang) {
            /** @var LanguagesMultiple[] $languages */
            $languagesOfCountry = $country->langMultiples;
            $languages = [];
            foreach ($languagesOfCountry as $key => $language) {
                $languages[] = [
                    'name' => $languagesOfCountry[$key]->languages->name,
                    'code' => $languagesOfCountry[$key]->languages->code
                ];
            }

        } else {
            $languages = $country->languages;
        }

        return $this->asJson($languages);
    }

    /**
     * Отображение списка моделей авто по странам
     *
     * @return string
     */
    public function actionCarList()
    {
        $searchModel = new ModelsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('car_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Показывает список доступных авто определенной марки
     * @param integer $id
     * @return mixed
     */
    public function actionView(int $id)
    {
        $searchModel = new CarsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
