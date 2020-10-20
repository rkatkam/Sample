<!DOCTYPE html>
<html>

<head>
    <title>CEIPAL API</title>
    <style>
        body {
            font-family: 'Tahoma'
        }
		.data{
			border:1px solid #ddd;
			max-height:250px;
			padding:5px;
			font-family:consolas;
			font-size:13px;
			overflow:auto;
			margin-top:5px;
		}
    </style>
</head>
<body>
    <?php
	set_time_limit(300);
	
		//Employees
		function get_data($url,$token){
        $options = array(
            'http' => array(
                'header'  => "Authorization: Bearer $token",
                'method'  => 'GET',
                'ignore_errors' => true
            ),
            'ssl' => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        );
        $context  = stream_context_create($options);
        //return json_decode(file_get_contents($url, false, $context));
		   return file_get_contents($url, false, $context);
		}
	
    echo time();
    echo '<hr/>';
    echo 'Step-1 : Getting the Employee details API... <br/>';
	
        $url = 'https://api.ceipal.com/authentication/';
        $data = [
            'email' => 'regina.sanku@compugain.com',
            'password' => 'Fanniemae@123',
            'api_key' => 'Z2hmWkFsSFpTRDVqWExlRWxDTXo3UT09'
        ];

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ),
            'ssl' => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
		$token_data = json_decode($result);
		
		echo '<br/>Token Details:';
		echo '<div class="data">';
		echo $token_data->access_token;
		echo '</div>';
		
		//should get page count dynamically (?)
		
		$employees_list=[];
		for($page=1;$page<=20;$page++){
			$url="https://api.ceipal.com/wf/employees/?page=$page";
			echo "<br/>$url";
			$result=json_decode(get_data($url,$token_data->access_token));
				$employees_list=array_merge($employees_list,$result->results);
		}
		
		echo "<hr/>Total Employees : ".count($employees_list);
		
		$placement_list=[];
		for($page=1;$page<=20;$page++){
			$url="https://api.ceipal.com/wf/placement/?page=$page";
			echo "<br/>$url";
			$result=json_decode(get_data($url,$token_data->access_token));
				$placement_list=array_merge($placement_list,$result->results);
		}
		echo "<hr/>Total Placements : ".count($placement_list);
		
		$sql = "INSERT INTO employee_client(details_uniqueid,external_id, first_name, last_name, client_id, end_client_id, hire_date, end_date, assigned_csm, assigned_csr, work_email,personal_email, employee_status_id, timesheet_cycle_id, ot_applicable, mt_80_h_approval, mt_80_h_approval_tilldate, mh_approved_over_80, lt_80_h_approval, min_h_approved_lt_80, weekend_work_approval,  shift_id,  created_time, created_by, ip_address, is_active,lt_80_h_approval_expiration,weekend_work_approval_expiration,after_hours_support_worker)
		VALUES ('."date('Y-m-d H-i-s')."', '$employees_list->fnma_fg_id', '$employees_list->first_name', '$employees_list->last_name', '$placement_list->client', 'No' , '$employees_list->date_of_joining', '$placement_list->job_end_date', 'katrina.egan@compugain.com', 'katrina.egan@compugain.com', '$employees_list->email_id', '$employees_list->email_id', '$employees_list->emptype_category', 'Bi-weekly', '$employees_list->ot_applicable', 'No', '000-00-00', '0', 'No', '0', 'No', '',  'date('Y-m-d H:i:s')', '1', Null, '1','0000-00-00 00:00:00','0000-00-00 00:00:00', 'No' )";
		
		foreach($employees_list as $employee){
			foreach($placement_list as $placement){
				//actual compare logic
				if($employee->first_name==$placement->employee){
					echo "<br/>Employee : $employee->first_name Placement :  $placement->employee";
					//loop should break
					break;
				}
			}
		}
		?>
</body>
</html>
		
