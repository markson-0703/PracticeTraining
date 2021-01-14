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

class ProbationController extends Controller{

     public function actionIndex()
	{
		return "见习部分";
	}

	// 加密
	 public function PasswordEncry($password,$encryptedData="zhouqing")
    {
        $en = \Yii::$app->getSecurity()->encryptByPassword($password,$encryptedData);
        return base64_encode($en);
    }

    //生成token
    public function generateAccessToken()
    {
//        $this->token = \Yii::$app->security->generateRandomString();
        return \Yii::$app->security->generateRandomString();
    }

	public function actionGetusers(){
		//获取用户信息
		 $request = \Yii::$app->request;
		 $currentpage=$request ->post('page');
		 $pageSize=8;
		 $query=(new Query())
		      ->select('*')
		      ->from('users')
		      ->andWhere(['status' => 1])
          ->andWhere(['probation' => 1])
          ->andWhere(['<>','role',1])//不将管理员自身设置到管理中
		      ->all();
		 $query1=(new Query())
		      ->select('*')
		      ->from('users')
		      ->andWhere(['status' => 1])
          ->andWhere(['probation' => 1])
          ->andWhere(['<>','role',1]);
		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionQueryuser(){
		//查找用户信息
		$request = \Yii::$app->request;
		$kind=$request->post('role');
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('users')
		      ->andWhere(['role' => $kind])
		      ->andWhere(['status' => 1])
          ->andWhere(['probation' => 1])
		      ->all();
		 //分页
		 $query1=(new Query())
		      ->select('*')
		      ->from('users')
		      ->andWhere(['role' => $kind])
          ->andWhere(['probation' => 1])
		      ->andWhere(['status' => 1]);
		 $countQuery=clone $query1;
		 $totalCount=$countQuery->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionDeleteuser(){
		//永久删除用户
		$request = \Yii::$app->request;
		$username=$request->post('uname');
		$role=$request->post('role');
		if($role==2){
			$deleteU = \Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
            // $result1 = \Yii::$app->db->createCommand()
            //      ->delete('teacher_info','username=:uname',
            //      	[':uname'=>$username])->execute(); 

            if($deleteU){
         	return array("data"=>[],"msg"=>"success");
	     }
	     else{
	     	return array("data"=>[],"msg"=>"failure");
	     }

		}
		else if($role==3){
		$deleteU = \Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
        $result2 = \Yii::$app->db->createCommand()->update('student_info',
        [
        'probation'=>0
        ],'username=:uname',[':uname'=>$uname])->execute();

         if($deleteU & $result2){
         	return array("data"=>[],"msg"=>"success");
	     }
	     else{
	     	return array("data"=>[],"msg"=>"failure");
	     }
	 }
	    else if($role==4){
        $deleteU = \Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:uname',[':uname'=>$uname])->execute();
        $result3 = \Yii::$app->db->createCommand()->update('tutor_info',
        [
        'probation'=>0
        ],'username=:uname',[':uname'=>$uname])->execute();
         if($deleteU & $result3){
         	return array("data"=>[],"msg"=>"success");
	     }
	     else{
	     	return array("data"=>[],"msg"=>"failure");
	     }
	  }
   }

   public function actionBulkdelusers(){
    //批量删除选中的用户
    $request = \Yii::$app->request;
    $member=$request->post('member');
    for($i=0;$i<count($member);$i++){
      $uname=$member[$i];
      $updateU=\Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
      $role=(new Query())
           ->select('*')
           ->from('users')
           ->andWhere(['username'=>$uname])
           ->one();
      // if($role['role']==2){
      //   $teacher=\Yii::$app->db->createCommand()->update('teacher_info',
      //   [
      //   'status'=>0
      //   ],'username=:username',[':username'=>$uname])->execute();
      // }else
      if($role['role']==3){
         $student=\Yii::$app->db->createCommand()->update('student_info',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
      }else if($role['role']==4){
         $tutor=\Yii::$app->db->createCommand()->update('tutor_info',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
      }
    }
    if($updateU){
      return array("data"=>$member,"msg"=>"success");
    }else{
       return array("data"=>[],"msg"=>"failure");
    }
   }

   public function actionGettea(){
   	//获取教师信息
   	$request = \Yii::$app->request;
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$teadata=(new Query())
   	        ->select('*')
   	        ->from('teacher_info')
   	        ->andWhere(['status' => 1])
   	        ->all();

   	$query1=(new Query())
   	        ->select('*')
   	        ->from('teacher_info')
   	        ->andWhere(['status' => 1]);
   	$countQuery = clone $query1;
    $totalCount=$countQuery->count();
	  $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success"); 
   }

   public function actionGetschool(){
    //获取学校信息
    $request = \Yii::$app->request;
    $currentpage=$request ->post('page');
    $pageSize=8;
    $schooldata=(new Query())
            ->select('*')
            ->from('site_arrange')
            ->andWhere(['typeId'=>1])
            ->andWhere(['status' => 1])
            ->all();

    $query1=(new Query())
            ->select('*')
            ->from('site_arrange')
            ->andWhere(['typeId'=>1])
            ->andWhere(['status' => 1]);
    $countQuery = clone $query1;
    $totalCount=$countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success"); 
   }

     public function actionGettut(){
   	//获取校外教师信息
   	$request = \Yii::$app->request;
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$teadata=(new Query())
   	        ->select('*')
   	        ->from('tutor_info')
   	        ->andWhere(['status' => 1])
            ->andWhere(['probation' => 1])
   	        ->all();

   	$query1=(new Query())
   	        ->select('*')
   	        ->from('tutor_info')
            ->andWhere(['probation' => 1])
   	        ->andWhere(['status' => 1]);
   	$countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all(); 
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success"); 
   }

   public function actionGetstu(){
   	//获取学生信息
   	$request=\Yii::$app->request;
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$studata=(new Query())
   	        ->select('*')
   	        ->from('student_info')
            
            ->andWhere(['probation' => 1])
   	        ->andWhere(['status'=>1])
   	        ->all();
    $query1=(new Query())
   	        ->select('*')
   	        ->from('student_info')
            ->andWhere(['probation' => 1])
   	        ->andWhere(['status'=>1]);
   	$countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success"); 
   }

   public function actionQuerytea(){
   	//搜索教师信息
   	$request = \Yii::$app->request;
   	$name=$request->post('tName');
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$queryT=(new Query())
   	       ->select('*')
   	       ->from('teacher_info')
   	       ->andWhere(['tName'=>$name])
   	       ->andWhere(['status'=>1])
   	       ->all();

   	$query1=(new Query())
   	       ->select('*')
   	       ->from('teacher_info')
   	       ->andWhere(['tName'=>$name])
   	       ->andWhere(['status'=>1]);

    $countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success");
   }

   public function actionQuerysite(){
    $request = \Yii::$app->request;
    $name=$request->post('school');
    $currentpage=$request ->post('page');
    $pageSize=8;
    $queryT=(new Query())
           ->select('*')
           ->from('site_arrange')
           ->andWhere(['site'=>$name])
           ->andWhere(['status'=>1])
           ->andWhere(['typeId'=>1])
           ->all();

    $query1=(new Query())
           ->select('*')
           ->from('site_arrange')
           ->andWhere(['site'=>$name])
           ->andWhere(['typeId'=>1])
           ->andWhere(['status'=>1]);

    $countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success");
   }

    public function actionQuerytut(){
   	//搜索校外教师信息
   	$request = \Yii::$app->request;
   	$name=$request->post('tName');
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$queryT=(new Query())
   	       ->select('*')
   	       ->from('tutor_info')
   	       ->andWhere(['tName'=>$name])
   	       ->andWhere(['status'=>1])
           ->andWhere(['probation' => 1])
   	       ->all();

   	$query1=(new Query())
   	       ->select('*')
   	       ->from('tutor_info')
   	       ->andWhere(['tName'=>$name])
           ->andWhere(['probation' => 1])
   	       ->andWhere(['status'=>1]);

    $countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success");
   }

   public function actionQuerystu(){
   	//搜索学生信息
   	$request = \Yii::$app->request;
   	$name=$request->post('name');
   	$currentpage=$request ->post('page');
	  $pageSize=8;
   	$queryS=(new Query())
   	       ->select('*')
   	       ->from('student_info')
   	       ->andWhere(['sName'=>$name])
   	       ->andWhere(['status'=>1])
           ->andWhere(['probation' => 1])
   	       ->all();
   	$query1=(new Query())
   	       ->select('*')
   	       ->from('student_info')
   	       ->andWhere(['sName'=>$name])
           ->andWhere(['probation' => 1])
   	       ->andWhere(['status'=>1]);

   	$countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success");
   }

   public function actionEdittea(){
   	//获取教师表单数据
   	$request = \Yii::$app->request;
   	$username=$request->post('username');//考虑管理员不可以随意更改用户的用户名
   	$result=(new Query())
   	       ->select('*')
   	       ->from('teacher_info')
   	       ->andWhere(['username'=>$username])
   	       ->andWhere(['status'=>1])
   	       ->one();
   	if($result){
   		return array("data"=>$result,"msg"=>"success");
   	}
   	else{
   		return array("data"=>[],"msg"=>"failure");
   	}
   }

      public function actionEdittut(){
   	//获取校外教师表单数据
   	$request = \Yii::$app->request;
   	$username=$request->post('username');//考虑管理员不可以随意更改用户的用户名
   	$result=(new Query())
   	       ->select('*')
   	       ->from('tutor_info')
   	       ->andWhere(['username'=>$username])
   	       ->andWhere(['status'=>1])
   	       ->one();
   	if($result){
   		return array("data"=>$result,"msg"=>"success");
   	}
   	else{
   		return array("data"=>[],"msg"=>"failure");
   	}
   }

   public function actionEditstu(){
   	//获取学生表单数据
   	$request = \Yii::$app->request;
   	$username=$request->post('username');
   	$result=(new Query())
   	       ->select('*')
   	       ->from('student_info')
   	       ->andWhere(['username'=>$username])
   	       ->andWhere(['status'=>1])
   	       ->one();
   	 if($result){
   	 	return array("data"=>$result,"msg"=>"success");
   	 }
   	 else{
   	 	return array("data"=>[],"msg"=>"failure");
   	 }
   }

   public function actionAltertea(){
   	//更新修改的教师信息
   	$request = \Yii::$app->request;
   	$username= $request->post('username');
   	$name= $request->post('tname');
   	$job_num= $request->post('jnum');
   	$phone= $request->post('phone');
   	$email= $request->post('email');
   	$rank= $request->post('rank');
   	//更新数据
   	$updateT=\Yii::$app->db->createCommand()->update('teacher_info',
   		[
			'tName'=>$name,
			'job_num'=>$job_num,
			'contactPhone'=>$phone,
			'email'=>$email,
			'rank'=>$rank
			],
			'username=:username',[':username'=>$username])
         	->execute();
        if($updateT){
        	 return array("data"=>$updateT,"msg"=>"success");
        }
        else{
        	return array("data"=>$updateT,"msg"=>"failure");
        }
   }

      public function actionAltertut(){
   	//更新修改的校外教师信息
   	$request = \Yii::$app->request;
   	$username= $request->post('username');
   	$name= $request->post('tname');
   	$job_num= $request->post('jnum');
   	$school_name= $request->post('school_name');
   	$phone= $request->post('phone');
   	$email= $request->post('email');
   	$rank= $request->post('rank');
   	//更新数据
   	$update=\Yii::$app->db->createCommand()->update('tutor_info',
   		[
			'tName'=>$name,
			'school_name'=>$school_name,
			'job_num'=>$job_num,
			'contactPhone'=>$phone,
			'email'=>$email,
			'rank'=>$rank
			],
			'username=:username',[':username'=>$username])
         	->execute();
        if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>$update,"msg"=>"failure");
        }
   }

   public function actionAlterstu(){
   	//更新修改的学生信息
   	$request = \Yii::$app->request;
   	$username= $request->post('username');
   	$name= $request->post('name');
   	$sno= $request->post('sno');
   	$sex= $request->post('sex');
   	$cId= $request->post('cId');
   	$cName= $request->post('cName');
   	$mId= $request->post('mId');
   	$mName= $request->post('mName');
   	$bornDate= $request->post('bornDate');
   	$phone= $request->post('phone');
   	$email= $request->post('email');
   	//更新数据
   	$updateS=\Yii::$app->db->createCommand()->update('student_info',
   		[
   		'sName'=>$name,
   		'sno'=>$sno,
   		'sex'=>$sex,
   		'cId'=>$cId,
   		'className'=>$cName,
   		'majorId'=>$mId,
   		'majorName'=>$mName,
   		'bornDate'=>$bornDate,
   		'phone'=>$phone,
   		'email'=>$email
   		],
   		'username=:username',[':username'=>$username])
   	     ->execute();
   	    if($updateS){
        	 return array("data"=>$updateS,"msg"=>"success");
        }
        else{
        	return array("data"=>$updateS,"msg"=>"failure");
        }
   }

   public function actionInserttea(){
   	//新增一条教师数据
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$name = $request->post('name');
   	$jno = $request->post('jno');
   	$phone = $request->post('phone');
   	$email = $request->post('email');
   	$rank = $request->post('rank');
   	//先找数据表中有没有该对象
   	$query=(new Query())
   	      ->select('*')
   	      ->from('teacher_info')
   	      ->andWhere(['job_num'=>$jno])
   	      ->andWhere(['status'=>1])
   	      ->one();
   	if($query==null){
   		//如果教师表中没有该用户，则将其插入
   		$currentId=(new Query())
    			  ->select('*')
    			  ->from('teacher_info')
                  ->max('tId');
        $id=$currentId+1;
   		$result=\Yii::$app->db->createCommand()->insert('teacher_info',
   			   array(
          'tId'=>$id, 
          'username'=>$username,
          'tName'=>$name,
          'job_num'=>$jno,
          'contactPhone'=>$phone,
          'email'=>$email,
          'rank'=>$rank
          )
   			   )->execute();
   		if($result){
   			//如果教师表中增加成功，就要将这个新用户再插入到users表中
   			$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   			$maxId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
            $newId=$maxId+1;
   			$user = \Yii::$app->db->createCommand()->insert('users',
   			 array(
            'id'=>$newId, 
            'username'=>$username,  
            'password'=>$passwordE,
            'role'=>2,
            'status'=>1,
            'probation'=>1,
            'practice'=>1,
            'microteaching'=>1,
            'socials'=>1
            ))->execute();

   			 if($user){
   			 	return array("data"=>$user,"msg"=>"success");
   			 }
   			 else{
   			 	return array("data"=>[],"msg"=>"failure");
   			 }
   		}
   		else{
   			return array("data"=>$result,"msg"=>"教师添加失败");
   		}
   	}
   	else{
   		return array("data"=>$query,"msg"=>"已存在");
   	}
   }

    public function actionInserttut(){
   	//新增一条校外教师数据
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$name = $request->post('name');
   	$jno = $request->post('jno');
   	$school_name = $request->post('school_name');
   	$phone = $request->post('phone');
   	$email = $request->post('email');
   	$rank = $request->post('rank');
   	//先找数据表中有没有该对象
   	$query=(new Query())
   	      ->select('*')
   	      ->from('tutor_info')
   	      ->andWhere(['job_num'=>$jno])
   	      ->andWhere(['status'=>1])
   	      ->one();
   	if($query==null){
   		//如果校外教师表中没有该用户，则将其插入
   		$currentId=(new Query())
    			  ->select('*')
    			  ->from('tutor_info')
                  ->max('tId');
        $id=$currentId+1;
   		$result=\Yii::$app->db->createCommand()->insert('tutor_info',
   			   array(
          'tId'=>$id, 
          'username'=>$username,
          'tName'=>$name,
          'school_name'=>$school_name,
          'job_num'=>$jno,
          'contactPhone'=>$phone,
          'email'=>$email,
          'rank'=>$rank
          )
   			   )->execute();
   		if($result){
   			//如果校外教师表中增加成功，就要将这个新用户再插入到users表中
   			$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   			$maxId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
            $newId=$maxId+1;
   			$user = \Yii::$app->db->createCommand()->insert('users',
   			 array(
            'id'=>$newId, 
            'username'=>$username,  
            'password'=>$passwordE,
            'role'=>4,
            'status'=>1,
            'probation'=>1,
            'practice'=>1,
            'microteaching'=>1,
            'socials'=>1
            ))->execute();

   			 if($user){
   			 	return array("data"=>$user,"msg"=>"success");
   			 }
   			 else{
   			 	return array("data"=>[],"msg"=>"failure");
   			 }
   		}
   		else{
   			return array("data"=>$result,"msg"=>"教师添加失败");
   		}
   	}
   	else{
   		return array("data"=>$query,"msg"=>"已存在");
   	}
   }

   public function actionInsertstu(){
   	//新增一条学生数据
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$name = $request->post('name');
   	$sno = $request->post('sno');
   	$sex = $request->post('sex');
   	$cId = $request->post('cId');
   	$className = $request->post('className');
   	$mId = $request->post('mId');
   	$majorName = $request->post('majorName');
   	$bornDate = $request->post('bornDate');
   	$phone = $request->post('phone');
   	$email = $request->post('email');
   	//先找学生表中有没有该对象
   	$query=(new Query())
   	      ->select('*')
   	      ->from('student_info')
   	      ->andWhere(['sno'=>$sno])
   	      ->andWhere(['status'=>1])
   	      ->one();
   	if($query==null){
   		//如果学生表中没有该用户，则将其插入
   		$currentId=(new Query())
    			  ->select('*')
    			  ->from('student_info')
                  ->max('sId');
        $id=$currentId+1;
   		$result=\Yii::$app->db->createCommand()->insert('student_info',
   			   array(
          'sId'=>$id, 
          'username'=>$username,
          'sName'=>$name,
          'sno'=>$sno,
          'sex'=>$sex,
          'cId'=>$cId,
          'className'=>$className,
          'majorId'=>$mId,
          'majorName'=>$majorName,
          'bornDate'=>$bornDate,
          'phone'=>$phone,
          'email'=>$email
          )
   			   )->execute();
   		if($result){
   			//如果学生表中增加成功，就要将这个新用户再插入到users表中
   			$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   			$maxId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
            $newId=$maxId+1;
   			$user = \Yii::$app->db->createCommand()->insert('users',
   			 array(
            'id'=>$newId, 
            'username'=>$username,  
            'password'=>$passwordE,
            'role'=>3,
            'status'=>1,
            'probation'=>1,
            'practice'=>1,
            'microteaching'=>1,
            'socials'=>1
            ))->execute();

   			 if($user){
   			 	return array("data"=>$user,"msg"=>"success");
   			 }
   			 else{
   			 	return array("data"=>[],"msg"=>"failure");
   			 }
   		}
   		else{
   			return array("data"=>$result,"msg"=>"学生添加失败");
   		}
   	}
   	else{
   		return array("data"=>$query,"msg"=>"已存在");
   	}
   }


   public function actionDeletetea(){
   	//删除教师用户
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$del1= \Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
   	// $del2= \Yii::$app->db->createCommand()
   	//      ->delete('users','username=:username',
   	//      	[':username'=>$username])->execute();

   	if($del1){
   		return array("data"=>[],"msg"=>"success");
   	}else{
   		return array("data"=>[],"msg"=>"failure");
   	}
   }


   public function actionDeletetut(){
   	//删除教师用户
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$del1= \Yii::$app->db->createCommand()->update('tutor_info',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
   	$del2= \Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();

   	if($del1&$del2){
   		return array("data"=>[],"msg"=>"success");
   	}else{
   		return array("data"=>[],"msg"=>"failure");
   	}
   }

   public function actionDeletestu(){
   	//删除学生用户
   	$request = \Yii::$app->request;
   	$username = $request->post('username');
   	$del1= \Yii::$app->db->createCommand()->update('student_info',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();
   	$del2=\Yii::$app->db->createCommand()->update('users',
        [
        'probation'=>0
        ],'username=:username',[':username'=>$uname])->execute();

   	if($del1&$del2){
   		return array("data"=>[],"msg"=>"success");
   	}else{
   		return array("data"=>[],"msg"=>"failure");
   	}
   	
   }

   public function actionImportexcel(){
   	//校内教师用户批量导入
   	$request = \Yii::$app->request->post("data");
   	$request=json_decode($request,true);
   	for($i=0;$i<count((array)$request);$i++){
   		$username=isset($request[$i]['username'])?$request[$i]['username']:"";
   		$tName=isset($request[$i]['tName'])?$request[$i]['tName']:"";
   		$job_num=isset($request[$i]['job_num'])?$request[$i]['job_num']:"";
   		$contactPhone=isset($request[$i]['contactPhone'])?$request[$i]['contactPhone']:"";
   		$email=isset($request[$i]['email'])?$request[$i]['email']:"";
   		$rank=isset($request[$i]['rank'])?$request[$i]['rank']:"";

   		$query=(new Query())
   		      ->select('*')
   		      ->from('teacher_info')
   		      ->andWhere(['username'=>$username])
   		      ->andWhere(['status'=>1])
   		      ->one();
   		if($query==null){
   			$Id=(new Query())
    			  ->select('*')
    			  ->from('teacher_info')
                  ->max('tId');
            $maxId=$Id+1;
   			$teachers=\Yii::$app->db->createCommand()->insert('teacher_info',
   				[
   				 'tId'=>$maxId,
   				 'username'=>$username,
   				 'tName'=>$tName,
   				 'job_num'=>$job_num,
   				 'contactPhone'=>$contactPhone,
   				 'email'=>$email,
   				 'rank'=>$rank,
   				 'status'=>1
   				])->execute();
   			if($teachers){
   				//如果插入教师表成功，就需要插入到用户表
   				$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   				$biggestId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
                $insertId=$biggestId+1;
                $users=\Yii::$app->db->createCommand()->insert('users',
                	[
                	'id'=>$insertId,
                	'username'=>$username,
                	'password'=>$passwordE,
                	'role'=>2,
                	'status'=>1
                	])->execute();
   			}
   			else{
   				return array("data"=>[],"msg"=>"添加到教师表失败");
   			}
   		}
   		else{
   			return array("data"=>[],"msg"=>"已存在");
   		}
   	}
   }

      public function actionImportexcel1(){
   	//学生用户批量导入
   	$request = \Yii::$app->request->post("data");
   	$request=json_decode($request,true);
   	for($i=0;$i<count((array)$request);$i++){
   		$username=isset($request[$i]['username'])?$request[$i]['username']:"";
   		$sName=isset($request[$i]['sName'])?$request[$i]['sName']:"";
   		$sno=isset($request[$i]['sno'])?$request[$i]['sno']:"";
   		$sex=isset($request[$i]['sex'])?$request[$i]['sex']:"";
   		$cId=isset($request[$i]['cId'])?$request[$i]['cId']:"";
   		$className=isset($request[$i]['className'])?$request[$i]['className']:"";
   		$majorId=isset($request[$i]['majorId'])?$request[$i]['majorId']:"";
   		$majorName=isset($request[$i]['majorName'])?$request[$i]['majorName']:"";
   		$bornDate=isset($request[$i]['bornDate'])?$request[$i]['bornDate']:"";
   		$phone=isset($request[$i]['phone'])?$request[$i]['phone']:"";
   		$email=isset($request[$i]['email'])?$request[$i]['email']:"";

   		$query=(new Query())
   		      ->select('*')
   		      ->from('student_info')
   		      ->andWhere(['username'=>$username])
   		      ->andWhere(['status'=>1])
   		      ->one();
   		if($query==null){
   			$Id=(new Query())
    			  ->select('*')
    			  ->from('student_info')
                  ->max('sId');
            $maxId=$Id+1;
   			$students=\Yii::$app->db->createCommand()->insert('student_info',
   				[
   				 'sId'=>$maxId,
   				 'username'=>$username,
   				 'sName'=>$sName,
   				 'sno'=>$sno,
   				 'sex'=>$sex,
   				 'cId'=>$cId,
   				 'className'=>$className,
   				 'majorId'=>$majorId,
   				 'majorName'=>$majorName,
   				 'bornDate'=>$bornDate,
   				 'phone'=>$phone,
   				 'email'=>$email,
   				 'status'=>1,
           'probation'=>1,
           'practice'=>1,
           'microteaching'=>1,
           'socials'=>1
   				])->execute();
   			if($students){
   				//如果插入学生表成功，就需要插入到用户表
   				$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   				$biggestId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
                $insertId=$biggestId+1;
                $users=\Yii::$app->db->createCommand()->insert('users',
                	[
                	'id'=>$insertId,
                	'username'=>$username,
                	'password'=>$passwordE,
                	'role'=>3,
                	'status'=>1,
                  'probation'=>1,
                  'practice'=>1,
                  'microteaching'=>1,
                  'socials'=>1
                	])->execute();
   			}
   			else{
   				return array("data"=>[],"msg"=>"添加到学生表失败");
   			}
   		}
   		else{
   			return array("data"=>[],"msg"=>"已存在");
   		}
   	}

   }

      public function actionImportexcel2(){
   	//校外教师用户批量导入
   	$request = \Yii::$app->request->post("data");
   	$request=json_decode($request,true);
   	for($i=0;$i<count((array)$request);$i++){
   		$username=isset($request[$i]['username'])?$request[$i]['username']:"";
   		$tName=isset($request[$i]['tName'])?$request[$i]['tName']:"";
   		$job_num=isset($request[$i]['job_num'])?$request[$i]['job_num']:"";
   		$school_name=isset($request[$i]['school_name'])?$request[$i]['school_name']:"";
   		$contactPhone=isset($request[$i]['contactPhone'])?$request[$i]['contactPhone']:"";
   		$email=isset($request[$i]['email'])?$request[$i]['email']:"";
   		$rank=isset($request[$i]['rank'])?$request[$i]['rank']:"";

   		$query=(new Query())
   		      ->select('*')
   		      ->from('tutor_info')
   		      ->andWhere(['username'=>$username])
   		      ->andWhere(['status'=>1])
   		      ->one();
   		if($query==null){
   			$Id=(new Query())
    			  ->select('*')
    			  ->from('tutor_info')
                  ->max('tId');
            $maxId=$Id+1;
   			$tutors=\Yii::$app->db->createCommand()->insert('tutor_info',
   				[
   				 'tId'=>$maxId,
   				 'username'=>$username,
   				 'tName'=>$tName,
   				 'job_num'=>$job_num,
   				 'school_name'=>$school_name,
   				 'contactPhone'=>$contactPhone,
   				 'email'=>$email,
   				 'rank'=>$rank,
   				 'status'=>1,
           'probation'=>1,
           'practice'=>1,
           'microteaching'=>1,
           'socials'=>1
   				])->execute();
   			if($tutors){
   				//如果插入教师表成功，就需要插入到用户表
   				$passwordE = $this->PasswordEncry(123456);//密码默认为123456，进行加密
   				$biggestId=(new Query())
    			  ->select('*')
    			  ->from('users')
                  ->max('id');
                $insertId=$biggestId+1;
                $users=\Yii::$app->db->createCommand()->insert('users',
                	[
                	'id'=>$insertId,
                	'username'=>$username,
                	'password'=>$passwordE,
                	'role'=>4,
                	'status'=>1,
                  'probation'=>1,
                  'practice'=>1,
                  'microteaching'=>1,
                  'socials'=>1
                	])->execute();
   			}
   			else{
   				return array("data"=>[],"msg"=>"添加到教师表失败");
   			}
   		}
   		else{
   			return array("data"=>[],"msg"=>"已存在");
   		}
   	}
   }


}
