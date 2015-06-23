<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">
<script src="<?php echo base_url()?>js/solicitudes/solicitudes.js"></script>  

<div>
<form class="" id="frm_solicitud" name="frm_solicitud" method="POST" action="<?php echo base_url()?>home/solicitudes/entrada_solicitud"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Solicitudes de Artículos</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
            
        	<table width="50%" align="left">
        		<tr><th width="23%"></th><th></th><th></th></tr>
        		
                <tr> <!-- <i id="requerido">*</i> -->
        		<td width="10%"><h5>Solicitante:<b style="color:red;">*</b></h5></td>
        			<td colspan="2">
        				<select class="form-control select2" id="dpi_interno" name="dpi_interno" placeholder="seleccione">
        					<?php if(isset($dep_internos)) {echo $dep_internos;} ?>
        				</select>
                        <div id="dpi_interno_error" style="color:red;font-size:11px;"></div>
        			</td>
        		</tr>
        		
                <tr>
        		<td width="10%"><h5>Plazo entrega:</h5></td>
        			<td width="">
        				<!-- <textarea id="plazo_entrega" name="plazo_entrega" style="width:100%"></textarea> -->
                        <input id="plazo_entrega" name="plazo_entrega" type="text"  maxlength="2"  class="datetime-input form-control enteros" >
        			</td>
                    <td><input type="text"  class="datetime-input form-control" value="Dias" disabled="disabled" style="text-align:center;"></td>
        		</tr>

        		<tr>
        		<td width="10%"><h5>Fecha registro:</h5></td>
        			<td colspan="2">
        				<input id="fecha_entrega" name="fecha_entrega" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control">
        			</td>
        		</tr>
                <tr>
                <td width="10%"><h5>Numero entregas:</h5></td>
                    <td colspan="2">
                        <input id="numero_entrega" name="numero_entrega" type="text" value="1" maxlength="1"  class="datetime-input form-control enteros">
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
                        <textarea id="lugar_entrega" name="lugar_entrega" style="width:100%" disabled="disabled"></textarea>
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Categoría:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="categoria" name="categoria" placeholder="seleccione">
                            <?php if(isset($categoria)) {echo $categoria;} ?>
                        </select>
                        <div id="categoria_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>

                <tr>
                <td width="10%"><h5>Financiamiento:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="fondo" name="fondo" placeholder="seleccione">
                            <?php if(isset($fondo)) {echo $fondo;} ?>
                        </select>
                        <div id="fondo_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>

        		<tr>

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
                        <select class="form-control select2" id="sub_categoria" name="sub_categoria" placeholder="seleccione" disabled="disabled">
                            
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
                        <div class="form-group">    
                            <textarea id="descripcion" name="descripcion" style="width:100%" disabled="disabled"></textarea>
                        </div>              
                    </td>
                </tr>
                <tr class="unidad_medida">
                <td width="10%"><h5>Unidad de medida:<b style="color:red;">*</b></h5></td>
                    <td colspan="2">
                        <select class="form-control select2" id="um" name="um" style="width:100%;">
                            <?php if(isset($unidad_medida)) {echo $unidad_medida;} ?>
                        </select>
                        <div id="um_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
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
            </table>    
 		</div>
    </div>

    <!-- Creacion del datatable -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><b>Detalle de productos</b></h5>
        </div>
        <div class="panel-body">
        <br /><small id="validar_datagried" style="color:red;" ></small>    
        <table class="responsive table table-bordered contenedor" id="datagried" name="datagried">
        <thead id="cabezera" style="display:none;">
        <tr>
            <th style="display:none;">
                Id Producto
            </th>
            <th>
                Nombre de Artículo
            </th>
            <th style="">
                Cantidad
            </th>
            <th style="">
                Precio ($)
            </th>
            <th style="">
                Sub-Total
            </th>
            <!--<th style="">
                Fecha de caducidad
            </th>-->
             <th style="">
                Acción
            </th>
        </tr>
        </thead>
    <tbody id="contenedor">

    </tbody>
</table>
<div style="text-align: center;">
    <span><label id="total_suma"></label></span>
    <input id="total_suma_hidden" name="total" type="hidden">
</div>    
    <div class="form-actions">
        <button type="button" class="btn btn-success" id="registrar_solicitud" disabled><span class="fa fa-check white"></span> Procesar</button>
        <!-- <button type="button" class="btn btn-danger" id="anular" disabled><span class="icomoon-icon-cancel-3"></span>Anular</button> -->
    </div>

        </div>
    </div>

    </form>
</div>