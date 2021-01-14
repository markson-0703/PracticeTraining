<?php
namespace backend\module\notice\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Notice;
use common\models\StudentInfo;
use common\models\TeacherInfo;
use common\models\TutorInfo;
use common\models\ArrangeInfo;
use common\models\ProbationAssess;

class PushController extends Controller{
	public function actionIndex(){
		return "消息推送部分";
	}

	public function actionIsselect(){
		//判断是否选择导师
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
        $query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        //查看是否在arrangeInfo表中，在就是选了，不在就是没选
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 array_push($pushList,$query[$i]['sId']);
        	$query1=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['username'=>$query[$i]['username']])
        	       ->andWhere(['type'=>1])
        	       ->one();
        	if($query1==null){
        		//如果该表中没有这个学生
        		  array_push($pushList,$query[$i]['sId']);
        	}
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';//字符串类型
        //将推送Id插入到表中
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionWaitcheck(){
		//判断选择了但还没有被审核的
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		$query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
          for($i=0;$i<count($query);$i++){
            $query1=(new Query())
                   ->select('*')
                   ->from('arrange_info')
                   ->andWhere(['username'=>$query[$i]['username']])
                   ->andWhere(['type'=>1])
                   ->andWhere(['ischecked'=>1])//审核了
        		   ->one();
            if($query1==null){
            	//如果学生没有被导师审核
        		  array_push($pushList,$query[$i]['sId']);
            }
        }
            $str= implode(',',$pushList);
            $pushId = $str.'';
            //将推送Id插入到表中
          $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionNeedcheck(){
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		$query=(new Query())
              ->select('*')
              ->from('teacher_info')
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	$query1=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['job_num'=>$query[$i]['job_num']])
        	       ->andWhere(['ischecked'=>0])//还没审核
        	       ->all();
        	if($query1){
        		//有未审核的
        		 array_push($pushList,$query[$i]['tId']);
        	}
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
         //将推送Id插入到表中
          $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionHavecheck(){
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		$query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	$query1=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['username'=>$query[$i]['username']])
        	       ->andWhere(['<>','grade', null])//grade字段不为空
        	       ->andWhere(['ischecked'=>1])//已审核
        	       ->one();
        	if($query1==null){
        		array_push($pushList,$query[$i]['sId']);
        	}	
        } 
        $str= implode(',',$pushList);
        $pushId = $str.'';
        //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionAsleader(){
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		$query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	$query1=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['username'=>$query[$i]['username']])
        	       ->andWhere(['ischecked'=>1])//已审核
        	       ->andWhere(['leader'=>1])//已审核
        	       ->one();
        	if($query1){
        		array_push($pushList,$query[$i]['sId']);
        	}	
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
            //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionTutorevaluate(){
		//判断校外是否评价
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		//所有校外导师
		$query=(new Query())
              ->select('*')
              ->from('tutor_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['tutor'=>$query[$i]['tName']])
               ->andWhere(['status'=>2])//仍处于小组评价完成阶段
               ->all();
            if($query1){
            	array_push($pushList,$query[$i]['tId']);
            }
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
                 //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionTeacherevaluate(){
		//判断校内是否评价
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		//所有校内导师
		$query=(new Query())
              ->select('*')
              ->from('teacher_info')
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['teacher'=>$query[$i]['tName']])
               ->andWhere(['status'=>3])//仍处于校外评价完成阶段
               ->all();
            if($query1){
            	array_push($pushList,$query[$i]['tId']);
            }
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
        //插入
       $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionFinalmark(){
		//判断校内是否给出最终见习成绩
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		//所有校内导师
		$query=(new Query())
              ->select('*')
              ->from('teacher_info')
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['teacher'=>$query[$i]['tName']])
               ->andWhere(['mark'=>null])//还未打分
               ->all();
            if($query1){
            	array_push($pushList,$query[$i]['tId']);
            }
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
        //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        } 
	}

	public function actionMarkresult(){
		//见习成绩已出
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
		//所有见习学生
		$query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['username'=>$query[$i]['username']])
               ->andWhere(['<>','mark', null])//成绩有给出
               ->one();
            if($query1){
            	array_push($pushList,$query[$i]['sId']);
            }
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
        //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}

	public function actionAssessresult(){
		//见习评定已出
		$request = \Yii::$app->request;
		$nId=$request->post('id');//消息Id
		$object=$request->post('object');//消息对象
		$pushTime=date('YmdHis');//推送时间
	    //所有见习学生
		$query=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['probation'=>1])
              ->andWhere(['status'=>1])
              ->all();
        $pushList=[];
        for($i=0;$i<count($query);$i++){
        	 $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['username'=>$query[$i]['username']])
               ->andWhere(['status'=>4]||['status'=>5 ])
               ->one();
            if($query1){
            	array_push($pushList,$query[$i]['sId']);
            }
        }
        $str= implode(',',$pushList);
        $pushId = $str.'';
        //插入
        $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'pushId'=>$pushId,
   	    	'pushTime'=>$pushTime
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
        if($update){
        	return array("data"=>$pushId,"msg"=>"success");
        }else{
        	return array("data"=>$pushId,"msg"=>"failure");
        }
	}
}