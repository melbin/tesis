<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">
<script src="<?php echo base_url()?>js/inventario/entrada_articulos.js"></script>  

<div>
<form class="" id="pro_entrada" name="pro_entrada" method="POST" action="<?php echo base_url()?>home/abastecimiento/entrada_de_articulos"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Datos Generales</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		<tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><label>Bodega:</label></td>
        			<td colspan="2">
        				<select class="form-control" id="bodega" name="bodega" placeholder="seleccione">
        					<?php if(isset($articulos)) {echo $articulos;} ?>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Proveedor:</label></td>
        			<td colspan="2">
        				<select class="form-control" id="proveedor" name="proveedor">
        					<?php if(isset($proveedores)) {echo $proveedores;} ?>
        				</select>
        			</td>
        		</tr>
        		<tr>

        		<tr>
        		<td width="10%"><label>Fecha de Registro:</label></td>
        			<td colspan="2">
        				<input id="fecha_registro" name="fecha_registro" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control">
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Tipo de entrada:</label></td>
        			<td colspan="2">
        				<select class="form-control" id="entrada" name="entrada" placeholder="seleccione">
        					<?php if(isset($procesos)) {echo $procesos;} ?>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		

        	</table>                                          
       </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
        	<label>Datos Específicos</label>
        </div>
        <div class="panel-body">
        	<table width="50%" align="left">
        		<tr><th></th><th></th><th></th></tr>
        		<tr>
        		<td width="10%"><label>Artículo:</label></td>
        			<td colspan="2">
        				<select class="form-control" id="articulo" name="articulo">
        					<?php if(isset($productos)) {echo $productos;} ?>
        				</select>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Cantidad:</label></td>
	        		<td colspan="2">	        			
                        <div class="form-group">	
		        			<input type="text" class="form-control" placeholder="Cantidad" id="cantidad" name="cantidad">	
	        			</div>				
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Precio:</label></td>
	        		<td colspan="2">	        			
                        <div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control" placeholder="Precio" id="precio" name="precio">	
	        			</div>				
        			</td>
        		</tr>
        		<tr>
        			<td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>
        			<td><button id="cancelar" name="cancelar" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
        		</tr>
            </table>    
 		</div>
    </div>
    </form>
</div>