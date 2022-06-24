<?php
/* 
Web Scraping
GET: Gold Price Rates
Output format type: html, table, json
*/

$file = 'tmp/wsc_output.txt';
$content = file_get_contents($file);

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
$result = ["title"=> "Gold Price Rates"];

foreach ($xpath->query('//tbody/tr') as $tr) {
    $tmp = []; // reset the temporary array so previous entries are removed
    foreach ($xpath->query("td", $tr) as $key => $td) {
        $tmp[$key] = trim($td->textContent);
    }
    $result["data"][] = $tmp;
}
if ($result["data"]){
	$result["success"] = true;
	$result["message"] = "Successfuly, display data!";
}else{
	$result["success"] = false;
	$result["message"] = "Failed, display data!";
}
// JSON Data Result
$json_result = json_encode($result);

// Render HTML
echo <<<EOD
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$result['title']}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
		<main>
			<section class="pt-4 text-center container">
				<div class="row py-lg-4">
					<div class="col-lg-6 col-md-8 mx-auto">
						<h1 class="fw-light">{$result['title']}</h1>
						<p class="lead text-muted">Web scraping {$result['title']} from other website.</p>
						<p class="text-muted">In order to get site content, app require to run cron file in 'cron.php' daily or hourly.</p>
						
						<h3 class="fw-light my-4">Raw Table Output</h3>
						<div class="border shadow p-3 mb-5 bg-body rounded">
							{$filter_content}
						</div>
						
						<hr/>

						<h3 class="fw-light my-4">JSON Output</h3>
						<div class="text-start">
							<code>{$json_result}</code>
						</div>
						
						<hr/>
						<p><small>Last update on {$date_modified}</small></p>
					</div>
				</div>
			</section>
		</main>
		    
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>
EOD;
?>