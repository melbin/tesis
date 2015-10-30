<style type="text/css">
    #return {
    	position: relative;
  		margin-left: 90%;
    }
</style>

<form method="POST" action="<?php echo base_url()?>bancos/especificos/detalle_especifico">
    <button type="submit" id="return" class="btn btn-outline btn-info fa fa-mail-reply"> Regresar</button>
</form>

<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">
<script src="<?php echo base_url()?>js/bancos/detalle_especifico.js"></script>  

<div>
<form class="" id="pro_entrada" name="pro_entrada" method="POST" action="<?php echo base_url()?>bancos/especificos/guardar_detalle_especifico"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Datos Generales</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
                <tr>
                <td width="10%"><label>Fondo:<b style="color:red;">*</b></label></td>
                    <td colspan="2">
                        <select onchange="$('#fondo_error').text('');" class="form-control select2" id="fondo" name="fondo">
                            <?php if(isset($fondo)) {echo $fondo;} ?>
                        </select>
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
        		<tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><label>Específico:<b style="color:red;">*</b></label></td>
        			<td colspan="2">
        				<select onchange="$('#especifico_error').text('');" class="form-control select2" id="especifico" name="especifico" placeholder="seleccione">
        					<?php if(isset($especificos)) {echo $especificos;} ?>
        				</select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Saldo:</label></td>
	        		<td colspan="2">
	        			<div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control" placeholder="0.00" id="saldo" name="saldo" maxlength="8" disabled="disabled">	
	        			</div>	        			
                        <div id="saldo_error" style="color:red;font-size:11px;"></div>				
        			</td>
        		</tr>
        		<tr>
                <td width="10%"><label>Descripción:</label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea id="descripcion" name="descripcion" style="width:100%"></textarea>
                        </div>              
                    </td>
                </tr>
        		<tr>
        		<td width="10%"><label>Fecha:</label></td>
        			<td colspan="2">
        				<input id="fecha_registro" name="fecha_registro" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control">
                        <div id="fecha_registro_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        	</table>                                          
       </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
        	<label>Datos Específicos (Asignaciones)</label>
        </div>
        <div class="panel-body">
        	<table width="50%" align="left">
        		<tr><th></th><th></th><th></th></tr>
        		<tr>
                <td width="10%"><label>Departamento:</label></td>
                    <td colspan="2">
                        <select onchange="$('#depto_error').text('');" class="form-control select2" id="depto" name="depto" placeholder="seleccione">
                            <?php if(isset($departamentos)) {echo $departamentos;} ?>
                        </select>
                        <div id="depto_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
                <tr>
        		<td width="10%"><label>Cantidad:</label></td>
	        		<td colspan="2">
	        			<div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control" placeholder="0.00" id="cantidad" name="cantidad" maxlength="8">	
	        			</div>	        			
                        <div id="cantidad_error" style="color:red;font-size:11px;"></div>				
        			</td>
        		</tr>
        		<tr>
        			<td><button id="agregar" name="agregar" type="button" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>
        			<td><button id="cancelar" name="cancelar" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
        		</tr>
            </table>    
 		</div>
    </div>

        <!-- Creacion del datatable -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><b>Detalles</b></h5>
        </div>
        <div class="panel-body">
        <br /><small id="validar_datagried" style="color:red;" ></small>    
        <table class="responsive table table-bordered contenedor" id="datagried" name="datagried">
        <thead id="cabezera" style="display:none;">
        <tr>
            <th>
                #
            </th>
            <th>
                Nombre
            </th>
            <th style="">
                Departamento
            </th>
            <th style="">
                Monto Asignado ($)
            </th>
             <th style="">
                Acción
            </th>
        </tr>
        </thead>
    <tbody id="contenedor">

    </tbody>
</table>
<br>
<div style="text-align: center;">
    <span><label id="total_restante"></label></span>
    <input id="total_restante_hidden" name="total_restante" type="hidden">
    <input id="monto_asignado_total" name="monto_asignado_total" type="hidden" value="-1">
</div>    
    <div class="form-actions">
    <!-- <td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td> -->
        <button type="button" class="btn btn-success" id="registrar_entrada" disabled><span class="fa fa-check white"></span> Procesar</button>
        <!-- <button type="button" class="btn btn-danger" id="anular" disabled><span class="icomoon-icon-cancel-3"></span>Anular</button> -->
    </div>

        </div>
    </div>

    </form>
</div>