<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Game Server Status Message.
 */
?>
<div id="game_status">
	<p>
		<b>Game Server Status:</b><br />
		Round: <b>{round}</b> | State: <b>{state} ({current})</b> | Countdown:  <b>{countdown} second{s}</b> until state is {upcoming} (at {alarm}) | Current Game/Server Time:  <b>{now}</b>
	</p>
</div>