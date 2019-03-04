<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\db\ActiveRecord;
use common\error\Error;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use backend\models\common\Common_model;
use backend\models\cfg_management\product_management\ProductManagement;
use backend\models\Dev_card_id;
/**
 * product management controller.
 * 机种管理控制器.
 */
class ProductManagementController extends ActiveController
{   
    /**
     * 指定调用这个控制器时链接哪个数据模型.
     * 该属性必须设置.
     */
    public $modelClass = 'backend\models\Dev_card_id';
    /**
     * 设置控制器的属性
     * @return $actions
     */
    public function actions()
    {
	    $actions = parent::actions();
        /**
         * 注销系统自带的实现方法.
         */
	    unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
	   	return $actions;
     }

    /**
     * Action: Displays all infomation of all products.
     * @return array
     */
    public function actionIndex()
    {   
        $_get = Yii::$app->request->get();
        $modelClass = $this->modelClass;
        $query = $modelClass::find();

        $_data = $query->all();
        $_count =  $query->count();

        return array ("count" => $_count,"body" => $_data);
    }

    /**
     * Action: Updates an existing product model.
     * @param string $id the primary key of the product model
     * @return the product model being updated
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $_post = Yii::$app->request->post();
       
        $model->attributes = $_post;

        if (! $model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    /**
     * Action: Updates an existing product model,and template file need update.
     * @param string $id the primary key of the product model
     * @return the product model being updated
     */
    public function actionUpdateTemplate($id)
    {
        
        $model = $this->findModel($id);
        $_post = Yii::$app->request->post();
        //获取保存上传文件的路径
        $path = ProductManagement::save_model_uploadfile($_post);
        //更新对应机种数据表
        ProductManagement::updateProductDefaultTemplateTable($_post['model_name'],$path);

        $model->template_path = $path; 
        $model->attributes = $_post;
        if (! $model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    /**
     * Action: Create a new product.
     * @return the product model being created
     */
    public function actionCreate()
    {
        $model = new $this->modelClass();
        $_post = Yii::$app->request->post();
        //获取保存上传文件的路径
        $path = ProductManagement::save_model_uploadfile($_post);
        //为对应机种创建数据表
        ProductManagement::create_product_default_template_table($_post['model_name'],$path);

        $model->attributes = $_post;
        $model->template_path = $path;
        if (! $model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    /**
     * Action: Delete a  product.
     * @param string $id the primary key of the model.
     * @return 
     */
    public function actionDelete($id)
    {
        $_model = $this->findModel($id);
        /**
         * 删除相关文件及目录和数据表.
         */
        ProductManagement::delete_model_uploadfile($_model['model_name']);
        return $_model->delete();
    }

    /**
     * Function: select a product by id.
     * @param string $id the primary key of the product model
     * @return the product model being founded
     * @throws ServerErrorHttpException if there is any error when seraching the model
     */
    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

}