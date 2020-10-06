<?php
 include 'dbh.php';
 ?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>

  </head>
  
  <body ng-app='myapp'>
  <?php
  
  require_once("divy.php");
  
  ?>
  
<div class="container">

<div class ="form-group">
<label for="date"> Data </label>
<input id="date" name="date" [(ngModel)] = "date" type="date" class="form-control">
</div>


	<div id="tabela" ng-controller='userCtrl'>
		<table class="table table-bordered">
		<tr>
			<th>ID</th>
			<th>Name</th>
		</tr>
		<tr ng-repeat="x in mysampletable">
			<td>{{x.ID}}</td>
			<td>{{x.Name}}</td>
			
		</tr>
		</table>
	</div> 

	<!-- Script -->
	<script type="text/javascript">
	var fetch = angular.module('myapp',[]);

	fetch.controller('userCtrl',['$scope','$http',function($scope,$http){

		$http({
			method: 'get',
			url: 'getdata.php'
		}).then(function successCallback(response){
			$scope.mysampletable = response.data;
		});
	}]);
	</script>


</div>
  <script src="js/bootstrap.min.js"> </script>
  
  </body>
</html>
