<?php
/* 
Web Scraping
GET: Gold Price Rates
Output format type: html, table, json
Author: Afif Dev <https://github.com/afif-dev>
*/

$url = "https://www.muamalat.com.my/";
$webscrap_file = 'tmp/content_output.txt';
$data = [];
$getDate = date(DATE_RFC850);

// Create curl resource
$ch = curl_init();
// Set url
curl_setopt($ch, CURLOPT_URL, $url);
// Return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output contains the output string
$output = curl_exec($ch);
// Close curl resource to free up system resources
curl_close($ch); 

if($output) {
	$output = $output."\nwsc_date_modified: ".$getDate;
	file_put_contents($webscrap_file, $output);
	$data = ["success" => true, "message" => "Successfuly, saved content into file!", "date" => $getDate];
	filterContent($webscrap_file,"Gold Price Rates");
}else{
	$data = ["success" => false, "message" => "Failed, saved content into file!", "date" => $getDate];
}

// Output 
echo json_encode($data);

function filterContent($webscrap_file, $title="") {
	$content = file_get_contents($webscrap_file);

	// Filter Content
	preg_match('/wsc_date_modified:(.*)/s', $content, $match_date_mod);
	preg_match('/<h3 class="rates_title">Gold Price Rates<\/h3>(.*?)<\/div>/s', $content, $match_main);
	preg_match('/<table class="table table-borderless text-center">(.*?)<\/table>/s', $match_main[0], $match_table);

	// Filter Content Result
	$date_modified = $match_date_mod[1];
	$filter_content = $match_table[0];

	// HTML String
	$htmlString = "<html><body>".$filter_content."</body></html>";

	// DOM Parser Object
	$htmlDom = new DOMDocument();
	$htmlDom->loadHTML($htmlString);
	$xpath = new DOMXPath($htmlDom); 
	$result = ["title" => $title];

	$tmp_th = [];
	foreach ($xpath->query('//thead/tr/th') as $th) {
		$tmp_th[] = trim($th->textContent);
	}
	foreach ($xpath->query('//tbody/tr') as $tr) {
		$tmp = []; // reset the temporary array so previous entries are removed
		foreach ($xpath->query("td", $tr) as $key => $td) {
			$tmp[$tmp_th[$key]] = trim($td->textContent);
		}
		$result["data"][] = $tmp;
	}
	if ($result["data"]){
		$result["date_modified"] = $date_modified;
		
		// JSON Data Result
		$json_result = json_encode($result);
		// Save as JSON File
		file_put_contents("data.json", $json_result);
	}
}
?>