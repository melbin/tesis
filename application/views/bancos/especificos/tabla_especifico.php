<table class="table table-striped table-bordered table-hover"  id="dataTables-example">
<thead>
    <tr>
        <th>#</th>
        <th align="center" style="vertical-align: text-top;">Nombre</th>
        <th align="center" style="vertical-align: text-top;">Fondo</th>
        <th align="center" style="vertical-align: text-top;">Presupuesto Votado</th>
        <th align="center" style="vertical-align: text-top;">Saldo ($)</th>
        <th align="center" style="vertical-align: text-top;">Asignado (%)</th>
        <th align="center" style="vertical-align: text-top;">Devengado (%)</th>
        <th align="center" style="vertical-align: text-top;">Congelado ($)</th>
        <th align="center" style="vertical-align: text-top;">% de ejecución</th>
        <th align="center" style="vertical-align: text-top;">Acciones</th>
    </tr>
</thead>
<tbody>
    <?php 
    foreach ($esp_detalles as $key => $value) {   ?>

        <tr>
            <td><?php echo ++$key; ?></td>
            <td><?php echo $value['esp_nombre']; ?></td>
            <td><?php echo $value['fon_nombre']; ?></td>
            <td><?php echo '$'.number_format($value['det_saldo_votado'],2); ?></td>
            <td><?php echo '$'.number_format($value['det_saldo'],2); ?></td>
            <td><?php echo isset($monto_asignado[$key-1])? number_format(($monto_asignado[$key-1]['suma']*100)/$monto_asignado[$key-1]['total'],1).'%' :'0.0%'; ?></td>
            <td><?php echo ($value['det_saldo_devengado']>0)? number_format(($value['det_saldo_devengado']*100/$monto_asignado[$key-1]['total']),1).'%' :'0.0%'; ?></td>
            <td><?php echo '$'.number_format($value['det_saldo_congelado'],2); ?></td>
            <td><?php echo '$'.number_format($value['det_saldo_ejecutado'],2); ?></td>
            <!-- <td id="estado_<?php echo $key; ?>"><?php echo $value['ets_nombre']; ?></td> -->
            <td> 
                <div class="tools">
                    <div class="btn-group">
                        <button class="btn" style="color: black;"><span class="glyphicon glyphicon-cog"></span></button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="<?php echo ($value['det_saldo_congelado']>0)? 'congelado':''; ?>" title="Editar" id="editar_detalle" href="<?=base_url('bancos/especificos/detalle_especifico_editar/'.$value['det_id'])?>" >
                                <span class="fa fa-pencil"></span>
                                Editar</a>
                            </li>
                 <!--            <li>
                                <a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'].'/2')?>" >
                                <span class="fa fa-pencil"></span>
                                Congelar</a>
                            </li>
                            <li>
                                <a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'].'/2')?>" >
                                <span class="fa fa-pencil"></span>
                                Reactivar</a>
                            </li>
                            <li>
                                <a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'].'/2')?>" >
                                <span class="fa fa-pencil"></span>
                                Reporte Excel</a>
                            </li> -->
                             <!-- <li>
                                <a class="" title="Eliminar información" href="">
                                    <span  class="glyphicon glyphicon-remove"></span>
                                    Eliminar
                                </a>
                            </li>                                                     -->
                        </ul>
                    </div>
                </div>                        
            </td>
        </tr>
    <?php } ?>    
    <?php if(empty($esp_detalles)){ ?>
        <tr>
            <td align="center" colspan="10">No hay registros...</td>
        </tr>
    <?php } ?>
</tbody>
</table>