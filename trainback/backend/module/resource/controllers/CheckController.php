<?php
namespace backend\module\resource\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\TutorInfo;
use common\models\ArrangeInfo;
use common\models\StudentFiles;
use TCPDF;
use common\config\tcpdf_include;

class CheckController extends Controller{
	public function actionIndex(){
		return "导师审阅部分";
	}

	public function actionBaseinfo(){
		//获取自己所指导学生的基本信息
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $kind=$request->post('kind');
        $query=(new Query())
              ->select('*')
              ->from('tutor_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	//查找指导的学生
          $jno=$query['job_num'];
          $member=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['jno'=>$jno])
        	       ->andWhere(['ischecked'=>1])
                   ->andWhere(['type'=>2])
        	       ->andWhere(['status'=>1])
        	       ->all();
          $data=[];
         for($i=0;$i<count($member);$i++){
         	$uname=$member[$i]['username'];//学生的用户名
         	//实习数据
         	$design=(new Query())
         	       ->select('*')
         	       ->from('student_files')
         	       ->andWhere(['username'=>$uname])
         	       ->andWhere(['type'=>2])
         	       ->andWhere(['kind'=>$kind])
         	       ->andWhere(['status'=>1])
         	       ->all();
         	$designnum=count($design);
         	$review=1;//默认审阅状态为1
         	if($designnum==0){
         		$review=2;//表示学生还未提交任何文件
         	}
            for($j=0;$j<$designnum;$j++){
        	    	if($design[$j]['suggestion']==null){
        	    		$review=0;//表示仍有未审阅的教学设计
        	    	}
        	    }
        	//写入数组并返回
                $data[$i]['username']=$uname;
        	    $data[$i]['sno']=$member[$i]['sno'];
        	    $data[$i]['sName']=$member[$i]['sName'];
        	    $data[$i]['design']=$designnum;
        	    $data[$i]['status']=$review;
         }
         return array("data"=>$data,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionDesigninfo(){
		//获取教学设计详情
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $kind=$request->post('kind');
        $query=(new Query())
              ->select('*')
              ->from('student_files')
              ->andWhere(['username'=>$username])
              ->andWhere(['type'=>2])
              ->andWhere(['kind'=>$kind])
              ->andWhere(['status'=>1])
              ->all();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionGetlink(){
		//获取文件的路径
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $url=(new Query())
            ->select('*')
            ->from('student_files')
            ->andWhere(['rId'=>$id])
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

	public function actionReviewcontent(){
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $query=(new Query())
              ->select('*')
              ->from('student_files')
              ->andWhere(['rId'=>$id])
              ->one();
        $content=$query['suggestion'];
        if($content){
        	return array("data"=>$content,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionSubmitcontent(){
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $content=$request->post('content');
        $suggestion= \Yii::$app->db->createCommand()->update('student_files',
        	[
        	'suggestion'=>$content
        	],'rId=:id',[':id'=>$id])->execute();
        if($suggestion){
        	return array("data"=>[],"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

}