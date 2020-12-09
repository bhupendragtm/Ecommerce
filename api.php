<?php
error_reporting(1);
header("Content-Type: application/json");
header("Expires: 0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require('conn.php');
$response = array();
$db = new Database();
$db->connect();

/* 	API methods
	API Written By Thulo Technology Pvt.Ltd
	For Thulo IMS
	Permission: Select, Insert & Update Only
	Last Updated Date: 2020: 04: 02
	------------------
	1.  get_businessdetailbyuid()
	2.  add_businesswithuser()
	3.  update_business()
	4.  get_userbybusinessid()
	5.  add_userbybusiness()
	6.  update_user()
	7.  update_userpassword()
	8.  get_salesbydate()
	9.  add_sales()
	10. update_sales()
	11. get_salesbybillno()
	12. get_salesbybillname()
	13. get_purchasebydate()
	14. add_purchase()
	15. update_purchases()
	16. get_sellsbybillno()
	17. searchpurchasebybillname()
	18. get_totalsalesandpurchasebetweendates()
	19. get_totalprofilterfromdates()
	20. get_totalpurchase()
	21. get_totalsales()
	22. add_history()
    23. get_history()
    24. get_historyofbusinessfromadmin()
    25. add_businesswithuser()
    26. check_login()
    27. delete_user()
    28. get_userdetailbyuid()
    29. get_salesbydatebybusiness()
	30. get_salesbyid()
	31. get_purchasebyid()
	32. get_salesbycatrgory()
	33. get_purchasebycategory()


	*/


//2.  add_businesswithuser()
if(isset($_POST['access_key']) &&
 isset($_POST['add_businesswithuser']) &&
 isset($_POST['b_name']) &&
 isset($_POST['b_phone']) &&
 isset($_POST['u_fname']) &&
 isset($_POST['u_lname']) &&
 isset($_POST['u_name']) &&
 isset($_POST['u_email']) &&
 isset($_POST['u_password'])

){
	/*	Parameters to be passed
		1. access_key
		2. add_businesswithuser
		3. b_name
		4. b_phone
		5. u_fname
		6. u_lname
		7. u_name
		8. u_email
		9. u_password
	*/
	if($access_key != $_POST['access_key']){
		$response['error'] = "true";
		$response['message'] = "Invalid Access Key";
		print_r(json_encode($response));
		return false;
	}
	$u_name = $_POST['u_name'];

$sql = "SELECT u_id FROM users WHERE u_name='$u_name';";
$db->sql($sql);
$res = $db->getResult();
if(empty($res)){
		// Self Code
		$bname  = $_POST['b_name'];
		$bphone = $_POST['b_phone'];
	
	
	$sql = "INSERT INTO business(b_name, b_phone, b_website, b_establishengdate, b_address,  b_numberofstaff, b_isadded, b_logo, b_remarks) VALUES ('$bname', '$bphone', 'N/A','N/A','N/A','2000-01-01','N/A','N/A','N/A'); ";
	$db->sql($sql);
	//$res = $db->getResult();
	$sql = "SELECT LAST_INSERT_ID() as b_id;";
	$db->sql($sql);
	$res = $db->getResult();
	if(empty($res)){
		$response['error'] = "true";
		$response['message'] = "No any data found!";
		print_r(json_encode($response));
	}else{
		$tempRow = array();
		$rows = array();
		foreach($res as $row){
			$lastid = $row['b_id'];
			//echo $lastid;
	
			$data2 = array(
				'u_fname' => $_POST['u_fname'],
				'u_lname' => $_POST['u_lname'],
				'u_name' => $_POST['u_name'],
				'u_email' => $_POST['u_email'],
				'u_status' => 'Registered',
				'u_role' => 'Admin',
				'u_contact' => $bphone,
				'business_id' => $lastid,
				'u_password' => $_POST['u_password'],
				'u_password2' => 'N/A',
				'u_remarks' => 'N/A',
				'u_image' => 'N/A',
				'u_isdeleted' => '0',
				'u_isdeactivated' => '0'
			);
			$result =  $db->insert('users',$data2);

			if($result){
				$response['error'] = "false";
				$response['message'] = "Your Business is Successfully Registered. Now You Can Login.";
				print_r(json_encode($response));
				return true;
			}else {
				$response['error'] = "true";
				$response['message'] = "Failed to add data";
				print_r(json_encode($response));
				return false;
				}
			}
		}	
}else{
		$response['error'] = "true";
		$response['message'] = "Username is not available. Try using different username.";
		print_r(json_encode($response));

	}

}

// 3. update_business()
if(isset($_POST['access_key']) &&
 isset($_POST['update_business']) &&
 isset($_POST['b_id']) &&
 isset($_POST['b_name']) &&
 isset($_POST['b_email']) &&
 isset($_POST['b_website']) &&
 isset($_POST['b_phone']) &&
 isset($_POST['b_establishengdate']) &&
 isset($_POST['b_pan']) &&
 isset($_POST['b_numberofstaff']) &&
 isset($_POST['b_address']) &&
 isset($_POST['b_remarks'])
){
	/*	Parameters to be passed
		1. access_key
		2. update_customerstatus
		3. b_id
		4. b_name
		5. b_email
		6. b_website
		7. b_phone
		8. b_establishengdate
		9. b_pan
		10. b_numberofstaff
		11. b_address
		12. b_remarks
	*/

	$b_id = $_POST['b_id'];

	if($access_key != $_POST['access_key']){
		$response['error'] = "true";
		$response['message'] = "Invalid Access Key";
		print_r(json_encode($response));
		return false;
	}
	// Self Code
	$data = array(
		'b_name' => $_POST['b_name'],
		'b_email' => $_POST['b_email'],
		'b_website' => $_POST['b_website'],
		'b_establishengdate' => $_POST['b_establishengdate'],
		'b_pan' => $_POST['b_pan'],
		'b_phone' => $_POST['b_phone'],
		'b_numberofstaff' => $_POST['b_numberofstaff'],
		'b_address' => $_POST['b_address'],
		'b_remarks' => $_POST['b_remarks']
	);

	$result =  $db->update('business',$data,"b_id='$b_id'");
	if($result){
		$response['error'] = "false";
		$response['message'] = "Business Updated.";
		print_r(json_encode($response));
		return true;
	}else {
		$response['error'] = "true";
		$response['message'] = "Failed to update data";
		print_r(json_encode($response));
		return false;
	}

}



//8.  get_salesbydate()
if(isset($_POST['access_key']) &&
   isset($_POST['get_salesbydate']) &&
   isset($_POST['s_date1']) && 
   isset($_POST['s_date2']) && 
   isset($_POST['s_userid']))
{
	/*	Parameters to be passed
		1. access_key
		2. get_salesbydate
		3. s_date1
		4. s_date2
		5. s_userid
	*/
	$date1 = $_POST['s_date1'];
	$date2 = $_POST['s_date2'];
	$userid = $_POST['s_userid'];

	if($access_key != $_POST['access_key']){
		$response['error'] = "true";
		$response['message'] = "Invalid Access Key";
		print_r(json_encode($response));
		return false;
	}
	$sql = "SELECT s_id, s_name, s_phone, s_billno, s_category, s_status, s_amount, s_vatamount, s_remarks, s_isadded, s_isupdated, s_userid FROM sales JOIN users ON sales.s_userid = users.u_id JOIN business ON users.business_id = business.b_id AND business.b_id = (SELECT business_id FROM users WHERE u_id ='$userid') AND s_date BETWEEN '$date1' AND '$date2';";
	$db->sql($sql);
	$res = $db->getResult();
	if(empty($res)){
		$response['error'] = "true";
		$response['message'] = "No any data found!";
		print_r(json_encode($response));
	}else{
		$tempRow = array();
		$rows = array();
		foreach($res as $row){
			$tempRow['s_id'] = $row['s_id'];
			$tempRow['s_name'] = $row['s_name'];
			$tempRow['s_phone'] = $row['s_phone'];
			$tempRow['s_billno'] = $row['s_billno'];
			$tempRow['s_category'] = $row['s_category'];
			$tempRow['s_status'] = $row['s_status'];
			$tempRow['s_amount'] = $row['s_amount'];
			$tempRow['s_vatamount'] = $row['s_vatamount'];
			$tempRow['s_remarks'] = $row['s_remarks'];
			$tempRow['s_isadded'] = $row['s_isadded'];
			$tempRow['s_isupdated'] = $row['s_isupdated'];
			$tempRow['s_userid'] = $row['s_userid'];
			$rows[] = $tempRow;
		}
		$response['error'] = "false";
		$response['data'] = $rows;
		print_r(json_encode($response));
	}
}



?>