<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Assemble Bots Page
 */
?>
<form method='post'>
	<div id="FinishedProduct">
		<table>
			<tr>
				<td>
					<img id="topPiece" src="{appRoot}/assets/images/bot/gen-0.png" alt="Generic Bot Top Piece Image" />
				</td>
				<td class="select">
					<p>Top Piece</p>
					<select id="top" name='top'>
						<option value="" selected="selected" disabled="disabled">Select a Top Piece</option>
						{topOptions}
					</select><br />
					<span class='error'>{topPiece}</span>
				</td>
			</tr>
			<tr>
				<td>
					<img id="middlePiece" src="{appRoot}/assets/images/bot/gen-1.png" alt="Generic Bot Middle Piece Image" />
				</td>
				<td class="select">
					<p>Middle Piece</p>
					<select id="middle" name='middle'>
						<option value="" selected="selected" disabled="disabled">Select a Middle Piece</option>
						{middleOptions}
					</select><br />
					<span class='error'>{middlePiece}</span>
				</td>
			</tr>
			<tr>
				<td>
					<img id="bottomPiece" src="{appRoot}/assets/images/bot/gen-2.png" alt="Generic Bot Bottom Piece Image" />
				</td>
				<td class="select">
					<p>Bottom Piece</p>
					<select id="bottom" name='bottom'>
						<option value="" selected="selected" disabled="disabled">Select a Bottom Piece</option>
						{bottomOptions}
					</select><br />
					<span class='error'>{bottomPiece}</span>
				</td>
			</tr>
		</table>
	</div>
	<div id="Assemble">
		<input type='hidden' name='topPiece' id="hidden_topPiece" value=''/>
		<input type='hidden' name='middlePiece' id="hidden_middlePiece" value=''/>
		<input type='hidden' name='bottomPiece' id="hidden_bottomPiece" value=''/>
		<input type='submit' name='btnAssemble' id='btnAssemble' value='Assemble and Sell Bot to Server' />

	</div>
</form>
<div id="assembleResult">
	<span id="result"></span>
</div>