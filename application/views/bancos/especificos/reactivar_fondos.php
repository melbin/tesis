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
<form  id="frm_solicitud" name="frm_solicitud" method="POST" action="<?php echo base_url()?>bancos/especificos/reactivar_fondos"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Detalles</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->

        	<table width="60%" align="left">
        		<tr><th width="30%"></th><th></th><th></th></tr>
        		
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
        				</select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
                <tr>
                <td width="10%"><label>Presupuesto Votado:</label></td>
                    <td colspan="2">
                        <div class="form-group input-group">    
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input type="text" class="form-control" placeholder="0.00" id="presupuesto_votado" name="presupuesto_votado" maxlength="16" disabled="disabled" style="width: 392px;">   
                        </div>                      
                        <div id="presupuesto_votado_error" style="color:red;font-size:11px;"></div>               
                    </td>
                </tr>                
                <tr>
                <td width="10%"><label>Saldo actual:</label></td>
                    <td colspan="2">
                        <div class="form-group input-group">    
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input type="text" class="form-control" placeholder="0.00" id="saldo_actual" name="saldo_actual" maxlength="16" disabled="disabled"  style="width: 392px;">   
                        </div>                      
                        <div id="saldo_actual_error" style="color:red;font-size:11px;"></div>               
                    </td>
                </tr>

        		<tr>
        		<td width="10%"><label>Cantidad congelada:</label></td>
	        		<td colspan="2">
	        			<div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control decimales" placeholder="0.00" id="cantidad_congel_label" maxlength="16" disabled="disabled" style="width: 392px;">	
	        			</div>	        			
                        <div id="cantidad_error" style="color:red;font-size:11px;"></div>				
        			</td>
        		</tr>
                <tr>
                <td width="10%"><label>Cantidad a descongelar:</label><b style="color:red;"> *</b></td>
                    <td colspan="2">
                        <div class="form-group input-group">    
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input type="text" class="form-control decimales" placeholder="0.00" id="cantidad" name="cantidad" maxlength="16"  style="width: 392px;">   
                            <input type="hidden" id="cantidad_congelada" value="0">
                        </div>                      
                        <div id="cantidad_error" style="color:red;font-size:11px;"></div>               
                    </td>
                </tr>
        		<tr>
                <td width="10%"><label>Descripción:</label><b style="color:red;"> *</b></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea onchange="$('#descripcion_error').text('');" id="descripcion" name="descripcion" style="width:100%"></textarea>
                            <div id="descripcion_error" style="color:red;font-size:11px;"></div>
                        </div>              
                    </td>
                </tr>
        		<tr>
        		<td width="10%"><label>Fecha:</label></td>
        			<td colspan="2">
        				<input id="fecha_registro" name="fecha_registro" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control fecha">
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
                        <td></td>
        				<td><button type="button" class="btn btn-success" id="registrar_entrada"><span class="fa fa-check white"></span> Procesar</button>
        				<a href="<?php echo base_url()?>bancos/bancos" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></a></td>
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

        $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

		//$("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});
		$(".decimales").validarCampo('0123456789.,'); 

		$("#cantidad").blur(function(){
			$("#cantidad_error").text('');
			if($(this).val()==0){
				$("#cantidad").val($("#cantidad_congelada").val());
				 alertify.error('Debe especificar una cantidad mayor.');
			} else {
                if(parseFloat($(this).val()) > parseFloat($("#cantidad_congelada").val())){  
                 alertify.error('El monto asignado excede los fondos congelados.');  
                 $(this).val($("#cantidad_congelada").val()); 
                }
            }
		});

		$("#fondo").on('change', function(){
            	
                var fondo = parseInt($(this).val());
                
                if(fondo>0){
                    $.ajax({
                    url: urlj+'bancos/especificos/get_especifico_fondo',
                    type: 'POST',
                    dataType: 'json',
                    data: {fondo : fondo, reactivar: 1},
                    success:function(data) {
                      $("#especifico").html(data.especificos_origen);


                    }
                });
                } else {
                    $("#especifico").html("<option value='0' saldo='0'>Seleccione</option>").trigger('change');
                }
	  	});

        $("#especifico").on('change', function(){   
                var id_esp = parseInt($(this).val());
                var id_fondo = $("#fondo").val();
                if(id_esp>0){
                    $.ajax({
                    url: urlj+'bancos/especificos/get_especifico_detalle',
                    type: 'POST',
                    dataType: 'json',
                    data: {id_esp :id_esp, id_fondo:id_fondo},
                    success:function(data) {
                      $("#presupuesto_votado").val($.number(data.det_saldo_votado,2));
                      $("#saldo_actual").val($.number(data.det_saldo,2));
                      $("#cantidad, #cantidad_congelada, #cantidad_congel_label").val($.number(data.det_saldo_congelado,2,'.',''));
                      //$("#especifico").html(data.especificos_origen).trigger('change');


                    }
                });
                } else {
                    $("#especifico").html("<option value='0' saldo='0' selected>Seleccione</option>").trigger('change');
                }
        });

		    
		    // Codigo para los select
		$("#registrar_entrada").on('click', function(){
			if($("#fondo").val()==0){
				$("#fondo_error").text('Debe seleccionar un fondo');
			}
			if(!$("#especifico").length){
				$("#especifico_error").text('Debe seleccionar un específico');
			}
			if($("#cantidad").val()==0 || $("#cantidad").val()==''){
				$("#cantidad_error").text('Debe agregar una cantidad');
			}
            if($.trim($("#descripcion").val()) == ''){
                $("#descripcion_error").text('Debe agregar una descripción');
            }
			if($("#cantidad").val()>0 && $("#especifico").val()>0 && $("#fondo").val()>0 && $.trim($("#descripcion").val()) != '' ){

				$("#frm_solicitud").submit();
			}
		});  
	});
</script>