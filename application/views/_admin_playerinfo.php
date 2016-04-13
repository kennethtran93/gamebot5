<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * A Table row for Player Info
 */
?>
{Players}
<tr>
	<td class="control"></td>
	<td class="all"><input type="checkbox" name="{Player}" value="checked" /></td>
	<td class="all">{Player}</td>
	<td>{Avatar}</td>
	<td>{AccessLevel}</td>
	<td>{DateRegistered}</td>
	<td>{LastUpdated}</td>
</tr>
{/Players}