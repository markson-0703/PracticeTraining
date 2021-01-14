<?php
namespace backend\module\evaluation\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\ArrangeInfo;
use common\models\TeacherInfo;
use common\models\ProbationAssess;
use common\models\ProbationMark;

class MarkController extends Controller{
	public function actionIndex(){
		return "评分部分";
	}

	public function actionStudentmark(){
		//校内导师获取见习生的基本情况
	   $request = \Yii::$app->request;
       $username=$request->post('username');
	   //去查教师的工号
	   $query=(new Query())
	         ->select('*')
	         ->from('teacher_info')
	         ->andWhere(['username'=>$username])
	         ->andWhere(['status'=>1])
             ->one();
       if($query){
       	$jno=$query['job_num'];
       	$member=(new Query())
       	       ->select('*')
       	       ->from('arrange_info')
       	       ->andWhere(['job_num'=>$jno])
       	       ->andWhere(['ischecked'=>1])
    	       ->andWhere(['type'=>1])
    	       ->andWhere(['status'=>1])
    	       ->all();
    	//写入信息
    	$data=[];
    	for($i=0;$i<count($member);$i++){
    		$data[$i]['uname']=$member[$i]['username'];//学生的用户名
        	$data[$i]['sno']=$member[$i]['sno'];//学号
        	$data[$i]['sName']=$member[$i]['sName'];//姓名
        	$data[$i]['tutorName']=$member[$i]['tutor_name'];//校外教师
        	$data[$i]['school']=$member[$i]['school_name'];//校外教师
        	$data[$i]['grade']=$member[$i]['grade'];//校外教师
           //获取分数
           	$mark=(new Query())
        	       ->select('*')
        	       ->from('probation_assess')
        	       ->andWhere(['username'=>$data[$i]['uname']])
        	       ->one();
        	$data[$i]['mark']=$mark['mark'];//学生的见习成绩
    	}
    	return array("data"=>$data,"msg"=>"success"); 
       }else{
       	 return false;
       }
	}

	public function actionSubmitgrade(){
	   $request = \Yii::$app->request;
       $username=$request->post('username');
       $total=$request->post('total');
       $mark1=$request->post('mark1');
       $mark2=$request->post('mark2');
       $mark3=$request->post('mark3');
       $mark4=$request->post('mark4');
       $mark5=$request->post('mark5');
       $mark6=$request->post('mark6');
       $query=(new Query())
             ->select('*')
             ->from('probation_assess')
             ->andWhere(['username'=>$username])
             ->one();
       $mark=$query['mark'];
        if($mark==null){
        	//插入分数
        	$update=\Yii::$app->db->createCommand()->update('probation_assess',
        		[
        		'mark'=>$total
        		],'username=:username',[':username'=>$username])->execute();
        if($update){
        $query1=(new Query())
   	      ->select('*')
   	      ->from('probation_mark')
   	      ->andWhere(['uname'=>$username])
   	      ->andWhere(['status'=>1])
   	      ->one();
   	      if($query1==null){
   	      	$currentId=(new Query())
    			  ->select('*')
    			  ->from('probation_mark')
                  ->max('mId');
           $id=$currentId+1;
           $detail=\Yii::$app->db->createCommand()->insert('probation_mark',
        		[
        		'mId'=>$id,
        		'uname'=>$username,
        		'mark1'=>$mark1,
        		'mark2'=>$mark2,
        		'mark3'=>$mark3,
        		'mark4'=>$mark4,
        		'mark5'=>$mark5,
        		'mark6'=>$mark6
        		])->execute();
           if($detail){
           	return array("data"=>$detail,"msg"=>"success");
           }else{
             return false;
           }
   	      }else{
   	      	return array("data"=>[],"msg"=>"已存在");
   	      }	
         }else{
         	return array("data"=>[],"msg"=>"更新分数失败");
         }
        }else{
        	return array("data"=>[],"msg"=>"重复打分");
        }
	}

	public function actionMarkdetail(){
	   $request = \Yii::$app->request;
       $username=$request->post('uname');
       //先总后细节
       $data=[];
       $query=(new Query())
             ->select('*')
             ->from('probation_assess')
             ->andWhere(['username'=>$username])
             ->one();
        $query1=(new Query())
             ->select('*')
             ->from('probation_mark')
             ->andWhere(['uname'=>$username])
             ->andWhere(['status'=>1])
             ->one();
        $data['totalmark']=$query['mark'];
        $data['mark1']=$query1['mark1'];
        $data['mark2']=$query1['mark2'];
        $data['mark3']=$query1['mark3'];
        $data['mark4']=$query1['mark4'];
        $data['mark5']=$query1['mark5'];
        $data['mark6']=$query1['mark6'];
        if($query&$query1){
        	return array("data"=>$data,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionMarkdata(){
	  	$query=(new Query())
   	      ->select('*')
   	      ->from('probation_assess')
   	      ->andWhere(['<>','status',0])
   	      ->all();
   	  if($query){
   		return array("data"=>$query,"msg"=>"success"); 
   	  }else{
   		return array("data"=>[],"msg"=>"failure"); 
     }
	}

}