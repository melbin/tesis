    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><b>Detalles</b></h5>
        </div>
        <div class="panel-body">
        <br /><small id="validar_datagried" style="color:red;" ></small>    
        <table class="responsive table table-bordered contenedor" id="datagried" name="datagried">
            <thead id="cabezera">
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Nombre
                    </th>
                    <th style="">
                        Departamento
                    </th>
                    <th style="">
                        Monto Asignado ($)
                    </th>
                     <th style="">
                        Acción
                    </th>
                </tr>
            </thead>
            <tbody id="contenedor">
            <?php 
            $subtotal = 0.0;
             $row = 1; 
            foreach ($det_asignaciones as $key => $value) { 
                
                $subtotal += floatval($value['axd_cantidad']);
                ?>
                
                <tr id="<?php echo $row; ?>" bgcolor="<?php echo (!empty($value['axd_reserva']) && $value['axd_reserva']>0)? '#00FF7F':''; ?>">
                    <td>
                        <input type="hidden" value="<?php echo $row; ?>" name="ids_filaP[]" />
                        <input type="hidden" name="departamentos[]" class="departamentos" value="<?php echo $value['axd_depto_id']; ?>"/>
                        <input type="hidden" name="axd_asignaciones[]" class="" value="<?php echo $value['axd_id']; ?>"/>
                        <label><?php echo $row; ?></label>
                    </td>
                    <td><label name="esp_label" id="esp_label"><?php echo $especifico; ?></label></td>
                    <td><label name="depto_label" id="depto_label"/><?php echo $value['dpi_nombre']; ?></td>
                    <td><input name="cantidad_depto[]" id="cantidad_depto_<?php echo $row; ?>" class="monto_asignado" style="text-align:center;" value="<?php echo number_format($value['axd_cantidad'],2); ?>" saldo_reserva="<?php echo !empty($value['axd_reserva'])? $value['axd_reserva']:0; ?>" /></td>
                    <td><button type="button" id="remove" id_fila="<?php echo $row; ?>" class="remove" <?php echo (!empty($value['axd_reserva']) && $value['axd_reserva']>0)? 'disabled':''; ?>><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
                </tr>    
                <?php $row++; } ?>
            </tbody>
        </table>
        <br>
        <div style="text-align: center;">
            <span><label id="total_restante"><h1>Saldo pendiente: $<?php echo number_format((floatval($total)-$subtotal),2); ?></h1></label></span>
            <input id="total_restante_hidden" name="total_restante" value="<?php echo round((floatval($total)-$subtotal),2); ?>" type="hidden">
            <input id="monto_asignado_total" name="monto_asignado_total" value="<?php echo round($subtotal,2); ?>" type="hidden">
        </div>    
    <div class="form-actions">
        <!-- <td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td> -->
        <button type="button" class="btn btn-success" id="registrar_entrada" <?php echo (count($det_asignaciones)>0)? '':'disabled'; ?> ><span class="fa fa-check white"></span> Procesar</button>
        <!-- <button type="button" class="btn btn-danger" id="anular" disabled><span class="icomoon-icon-cancel-3"></span>Anular</button> -->
    </div>
    </div> <!-- End panel boddy -->
    </div> <!-- End panel default -->

    <script type="text/javascript">
    $(document).ready(function(){

        $("#saldo").focusout(function(){
            if(parseFloat($(this).val()) < parseFloat($("#monto_asignado_total").val())){
                var cantidad = (parseFloat($("#pre_votado").val())).toFixed(2);

                if(parseFloat($("#pre_votado").val()) < parseFloat($("#saldo_origen").val())){
                    cantidad = (parseFloat($("#saldo_origen").val())).toFixed(2);
                }
                $(this).val(cantidad);
                alertify.error("Ingrese una cantidad mayor.");
            } else {
                restante(0);
            }
        });

        $(".monto_asignado").focusout(function(){

            $(this).css('background-color','#FFFFFF');
            if(parseFloat($(this).attr('saldo_reserva'))>0){
                if(parseFloat($(this).val()) < parseFloat($(this).attr('saldo_reserva'))){
                    alertify.error("Posee saldo en movimiento.");
                    $(this).val($.number($(this).attr('saldo_reserva'),2));
                } 
            }
            restante($(this).attr('id'));
        });
    });
    </script>