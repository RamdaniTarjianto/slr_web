<!doctype html>
<html lang="en">
  <head>
  	<title>Automatic SLR API</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	</head>
	<body>
		<!-- <div class="d-flex justify-content-center py-3 bg-primary text-light">
			<h1>Automatic Systematic Literature Review API</h1>
		</div>
		<div class=" justify-content-center p-3 m-auto w-50"> -->
			   
	
		<form action="" method="post">
			<div class="input-group mt-5 ">
			  <input type="text" class="form-control" placeholder="Research Topic" aria-label="Research Topic" name="research_name" aria-describedby="button-addon2" required>
			  <div class="input-group-append">
				<input class="btn btn-outline-secondary" name="submit" type="submit" id="button-addon2" value="Search">
			  </div>
			</div>
	
		</form>

		<?php
			if (isset($_POST['submit'])) {
				// Your code that you want to execute
				$key = $_POST['research_name'];
				$keyword = preg_replace('/\s+/', '+', $key);
				$query = "https://api.semanticscholar.org/graph/v1/paper/search?query=$keyword&limit=50&fields=title,authors,abstract,url";
				$url = file_get_contents($query);
				$data = json_decode($url, true);
				// echo $query;
				// var_dump($data['data'][0]['paperId']);
			}
  		?>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
			<div class="row">
				<div class="col-md-12">
					<?php echo '<h4 class="text-center mb-4">Search Result For ' . $key . '</h4>' ?>
					<div class="table-wrap">
						<table class="table">
					    <thead class="thead-primary">
					      <tr>
					        <th>Title</th>
					        <th>Abstract</th>
					        <th>Result</th>
					      </tr>
					    </thead>
					    <tbody>
						<?php
							for ($i = 0; $i < sizeof($data['data']); $i++) {
								$title = $data['data'][$i]['title'];
								$abstract = $data['data'][$i]['abstract'];
								$url = $data['data'][$i]['url'];
								$link = "<a href='$url'>$title</a>";
								$titleAbstract = $title . " " . $abstract;
								$txt = preg_replace('/\s+/', '+', $titleAbstract);
								$query = "https://slrdeploy-z35x3o5coa-uc.a.run.app/predict?text=$txt";
								// $url = file_get_contents($query);
								// $data = json_decode($url, true);
								// // echo $query;
								// var_dump($data);
			
								$curl = curl_init();
			
								curl_setopt_array($curl, array(
									CURLOPT_URL => $query,
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_TIMEOUT => 30,
									CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									CURLOPT_CUSTOMREQUEST => "POST",
									CURLOPT_HTTPHEADER => array(
										"Content-Length: 0"
									),
								));
			
								$response = curl_exec($curl);
								$err = curl_error($curl);
								echo '<tr>';
								echo '<th scope="row" class="scope">' , $link , '</th>';
								echo '<td>' . $abstract . '</td>';
								if($response == "include"){
									echo '<td><a href="" class="btn btn-success">' . $response . '</a></td>';
								}else{
									echo '<td><a href="" class="btn btn-danger">' . $response . '</a></td>';
								}
								echo '</tr>';
							}
						?>

					    </tbody>
					  </table>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html>

