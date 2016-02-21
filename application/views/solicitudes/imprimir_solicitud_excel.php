<style type="text/css">

	#nombre {
		position: fixed;
		text-align: center;
		padding-left: 450px;
		width: 400px;
	}
	#table_info {
		float: left;
		margin-top: 140px;
		width: 90%;
	}
	.borde {
		border: black 1px solid;	
	}

	#detalle_prod th {
		font-size: 12px;
	}
	@media all {
   div.saltopagina{
      display: none;
   }
}
   
@media print{
   div.saltopagina{ 
      display:block; 
      page-break-before:always;
   }
}


</style>

<table width="100%" border="0">
	<tr>
		<th width="22%"></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
	
	</tr>
	<tr>
		<td><img src="<?php echo base_url();?>media/sistema/escudo02.jpg" width="60" height="90" style="float:left;"></td>
		<td></td>
		<td></td>
		<td colspan="2" align="center">

			<b>MINISTERIO DE SALUD</b><br>
			<b>REGION OCCIDENTAL</b><br>
			<font size="2">DEPARTAMENTO DE ABASTECIMIENTO</font><br>
			<b>SOLICITUD DE COMPRA</b><br>	

		</td>
		
		<td></td>
		<td></td>
		<td colspan="2" align="right"><img src="<?php echo base_url();?>media/sistema/ministerio_salud.jpg" width="120" height="60">	</td>
	</tr>
</table>

<!-- Tema -->


<div> <!--  id="table_info" -->
	<table border="0" width="100%">
		<!-- <tr><th width="5%"></th><th width="30%"></th><th></th><th></th><th></th><th></th></tr> -->
		<tr><th width="15%"></th><th width="15%"></th><th width="15%"></th><th width="15%"></th><th width="5%"></th><th width="5%"></th><th></th><th></th></tr>
		<tr>
			<td colspan="4"></td>
			<td><b>FECHA:</b></td>
			<td align="center" class="borde"><?php echo $dia; ?></td>
			<td align="center" class="borde"><?php echo $mes; ?></td>
			<td align="center" class="borde"><?php echo $anio; ?></td>
		</tr>
		<tr>
			<td colspan="3"><b>SOLICITUD No.:</b></td>
			<td colspan="5" class="borde" align="left"><?php echo $detalle_sol['sol_id']; ?></td>
		</tr>
		<tr>
			<td colspan="3"><label>DEPENDENCIA SOLICITANTE:</label></td>
			<td colspan="5" class="borde"><?php echo $detalle_sol['dpi_nombre']; ?></td>
		</tr>
		<tr>
			<td colspan="3"><label>PLAZO DE ENTREGA EN DIAS CALENDARIO:</label></td>
			<td colspan="5" class="borde"><?php echo $detalle_sol['des_plazo_entrega'] .' '. 'DIAS DESPUES DE RECIBIDA LA ORDEN DE COMPRA'; ?></td>
		</tr>
		<tr>
			<td colspan="4"><label>NÚMERO DE ENTREGAS:</label></td>
			<td class="borde"><label>UNA:  ___X___</label></td>
			<td class="borde"><label>DOS:  _______</label></td>
			<td class="borde" colspan="2"><label>MÁS:  _______</label></td>
		</tr>
		<tr>
			<td colspan="4"><label>ALMACEN O LUGAR DE ENTREGA DE LOS SUMINISTROS SOLICITADOS:</label></td>
			<td colspan="4" class="borde"><?php echo $detalle_sol['ali_nombre']; ?></td>
		</tr>
		<tr>
			<td colspan="3"><label>DIRECCIÓN DE ALMACEN O LUGAR DE ENTREGA:</label></td>
			<td colspan="5" class="borde"><?php echo $detalle_sol['ali_direccion']; ?></td>
		</tr>
		<tr>
			<td colspan="3"><label>PERIODO DE UTILIZACIÓN:</label></td>
			<td colspan="5" class="borde"><b>INDEFINIDO</b></td>
		</tr>
		<tr>
			<td colspan="3"><label>CLASE DE SUMINISTRO O SERVICIO:</label></td>
			<td colspan="5" class="borde"><label><?php echo $detalle_sol['cat_nombre'] .' ( '.$detalle_sol['cat_codigo'] .' )'; ?></label></td>
		</tr>
		<tr>
			<td colspan="3"><label>FUENTE DE FINANCIAMIENTO:</label></td>
			<td colspan="5" class="borde"><label><b><?php echo $detalle_sol['fon_nombre']; ?></b></label></td>
		</tr>
		<tr>
			<td colspan="3"><label>MONTO PRESUPUESTADO:</label></td>
			<td></td>
			<td colspan="1" class="borde" rowspan="2" align="center"><label><b>$ <?php echo number_format($detalle_sol['des_total'],2); ?></b></label></td>
			<td colspan="3" class="borde" rowspan="2" align="center"><label><?php echo $valor_letras; ?></label></td>
		</tr>

	</table>

	<br><br>
	
	<table border="1" width="100%" id="detalle_prod"> 
		<tr>
			<th width="5%" rowspan="2">#</th>
			<th width="10%" rowspan="2">CÓDIGO DEL PRODUCTO</th>
			<th width="10%" rowspan="2">CÓDIGO DEL PRODUCTO SEGÚN CATALOGO NACIONES UNIDAS</th>
			<th width="35%" rowspan="2">DESCRIPCION DE SUMINISTROS O SERVICIO</th>
			<th width="5%" rowspan="2">UNIDAD DE MEDIDA</th>
			<th width="5%" rowspan="2">CANTIDAD</th>
			<th colspan="2">MONTOS ESTIMADOS EN DOLARES</th>
		</tr>
		<tr> <!-- Segunda fila   -->
			<td align="center" style="font-size: 12px;">UNITARIO</td>
			<td align="center" style="font-size: 12px;">TOTALES</td>
		</tr>

		<?php
			$suma=0; 
    		foreach ($productos as $key => $value) {   ?>
	        <tr>
	            <td align="center"><?php echo ++$key; ?></td>
	            <td align="center"><?php echo $value['pro_codigo']; ?></td>
	            <td align="center"><?php echo $value['pro_codigo_nac']; ?></td>
	            <td align="center"><?php echo $value['pro_descripcion']; ?></td>
	            <td align="center"><?php echo $value['uni_valor']; ?></td>
	            <td align="center"><?php echo $value['pxs_cantidad']; ?></td>
	            <td align="center"><?php echo '$ '. number_format($value['pxs_precio'],2); ?></td>
	            <td align="center"><?php echo '$ '. number_format($value['pxs_precio']*$value['pxs_cantidad'],2); ?></td>
	        </tr>
    	<?php 
    		  $suma = $suma + number_format($value['pxs_precio']*$value['pxs_cantidad'],2);
    		} ?>    
    	<?php if(empty($productos)){ ?>
    		<tr><td colspan="8" align="center">No hay registros...</td></tr>
   		<?php } else { ?>
   				<tr>
   					<td colspan="6"></td>
   					<td align="center"><label><b>TOTAL...</b></label></td>
   					<td align="center"><label><b><?php echo '$ '. $suma; ?></b></label></td>
   				</tr>
   		 <?php } ?>
	</table>
	<div class="saltopagina"></div>
	<!-- Datos de los Involucrados en el Proceso -->
	<br><br><br><br><br><br>
	<table border="0" width="100%">
		<tr><th width="15%"></th><th width="15%"></th><th width="15%"></th><th width="15%"></th><th width="5%"></th><th width="5%"></th><th></th><th></th></tr>
		<tr>
			<td colspan="8"><b><?php echo !empty($coordinador_abastecimiento)? $coordinador_abastecimiento:''; ?></b></td>
		</tr>
		<tr>
			<td colspan="8">Coordinador de Abastecimiento Regional</td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="4"><b>Unidad Solicitante:</b></td>
			<td colspan="4"><b>Autoriza:</b></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="4">Firma:</td>
			<td colspan="4">Firma:</td>
		</tr>
		<tr>
			<td>Nombre:</td>
			<td colspan="3"><b><?php echo !empty($solicitante['per_nombre'])? $solicitante['per_nombre'].' '.$solicitante['per_apellido']:''; ?></b></td>
			<td>Nombre:</td>
			<td colspan="3"><b><?php echo !empty($coordinador_primer_nivel)? $coordinador_primer_nivel:''; ?></b></td>
		</tr>
		<tr>
			<td>Cargo:</td>
			<td colspan="3"><?php echo !empty($solicitante['per_cargo'])? $solicitante['per_cargo']:''; ?></td>
			<td>Cargo:</td>
			<td colspan="3">Coordinador División Administrativa Regional de Salud de Primer Nivel, MINSAL</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<td colspan="8"><b>Certificación de Fondos:</b></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="4">Firma:</td>
			<td colspan="4">Firma:</td>
		</tr>
		<tr>
			<td>Nombre:</td>
			<td colspan="3"><b><?php echo !empty($jefe_ufi)? $jefe_ufi:''; ?></b></td>
			<td>Nombre:</td>
			<td colspan="3"><b><?php echo !empty($director_regional)? $director_regional:''; ?></b></td>
		</tr>
		<tr>
			<td>Cargo:</td>
			<td colspan="3">Jefe UFI, MINSAL</td>
			<td>Cargo:</td>
			<td colspan="3">Directora Regional de Salud Occidental</td>
		</tr>
	</table>
</div>	
<br><br>