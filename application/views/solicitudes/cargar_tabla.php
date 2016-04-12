<table class="table table-striped table-bordered table-hover"  id="dataTables-example">
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

        <tr <?php echo (!isset($usuario_final) && $value['sol_soe_id']==2)? "style='background-color:#FBA569;'":NULL;  ?>>
            <td><?php echo $value['sol_id']; ?></td>
            <td><?php echo $value['dpi_nombre']; ?></td>
            <td><?php echo date('d-m-Y g:i a', strtotime($value['sol_fecha']));?></td>
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

                            <?php if(isset($financiero) && !empty($financiero)) {
                                if(isset($value['saldo_congelado']) && $value['saldo_congelado'] > 0){ ?>
                                    <li><span>Saldo congelado</span></li>
                                <?php } else {

                                if($value['ets_id']==7){ ?>
                            
                            <li>
                                <a class="ver_modal" title="Enviar" href="#" id_sol="<?php echo $value['sol_id']; ?>" >
                                <span class="fa fa-check"></span>
                               Abastecimiento</a>
                            </li>
                            <li><a target="_blank" title="Anexo" href="<?php echo base_url();?>home/abastecimiento/anexo_solicitud/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-file-photo-o"></span>
                                    Anexo
                                </a>
                            </li>
                            <li>
                                <a target="_blank" title="Imprimir" href="<?php echo base_url();?>home/solicitudes/imprimir_excel/<?php echo $value['sol_id']; ?>/1">
                                    <span class="glyphicon glyphicon-print"></span>
                                    Imprimir
                                </a>
                            </li>            
                            <li><a target="_blank" title="Excel" href="<?php echo base_url();?>home/solicitudes/imprimir_excel/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-file-excel-o"></span>
                                    Exportar
                                </a>
                            </li>                        
                            <?php
                             } else { ?>

                             <li>
                                <a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'].'/2')?>" >
                                <span class="fa fa-pencil"></span>
                                Editar</a>
                            </li>
                             
                             <?php }
                                  }
                                 } else
                                    if(isset($abastecimiento) && !empty($abastecimiento)) {
                                        if($value['ets_id']==4 || $value['ets_id']==5){ ?>
                            <li>
                                <a target="_blank" title="Imprimir" href="<?php echo base_url();?>home/solicitudes/imprimir_excel/<?php echo $value['sol_id']; ?>/1">
                                    <span class="glyphicon glyphicon-print"></span>
                                    Imprimir
                                </a>
                            </li>            
                            <li><a target="_blank" title="Excel" href="<?php echo base_url();?>home/solicitudes/imprimir_excel/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-file-excel-o"></span>
                                    Exportar
                                </a>
                            </li>
                            <li><a target="_blank" title="Compra" href="<?php echo base_url();?>home/solicitudes/registrar_compra/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-check"></span>
                                    Registrar compra
                                </a>
                            </li>
                            <li><a target="_blank" title="Anexo" href="<?php echo base_url();?>home/abastecimiento/anexo_solicitud/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-file-photo-o"></span>
                                    Anexo
                                </a>
                            </li>
                            <?php } else { 
                                if(isset($value['saldo_congelado']) && $value['saldo_congelado'] > 0){ ?>
                                    <li><span>Saldo congelado</span></li>
                                <?php } else { ?>

                                <li><a class="" title="Editar" href="<?=base_url('home/abastecimiento/ver_solicitudes_edit/'.$value['sol_id'])?>" >
                                    <span class="fa fa-pencil"></span>
                                    Editar</a></li>     
                                <li>
                                <?php } ?>
                            <?php } 
                            } else { ?>
                                <li><a congelado = "<?php echo (isset($value['saldo_congelado']) && $value['saldo_congelado'] > 0 && $value['des_ets_id'] != 4 && $value['des_ets_id'] != 5  )? $value['saldo_congelado']:0; ?>"  cont="<?php echo $key; ?>" id="detalle_<?php echo $key; ?>" estado="<?php echo $value['des_ets_id']; ?>" value="<?php echo $value['sol_id']; ?>" href="#">
                                <span class="glyphicon glyphicon-search"></span>
                                Detalle</a></li>
                            <?php } ?>
                            <!--  || (!empty($abastecimiento))  -->
                            <?php if(!empty($financiero)) {?> 
                                <!-- <li><a target="_blank" title="PDF" href="<?php echo base_url();?>home/solicitudes/imprimir_pdf/<?php echo $value['sol_id']; ?>">
                                <span class="fa fa-file-pdf-o"></span>
                                Generar PDF</a></li> -->
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
        <tr>
            <td align="center">No hay registros...</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php } ?>
</tbody>
</table>