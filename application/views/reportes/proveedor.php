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
	var accion_excel="<?php echo base_url('home/reportes/imprimir_proveedor/1'); ?>";
 	var accion_pdf="<?php echo base_url('home/reportes/imprimir_proveedor/2'); ?>";
</script>
<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Datos Generales</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="50%" align="left" border="0">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		<tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><label>Proveedor:<b style="color:red;">*</b></label></td>
        			<td colspan="2">
        				<select class="form-control select2" id="proveedor" name="proveedor" placeholder="seleccione">
        					<?php echo (isset($proveedor))? $proveedor:null; ?>
        				</select>	
                        <div id="proveedor_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
        			<td align="center" colspan="3"><button id="buscar" name="buscar" type="button" class="btn btn-primary"> <span class="fa fa-search"> Buscar</span></button></td>
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
            <b>Existencias</b> 
        </div>
       
        <div class="panel-body">
            <!-- All your code here -->
            <form target="_blank" id="frm-descarga" method="POST">
                <input type="hidden" name="id_proveedor" id="id_proveedor">
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

		$("#buscar").on('click',function(){
			
			if( $.trim($("#proveedor").val())!='' && $.trim($("#proveedor").val())>0 )
			{	

			$.ajax({
	            url: urlj+'home/reportes/imprimir_proveedor',
	            type: 'POST',
	            dataType: 'json',
	            data: { id_proveedor : $("#proveedor").val() },
	            success:function(data) {
	               $("#id_proveedor").val($("#proveedor").val());
	               $("#contenedor_informe").show("slide", { direction: "left" }, 1000);
	               $("#div_detalle").html('').hide("slide", {direction: "right"}, 1000);
                    $("#div_detalle").html(data.drop).show("slide", { direction: "left" }, 1000);
                    $('#dataTables-example').DataTable({
                        responsive: true,
                        emptyTable: "No existen registros",     
                    });
                    $(".dataTables_empty").text("No se encontraron registros...");
                    // $("#td_temporal").attr("colspan",8);
                    // $(".drop").remove();
	            }
	        });

			} else {
				alertify.error("Debe de seleccionar un proveedor");
			}
		});	

	});
</script>