<?php
namespace backend\module\resource\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\TeacherInfo;
use common\models\TeacherFiles;
use common\models\ArrangeInfo;
use common\models\ProbationFiles;
use common\models\ProbationVideos;

class DocumentionController extends Controller{
	public function actionIndex(){
		return "文档管理";
	}

	public function actionBasicinfo(){
		//获取自己指导学生的基本信息
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('teacher_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	$job_num=$query['job_num'];
        	$member=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['job_num'=>$job_num])
        	       ->andWhere(['ischecked'=>1])
        	       ->andWhere(['status'=>1])
        	       ->all();
          $data=[];//定义一个空数组
       for($i=0;$i<count($member);$i++){
       	$uname=$member[$i]['username'];//学生的用户名
       	//文档的数据
       	$document=(new Query())
       	         ->select('*')
       	         ->from('probation_files')
       	         ->andWhere(['username'=>$uname])
       	         ->andWhere(['type'=>1])
       	         ->andWhere(['status'=>1] OR ['status'=>2])
       	         ->all();
       $filenum=count($document);
       //视频的数据
       $video=(new Query())
             ->select('*')
             ->from('probation_videos')
             ->andWhere(['username'=>$uname])
             ->andWhere(['type'=>1])
             ->andWhere(['status'=>1])
             ->all();
       $videonum=count($video);
       //写入数组中
       $data[$i]['username']=$member[$i]['username'];
       $data[$i]['sno']=$member[$i]['sno'];
       $data[$i]['sName']=$member[$i]['sName'];
       $data[$i]['file']=$filenum;
       $data[$i]['video']=$videonum;
       }
       return array("data"=>$data,"msg"=>"success");
     }else{
        	return false;
        }
	}

	public function actionFileinfo(){
		//获取文档详情
		$request = \Yii::$app->request;
        $username=$request->post('username');//学生的用户名
        $query=(new Query())
              ->select('*')
              ->from('probation_files')
              ->andWhere(['username'=>$username])
              ->andWhere(['type'=>1])
              ->andWhere(['status'=>1] OR ['status'=>2])
              ->all();
        if($query){
        	$path=[];
        	for($j=0;$j<count($query);$j++){
        		$url=explode(':',$query[$j]['path']);
        		$dir=explode('/',$url[1]);
        		$arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        		$final=implode('/',$arr);
        		$path[$j]['url']=$final;
        	}
        	return array("data"=>[$query,$path],"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionVideoinfo(){
		//获取视频详情
		$request = \Yii::$app->request;
        $username=$request->post('username');//学生的用户名
        $query=(new Query())
              ->select('*')
              ->from('probation_videos')
              ->andWhere(['username'=>$username])
              ->andWhere(['type'=>1])
              ->andWhere(['status'=>1])
              ->all();
         if($query){
        	$path=[];
        	for($j=0;$j<count($query);$j++){
        		$url=explode(':',$query[$j]['path']);
        		$dir=explode('/',$url[1]);
        		$arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        		$final=implode('/',$arr);
        		$path[$j]['url']=$final;
        	}
        	return array("data"=>[$query,$path],"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionTheirurl(){
		//获取文档地址
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $url=(new Query())
            ->select('*')
            ->from('probation_files')
            ->andWhere(['fId'=>$id])
            ->one();
        if($url){
         $path=explode(':',$url['path']);
         $dir=explode('/',$path[1]);
         $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
         $final =implode('/',$arr);
         return array("data"=>$final,"msg"=>"success");
         }else{
         	return false;
         }
	}

	public function actionThisurl(){
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $url=(new Query())
            ->select('*')
            ->from('probation_videos')
            ->andWhere(['vId'=>$id])
            ->andWhere(['status'=>1])
            ->one();
         if($url){
         $path=explode(':',$url['path']);
         $dir=explode('/',$path[1]);
         $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
         $final =implode('/',$arr);
         return array("data"=>$final,"msg"=>"success");
         }else{
         	return false;
         }
	}

	public function actionTeafileurl(){
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $url=(new Query())
            ->select('*')
            ->from('teacher_files')
            ->andWhere(['rId'=>$id])
            ->one();
            if($url){
         $path=explode(':',$url['path']);
         $dir=explode('/',$path[1]);
         $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
         $final =implode('/',$arr);
         return array("data"=>$final,"msg"=>"success");
         }else{
         	return false;
         }
	}

}