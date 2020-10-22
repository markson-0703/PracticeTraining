<?php
namespace backend\module\refine\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\StudentInfo;
use common\models\TeacherInfo;
use common\models\TutorInfo;

class RefineController extends Controller{
	public function actionIndex()
	{
		return "完善个人注册信息";
	}

	public function actionSubtea()
	{
		//提交本校教师信息
		$request = \Yii::$app->request;
		$username=$request->post('username');
		$name=$request->post('name');
		$jno=$request->post('jno');
		$phone=$request->post('phone');
		$email=$request->post('email');
		$rank=$request->post('rank');

		$currentId=(new Query())
    			  ->select('*')
    			  ->from('teacher_info')
    				// ->andWhere(['status' =>1])
                  ->max('tId');
        $id=$currentId+1;

		$result=(new Query())
		       ->select('*')
		       ->from('teacher_info')
		       ->andWhere(['job_num'=>$jno])
		       ->andWhere(['status'=>1])
		       ->one();

		if($result==null){
			//如果当前数据表中还未有该对象，就将其信息插入到数据表中
			$insertTea= \Yii::$app->db->createCommand()
			          ->insert('teacher_info',
			          	array(
			          		'tId'=>$id,
			          		'tName'=>$name,
			          		'username'=>$username,
			          		'job_num'=>$jno,
			          		'contactPhone'=>$phone,
			          		'email'=>$email,
			          		'rank'=>$rank,
			          		'status'=>1)
			          	      )
			          ->execute();
			if($insertTea)
			{
				return array("data"=>$id,"msg"=>"提交成功");
			}
			else{
				return array("data"=>$insertTea,"msg"=>"提交失败");
			}
		}
		else
		{
			return array("data"=>[],"msg"=>"信息已提交");
		}
	}

	public function actionGetteadetail(){
		//获取本校教师信息
		$request = \Yii::$app->request;
		$id=$request->post('tId');
		$query=(new Query())
		      ->select('*')
		      ->from('teacher_info')
		      ->andwhere(['tId'=>$id])
		      ->one();

		 if($query==null){
            return "failure";
          } else {
            return array("data"=>$query,"msg"=>"success");
          }
	} 

	public function actionEdittea(){
		$request = \Yii::$app->request;
		$id=$request->post('tId');
		$result=(new Query())
		       ->select('*')
		       ->from('teacher_info')
		       ->andwhere(['tId'=>$id])
		       ->one();
		if($result==null){
			return "failure";
		}else{
			return array("data"=>$result,"msg"=>"success");
		}
	}

	public function actionAltertea(){
		//修改校内教师信息
		$request = \Yii::$app->request;
		$id=$request->post('tId');
		$name=$request->post('name');
		$jno=$request->post('jno');
		$phone=$request->post('phone');
		$email=$request->post('email');
		$rank=$request->post('rank');
		//更新数据
		
        $query=(new Query())
             ->select('*')
             ->from('teacher_info')
             ->Where(['tId'=> $id])
             ->andWhere(['status'=> 1])
             ->one();

        if($query!=null){
		$updateT=\Yii::$app->db->createCommand()->update('teacher_info',
			[
			'tName'=>$name,
			'job_num'=>$jno,
			'contactPhone'=>$phone,
			'email'=>$email,
			'rank'=>$rank
			],
			'tId=:tId',[':tId'=>$id])
		    ->execute();
		if($updateT){
		    return array("data"=>$updateT,"msg"=>"success");
		}else {
			return array("data"=>$updateT,"msg"=>"failure");
		}
	  }
	  else{
	  	return array("data"=>$query,"msg"=>"用户信息不完善");
	  }
	}

	public function actionSubstu(){
		//提交学生信息
		$request = \Yii::$app->request;
		$username=$request->post('username');
		$name=$request->post('name');
		$sno=$request->post('sno');
		$sex=$request->post('sex');
		$classId=$request->post('classId');
		$className=$request->post('className');
		$majorId=$request->post('majorId');
		$majorName=$request->post('majorName');
		$bDate=$request->post('bDate');
		$phone=$request->post('phone');
		$email=$request->post('email');

		$currensId=(new Query())
    			  ->select('*')
    			  ->from('student_info')
                  ->max('sId');
        $id=$currensId+1;

        $result=(new Query())
		       ->select('*')
		       ->from('student_info')
		       ->andWhere(['sno'=>$sno])
		       ->andWhere(['status'=>1])
		       ->one();
		if($result==null){
			//如果学生表中还没有该学生用户
			$insertStu=\Yii::$app->db->createCommand()
			          ->insert('student_info',
			          	array(
			          		'sId'=>$id,
			          		'sName'=>$name,
			          		'username'=>$username,
			          		'sno'=>$sno,
			          		'sex'=>$sex,
			          		'cId'=>$classId,
			          		'className'=>$className,
			          		'majorId'=>$majorId,
			          		'majorName'=>$majorName,
			          		'bornDate'=>$bDate,
			          		'phone'=>$phone,
			          		'email'=>$email,
			          		'status'=>1))->execute();
			if($insertStu){
				return array("data"=>$id,"msg"=>"success");
			}
			else{
				return array("data"=>$insertStu,"msg"=>"failure");
			}
		}
		else{
			//学生表中已有该用户
			return array("data"=>[],"msg"=>"信息已提交");
		}
	}

	public function actionGetstudetail(){
		//获取学生信息
		$request = \Yii::$app->request;
		$id=$request->post('sId');
		$query=(new Query())
		      ->select('*')
		      ->from('student_info')
		      ->andwhere(['sId'=>$id])
		      ->one();

		 if($query==null){
            return "failure";
          } else {
            return array("data"=>$query,"msg"=>"success");
          }

	}

	public function actionAlterstu(){
		//修改学生信息
		$request=\Yii::$app->request;
		$id=$request->post('sId');
		$name=$request->post('name');
		$sno=$request->post('sno');
		$sex=$request->post('sex');
		$classId=$request->post('classId');
		$className=$request->post('className');
		$majorId=$request->post('majorId');
		$majorName=$request->post('majorName');
		$bDate=$request->post('bDate');
		$phone=$request->post('phone');
		$email=$request->post('email');

		$query=(new Query())
             ->select('*')
             ->from('student_info')
             ->Where(['sId'=> $id])
             ->andWhere(['status'=> 1])
             ->one();
		//更新数据
		if($query!=null){
		$updateS=\Yii::$app->db->createCommand()->update('student_info',
			[
			'sName'=>$name,
			'sno'=>$sno,
			'sex'=>$sex,
			'cId'=>$classId,
			'className'=>$className,
			'majorId'=>$majorId,
			'majorName'=>$majorName,
			'bornDate'=>$bDate,
			'phone'=>$phone,
			'email'=>$email
			],
			'sId=:sId',[':sId'=>$id])
		    ->execute();
		  if($updateS){
		  	 return array("data"=>$updateS,"msg"=>"success");
		  }
		  else{
		  	return array("data"=>$updateS,"msg"=>"failure");
		  }
		}
		else{
			return array("data"=>$query,"msg"=>"用户信息不完善");
		}
	}

	public function actionSubtut(){
		//提交校外教师信息
		$request = \Yii::$app->request;
		$username=$request->post('username');
		$name=$request->post('name');
		$sName=$request->post('sName');
		$jno=$request->post('jno');
		$phone=$request->post('phone');
		$email=$request->post('email');
		$rank=$request->post('rank');

		$current=(new Query())
    			  ->select('*')
    			  ->from('tutor_info')
                  ->max('tId');
        $id=$current+1;

        $result=(new Query())
		       ->select('*')
		       ->from('tutor_info')
		       ->andWhere(['job_num'=>$jno])
		       ->andWhere(['status'=>1])
		       ->one();
		if($result==null){
			//如果校外教师表中还没有该用户
			$inserttut=\Yii::$app->db->createCommand()
			          ->insert('tutor_info',
			          	array(
			          		'tId'=>$id,
			          		'tName'=>$name,
			          		'username'=>$username,
			          		'school_name'=>$sName,
			          		'job_num'=>$jno,
			          		'contactPhone'=>$phone,
			          		'email'=>$email,
			          		'rank'=>$rank,
			          		'status'=>1))->execute();
			if($inserttut){
				return array("data"=>$id,"msg"=>"success");
			}
			else{
				return array("data"=>$inserttut,"msg"=>"failure");
			}
		}
		else{
			//校外教师表中已有该用户
			return array("data"=>[],"msg"=>"信息已提交");
		}
	}

		public function actionGettutordetail(){
		//获取校外教师信息
		$request = \Yii::$app->request;
		$id=$request->post('tId');
		$query=(new Query())
		      ->select('*')
		      ->from('tutor_info')
		      ->andwhere(['tId'=>$id])
		      ->one();

		 if($query==null){
            return "failure";
          } else {
            return array("data"=>$query,"msg"=>"success");
          }
	}

		public function actionAltertut(){
		//修改校外教师信息
		$request = \Yii::$app->request;
		$id=$request->post('tId');
		$name=$request->post('name');
		$sName=$request->post('sName');
		$jno=$request->post('jno');
		$phone=$request->post('phone');
		$email=$request->post('email');
		$rank=$request->post('rank');
		//更新数据
		
        $query=(new Query())
             ->select('*')
             ->from('tutor_info')
             ->Where(['tId'=> $id])
             ->andWhere(['status'=> 1])
             ->one();

        if($query!=null){
		$update=\Yii::$app->db->createCommand()->update('tutor_info',
			[
			'tName'=>$name,
			'school_name'=>$sName,
			'job_num'=>$jno,
			'contactPhone'=>$phone,
			'email'=>$email,
			'rank'=>$rank
			],
			'tId=:tId',[':tId'=>$id])
		    ->execute();
		if($update){
		    return array("data"=>$update,"msg"=>"success");
		}else {
			return array("data"=>$update,"msg"=>"failure");
		}
	  }
	  else{
	  	return array("data"=>$query,"msg"=>"用户信息不完善");
	  }
	}
}