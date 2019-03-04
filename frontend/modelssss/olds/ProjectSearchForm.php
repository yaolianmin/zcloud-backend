<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Command;





class ProjectSearchForm extends Model
{
	
	public $projectname_search;
    public $power_condition;
    public $devname_condition;
    public $username_condition;
    public $devname_select;
    public $username_select;
    /**
    *数据验证规则
    */
    public function rules()
    {
        return [
            // name, email, password and power are required
            
           // [['projectname_search','power_condition','devname_condition','username_condition','devname_select','username_select'], 'string', 'max' => 256],

           [['projectname_search','power_condition','devname_condition','username_condition','devname_select','username_select'],'safe'],
        ];
    }



}