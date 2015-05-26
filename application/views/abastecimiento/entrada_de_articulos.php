<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">

<div class="panel panel-default">
        <div class="panel-heading">
        	Datos Generales 
        </div>
        <div class="panel-body">
        	<!-- All your code here -->
        	<table width="50%" align="left">
        		<tr><th></th><th></th><th></th></tr>
        		<tr>
        		<td width="10%"><span>Bodega:</span></td>
        			<td colspan="2">
        				<select class="form-control" id="bodega" name="bodega">
        					<option>Seleccione</option>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><span>Proveedor:</span></td>
        			<td colspan="2">
        				<select class="form-control" id="proveedor" name="proveedor">
        					<option>Seleccione</option>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><span>Fecha de Registro:</span></td>
        			<td colspan="2">
        				<input id="fecha_registro" name="fecha_registro" type="text" value="" maxlength="19" class="datetime-input form-control hasDatepicker">
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><span>Tipo de entrada:</span></td>
        			<td colspan="2">
        				<select class="form-control" id="entrada" name="entrada">
        					<option>Seleccione</option>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		

        	</table>                                          
       </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
        	Datos Especificos 
        </div>
        <div class="panel-body">
        	<table width="50%" align="left">
        		<tr><th></th><th></th><th></th></tr>
        		<tr>
        		<td width="10%"><span>Articulo:</span></td>
        			<td colspan="2">
        				<select class="form-control" id="articulo" name="articulo">
        					<option>Seleccione</option>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><span>Cantidad:</span></td>
	        		<td colspan="2">	        			
                        <div class="form-group input-group">	
		        			<span class="input-group-addon"><i class=""></i></span>
		        			<input type="text" class="form-control" placeholder="Cantidad" id="cantidad" name="cantidad">	
	        			</div>				
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><span>Precio:</span></td>
	        		<td colspan="2">	        			
                        <div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control" placeholder="Precio" id="precio" name="precio">	
	        			</div>				
        			</td>
        		</tr>
        		<tr>
        			<td><button id="add" name="add" type="button">Agregar</button></td>
        		</tr>
 		</div>
    </div>
