<?php
namespace backend\module\practice\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Users;
use common\models\Diary;

class EventController extends Controller{
	 public function actionIndex()
	{
		return "教学日记";
	}

	public function actionSubmitoneevent(){
		//提交当前工作日记
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$time=$request ->post('time');
		$content=$request ->post('content');
		$kind=$request ->post('kind');
	    $query=(new Query())
		      ->select('*')
		      ->from('diary')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['kind'=>$kind])
		      ->andWhere(['date'=>$time])
		      ->one();
		if($query==null){
			$Id=(new Query())
		      ->select('*')
		      ->from('diary')
		      ->max('dId');
		    $currentId=$Id+1;
		$event=\Yii::$app->db->createCommand()->insert('diary',
			[
			  'dId'=>$currentId,
			  'username'=>$username,
			  'kind'=>$kind,
			  'date'=>$time,
			  'content'=>$content,
			  'status'=>1
			])->execute();
		if($event){
			return array("data"=>$event,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
		}else{
			return array("data"=>$query,"msg"=>"信息已存在");
		}
	}

	public function actionGetevents(){
		//获取某名学生所有的教学工作日记
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$kind=$request ->post('kind');
		$query=(new Query())
		      ->select('*')
		      ->from('diary')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['kind'=>$kind])
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionGetoneevent(){
		//修改某条日记的具体内容
	    $request = \Yii::$app->request;
		$username=$request ->post('username');
		$dId=$request ->post('id');
	    $query=(new Query())
		      ->select('*')
		      ->from('diary')
		      ->andWhere(['dId'=>$dId])
		      ->one();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionEditevent(){
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$id=$request ->post('id');
		$date=$request ->post('date');
		$content=$request ->post('content');
		$update=\Yii::$app->db->createCommand()->update('diary',
			[
			'date'=>$date,
			'content'=>$content
			],'dId=:id',[':id'=>$id])
		     ->execute();
	    if($update){
		    	return array("data"=>$update,"msg"=>"success");
		    }
		    else{
		    	return array("data"=>[],"msg"=>"failure");
		    }
	}

}