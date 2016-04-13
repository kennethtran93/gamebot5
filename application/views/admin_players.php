<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Home Page
 */
?>
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
<div id="player_admin" class="box">
	<h3>Player Administration</h3>
	<p>Check of the player you wish to manage for the same action.</p>
	<ul>
		<li>Reset Passwords:  Password will be reset to their username.</li>
		<li>Delete Accounts:  Player Accounts will be removed from the database.</li>
		<li>Reset Avatars:  Custom avatars will be replaced with the generic avatar.</li>
	</ul>
	<form method="POST">
		With selected players:
		<input type="submit" name="resetPasswords" value="Reset Passwords" />
		<input type="submit" name="deleteAccounts" value="DELETE Accounts" />
		<input type="submit" name="resetAvatars" value="Reset to Generic Avatar" />
		<table id="allPlayers" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th class="all">Select</th>
					<th class="all">Username<br />(Player Name)</th>
					<th>Avatar Filename</th>
					<th>Access Level</th>
					<th>Date Registered</th>
					<th>Last Updated</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="control"></th>
					<th class="all">Select</th>
					<th class="all">Username<br />(Player Name)</th>
					<th>Avatar Filename</th>
					<th>Access Level</th>
					<th>Date Registered</th>
					<th>Last Updated</th>
				</tr>
			</tfoot>
			<tbody>
				{botPlayers}
			</tbody>
		</table>
		With selected players:
		<input type="submit" name="resetPasswords" value="Reset Passwords" />
		<input type="submit" name="deleteAccounts" value="Delete Accounts" />
		<input type="submit" name="resetAvatars" value="Reset to Generic Avatar" />
	</form>
</div>
<br />
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
