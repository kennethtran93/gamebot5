<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Player Administration Page
 */
?>
<div id="admin">
	<p>Welcome to the Botcards Site Administration page.  Please click one of the follow links to manage that feature.</p>
	<form method="POST">
		<input type="submit" name="adminAgent" id="adminAgent" value="Agent Management" />
		<input type="submit" name="adminPlayer" id="adminPlayer" value="Player Management" />
	</form>
</div>