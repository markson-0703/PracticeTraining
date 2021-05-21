<?php
namespace backend\module\practice\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\StudentInfo;
use common\models\TeacherInfo;
use common\models\TutorInfo;

class DetailController extends Controller{
	public function actionIndex()
	{
		return "实习获取信息部分";
	}
	public function actionStudentdetail(){
		//获取学生的个人信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$data=(new Query())
		     ->select('*')
		     ->from('student_info')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['practice'=>1])
		     ->andWhere(['status'=>1])
		     ->one();
		if($data){
			return array("data"=>$data,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionTeacherdetail(){
		//获取校内教师的个人信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$data=(new Query())
		     ->select('*')
		     ->from('teacher_info')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['status'=>1])
		     ->one();
		if($data){
			return array("data"=>$data,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionTutordetail(){
		//获取校外导师的个人信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
	    $data=(new Query())
		     ->select('*')
		     ->from('tutor_info')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['practice'=>1])
		     ->andWhere(['status'=>1])
		     ->one();
		if($data){
			return array("data"=>$data,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

}