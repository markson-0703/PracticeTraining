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
use common\models\ProbationFiles;
use common\models\ProbationVideos;

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

	public function actionGetteadetail(){
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

	public function actionGetproarr(){
		//获取见习分配的信息
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
		      ->andWhere(['job_num'=>$result])
		      ->andWhere(['typeId'=>1])//表明是见习的情况
		      ->andWhere(['status'=>1])
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionGetsitestu(){
		//获取见习点的学生信息
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$site=$request ->post('site');//对应一个校内指导老师
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['school_name'=>$site])
		      ->andWhere(['type'=>1])
		      ->andWhere(['status'=>1])
		      ->all();

		$query1=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['school_name'=>$site])
		      ->andWhere(['type'=>1])
		      ->andWhere(['status'=>1]);

		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
		return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionGetactivity(){
		$request = \Yii::$app->request;
		$query=(new Query())
		      ->select('*')
		      ->from('activity_info')
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionGetallactivity(){
		//获取某名学生所有的见习活动信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$type=$request ->post('type');
		$query=(new Query())
		      ->select('*')
		      ->from('activity_details')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['type'=>$type])
		      ->all();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionGettime(){
		//获取时间信息
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$contentId=$request ->post('contentId');
		$query=(new Query())
		      ->select('*')
		      ->from('activity_details')
		      ->andWhere(['username'=>$username])
		      ->andWhere(['contentId'=>$contentId])
		      ->one();
		if($query){
			return array("data"=>$query,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
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

	public function actionGetarrange(){
		//获取见习导师分配的所有信息
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['status'=>1])
		      ->andWhere(['type'=>1])
		      ->all();
		$query1=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['status'=>1])
		      ->andWhere(['type'=>1]);
		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
        return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionQueryarrange(){
		//按照学生姓名或教师姓名获取信息
		$request = \Yii::$app->request;
		$name=$request->post('name');
		$currentpage=$request ->post('page');
		$pageSize=8;
			$query=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sName' => $name])
		      ->andWhere(['status' => 1])
		      ->all();
		    if($query){
		    	//传过来的是学生姓名
		     $query1=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['sName' => $name ])
		      ->andWhere(['status' => 1]);
		 $countQuery=clone $query1;
		 $totalCount=$countQuery->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
		    }else{
		     $result=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['tName' => $name])
		      ->andWhere(['status' => 1])
		      ->all();
		      $result1=(new Query())
		      ->select('*')
		      ->from('arrange_info')
		      ->andWhere(['tName' => $name])
		      ->andWhere(['status' => 1]);
         $countQuery=clone $result1;
		 $totalCount=$countQuery->count();
		 $data=$result1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
		    }	
	}

	public function actionDelonearr(){
		//删除一条见习分配信息
		$request = \Yii::$app->request;
		$username=$request->post('username');
		// $delete=\Yii::$app->db->createCommand()
		//        ->delete('arrange_info','username=:uname',
  //                  [':uname' => $username])->execute();
		$delete=\Yii::$app->db->createCommand()->update('arrange_info',
   		[
			'status'=>0
			],
			'username=:username',[':username'=>$username])
         	->execute();
		if($delete){
			return array("data"=>$delete,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionDelallarr(){
		//删除一条见习分配信息
		$request = \Yii::$app->request;
        // $deleteAll=\Yii::$app->db->createCommand()
	       //  ->deleteAll('arrange_info')->execute();
		$deleteAll=Yii::$app->db->createCommand()->update('arrange_info',
   		[
			'status'=>0
		])->execute();
		if($deleteAll){
			return array("data"=>$deleteAll,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionGetfile(){
		//获取见习文件数据
		$request = \Yii::$app->request;
		$username=$request->post('username');
		$type=$request->post('type');
		$result=(new Query())
		       ->select('*')
		       ->from('probation_files')
		       ->andWhere(['username'=>$username])
		       ->andWhere(['type'=>$type])
		       ->andWhere(['status'=>1])
		       ->all();
		if($result){
			return array("data"=>$result,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}

	public function actionDelmyfile(){
		//根据Id删除文件
		$request = \Yii::$app->request;
		$id=$request->post('id');
		//直接删除
		// $delete=\Yii::$app->db->createCommand()
	 //          ->delete('probation_files','fId=:id',
  //             [':id' => $id])->execute();
  //    //修改状态
        $delete=Yii::$app->db->createCommand()->update('probation_files',
        	[
        	'status'=>0
        	],
        	'fId=:id',[':id' => $id])->execute();
         if($delete){
         	return array("data"=>$delete,"msg"=>"success");
         }else{
         	return array("data"=>[],"msg"=>"failure");
         }
	}

	public function actionDelmyvideo(){
		$request = \Yii::$app->request;
		$id=$request->post('id');
		$delete=Yii::$app->db->createCommand()->update('probation_videos',
        	[
        	'status'=>0
        	],
        	'vId=:id',[':id' => $id])->execute();
         if($delete){
         	return array("data"=>$delete,"msg"=>"success");
         }else{
         	return array("data"=>[],"msg"=>"failure");
         }
	}

	public function actionFileurl(){
		//获取文件路径
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $query=(new Query())
          ->select('*')
          ->from('probation_files')
          ->andWhere(['fId'=>$id])
          ->andWhere(['status'=>1])
          ->one();
        if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
	}

	public function actionVideourl(){
		//获取视频路径
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $query=(new Query())
          ->select('*')
          ->from('probation_videos')
          ->andWhere(['vId'=>$id])
          ->andWhere(['status'=>1])
          ->one();
        if($query){
        $url=explode(':',$query['path']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
        return array("data"=>$final,"msg"=>"success");
    }else{
        return false;
    }
	}
	public function actionGetvideo(){
		//获取见习视频文件数据
		$request = \Yii::$app->request;
		$username=$request->post('username');
		$type=$request->post('type');
		$result=(new Query())
		       ->select('*')
		       ->from('probation_videos')
		       ->andWhere(['username'=>$username])
		       ->andWhere(['type'=>$type])
		       ->andWhere(['status'=>1])
		       ->all();
		if($result){
			return array("data"=>$result,"msg"=>"success");
		}else{
			return array("data"=>[],"msg"=>"failure");
		}
	}




}