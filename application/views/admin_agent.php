<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Home Page
 */
?>
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
<div id="agent_admin" class="box">
	<h3>Agent Administration Tasks</h3>
	<form method="post">
		<fieldset>
			<legend>Agent Status</legend>
			Current Status:  <b>{agentStatus}</b><br />
			<input type="submit" name="toggleAgentStatus" value="Toggle Agent Status (Online/Offline)"/>
		</fieldset>
	</form>
	<br />
	<form method="post">
		<fieldset>
			<legend>Botcards Server Properties</legend>
			<table>
				<tr>
					<td><label for="serverURL">Botcards Server URL:</label></td>
					<td><input type="text" name="serverURL" id="serverURL" value="{serverURL}" placeholder="Botcards Server URL"  size="50"/></td>
					<td><span class='error'>{serverURLError}</span></td>
				</tr>
				<tr>
					<td><label for="serverPassword">Botcards Server Password:</label></td>
					<td><input type="text" name="serverPassword" id="serverPassword" value="{serverPassword}" placeholder="Botcards Server Password" /></td>
					<td><span class='error'>{serverPasswordError}</span></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='2'><input type="submit" name="changeServerProperties" value="Change Botcards Server Properties" /></td>
				</tr>
			</table>


		</fieldset>
	</form>
	<br />
	<form method="post">
		<fieldset>
			<legend>Agent Properties</legend>
			<table>
				<tr>
					<td><label for="agentCode">Agent Code:</label></td>
					<td><input type="text" name="agentCode" id="agentCode" value="{agentCode}" placeholder="Agent Code" maxlength="4"/></td>
					<td><span class='error'>{agentCodeError}</span></td>
				</tr>
				<tr>
					<td><label for="agentName">Agent Name:</label></td>
					<td><input type="text" name="agentName" id="agentName" value="{agentName}" placeholder="Agent Name" /></td>
					<td><span class='error'>{agentNameError}</span></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='2'><input type="submit" name="changeAgentProperties" value="Change Agent Properties" /></td>
				</tr>
			</table>
		</fieldset>
		<br />
		<form method="post">
			<fieldset>
				<legend>Force / Manual Agent Registration</legend>
				Note that this button only works when the agent's status is online.<br />
				<input type="submit" name="forceAgentRegistration" value="Force / Manual Agent Registration" />
			</fieldset>
		</form>
</div>
<br />
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
