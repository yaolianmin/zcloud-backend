<?php
namespace backend\models\cfg_management\product_management;

use Yii;
use yii\base\Model;
use yii\db\Command;

use backend\models\common\Common_model;
/**
 * 机种管理模型
 */
class ProductManagement extends Model
{
	
    /**
     * Function: 新建一张表,表名为机种名,表存储是该机种默认模板信息
     * @param string $table_name 与机种同名的数据表名
     * @param string $path 保存机种上传文件的路径
     * @return string
     */
    public function create_product_default_template_table($table_name,$path)
    {
        //获取模板描述文件的内容
        $_view_data = self::getDefaultTemplateCon($path);
        $_count = count($_view_data);
        /**
         * 新建一张表,表名为设备名
         */
        $_table_name = $table_name;
        $_create_sql = "DROP TABLE IF EXISTS `".$_table_name."`;
            CREATE TABLE `".$_table_name."` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";
        Yii::$app->db->createCommand($_create_sql)->execute();

        for($index=1;$index<$_count;$index++)
        {   
            /**
             * 取出对应字段
             */
            $_column_name = explode(',',$_view_data[$index]);
            $column_name = $_column_name[0];
            /**
             * 取对应字段的默认值
             */
            $_step1 = explode('value:',$_view_data[$index]);
            $_step2 = explode(']',$_step1[1]);
            $_column_value = explode('[',$_step2[0]);
            $column_value = $_column_value[1];
            /**
             * 给表添加字段并赋默认值
             */
            self::addTableColumn($_table_name,$column_name,$column_value);
        }
        /**
         * 添加一条新数据
         */
        $_insert_sql = "INSERT INTO `".$_table_name."` (id) VALUES (1);";
        Yii::$app->db->createCommand($_insert_sql)->execute();
    }

    /**
     * Function: 更新数据表中的信息
     * @param  string $table_name 要更新的表名
     * @param  string $column_name 保存对应机种默认模板描述文件的路径
     * @return [type]             [description]
     */
    public function updateProductDefaultTemplateTable($table_name,$path)
    {
        //获取模板描述文件的内容
        $_view_data = self::getDefaultTemplateCon($path);
        $_count = count($_view_data);

        //判断对应机种的表是否存在
        $_table_exist = self::tableExist($table_name);
        if ($_table_exist) {
            //获取表中所有字段和字段总数
            $results = self::getTableColumns($table_name);
            
            $same_columnArray = array();//保存相同字段
            for($index=1,$same_count=0;$index<$_count;$index++)
            { 
                /**
                 * 获取对应字段
                 */
                $_column_name = explode(',',$_view_data[$index]);
                $column_name = $_column_name[0];
                /**
                 * 获取对应字段的默认值
                 */
                $_step1 = explode('value:',$_view_data[$index]);
                $_step2 = explode(']',$_step1[1]);
                $_column_value = explode('[',$_step2[0]);
                $column_value = $_column_value[1];

                //判断新模板与之前的模板有多少字段是相同的
                $column_exit = self::tableColumnExist($table_name,$column_name);
                if ($column_exit) {
                    $same_count++;
                    $same_columnArray[$same_count] = $column_name;
                    //若字段相同再判断该字段的默认值是否改变
                    $column_default_change = self::tableColumnDefaultChange($table_name,$column_name,$column_value);
                   
                    //如默认值改变,则重新设定默认值
                    if (!$column_default_change) {
                        self::modifyTableColumnDefault($table_name,$column_name,$column_value);
                    }
                } else {
                    /**
                     * 给表添加新字段并设置默认值
                     */
                    self::addTableColumn($table_name,$column_name,$column_value);
                }
            }
            //相同的字段比原来的少,需要删除减少的字段
            if (($results["column_count"] - 1) > $same_count) {
                    for($i=1;$i<$results["column_count"];$i++)
                    {
                        //echo in_array($results["all_column"][$i],$same_columnArray)?$results["all_column"][$i]."在数组中\n":$results["all_column"][$i]."不在数组中\n";
                        //找出不同字段
                        if (!in_array($results["all_column"][$i],$same_columnArray)) {
                            //echo  $results["all_column"][$i]." 不在数组中,删除对应字段\n";
                            //删除减少的字段
                            self::dropTableColumn($table_name,$results["all_column"][$i]);
                        }
                    }
            }

        }else {
            self::create_product_default_template_table($table_name,$path);
        }

    }

    /**
     * Function: 保存对应机种上传的文件
     * @param  string $post_info 通过post请求发送的数据
     * @return string  返回保存机种上传文件的路径
     */
    public function save_model_uploadfile($post_info)
    {
        $_post = $post_info;
        $template_Folder_Name = $_post['model_name']."_template_file";
        system("cd /var/www/ZOAM/uploadfile/; rm ".$template_Folder_Name." -rf;mkdir ".$template_Folder_Name.";");
        /**
         * 将上传的文件流保存到相应的目录下
         */
        move_uploaded_file($_FILES["uploadFileName"]["tmp_name"],"/var/www/ZOAM/uploadfile/".$template_Folder_Name."/" .$_FILES["uploadFileName"]["name"]);
        /**
         * 解压上传的模板压缩文件
         */
        system("cd /var/www/ZOAM/uploadfile/".$_post['model_name']."_template_file/;tar -xvf ".$_FILES["uploadFileName"]["name"]);
        system("cd /var/www/ZOAM/uploadfile/;chmod 777 -R ./");

        $path = "/var/www/ZOAM/uploadfile/".$_post['model_name']."_template_file/";
        //为对应机种创建数据表
        //self::create_product_default_template_table($_post['model_name'],$path);
        return $path;
    }

    /**
     * Function: 删除对应机种上传的文件、保存文件的目录、保存模板数据的数据表
     * @param  string $model_name  对应机种的model_name
     * @return [type]             [description]
     */
    public function delete_model_uploadfile($model_name)
    {   
        $_table_name = $model_name;
        $_delete_sql ="DROP TABLE IF EXISTS `".$_table_name."`;";
        /**
         * 删除存储对应机种默认模板数据的表
         */
        Yii::$app->db->createCommand($_delete_sql)->execute();
        /**
         * 删除存放机种上传文件的目录
         */
        system("cd /var/www/ZOAM/uploadfile/;rm ".$model_name."_template_file -rf");
    }

    /**
     * Function: 获取模板描述文件的内容
     * @param  string $path 保存模板描述文件的路径
     * @return [type]       [description]
     */
    protected function getDefaultTemplateCon($path)
    {
        $con = self::parserDescTxt($path);
        $filename = $path.$con;
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize ($filename));
        fclose($handle);
        /**
         * 压缩文件中多余的符号
         */
        $_string = str_replace(array("\r\n", "\r", "\n","'"," "), "", $contents);
        $view_data = explode('key:',$_string);
        return $view_data;
    }

    /**
     * Function:获取模板描述文件[*.js]的名称.
     * @param  string  保存desc.txt文件的路径
     * @return string  模板描述文件的名称
     */
    protected function parserDescTxt($path)
    {
        $filename = $path."desc.txt";
        $con = file_get_contents($filename);
        $contents = explode(',',$con);

        return $contents[1];
    }

    /**
     * 获取表中所有字段和字段总数.
     * @param  string $table_name 要查询的表名
     * @return array     保存查询结果的数组
     */
    protected function getTableColumns($table_name)
    {
        $model = new Common_model();
        $model->find_model($table_name);
        
        $table = $model->tableName();
        $tableSchema = Yii::$app->db->schema->getTableSchema($table);

        $_all_column_results = \yii\helpers\ArrayHelper::getColumn($tableSchema->columns, 'name', false);
        $_table_column_count = count($_all_column_results);
        
        return  array('all_column' => $_all_column_results, 'column_count'=> $_table_column_count);
    }
    /**
     * Function: 查询表是否存在.
     * @param  string $table_name 要查询的表名
     * @return string  true:存在 false:不存在
     */
    protected function tableExist($table_name)
    {
        //判断对应名称的表是否存在
        $sql_table_exist = "select count(*) from information_schema.tables WHERE table_name='".$table_name."';";
        $table_exist = Yii::$app->db->createCommand($sql_table_exist)->queryAll();
        $table_exist_count = (int)$table_exist[0]["count(*)"];

        return $table_exist_count ?true :false;
    }

    /**
     * Function: 判断表中是否存在该字段.
     * @param  string $table_name 要查询的表名
     * @param  string $column_name 要查询的字段名
     * @return string  true:存在 false:不存在
     */
    protected function tableColumnExist($table_name,$column_name)
    {
        //判断新模板与之前的模板有多少字段是相同的
        $sql_column_exist = "select count(*) from information_schema.columns WHERE table_name='".$table_name."'and column_name='".$column_name."';";
        $column_exist = Yii::$app->db->createCommand($sql_column_exist)->queryAll();
        $column_exist_count = (int)$column_exist[0]["count(*)"];

        return $column_exist_count ?true :false;
    }

    /**
     * Function: 判断表中字段默认值是否改变.
     * @param  string $table_name 要查询的表名
     * @param  string $column_name 要查询的字段名
     * @param  string $default_value 要查询的字段的默认值
     * @return string  true:改变 false:未改变
     */
    protected function tableColumnDefaultChange($table_name,$column_name,$default_value)
    {
        //判断字段默认值是否改变
        $sql_column_default = "select count(*) from information_schema.columns WHERE table_name='".$table_name."'and column_name='".$column_name."'and column_default='".$default_value."';";
        $column_default_change = Yii::$app->db->createCommand($sql_column_default)->queryAll();
        $column_default_change_count = (int)$column_default_change[0]["count(*)"];

        return $column_default_change_count ?true :false;
    }

    /**
     * Function: 修改数据表中字段的默认值.
     * @param  string $table_name 要修改的表名
     * @param  string $column_name 要修改的字段名
     * @param  string $default_value 要修改的字段的默认值
     * @return [type]                [description]
     */
    protected function modifyTableColumnDefault($table_name,$column_name,$default_value)
    {
        //先删除原来的默认值再设置
        $sql_change_default = "alter table `".$_table_name."` alter column `".$column_name."` drop default;alter table `".$_table_name."` alter column `".$column_name."` set default '".$column_value."';";
        Yii::$app->db->createCommand($sql_change_default)->execute();
    }

    /**
     * Function: 在数据表中添加新字段并设置默认值
     * @param string $table_name 要设置的表名
     * @param string $column_name 要设置的字段名
     * @param string $default_value 要设置的字段的默认值
     */
    protected function addTableColumn($table_name,$column_name,$default_value)
    {
        /**
         * 给表添加新字段并设置默认值
         */
        $_add_new_column_sql = "alter table `".$table_name."` ADD COLUMN `".$column_name."`varchar(256) default '".$default_value."';";
        Yii::$app->db->createCommand($_add_new_column_sql)->execute();
    }

    /**
     * Function: 删除表中某个字段
     * @param  string $table_name 要删除的表名
     * @param  string $column_name 要删除的字段名
     * @return [type]              [description]
     */
    protected function dropTableColumn($table_name,$column_name)
    {
        $_drop_old_column_sql = "alter table `".$table_name."` DROP COLUMN `".$column_name."`;";
        Yii::$app->db->createCommand($_drop_old_column_sql)->execute();
    }


}