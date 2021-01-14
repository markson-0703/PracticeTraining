<?php
namespace backend\module\evaluation\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\ArrangeInfo;
use common\models\ProbationAssess;
use common\models\StudentInfo;
use common\models\TutorInfo;
use common\models\TeacherInfo;
use common\models\RecordFiles;
use TCPDF;
use common\config\tcpdf_include;

class EvaluationController extends Controller{
	public function actionIndex(){
		return "评定部分";
	}

	public function actionStubaseinfo(){
		$request = \Yii::$app->request;
        $username=$request->post('username');
        //先去找系统中已经录入的信息
        $query=(new Query())
              ->select('*')
              ->from('arrange_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['ischecked'=>1])
              ->andWhere(['type'=>1])
              ->andWhere(['status'=>1])
              ->one();
        //再去找评定信息
        $result=(new Query())
              ->select('*')
              ->from('student_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['status'=>1])
              ->one();
        $major=$result['majorName'];
        $query1=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['username'=>$username])
               ->andWhere(['<>','status', 0])
               ->one();
        if($query){
        	return array("data"=>[$query,$query1,$major],"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionSelfassess(){
		//提交自我评价
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $sName=$request->post('sName');
        $major=$request->post('major');
        $school=$request->post('school');
        $grade=$request->post('grade');
        $subject=$request->post('subject');
        $section=$request->post('section');
        $startTime=$request->post('startTime');
        $endTime=$request->post('endTime');
        $selfevaluation=$request->post('selfevaluation');
        //先检测是否重复提交了
        $query=(new Query())
              ->select('*')
              ->from('probation_assess')
              ->andWhere(['username'=>$username])
              ->one();
        if($query==null){
        	//插入
        	$currentId=(new Query())
    			  ->select('*')
    			  ->from('probation_assess')
                  ->max('eId');
            $id=$currentId+1;
            $result=\Yii::$app->db->createCommand()->insert('probation_assess',
            	[
            	'eId'=>$id,
            	'username'=>$username,
            	'sName'=>$sName,
            	'major'=>$major,
            	'school'=>$school,
            	'grade'=>$grade,
            	'subject'=>$subject,
            	'section'=>$section,
            	'startTime'=>$startTime,
            	'endTime'=>$endTime,
            	'selfevaluation'=>$selfevaluation,
            	'status'=>1//自评结束状态由0变为1
            	])->execute();
            if($result){
        	return array("data"=>$result,"msg"=>"success");
            }else{
        	return array("data"=>[],"msg"=>"插入数据失败");
           }
        }else{
        	return array("data"=>[],"msg"=>"内容已存在");
        }
	}

	public function actionAssessinfo(){
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('probation_assess')
              ->andWhere(['username'=>$username])
              ->andWhere(['<>','status', 0])
              ->one();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionCheckleader(){
		//判断组长身份
		$request = \Yii::$app->request;
        $username=$request->post('username');
        $query=(new Query())
              ->select('*')
              ->from('arrange_info')
              ->andWhere(['username'=>$username])
              ->andWhere(['ischecked'=>1])
              ->andWhere(['type'=>1])
              ->andWhere(['status'=>1])
              ->one();
        if($query){
        	return array("data"=>$query,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
	}

	public function actionMymember(){
		//获取自己的组员
		$request = \Yii::$app->request;
        $school=$request->post('school');
        $tName=$request->post('tName');
        $query=(new Query())
               ->select('*')
               ->from('arrange_info')
               ->andWhere(['tName'=>$tName])
               ->andWhere(['school_name'=>$school])
               ->andWhere(['ischecked'=>1])
               ->andWhere(['type'=>1])
               ->andWhere(['status'=>1])
               ->all();
        if($query){
        	$member=[];
        	for($i=0;$i<count($query);$i++){
        		$member[$i]['username']=$query[$i]['username'];
        		$status=(new Query())
        		       ->select('*')
        		       ->from('probation_assess')
        		       ->andWhere(['username'=>$member[$i]['username']])
        		       ->one();
        		$member[$i]['status']=$status['status'];
        		$member[$i]['sno']=$query[$i]['sno'];
        		$member[$i]['sName']=$query[$i]['sName'];
        		$member[$i]['tName']=$query[$i]['tName'];
        	}
        	return array("data"=>$member,"msg"=>"success");
        }else{
        	return false;
        }
	}

  public function actionTotalsituation(){
    //小组长查看组员的所有评价情况
    $request = \Yii::$app->request;
    $username=$request->post('uname');
    $evaluation=(new Query())
               ->select('*')
               ->from('probation_assess')
               ->andWhere(['username'=>$username])
               ->one();
    if($evaluation){
      return array("data"=>$evaluation,"msg"=>"success");
    }else{
      return array("data"=>[],"msg"=>"failure");
    }
  }

	public function actionGroupevaluation(){
    //小组评价上传
    $request = \Yii::$app->request;
    $username=$request->post('username');//当前评价的学生
    $groupevaluation=$request->post('content');//小组评价内容
    $leader=$request->post('leader');//组长
    //内容更新
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
    	[
    	'groupevaluation'=>$groupevaluation,
    	'leader'=>$leader,
    	'status'=>2//小组评价结束状态由1变为2
    	],'username=:username',[':username'=>$username])->execute();
    if($update){
        	 return array("data"=>$groupevaluation,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionGroupupdate(){
    //小组评价更新
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $content=$request->post('content');
    //内容更新，除了内容，其他不做改变
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
      [
      'groupevaluation'=>$content
      ],'username=:username',[':username'=>$username])->execute();
    if($update){
           return array("data"=>$content,"msg"=>"success");
        }
        else{
          return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionTutorevaluation(){
   	//校外评价上传
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $tutorevaluation=$request->post('content');
    $tutor=$request->post('tutor');
   //内容更新
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
    	[
    	'tutorevaluation'=>$tutorevaluation,
    	'tutor'=>$tutor,
    	'status'=>3//校外评价结束状态由2变为3
    	],'username=:username',[':username'=>$username])->execute();
       if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }

  public function actionTutorupdate(){
    //校外评价更新
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $content=$request->post('content');
    //内容更新，除了内容，其他不做改变
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
      [
      'tutorevaluation'=>$content
      ],'username=:username',[':username'=>$username])->execute();
    if($update){
           return array("data"=>$content,"msg"=>"success");
        }
        else{
          return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionTeacherupdate(){
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $content=$request->post('content');
    $finishTime=date('YmdHis');
    //内容更新，除了内容，其他不做改变
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
      [
      'teacherevaluation'=>$content,
      'finishTime'=>$finishTime
      ],'username=:username',[':username'=>$username])->execute();
    if($update){
         //先获取数据
         $detail=(new Query())
                ->select('*')
                ->from('probation_assess')
                ->andWhere(['username'=>$username])
                ->one();
         $stuName=$detail['sName'];
         $time=$detail['finishTime'];
         //创建新的pdf文件
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);
        //设置文件基本信息
        $pdf->SetCreator(PDF_CREATOR); //设置创建者
        $pdf->SetAuthor('shiyu'); //设置作者
        $pdf->SetTitle($stuName.'评定结果'); //设置文件的title
        $pdf->SetSubject('student_evaluation'); //设置主题
        $pdf->SetKeywords('student,probation,evaluation'); //设置关键词
        $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习评定', $detail['sName'], array(0, 64, 255), array(0, 64, 128)); 
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
      $pdf->SetFont('droidsansfallback' , 'B', 14);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '见习生姓名:', 0, 1, 'L');
      $pdf->Write(0, $detail['sName'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '专业:', 0, 1, 'L');
      $pdf->Write(0, $detail['major'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习学校:', 0, 1, 'L');
      $pdf->Write(0, $detail['school'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习年级:', 0, 1, 'L');
      $pdf->Write(0, $detail['grade'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习科目:', 0, 1, 'L');
      $pdf->Write(0, $detail['subject'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '观摩节数:', 0, 1, 'L');
      $pdf->Write(0, $detail['section'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习开始时间:', 0, 1, 'L');
      $pdf->Write(0, $detail['startTime'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习结束时间:', 0, 1, 'L');
      $pdf->Write(0, $detail['endTime'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '自我评价（收获、优缺点）:', 0, 1, 'L');
      $pdf->Write(0, $detail['selfevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '见习小组评价:', 0, 1, 'L');
      $pdf->Write(0, $detail['groupevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '校外指导教师评估:', 0, 1, 'L');
      $pdf->Write(0, $detail['tutorevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '校内指导教师评估:', 0, 1, 'L');
      $pdf->Write(0, $detail['teacherevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $menu=str_replace('\\','/',Yii::$app->basePath);
      $path=$menu.'/assessment/'.$stuName;
      $filename=$stuName.'见习评定';
      $dir= $path.'见习评定.pdf';
      $pdf->Output($dir,'F'); //保存至当前file文件夹下
      // 将结果插入到数据表中
      $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['username'=>$username])
          ->one();
          if($query==null){
          $id=(new Query())
           ->select('*')
           ->from('record_files')
           ->max('uId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('record_files',
            [
            'uId'=>$currId,
            'type'=>5,
            'kind'=>1,
            'username'=>$username,
            'filename'=>$filename,
            'filedir'=>$dir,
            'createdTime'=>$time,
            'status'=>1
            ])->execute();
          }
           return array("data"=>$dir,"msg"=>"success");
          }
        else{
          return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionTeacherevaluation(){
   	//校内评价上传
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $teacherevaluation=$request->post('content');
    $teacher=$request->post('teacher');
    $finishTime=date('YmdHis');
    //内容更新
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
    	[
    	'teacherevaluation'=>$teacherevaluation,
    	'teacher'=>$teacher,
    	'finishTime'=>$finishTime,//评价结束时间
    	'status'=>4//校内评价结束状态由3变为4
    	],'username=:username',[':username'=>$username])->execute();
     if($update){
       	 //先获取数据
         $detail=(new Query())
                ->select('*')
                ->from('probation_assess')
                ->andWhere(['username'=>$username])
                ->one();
         $stuName=$detail['sName'];
         $time=$detail['finishTime'];
         //创建新的pdf文件
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);
        //设置文件基本信息
        $pdf->SetCreator(PDF_CREATOR); //设置创建者
        $pdf->SetAuthor('shiyu'); //设置作者
        $pdf->SetTitle($stuName.'评定结果'); //设置文件的title
        $pdf->SetSubject('student_evaluation'); //设置主题
        $pdf->SetKeywords('student,probation,evaluation'); //设置关键词
        $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习评定', $detail['sName'], array(0, 64, 255), array(0, 64, 128)); 
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
      $pdf->SetFont('droidsansfallback' , 'B', 14);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '见习生姓名:', 0, 1, 'L');
      $pdf->Write(0, $detail['sName'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '专业:', 0, 1, 'L');
      $pdf->Write(0, $detail['major'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习学校:', 0, 1, 'L');
      $pdf->Write(0, $detail['school'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习年级:', 0, 1, 'L');
      $pdf->Write(0, $detail['grade'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习科目:', 0, 1, 'L');
      $pdf->Write(0, $detail['subject'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '观摩节数:', 0, 1, 'L');
      $pdf->Write(0, $detail['section'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习开始时间:', 0, 1, 'L');
      $pdf->Write(0, $detail['startTime'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->Cell(0, 10, '见习结束时间:', 0, 1, 'L');
      $pdf->Write(0, $detail['endTime'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '自我评价（收获、优缺点）:', 0, 1, 'L');
      $pdf->Write(0, $detail['selfevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '见习小组评价:', 0, 1, 'L');
      $pdf->Write(0, $detail['groupevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '校外指导教师评估:', 0, 1, 'L');
      $pdf->Write(0, $detail['tutorevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $pdf->AddPage(); //增加一个页面
      $pdf->Cell(0, 10, '校内指导教师评估:', 0, 1, 'L');
      $pdf->Write(0, $detail['teacherevaluation'], '', 0, 'L', true, 0, false, false, 0);
      $menu=str_replace('\\','/',Yii::$app->basePath);
      $path=$menu.'/assessment/'.$stuName;
      $filename=$stuName.'见习评定';
      $dir= $path.'见习评定.pdf';
      $pdf->Output($dir,'F'); //保存至当前file文件夹下
      // 将结果插入到数据表中
      $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['username'=>$username])
          ->one();
          if($query==null){
          $id=(new Query())
           ->select('*')
           ->from('record_files')
           ->max('uId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('record_files',
            [
            'uId'=>$currId,
            'type'=>5,
            'kind'=>1,
            'username'=>$username,
            'filename'=>$filename,
            'filedir'=>$dir,
            'createdTime'=>$time,
            'status'=>1
            ])->execute();
          }
        	 return array("data"=>$dir,"msg"=>"success");
          }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }

   public function actionTraineeinfo(){
   	//校外导师获取见习生的基本情况
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    //去查找校外教师的基本信息
    $query=(new Query())
          ->select('*')
          ->from('tutor_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
    if($query){
    	//去查找带领学生的信息
    	$jno=$query['job_num'];
    	$member=(new Query())
    	       ->select('*')
    	       ->from('arrange_info')
    	       ->andWhere(['jno'=>$jno])
    	       ->andWhere(['ischecked'=>1])
    	       ->andWhere(['type'=>1])
    	       ->andWhere(['status'=>1])
    	       ->all();
    	//写入重要信息
        $data=[];
        for($i=0;$i<count($member);$i++){
        	$data[$i]['uname']=$member[$i]['username'];//学生的用户名
        	$data[$i]['sno']=$member[$i]['sno'];//学号
        	$data[$i]['sName']=$member[$i]['sName'];//姓名
        	$data[$i]['tName']=$member[$i]['tName'];//校内教师
        	$data[$i]['tutorName']=$member[$i]['tutor_name'];//校外教师
        	//获取评价信息
        	$assess=(new Query())
        	       ->select('*')
        	       ->from('probation_assess')
        	       ->andWhere(['username'=>$data[$i]['uname']])
        	       ->one();
        	$data[$i]['selfevaluation']=$assess['selfevaluation'];
        	$data[$i]['groupevaluation']=$assess['groupevaluation'];
        	$data[$i]['status']=$assess['status'];
        }
        return array("data"=>$data,"msg"=>"success");
    }else{
    	return false;
    }
   }

   public function actionStudentdata(){
       	//校内导师获取见习生的基本情况
    $request = \Yii::$app->request;
    $username=$request->post('username');
    //去查找校内教师的基本信息
    $query=(new Query())
          ->select('*')
          ->from('teacher_info')
          ->andWhere(['username'=>$username])
          ->andWhere(['status'=>1])
          ->one();
    if($query){
        //去查找带领学生的信息
        $jno=$query['job_num'];
    	$member=(new Query())
    	       ->select('*')
    	       ->from('arrange_info')
    	       ->andWhere(['job_num'=>$jno])
    	       ->andWhere(['ischecked'=>1])
    	       ->andWhere(['type'=>1])
    	       ->andWhere(['status'=>1])
    	       ->all();
    	  	//写入重要信息
        $data=[];
        for($i=0;$i<count($member);$i++){
        	$data[$i]['uname']=$member[$i]['username'];//学生的用户名
        	$data[$i]['sno']=$member[$i]['sno'];//学号
        	$data[$i]['sName']=$member[$i]['sName'];//姓名
        	$data[$i]['tName']=$member[$i]['tName'];//校内教师
        	$data[$i]['tutorName']=$member[$i]['tutor_name'];//校外教师
        	//获取评价信息
        	$assess=(new Query())
        	       ->select('*')
        	       ->from('probation_assess')
        	       ->andWhere(['username'=>$data[$i]['uname']])
        	       ->one();
        	$data[$i]['school']=$member[$i]['school_name'];
        	$data[$i]['grade']=$member[$i]['grade'];
        	$data[$i]['selfevaluation']=$assess['selfevaluation'];
        	$data[$i]['groupevaluation']=$assess['groupevaluation'];
        	$data[$i]['tutorevaluation']=$assess['tutorevaluation'];
        	$data[$i]['status']=$assess['status'];
        }
        return array("data"=>$data,"msg"=>"success"); 
    }else{
    	return false;
    }
   }

   public function actionSubmitsee1(){
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $query=(new Query())
          ->select('*')
          ->from('probation_assess')
          ->andWhere(['username'=>$username])
          ->one();
    if($query){
    	 return array("data"=>$query['tutorevaluation'],"msg"=>"success"); 
    }else{
    	return false;
    }
   }
    public function actionSubmitsee2(){
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $query=(new Query())
          ->select('*')
          ->from('probation_assess')
          ->andWhere(['username'=>$username])
          ->one();
    if($query){
    	 return array("data"=>$query['teacherevaluation'],"msg"=>"success"); 
    }else{
    	return false;
    }
   }

   public function actionEvaluationdata(){
   	$query=(new Query())
   	      ->select('*')
   	      ->from('probation_assess')
   	      ->andWhere(['status'=>4] OR ['status'=>5] )//代表全部评价环节结束
   	      ->all();
   	if($query){
   		return array("data"=>$query,"msg"=>"success"); 
   	}else{
   		return array("data"=>[],"msg"=>"failure"); 
   	}
   }

   public function actionFinalevaluation(){
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['username'=>$username])
          ->andWhere(['type'=>5])
          ->andWhere(['kind'=>1])
          ->andWhere(['status'=>1])
          ->one();
    if($query){
    	$url=explode(':',$query['filedir']);
        $dir=explode('/',$url[1]);
        $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
        $final =implode('/',$arr);
    	return array("data"=>$final,"msg"=>"success");
    }else{
    	return false;
    }
   }

   public function actionCheckevaluation(){
   	//管理员审核
   	$request = \Yii::$app->request;
    $username=$request->post('username');
    $update=\Yii::$app->db->createCommand()->update('probation_assess',
    	[
    	'status'=>5//管理员审核结束状态由4变为5
    	],'username=:username',[':username'=>$username])->execute();
       if($update){
        	 return array("data"=>$update,"msg"=>"success");
        }
        else{
        	return array("data"=>[],"msg"=>"failure");
        }
   }

}