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
use common\models\SiteArrange;
use common\models\TutorArrange;
use common\models\ArrangeInfo;

class SelectController extends Controller{
	 public function actionIndex()
	{
		return "选择导师部分";
	}

	public function actionGetsitedata(){
		//根据前端传来的教师信息查询对应的见习地点
		$request = \Yii::$app->request;
		$tName=$request ->post('tName');
		$job_num=$request ->post('job_num');
		$query=(new Query)
		      ->select('*')
		      ->from('site_arrange')
		      ->andWhere(['tName'=>$tName])
		      ->andWhere(['job_num'=>$job_num])
		      ->andWhere(['typeId'=>1])
		      ->all();
		// $list= array();
		// for ($i=0;$i<count($query);$i++){
		// 	array_push($list,$query[$i]['site']);
		// }
		return array("data"=>$query,"msg"=>"success");
	}

	public function actionGettutdata(){
		//根据前端传来的见习点信息查询对应的见习学校导师
		$request = \Yii::$app->request;
		$site=$request ->post('site');
		$query=(new Query())
	          ->select('*')
	          ->from('tutor_info')
	          ->andWhere(['school_name'=>$site])
	          ->andWhere(['probation'=>1])
	          ->andWhere(['status'=>1])
	          ->all();
	    return array("data"=>$query,"msg"=>"success");
	}

	public function actionGettutor(){
		$request = \Yii::$app->request;
		$site=$request ->post('school');
		$query=(new Query())
	          ->select('*')
	          ->from('tutor_info')
	          ->andWhere(['school_name'=>$site])
	          ->andWhere(['status'=>1])
	          ->all();
	    return array("data"=>$query,"msg"=>"success");
	}

	public function actionGetonejno(){
		//获取学生所选校外导师的工号
		$request = \Yii::$app->request;
		$name=$request ->post('tName');
		$query=(new Query())
		      ->select('*')
		      ->from('tutor_info')
		      ->andWhere(['tName'=>$name])
		      ->andWhere(['status'=>1])
		      ->one();
		return array("data"=>$query,"msg"=>"success");
	}

	public function actionGetstuinfo(){
		//根据学生用户名获取用户的学号和姓名
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$query=(new Query())
		      ->select('*')
		      ->from('student_info')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['status'=>1])
		      ->one();
		return array("data"=>$query,"msg"=>"success");      
	}

	public function actionTutorarrange(){
		//将见习信息插入到导师安排表
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$sno=$request ->post('sno');
		$sName=$request ->post('sName');
		$tutor=$request ->post('tutor');//校外
		$jno=$request ->post('jno');//校外
		$tName=$request ->post('tName');//校内
		$job_num=$request ->post('job_num');//校内
		$site=$request ->post('site');
		$type=1;//表示该条属于见习分配信息
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->andWhere(['type'=>$type])
		      ->one();
		if($query==null){
		$Id=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->max('aId');
		$currentId=$Id+1;
		$arrange=\Yii::$app->db->createCommand()->insert('arrange_info',
			[
			'aId'=>$currentId,
			'username'=>$username,
			'sno'=>$sno,
			'sName'=>$sName,
			'type'=>$type,
			'job_num'=>$job_num,
			'tName'=>$tName,
			'tutor_name'=>$tutor,
			'jno'=>$jno,
			'school_name'=>$site
			])->execute();
		if($arrange){
			$query1=(new Query())
			       ->select('*')
			       ->from('arrange_info')
			       ->andWhere(['username'=>$username])
			       ->andWhere(['type'=>1])
			       ->one();
			return array("data"=>$query1,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	  }else{
	  	return array("data"=>$query,"msg"=>"信息已存在");
	  }
	}

	public function actionIschoose(){
		//判定学生用户是否已经选择导师
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['type'=>1])
		      ->one();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"学生还未申请导师");
		}
	}

	public function actionGetmember(){
		//获取小组成员信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['type'=>1])
		      ->one();
		if($query)
		{
			$site=$query['school_name'];
			$query1=(new Query())
			       ->select('*')
			       ->from('arrange_info')
			       ->andWhere(['school_name'=>$site])
			       ->andWhere(['ischecked'=>1])
			       ->andWhere(['type'=>1])
			       ->andWhere(['status'=>1])
			       ->all();
			if($query1){
			return array("data"=>$query1,"msg"=>"success");
		  }else{
			return array("data"=>[],"msg"=>"failure");
		  }
		}
		else{
			return false;
		}
	}
}