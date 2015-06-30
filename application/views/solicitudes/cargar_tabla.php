<table class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
    <tr>
        <th>#</th>
        <th>Solicitante</th>
        <th>Fecha</th>
        <th>Almacen entrega</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    <?php 
    foreach ($solicitudes as $key => $value) {   ?>
        <tr>
            <td><?php echo $key; ?></td>
            <td><?php echo $value['dpi_nombre']; ?></td>
            <td><?php echo date('d-m-Y H:i:s', strtotime($value['sol_fecha']));?></td>
            <td><?php echo $value['ali_nombre']; ?></td>
            <td id="estado_<?php echo $key; ?>"><?php echo $value['ets_nombre']; ?></td>
      
            <?php if(!empty($abastecimiento)) { ?>
            <td><a class="btn btn-info" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'])?>" >
                    <span class="fa fa-pencil"></span>
                Editar</a> </td>
             <?php } else { ?>
            <td align="center"><button type="button" cont="<?php echo $key; ?>" id="detalle_<?php echo $key; ?>" value="<?php echo $value['sol_id']; ?>"  class="btn btn-info btn-xs">Detalle</button></td>
            <?php } ?>
        </tr>
    <?php } ?>    
</tbody>
</table>