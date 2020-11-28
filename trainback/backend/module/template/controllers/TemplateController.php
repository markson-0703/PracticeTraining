<?php
namespace backend\module\template\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Users;
use common\models\Templates;

class TemplateController extends Controller{

	public function actionIndex(){
		return "模板上传";
	}

	public function actionUploadtemplate(){
		//学生模板上传
	  $request = \Yii::$app->request;
    $username=$request->post('username');//模板上传前，先获取需要的变量
    $kind=$request->post('kind');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $dir=$menu.'/template/';
    $publishTime=date('YmdHis');//提交时间
    $filename = $_FILES['file']['name'];//文件名
    //获取后缀名
    // $suffix=explode('.',$filename);//$suffix[0]是前面的文件名,$suffix[1]是文件的后缀
    $size = $_FILES['file']['size'];//文件大小
    $type = $_FILES['file']['type'];//文件类型
    $tmp_name = $_FILES['file']['tmp_name'];//文件临时存放的位置
    //构建新的路径
    $path=$menu.'/template/'.$filename;
    //移动到新文件夹下
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
    //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('templates')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['status'=>1])
          ->one();
    if($query==null){
    	//插入
    	$id=(new Query())
           ->select('*')
           ->from('templates')
           ->max('temId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('templates',
        	[
        	'temId'=>$currId,
        	'publisher'=>$username,
        	'type'=>1,
        	'kind'=>$kind,//代表教学设计模板
        	'filename'=>$filename,
        	'path'=>$path,
        	'publishTime'=>$publishTime,
        	'status'=>1
        	])->execute();
         if($result){
            return array('data'=>$result,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        }
     }else{
     	return array('data'=>[],"msg"=>"不要重复上传");
     }
	}


  public function actionUploadtemplate1(){
    //教师模板上传
    $request = \Yii::$app->request;
    $username=$request->post('username');//模板上传前，先获取需要的变量
    $kind=$request->post('kind');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $dir=$menu.'/template/';
    $publishTime=date('YmdHis');//提交时间
    $filename = $_FILES['file']['name'];//文件名
    $size = $_FILES['file']['size'];//文件大小
    $type = $_FILES['file']['type'];//文件类型
    $tmp_name = $_FILES['file']['tmp_name'];//文件临时存放的位置
     //构建新的路径
    $path=$menu.'/template/'.$filename;
    //移动到新文件夹下
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
     //将数据存入到数据库
    $template=(new Query())
          ->select('*')
          ->from('templates')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['status'=>2])
          ->one();
    if($template==null){
      //插入数据
      $id=(new Query())
           ->select('*')
           ->from('templates')
           ->max('temId');
      $currId=$id+1;
      $result=\Yii::$app->db->createCommand()->insert('templates',
          [
          'temId'=>$currId,
          'publisher'=>$username,
          'type'=>1,
          'kind'=>$kind,//代表教学设计模板
          'filename'=>$filename,
          'path'=>$path,
          'publishTime'=>$publishTime,
          'status'=>2
          ])->execute();
     if($result){
            return array('data'=>$path,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        }
    }else{
      return array('data'=>[],"msg"=>"请勿重复上传");
    }
  }

	public function actionGettemplate(){
    //获取所有学生用模板
		    $request = \Yii::$app->request;
        $username=$request->post('username');
        $data=(new Query())
             ->select('*')
             ->from('templates')
             ->andWhere(['publisher'=>$username])
             ->andWhere(['type'=>1])
             ->andWhere(['status'=>1])
             ->all();
        if($data){
        	return array("data"=>$data,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

  public function actionTeatemplate(){
    //获取所有老师用模板
     $request = \Yii::$app->request;
     $username=$request->post('username');
     $data=(new Query())
             ->select('*')
             ->from('templates')
             ->andWhere(['publisher'=>$username])
             ->andWhere(['type'=>1])
             ->andWhere(['status'=>2])
             ->all();
     if($data){
          return array("data"=>$data,"msg"=>"success");
      }else{
          return array("data"=>[],"msg"=>"failure");
      }
  }

	public function actionDeltemplate(){
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $delete=\Yii::$app->db->createCommand()->update('templates',
        	[
        	'status'=>0
        	],
        	'temId=:id',[':id'=>$id])->execute();
        if($delete){
			return array("data"=>$delete,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

}