    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><b>Detalle de productos</b></h5>
        </div>
        <div class="panel-body">
        <br /><small id="validar_datagried" style="color:red;" ></small>    
            <table class="responsive table table-bordered contenedor" id="datagried" name="datagried">
            <thead id="cabezera">
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
                 <th style="">
                    Acción
                </th>
            </tr>
            </thead>
            <tbody id="contenedor">
            <?php 
            $total = 0.0;
            foreach ($solicitud as $key => $value) { 
                $row = 1; 
                $subtotal = $value['pxs_precio']*$value['pxs_cantidad'];
                $total +=  $subtotal;
                ?>

                <!-- var subtotal = $("#precio").val()*$("#cantidad").val();  -->
                <tr id="">
                    <td>
                        <input type="hidden" value="<?php echo $key; ?>" name="ids_filaP[]" />
                        <input type="hidden" name="productos[]" id="productos" value="<?php echo $value['pxs_pro_id']; ?>"/>
                        <input type="hidden" name="financiamiento[]" id="financiamiento" value="<?php echo $info_general[0]['des_fon_id']; ?>"/>
                        <input type="hidden" name="cantidad[]" id="cantidadi<?php echo $row; ?>" value="<?php echo $value['pxs_cantidad'];?> "/>
                        <input type="hidden" name="precios[]" id="precioi<?php echo $row; ?>" value="<?php echo $value['pxs_precio']; ?>"/>
                        <input type="hidden" name="descripcion[]" id="descripcioni<?php echo $row; ?>" value="<?php echo $value['pxs_descripcion']; ?>"/>
                        <input type="hidden" name="total[]" id="totali<?php echo $row; ?>" class="activo" value="<?php echo $subtotal; ?>"/>
                        <label name="producto_label" id="productos"/><?php echo $value['pro_nombre']; ?>
                    </td>
                    <td><label name="cantidad_label" id="cantidadl<?php echo $row; ?>"/><?php echo $value['pxs_cantidad']; ?></td>
                    <td><label name="precio_label" id="preciol<?php echo $row; ?>"/><?php echo $value['pxs_precio']; ?></td>
                    <td><label name="total_label" id="totall<?php echo $row; ?>"/><?php echo $subtotal; ?></td>
                    <td><button type="button" id="remove" id_fila="<?php echo $row; ?>" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>
                </tr>
                <?php $row++; } ?>
            </tbody>
        </table>
        <div style="text-align: center;">
            <span><label id="total_suma"><h1>Total: <?php echo  '$ '.number_format($total,2,'.',','); ?></h1></label></span>
            <input id="total_suma_hidden" name="total" value="<?php echo $total; ?>" type="hidden">
        </div>    
    <div class="form-actions">
        <button type="button" class="btn btn-success" id="actualizar"><span class="fa fa-check white"></span> Actualizar</button>
        <button type="button" class="btn btn-success" id="aceptar"><span class="fa fa-check white"></span> Aceptar</button>
        <button type="button" class="btn btn-danger" onclick="$('#motivo_error').text('');" id="rechazar"><span class="fa fa-times white"></span> Rechazar</button>
        <!-- <button type="button" class="btn btn-danger" id="anular" disabled><span class="icomoon-icon-cancel-3"></span>Anular</button> -->
    </div>

        </div>
    </div> <!-- Fin del datatable -->
