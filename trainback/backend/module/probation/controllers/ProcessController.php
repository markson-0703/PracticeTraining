<?php
namespace backend\module\probation\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Users;
use common\models\StudentInfo;
use common\models\TeacherInfo;
use common\models\TutorInfo;
use common\models\ArrangeInfo;
use common\models\SiteArrange;
use common\models\ActivityInfo;
use common\models\ActivityDetails;

class ProcessController extends Controller{
	 public function actionIndex()
	{
		return "见习进程与记录";
	}

	public function actionSubmitoneprocess(){
		//提交当前见习进程
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$sno=$request ->post('sno');
		$sName=$request ->post('sName');
		$contentId=$request ->post('contentId');
		$startTime=$request ->post('startTime');
		$endTime=$request ->post('endTime');
		$type=1;//见习

		$query=(new Query())
		      ->select('*')
		      ->from('activity_details')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['contentId'=>$contentId])
		      ->andWhere(['type'=>1])
		      ->one();
		if($query==null){
			//如果表中还未有该条信息
			$Id=(new Query())
		      ->select('*')
		      ->from('activity_details')
		      ->max('activityId');
		    $currentId=$Id+1;

		    $content=(new Query())
		            ->select('content')
		            ->from('activity_info')
		            ->andWhere(['contentId'=>$contentId]);

		    $activity=\Yii::$app->db->createCommand()->insert('activity_details',
		    	[
		    	'activityId'=>$currentId,
		    	'type'=>$type,
		    	'username'=>$username,
		    	'sno'=>$sno,
		    	'sName'=>$sName,
		    	'contentId'=>$contentId,
		    	'content'=>$content,
		    	'startTime'=>$startTime,
		    	'endTime'=>$endTime,
		    	'status'=>1
		    	])->execute();
            if($activity){
            	$query1=(new Query())
			       ->select('*')
			       ->from('activity_details')
			       ->andWhere(['username'=>$username])
			       ->andWhere(['contentId'=>$contentId])
			       ->andWhere(['type'=>1])
			       ->one();
			 if($contentId==1){
			       	$update=\Yii::$app->db->createCommand()->update('arrange_info',
   		        [
			    'startTime'=>$startTime,
			    'endTime'=>$endTime
			],'username=:username',[':username'=>$username])
			       	->execute();
			       }
			  return array("data"=>$query1,"msg"=>"success");
            }else{
            	return array("data"=>[],"msg"=>"failure");
            }
		}
		else{
			return array("data"=>$query,"msg"=>"信息已存在");
		}
	}

	public function actionEdittime(){
		//调整活动日期
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$contentId=$request ->post('contentId');
		$start=$request ->post('start');
		$end=$request ->post('end');
        //先找到需要更改的数据
        $query=(new Query())
              ->select('*')
              ->from('activity_details')
              ->andWhere(['username'=>$username])
              ->andWhere(['contentId'=>$contentId])
              ->one();
        if($query){
        	$activityId=$query['activityId'];
        	$updateTime=\Yii::$app->db->createCommand()->update('activity_details',
			[
			'startTime'=>$start,
			'endTime'=>$end
			],
			'activityId=:id',[':id'=>$activityId])
			->execute();
		    if($updateTime){
		    	return array("data"=>$updateTime,"msg"=>"success");
		    }
		    else{
		    	return array("data"=>[],"msg"=>"failure");
		    }
        }
        else{
        	return false;
        }	
	}
}