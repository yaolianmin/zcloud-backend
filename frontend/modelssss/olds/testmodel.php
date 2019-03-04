<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class testmodel extends Model
{

    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'img', 'maxSize' => 1024*1024*20],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('/var/www/uploadfile/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}


?>