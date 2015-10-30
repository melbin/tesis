<style type="text/css">
	table th, td {
	 	border: 0px solid black;
    	border-collapse: collapse;	
	}	

	th, td {
		padding: 5px;
	}
</style>

<div>
<form  id="frm_solicitud" name="frm_solicitud" method="POST" action="<?php echo base_url()?>bancos/especificos/asignar_fondos"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Detalles</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->

        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		
                <tr>
        		<td width="10%"><label>Fondo:</label><b style="color:red;"> *</b></td>
        			<td colspan="2">
        				<select class="form-control select2" id="fondo" name="fondo" placeholder="seleccione" onchange="$('#fondo_error').text('');">
        					<?php if(isset($fondo)) {echo $fondo;} ?>
        				</select>
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Específico:</label><b style="color:red;"> *</b></td>
        			<td colspan="2">
        				<select class="form-control select2" id="especifico" name="especifico" placeholder="seleccione" onchange="$('#especifico_error').text('');">
        					<?php if(isset($especificos)) {echo $especificos;} ?>
        				</select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><label>Cantidad:</label><b style="color:red;"> *</b></td>
	        		<td colspan="2">
	        			<div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control decimales" placeholder="0.00" id="cantidad" name="cantidad" maxlength="8">	
	        			</div>	        			
                        <div id="cantidad_error" style="color:red;font-size:11px;"></div>				
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
        		<tr>
        			<td>
        				<div class="form-group"></div>
        			</td>
        		</tr>
        		<tr>			
        			<div class="form-actions">
        				<td><button type="button" class="btn btn-success" id="registrar_entrada"><span class="fa fa-check white"></span> Procesar</button></td>     
        				<td><a href="<?php echo base_url()?>bancos/especificos" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></a></td>
        		</tr>
        	</table>

        </div> <!-- End panel-body -->        	
</div> <!-- End panel-default -->        	
</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

		$("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});
		$(".decimales").validarCampo('0123456789.,'); 

		$("#cantidad").blur(function(){
			$("#cantidad_error").text('');
			if($(this).val()==0){
				$("#cantidad").val('');
				 alertify.error('Debe especificar una cantidad mayor');
			} else 
            if(parseFloat($(this).val()) > parseFloat($("#fondo option:selected").attr('saldo'))){
                alertify.error('Debe especificar una cantidad Menor');
                $(this).val('');
            }
		});

		$("#fondo").on('change', function(){
         	if($(this).select2('val')!==null){
            	alertify.success("El saldo de este fondo es: <b>$"+$.number($("#fondo option:selected").attr('saldo'),2)+'<b>');
        	}
	  	});
		    
		    // Codigo para los select
		$("#registrar_entrada").on('click', function(){
			if($("#fondo").val()==0){
				$("#fondo_error").text('Debe seleccionar un fondo');
			}
			if($("#especifico").val()==0){
				$("#especifico_error").text('Debe seleccionar un específico');
			}
			if($("#cantidad").val()==0 || $("#cantidad").val()==''){
				$("#cantidad_error").text('Debe agregar una cantidad');
			}
			if($("#cantidad").val()>0 && $("#especifico").val()>0 && $("#fondo").val()){
				$("#frm_solicitud").submit();
			}
		});  
        
        $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });
	});
</script>