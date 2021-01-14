<?php 

include "top.php";
print "<!-- VARIABLES-->";
$records = "";
$total = "";
$userId = array($_SESSION["userId"]);
$dataGood= "";

$cartQuery = "SELECT `pfkUserId`,`pfkItemId`,`pmkItemId`,`itemName`,`description`,`imageString`,`price` FROM `tblCart` JOIN `tblInventory` ON `pmkItemId` = `pfkItemId` WHERE `pfkUserId` LIKE ?";
if ($thisDatabaseReader->querySecurityOk($cartQuery, 1)) {
    		$query = $thisDatabaseReader->sanitizeQuery($cartQuery);
    		$records = $thisDatabaseReader->select($cartQuery,$userId);
}

print "<h1>Your Cart:</h1>";
print "<table class = 'cart'>";
foreach($records as $record){
	print "<tr>".PHP_EOL;
		print "<td class='itemName'>".$record['itemName']."</td>".PHP_EOL;
		print "<td class='description'>".$record['description']."</td>".PHP_EOL;
		print "<td class='price'>".$record['price']."</td>".PHP_EOL;
		print "<td class='cartImg'><img src=".$record['imageString']."></td>".PHP_EOL;		
		print "</tr>".PHP_EOL;
		@$total = $total + $record['price'];

}
print "<tr><td> </td><td> </td><td><h1>Total: ".$total."</h1></td></tr>";
print "</table>";

?>
<form action = "checkout.php" id="frmSearch" method="post">
    <input class="button" id="btncheckOut" name="btnSubmit" type="submit" value="Checkout">

</form>


<?php 
include "footer.php";
?>