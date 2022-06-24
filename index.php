<?php
/* 
Web Scraping
GET: Gold Price Rates
Output format type: html, table, json
Author: Afif Dev <https://github.com/afif-dev>
*/

$data_file = 'data.json';
$content = file_get_contents($data_file);
$result = json_decode($content);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $result->title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body>
		<main>
			<section class="pt-4 text-center container">
				<div class="row py-lg-4">
					<div class="col-lg-6 col-md-8 mx-auto">
						<h1 class="fw-light"><?= $result->title; ?></h1>
						<p class="lead text-muted">Web scraping <?= $result->title; ?> from other website.</p>
						<p class="text-muted">In order to get site content, app require to run cron file in 'cron.php' daily or hourly.</p>
						
						<h3 class="fw-light my-4">Table Output</h3>
						<div class="border shadow p-3 mb-5 bg-body rounded table-responsive">
						<table class="table">
							<thead class="thead-light">
								<tr>
									<th scope="col">#</th>
								<?php foreach($result->data as $key => $data):
								if ($key == 0):
									foreach($data as $item => $value):  ?>
									<th scope="col"><?= $item; ?></th>
								<?php endforeach;
								endif; 
								endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach($result->data as $key => $data): ?>
								<tr>
									<th scope="row"><?= ($key+1) ?></th>
								<?php foreach($data as $item => $value):  ?>
									<td><?= $value ?></td>
								<?php endforeach;?>
								</tr>		
								<?php endforeach;?>
							</tbody>
							</table>
						</div>
						
						<hr/>

						<h3 class="fw-light my-4">JSON Output</h3>
						<div class="text-start">
							<code><?= $content; ?></code>
						</div>
						
						<hr/>
						<p><small>Last update on <?= $result->date_modified; ?></small></p>
					</div>
				</div>
			</section>
		</main>
		    
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>