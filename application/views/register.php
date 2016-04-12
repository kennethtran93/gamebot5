<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The Individual Player Page
 */
?>
<div>
	<i>
		Have an account with us already?  Login using the form in the navigation above!<br /><br />
		Forgotten your password?  Contact one of this broker agent's administrator for assistance.<br /><br />
		Otherwise, fill in the registration form below to get started!
	</i>
</div>
<br /><br />
<div id="registration">
	<form method="POST" enctype="multipart/form-data">
		<table>
			<tr>
				<td><label for="regUsername">Username:</label></td>
				<td>
					<input type="text" name="regUsername" id="regUsername" class="textbox" maxlength="25" value="{regUsername}"/>
				</td>
				<td><span class="error">{regUsernameError}</td>
			</tr>
			<tr>
				<td><label for="regPassword">Password:</label></td>
				<td>
					<input type="password" name="regPassword" id="regPassword" class="textbox" value=""/>
				</td>
				<td><span class="error">{regPasswordError}</td>
			</tr>
			<tr>
				<td><label for="regConfirmPassword">Confirm Password:</label></td>
				<td>
					<input type="password" name="regConfirmPassword" id="regConfirmPassword" class="textbox" value=""/>
				</td>
				<td><span class="error">{regPasswordError}</td>
			</tr>
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
					<input type="submit" name="regSubmit" id="regSubmit" value="Create Account" />
				</td>
			</tr>
		</table>
	</form>
</div>