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

<div>
<form class="" id="pro_entrada" name="pro_entrada" method="POST" action="<?php echo base_url()?>bancos/especificos/guardar_detalle_especifico_editar"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Datos Generales</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		<tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><label>Específico:<b style="color:red;">*</b></label></td>
        			<td colspan="2">
        				<select onchange="$('#especifico_error').text('');" class="form-control select2" id="especifico" name="especifico" placeholder="seleccione" disabled="disabled">
        					<?php if(isset($especificos)) {echo $especificos;} ?>
        				</select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Fondo:<b style="color:red;">*</b></label></td>
        			<td colspan="2">
        				<select onchange="$('#fondo_error').text('');" class="form-control select2" id="fondo" name="fondo" disabled="disabled">
        					<?php if(isset($fondo)) {echo $fondo;} ?>
        				</select>
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Saldo:</label></td>
	        		<td colspan="2">
	        			<div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control" placeholder="0.00" id="saldo" name="saldo" maxlength="8" value="<?php echo !empty($det_detalles['det_saldo'])? number_format(($det_detalles['det_saldo'] - $det_detalles['det_saldo_ejecutado']),2,'.',''):''; ?>">	
	        			</div>	        			
                        <div id="saldo_error" style="color:red;font-size:11px;"></div>				
        			</td>
        		</tr>
                <tr>
                <td width="10%"><label>Presupuesto Votado:</label></td>
                    <td colspan="2">
                        <div class="form-group input-group">    
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input type="text" class="form-control" placeholder="0.00" id="pre_votado" name="pre_votado" saldo="<?php echo !empty($det_detalles['det_saldo_votado'])? $det_detalles['det_saldo_votado']:0; ?>" maxlength="8" value="<?php echo !empty($det_detalles['det_saldo_votado'])? number_format($det_detalles['det_saldo_votado'],2):''; ?>" disabled="disabled"> 
                        </div>                      
                        <div id="saldo_error" style="color:red;font-size:11px;"></div>              
                    </td>
                </tr>
        		<tr>
                <td width="10%"><label>Descripción: <b style="color:red;">*</b></label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea onchange="$('#descripcion_error').text('');" id="descripcion" name="descripcion" style="width:100%"><?php echo !empty($det_detalles['det_descripcion'])? $det_detalles['det_descripcion']:''; ?></textarea>
                            <div id="descripcion_error" style="color:red;font-size:11px;"></div>
                        </div>              
                    </td>
                </tr>
        		<tr>
        		<td width="10%"><label>Fecha:</label></td>
        			<td colspan="2">
        				<input id="fecha_registro" name="fecha_registro" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control" disabled="disabled fecha">
                        <div id="fecha_registro_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        	</table>                                          
       </div>
    </div>

    <input type="hidden" id="det_id" name="det_id" value="<?php echo $det_detalles['det_id']; ?>">
    <input type="hidden" id="saldo_origen" value="<?php echo !empty($det_detalles['det_saldo'])? $det_detalles['det_saldo']:0; ?>">

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
        			<td><a  href="<?php echo base_url()?>bancos/especificos/detalle_especifico" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></a></td>
        		</tr>
            </table>    
 		</div>
    </div>

        <!-- Creacion del datatable -->
        <?php echo $tabla_asignaciones; ?>
    

    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("input[name='cantidad_depto[]']").numeric();
        $("#fondo").trigger('change', function(){
            alertify.success("El saldo de este fondo es: <b>$"+$.number($("#fondo option:selected").attr('saldo'),2)+'<b>');
      });
    });
</script>
<script src="<?php echo base_url()?>js/bancos/detalle_especifico_editar.js"></script>  