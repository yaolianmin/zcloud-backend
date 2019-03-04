<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\testmodel;
use yii\web\UploadedFile;

class TestController extends Controller
{
    public function actionTest()
    {
        $model = new testmodel();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // �ļ��ϴ��ɹ�
                return;
            }
        }

        return $this->render('test', ['model' => $model]);
    }
}

?>