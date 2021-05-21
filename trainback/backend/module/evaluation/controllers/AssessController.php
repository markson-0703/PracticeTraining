<?php
namespace backend\module\evaluation\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\ArrangeInfo;
use common\models\TeachAssess;

class AssessController extends Controller{
	public function actionIndex(){
		return "实习评定部分";
	}

	public function actionMyinfo(){
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('arrange_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['ischecked'=>1])
              ->andWhere(['type'=>2])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionSubmitoneassess(){
		//提交一次实习授课评定
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$time=$request ->post('date');
		$weekday=$request ->post('weekday');
		$section=$request ->post('section');
		$grade=$request ->post('grade');
		$classform=$request ->post('classform');
		$content=$request ->post('content');
		$selfAssess=$request ->post('selfAssess');
		//查看是否有重复
		$query=(new Query())
		      ->select('*')
		      ->from('teach_assess')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['time'=>$time])
		      ->andWhere(['section'=>$section])
		      ->one();
		if($query==null){
			$id=(new Query())
		      ->select('*')
		      ->from('teach_assess')
		      ->max('id');
		    $currentId=$id+1;
	    $assess=\Yii::$app->db->createCommand()->insert('teach_assess',
	    	[
	    	  'id'=>$currentId,
	    	  'username'=>$username,
	    	  'class'=>$grade,
	    	  'time'=>$time,
	    	  'weekday'=>$weekday,
	    	  'section'=>$section,
	    	  'classform'=>$classform,
	    	  'content'=>$classform,
	    	  'selfAssess'=>$selfAssess,
	    	  'status'=>1//完成自评，状态改为1
	    	])->execute();
	    if($assess){
	    	return array("data"=>[],"msg"=>"success");
	    }else{
	    	return array("data"=>[],"msg"=>"failure");
	    }

		}else{
			return array("data"=>[],"msg"=>"信息已存在");
		}
	}

	public function actionGetallsubmit(){
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$currentpage=$request ->post('page');
	    $pageSize=8;
		$info=(new Query())
		     ->select('*')
		     ->from('teach_assess')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['<>','status', 0])//开始了自评环节
		     ->all();
	    $query1=(new Query())
		     ->select('*')
		     ->from('teach_assess')
		     ->andWhere(['username'=>$username])
		     ->andWhere(['<>','status', 0]);
		$countQuery = clone $query1;
        $totalCount=$countQuery->count();
	    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$data,$pageNum],"msg"=>"success"); 
	}

	public function actionJudgeleader(){
		//判断是否为组长
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('arrange_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['ischecked'=>1])
              ->andWhere(['type'=>2])//实习
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionGetmember(){
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $info=(new Query())
             ->select('*')
             ->from('arrange_info')
             ->andWhere(['username'=>$username])
             ->andWhere(['type'=>2])
             ->one();
        $tName=$info['tName'];
        $school=$info['school_name'];
        $query=(new Query())
               ->select('*')
               ->from('arrange_info')
               ->andWhere(['tName'=>$tName])
               ->andWhere(['school_name'=>$school])
               ->andWhere(['ischecked'=>1])
               ->andWhere(['type'=>2])
               ->andWhere(['status'=>1])
               ->all();
        if($query){
        	$member=[];
        	for($i=0;$i<count($query);$i++){
        		$member[$i]['username']=$query[$i]['username'];
        		$member[$i]['sno']=$query[$i]['sno'];
        		$member[$i]['sName']=$query[$i]['sName'];
        		$member[$i]['tName']=$query[$i]['tName'];
        		$member[$i]['tutor_name']=$query[$i]['tutor_name'];
          }
          return array("data"=>$member,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionSeeassess(){
	  $request = \Yii::$app->request;
      $username=$request->post('username');
      $content=(new Query())
              ->select('*')
              ->from('teach_assess')
              ->andWhere(['username'=>$username])
              ->andWhere(['<>','status', 0])
              ->all();
      if($content){
        	return array("data"=>$content,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionEveryform(){
	  $request = \Yii::$app->request;
      $id=$request->post('id');
      $evaluation=(new Query())
                 ->select('*')
                 ->from('teach_assess')
                 ->andWhere(['id'=>$id])
                 ->one();
      if($evaluation){
        	return array("data"=>$evaluation,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionTeamassess(){
	    $request = \Yii::$app->request;
        $id=$request->post('id');
        $groupevaluation=$request->post('content');
        $update=\Yii::$app->db->createCommand()->update('teach_assess',
    	[
    	'groupAssess'=>$groupevaluation,
    	'status'=>2//小组评价结束状态由1变为2
    	],'id=:Id',[':Id'=>$id])->execute();
      if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionAssessupdate(){
    //小组评价更新
    $request = \Yii::$app->request;
    $id=$request->post('id');
    $content=$request->post('content');
    //内容更新，除了内容，其他不做改变
    $update=\Yii::$app->db->createCommand()->update('teach_assess',
      [
      'groupAssess'=>$content
      ],'id=:Id',[':Id'=>$id])->execute();
    if($update){
           return array("data"=>$content,"msg"=>"success");
        }
        else{
          return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionAllstudent(){
   	$request = \Yii::$app->request;
    $uname=$request->post('username');
    $query=(new Query())
              ->select('*')
              ->from('tutor_info')
              ->andWhere(['username'=>$uname])
              ->andWhere(['status'=>1])
              ->one();
    if($query){
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
    	$uname=$member[$i]['username'];
        $data[$i]['username']=$uname;
        $data[$i]['sno']=$member[$i]['sno'];
        $data[$i]['sName']=$member[$i]['sName'];
        $data[$i]['tName']=$member[$i]['tName'];
     }
     return array("data"=>$data,"msg"=>"success");
    }else{
    	return false;
    }
   }

   public function actionEverystudent(){
   	$request = \Yii::$app->request;
    $uname=$request->post('username');
    $query=(new Query())
          ->select('*')
          ->from('teach_assess')
          ->andWhere(['username'=>$uname])
          ->all();
    if($query){
    	return array("data"=>$query,"msg"=>"success");
    }else{
    	return array("data"=>[],"msg"=>"success");
    }
   }

   public function actionTutsubmit(){
   	$request = \Yii::$app->request;
   	$id=$request->post('id');
    $content=$request->post('content');
    $update=\Yii::$app->db->createCommand()->update('teach_assess',
    	[
    	'tutorAssess'=>$content,
    	'status'=>3//导师评价结束状态由2变为3
    	],'id=:Id',[':Id'=>$id])->execute();
      if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }

    public function actionTutupdate(){
    $request = \Yii::$app->request;
   	$id=$request->post('id');
    $content=$request->post('content');
    $update=\Yii::$app->db->createCommand()->update('teach_assess',
    	[
    	'tutorAssess'=>$content,
    	],'id=:Id',[':Id'=>$id])->execute();
      if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }
}