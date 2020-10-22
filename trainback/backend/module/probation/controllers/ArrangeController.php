<?php
namespace backend\module\probation\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\Users;
use common\models\TutorInfo;
use common\models\TeacherInfo;
use common\models\SiteArrange;
use common\models\TutorArrange;

class ArrangeController extends Controller{
	public function actionIndex(){
		return "分配页";
	}

	public function actionImportexcel(){
		//管理员上传见习点分配名单
		$request = \Yii::$app->request->post("data");
		$request=json_decode($request,true);
		for($i=0;$i<count((array)$request);$i++){
		$tName=isset($request[$i]['tName'])?$request[$i]['tName']:"";
   		$job_num=isset($request[$i]['job_num'])?$request[$i]['job_num']:"";
   		$typeId=isset($request[$i]['typeId'])?$request[$i]['typeId']:"";
   		$site=isset($request[$i]['site'])?$request[$i]['site']:"";

   		$query=(new Query())
   		      ->select('*')
   		      ->from('site_arrange')
   		      ->andWhere(['tName'=>$tName])
   		      ->andWhere(['status'=>1])
   		      ->one();
   		if($query==null){
   			$Id=(new Query())
    			  ->select('*')
    			  ->from('site_arrange')
                  ->max('aId');
             $maxId=$Id+1;
        $arrange=\Yii::$app->db->createCommand()->insert('site_arrange',
        	[
        	 'aId'=>$maxId,
        	 'tName'=>$tName,
        	 'job_num'=>$job_num,
        	 'typeId'=>$typeId,
        	 'site'=>$site,
        	 'status'=>1
        	])->execute();
   		}
   		else{
   			return array("data"=>[],"msg"=>"已存在");
   		}
	  }
	}

	public function actionGetarr(){
		//返回地点分配数据(见习)
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('site_arrange')
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['status'=>1])
		      ->all();
		$query1=(new Query())
		      ->select('*')
		      ->from('site_arrange')
		      ->andWhere(['typeId'=>1])
		      ->andWhere(['status'=>1]);
		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
         return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionQueryarr(){
	$request = \Yii::$app->request;
   	$name=$request->post('tName');
   	$currentpage=$request ->post('page');
	$pageSize=8;
   	$queryT=(new Query())
   	       ->select('*')
   	       ->from('site_arrange')
   	       ->andWhere(['tName'=>$name])
   	       ->andWhere(['status'=>1])
   	       ->all();

   	$query1=(new Query())
   	       ->select('*')
   	       ->from('site_arrange')
   	       ->andWhere(['tName'=>$name])
   	       ->andWhere(['status'=>1]);

    $countQuery = clone $query1;
    $totalCount = $countQuery->count();
    $data=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();  
    $pageNum = ceil($totalCount/8);
    return array("data"=>[$data,$pageNum],"msg"=>"success");
	}

	public function actionInsertarr(){
		$request = \Yii::$app->request;
		$tName= $request->post('tname');
		$jno= $request->post('jno');
		$typeId= $request->post('typeId');
		$site= $request->post('site');

		//先检查传过来的姓名是否存在于教师表中
		//先找分配表中有没有该对象
		
		$query=(new Query())
   	      ->select('*')
   	      ->from('teacher_info')
   	      ->andWhere(['tName'=>$tName])
   	      ->andWhere(['status'=>1])
   	      ->one();
   	    if($query!=null){
   	    	//新增信息的对象存在就直接插入，因为一个教师可以负责不止一个地点
   	    	$currentId=(new Query())
    			  ->select('*')
    			  ->from('site_arrange')
                  ->max('aId');
            $id=$currentId+1;
            $result=\Yii::$app->db->createCommand()->insert('site_arrange',
   			   array(
          'aId'=>$id, 
          'tName'=>$tName,
          'job_num'=>$jno,
          'typeId'=>$typeId,
          'site'=>$site,
          'status'=>1
          )
   			   )->execute();
            if($result){
              return array("data"=>$result,"msg"=>"插入成功");
            }else{
              return array("data"=>[],"msg"=>"插入失败");
            }
   	    }else{
   	    	return array("data"=>[],"msg"=>"该教师不存在");
   	    }
	}

	public function actionEditarr(){
   	$request = \Yii::$app->request;
   	$aId=$request->post('aId');//考虑管理员不可以随意更改用户的用户名
   	$tName=$request->post('name');
   	$result=(new Query())
   	       ->select('*')
   	       ->from('site_arrange')
   	       ->andWhere(['aId'=>$aId])
   	       ->andWhere(['tName'=>$tName])
   	       ->andWhere(['status'=>1])
   	       ->one();
   	if($result){
   		return array("data"=>$result,"msg"=>"success");
   	}
   	else{
   		return array("data"=>[],"msg"=>"failure");
   	}
	}

	public function actionAlterarr(){
	$request = \Yii::$app->request;
	$aId= $request->post('aId');
   	$name= $request->post('name');
   	$jno= $request->post('jno');
   	$typeId= $request->post('typeId');
   	$site= $request->post('site');
   	//先更新分配表中的数据
   	 $updateA=\Yii::$app->db->createCommand()->update('site_arrange',
   		[
			'job_num'=>$jno,
			'typeId'=>$typeId,
			'site'=>$site,
			'status'=>1
			],
			'aId=:aId',[':aId'=>$aId])
         	->execute();
    if($updateA){
      return array("data"=>$updateA,"msg"=>"success");
    }else{
      return array("data"=>[],"msg"=>"failure");
    }
	}

	public function actionDeletearr(){
		$request = \Yii::$app->request;
		$aId= $request->post('aId');
		$name = $request->post('name');
		//先删分配表，再删导师表
		$del1= \Yii::$app->db->createCommand()
   	     ->delete('site_arrange','aId=:aId',
   	     	[':aId'=>$aId])->execute();
		if($del1){
   		return array("data"=>[],"msg"=>"success");
     	}else{
   		return array("data"=>[],"msg"=>"failure");
   	}
		
	}





}