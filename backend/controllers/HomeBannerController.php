<?php

namespace backend\controllers;

use Yii;
use common\models\HomeBanner;
use common\models\HomeBannerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * HomeBannerController implements the CRUD actions for HomeBanner model.
 */
class HomeBannerController extends Controller
{
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
     * Lists all HomeBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HomeBannerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HomeBanner model.
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
     * Creates a new HomeBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
    {
        $model = new HomeBanner();

        if ($model->load(Yii::$app->request->post())) {
            $image=UploadedFile::getInstance($model, 'BannerImage');
           /* echo '<pre>';
            print_r($image);
            exit;
            echo '</pre>';*/
            $fileend=explode(".", $image->name);
            $ext = end($fileend);
            $basepath=Yii::getAlias('@storage');
            Yii::$app->params['uploadPath'] = $basepath . '/imageupload/';
            $ppp=Yii::$app->security->generateRandomString();
            $path = Yii::$app->params['uploadPath'] . $ppp.".{$ext}";
            $model->BannerImage = $ppp.".{$ext}";
            $model->OnDate=date('Y-m-d H:i:s');
            
            $image->saveAs($path);
            $model->save();
            Yii::$app->session->setFlash('success', "Banner added successfully.");
            return $this->redirect(['index', 'id' => $model->BannerId]);
        } else {
            Yii::$app->session->setFlash('Error', "There is some error.");
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing HomeBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Your message to display.");
            return $this->redirect(['view', 'id' => $model->BannerId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing HomeBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Your message to display.");
        return $this->redirect(['index']);
    }

    /**
     * Finds the HomeBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HomeBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HomeBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
