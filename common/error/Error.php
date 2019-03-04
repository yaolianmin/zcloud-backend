<?php

namespace common\error;

use Yii;

class  Error{
	
	public static function Info($_errors,$request) 
	{ 

		$result  = strstr($_errors,Yii::t('yii','Not exist'));   //数据不存在  10001
        $result1 = strstr($_errors,Yii::t('yii','Null'));        //参数不能为空  10002
        $result2 = strstr($_errors,Yii::t('yii','Fail'));        //新增、更新、删除失败 10003
        $result3 = strstr($_errors,Yii::t('yii','Not right'));   //XX不正确 10004
        $result4 = strstr($_errors,Yii::t('yii','Robc'));        //XX无权限 10005

        //数据不存在  10001
        if(!empty($result))
        {  
           return self::ErrorCreate($_errors,'10001',$request);
        }

        //参数不能为空  10002
       	if(!empty($result1))
        {  
           return self::ErrorCreate($_errors,'10002',$request);
        }

        //新增、更新、删除失败 10003
        if(!empty($result2))
        {  
           return self::ErrorCreate($_errors,'10003',$request);
        }

        //XX不正确 10004
        if(!empty($result3))
        {  
           return self::ErrorCreate($_errors,'10004',$request);
        }
        
        //XX无权限 10005
        if(!empty($result4))
        {  
           return self::ErrorCreate($_errors,'10005',$request);
        }
       
        //默认类型 11000
        return self::ErrorCreate($_errors,'11000',$request);
       

	}


	 public static function ErrorCreate($_errors,$code,$request) 
	 {  
            if (empty($_errors)) 
            {
                $errors['error']['request']        	= 	 	 $request;
                $errors['error']['error_code']  	=       	$code; 
                $errors['error']['error']     		=    "successful";
                return $errors;
                //print_r(json_encode([]));
            } else { 
                $errors['error']['request']        	= 	 $request;
                $errors['error']['error_code']  	=       $code; 
                $errors['error']['error']     		=    $_errors;
                return $errors;
                //print_r(json_encode($errors));
            }
    }




}