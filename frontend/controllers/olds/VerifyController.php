<?php
namespace frontend\controllers;

use yii\web\Controller;


/**
* 功能：显示自制的验证码
*  
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/9/18 14:06 
*/

class VerifyController extends Controller{

	/**
	*验证码显示页面
	*/
    public function actionVerify(){

    	return $this->renderPartial('verify');

    }


}