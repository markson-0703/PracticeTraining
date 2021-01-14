<?php
namespace backend\module\record\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\ProbationRecord1;
use common\models\ProbationRecord5;
use common\models\RecordFiles;
use common\models\Templates;
use common\models\ProbationFiles;
use common\models\ProbationVideos;
use TCPDF;
use common\config\tcpdf_include;

class RecordController extends Controller{
	public function actionIndex()
	{
		return "记录部分";
	}

	public function actionSubmitrecord(){
		//提交见习记录
		$request = \Yii::$app->request;
		$username=$request ->post('username');
		$type=$request ->post('type');
		$date=$request ->post('date');
		$weekday=$request ->post('weekday');
		$className=$request ->post('className');
		$instructor=$request ->post('instructor');
		$theme=$request ->post('theme');
		$classform=$request ->post('classform');
		$content1=$request ->post('content1');  
		$content2=$request ->post('content2');
		$content3=$request ->post('content3');
		$submitTime=$request ->post('submitTime');
		//检测是否重复提交了
		$query=(new Query())
		      ->select('*')
		      ->from ('probation_record1')
		      ->andWhere(['username'=>$username])
              ->andWhere(['weekday'=>$weekday])
              ->andWhere(['className'=>$className])
		      ->one();
		if($query==null){
			//插入数据
		$currentId=(new Query())
    			  ->select('*')
    			  ->from('probation_record1')
                  ->max('id');
        $id=$currentId+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_record1',
        	[
        	'id'=>$id,
        	'username'=>$username,
        	'type'=>$type,
        	'date'=>$date,
        	'weekday'=>$weekday,
        	'className'=>$className,
        	'instructor'=>$instructor,
        	'theme'=>$theme,
        	'classform'=>$classform,
        	'content1'=>$content1,
        	'content2'=>$content2,
        	'content3'=>$content3,
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

   public function actionExportpdf(){
    //先获取数据
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $time=$request->post('time');
    $detail=(new Query())
           ->select('*')
           ->from('probation_record1')
           ->andWhere(['submitTime'=>$time])
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
    $pdf->SetHeaderData(0, PDF_HEADER_LOGO_WIDTH, '见习——课堂教学观摩记录', $detail['id'], array(0, 64, 255), array(0, 64, 128)); 
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
    $pdf->Cell(0, 10, '见习班级:', 0, 1, 'L');
    $pdf->Write(0, $detail['className'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '授课老师:', 0, 1, 'L');
    $pdf->Write(0, $detail['instructor'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '课题:', 0, 1, 'L');
    $pdf->Write(0, $detail['theme'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->Cell(0, 10, '课型:', 0, 1, 'L');
    $pdf->Write(0, $detail['classform'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '听课记录:', 0, 1, 'L');
    $pdf->Write(0, $detail['content1'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '听课反思:', 0, 1, 'L');
    $pdf->Write(0, $detail['content2'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->AddPage(); 
    $pdf->Cell(0, 10, '教师指导意见记录:', 0, 1, 'L');
    $pdf->Write(0, $detail['content3'], '', 0, 'L', true, 0, false, false, 0);
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $date=explode(' ',$time);
    $time0=explode('-',$date[0]);
    $time1 = explode(':',$date[1]);
    $t0 = implode('',$time0);
    $t1=implode('',$time1);
    $arr=array($t0,$t1);
    $t =implode('',$arr);
    $test=$menu.'/probation/'.$t;
    $filename= iconv("utf-8","gb2312",$test.'.pdf');
    $pdf->Output($filename,'F'); //保存至当前file文件夹下
    // 将结果插入到数据表中
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
            'kind'=>1,
            'username'=>$username,
            'filename'=>$t,
            'filedir'=>$filename,
            'createdTime'=>$time,
            'status'=>1
            ])->execute();
        if($result){
           return array("data"=>$result,"msg"=>"success");
        }else{
            return array("data"=>$t,"msg"=>"failure");
        }
    }else{
        return array("data"=>$t,"msg"=>"已存在");
    }
   }

   public function actionGetpdf()
   {
    $request = \Yii::$app->request;
    $id=$request->post('uId');
    $query=(new Query())
          ->select('*')
          ->from('record_files')
          ->andWhere(['uId'=>$id])
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

   public function actionUploadfiles(){
    //上传文件
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $menu=str_replace('\\','/',Yii::$app->basePath);
    $dir=$menu.'/uploads/';
    $currentTime=date('YmdHis');
    $filename = $_FILES['file']['name'];//文件名
    //获取后缀名
    $suffix=explode('.',$filename);
    $size = $_FILES['file']['size'];//文件大小
    $type = $_FILES['file']['type'];//文件类型
    $tmp_name = $_FILES['file']['tmp_name'];//文件被上传后在服务端储存的临时文件名
    $path=$menu.'/uploads/'.$currentTime.'.'.$suffix[1];
    //移动到新文件夹下
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
    //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('probation_files')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['submitTime'=>$currentTime])
          ->one();
    if($query==null){
        //插入新数据
        $id=(new Query())
           ->select('*')
           ->from('probation_files')
           ->max('fId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_files',
            [
            'fId'=>$currId,
            'type'=>1,
            'username'=>$username,
            'filename'=>$filename,
            'path'=>$path,
            'submitTime'=>$currentTime,
            'status'=>1
            ])->execute();
        if($result){
            return array('data'=>$result,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        }

    }else{
        return array('data'=>[],"msg"=>"文件已存在");
    }
   }

   public function actionUploadvideo(){
    //上传视频
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $nowTime=date('YmdHis');//当前日期
    $menu=str_replace('\\','/',Yii::$app->basePath);//根目录
    $dir=$menu.'/video/';
    $videoname = $_FILES['file']['name'];//视频名称
    $size = $_FILES['file']['size'];//视频大小
    $type = $_FILES['file']['type'];//视频类型
    $tmp_name = $_FILES['file']['tmp_name'];//视频被上传后在服务端储存的临时文件名
    $suffix=explode('.',$videoname);
    $path=$dir.$nowTime.'.'.$suffix[1];
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
     //将视频数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('probation_videos')
          ->andWhere(['videoname'=>$videoname])
          ->andWhere(['submitTime'=>$nowTime])
          ->one();
        if($query==null){
        //插入新数据
        $id=(new Query())
           ->select('*')
           ->from('probation_videos')
           ->max('vId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_videos',
            [
            'vId'=>$currId,
            'type'=>1,
            'username'=>$username,
            'videoname'=>$videoname,
            'path'=>$path,
            'submitTime'=>$nowTime,
            'status'=>1
            ])->execute();
        if($result){
            $url=explode(':',$path);
            $dir=explode('/',$url[1]);
            $arr=array($dir[3],$dir[4],$dir[5],$dir[6],$dir[7]);
            $final =implode('/',$arr);
            return array('data'=>$final,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        }

    }else{
        return array('data'=>[],"msg"=>"文件已存在");
    }
   }

   public function actionMytemplate(){
    //见习教学设计模板下载1
     $request = \Yii::$app->request;
     $kind=$request->post('kind');
     $query=(new Query())
           ->select('*')
           ->from('templates')
           ->andWhere(['type'=>1])
           ->andWhere(['kind'=>$kind])
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

   public function actionMytemplate1(){
    //见习总结模板下载2
     $request = \Yii::$app->request;
     $kind=$request->post('kind');
     $query=(new Query())
           ->select('*')
           ->from('templates')
           ->andWhere(['type'=>1])
           ->andWhere(['kind'=>$kind])
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

   public function actionInstruction(){
    //见习教学设计文件上传
     $request = \Yii::$app->request;
     $username=$request->post('username');
     $menu=str_replace('\\','/',Yii::$app->basePath);
     $dir=$menu.'/uploads/';
     $submitTime=date('YmdHis');//提交时间
     $filename = $_FILES['file']['name'];//文件名
    $suffix=explode('.',$filename);
    $size = $_FILES['file']['size'];//文件大小
    $type = $_FILES['file']['type'];//文件类型
    $tmp_name = $_FILES['file']['tmp_name'];//文件临时存放的位置
    //构建新的路径
    $path=$menu.'/uploads/'.$submitTime.'.'.$suffix[1];
    move_uploaded_file($_FILES['file']['tmp_name'],$path);
      //将数据存入到数据库
    $query=(new Query())
          ->select('*')
          ->from('probation_files')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['username'=>$username])
          ->one();
    if($query==null){
        //插入
        $id=(new Query())
           ->select('*')
           ->from('probation_files')
           ->max('fId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_files',
            [
            'fId'=>$currId,
            'type'=>1,
            'username'=>$username,
            'filename'=>$filename,
            'path'=>$path,
            'submitTime'=>$submitTime,
            'status'=>1
            ])->execute();
     if($result){
            return array('data'=>$result,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        }
    }else{
        return array('data'=>[],"msg"=>"文件已存在");
    }
   }

   public function actionGetrecorddata(){
     $request = \Yii::$app->request;
     $username=$request->post('username');
     $type=$request->post('type');
     $query=(new Query())
           ->select('*')
           ->from('record_files')
           ->andWhere(['username'=>$username])
           ->andWhere(['type'=>$type])
           ->andWhere(['status'=>1])
           ->all();
    if($query){
        return array('data'=>$query,"msg"=>"success");
    }else{
        return array('data'=>[],"msg"=>"failure");
    }
   }

   public function actionGetreview(){
    //获得见习教学设计部分的导师审阅意见
    $request = \Yii::$app->request;
    $username=$request->post('username');
    $submitTime=$request->post('submitTime');
    $query=(new Query())
          ->select('*')
          ->from('probation_record5')
          ->andWhere(['username'=>$username])
          ->andWhere(['submitTime'=>$submitTime])
          ->andWhere(['status'=>2])
          ->one();
     if($query){
        return array('data'=>$query,"msg"=>"success");
    }else{
        return array('data'=>[],"msg"=>"failure");
    }
   }

   public function actionConclusion(){
    //见习总结文件上传
     $request = \Yii::$app->request;
     $username=$request->post('username');
     $menu=str_replace('\\','/',Yii::$app->basePath);
     $dir=$menu.'/uploads/';
     $submitTime=date('YmdHis');//提交时间
     $filename = $_FILES['file']['name'];//文件名
     $size = $_FILES['file']['size'];//文件大小
     $type = $_FILES['file']['type'];//文件类型
     $tmp_name = $_FILES['file']['tmp_name'];//文件临时存放的位置
      //构建新的路径
     $path=$menu.'/uploads/'.$filename;
     move_uploaded_file($_FILES['file']['tmp_name'],$path);
      //将数据存入到数据库
     $query=(new Query())
          ->select('*')
          ->from('probation_files')
          ->andWhere(['filename'=>$filename])
          ->andWhere(['username'=>$username])
          ->one();
    if($query==null){
        //插入
         $id=(new Query())
           ->select('*')
           ->from('probation_files')
           ->max('fId');
        $currId=$id+1;
        $result=\Yii::$app->db->createCommand()->insert('probation_files',
            [
            'fId'=>$currId,
            'type'=>1,
            'username'=>$username,
            'filename'=>$filename,
            'path'=>$path,
            'submitTime'=>$submitTime,
            'status'=>2//表示提交的是总结
            ])->execute();
        if($result){
            return array('data'=>$submitTime,"msg"=>"success");
        }else{
            return array('data'=>[],"msg"=>"failure");
        } 
    }else{
        return array('data'=>[],"msg"=>"重复提交了");
    }
   }


}
