<?php
	$logo='<img height="48px" width="auto" src="'.base_url().'media/sistema/'.$this->regional_model->get_parametro("logo").'.gif">';
	$texto='<b>MINISTERIO DE SALUD<br></b>';
	$texto.='<b>REGIÓN OCCIDENTAL<br></b>';
	$texto.='<br>'.date('d/m/Y').' '.date('h:i A');
	$el_salvador='<img height="48px" width="auto" src="'.base_url().'media/sistema/ministerio_salud.jpg">';
	
?>

<table border=0 style="border:none !important;">
	<tr border="0" style="border:none !important;">
		<td width="15%" align="center" border="0" style="border:none !important;">
			<?php echo $logo; ?>
		</td>
		<td width="70%" align="center" valign="middle" border="0" style="border:none !important;">
			<?php echo $texto; ?>
		</td>
		<td width="15%" align="center" border="0" style="border:none !important;">
			<?php echo $el_salvador; ?>
		</td>
	</tr>

	<tr style="border:none !important;"><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>
	<tr border="0" style="border:none !important;">
		<td width="15%" align="center" border="0" style="border:none !important;"></td>
		<td width="70%" align="center" valign="middle" border="0" style="border:none !important;">
		<?php if (isset($titulo) and $titulo!='') {
			echo "<h3>Reporte de ".$titulo."</h3>";
		} ?></td>
		<td width="15%" align="center" border="0" style="border:none !important;"></td>
	</tr>
</table>

