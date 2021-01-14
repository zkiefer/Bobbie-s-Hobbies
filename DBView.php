<?php
include "top.php";
print "<!-- VARIABLES -->";


$table = "";
$records = "";
@$loggedIn = $_SESSION["loggedIn"];
@$admin = $_SESSION["adminAccess"];

print "<!-- FORM PROCESSING -->";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($loggedIn && $admin) {
        $table = htmlentities($_POST["lstTable"]);
        $tableQuery = "SELECT * FROM" . $table;
        if ($thisDatabaseReader->querySecurityOk($tableQuery, 0)) {
            //$query = $thisDatabaseReader->sanitizeQuery($tableQuery);
            $records = $thisDatabaseReader->select($tableQuery, $table);
        }
    } else {
        print "<h1 class = 'error'>Please Log on with an Admin account to access this</h1>";
        print "<p class = 'error'>if your account should have admin access, please contact webmaster</p>";

    }

}
?>
<h1>Table to View:</h1>
<form id="lstTables" method="post">
    <select id="lstTable" name="lstTable">
        <option value="`tblCart`">Cart</option>
        <option value="`tblInventory`">Inventory</option>
        <option value="`tblReviews`">Reviews</option>
        <option value="`tblUser`">User</option>
    </select>
    <input class="button" id="btnSubmit" name="btnSubmit" type="submit" value="Submit">
</form>
<?php
DisplayTable($records, $table);

?>
<?php
include "footer.php";
?>
	