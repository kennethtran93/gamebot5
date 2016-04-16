<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Login Form
 */
?>
<form class="login" method="post">
	<table>
		<tr>
			<td><label for="username">Username:</label></td>
			<td><input type="text" name="username" id="username" class="textbox" maxlength="25" placeholder="Username"/></td>
			<td><label for="password">Password:</label></td>
			<td><input type="password" name="password" id="password" class="textbox" placeholder="Password"/></td>
			<td><input type="submit" name="login" value="Login"/></td>
			<td><input type="submit" name="register" value="Register" formnovalidate="formnovalidate"/></td>
		</tr>
	</table>
</form>
