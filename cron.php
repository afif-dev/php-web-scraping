<?php
	$wsc_file = 'tmp/wsc_output.txt';
	$data = [];
	$getDate = date(DATE_RFC850);
	// create curl resource
	$ch = curl_init();

	$url = "https://www.muamalat.com.my/";
	// set url
	curl_setopt($ch, CURLOPT_URL, $url);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$output = curl_exec($ch);
	
	// close curl resource to free up system resources
	curl_close($ch); 
	
	if($output) {
		$output = $output."\nwsc_date_modified: ".$getDate;
		file_put_contents($wsc_file, $output);
		$data = ["success" => true, "message" => "Successfuly, saved content into file!", "date" => $getDate];
	}else{
		$data = ["success" => false, "message" => "Failed, saved content into file!", "date" => $getDate];
	}
	
	echo json_encode($data);    
?>