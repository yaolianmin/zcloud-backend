<?php


namespace frontend\controllers;
use Yii;
use yii\web\Controller;
//use frontend\models\File_management;
use frontend\models\Common;
use yii\base\Model;

use yii\data\Pagination;
use frontend\models\Login;


class File_managementController extends Controller{
	
	public $enableCsrfValidation = false;//post传值时 关闭csrf验证功能
	
	public function actionFile_management()
	{
		//判断是否是语言切换的提�?
		$request = Yii::$app->request;
		
        if($request->isGet){
            $language = $request->get('lang');
			
            if($language){
                $result = Common::change_language($language);
                if($result == 'success'){
                    return $this->redirect(['file_management/file_management']);
                } 
            }  
        }
		
		if ($request->isPost) {
			$data = $request->post();
			$flag = $data['flag'];
			if($flag == "1")
			{
				$firmware_name= $data['firmware_name'];
				$device_type= implode(',',$data['device_type']);
				$card_type= implode(',',$data['card_type']);
				$application_scenarios= implode(',',$data['application_scenarios']);
				
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				
				$sql = "SELECT * FROM file_management";
			
				$has_where = FALSE;
				if(!empty($firmware_name)) {
					$sql = $this->append_where($sql, $has_where);
					$has_where = true;
					$sql .= "firmware_name like '%{$firmware_name}%'";	
				}
				if(!empty($device_type)) {
					$sql = $this->append_where($sql, $has_where);
					$has_where = true;
					$sql .= "device_type in(";
					$dtcount = count($data['device_type'])-1;
					foreach($data['device_type'] as $dtkey => $dtvalue)
					{
						if($dtkey == $dtcount){
							$sql .= "'{$dtvalue}')";
						}
						else
							$sql .= "'{$dtvalue}',";
					}
					
				}
				if(!empty($card_type)) {
					$sql = $this->append_where($sql, $has_where);
					$has_where = true;
					$sql .= "card_type in(";
					$ctcount = count($data['card_type'])-1;
					foreach($data['card_type'] as $ctkey => $ctvalue)
					{
						if($ctkey == $ctcount){
							$sql .= "'{$ctvalue}')";
						}
						else
							$sql .= "'{$ctvalue}',";
					}
					
				}
				if(!empty($application_scenarios)) {
					$sql = $this->append_where($sql, $has_where);
					$has_where = true;
					$sql .= "application_scenarios in(";
					$ascount = count($data['application_scenarios'])-1;
					foreach($data['application_scenarios'] as $askey => $asvalue)
					{
						if($askey == $ascount){
							$sql .= "'{$asvalue}')";
						}
						else
							$sql .= "'{$asvalue}',";
					}
				}
				
				$connection = \Yii::$app->db;
//$command = $connection->createCommand("SELECT device_type FROM file_management WHERE firmware_name='$firmware_name'");
//注：引用变量(eg:'$firmware_name')时，不安�?如果别人攻击你服务器,$firmware_name传入一个delete sql语句很有可能会把你的数据表删除掉
//$command = $connection->createCommand("SELECT * FROM file_management WHERE firmware_name=:firmware_name AND device_type=:device_type", [':firmware_name' => $firmware_name, ':device_type' => $device_type] );
//改成这种格式，createCommand会以bindValues绑定参数，在调用PDO操作时是会从_pendingParams读取相应的值以bindValue操作查询�?这个要看PDO.php的bindValue这个处理原理，应该会去做检测，有时间可以研究下)
//$post = $command->queryColumn();
				$command = $connection->createCommand($sql);
				$post = $command->queryAll();
				
				return ["result" => $post];
			}
			if($flag == "2")
			{
				
			}
		}
		
        return $this->render('file_management');
    }
	
	function append_where($sql, $has_where) {
		$sql .= $has_where?' AND ':' WHERE ';
		return $sql;
	}
	
	

    public function actionIndex() {
		
		$sql = "SELECT * FROM file_management where firmware_name='715'";
		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$post = $command->queryOne();
		$count = $connection->createCommand($sql)->query()->rowCount;
		
		/*$query = Login::find()->where(['user_name'=>'zhangsan']);
		$count = Login::find()->where(['user_name'=>'zhangsan'])->count();*/
		
		
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $count
        ]);
		
		var_dump($count);
		exit;
		
        /*$article_list = $query
			->offset($pagination->offset)
			->limit($pagination->limit)
			->all();*/
		
		$article_list = \Yii::$app->db->createCommand($sql)
			->bindValue(':offset', $pagination->offset)
			->bindValue(':limit', $pagination->limit)
			->all();	
			
		
        return $this->render('index', ['article_list' => $article_list,'pagination' => $pagination]);
		
    }


}

?>