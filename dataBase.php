<html>
<head>
</head>
<body>

<form action="dataBase.php" method = "post">
Name:<input type="text" name="name"><br />
Category:<input type="text" name="category"><br />
Length:<input type="number" name="length"><br />
<input type="hidden" name="rented" value= "0">
Is This Video Checked Out?:<input type="checkbox" value = "1" name="rented" checked ="1" autocomplete = "off"> <br />
<input type = "submit" name ="submit" value = "Submit Video">
</form>

<form action ="dataBase.php" method = "post">
<input type ="hidden" name="hidden"><br/>
<input type ="submit" name ="delete" value = "Delete All Videos"><br />
</form>



</body>
</html>

<?php
	//create connection
	$dbhost = 'oniddb.cws.oregonstate.edu';
	$dbname = 'bauerbr-db';
	$dbuser = 'bauerbr-db';
	$dbpass = 'M2whRxJMNGLI85Ki';

	$con = new mysqli("oniddb.cws.oregonstate.edu", "bauerbr-db", "M2whRxJMNGLI85Ki", "bauerbr-db");
	if ($con->connect_errno) {
		echo "Failed to connect to con: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {
		echo "";
	}
//Delete Table
if(isset($_POST['delete'])){
	if($con->query("DELETE FROM videoInventory") == TRUE){
		echo "Succesfully Deleted";
	}
}



	
	if(isset($_POST['submit'])){
	

	//create database
	//http://www.w3schools.com/php/php_mysql_create.asp

	//$sql = "CREATE DATABASE video";
	//if($con-> query($sql) === TRUE) {
		//echo "Database created";
	//} else {
		//echo "Error creating database: " . $mysqli->error;
	//}

	//if ($con->query("CREATE TABLE videoInventory (
	//id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	// name VARCHAR(255) NOT NULL ,
	// category VARCHAR(255),
	// length INT(6),
	 //rented TINYINT(1))") == TRUE){
		 //printf("table created succesfully");
	 //}
	 
	 //if($con-> query($con) === TRUE) {
		//echo "Table created";
	//} else {
		//echo "Error creating Table " . $con->error;
	//}
	//Inserting Values through form
	if (empty($_POST["name"])) {
		echo "Name cannot be empty*";
	} else {
		$sql = "INSERT INTO videoInventory (name,category,length,rented) VALUES('$_POST[name]','$_POST[category]','$_POST[length]','$_POST[rented]')";
		if(!$result = $con->query($sql)){
			die('There was an error running the query [' . $con->error . ']');
		}
	}


	}
	
	$sort = "SELECT category FROM videoInventory";
	if(!$result = $con->query($sort)){
		die('There was an error running the query [' . $con->error . ']');
		$result ->close();
	}
	$categoryArrayNon = array();
	while ($row = mysqli_fetch_array($result)){
		array_push($categoryArrayNon, $row["category"]);
	}
	
	$table = "SELECT * FROM videoInventory";
	if(isset($_POST['filter'])){
		if($_POST['dropdown'] == "ALL_MOVIES"){
			$table = "SELECT * FROM videoInventory";
		} else {
			$table =  "SELECT * FROM videoInventory WHERE category = '$_POST[dropdown]'";
		}
		
}
	if(!$stmt = $con->query($table)){
		die('There was an error running the query [' . $con->error . ']');
	}
	
	echo "<table border ='3' cellspacing ='1' cellpadding= '1'>";
	echo "<caption> Movies</caption>";
	echo "<p> PRESS TWICE TO DELETE AND CHANGE FROM CHECKED OUT TO AVAILABLE<p>";
	echo "<tr>";
	echo "<th> Movie Name </th>";
	echo "<th> Category </th>";
	echo "<th> Length </th>";
	echo "<th> Rented </th>";
	echo "<th></th>";
	echo "<th>Delete</th>";
	echo "</tr>";
	
	while ($row = mysqli_fetch_array($stmt)){
		$rentedvalue = '';
		if($row["rented"] == 1){
			$rentedvalue = "Checked Out";
			
		} else {
			$rentedvalue= "Available";
		}
		
		echo "<tr>";
		echo "<td>" . $row["name"] . "</td>";
		echo "<td>" . $row["category"] . "</td>";
		echo "<td>" . $row["length"] . "</td>";
		echo "<td><form action ='dataBase.php' method = 'post'> <input type ='hidden' name='hidden2' value =" . $row["rented"]. "><br/><input type ='hidden' name='hidden3' value =" . $row["id"]. "><br/><input type ='submit' name ='checkedoutstatus' value =" . $rentedvalue . "><br /></form><td>";
		echo "<td><form action ='dataBase.php' method = 'post'> <input type ='hidden' name='hidden' value =" . $row["id"]. "><br/><input type ='submit' name ='deleteOne' value = 'Delete Video(Press Twice)'><br /></form><td>";
		echo "</tr>";
	}
	echo "</table>";

	
	//Changed Checked out Status
	
	if(isset($_POST['checkedoutstatus'])){
		if($_POST['hidden2'] == 1){
		$newRentedValue = 0;
	} else {
		$newRentedValue = 1;
	}
	if($con->query("UPDATE videoInventory SET rented = '$newRentedValue' WHERE id = '$_POST[hidden3]'") == FALSE ){
		echo "ERROR";
	}
}
	//Delete a row
	if(isset($_POST['deleteOne'])){
	if($con->query("DELETE FROM videoInventory WHERE id = '$_POST[hidden]'") == TRUE){
		echo "Delete Successful";
	}
}

	$categoryArray = array_unique($categoryArrayNon);
	array_push($categoryArray,"ALL_MOVIES");
	
	
	$length = count($categoryArray);
	//Dropdown Box
	echo "<form action='dataBase.php' method = 'post'>";
	echo "<select name ='dropdown'>";
	for($i = 0; $i < $length; $i++){
		{
		echo "<option value='" .$categoryArray[$i]."'>".$categoryArray[$i]."  </option>";
		}
	}

echo "</select>";
echo "<input type = 'submit' name ='filter' value = 'Filter By Category'>";
echo "</form>";

//<span class="error">*  <?php echo $errName; </span>
//Check to make sure Name isnt blank.
//$errName = "";
//$nameTest = "";
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //if (empty($_POST["name"])) {
    //$errName = "Name of video is required";
  //} else {
    //$nameTest = test_input($_POST["name"]);
  //}
//}


	
?>



