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

class InfomationController extends Controller{

     public function actionIndex()
	{
		return "获取信息部分";
	}

	public function actionGetstudetail(){
		//获取学生的个人信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$data=(new Query())
		     ->select('*')
		     ->from('student_info')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['status'=>1])
		     ->one();
		if($data){
			return array("data"=>$data,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}


	}