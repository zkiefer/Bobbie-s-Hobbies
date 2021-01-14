<?php 

include "top.php";
print "<!-- VARIABLES-->";

$total = 0;
$userId = array($_SESSION["userId"]);
$dataGood= "";
$fname = "";
$lname = "";
$street="";
$state = "";
$zip="";
$update="";
$from= "";
$info = "";
$test = "";
$addressType = "";
$message = "";
$headers = "";
print "<!-- QUERIES-->";

$cartQuery = "SELECT `pfkUserId`,`pfkItemId`,`pmkItemId`,`itemName`,`description`,`imageString`,`price` FROM `tblCart` JOIN `tblInventory` ON `pmkItemId` = `pfkItemId` WHERE `pfkUserId` LIKE ?";
if ($thisDatabaseReader->querySecurityOk($cartQuery, 1)) {
    		$query = $thisDatabaseReader->sanitizeQuery($cartQuery);
    		$records = $thisDatabaseReader->select($cartQuery,$userId);
}
foreach($records as $record){
	@$total = $total + $record['price'];
}
print "<!-- FORM PROCESSING-->";
if ($_SERVER["REQUEST_METHOD"] == "GET") {	
	$dataGood = true;
	@$fname= htmlentities($_GET["fname"]);
	@$lname = htmlentities($_GET["lname"]);
	@$street=htmlentities($_GET["street"]);
	@$state = htmlentities($_GET["state"]);
	@$zip= htmlentities($_GET["zip"]);
	$email = $_SESSION["email"];
	@$addressType = $_GET["addressType"];
	
	if($fname == ''){
		print "<p class = 'error'>Looks like you didnt put in your first name!</p>";
		$dataGood = false;
	}
	if($lname == ''){
		print "<p class = 'error'>Looks like you didnt put in your last name!</p>";
		$dataGood = false;
	}
	if($street == ''){
		print "<p class = 'error'>Looks like you didnt put in your street address!</p>";
		$dataGood = false;
	}
	if($state == ''){
		print "<p class = 'error'>Looks like you didnt put in your state!</p>";
		$dataGood = false;
	}
	if($zip == ''){
		print "<p class = 'error'>Looks like you didnt put in your zip!</p>";
		$dataGood = false;
	}
	if($addressType == ''){
		print "<p class = 'error'>Looks like you didnt select your address type!</p>";
		$dataGood = false;
	}
	if($dataGood){
		//update quantities in dataBase
		
		$updateCountQuery = "UPDATE `tblInventory` SET `quantity`= 50 WHERE `pmkItemId` = ?";
		
		foreach($records as $record){	
			$item = array($record[1]);
			if ($thisDatabaseReader->querySecurityOk($updateCountQuery, 1)) {
    				//$query = $thisDatabaseReader->sanitizeQuery($updateCountQuery);
    				$update = $thisDatabaseWriter->update($updateCountQuery,$item);
			}

		}
		$from = 'shcarlso@uvm.edu';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
    		'X-Mailer: PHP/' . phpversion();
 
		//HTML email message
		$message = '<html><body>';
		$message .= '<img src="images/logo.png">';
		$message .= "<h1>Hi ".$fname.',';
		$message .= '<p>Thank you for your order!</p>';
		$message .= '<p>This email is to let you know that your order from BobbiesHobbies was Successfully placedand will ship to your '.$addressType.'address!<!/p>';
		$message .= '<p>Item Summary:</p>';
		$message .= '<table style="width = 40%">';
		$message .= '<tr>';
		$message .= '<th>Item</th>';
		$message .= '<th>Price</th>';
		$message .= '<th></th>';
		$message .= '</tr>';
		foreach($records as $record){
			$message .= '<tr>';
			$message.= "<td class='itemName'>".$record['itemName']."</td>";

			$message.= "<td class='price'>".$record['price']."</td>";
			$message.= "</tr>";
		}		
		$message .= '</table>';
		$message .= '<p>Your support means a lot to us</p>';
		$message .= '<p>-Bobby</p>';
		//if update query goes through, email the user, clear that users cart table entries, and redirect them to index.php with a redirect message
		if($update){
			mail($email,$subject="Your BobbiesHobbies Order!",$message, $headers);
			$emptyCartQuery = "DELETE FROM `tblCart` WHERE `pfkUserId` = ?";
			if ($thisDatabaseReader->querySecurityOk($emptyCartQuery, 1)) {
    				//$query = $thisDatabaseReader->sanitizeQuery($updateCountQuery);
    				$update = $thisDatabaseWriter->update($emptyCartQuery,$userId);
			}
			header("location: index.php?redirect=1");

		}

	}
}

?>
<form id="checkout" method = "get">
	<fieldset>
		<legend><h1>Shipping Info:</h1></legend>
		<input type = "text" id="fname" name="fname" placeholder ="First Name">
		<input type = "text" id="lname" name="lname" placeholder ="Last Name">
		<input type = "text" id="street" name="street" placeholder ="Street Address">
		<input type = "text" id="city" name="city" placeholder ="City">
		<input type = "text" id="state" name="state" placeholder ="State">
		<input type = "text" id="zip" name="zip" placeholder ="Zip Code">
		<fieldset>
		<legend>What type of address is this?</legend>
		<input type = "radio" id = "home" name = "addressType" value = "Home">
		<label for="home">Home</label>
		<input type = "radio" id = "buisness" name = "addressType" value = "Buisness">
		<label for="buisness">Buisness</label>
		<input type = "radio" id = "other" name = "addressType" value = "Other">
		<label for="other">Other</label>

		</fieldset>
		<h1 id = "important">DO NOT PUT ANY SENSITIVE INFO INTO FORM, NOT A FULLY SECURE WEBSITE</h1>
		<h2 id="totalPrice">Final Price: <?php print '$'.$total;?></h2>
		<input class="button" id="btnSubmit" name="btnSubmit" type="submit" value="Checkout">

	</fieldset>
</form>
<h1>Your Items:</h1>
<?php
print "<table class = 'cart'>";
foreach($records as $record){
	print "<tr>".PHP_EOL;
		print "<td class='itemName'>".$record['itemName']."</td>".PHP_EOL;
		print "<td class='description'>".$record['description']."</td>".PHP_EOL;
		print "<td class='price'>".$record['price']."</td>".PHP_EOL;
		print "<td class='cartImg'><img src=".$record['imageString']."></td>".PHP_EOL;		
		print "</tr>".PHP_EOL;
}
print "<tr><td> </td><td> </td><td><h1>Total: ".$total."</h1></td></tr>";
print "</table>";

include 'footer.php';
?>
</body>
</html>
