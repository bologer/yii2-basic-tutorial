<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\Car;
use app\models\search\CarSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CarController extends Controller
{
    /**
     * @var null|User
     */
    private $_user = null;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->_user === null)
            $this->_user = Yii::$app->user->identity;

        return parent::beforeAction($action);
    }

    /**
     * Просмотр всех автомобилей
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр определенного автомобиля
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Создание нового автомобиля.
     * Если удаление будет успешным, то пользователя перекинет на главную страницу (view).
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Car();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Изменение определенного автомобиля.
     * @param integer $id Уникальное ID автомобиля.
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::info("Машина ID: {$model->id} была изменена {$this->_user->username}");
            } else {
                Yii::error("Машина ID: {$model->id} НЕ была изменена {$this->_user->username}. Возможные ошибки модели: " . json_encode($model->errors));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удалить существующий автомобиль.
     * Если удаление будет успешным, то пользователя перекинет на главную страницу (index).
     * @param integer $id Уникальное ID машины.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $modelId = $model->id;

        if ($model->delete()) {
            Yii::info("Машина ID: {$modelId} была удалена {$this->_user->username}");
        } else {
            Yii::error("Машина ID: {$modelId} НЕ была удалена {$this->_user->username}. Возможные ошибки модели: " . json_encode($model->errors));
        }

        return $this->redirect(['index']);
    }

    /**
     * Находит нужный автомобиль исходя из ключа ID.
     * Если машина не будет найдета, то бросает 404 HTTP ошибку.
     * @param integer $id Уникальное ID автомобиля.
     * @return Car Загруженная модель
     * @throws NotFoundHttpException Если не удалось найти автомобиль.
     */
    protected function findModel($id)
    {
        if (($model = Car::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}