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
	<p>Select the player(s) you wish to manage for the same action.</p>
	<table>
		<tr>
			<th>Action</th><th>Description</th>
		</tr>
		<tr>
			<td>Reset Passwords:</td><td>Password will be reset to their username</td>
		</tr>
		<tr>
			<td>Delete Accounts:</td><td>Player Accounts will be removed from the database.</td>
		</tr>
		<tr>
			<td>Reset Avatars:</td><td>Custom avatars will be replaced with the generic avatar.</td>
		</tr>
		<tr>
			<td>Promote to Admin:</td><td>Make account an admin account.</td>
		</tr>
		<tr>
			<td>Remove Admin Status:</td><td>Make account not an admin account.</td>
		</tr>
	</table>
	<br />
	<form method="POST">
		With selected player(s):
		<input type="submit" name="resetPasswords" value="Reset Passwords" />
		<input type="submit" name="deleteAccounts" value="DELETE Accounts" />
		<input type="submit" name="resetAvatars" value="Reset to Generic Avatar" />
		<input type="submit" name="promoteToAdmin" value="Promote to Admin" />
		<input type="submit" name="removeAdmin" value="Remove Admin Status" />
		<br /><br />
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
		With selected player(s):
		<input type="submit" name="resetPasswords" value="Reset Passwords" />
		<input type="submit" name="deleteAccounts" value="Delete Accounts" />
		<input type="submit" name="resetAvatars" value="Reset to Generic Avatar" />
		<input type="submit" name="promoteToAdmin" value="Promote to Admin" />
		<input type="submit" name="removeAdmin" value="Remove Admin Status" />
	</form>
</div>
<br />
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
