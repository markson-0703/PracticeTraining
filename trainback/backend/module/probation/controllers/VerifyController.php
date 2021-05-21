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

class VerifyController extends Controller{
	 public function actionIndex()
	{
		return "导师审核部分";
	}

	public function actionSetleader(){
		//导师设置组长
		$request = \Yii::$app->request;
		$sno=$request ->post('sno');
		//先查
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->andWhere(['type'=>1])
		      ->one();

		if($query){
           $id=$query['aId'];
		   $set=\Yii::$app->db->createCommand()->update('arrange_info',
			[
			'leader'=>1
			],
			'aId=:id',[':id'=>$id])
		->execute();
	    if($set){
            return array("data"=>$set,"msg"=>"success");
              }else{
            return array("data"=>[],"msg"=>"failure");
          }
      }else{
      	return false;
      }
  }

  public function actionRemoveleader(){
  	//导师移除某学生组长的位置
  	$request = \Yii::$app->request;
	$sno=$request ->post('sno');
			//先查
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->andWhere(['type'=>1])
		      ->one();

		if($query){
           $id=$query['aId'];
		   $set=\Yii::$app->db->createCommand()->update('arrange_info',
			[
			'leader'=>0
			],
			'aId=:id',[':id'=>$id])
		->execute();
	    if($set){
            return array("data"=>$set,"msg"=>"success");
              }else{
            return array("data"=>[],"msg"=>"failure");
          }
      }else{
      	return false;
      }
  }

  public function actionCheckstu(){
  	//导师审核学生
  	$request = \Yii::$app->request;
	$sno=$request ->post('sno');
	$grade=$request ->post('grade');
	$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->andWhere(['type'=>1])
		      ->one();

		if($query){
           $id=$query['aId'];
		   $set=\Yii::$app->db->createCommand()->update('arrange_info',
			[
			'ischecked'=>1,
			'grade'=>$grade
			],
			'aId=:id',[':id'=>$id])
		->execute();
	    if($set){
            return array("data"=>$query,"msg"=>"success");
              }else{
            return array("data"=>[],"msg"=>"failure");
          }
      }else{
      	return false;
      }
  }

  public function actionDeleteset(){
  	//删除已结束的学生见习信息
  	$request = \Yii::$app->request;
	$sno=$request ->post('sno');
	$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sno'=>$sno])
		      ->andWhere(['type'=>1])
		      ->one();

    	if($query){
           $id=$query['aId'];
		   $del=\Yii::$app->db->createCommand()->update('arrange_info',
			[
			'ischecked'=>0,
			'status'=>0
			],
			'aId=:id',[':id'=>$id])
		->execute();
	    if($del){
            return array("data"=>$del,"msg"=>"success");
              }else{
            return array("data"=>[],"msg"=>"failure");
          }
      }else{
      	return false;
      }
  }


}
