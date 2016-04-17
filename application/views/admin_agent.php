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
			Current Status:  <b>{agentStatus}</b><span class='{toggleAgentFeedbackType}'>{toggleAgentFeedback}</span><br />
			<input type="submit" name="toggleAgentStatus" value="Toggle Agent Status (Online/Offline)"/>
			<input type="submit" name="forceAgentRegistration" value="Force / Manual Agent Registration (only when status is online)" />
		</fieldset>
	</form>
	<br />
	<form method="post">
		<fieldset>
			<legend>Botcards Server Properties</legend>
			<b>*** NOTE: ***</b><br />
			Updating any of these fields will send a register request to the server, regardless of the current agent status.<br />
			Results from the register response will NOT be stored in our database.<br />
			<table>
				<tr>
					<td><label for="serverURL">Botcards Server URL:</label></td>
					<td><input type="text" name="serverURL" id="serverURL" value="{serverURL}" placeholder="Botcards Server URL"  size="50"/></td>
					<td><span class='{serverURLFeedbackType}'>{serverURLFeedback}</span></td>
				</tr>
				<tr>
					<td><label for="serverPassword">Botcards Server Password:</label></td>
					<td><input type="text" name="serverPassword" id="serverPassword" value="{serverPassword}" placeholder="Botcards Server Password" /></td>
					<td><span class='{serverPasswordFeedbackType}'>{serverPasswordFeedback}</span></td>
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
					<td><span class='{agentCodeFeedbackType}'>{agentCodeFeedback}</span></td>
				</tr>
				<tr>
					<td><label for="agentName">Agent Name:</label></td>
					<td><input type="text" name="agentName" id="agentName" value="{agentName}" placeholder="Agent Name" /></td>
					<td><span class='{agentNameFeedbackType}'>{agentNameFeedback}</span></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='2'><input type="submit" name="changeAgentProperties" value="Change Agent Properties" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<br />
<form method="post" >
	<input type="submit" name="adminReturn" value="Back to Site Administration" />
</form>
