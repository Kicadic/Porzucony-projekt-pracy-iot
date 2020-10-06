<?php
	include 'dbh.php';
	$output = array();
		$sql = "SELECT * FROM mysampletable";
		$result = mysqli_query($conn, $sql);
		if(mysqli_num_rows($result) > 0){
		while ($row = mysqli_fetch_array($result)) {
			$output[] = array(
			"ID" => $row['ID'],
			"Name" => $row['Name'],
			);
		}
		echo json_encode($output);
		
		} else {
			echo "No data!";
		}
	
	?>