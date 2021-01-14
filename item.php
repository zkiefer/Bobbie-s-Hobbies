<?php
include("top.php");
print PHP_EOL;

$item = array($_GET["itemId"]);
$records = "";
$reviews = "";
$recordInsert = "";
$dataIsGood = false;
$itemQuery = "SELECT `itemName`,`description`,`price`,`imageString`,`category`FROM `tblInventory` WHERE `pmkItemId` LIKE ?";

if ($thisDatabaseReader->querySecurityOk($itemQuery, 1)) {
    $query = $thisDatabaseReader->sanitizeQuery($itemQuery);
    $records = $thisDatabaseReader->select($query, $item);
}

$reviewQuery = "SELECT `pmkItemId`,`stars`, `reviewText`, `pfkItemId` FROM `tblInventory` JOIN `tblReviews` ON `pmkItemId` = `pfkItemId` WHERE `pmkItemId` LIKE ?";
if ($thisDatabaseReader->querySecurityOk($reviewQuery, 1, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($reviewQuery);
    $reviews = $thisDatabaseReader->select($query, $item);
}


print "<!-- FORM PROCESSING -->";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION["loggedIn"]) {
        $userId = $_SESSION["userId"];
        $data = array($userId, $item[0]);
        $queryInsert = 'INSERT INTO `tblCart`(`pfkUserId`,`pfkItemId`) VALUES (?,?)';
        if ($thisDatabaseWriter->querySecurityOk($queryInsert, 0)) {
            //$query = $thisDatabaseReader->sanitizeQuery($queryInsert);
            $recordInsert = $thisDatabaseWriter->insert($queryInsert, $data);
        }
        if ($recordInsert) {
            print "<h2>Item added to cart!</h2>";
        } elseif (!$recordInsert) {
            print "<h2>Something went wrong, item not added</h2>";
        }
    } else {
        header("Location: login.php");
        exit;
    }


}

print "<!-- DISPLAY ITEM -->" . PHP_EOL;
print "<article>";
print "<img class = 'itemPageImg' src = " . $records[0][3] . ">" . PHP_EOL;
print "<h2 class = 'itemPageHeader'>" . $records[0][0] . "</h2>" . PHP_EOL;
print "<p class = 'itemPageDesc'>" . $records[0][1] . "</p>" . PHP_EOL;
print "<p class = 'itemPagePrice'>" . $records[0][2] . "</p>" . PHP_EOL;
?>
<form id="frmSearch" method="post">
    <input class="button" id="btnAddCart" name="btnSubmit" type="submit" value="Add To Cart">

</form>
<?php
print "</article>";
print "<h2>User Reviews:</h2>" . PHP_EOL;
print "<table class = 'review'>" . PHP_EOL;
foreach ($reviews as $review) {
    print "<tr class = 'reviewTable'>";
    print "<td>" . displayStars($review[1]) . "</td>" . PHP_EOL;
    print "<td>" . $review[2] . "</td>" . PHP_EOL;
    print "</tr>";
}
print "</table>";

?>

<?php
include("footer.php");
?>
</body>
</html>
