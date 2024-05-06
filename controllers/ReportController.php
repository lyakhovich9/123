<?php

namespace app\controllers;

use app\models\Report;
use app\models\ReportSearch;
use app\models\Status;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

  
    public function actionIndex()
    {
        $user = User::getInstance();
        if (!$user) 
        {
            return $this->goHome();
        }
        if ($user->isAdmin()) {

            $searchModel = new ReportSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
    
            return $this->render('index_admin', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->search($this->request->queryParams,$user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


  
    public function actionCreate()
    {
        $user = User::getInstance();
        if (!$user) 
        {
            return $this->goHome();
        }
        $model = new Report();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->status_id = Status::NEW;
                $model->user_id = $user->id; 
                if ($model->save()) {
                    return $this->redirect('index');
                  }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

  
    public function actionUpdate($id)
    {
        $user = User::getInstance();
        if (!$user) 
        {
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    protected function findModel($id)
    {
        if (($model = Report::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
