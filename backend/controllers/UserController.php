<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\CommonVariables;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [        		
        	'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
        			//not logged users do not have access to any action
        			/*[
        			 'actions' => ['login', 'error'],
        				'allow' => true,
        			],*/
        			//only logged users have access to actions
        			[
        				'actions' => [	'index', 'view', 'create', 'update', 'delete', 'profile',
        				],
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
    public function actions()
    {
    	$this->layout = 'adminlayout';
    	return [
    			'error' => [
    					'class' => 'yii\web\ErrorAction',
    			],
    	];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    

    public function actionProfile()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(['error']);
    	}
    	 
    	$id = Yii::$app->user->identity->attributes["id"];
    	$model = $this->findModel($id);
    	var_dump($model->middle_name);
    	if ($model->load(Yii::$app->request->post())) {
    		//var_dump($this->middle_name);
    		var_dump($model->middle_name);
    		var_dump(Yii::$app->request->post());
    		return;
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {    	
	    	$common_vars = new CommonVariables();
	    	
	    	if(isset($model->gender)){
	    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$model->gender => ['Selected' => 'selected']]];
	    	} else {
	    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
	    	}
	    	
	    	if(isset($model->country)){
	    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$model->country => ['Selected' => 'selected']]];
	    	} else {
	    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
	    	}
	    	
	    	$additional_vars = (object)$additional_params;
	    	 
	    	return $this->render('profile', [
	    			'model' => $model,
	    			'common_vars' => $common_vars,
	    			'additional_vars' => $additional_vars
	    	]);
    	}
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
