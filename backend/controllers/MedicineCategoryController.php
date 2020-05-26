<?php

namespace backend\controllers;

use Yii;
use common\models\MedicineCategory;
use common\models\search\MedicineCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MedicineCategoryController implements the CRUD actions for MedicineCategory model.
 */
class MedicineCategoryController extends Controller
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
     * Lists all MedicineCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
	   return $this->render('index');
    }

}
