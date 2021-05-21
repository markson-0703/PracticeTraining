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
use common\models\TeacherFiles;
use common\models\StudentFiles;
use common\models\TutorFiles;

class MouldController extends Controller{
	public function actionIndex(){
		return "模板数据";
	}

	public function actionGetteatemplate(){
		//获取校内教师用的模板数据
		      $request = \Yii::$app->request;
          $type=$request->post('type');
          $status=$request->post('status');
          $query=(new Query())
                ->select('*')
                ->from('templates')
                ->andWhere(['type'=>$type])
                ->andWhere(['status'=>$status])
                ->all();
          if($query){
          	return array("data"=>$query,"msg"=>"success");
          }else{
          	return array("data"=>[],"msg"=>"failure");
          }
	}

  public function actionListentemplate(){
    //获取实习听课记录
          $request = \Yii::$app->request;
          $type=$request->post('type');
          $status=$request->post('status');
          $kind=$request->post('kind');
          $query=(new Query())
                ->select('*')
                ->from('templates')
                ->andWhere(['type'=>$type])
                ->andWhere(['status'=>$status])
                ->andWhere(['kind'=>$kind])
                ->all();
         if($query){
            return array("data"=>$query,"msg"=>"success");
          }else{
            return array("data"=>[],"msg"=>"failure");
          }
  }

  public function actionSometemplate(){
    //获取班主任实习部分的所有记录
          $request = \Yii::$app->request;
          $type=$request->post('type');
          $status=$request->post('status');
          $query=(new Query())
                ->select('*')
                ->from('templates')
                ->andWhere(['type'=>$type])
                ->andWhere(['status'=>$status])
                ->andWhere(['>=','kind', 3])
                ->andWhere(['<=','kind', 8])
                ->all();
        if($query){
            return array("data"=>$query,"msg"=>"success");
          }else{
            return array("data"=>[],"msg"=>"failure");
          }
  }

  public function actionGettuttemplate(){
    //获取校内教师用的模板数据
    $request = \Yii::$app->request;
    $type=$request->post('type');
    $status=$request->post('status');
     $query=(new Query())
                ->select('*')
                ->from('templates')
                ->andWhere(['type'=>$type])
                ->andWhere(['status'=>$status])
                ->all();
    if($query){
            return array("data"=>$query,"msg"=>"success");
     }else{
            return array("data"=>[],"msg"=>"failure");
      }
  }

	public function actionGetmyrecords(){
		//获取校内教师自己提交的记录
		    $request = \Yii::$app->request;
        $username=$request->post('username');
        $type=$request->post('type');
        $data=(new Query())
             ->select('*')
             ->from('teacher_files')
             ->andWhere(['type'=>$type])
             ->andWhere(['username'=>$username])
             ->all();
        if($data){
        	return array("data"=>$data,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

  public function actionGetmyplans(){
    //获取校内教师自己提交的实习计划
        $request = \Yii::$app->request;
        $username=$request->post('username');
        $type=$request->post('type');
        $status=$request->post('status');
        $data=(new Query())
             ->select('*')
             ->from('teacher_files')
             ->andWhere(['type'=>$type])
             ->andWhere(['username'=>$username])
             ->andWhere(['status'=>$status])
             ->all();
      if($data){
          return array("data"=>$data,"msg"=>"success");
        }else{
          return array("data"=>[],"msg"=>"failure");
      }
  }

    public function actionGetmylistens(){
    //获取学生实习听课记录
        $request = \Yii::$app->request;
        $username=$request->post('username');
        $type=$request->post('type');
        $kind=$request->post('kind');
        $status=$request->post('status');
        $data=(new Query())
             ->select('*')
             ->from('student_files')
             ->andWhere(['type'=>$type])
             ->andWhere(['username'=>$username])
             ->andWhere(['status'=>$status])
             ->andWhere(['kind'=>$kind])
             ->all();
      if($data){
          return array("data"=>$data,"msg"=>"success");
        }else{
          return array("data"=>[],"msg"=>"failure");
      }
  }

  public function actionGettutrecords(){
    //获取校内教师自己提交的记录
        $request = \Yii::$app->request;
        $username=$request->post('username');
        $type=$request->post('type');
        $data=(new Query())
             ->select('*')
             ->from('tutor_files')
             ->andWhere(['type'=>$type])
             ->andWhere(['username'=>$username])
             ->all();
        if($data){
          return array("data"=>$data,"msg"=>"success");
        }else{
          return array("data"=>[],"msg"=>"failure");
        }
  }

	public function actionDowntemplate(){
		$request = \Yii::$app->request;
		$type=$request->post('type');
		$status=$request->post('status');
    $kind=$request->post('kind');
        $query=(new Query())
           ->select('*')
           ->from('templates')
           ->andWhere(['type'=>$type])
           ->andWhere(['kind'=>$kind])
           ->andWhere(['status'=>$status])
           ->one();
        if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
  }

  public function actionUploadstudata(){
    //学生上传实习记录文件
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $kind=$request->post('kind');
    $type=$request->post('type');
    $status=$request->post('status');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $submitTime=date('YmdHis');
    $filename = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $path=$menu.'/practice/'.$filename;
    move_uploaded_file($tmp_name,$path);
    //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('student_files')
          ->andWhere(['username'=>$username])
          ->andWhere(['filename'=>$filename])
          ->andWhere(['type'=>$type])
          ->andWhere(['kind'=>$kind])
          ->one();
  if($query==null){
      //插入
     $id=(new Query())
           ->select('*')
           ->from('student_files')
           ->max('rId');
      $currId=$id+1;
      $result=\Yii::$app->db->createCommand()->insert('student_files',
      [
      'rId'=>$currId,
      'type'=>$type,
      'kind'=>$kind,
      'username'=>$username,
      'filename'=>$filename,
      'path'=>$path,
      'submitTime'=>$submitTime,
      'status'=>$status
      ])->execute();
     if($result){
      return array('data'=>$result,"msg"=>"success");
     }else{
      return array('data'=>[],"msg"=>"failure");
     }
    }else{
      return array('data'=>[],"msg"=>"重复提交了");
    }
  }

  public function actionUploadteaplan(){
    // 校内教师上传实习计划文件
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $kind=$request->post('kind');
    $type=$request->post('type');
    $status=$request->post('status');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $submitTime=date('YmdHis');
    $filename = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $path=$menu.'/practice/'.$filename;
    move_uploaded_file($tmp_name,$path);
    //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('teacher_files')
          ->andWhere(['username'=>$username])
          ->andWhere(['filename'=>$filename])
          ->andWhere(['type'=>$type])
          ->andWhere(['kind'=>$kind])
          ->one();
   if($query==null){
      //插入
     $id=(new Query())
           ->select('*')
           ->from('teacher_files')
           ->max('rId');
      $currId=$id+1;
      $result=\Yii::$app->db->createCommand()->insert('teacher_files',
      [
      'rId'=>$currId,
      'type'=>$type,
      'kind'=>$kind,
      'username'=>$username,
      'filename'=>$filename,
      'path'=>$path,
      'submitTime'=>$submitTime,
      'status'=>$status
      ])->execute();
     if($result){
      return array('data'=>$result,"msg"=>"success");
     }else{
      return array('data'=>[],"msg"=>"failure");
     }
    }else{
      return array('data'=>[],"msg"=>"重复提交了");
    }
  }

  public function actionUploadtearecord(){
  	//校内教师上传文件
  	$request = \Yii::$app->request;
  	$username=$request->post('username');
  	$kind=$request->post('kind');
  	$type=$request->post('type');
  	$menu=str_replace('\\','/',Yii::$app->basePath);
    $submitTime=date('YmdHis');
    $filename = $_FILES['file']['name'];//文件名
    $tmp_name = $_FILES['file']['tmp_name'];//文件被上传后在服务端储存的临时文件名
    $path=$menu.'/probation/'.$filename;
    //移动到新文件夹下
    move_uploaded_file($tmp_name,$path);
    //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('teacher_files')
          ->andWhere(['username'=>$username])
          ->andWhere(['filename'=>$filename])
          ->andWhere(['type'=>$type])
          ->andWhere(['kind'=>$kind])
          ->one();
    if($query==null){
    	//插入
     $id=(new Query())
           ->select('*')
           ->from('teacher_files')
           ->max('rId');
     $currId=$id+1;
     if($kind==6){
     	$result=\Yii::$app->db->createCommand()->insert('teacher_files',
     	[
     	'rId'=>$currId,
     	'type'=>$type,
     	'kind'=>$kind,
     	'username'=>$username,
     	'filename'=>$filename,
     	'path'=>$path,
     	'submitTime'=>$submitTime,
     	'status'=>2
     	])->execute();
     }else{
     	$result=\Yii::$app->db->createCommand()->insert('teacher_files',
     	[
     	'rId'=>$currId,
     	'type'=>$type,
     	'kind'=>$kind,
     	'username'=>$username,
     	'filename'=>$filename,
     	'path'=>$path,
     	'submitTime'=>$submitTime,
     	'status'=>1
     	])->execute();
     }
     if($result){
     	return array('data'=>$result,"msg"=>"success");
     }else{
     	return array('data'=>[],"msg"=>"failure");
     }
    }else{
    	return array('data'=>[],"msg"=>"重复提交了");
    }
  }

  public function actionUploadtutrecord(){
        //校外教师上传文件
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $kind=$request->post('kind');
    $type=$request->post('type');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $submitTime=date('YmdHis');
    $filename = $_FILES['file']['name'];//文件名
    $tmp_name = $_FILES['file']['tmp_name'];//文件被上传后在服务端储存的临时文件名
    $path=$menu.'/probation/'.$filename;
    //移动到新文件夹下
    move_uploaded_file($tmp_name,$path);
      //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('tutor_files')
          ->andWhere(['username'=>$username])
          ->andWhere(['filename'=>$filename])
          ->andWhere(['type'=>$type])
          ->andWhere(['kind'=>$kind])
          ->one();
   if($query==null){
    //插入
    $id=(new Query())
           ->select('*')
           ->from('tutor_files')
           ->max('dId');
     $currId=$id+1;
     //校外教师模板暂不确定，先简单插入
     $result=\Yii::$app->db->createCommand()->insert('tutor_files',
      [
      'dId'=>$currId,
      'type'=>$type,
      'kind'=>$kind,
      'username'=>$username,
      'filename'=>$filename,
      'path'=>$path,
      'submitTime'=>$submitTime,
      'status'=>1
      ])->execute();
     if($result){
      return array('data'=>$result,"msg"=>"success");
     }else{
      return array('data'=>[],"msg"=>"failure");
     }
   }else{
    return array('data'=>[],"msg"=>"请勿重复提交");
   }
  }

  public function actionGetonerecord(){
  	$request = \Yii::$app->request;
    $id=$request->post('rId');
    $query=(new Query())
          ->select('*')
          ->from('teacher_files')
          ->andWhere(['rId'=>$id])
          ->one();
    if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
  }

  public function actionGetonelisten(){
    //学生获取一条听课记录
    $request = \Yii::$app->request;
    $id=$request->post('rId');
    $query=(new Query())
          ->select('*')
          ->from('student_files')
          ->andWhere(['rId'=>$id])
          ->one();
    if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
  }

  public function actionOnetutrecord(){
    $request = \Yii::$app->request;
    $id=$request->post('dId');
    $query=(new Query())
          ->select('*')
          ->from('teacher_files')
          ->andWhere(['rId'=>$id])
          ->andWhere(['status'=>1])
          ->andWhere(['status'=>1])
          ->one();
   if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
  }

}