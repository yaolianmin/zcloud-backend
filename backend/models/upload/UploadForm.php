<?php
namespace backend\models\upload;

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
    public $uploadFileName;

    /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [['uploadFileName'], file],
        ];
    }
}