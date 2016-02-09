
                <!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
                    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                        <thead>
                        <?php $cont =0; foreach ($sub_categoria as $key => $value) {
                            $entrada = 0;
                            $salida = 0;
                            $saldo = 0;
                            if( isset($articulos_array[$key]) && count($articulos_array[$key])>0 ){ ?>
                                <tr><td colspan="7"><label><b>Categoría: <?php echo $value['sub_nombre']; ?></b></label></td></tr>     

                                <tr class="color_fondo titulo">
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>U.M</th>
                                    <th>Entradas</th>
                                    <th>Salidas</th>
                                    <th>Saldo</th>
                                </tr>
                    </thead>
                                <tbody>
                                <?php foreach ($articulos_array[$key] as $key2 => $value2) { ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center;"><?=$value2['codigo']?></td>
                                        <td style="text-align:center;"><?=$value2['nombre']?></td>
                                        <td style="text-align:center;">$<?=number_format($value2['precio'],2)?></td>
                                        <td style="text-align:center;"><?=$value2['UM']?></td>
                                        <td style="text-align:center;"><?=$value2['entradas']?></td>
                                        <td style="text-align:center;"><?=$value2['salidas']?></td>
                                        <td style="text-align:center;"><?=$value2['saldo']?></td>

                                        <!-- <td style="text-align:right;">$<?=number_format($sld['precio']*$sld['cantidad'],2)?></td> -->
                                    </tr>
                             <?php   
                                $entrada += (float) $value2['entradas'];
                                $salida += (float) $value2['salidas'];
                                $saldo += (float)   $value2['saldo'];
                                } ?>
                                <tr class="color_fondo">
                                    <td><label><b>Total:</b></label></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="center"><label><b><?php echo $entrada; ?></b></label></td>
                                    <td align="center"><label><b><?php echo $salida; ?></b></label></td>
                                    <td align="center"><label><b><?php echo $saldo; ?></b></label></td>
                                </tr>
                            </tbody>

                           <?php $cont++; }
                            } ?> 
                            <?php if($cont==0){ ?>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>U.M</th>
                                <th>Entradas</th>
                                <th>Salidas</th>
                                <th>Saldo</th>
                            </tr>
                            
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="td_temporal" align="center">No se encontraron registros...</td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                    <td class="drop"></td>
                                </tr>

                            </tbody>
                        <?php } ?>
            </table>
    