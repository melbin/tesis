
<?php
$html = '';
	foreach ($solicitudes as $key => $value) {
	
		  	$html .= '<tr>'.
		  			'<td>'.$key.'</td>'.
	            '<td>'.$value['dpi_nombre'].'</td>'.
	            '<td>'.$value['sol_fecha'].'</td>'.
	            '<td>'.$value['ali_nombre'].'</td>'.
	            '<td> '.$value['ets_nombre'].' </td>'.
	            '<td class="center">X</td>'.
        		'</tr>';		
	}
	echo $html;

 ?>