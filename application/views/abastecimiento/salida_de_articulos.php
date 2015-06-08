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
                  <td><label>Bodega:</label><b class="error" style="color:red; display:none;">*</b></label></td>
                    <td colspan="2">
                        <select class="form-control" id="bodega" name="bodega">
                            <?php if(isset($articulos)) {echo $articulos;} ?>
                        </select>
                    </td>
                </tr>

                <tr>
                <td width="10%"><label>Fecha de salida:</label></td>
                    <td colspan="2">
                        <input id="fecha_salida" name="fecha_salida" type="text" value="<?php echo date('d-m-Y'); ?>" maxlength="19" placeholder="__/__/____" class="datetime-input form-control">
                    </td>
                </tr>
                <tr>
                <td width="10%"><label>Tipo de salida:</label></td>
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
                <tr>
                <td width="10%"><label>Articulo:</label></td>
                    <td colspan="2">
                        <select class="form-control" id="articulo" name="articulo">
                            <option value="0">Seleccione</option>
                        </select>
                    </td>
                </tr>

                <tr>
                <td width="10%"><label>Cantidad:</label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <input type="text" class="form-control" placeholder="Cantidad" id="cantidad" name="cantidad" onchange="validar_cantidad();">    
                            <input type="hidden" id="cant_real" name="cant_real">
                        </div>              
                    </td>
                </tr>
                <tr>
                    <td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Procesar</span></button></td>
                    <td><button id="cancelar" name="cancelar" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
                    <td></td>
                </tr>
               
            </table>
 		</div>
    </div>
    </form>
</div>

<script src="<?php echo base_url()?>js/inventario/salida_articulos.js"></script>  





