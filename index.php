<?php
include("top.php");
print PHP_EOL;

print "<!-- FORM VARIABLES -->";

$searchBar = "";
$searchTerm = "";
$startResults = "";
$dataIsGood = false;
$searchResults = "";
$searched = false;
$redirect = getData("redirect");
$records = "";


print "<!-- QUERIES/FORM PROCESSING-->";

//pull all items with reviews when the page loads, order by #stars descending 
$startupQuery = "SELECT `stars`,`pfkItemId`,`itemName`, `reviewText`,`description`,`price`,`imageString`,`category` FROM `tblInventory` JOIN `tblReviews`ON `pmkItemId`=`pfkItemId` ORDER BY `tblReviews`.`stars` DESC";

//$records = $thisDatabaseReader->testSecurityQuery($startupQuery, 0,1);
if ($thisDatabaseReader->querySecurityOk($startupQuery, 0, 1)) {
    $query = $thisDatabaseReader->sanitizeQuery($startupQuery);
    $startResults = $thisDatabaseReader->select($query);
}
$itemQuery = "SELECT * FROM `tblInventory`";

if ($thisDatabaseReader->querySecurityOk($itemQuery, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($itemQuery);
    $records = $thisDatabaseReader->select($query);
}

//process search by user and display results 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $dataIsGood = true;
    $searchTerm = getdata("searchBar");
    if ($searchTerm == "") {
        $dataIsGood = false;
    }
    if ($dataIsGood) {
        $searchTerm = htmlentities($_GET["searchBar"]);
        $searchQuery = 'SELECT *  FROM `tblInventory` WHERE `itemName` LIKE "%' . $searchTerm . '%"';
        $searched = true;

        if ($thisDatabaseReader->querySecurityOk($searchQuery, 1, 0, 2, 0, 0)) {
            //$query = $thisDatabaseReader->sanitizeQuery($searchQuery);
            $searchResults = $thisDatabaseReader->select($searchQuery);
        }

    }
}

if($redirect == 1){
	print "<h2 class = 'orderPlaced'>Your order was Successfully placed!</h2>";
}
?>
<!-- HTML FORM -->
<form id="frmSearch" method="get">
    <input type="text" class="searchBar" name="searchBar">
    <input class="button" id="btnSubmit" name="btnSubmit" type="submit" value="Search">

</form>
<?php
if ($searched) {
    print "<ul class='displaySearch'>";
    if(!$searchResults){
	print "<li><h2>Sorry! Nothing matches what you searched for, try a different search term!</h2></li>";
    }else{
    	displayItems($searchResults, $className = "displaySearch");
    }
	print "</ul>";
} else {
    print "<h1 class='display'>Our Top Rated Items</h1>";
    print "<ul class='displayRated'>";
    displayItems($startResults, $className = "displayRated");
    print PHP_EOL;
    print "</ul>";
}

?>
<h1>All our Items</h1>
<ul class='displaySearch'>
<?php 
displayItems($records, $className='displaySearch');
?>
</ul>

<?php
include("footer.php");
?>
</body>
</html>
