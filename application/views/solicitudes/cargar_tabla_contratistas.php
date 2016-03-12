

    <?php 
    foreach ($contratistas as $key => $value) {   ?>

        <tr>
            <td><label><?php echo $value['prv_nombre'].' '.$value['prv_apellido']; ?></label></td>
            <td><label><?php echo $value['con_contrato']; ?></label></td>
            <td><label><?php echo number_format($value['con_monto'],'2','.',','); ?></label></td>
            <td><label><?php echo $value['con_renta']; ?></label></td>
            <td><label><?php echo $value['con_nit']; ?></label></td>
            <td><label><?php echo $value['con_retencion']; ?></label></td>
            <td><button type="button" disabled="disabled"><span class="glyphicon glyphicon-remove"></span> Anular</button></td>     
        </tr>

    <?php } ?>    
    <?php if(empty($contratistas)){ ?>
        <tr>
            <td align="center" id="fila_temporal" colspan="7">No hay registros...</td>
        </tr>
    <?php } ?>

