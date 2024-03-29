<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-18
 * Time: 下午10:20
 */

namespace common\components;

use yii;
use yii\base\Object;
use yii\web\UploadedFile;

class ImageUpload extends Object
{
    public $imageMaxSize;// = 1024 * 1024 * 2;
    /**
     * [UploadPhoto description]
     * @param [type]  $model      [实例化模型]
     * @param [type]  $path       [图片存储路径]
     * @param [type]  $originName [图片源名称]
     * @param boolean $isthumb    [是否要缩略图]
     */
    public function UploadPhoto($model,$path,$originName,$isthumb=false){
        $root = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
        //返回一个实例化对象
        $files = UploadedFile::getInstance($model,$originName);
        $model->$originName = $files;
        if(!$model->validate()){
            die("验证不通过");
        }
        $folder = date('Ymd')."/";
        $pre = rand(999,9999).time();
        if($files && ($files->type == "image/jpeg" || $files->type == "image/pjpeg" || $files->type == "image/png" || $files->type == "image/x-png" || $files->type == "image/gif"))
        {
            $newName = $pre.'.'.$files->getExtension();
        }else{
            die($files->type);
        }
        if($files->size > $this->imageMaxSize){
            die("上传的文件太大");
        }
        if(!is_dir($root.$folder))
        {
            if(!mkdir($root.$folder, 0777, true)){
                die('创建目录失败...');
            }else{
                //	chmod($root.$folder,0777);
            }
        }
        //echo $root.$folder.$newName;exit;
        if($files->saveAs($root.$folder.$newName))
        {
            if($isthumb){//是否缩略图
                //$this->thumbphoto($files,$path.$folder.$newName,$path.$folder.'thumb'.$newName);
                return $path.$folder.$newName.'#'.$path.$folder.'thumb'.$newName;
            }else{
                return $path.$folder.$newName;
            }

        }
    }
}