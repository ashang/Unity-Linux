<?php/* Anurag Bhandari * 24-07-09 * * This script handles the package submission part of adding to FlySpray. * */  $connection = mysql_connect("localhost","*****","*****");  mysql_select_db("*****",$connection);  $time = time();  $_POST['bureaucracy'][5] = mysql_real_escape_string($_POST['bureaucracy'][5]);  if(get_magic_quotes_gpc())  {    $package_name = stripslashes($_POST['bureaucracy'][1]);    $package_website = stripslashes($_POST['bureaucracy'][2]);    $package_type = $_POST['bureaucracy'][3];    $package_version = stripslashes($_POST['bureaucracy'][4]);    $package_desc = stripslashes($_POST['bureaucracy'][5]);    $spam_protect = stripslashes($_POST['bureaucracy'][10]);  }  else  {    $package_name = $_POST['bureaucracy'][1];    $package_website = $_POST['bureaucracy'][2];    $package_type = $_POST['bureaucracy'][3];    $package_version = $_POST['bureaucracy'][4];    $package_desc = $_POST['bureaucracy'][5];    $spam_protect = $_POST['bureaucracy'][10];  }  $body = "Package Name: $package_name\n\nVersion: $package_version\n\nWebsite: $package_website\n\nType: $package_type\n\nDescription:\n$package_desc";  // Check for duplicates  $check = "SELECT * FROM flyspray_tasks WHERE item_summary='$package_name'";  $result = mysql_query($check,$connection) or die(mysql_error());  // If no package with the same name already exists in the db, continue to insert it  if(mysql_num_rows($result) == 0)  {    $insert_task = "INSERT INTO flyspray_tasks(project_id, task_type, date_opened, opened_by, closure_comment, item_summary, detailed_desc, item_status, resolution_reason, product_category, product_version, operating_system, task_severity, task_priority) VALUES(1, 3, $time, 1,  '', '$package_name', '$body', 2, 1, 4, 2, 1, 1, 2)";    mysql_query($insert_task,$connection) or die(mysql_error());    // Get the task_id of the just submitted task    $query_getid = "SELECT * FROM flyspray_tasks WHERE item_summary='$package_name'";    $result_getid = mysql_query($query_getid,$connection) or die(mysql_error());    while($Array = mysql_fetch_array($result_getid))    {      $id = $Array['task_id'];    }    // Add an assignee to the just submitted task    $insert_assignee = "INSERT INTO flyspray_assigned(task_id, user_id) VALUES($id, 1)";    mysql_query($insert_assignee,$connection) or die(mysql_error());    print '<div style="font-weight:bold; color:blue; margin: 10px 0 10px 0;">Task created for this package successfully on our <a href="http://issues.unity-linux.org/">issue tracker</a>!</div>';  }  // If a duplicate exists, show error  else  {    print '<div style="font-weight:bold; color:red; margin: 10px 0 10px 0;">Package could not be added. A package submission with the same name already exists.</div>';  }?>