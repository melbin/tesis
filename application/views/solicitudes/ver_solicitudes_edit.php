<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">
<script src="<?php echo base_url()?>js/solicitudes/solicitudes.js"></script>  

<div>
<form class="" id="frm_solicitud" name="frm_solicitud" method="POST" action="<?php echo base_url()?>home/abastecimiento/ver_solicitudes_edit"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Edición de Solicitud</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->

        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		
                <tr>
                <td width="10%"><h5>Financiamiento:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="fondo" name="fondo" placeholder="seleccione" onchange="$('#fondo_error').text('');" disabled="disabled">
                            <?php if(isset($fondo)) {echo $fondo;} ?>
                        </select>
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>

                <tr>
                <td width="10%"><h5>Específico:<b style="color:red;"> *</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="especifico" name="especifico" placeholder="seleccione" onchange="$('#especifico_error').text('');" disabled="disabled">
                            <?php if(isset($especifico)) {echo $especifico; } ?>
                        </select>
                        <div id="especifico_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>

                <tr> <!-- <i id="requerido">*</i> -->
                <td width="10%"><h5>Solicitante:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="dpi_interno" name="dpi_interno" placeholder="seleccione" onchange="$('#dpi_interno_error').text('');" disabled="disabled">
                            <?php if(isset($dep_internos)) {echo $dep_internos;} ?>
                        </select>
                        <div id="dpi_interno_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
                <input type="hidden" id="dpi_monto_asignado" value="0">
                <input type="hidden" class="asignacion_depto" name="axd_id" value="0">

                <tr>
        		<td width="10%"><h5>Plazo entrega:</h5></td>
        			<td width="">
        				<!-- <textarea id="plazo_entrega" name="plazo_entrega" style="width:100%"></textarea> -->
                        <input id="plazo_entrega" name="plazo_entrega" value="<?php echo $detalle_sol[0]['des_plazo_entrega']; ?>" type="text"  maxlength="2"  class="datetime-input form-control enteros" >
        			</td>
                    <td><input type="text"  class="datetime-input form-control" value="Días" disabled="disabled" style="text-align:center;"></td>
        		</tr>

        		<tr>
        		<td width="10%"><h5>Fecha registro:</h5></td>
        			<td colspan="2">
        				<input id="fecha_entrega" name="fecha_entrega" disabled="disabled" type="text" value="<?php echo date('d-m-Y', strtotime($detalle_sol[0]['sol_fecha'])); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control">
        			</td>
        		</tr>
                <tr>
                <td width="10%"><h5>Numero entregas:</h5></td>
                    <td colspan="2">
                        <input id="numero_entrega" name="numero_entrega" type="text" value="<?php echo $detalle_sol[0]['sol_num_entregas']; ?>" maxlength="1"  class="datetime-input form-control enteros">
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Bodega:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="bodega" name="bodega" placeholder="seleccione">
                            <?php if(isset($bodega)) {echo $bodega;} ?>
                        </select>
                        <div id="bodega_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Dirección Lugar de Entrega:</h5></td>
                    <td colspan="2">
                        <textarea id="lugar_entrega" name="lugar_entrega" style="width:100%" disabled="disabled"><?php echo $detalle_sol[0]['ali_direccion']; ?></textarea>
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Categoría:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="categoria" name="categoria" placeholder="seleccione" disabled="disabled">
                            <?php if(isset($categoria)) {echo $categoria;} ?>
                        </select>
                        <div id="categoria_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
        	</table>                                          
       </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
        	<h5><b>Datos Específicos</b></h5>
        </div>
        <div class="panel-body">
        	<table width="50%" align="left">
        		<tr><th></th><th></th><th></th></tr>
                <tr>
                <td width="10%"><h5>Subcategoría:</h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="sub_categoria" name="sub_categoria" placeholder="seleccione">
                            <!-- <?php if(isset($sub_categoria)) {echo $sub_categoria;} ?> -->
                        </select>
                    </td>
                </tr>

        		<tr>
        		<td width="10%"><h5>Artículo:<b style="color:red;">*</b></h5></td>
        			<td colspan="2">
        				<select class="form-control select2" id="articulo" name="articulo">
        					<?php if(isset($productos)) {echo $productos;} ?>
        				</select>
                        <div id="articulo_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		<tr>
                <td width="10%"><h5>Descripción:</h5></td>
                    <td colspan="2">                        
                        <div class="form-group" style="margin-bottom:-7px; !important">    
                            <textarea id="descripcion" name="descripcion" style="width:100%" disabled="disabled"></textarea>
                        </div>              
                    </td>
                </tr>
                <tr class="unidad_medida">
                    <td width="10%"><h5>Unidad de medida:</h5></td>
                    <td colspan="2"><input type="text"  class="datetime-input form-control" id="um" name="um" value="prueba" disabled="disabled" style="text-align:center;"></td>
                </tr>

                <!-- <tr class="unidad_medida">
                <td width="10%"><h5>Unidad de medida:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="um" name="um" style="width:100%;">
                            <?php if(isset($unidad_medida)) {echo $unidad_medida;} ?>
                        </select>
                        <div id="um_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr> -->
                <tr>
        		<td width="10%"><h5>Cantidad:<b style="color:red;">*</b></h5></td>
	        		<td colspan="2">	        			
                        <div class="form-group">	
		        			<input type="text" class="form-control enteros" maxlength="3" placeholder="Cantidad" id="cantidad" name="cantidad">	
	        			</div>				
                        <div id="cantidad_error" style="color:red;font-size:11px;"></div>             
        			</td>
        		</tr>
        		<tr>
        		<td width="10%"><h5>Precio:<b style="color:red;">*</b></h5></td>
	        		<td colspan="2">	        			
                        <div class="form-group input-group">	
		        			<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
		        			<input type="text" class="form-control decimales" placeholder="Precio" id="precio" name="precio">	
                            <span class="input-group-addon">.00</span>
	        			</div>
                        <div id="precio_error" style="color:red;font-size:11px;"></div>				
        			</td>
        		</tr>
        		<tr>
        			<td><button id="add" name="add" type="button" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>
        			<td><button id="cancelar" name="cancelar" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
        		</tr>
                <!-- hidden -->
                <input type="hidden" name="id_sol" id="id_sol" value="0">
            </table>    
 		</div>
    </div>

    <!-- Creacion del datatable -->
    <div id="data_table_edit">
        <?php echo $detalle_articulos; ?>
    </div>

    </form>
</div>

<div>
    <form class="" id="sol_aceptar" name="sol_aceptar" method="POST" action="<?php echo (!isset($financiero))? base_url().'home/abastecimiento/aprobar_solicitud' : base_url().'home/abastecimiento/aprobar_solicitud2'; ?>"> 
        <input type="hidden" name="solicitud" value="<?php echo $detalle_sol[0]['sol_id']; ?>">
        <input type="hidden" name="tipo_envio" id="tipo_envio" value="0">
    </form>
</div>

<div style="display:none;">
    <div id="form_eliminar" style="width:600px;">
        <div class="panel panel-default">
        <div class="panel-heading">
            <b>Rechazar Solicitud</b> 
        </div>
        <div class="panel-body">
        <form id="sol_rechazar" name="sol_rechazar" method="POST" action="<?php echo base_url()?>home/abastecimiento/rechazar_solicitud"> 
      
          <input type="hidden" name="solicitud" value="<?php echo $detalle_sol[0]['sol_id']; ?>">
          <input type="hidden" class="asignacion_depto" name="axd_id" value="0">
          <?php if(!empty($financiero)){ ?>  
                <input type="hidden" name="departamento" value="2"> <!-- 1= Abastecimiento, 2=financiero -->
          <?php } else { ?>
            <input type="hidden" name="departamento" value="1">
           <?php } ?>
            <table width="100%" align="left">
                <tr><th></th><th></th></tr>
                <tr>
                <td width="20%"><label>Describa el motivo:</label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea id="motivo" name="motivo" placeholder="motivo..." style="width:100%"></textarea>
                            <label id="motivo_error" style="color:red;font-size:11px;"></label>
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td><button id="anular" name="anular" type="button" class="btn btn-success"><span class="fa fa-check white"></span> Aceptar</button></td>
                    <td><button id="cancel" name="cancel" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
                </tr>
              </table>
            </form>    
        </div>
        </div>     
    </div>
</div> <!-- End div form_eliminar -->

<script type="text/javascript">
    $(document).ready(function(){

    var pathArray = window.location.pathname.split( '/' );
    var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

    
    $("#id_sol").val(pathArray[pathArray.length-1]);
    $("#categoria").trigger("change");    
    $("#especifico").trigger("change");
    setTimeout(function(){ 
        $("#dpi_interno").trigger("change");
     }, 800);
    
    // $("#iButton").iButton({labelOn: "Si",labelOff: "No" });

    // $("#iButton").on('change', function(){
    //     if($("#iButton").checked(true)){
    //         alert($(this).val());    
    //     }
    //     //$("#tipo_envio").val($(this).val());
    // }); 

    $("#aceptar").click(function(){
        $("#sol_aceptar").submit();          
    });

    $("#rechazar").click(function(){
        $.fancybox({
            content: $("#form_eliminar"),
            scrolling :'no' 
        });   
    });

    $("#anular").click(function(){
        if($("#motivo").val()!=''){
            $("#sol_rechazar").submit();     
        } else {
            $("#motivo_error").text('Campo requerido');
        }
    });
    $("#cancel").click(function(){
        $("#motivo").val('');
        $.fancybox.close();
    });

    $("#remove").live("click", function() {
    $(this).parents("tr").remove();     
      sumar_total();
      
      if($('#datagried tr').length<=1 ){
            $("#categoria").attr('disabled',false);  
        }    
    });//Fin de eliminar

    }); // End document ready

</script>

