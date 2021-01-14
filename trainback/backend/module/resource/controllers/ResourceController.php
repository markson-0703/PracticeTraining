<?php
namespace backend\module\resource\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\TeacherInfo;
use common\models\TeacherFiles;
use common\models\ArrangeInfo;
use common\models\RecordFiles;
use common\models\ProbationFiles;
use common\models\ProbationVideos;
use common\models\ProbationRecord5;
use TCPDF;
use common\config\tcpdf_include;

class ResourceController extends Controller{
	public function actionIndex(){
		return "记录资源管理部分";
	}

	public function actionStudentinfo(){
		//获取自己指导学生的基本信息
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('teacher_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	//获取教师的工号去查找指导的学生
        	$job_num=$query['job_num'];
        	$member=(new Query())
        	       ->select('*')
        	       ->from('arrange_info')
        	       ->andWhere(['job_num'=>$job_num])
        	       ->andWhere(['ischecked'=>1])
        	       ->andWhere(['status'=>1])
        	       ->all();
        	 $data=[];
        	for($i=0;$i<count($member);$i++)
        	{
        		$uname=$member[$i]['username'];//学生的用户名
        		//记录的数据
        	    $record=(new Query())
        	       ->select('*')
        	       ->from('record_files')
        	       ->andWhere(['username'=>$uname])
        	       ->andWhere(['type'=>1])
        	       ->andWhere(['status'=>1])
        	       ->all();
        	    $recordnum=count($record);
        	    //教学设计数据
        	    $design=(new Query())
        	          ->select('*')
        	          ->from('probation_record5')
        	          ->andWhere(['username'=>$uname])
        	          ->andWhere(['status'=>1])
        	          ->all();
        	    $designnum=count($design);
        	    $review=1;//默认设置审阅状态
        	    for($j=0;$j<$designnum;$j++){
        	    	if($design[$j]['review']==null){
        	    		$review=0;
        	    	}
        	    }
        	    //写入数组中
        	    $data[$i]['username']=$member[$i]['username'];
        	    $data[$i]['sno']=$member[$i]['sno'];
        	    $data[$i]['sName']=$member[$i]['sName'];
        	    $data[$i]['record']=$recordnum;
        	    $data[$i]['design']=$designnum;
        	    $data[$i]['status']=$review;
        	}
        	return array("data"=>$data,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionRecordinfo(){
		//根据传过来的用户名获取记录数据
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('record_files')
              ->andWhere(['username'=>$username])
              ->andWhere(['type'=>1])
              ->andWhere(['status'=>1])
              ->all();
        if($query){
        	$path=[];
        	for($j=0;$j<count($query);$j++){
        		$url=explode(':',$query[$j]['filedir']);
        		$dir=explode('/',$url[1]);
        		$arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        		$final=implode('/',$arr);
        		$path[$j]['attachment']=$final;
        	}
        	return array("data"=>[$query,$path],"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionInstructioninfo(){
		//获取教学设计数据
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('probation_record5')
              ->andWhere(['username'=>$username])
              ->andWhere(['status'=>1])
              ->all();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return false;
        }
	}

	public function actionOneinstruction(){
		//获取一条教学设计数据
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $query=(new Query())
              ->select('*')
              ->from('probation_record5')
              ->andWhere(['id'=>$id])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionGeturl(){
		//根据传过来的id查询文件的地址
		$request = \Yii::$app->request;
        $id=$request->post('uId');
        $url=(new Query())
            ->select('*')
            ->from('record_files')
            ->andWhere(['uId'=>$id])
            ->andWhere(['status'=>1])
            ->one();
         if($url){
         $path=explode(':',$url['filedir']);
         $dir=explode('/',$path[1]);
         $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
         $final =implode('/',$arr);
         return array("data"=>$final,"msg"=>"success");
         }else{
         	return false;
         }
	}

	public function actionSubmitreview(){
		//提交审阅
		$request = \Yii::$app->request;
        $id=$request->post('id');
        $content=$request->post('content');
        //确认为空才插入
        $query=(new Query())
              ->select('*')
              ->from('probation_record5')
              ->andWhere(['id'=>$id])
              ->andWhere(['status'=>1])
              ->one();
        $review=$query['review'];
        $time=$query['submitTime'];
        $username=$query['username'];//学生用户名
        if($review==null){
        	//插入
        	$result=Yii::$app->db->createCommand()->update('probation_record5',
        		[
        		'review'=>$content
        		],
        		'id=:id',[':id'=>$id])->execute();
        if($result){
        	//插入成功自动生成pdf放到表中
        	//完整获取数据
           $detail=(new Query())
           ->select('*')
           ->from('probation_record5')
           ->andWhere(['id'=>$id])
           ->andWhere(['status'=>1])
           ->one();
           //创建新的pdf文件
       $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);
        //设置文件基本信息
    $pdf->SetCreator(PDF_CREATOR); //设置创建者
    $pdf->SetAuthor('shiyu'); //设置作者
    $pdf->SetTitle($time); //设置文件的title
    $pdf->SetSubject('student_records'); //设置主题
    $pdf->SetKeywords('student,probation,record'); //设置关键词
    $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习——试讲教学设计', $detail['id'], array(0, 64, 255), array(0, 64, 128)); 
       //设置头部,比如header_logo，header_title，header_string及其属性
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
    $pdf->setHeaderFont(Array("droidsansfallback", '', 12));
    $pdf->setFooterFont(Array('droidsansfallback', '', 12));
    $pdf->SetDefaultMonospacedFont('droidsansfallback'); //设置默认等宽字体
    $pdf->SetFont('droidsansfallback' , '', 14, '', true); //设置字体
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); //设置自动分页
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //设置调整图像自适应比例
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
    }
    $pdf->SetMargins(20, 20, 20); //设置margins 参考css的margins
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); //设置页头margins
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER); //设置页脚margins
    $pdf->setFontSubsetting(true); //设置默认字体子集模式
    $pdf->AddPage(); //增加一个页面
    $pdf->SetFont('droidsansfallback' , 'B', 14);
    $pdf->Cell(0, 10, '见习日期:', 0, 1, 'L');
    $pdf->Write(0, $detail['date'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '具体时间:', 0, 1, 'L');
    $pdf->Write(0, $detail['weekday'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '节数:', 0, 1, 'L');
    $pdf->Write(0, $detail['section'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '年级:', 0, 1, 'L');
    $pdf->Write(0, $detail['grade'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '指导教师:', 0, 1, 'L');
    $pdf->Write(0, $detail['tutor'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '课型:', 0, 1, 'L');
    $pdf->Write(0, $detail['classform'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '授课时间:', 0, 1, 'L');
    $pdf->Write(0, $detail['teachTime'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '授课内容（章节）:', 0, 1, 'L');
    $pdf->Write(0, $detail['teachContent'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '学习内容分析:', 0, 1, 'L');
    $pdf->Write(0, $detail['contentAnalyse'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '学习者分析:', 0, 1, 'L');
    $pdf->Write(0, $detail['objectAnalyse'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教学目标:', 0, 1, 'L');
    $pdf->Write(0, $detail['objections'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教学重难点:', 0, 1, 'L');
    $pdf->Write(0, $detail['difficulties'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教学策略:', 0, 1, 'L');
    $pdf->Write(0, $detail['strategies'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教学过程:', 0, 1, 'L');
    $pdf->Write(0, $detail['process'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '板书设计:', 0, 1, 'L');
    $pdf->Write(0, $detail['writing'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教学反思:', 0, 1, 'L');
    $pdf->Write(0, $detail['reflect'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '学院指导教师审阅意见:', 0, 1, 'L');
    $pdf->Write(0, $detail['review'], '', 0, 'L', true, 0, false, false, 0);
    //放到文件夹下
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $date=explode(' ',$time);
    $time0=explode('-',$date[0]);
    $time1 = explode(':',$date[1]);
    $t0 = implode('',$time0);
    $t1=implode('',$time1);
    $arr=array($t0,$t1);
    $t =implode('',$arr);
    $dir=$menu.'/probation/'.$t;
    $filename= iconv("utf-8","gb2312",$dir.'.pdf');
    $pdf->Output($filename,'F'); //保存至当前file文件夹下
     // 将结果插入到记录表中
     $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['filename'=>$t])
          ->andWhere(['username'=>$username])
          ->one();
     if($query==null){
     	//插入
     	 $id=(new Query())
           ->select('*')
           ->from('record_files')
           ->max('uId');
         $currId=$id+1;
         $result=\Yii::$app->db->createCommand()->insert('record_files',
         	[
         	'uId'=>$currId,
            'type'=>1,
            'kind'=>5,
            'username'=>$username,
            'filename'=>$t,
            'filedir'=>$filename,
            'createdTime'=>$time,
            'status'=>1
         	])->execute();
        if($result){
           return array("data"=>$username,"msg"=>"success");
        }else{
            return array("data"=>$t,"msg"=>"failure");
        }
     }else{
        return array("data"=>$t,"msg"=>"已存在");
       }
    }else{
        	return array("data"=>[],"msg"=>"插入失败");
        }
      }else{
        	return false;
        }
	}

	public function actionAllteafile(){
		$request = \Yii::$app->request;
		$currentpage=$request ->post('page');
		$pageSize=8;
		$query=(new Query())
		      ->select('*')
		      ->from('teacher_info')
		      ->andWhere(['status'=>1])
		      ->all();
	    $query1=(new Query())
		      ->select('*')
		      ->from('teacher_info')
		      ->andWhere(['status'=>1]);
		 $querycount=clone $query1;
		 $totalCount=$querycount->count();
		 $result=$query1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
		if($result){
			$data=[];
			for($i=0;$i<count($result);$i++){
			$username=$result[$i]['username'];
		    $record=(new Query())
		           ->select('*')
		           ->from('teacher_files')
		           ->andWhere(['username'=>$username])
		           ->andWhere(['type'=>1])
		           ->andWhere(['status'=>1])
		           ->all();
		    $recordnum=count($record);
		    $data[$i]['username']=$result[$i]['username'];
		    $data[$i]['tName']=$result[$i]['tName'];
		    $data[$i]['job_num']=$result[$i]['job_num'];
		    $data[$i]['number']=$recordnum;
		}
		return array("data"=>[$data,$pageNum],"msg"=>"success");
		}else{
			return false;
		}
	}

	public function actionTeafile(){
		$request = \Yii::$app->request;
		$name=$request ->post('name');
		$query=(new Query())
		      ->select('*')
		      ->from('teacher_info')
		      ->andWhere(['tName'=>$name])
		      ->andWhere(['status'=>1])
		      ->all();
		 if($query){
		 	$data=[];
		 	for($j=0;$j<count($query);$j++){
		 	$username=$query[$j]['username'];
		 	$number=(new Query)
		 	       ->select('*')
		 	       ->from('teacher_files')
		 	       ->andWhere(['username'=>$username])
		 	       ->andWhere(['type'=>1])
		 	       ->andWhere(['status'=>1] OR ['status'=>2])
		 	       ->all();
		 	$data[$j]['username']=$query[$j]['username'];       
		 	$data[$j]['tName']=$name;
		 	$data[$j]['job_num']=$query[$j]['job_num'];
		 	$data[$j]['number']=count($number);
		 }
		 return array("data"=>$data,"msg"=>"success");
		 }else{
		 	return false;
		 }
	}

	public function actionStufile(){
		//查看某教师指导学生的所有数据
		$request = \Yii::$app->request;
		$name=$request ->post('name');//指导教师的姓名
		$student=(new Query())
		        ->select('*')
		        ->from('arrange_info')
		        ->andWhere(['tName'=>$name])
		        ->andWhere(['ischecked'=>1])
		        ->andWhere(['status'=>1])
		        ->all();
		if($student){
			$jno=[];
			for($a=0;$a<count($student);$a++){
				//先获取有几个教师，根据工号
			    array_push($jno,$student[$a]['job_num']);
			 for($b=$a+1;$b<count($student);$b++){
			 	if($student[$b]['job_num']==$student[$a]['job_num']){
			 		$a++;
			 	}else{
			 		break;
			 	}
			 }
			}
			//获取各自指导学生的数据
			$data=[];
			for($i=0;$i<count($jno);$i++){
				$job_num=$jno[$i];
				$member=(new Query())
				       ->select('*')
				       ->from('arrange_info')
				       ->andWhere(['job_num'=>$job_num])
				       ->andWhere(['ischecked'=>1])
				       ->andWhere(['status'=>1])
				       ->all();
				for($j=0;$j<count($member);$j++){
				 $username=$member[$j]['username'];//学生用户名
				//先记录数据
				$record=(new Query())
				       ->select('*')
				       ->from('record_files')
				       ->andWhere(['type'=>1])
				       ->andWhere(['status'=>1])
				       ->andWhere(['username'=>$username])
				       ->all();
				//文档数据
				$file=(new Query())
				     ->select('*')
				     ->from('probation_files')
				     ->andWhere(['type'=>1])
				     ->andWhere(['username'=>$username])
				     ->andWhere(['status'=>1] OR ['status'=>2])
				     ->all();
				//视频数据
				$video=(new Query())
				      ->select('*')
				      ->from('probation_videos')
				      ->andWhere(['type'=>1])
				      ->andWhere(['username'=>$username])
				      ->andWhere(['status'=>1])
				      ->all();
				$data[$i][$j]['sno']=$member[$j]['sno'];
				$data[$i][$j]['sName']=$member[$j]['sName'];
				$data[$i][$j]['username']=$member[$j]['username'];
				$data[$i][$j]['num1']=count($record);
				$data[$i][$j]['num2']=count($file);
				$data[$i][$j]['num3']=count($video);
				$data[$i][$j]['tName']=$name;
				$data[$i][$j]['job_num']=$job_num;
				}
			}
			return array("data"=>$data,"msg"=>"success");
		}else{
			return false;
		}
	}

	public function actionRecorddetail(){
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
             ->select('*')
             ->from('teacher_files')
             ->andWhere(['username'=>$username])
             ->andWhere(['type'=>1])
             ->andWhere(['status'=>1] OR ['status'=>2])
             ->all();
         if($query){
        	$path=[];
        	for($j=0;$j<count($query);$j++){
        		$url=explode(':',$query[$j]['path']);
        		$dir=explode('/',$url[1]);
        		$arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        		$final=implode('/',$arr);
        		$path[$j]['url']=$final;
        	}
        	return array("data"=>[$query,$path],"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionAllstufile(){
		$request = \Yii::$app->request;
		$currentpage=$request->post('page');
		$pageSize=8;
		$member=(new Query())
		       ->select('*')
		       ->from('arrange_info')
		       ->andWhere(['ischecked'=>1])
		       ->andWhere(['status'=>1])
		       ->all();
	    $member1=(new Query())
		       ->select('*')
		       ->from('arrange_info')
		       ->andWhere(['ischecked'=>1])
		       ->andWhere(['status'=>1]);
		 $querycount=clone $member1;
		 $totalCount=$querycount->count();
		 $result=$member1->offset($pageSize*($currentpage-1))->limit($pageSize)->all();
         $pageNum = ceil($totalCount/8);
		if($result){
			$data=[];
			for($i=0;$i<count($result);$i++){
				$username=$result[$i]['username'];//学生的用户名
				//先记录数据
				$record=(new Query())
				       ->select('*')
				       ->from('record_files')
				       ->andWhere(['type'=>1])
				       ->andWhere(['status'=>1])
				       ->andWhere(['username'=>$username])
				       ->all();
				//文档数据
				$file=(new Query())
				     ->select('*')
				     ->from('probation_files')
				     ->andWhere(['type'=>1])
				     ->andWhere(['username'=>$username])
				     ->andWhere(['status'=>1] OR ['status'=>2])
				     ->all();
				//视频数据
				$video=(new Query())
				      ->select('*')
				      ->from('probation_videos')
				      ->andWhere(['type'=>1])
				      ->andWhere(['username'=>$username])
				      ->andWhere(['status'=>1])
				      ->all();
			  $data[$i]['username']=$username;//学生用户名
			  $data[$i]['sno']=$result[$i]['sno'];//学号
			  $data[$i]['sName']=$result[$i]['sName'];//学生姓名
			  $data[$i]['tName']=$result[$i]['tName'];//教师姓名
			  $data[$i]['job_num']=$result[$i]['job_num'];//工号
			  $data[$i]['num1']=count($record);
			  $data[$i]['num2']=count($file);
			  $data[$i]['num3']=count($video);
			}
         return array("data"=>[$data,$pageNum],"msg"=>"success");
		}else{
			return false;
		}
	}


}