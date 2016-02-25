
                <!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
                    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                        <thead>
                        <?php $cont =0; foreach ($especifico as $key => $value) {
                            // $entrada = 0;
                            // $salida = 0;
                            // $saldo = 0;
                            if( isset($asignacion_array[$key]) && count($asignacion_array[$key])>0 ){ ?>
                                <tr>
                                    <td colspan="3"><label><b>Específico: <?php echo $value['esp_nombre']; ?></b></label></td>
                                    <td style="text-align:center;"><label><b><?php echo '$ '.number_format(($value['det_saldo'] - $value['det_saldo_ejecutado']),2); ?></b></label></td>
                                </tr>     

                                <tr class="color_fondo titulo">
                                    <th style="text-align:center;" width="25%">Nombre departamento</th>
                                    <th style="text-align:center;">Fecha asignación</th>
                                    <th style="text-align:center;">Asignado</th>
                                    <th style="text-align:center;">Disponibilidad</th>
                                </tr>
                        </thead>
                                <tbody>
                                <?php foreach ($asignacion_array[$key] as $key2 => $value2) { ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center;"><?=$value2['dpi_nombre']?></td>
                                        <td style="text-align:center;"><?= date('d-m-Y g:i A', strtotime($value2['fecha_asignacion'])) ?></td>
                                        <td style="text-align:center;">$<?=number_format($value2['salida'],2)?></td>
                                        <td style="text-align:center;">$<?=number_format($value2['total'],2)?></td>
                                    </tr>
                             <?php   
                                //$entrada += (float) $value2['entradas'];
                                // $salida += (float) $value2['salida'];
                                // $saldo += (float)   $value2['total'];
                                } ?>
                                <!-- <tr class="color_fondo">
                                    <td><label><b>Σ:</b></label></td>
                                    <td></td>
                                    <td></td>
                                    <td align="center"><label><b><?php echo '$'.number_format($salida); ?></b></label></td>
                                    <td align="center"><label><b><?php echo '$'.number_format($saldo); ?></b></label></td>
                                </tr> -->
                            </tbody>

                           <?php $cont++; }
                            } ?> 
                            <?php if($cont==0){ ?>
                            <tr>
                                <th width="20%">Nombre departamento</th>
                                <th>Fecha asignación</th>
                                <th>Salida</th>
                                <th>Total</th>
                            </tr>
                            
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="td_temporal" align="center">No se encontraron registros...</td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                </tr>

                            </tbody>
                        <?php } ?>
            </table>
    