<?php /*
* Module Name: 	footer.php
* Date: 		2023-03-25
* Author:		J. Sayre
* Purpose:		Footer navigation for inclusion on all site pages for persistence and ease-of-updating.
*/
?>

		<div class="footer-container">
			<div><a class="a-default" href="https://github.com/MrTwinkles47/Stepmania-Stream-Tools-MrTwinkles">View SMRequests on GitHub</a></div>
			<div><a class="a-default" href="https://smrequests.com/discord">SMRequests Discord</a></div>
			<!--Logic to conditionally display navigation links based on the user's access level -->
			<?php if (isLoggedIn()) : ?>
				<?php if (isAdmin()) : ?>
					<div>
						<!-- Display a link to the Admin Panel -->
						<a class="a-default" href="adminpanel.php">Admin Panel</a>
					</div>
				<?php endif; ?>
				<div>
					<a class="a-default" href="myaccount.php">My Account</a> <!-- shown to all logged-in users -->
				</div>
				<?php if (isAdmin() || isPremium() || isProvisioned()) : ?>
				
					<div>
						<a class="a-default" href="mysettings.php">Manage Settings</a>
					</div>
					<div>
						<a class="a-default" href="myviewers.php">Manage Requestors</a>
					</div>
					<div>
						<a class="a-default" href="mysongs.php">Manage Songs</a>
					</div>
				<?php endif; ?>
				<?php if (isAdmin() || isPremium()) :  ?>
					<div>
						<a class="a-default" href="mywebhooks.php">Manage Webhooks</a>
					</div>
				<?php endif; ?>
				<?php if (!isProvisioned() && !isPremium() && !isAdmin() && !isSetupSubmitted()) : ?>
					<div>
						<a class="a-default" href="setupsmr.php">Setup SMR</a>
					</div>
				<?php endif; ?>
				<div id="logout_link" class="a-default">Logout</div> <!-- shown to all logged-in users -->
		<?php endif; ?>
			<div><a class="a-default" href="https://github.com/jstolpe/easycodeis">View Easy, Code Is on GitHub</a></div>
		</div>