<?php @session_start(); ?>
<?php include('database_connect.php'); ?>

<?php
if(isset($_GET['referral']) && $_GET['referral'] != "")
{
  //$_GET['referral'];
  //exit();
  $obj = new dataInsertQuery();
  $obj->$_GET['referral']();
}
?>

<?php
class dataInsertQuery
{


public function saveNewUser()
{
  $newUser = array('email','password','f_name','l_name','age','gender','ph_no','profession','college','subject','university','country','state','user_type');
  
  $query = "INSERT INTO `user` (";
  $value = " VALUES (";
  foreach($newUser as $val)
   {
     if(isset($_POST[$val])  && $_POST[$val] != ""){
	   $query = $query."`".$val."`".",";
	   $value = $value."'".$_POST[$val]."'".",";
	   }
   }
   $query = $query."`user_type`,`status`)";
   $value = $value."'Normal','Active');";
   $query = $query.$value;
   mysql_query($query);
   
   $sql = "select * from `user` where `email` = '".$_POST['email']."' and `f_name`='".$_POST['f_name']."' and `l_name`='".$_POST['l_name']."' ";
   $res = mysql_query($sql);
   $row = mysql_fetch_array($res);
 
   $makeProfFolderPath = "../users/".$_POST['profession'];
   $makeUserFolderPath = $makeProfFolderPath."/".$row['f_name']."_".$row['l_name']."_".$row['uid']; 

   //First check the profession folder is exists or not, if not then create one, eg. student or Academician
   if(file_exists($makeProfFolderPath) && is_dir($makeProfFolderPath))
   {
     mkdir($makeUserFolderPath, 0777);     @chmod($makeUserFolderPath, 0777);
   }
   else
   {
     mkdir($makeProfFolderPath, 0777);     @chmod($makeProfFolderPath, 0777);
     mkdir($makeUserFolderPath, 0777);     @chmod($makeUserFolderPath, 0777);
   }
   
   header('Location: ../registration.php?msg=success');
}



public function ajxFeedbackSave()
{
  if(isset($_SESSION['uid']))
  {
     $name = $_SESSION['f_name']." ".$_SESSION['l_name'];
	 $uid      = $_SESSION['uid'];
	 $email  = $_SESSION['email'];
	 $user_type = $_SESSION['user_type'];
	 $department = "";
  }
  else
  {
     $name = $_GET['name'];
	 $uid      = "";
	 $email  = $_GET['email'];
	 $user_type = "Guest";
	 $department = $_GET['department'];
  }
  //exit();
  $sql = "insert into feedbacks set
                                                   expId ='".$_GET[ 'expId']."',
												   name = '".mysql_real_escape_string($name)."',
												   email = '".mysql_real_escape_string($email)."',
												   uid = '".$uid."',
												   user_type= '".$user_type."',
												   department = '".mysql_real_escape_string($department)."',
												   expPerformance = '".$_GET['expPerformance']."',
												   interactionCtrl = '".$_GET['interactionCtrl']."',
												   simulatorCmpr = '".$_GET['simulatorCmpr']."',
												   dataMrmnt = '".$_GET['dataMrmnt']."',
												   manuals =  '".$_GET['manuals']."',
												   expObjectives =  '".$_GET['expObjectives']."',
                                                   expResult = '".$_GET['expResult']."',
												   understandExp = '".$_GET['understandExp']."',
												   sysHelp = '".mysql_real_escape_string($_GET['sysHelp'])."',
												   sysPblm = '".mysql_real_escape_string($_GET['sysPblm'])."',
												   tellUs = '".mysql_real_escape_string($_GET['tellUs'])."',
												   confidence = '".mysql_real_escape_string($_GET['confidence'])."',
												   expMotivation = '".mysql_real_escape_string($_GET['expMotivation'])."',
												   stepmethdExp =  '".$_GET['stepmethdExp']."',
												   actualLab =  '".$_GET['actualLab']."',
                                                   instructorAbs = '".$_GET['instructorAbs']."',
												   withoutInt = '".$_GET['withoutInt']."',
												   analyzeData = '".$_GET['analyzeData']."',
												   stepmethdBeforeRun = '".$_GET['stepmethdBeforeRun']."',
												   compareRes = '".$_GET['compareRes']."',
												   performExp = '".mysql_real_escape_string($_GET['performExp'])."',
												   threePblms = '".mysql_real_escape_string($_GET['threePblms'])."', 
												   threeThings = '".mysql_real_escape_string($_GET['threeThings'])."',                  
												   moreScope =  '".$_GET['moreScope']."'  ";
	mysql_query($sql);
	echo "Thank you very much for your comment";
}


public function user_track($current_page_url, $ref)
{

	$uid       = $_SESSION['uid'];
	$date = date("Y-m-d");
	$time = date("H:i:s");  
	$activities  = $current_page_url." -- ".$time;

	$query = "select * from `user_log` where `uid`='".$_SESSION['uid']."' and `date`='".date("Y-m-d")."' ";
	$res   = mysql_query($query);
	if(mysql_num_rows($res) > 0)
	{
	  $row = mysql_fetch_array($res);
	  $query = "update `user_log` set
	     `activities` ='".$row['activities']." | ".$activities."' where `uid`='".$_SESSION['uid']."' and `date`='".date("Y-m-d")."' ";
	}

	else
	{
	$query = "insert into `user_log` set
		 `uid` ='".$uid."', 
		 `activities` ='".$activities."', 
		 `date` ='".$date."'";
	}
	mysql_query($query);
}


}
?>
