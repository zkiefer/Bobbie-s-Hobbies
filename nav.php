<nav>

    <ul>

        <?php

        if (@$_SESSION["loggedIn"]) {
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'login') {
                print ' class="activePage" ';
            }
            print '><a href="login.php?SignUp=2">Logout</a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'cart') {
                print ' class="activePage" ';
            }
            print '><a href="cart.php"><img class = "cart" src = "images/cart.png"></a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            if ($_SESSION["adminAccess"]) {
                print '<li id="nav"';
                if (PATH_PARTS['filename'] == 'DBView') {
                    print ' class="activePage" ';
                }
                print '><a href="DBView.php">View DataBase</a></li>';
            }
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'index') {
                print ' class="activePage" ';
            }
            print '><a href="index.php">Home</a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav" class="welcome"><p>Welcome ';
            print $_SESSION["firstName"];
            print '</p></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        } else {

            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'login') {
                print ' class="activePage" ';
            }
            print '><a href="login.php">Login</a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'cart') {
                print ' class="activePage" ';
            }
            print '><a href="login.php"><img class = "cart" src = "images/cart.png"></a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            print '<li id = "nav"';
            if (PATH_PARTS['filename'] == 'index') {
                print ' class="activePage" ';
            }
            print '><a href="index.php">Home</a></li>';
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        }


        ?>

    </ul>

</nav>