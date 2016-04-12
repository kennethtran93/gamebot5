<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Individual Player Page
 */
?>
<div id="accountInfo">

	<table>
		<tr>
			<td>Player Name / Username:</td>
			<td><b>{username}</b></td>
		</tr>
		<tr>
			<td>Account Role:</td>
			<td><b>{role}</b></td>
		</tr>
		<tr>
			<td>Date Registered:</td>
			<td><b>{dateRegistered}</b></td>
		</tr>
		<tr>
			<td>Account Last Updated:</td>
			<td><b>{lastUpdated}</b></td>
		</tr>
	</table>
	<form method="post">
		<fieldset>
			<legend>Password Change</legend>
			<table>
				<tr>
					<td>Current Password</td>
					<td><input type="password" name="curPassword" id="curPassword" class="textbox" /></td>
					<td><span class="error">{curPasswordError}</td>
				</tr>
				<tr>
					<td>New Password</td>
					<td><input type="password" name="newPassword" id="newPassword" class="textbox" /></td>
					<td><span class="error">{newPasswordError}</td>
				</tr>
				<tr>
					<td>Confirm New Password</td>
					<td><input type="password" name="newConfirmPassword" id="newConfirmPassword" class="textbox" /></td>
					<td><span class="error">{newPasswordError}</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="pwSubmit" id="pwSubmit" value="Change Password" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
	<br />
	<form method="POST" enctype="multipart/form-data">
		<fieldset>
			<legend>Avatar Change</legend>
			<table>
				<tr>
					<td><label for="avatarUpload">Avatar Upload:</label></td>
					<td><input type="file" name="avatarUpload" id="avatarUpload" accept=".jpeg, .jpg, .png"/></td>
					<td><span class="error">{avatarError}</span></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="checkbox" name="useGenericAvatar" id="useGenericAvatar" value="generic" /><label for="useGenericAvatar">Use Generic Avatar</label></td>
					<td></td>
				</tr>
				<tr>
					<td style="text-align:right;">Supported File Type:</td>
					<td><b>*.jpeg, *.jpg, *.png</b></td>
					<td></td>
				</tr>
				<tr>
					<td style="text-align:right;">Max File Size:</td>
					<td><b>2 MB</b></td>
					<td></td>
				</tr>
				<tr>
					<td style="text-align:right;">Max Image Dimension:</td>
					<td><b>175px x 200px</b></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="avatarChange" id="avatarChange" value="Change Avatar" />
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>