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
            
            <td> 
                <div class="tools">
                    <div class="btn-group">
                        <button class="btn" style="color: black;"><span class="glyphicon glyphicon-cog"></span></button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">

                            <?php if(!empty($financiero)) {?>
                            <li>
                                <a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'].'/2')?>" >
                                <span class="fa fa-pencil"></span>
                                Editar</a></li>      
                            <?php } else { ?>
                            <?php if(!empty($abastecimiento)) { ?>
                                <li><a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'])?>" >
                                <span class="fa fa-pencil"></span>
                                Editar</a></li>     

                            <?php } else { ?>
                                <li><a  cont="<?php echo $key; ?>" id="detalle_<?php echo $key; ?>" value="<?php echo $value['sol_id']; ?>" href="#">
                                <span class="glyphicon glyphicon-search"></span>
                                Detalle</a></li>
                            <?php }} ?>
                            <?php if(!empty($financiero) || (!empty($abastecimiento)) ) {?>
                                <li><a class="" title="PDF" href="#">
                                <span class="fa fa-file-pdf-o"></span>
                                Generar PDF</a></li>
                                <li><a class="" title="Excel" href="#">
                                <span class="fa fa-file-excel-o"></span>
                                Generar Excel</a></li>            
                            <?php }?>    
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
    <?php if(empty($solicitudes)){ ?>
    <tr><td colspan="6" align="center">No hay registros...</td></tr>
    <?php } ?>
</tbody>
</table>