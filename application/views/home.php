<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Home Page
 */
?>
<div class="content-left">
	<div id="bot_summary" class="box">
		<h3>Server Bot Piece Information</h3>
		<table id="botSummary" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th class="all">Bot Series</th>
					<th>Description</th>
					<th>Frequency</th>
					<th>Value <br/>(in Peanuts)</th>
					<th>Bot Pieces</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="control"></th>
					<th class="all">Bot Series</th>
					<th>Description</th>
					<th>Frequency</th>
					<th>Value <br/>(in Peanuts)</th>
					<th>Bot Pieces</th>
				</tr>
			</tfoot>
			<tbody>
				{botPieceSummary}
			</tbody>
		</table>
	</div>
	<div id="latest_activity">
		<h3>10 Latest Server Transaction History</h3>
		<table id="latestActivity" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th>Date &amp; Time</th>
					<th>Agent</th>
					<th>Player</th>
					<th>Series</th>
					<th>Transaction</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="control"></th>
					<th>Date &amp; Time</th>
					<th>Agent</th>
					<th>Player</th>
					<th>Series</th>
					<th>Transaction</th>
				</tr>
			</tfoot>
			<tbody>
				{serverLatestActivity}
			</tbody>
		</table>
	</div>
</div>
<div class='content-right'>
	<div id="player_info">
		<h3>Local Active Players Leaderboard</h3>
		<table id="playerSummary" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th class="all">Name</th>
					<th>Peanuts</th>
					<th>Equity</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="control"></th>
					<th class="all">Name</th>
					<th>Peanuts</th>
					<th>Equity</th>
				</tr>
			</tfoot>
			<tbody>
				{playerInfo}
			</tbody>
		</table>
	</div>

	<div id="latest_transactions">
		<h3>10 Latest Agent Transaction History</h3>
		<table id="latestTransactions" class="display">
			<thead>
				<tr>
					<th class="control"></th>
					<th>Date &amp; Time</th>
					<th>Player</th>
					<th>Type</th>
					<th>Peanuts</th>
					<th>Transaction</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="control"></th>
					<th>Date &amp; Time</th>
					<th>Player</th>
					<th>Type</th>
					<th>Peanuts</th>
					<th>Transaction</th>
				</tr>
			</tfoot>
			<tbody>
				{agentLatestActivity}
			</tbody>
		</table>
	</div>
</div>