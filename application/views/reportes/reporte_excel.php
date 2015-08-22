<?php 
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=" . $filename . ".xls");
?>

<table>
	<tr>
		<td colspan="8" align="center"><?php echo $table_header;?></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>

<table>
    <tbody>
        <?php echo $table_tbody?>
    </tbody>
</table>
