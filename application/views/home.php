<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Home Page
 */
?>
<div class="content-left">
	<div id="bot_summary" class="box">
		<h3>Bot Piece Summary</h3>
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
</div>

<div id="player_info">
	<h3>Player Summary</h3>
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
