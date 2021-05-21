<?php
namespace backend\module\practice\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Users;
use common\models\StudentInfo;
use common\models\StudentFiles;
use common\models\TeacherInfo;
use common\models\TutorInfo;
use common\models\ArrangeInfo;
use common\models\SiteArrange;

class MessageController extends Controller{
	public function actionIndex()
	{
		return "获取信息部分";
	}

	public function actionGetproass(){
		//获取实习分配的信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');//教师的用户名
		$result=(new Query())
		       ->select('*')
		       ->from('teacher_info')
		       ->andWhere(['username'=>$username])
		       ->one();
		$query=(new Query())
		      ->select('*')
		      ->from('site_arrange')
		      ->andWhere(['job_num'=>$result['job_num']])
		      ->andWhere(['typeId'=>2])//表明是实习的情况
		      ->andWhere(['status'=>1])
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}
	public function actionGetsitestu(){
		//获取实习点的学生信息
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$site=$request ->post('site');//对应一个校内指导老师
		$username=$request ->post('username');
		$search=(new Query())
		       ->select('*')
		       ->from('teacher_info')
		       ->andWhere(['username'=>$username])
		       ->one();
		$name=$search['tName'];
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['school_name'=>$site])
		      ->andWhere(['tName'=>$name])
		      ->andWhere(['type'=>2])
		      ->andWhere(['status'=>1])
		      ->all();

		$query1=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['school_name'=>$site])
		      ->andWhere(['tName'=>$name])
		      ->andWhere(['type'=>2])
		      ->andWhere(['status'=>1]);

		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
		return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	  	public function actionGetstatus(){
		//获取实践状态信息
		$request = \Yii::$app->request;
		$sno=$request ->post('sno');
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->one();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return false;
		}
	}

	public function actionGetdesign(){
		//获取教学设计数据
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$type=$request ->post('type');
		$kind=$request ->post('kind');
		$status=$request ->post('status');
		$query=(new Query())
		      ->select('*')
		      ->from('student_files')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['type'=>$type])
		      ->andWhere(['kind'=>$kind])
		      ->andWhere(['status'=>$status])
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return false;
		}
	}

	public function actionMemberlist(){
		//校外导师获取学生的基本情况
		$request = \Yii::$app->request;
		$username=$request->post('username');
		//先获取校外导师工号
		$query=(new Query())
		      ->select('*')
		      ->from('tutor_info')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['status'=>1])
		      ->one();
		$jno=$query['job_num'];
		$result=(new Query())
		       ->select('*')
		       ->from('arrange_info')
		       ->andWhere(['jno'=>$jno])
		       ->andWhere(['ischecked'=>1])
		       ->andWhere(['type'=>2])
		       ->andWhere(['status'=>1])
		       ->all();
		if($result){
			return array("data"=>$result,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

}