<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Actions extends ActiveRecord
{
	public static function tableName()
    {
        return 'actions';//创建一个数据模型链接数据表
    }
	
	function update_action_state($value){
		$model = new Actions();
		$model::updateAll(['action_state'=>'0'],['and', ['device_mac'=>$value], ['or', 'act_type_id=5', 'act_type_id=6']]);
	}
	
	function update_action_state_1032($action_id)
	{
		$model = new Actions();
		$model::updateAll(['action_state'=>'9'],['action_id'=>$action_id]);
	}
	
	function delete_act_type_id_6($mac)
	{
		$model = new Actions();
		$model::deleteAll(['device_mac'=>$mac,'act_type_id'=>'6','action_state'=>'0']);
		//"DELETE FROM actions WHERE device_mac='$mac' AND act_type_id=6 AND action_state=0"
	}
	
	function delete_act_type_id_7($mac)
	{
		$model = new Actions();
		$model::deleteAll(['device_mac'=>$mac,'act_type_id'=>'7','action_state'=>'0']);
		//"DELETE FROM actions WHERE device_mac='$mac' AND act_type_id=7 AND action_state=0"
	}
	
	function select_params_state_id($action_id)
	{
		//"SELECT act_params,action_state,act_type_id FROM actions WHERE actions.action_id={$action_id} "
		$model = new Actions();
		$query = $model::find()->select(['act_params','action_state','act_type_id'])->where(['action_id'=>$action_id])->all();

		return ['act_params' => $query[0]['act_params'],
				'action_state' => $query[0]['action_state'],
				'act_type_id' => $query[0]['act_type_id']
		];
	}
	
	function update_action_state_for_ackaction($action_id)
	{
		$model = new Actions();
		$model::updateAll(['action_state'=>'2'],['action_id'=>$action_id]);

	}
	
	function update_action_state_for_actfail($action_err,$action_id)
	{
		//"update actions set action_state={$action_err} where action_id = {$action_id}"
		$model = new Actions();
		$model::updateAll(['action_state'=>$action_err],['action_id'=>$action_id]);
		
	}
	
	
	
}


?>