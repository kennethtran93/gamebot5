<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Individual Player Page
 */
?>
<div id="playerSelect">
	Currently viewing player details for: 
	<select onchange="window.location.href = this.value">
		{players}
	</select>
	Select another player to view their details
</div>
<div class="center">
	<div class="contentleft">
		<div id="avatar">
			<span class="helper"></span>
			<img id="avatarImg" alt="User Avatar" src="{avatar}" />
		</div>
		<div id="playerDetails">
			<p><label>Name:</label>  <strong>{playerName}</strong></p>
			<p><label>Peanuts:</label>  <strong>{peanuts}</strong></p>
		</div>
	</div>
	<div class="content-right">
		<div id="player_cards">
			<h3>Player Card Collection</h3>
			<table id="playerCards" class="display">
				<thead>
					<tr>
						<th class="control"></th>
						<th class="all">Bot Series</th>
						<th>Bot Type</th>
						<th>Bot Part</th>
						<th>Card Quantity</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="control"></th>
						<th class="all">Bot Series</th>
						<th>Bot Type</th>
						<th>Bot Part</th>
						<th>Card Quantity</th>
					</tr>
				</tfoot>
				<tbody>
					{playerCards}
				</tbody>
			</table>
		</div>

		<div id="latest_activity">
			<h3>Player Transaction History</h3>
			<table id="latestActivity" class="display">
				<thead>
					<tr>
						<th class="control"></th>
						<th>Date &amp; Time</th>
						<th>Type</th>
						<th>Peanuts</th>
						<th>Transaction</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="control"></th>
						<th>Date &amp; Time</th>
						<th>Type</th>
						<th>Peanuts</th>
						<th>Transaction</th>
					</tr>
				</tfoot>
				<tbody>
					{playerLatestActivity}
				</tbody>
			</table>
		</div>
	</div>
</div>