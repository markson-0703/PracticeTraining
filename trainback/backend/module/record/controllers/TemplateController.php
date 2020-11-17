<?php
namespace backend\module\record\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\ProbationRecord1;
use common\models\ProbationRecord2;
use common\models\ProbationRecord3;
use common\models\RecordFiles;
use common\models\ProbationFiles;
use common\models\ProbationVideos;
use TCPDF;
use common\config\tcpdf_include;

class TemplateController extends Controller{
	public function actionIndex()
	{
		return "见习记录模板部分";
	}

	public function actionSubmitrecord1(){
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$type=$request ->post('type');
		$date=$request ->post('date');
		$weekday=$request ->post('weekday');
		$section=$request ->post('section');
		$className=$request ->post('className');
		$instructor=$request ->post('instructor');
		$theme=$request ->post('theme');
		$classform=$request ->post('classform');
		$content1=$request ->post('content1');
		$content2=$request ->post('content2');
		$submitTime=$request ->post('submitTime');
		//检测是否重复提交
		$query=(new Query())
		      ->select('*')
		      ->from('probation_record2')
		      ->andWhere(['username'=>$username]&&['weekday'=>$weekday]&&['section'=>$section])
		      ->one();
		if($query==null){
		$currentId=(new Query())
    			  ->select('*')
    			  ->from('probation_record2')
                  ->max('id');
        $id=$currentId+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_record2',
        	[
        	'id'=>$id,
        	'username'=>$username,
        	'type'=>$type,
        	'date'=>$date,
        	'weekday'=>$weekday,
        	'section'=>$section,
        	'classname'=>$className,
        	'instructor'=>$instructor,
        	'theme'=>$theme,
        	'classform'=>$classform,
        	'content1'=>$content1,
        	'content2'=>$content2,
        	'submitTime'=>$submitTime,
        	'status'=>1
        	])->execute();
         if($result){
        	return array("data"=>$submitTime,"msg"=>"success");
        }else{
        	return array("data"=>[],"msg"=>"failure");
        }
    }else{
    	return array("data"=>[],"msg"=>"请勿重复插");
		}
    }

    public function actionSubmitrecord2(){
    	$request = \Yii::$app->request;
    	$username=$request ->post('username');
    	$school_name=$request ->post('school_name');
    	$className=$request ->post('className');
    	$date=$request ->post('date');
    	$content1=$request ->post('content1');
    	$content2=$request ->post('content2');
    	$submitTime=$request ->post('submitTime');
    	//检测是否重复提交
    	$query=(new Query())
    	      ->select('*')
    	      ->from('probation_record3')
    	      ->andWhere(['username'=>$username]&&['date'=>$date]&&['className'=>$className])
    	      ->one();
    	if($query==null){
    		//插入新内容
    		$currentId=(new Query())
    			  ->select('*')
    			  ->from('probation_record3')
                  ->max('id');
            $id=$currentId+1;
            $result=\Yii::$app->db->createCommand()->insert('probation_record3',
            	[
            	'id'=>$id,
            	'username'=>$username,
            	'schoolname'=>$school_name,
            	'className'=>$className,
            	'date'=>$date,
            	'content1'=>$content1,
            	'content2'=>$content2,
            	'submitTime'=>$submitTime,
            	'status'=>1
            	])->execute();
            if($result){
        	return array("data"=>$submitTime,"msg"=>"success");
            }else{
        	return array("data"=>[],"msg"=>"failure");
            }
        }else{
        	return array("data"=>[],"msg"=>"请勿重复插");
        }
    }

	public function actionExportpdf1(){
		//先获取数据
		$request = \Yii::$app->request;
		$time=$request ->post('time');
		$username=$request ->post('username');
		$detail=(new Query())
           ->select('*')
           ->from('probation_record2')
           ->andWhere(['submitTime'=>$time])
           ->andWhere(['status'=>1])
           ->one();
        //创建pdf
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);
        //设置文件基本信息
    $pdf->SetCreator(PDF_CREATOR); //设置创建者
    $pdf->SetAuthor('shiyu'); //设置作者
    $pdf->SetTitle($time); //设置文件的title
    $pdf->SetSubject('student_records'); //设置主题
    $pdf->SetKeywords('student,probation,record'); //设置关键词
    $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习——课堂教学观摩讨论记录', $detail['id'], array(0, 64, 255), array(0, 64, 128)); 
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
    $pdf->Cell(0, 10, '见习形式:', 0, 1, 'L');
    $pdf->SetFont('droidsansfallback' , 'B', 14);
    $pdf->Write(0, $detail['type'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '见习日期:', 0, 1, 'L');
    $pdf->Write(0, $detail['date'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '具体时间:', 0, 1, 'L');
    $pdf->Write(0, $detail['weekday'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '节次:', 0, 1, 'L');
    $pdf->Write(0, $detail['section'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '见习班级:', 0, 1, 'L');
    $pdf->Write(0, $detail['classname'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '授课老师:', 0, 1, 'L');
    $pdf->Write(0, $detail['instructor'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '课题:', 0, 1, 'L');
    $pdf->Write(0, $detail['theme'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '课型:', 0, 1, 'L');
    $pdf->Write(0, $detail['classform'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); //增加一个新页面
    $pdf->Cell(0, 10, '讨论记录:', 0, 1, 'L');
    $pdf->Write(0, $detail['content1'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); //增加一个新页面
    $pdf->Cell(0, 10, '教师指导意见记录:', 0, 1, 'L');
    $pdf->Write(0, $detail['content2'], '', 0, 'L', true, 0, false, false, 0);
    //根目录
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $date=explode(' ',$time);
    $time0=explode('-',$date[0]);
    $time1 = explode(':',$date[1]);
    $t0 = implode('',$time0);
    $t1=implode('',$time1);
    $arr=array($t0,$t1);
    $t =implode('',$arr);
    $path=$menu.'/probation/'.$t.'.pdf';
    $filedir=iconv("utf-8","gb2312",$path);
    $pdf->Output($filedir,'F'); //保存至目标文件夹下
     // 将pdf结果插入到数据表中
     $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['filename'=>$t])
          ->one();
    if($query==null){
    	//将pdf插入
    	  $id=(new Query())
           ->select('*')
           ->from('record_files')
           ->max('uId');
         $currId=$id+1;
         $result=\Yii::$app->db->createCommand()->insert('record_files',
         	[
         	'uId'=>$currId,
         	'type'=>1,
         	'kind'=>2,
         	'username'=>$username,
         	'filename'=>$t,
         	'filedir'=>$filedir,
         	'createdTime'=>$time,
            'status'=>1
         	])->execute();
        if($result){
           return array("data"=>$result,"msg"=>"success");
        }else{
            return array("data"=>$filedir,"msg"=>"failure");
        }
    }else{
    	return array("data"=>$t,"msg"=>"已存在");
    }
  }

  public function actionExportpdf2(){
//先获取数据
		$request = \Yii::$app->request;
		$time=$request ->post('time');
		$username=$request ->post('username');
		$detail=(new Query())
           ->select('*')
           ->from('probation_record3')
           ->andWhere(['submitTime'=>$time])
           ->andWhere(['status'=>1])
           ->one();
       //创建pdf
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, true);
       //设置文件基本信息
    $pdf->SetCreator(PDF_CREATOR); //设置创建者
    $pdf->SetAuthor('shiyu'); //设置作者
    $pdf->SetTitle($time); //设置文件的title
    $pdf->SetSubject('student_records'); //设置主题
    $pdf->SetKeywords('student,probation,record'); //设置关键词
    $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习——班级管理见习记录', $detail['id'], array(0, 64, 255), array(0, 64, 128)); 
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
    $pdf->Cell(0, 10, '见习学校:', 0, 1, 'L');
    $pdf->Write(0, $detail['schoolname'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '见习班级:', 0, 1, 'L');
    $pdf->Write(0, $detail['className'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '日期:', 0, 1, 'L');
    $pdf->Write(0, $detail['date'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); //增加一个新页面
    $pdf->Cell(0, 10, '班级管理见习记录:', 0, 1, 'L');
    $pdf->Write(0, $detail['content1'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); //增加一个新页面
    $pdf->Cell(0, 10, '总结反思:', 0, 1, 'L');
    $pdf->Write(0, $detail['content2'], '', 0, 'L', true, 0, false, false, 0);
    //将生成的PDF文件存放到某文件夹下
    //根目录
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $date=explode(' ',$time);
    $time0=explode('-',$date[0]);
    $time1 = explode(':',$date[1]);
    $t0 = implode('',$time0);
    $t1=implode('',$time1);
    $arr=array($t0,$t1);
    $t =implode('',$arr);
    $path=$menu.'/probation/'.$t.'.pdf';
    $filedir=iconv("utf-8","gb2312",$path);
    $pdf->Output($filedir,'F'); //保存至目标文件夹下
     // 将pdf结果插入到文档数据中
     $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['filename'=>$t]&&['filename'=>$t])
          ->one();
  }

}