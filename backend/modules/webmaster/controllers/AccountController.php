<?php

namespace backend\modules\webmaster\controllers;

use backend\modules\webmaster\models\WebMasterSearch;
use common\components\grid\HandleChangeSingleColumnAction;
use common\models\WebMaster;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2mod\editable\EditableAction;

/**
 * AccountController implements the CRUD actions for WebMaster model.
 */
class AccountController extends Controller
{
    public $defaultAction = 'list';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
            'status' => [
                'class' => HandleChangeSingleColumnAction::class,
            ],
            'update-attr' => [
                'class' => EditableAction::class,
                'modelClass' => WebMaster::class,
            ]
        ];
    }
    
    /**
     * Lists all WebMaster models.
     *
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new WebMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new WebMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebMaster();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '创建成功');
            
            return $this->redirect(['list']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing WebMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '更新成功');
            
            return $this->redirect(['list']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Finds the WebMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @throws NotFoundHttpException if the model cannot be found
     *
     * @return WebMaster the loaded model
     */
    public function findModel($id)
    {
        if (($model = WebMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
