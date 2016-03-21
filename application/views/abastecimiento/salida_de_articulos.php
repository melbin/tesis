<link href="<?php echo base_url()?>stylesheet/sistema/entrada_articulos.css" rel="stylesheet">

<div>
    <form class="" id="pro_salida" name="pro_salida" method="POST" action="<?php echo base_url()?>home/abastecimiento/salida_de_articulos"> 

    <div class="panel panel-default">
        <div class="panel-heading">
            <label>Datos Generales</label>
        </div>
        <div class="panel-body">
            <table width="50%" border="0" align="left">
                <tr><th width="23%"></th><th></th><th></th></tr>
                <tr> <!-- <i id="requerido">*</i> -->
                  <td><label>Bodega: <b class="error" style="color:red;">*</b></label></td>
                    <td colspan="2">
                        <select class="form-control" id="bodega" name="bodega">
                            <?php if(isset($articulos)) {echo $articulos;} ?>
                        </select>
                        <div id="bodega_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>

                <tr>
                <td width="10%"><label>Fecha salida: <b class="error" style="color:red;">*</b></label></td>
                    <td colspan="2">
                        <input id="fecha_salida" name="fecha_salida" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control fecha">
                    </td>
                </tr>
                <tr>
                <td width="10%"><label>Tipo de salida:<b class="error" style="color:red;">*</b></label></td>
                    <td colspan="2">
                        <select class="form-control" id="salida" name="salida">
                            <?php if(isset($procesos)) {echo $procesos;} ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>    

	<div class="panel panel-default">
        <div class="panel-heading">
        	<label>Datos Específicos</label>
        </div>
        <div class="panel-body">
        	<table width="50%" border="0" align="left">
                <tr><th></th><th></th><th></th></tr>
                <!-- <tr>
                <td width="10%"><label>Categoría:</label></td>
                    <td colspan="2">
                        <select class="form-control select2" id="categoria" name="categoria" placeholder="seleccione">
                            <?php if(isset($categoria)) {echo $categoria;} ?>
                        </select>
                        <div id="categoria_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
                <tr>
                <td width="10%"><label>Subcategoría:</label></td>
                    <td colspan="2">
                        <select class="form-control select2" id="sub_categoria" name="sub_categoria" placeholder="seleccione" disabled="disabled">
                        </select>
                    </td>
                </tr> -->
                <tr>
                <td width="10%"><label>Artículo:</label></td>
                    <td colspan="2">
                        <select class="form-control" id="articulo" name="articulo">
                            <option value="0">Seleccione</option>
                        </select>
                        <div id="articulo_error" style="color:red;font-size:11px;"></div>
                    </td>
                </tr>
                <tr>
                <tr id="unidad_medida" style="display:none;">
                    <td width="10%"><label>Unidad de medida:</label></td>
                    <td colspan="2"><input type="text"  class="datetime-input form-control" id="um" name="um" value="prueba" disabled="disabled" style="text-align:center;"></td>
                </tr>
                <td width="10%"><label>Descripción:</label></td>
                    <td colspan="2">                        
                        <div class="form-group" style="margin-bottom:0px;">    
                            <textarea id="descripcion" name="descripcion" style="width:100%" disabled="disabled"></textarea>
                        </div>              
                    </td>
                </tr>
                <tr>
                <td width="10%"><label>Cantidad:</label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <input type="text" class="form-control" placeholder="Cantidad" id="cantidad" name="cantidad" onchange="validar_cantidad();" maxlength="5">    
                            <input type="hidden" id="cant_real" name="cant_real">
                            <div id="cantidad_error" style="color:red;font-size:11px;"></div>
                        </div>              
                    </td>
                </tr>
                <tr>
                    <td><button id="agregar" name="agregar" type="button" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>
                    <td><button id="cancelar" name="cancelar" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
                    <td></td>
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
                Nombre del Artículo
            </th>
            <th style="">
                Cantidad
            </th>
             <th style="">
                Acción
            </th>
        </tr>
        </thead>
    <tbody id="contenedor">

    </tbody>
</table>

    <div class="form-actions">
    <!-- <td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td> -->
        <button type="button" class="btn btn-success" id="registrar_salida" disabled><span class="fa fa-check white"></span> Procesar</button>
        <!-- <button type="button" class="btn btn-danger" id="anular" disabled><span class="icomoon-icon-cancel-3"></span>Anular</button> -->
    </div>

        </div>
    </div>

    </form>
</div>

<script src="<?php echo base_url()?>js/inventario/salida_articulos.js"></script>  





