 
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
            <thead>

                <?php if(count($solicitudes)>0){
                    $cantidad = 0;
                    $total    = 0;
                 ?>     

                    <tr>
                        <td colspan="6" style="text-align:left;"><label><b><?php echo 'Específico: '.$detalle['esp_nombre']; ?></b></label></td>
                        <td style="text-align:center;"><label><b><?php echo '$ '.number_format($detalle['det_saldo'],2); ?></b></label></td>
                    </tr>
                <?php } ?>

                <tr class="color_fondo titulo">
                    <th># Solicitud</th>
                    <th>Fecha creación</th>
                    <th>Solicitante</th>
                    <th>Categoria</th>
                    <th>Estado</th>
                    <th>$ Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $key => $value) { ?>
                    <tr class="odd gradeX">
                        <td style="text-align:center;"><?=$value['sol_id']?></td>
                        <td style="text-align:center;"><?= date('d-m-Y', strtotime($value['fecha'])) ?></td>
                        <td style="text-align:center;"><?= $value['nombre'] ?></td>
                        <td style="text-align:center;"><?= $value['categoria'] ?></td>
                        <td style="text-align:center;"><?= $value['ets_nombre'] ?></td>
                        <td style="text-align:center;"><?= number_format($value['cantidad'],2) ?></td>
                        <td style="text-align:center;"><?= number_format($value['total'],2) ?></td>
                    </tr>
                 <?php 
                 $cantidad  += (float) $value['cantidad'];
                 $total     = (float) $value['total'];
                 
                } ?> 
            </tbody>

            <?php if(count($solicitudes)>0){ ?>      
                 <tr class="color_fondo">
                    <td><label><b>Total:</b></label></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="center"><label><b><?php echo '$ '.number_format($cantidad,2); ?></b></label></td>
                    <td align="center"><label><b><?php echo '$ '.number_format($total,2); ?></b></label></td>
                </tr>
                <?php } ?>

    </table>
