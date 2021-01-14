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

class NoticeController extends Controller{
	public function actionIndex(){
		return "消息部分";
	}

	public function actionNoticedata(){
		//获取见习部分的消息
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('notice')
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['<>','status', 0])//状态为0是删除状态
          ->orderby('nId') 
		      ->all();
		//分页
		$query1=(new Query())
		      ->select('*')
		      ->from('notice')
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['<>','status', 0]);
		 $countQuery=clone $query1;
		 $totalCount=$countQuery->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionQuerynotice(){
		//搜寻见习部分信息
		$request = \Yii::$app->request;
		$object=$request->post('object');
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('notice')
		      ->andWhere(['ojbect'=>$object])
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['<>','status', 0])//未被删除
		      ->all();
		$query1=(new Query())
		      ->select('*')
		      ->from('notice')
		      ->andWhere(['ojbect'=>$object])
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['<>','status', 0]);
	     $countQuery=clone $query1;
		 $totalCount=$countQuery->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionInsertnotice(){
		//插入新的见习通知
		$request = \Yii::$app->request;
   	    $content = $request->post('content');
   	    $object = $request->post('object');
		$typeId = $request->post('typeId');
		//先查找是否已存在
		$query=(new Query())
   	      ->select('*')
   	      ->from('notice')
   	      ->andWhere(['typeId'=>$typeId])
   	      ->andWhere(['content'=>$content])
   	      ->one();
   	    if($query==null){
   	    	$currentId=(new Query())
    			  ->select('*')
    			  ->from('notice')
                  ->max('nId');
           $id=$currentId+1;	
           $result=\Yii::$app->db->createCommand()->insert('notice',
           	[
           	'nId'=>$id,
           	'typeId'=>$typeId,
           	'content'=>$content,
           	'ojbect'=>$object,
           	'status'=>1
           	])->execute();
           if($result){
           	return array("data"=>$result,"msg"=>"success");
           }else{
           	return array("data"=>[],"msg"=>"failure");
           }
   	    }else{
   	    	return false;
   	    }
	}

	public function actionDeletenotice(){
		//删除新通知
		$request = \Yii::$app->request;
   	    $nId = $request->post('id');//通知的Id
		$delete= \Yii::$app->db->createCommand()->update('notice',
        [
        'status'=>0
        ],'nId=:id',[':id'=>$nId])->execute();
   	if($delete){
   		return array("data"=>[],"msg"=>"success");
   	}else{
   		return array("data"=>[],"msg"=>"failure");
   	}
	}

	public function actionOnenotice(){
		//获取当前通知的内容
		$request = \Yii::$app->request;
   	    $nId = $request->post('id');//通知的Id
   	    $query=(new Query())
   	          ->select('*')
   	          ->from('notice')
   	          ->andWhere(['nId'=>$nId])
   	          ->one();
   	    if($query){
   	    	return array("data"=>$query,"msg"=>"success");
   	    }else{
   	    	return array("data"=>[],"msg"=>"failure");
   	    }
	}

	public function actionUpdatenotice(){
		//修改消息内容
		    $request = \Yii::$app->request;
   	    $nId = $request->post('id');
   	    $content = $request->post('content');
   	    $object = $request->post('ojbect');
   	    //更新数据
   	    $update=\Yii::$app->db->createCommand()->update('notice',
   	    	[
   	    	'content'=>$content,
   	    	'ojbect'=>$object
   	    	],
   	    	'nId=:id',[':id'=>$nId])->execute();
   	    if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

  public function actionDelallnotice(){
    //删除系统消息通知
    $request = \Yii::$app->request;
    //先notice表
     $update1=\Yii::$app->db->createCommand()->update('notice',
      [
      'status'=>0
      ])->execute();
     if($update1){
        return array("data"=>1,"msg"=>"success");
     }else{
      return array("data"=>0,"msg"=>"failure");
     }
  }

  public function actionGetstunotice(){
    //获取学生的未读消息
    $request = \Yii::$app->request;
    $username = $request->post('username');//学生的用户名
    $ojbect = $request->post('ojbect');
    //根据用户名去获取Id
    $query=(new Query())
          ->select('*')
          ->from('student_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['sId'];//学生的Id
    //在notice表中做操作
    //获取未读消息，存在于pushId字段中，不存在于finishId字段中
    $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //现将pushId、finishId转为数组
      $arr1 = explode(',', $result[$i]['pushId']);
      $arr2 = explode(',', $result[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&$judge2){
        //返回未读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionGetteanotice(){
    //获取校内教师的未读消息
    $request = \Yii::$app->request;
    $username = $request->post('username');//教师的用户名
    $ojbect = $request->post('ojbect');
    //根据用户名去获取Id
    $query=(new Query())
          ->select('*')
          ->from('teacher_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
     $target=$query['tId'];//教师的Id
     $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //现将pushId、finishId转为数组
      $arr1 = explode(',', $result[$i]['pushId']);
      $arr2 = explode(',', $result[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&$judge2){
        //返回未读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionGettutnotice(){
    $request = \Yii::$app->request;
    $username = $request->post('username');//教师的用户名
    $ojbect = $request->post('ojbect');
    //根据用户名去获取Id
    $query=(new Query())
          ->select('*')
          ->from('tutor_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['tId'];//教师的Id
     $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //现将pushId、finishId转为数组
      $arr1 = explode(',', $result[$i]['pushId']);
      $arr2 = explode(',', $result[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&$judge2){
        //返回未读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionChangestate(){
    //消息状态改变，由未读到已读,将相应用户的Id添加到finishId字段中
    $request = \Yii::$app->request;
    $nId = $request->post('nId');//消息的Id
    $username = $request->post('username');//学生的用户名
    //根据学生的用户名找到Id
    $query=(new Query())
          ->select('*')
          ->from('student_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $sId=$query['sId'];
    //先检测是否finishId中，在就不要重复添加
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['nId'=>$nId])
           ->one();
    $result=$query1['finishId'];
    if($result!=null){
    //遍历Id
    $finishId=explode(',',$query1['finishId']);//转为数组
    $done=in_array($sId,$finishId);//判断是否在已读字段中
    if($done){
      return array("data"=>[],"msg"=>"已读");
    }else{
      //插入
      $result.=(','.$sId);
      $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
    }
   }else{
    //直接插入
    $result.=$sId;
    $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
   }
  }

  public function actionChangestate1(){
    $request = \Yii::$app->request;
    $nId = $request->post('nId');//消息的Id
    $username = $request->post('username');
    //根据校内教师的用户名找到Id
    $query=(new Query())
          ->select('*')
          ->from('teacher_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
    $tId=$query['tId'];
     //先检测是否finishId中，在就不要重复添加
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['nId'=>$nId])
           ->one();
    $result=$query1['finishId'];
      if($result!=null){
    //遍历Id
    $finishId=explode(',',$query1['finishId']);//转为数组
    $done=in_array($tId,$finishId);//判断是否在已读字段中
    if($done){
      return array("data"=>[],"msg"=>"已读");
    }else{
      //插入
      $result.=(','.$tId);
      $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
    }
   }else{
    //直接插入
    $result.=$tId;
    $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
   }
  }

   public function actionChangestate2(){
    $request = \Yii::$app->request;
    $nId = $request->post('nId');//消息的Id
    $username = $request->post('username');
    //根据校内教师的用户名找到Id
    $query=(new Query())
          ->select('*')
          ->from('tutor_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $tId=$query['tId'];
     //先检测是否finishId中，在就不要重复添加
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['nId'=>$nId])
           ->one();
    $result=$query1['finishId'];
      if($result!=null){
    //遍历Id
    $finishId=explode(',',$query1['finishId']);//转为数组
    $done=in_array($tId,$finishId);//判断是否在已读字段中
    if($done){
      return array("data"=>[],"msg"=>"已读");
    }else{
      //插入
      $result.=(','.$tId);
      $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
    }
   }else{
    //直接插入
    $result.=$tId;
    $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
      if($update){
        return array("data"=>$result,"msg"=>"success");
      }else{
        return array("data"=>$result,"msg"=>"failure");
      }
   }
  }

  public function actionGetreadnotice(){
    //获取已读消息，存在于finishId字段中
    $request = \Yii::$app->request;
    $username = $request->post('username');//学生的用户名
    $ojbect = $request->post('ojbect');
     //根据用户名去获取Id
    $query=(new Query())
          ->select('*')
          ->from('student_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['sId'];//学生的Id
    $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //将finishId转为数组
      $arr = explode(',', $result[$i]['finishId']);
      $judge=in_array($target,$arr);//在已读里面
      if($judge){
        //返回已读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionGettearead(){
    //获取已读消息，存在于finishId字段中
    $request = \Yii::$app->request;
    $username = $request->post('username');
    $ojbect = $request->post('ojbect');
    $query=(new Query())
          ->select('*')
          ->from('teacher_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['tId'];
    $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //将finishId转为数组
      $arr = explode(',', $result[$i]['finishId']);
      $judge=in_array($target,$arr);//在已读里面
      if($judge){
        //返回已读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionGettutread(){
    //获取已读消息，存在于finishId字段中
    $request = \Yii::$app->request;
    $username = $request->post('username');
    $ojbect = $request->post('ojbect');
    $query=(new Query())
          ->select('*')
          ->from('tutor_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['tId'];
    $result=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['typeId'=>1])
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    $noticeList=[];
    $index=0;
    for($i=0;$i<count($result);$i++){
      //将finishId转为数组
      $arr = explode(',', $result[$i]['finishId']);
      $judge=in_array($target,$arr);//在已读里面
      if($judge){
        //返回已读消息
        $noticeList[$i]['index']=$index++;
        $noticeList[$i]['nId']=$result[$i]['nId'];
        $noticeList[$i]['content']=$result[$i]['content'];
        $noticeList[$i]['pushTime']=$result[$i]['pushTime'];
      }
    }
    return array("data"=>$noticeList,"msg"=>"success");
  }

  public function actionLabelnotice(){
    //将所有未读设置为已读
    $request = \Yii::$app->request;
    $username = $request->post('username');
    $ojbect = $request->post('ojbect');
    $query=(new Query())
          ->select('*')
          ->from('student_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['probation'=>1])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['sId'];   //学生Id号
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
    //把所有在pId中但不在fId中的，移到fId中
    for($i=0;$i<count($query1);$i++){
      //现将pushId、finishId转为数组
      $nId=$query1[$i]['nId'];
      $arr1 = explode(',', $query1[$i]['pushId']);
      $arr2 = explode(',', $query1[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&&$judge2){
        //插入到finishId字段中
        $result=$query1[$i]['finishId'];
        if($result){
          //不为空
          $result.=(','.$target);
          //更新
        $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
        }else{
          $result.=$target;
          $update=\Yii::$app->db->createCommand()->update('notice',
         [
         'finishId'=>$result
         ],'nId=:id',[':id'=>$nId])->execute();
        }
      }
    }
    return array("data"=>1,"msg"=>"success");
  }

  public function actionLabelnotice1(){
    //将所有未读设置为已读
    $request = \Yii::$app->request;
    $username = $request->post('username');
    $ojbect = $request->post('ojbect');
    $query=(new Query())
          ->select('*')
          ->from('teacher_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
    $target=$query['tId'];
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
      for($i=0;$i<count($query1);$i++){
      //现将pushId、finishId转为数组
      $nId=$query1[$i]['nId'];
      $arr1 = explode(',', $query1[$i]['pushId']);
      $arr2 = explode(',', $query1[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&&$judge2){
        //插入到finishId字段中
        $result=$query1[$i]['finishId'];
        if($result){
          //不为空
          $result.=(','.$target);
          //更新
        $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
        }else{
          //为空
          $result.=$target;
          $update=\Yii::$app->db->createCommand()->update('notice',
         [
         'finishId'=>$result
         ],'nId=:id',[':id'=>$nId])->execute();
        }
      }
    }
    return array("data"=>1,"msg"=>"success");
  }

   public function actionLabelnotice2(){
    //将所有未读设置为已读
    $request = \Yii::$app->request;
    $username = $request->post('username');
    $ojbect = $request->post('ojbect');
    $query=(new Query())
          ->select('*')
          ->from('tutor_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->andWhere(['probation'=>1])
          ->one();
    $target=$query['tId'];
    $query1=(new Query())
           ->select('*')
           ->from('notice')
           ->andWhere(['ojbect'=>$ojbect])
           ->andWhere(['status'=>1])
           ->all();
      for($i=0;$i<count($query1);$i++){
      //现将pushId、finishId转为数组
      $nId=$query1[$i]['nId'];
      $arr1 = explode(',', $query1[$i]['pushId']);
      $arr2 = explode(',', $query1[$i]['finishId']);
      $judge1=in_array($target,$arr1);//在未读里面
      $judge2=!in_array($target,$arr2);//不在已读里面
      if($judge1&&$judge2){
        //插入到finishId字段中
        $result=$query1[$i]['finishId'];
        if($result){
          //不为空
          $result.=(','.$target);
          //更新
        $update=\Yii::$app->db->createCommand()->update('notice',
        [
        'finishId'=>$result
        ],'nId=:id',[':id'=>$nId])->execute();
        }else{
          //为空
          $result.=$target;
          $update=\Yii::$app->db->createCommand()->update('notice',
         [
         'finishId'=>$result
         ],'nId=:id',[':id'=>$nId])->execute();
        }
      }
    }
    return array("data"=>1,"msg"=>"success");
  }
}