<?php
//ini_set('error_reporting', E_STRICT);
include ("conn.php");

	$action=$_GET['action'];
	
	if($action=='CheckChatAvail'){
		$data = json_decode(file_get_contents("php://input"));
		if($data->createnew==true){
			$selUser="SELECT * FROM `chat_groups` WHERE `chat_grp_nm`='$data->main'";
			$selResultUser=mysql_query($selUser);
			$rowSelUser = mysql_fetch_array($selResultUser,MYSQL_BOTH);
			$count = mysql_num_rows($selResultUser);
			if($count>0)
			{
				$obj->chatgrpavail=true;
				$obj->grpid=$rowSelUser['chat_grp_id'];
			}
			else{
				$insChatGrp = "INSERT INTO `chat_groups`(`chat_grp_nm`) VALUES ('$data->main')";
				mysql_query($insChatGrp);
				
				$selUser="SELECT * FROM `chat_groups` WHERE `chat_grp_nm`='$data->main'";
				$selResultUser=mysql_query($selUser);
				$rowSelUser = mysql_fetch_array($selResultUser,MYSQL_BOTH);
				$totGrps = $rowSelUser['chat_grp_id'];
				
				$insChatUsrMsgs = "INSERT INTO `chat_messages`(`chat_grp_id`, `chat_message`, `chat_date`) VALUES (".$totGrps.",'<span class=text-success><strong>".$data->usrname." Created chat room.</strong></span>','".$_SERVER['REQUEST_TIME']."')";
				mysql_query($insChatUsrMsgs);
				
				$obj->chatgrpavail=false;
				$obj->grpid=$totGrps;
			}
		}
		else{
			$selgrpid="SELECT * FROM `chat_groups` WHERE `chat_grp_nm`='$data->main'";
			$selResultGrp=mysql_query($selgrpid);
			$rowSelGrp = mysql_fetch_array($selResultGrp,MYSQL_BOTH);
			$obj->grpid=$rowSelGrp['chat_grp_id'];
			
			$count = mysql_num_rows($selResultGrp);
			if($count>0)
			{
				$selgrpmsgsid="SELECT * FROM `chat_messages` WHERE `chat_grp_id`=".$obj->grpid;
				$selResultMsgsGrp=mysql_query($selgrpmsgsid);
				$cnt=0;				
				while($row = mysql_fetch_array( $selResultMsgsGrp )) {
					$msg[$cnt]->usr=$row['chat_user'];
					$msg[$cnt]->msg=$row['chat_message'];
					$msg[$cnt]->chatdate=$row['chat_date'];
					$cnt++;
				}
				
				$obj->chatgrpavail=true;
				$obj->chtmsgs=$msg;				
			}
			 else{
				$obj->chatgrpavail=false; /* Chat group not available */
			} 
		}
		echo json_encode($obj);
	}
	
	if($action=='sendMsg'){
		$data = json_decode(file_get_contents("php://input"));
		$insChatUsrMsgs = "INSERT INTO `chat_messages`(`chat_grp_id`, `chat_user`, `chat_message`, `chat_date`) VALUES (".$data->grpId.",'".$data->usernm."','".$data->msgtext."','".$_SERVER['REQUEST_TIME']."')";
		mysql_query($insChatUsrMsgs);
		echo "done";
	}	
	
	if($action=='enterChat'){
		$data = json_decode(file_get_contents("php://input"));
		$insChatUsrMsgs = "INSERT INTO `chat_messages`(`chat_grp_id`, `chat_message`, `chat_date`) VALUES (".$data->grpid.",'<span class=text-info>".$data->usrname." entered chatroom.</span>','".$_SERVER['REQUEST_TIME']."')";
		mysql_query($insChatUsrMsgs);		
	}
?>