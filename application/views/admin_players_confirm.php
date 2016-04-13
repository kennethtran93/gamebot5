<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Home Page
 */
?>
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
	<input type="submit" name="decline" value="Back to Player Selection" />
</form>
<div id="player_admin" class="box">
	<h3>Player Administration</h3>
	<p>Please confirm the selection below for action [<b>{action}</b>] by checking off the boxes again. </p>
	<ul><li>{Description}</li></ul>
	<form method="POST">
		<table id="allPlayers" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th class="all">Confirm</th>
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
					<th class="all">Confirm</th>
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
		<form method="POST">
			With selected players:
			<input type="submit" name="confirm{action}" value="Confirm {Description}" />
		</form>
	</form>
</div>
<br />
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
	<input type="submit" name="decline" value="Back to Player Selection" />
</form>
