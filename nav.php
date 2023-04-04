<?php /*
* Module Name: 	nav.php
* Date: 		2023-03-30
* Author:		J. Sayre
* Purpose:		Modern navigation sidebar.
*/
?>

<div class="menu-container">
  
        <input type="checkbox" id="openmenu" class="hamburger-checkbox">
      
        <div class="hamburger-icon">
            <label for="openmenu" id="hamburger-label">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </label>    
        </div>
        <div class="menu-pane">
            <nav>
                <ul class="menu-links">
                <li>
                    <a class="a-default" href="index.php">Home</a> <!-- shown to all logged-in users -->
                </li>
            <?php if (isLoggedIn()) : ?>
                <?php if (isAdmin()) : ?>
                    <li>
                        <!-- Display a link to the Admin Panel -->
                        <a class="a-default" href="adminpanel.php">Admin Panel</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="a-default" href="myaccount.php">My Account</a> <!-- shown to all logged-in users -->
                </li>
                <?php if (isAdmin() || isPremium() || isProvisioned()) : ?>
                
                    <li>
                        <a class="a-default" href="mysettings.php">Manage Settings</a>
                    </li>
                    <li>
                        <a class="a-default" href="myviewers.php">Manage Requestors</a>
                    </li>
                    <li>
                        <a class="a-default" href="mysongs.php">Manage Songs</a>
                    </li>
                <?php endif; ?>
                <?php if (isAdmin() || isPremium()) :  ?>
                    <li>
                        <a class="a-default" href="mywebhooks.php">Manage Webhooks</a>
                    </li>
                <?php endif; ?>
                <?php if (!isProvisioned() && !isPremium() && !isAdmin() && !isSetupSubmitted()) : ?>
                    <li>
                        <a class="a-default" href="setupsmr.php">Setup SMR</a>
                    </li>
                <?php endif; ?>
                <li id="logout_link"><a href=#>Logout</a></li> <!-- shown to all logged-in users -->
            <?php else : ?>
                <li><a href="./login.php">Login</a></li> <!-- shown to all Not Logged in Users -->
                <li><a href="./signup.php">Sign-Up</a></li> <!-- shown to all Not Logged in Users -->
            <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>