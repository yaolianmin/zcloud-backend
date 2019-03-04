<?php
namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    /*public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }*/
	public function rules()
    {
        return [
            [['file'],'file',
			/*'extensions'=>['png','txt','img'],
			'wrongExtension'=>\Yii::t('yii','You can only upload').' '.'{extensions}'.' '.\Yii::t('yii','file type!'),
			'maxSize'=>1024*1024*20,  'tooBig'=> \Yii::t('yii','File upload too large!'),
			'message'=>'上传失败！'*/
			//加验证规则后，只能上传普通文件，img格式的不支持，目前没有找到解决办法
             ],
        ];
    }
}

?>