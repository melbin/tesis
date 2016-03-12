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
<form  id="frm_solicitud" name="frm_solicitud" method="POST" action="<?php echo base_url()?>bancos/especificos/traspaso_fondos"> 

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
        		<td width="10%"><label>Específico origen:</label><b style="color:red;"> *</b></td>
        			<td colspan="2">
        				<select class="form-control select2" id="especifico_origen" name="especifico_origen" placeholder="seleccione" onchange="$('#especifico_origen_error').text('');">
        				</select>
                        <div id="especifico_origen_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
                
                <tr>
                <td width="10%"><label>Específico destino:</label><b style="color:red;"> *</b></td>
                    <td colspan="2">
                        <select class="form-control select2" id="especifico_destino" name="especifico_destino" placeholder="seleccione" onchange="$('#especifico_destino_error').text('');">
                            <?php if(isset($especifico_destino)) {echo $especifico_destino;} ?>
                        </select>
                        <div id="especifico_destino_error" style="color:red;font-size:11px;"></div>
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
                <td width="10%"><label>Descripción: <b style="color:red;"> *</b></label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea "$('#descripcion_error').text('');" id="descripcion" name="descripcion" style="width:100%"></textarea>
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

		//$("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});
		$(".decimales").validarCampo('0123456789.,'); 

		$("#cantidad").blur(function(){
			$("#cantidad_error").text('');
			if($(this).val()==0){
				$("#cantidad").val('');
				 alertify.error('Debe especificar una cantidad mayor.');
			} else {
                if(parseFloat($(this).val()) > parseFloat($("#especifico_origen option:selected").attr('saldo'))){  
                 alertify.alert('No hay fondos suficientes.<br>El <b>fondo</b> para el específico seleccionado es de: <b>$'+ $.number($("#especifico_origen option:selected").attr('saldo'),2) +'</b>').setHeader('');  
                 $(this).val(''); 
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
                    data: {fondo : fondo},
                    success:function(data) {
                      $("#especifico_origen").html(data.especificos_origen).trigger('change');


                    }
                });
                } else {
                    $("#especifico_origen").html("<option value='0' saldo='0' selected>Seleccione</option>").trigger('change');
                }
	  	});

        $("#especifico_origen").on('change', function(){
            $("#especifico_destino option").each(function(){
                $(this).attr('disabled',false);
                $(this).attr('selected',false);
            });
            $("#especifico_destino").select2("destroy").select2({theme: "classic"});

            if($(this).select2('val')!==null){
                if(parseInt($("#especifico_origen option:selected").attr('saldo'))!=0){
                    alertify.success("El saldo Libre es: <b>$"+$.number($("#especifico_origen option:selected").attr('saldo'),2)+'<b>');    

                    $("#especifico_destino option").each(function(){
                        if($(this).val() === $("#especifico_origen").val()){
                            $(this).attr('disabled',true);      
                        }
                    }); 
                    $("#especifico_destino").trigger('change');
                }
            }
        });
		    
		    // Codigo para los select
		$("#registrar_entrada").on('click', function(){

			if($("#fondo").val()==0){
				$("#fondo_error").text('Debe seleccionar un fondo');
			}
			if($("#especifico_origen").val()==0){
				$("#especifico_origen_error").text('Debe seleccionar un específico');
			}
            if($("#especifico_destino").val()==0){
                $("#especifico_destino_error").text('Debe seleccionar un específico');
            }
			if($("#cantidad").val()==0 || $("#cantidad").val()==''){
				$("#cantidad_error").text('Debe agregar una cantidad');
			}
            if($.trim($("#descripcion").val()) == ''){
                $("#descripcion_error").text('Debe agregar una descripción');
            }
			if($("#cantidad").val()>0 && $("#especifico_origen").val()>0 && $("#especifico_destino").val()>0 && $("#fondo").val()>0 && $.trim($("#descripcion").val()) != '' ){
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