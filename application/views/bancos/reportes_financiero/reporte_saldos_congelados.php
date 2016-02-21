<style type="text/css">
    table th, td {
     border: 0px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 8px;
}
</style>
<script type="text/javascript">
	var accion_excel="<?php echo base_url('bancos/reportes_financiero/get_saldos_congelados/1'); ?>";
 	var accion_pdf="<?php echo base_url('bancos/reportes_financiero/get_saldos_congelados/2'); ?>";
</script>
<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Datos Generales</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="70%" align="left" border="0" style="margin-top:30px;">
        		<tr><th width="15%"></th><th></th><th></th><th></th></tr>
        		<tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><label>Fondo:</label></td>
        			<td colspan="3">
        				<select class="form-control select2" id="fondo" name="fondo" placeholder="seleccione">
        					<?php echo (isset($fondos))? $fondos:null; ?>
        				</select>	
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr> 
        		<td width="10%"><label>Específico:</label></td>
        			<td colspan="3">
        				<select class="form-control select2" id="especifico" name="especifico" placeholder="seleccione">
        				</select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
                <tr>
                <td width="10%"><label>Fecha inicio:<b style="color:red;">*</b></label></td>
                    <td>
                        <input id="fecha_inicio" name="fecha_inicio" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control fecha">
                    </td>
                    <td width="10%"><label>Fecha fin:<b style="color:red;">*</b></label></td>
                    <td>
                        <input id="fecha_fin" name="fecha_fin" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control fecha">
                    </td>
                </tr>
        		<tr>
                    <td></td>
        			<td align="left" colspan="3"><button id="buscar" name="buscar" type="button" class="btn btn-primary"> <span class="fa fa-search"> Buscar</span></button></td>
        		</tr>
        	</table>
        </div>	
	</div>

	<div class="panel panel-default" id="contenedor_informe" style="display:none;"> 
        <div class="panel-heading">
        	<b>Detalles</b> 
        </div>
       <div class="panel panel-default">
        <div class="panel-heading">
            <b></b> 
        </div>
       
        <div class="panel-body">
            <!-- All your code here -->
            <form target="_blank" id="frm-descarga" method="POST">
                <input type="hidden" name="id_fondo" id="id_fondo">
                <input type="hidden" name="id_especifico" id="id_especifico">
                <input type="hidden" name="fecha_in" id="fecha_in">
                <input type="hidden" name="fecha_out" id="fecha_out">
                <div class="form-actions">
                    <button type="button"  onclick="javascript: this.form.action=accion_excel; this.form.submit(); " class="btn btn-info" title="Exportar a excel" id="" ><strong><span class="icomoon-icon-file-excel white"></span>Exportar a excel</strong></button>
                    <button type="button"  onclick="javascript: this.form.action=accion_pdf; this.form.submit(); " class="btn btn-info" title="Exportar a PDF" id="" ><strong><span class="icomoon-icon-file-pdf white"></span>Exportar PDF</strong></button>
                </div>
            </form>
            <br><br>
            <div class="content noPad clearfix">
        		<div class="panel-body" id="div_detalle">
        		<!-- All your code here -->
        		</div>	
    		</div>    	
    	</div>
        </div> <!-- Fin panel-bogy -->
		</div>  <!-- Fin panel-default -->          	

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

        $("#fondo").on('change', function(){
        var fondo = parseInt($(this).val());
        if(fondo>0){
            $.ajax({
            url: urlj+'bancos/especificos/get_especifico_fondo',
            type: 'POST',
            dataType: 'json',
            data: {fondo : fondo},
            success:function(data) {
              $("#especifico").html(data.especificos_origen);
            }
        });
        } else {
            $("#especifico").html("<option value='0' saldo='0'>Seleccione</option>").trigger('change');
        }
        });

		$("#buscar").on('click',function(){
			
			if( $.trim($("#fecha_inicio").val())!='' && $.trim($("#fecha_fin").val()) != '' )
			{	

			$.ajax({
	            url: urlj+'bancos/reportes_financiero/get_saldos_congelados',
	            type: 'POST',
	            dataType: 'json',
	            data: {id_fondo : $("#fondo").val(), id_especifico: $("#especifico").val(), fecha_in: $("#fecha_inicio").val(), fecha_out: $("#fecha_fin").val() },
	            success:function(data) {
                    $("#id_fondo").val($("#fondo").val());
                    $("#id_especifico").val($("#especifico").val());
                    $("#fecha_in").val($("#fecha_inicio").val());
                    $("#fecha_out").val($("#fecha_fin").val());
	               $("#contenedor_informe").show("slide", { direction: "left" }, 1000);
	               $("#div_detalle").html('').hide("slide", {direction: "right"}, 1000);
                   $("#div_detalle").html(data.drop).show("slide", { direction: "left" }, 1000);
                    $('#dataTables-example').DataTable({
                        responsive: true,
                        emptyTable: "No existen registros",     
                    });
                    $("#td_temporal").attr("colspan",6);
                    $(".drop").remove();
	            }
	        });

			} else {
				alertify.error("Los campos con * en rojo son obligatorios");
			}
		});	

	});
</script>

