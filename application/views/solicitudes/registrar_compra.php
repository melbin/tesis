<style type="text/css">
    .centrar     td {
        padding: 5px;
    }
</style>

<!--  <script src="<?php echo base_url()?>js/solicitudes/solicitudes.js"></script>  -->
<script src="<?php echo base_url()?>js/solicitudes/registrar_compra.js"></script>

<div>
<form class="" id="frm_compra" name="frm_compra" method="POST" action="<?php echo base_url()?>home/solicitudes/registrar_compra"> 

<div class="panel panel-default">
        <div class="panel-heading">
        	<b>Detalle solicitud</b> 
        </div>
       
        <div class="panel-body">
        	<!-- All your code here -->
         <table width="100%" border="0">
           <tr>
               <th width="50%"></th>
               <th width="50%"></th>
           </tr>
           <tr>
               <td>
                <table width="100%" align="left" border="0">
                <tr><th style="width: 10%;"></th><th></th></tr>
                
                <tr>
                <td><h5>#:</h5></td>
                    <td colspan="2">
                        <input id="numero_solicitud" name="numero_solicitud" type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? $sol_array['sol_id']:''; ?>">
                        <input type="hidden" name="sol_id" value="<?php echo isset($sol_array)? $sol_array['sol_id']:''; ?>">
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Fecha:</h5></td>
                    <td colspan="2">
                        <input type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? date('d-m-Y', strtotime($sol_array['sol_fecha'])):''; ?>">
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Solicitante:</h5></td>
                    <td colspan="2">
                        <input type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? $sol_array['dpi_nombre']:''; ?>">
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Almacen entrega:</h5></td>
                    <td colspan="2">
                        <input type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? $sol_array['ali_nombre']:''; ?>">
                    </td>
                </tr>
            </table>       
               </td>

               <td style="vertical-align: top;">
                <table width="100%" align="left" border="0">
                <tr><th style="width: 10%;"></th><th></th></tr>
                <tr>
                <td width="10%"><h5>Clase de suministro:</h5></td>
                    <td colspan="2">
                        <input type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? $sol_array['cat_nombre']:''; ?>">
                    </td>
                </tr>
                  <tr>
                <td width="10%"><h5>Fondo:</h5></td>
                    <td colspan="2">
                        <input type="text" disabled="disabled" style="width: 95%;" value="<?php echo isset($sol_array)? $sol_array['fon_nombre']:''; ?>">
                    </td>
                </tr>
                <tr>
                <td width="10%"><h5>Monto ($):</h5></td>
                    <td colspan="2">
                        <input type="text" style="width: 95%;" disabled="disabled"  value="<?php echo isset($sol_array)? $sol_array['des_total']:''; ?>">
                    </td>
                </tr>
            </table>       
               </td>
           </tr>
        	
         </table>                                          
       </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
        	<h5><b>Detalle contratista</b></h5>
        </div>
        <div class="panel-body">
        	<table width="100%" align="left">
        		<tr>
                    <th width="50%"></th>
                    <th width="50%"></th>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <table width="100%" align="left" border="0" class="centrar">
                            <tr><th style="width: 10%;"></th><th></th></tr>
                            <tr>
                                <td width="12%"><label>Proveedor:<b style="color:red;">*</b></label></td>
                                <td colspan="2">
                                    <select class="form-control select2" id="proveedor" name="proveedor" style="width: 95%;">
                                        <?php if(isset($proveedor)) {echo $proveedor;} ?>
                                    </select>
                                    <div id="proveedor_error" style="color:red;font-size:11px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label>Contrato:<b style="color:red;">*</b></label></td>
                                <td colspan="2">
                                    <input id="contrato" name="contrato" type="text"  maxlength="10"  class="datetime-input form-control" style="width: 95%;">
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label>Monto:<b style="color:red;">*</b></label></td>
                                <td colspan="2">                        
                                    <div class="form-group input-group">    
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control decimales" placeholder="Monto" id="monto" name="monto" style="width: 95%;"> 
                                    </div>
                                    <!-- <div id="precio_error" style="color:red;font-size:11px;"></div>              -->
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label>Descripción:</label></td>
                                <td colspan="2">                        
                                    <!-- <div class="form-group">     -->
                                        <textarea id="descripcion" name="descripcion" rows="4" style="width:100%"></textarea>
                                        <div id="descripcion_error" style="color:red;font-size:11px;"></div>
                                    <!-- </div>               -->
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align: top;">
                        <table width="100%" align="left" border="0" class="centrar">
                            <tr><th style="width: 10%;"></th><th></th></tr>
                            <tr>
                                <td width="10%"><label>Renta:</label></td>
                                <td colspan="2">
                                    <input id="renta" name="renta" type="text"  maxlength="10"  class="datetime-input form-control decimales" style="width: 95%;">
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label>NIT:</label></td>
                                <td colspan="2">
                                    <input id="nit" name="nit" type="text"  class="datetime-input form-control" style="width: 95%;" placeholder="____-______-___-_">
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"><label>Retención:</label></td>
                                <td colspan="2">                        
                                    <div class="form-group input-group">    
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control decimales" placeholder="Retención" id="retencion" name="retencion"> 
                                    </div>
                                    <!-- <div id="precio_error" style="color:red;font-size:11px;"></div>              -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
    
                <tr>
                    <td><button id="add" name="add" type="button" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>           
                </tr>		
            </table>    
 		</div>
    </div>

    <!-- Creacion del datatable -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><b>Detalle de proveedores</b></h5>
        </div>
        <div class="panel-body">
        <br /><small id="validar_datagried" style="color:red;" ></small>    
        <table class="responsive table table-bordered contenedor" id="datagried" name="datagried">
        <thead id="cabezera">
        <tr>
            <th>
                Proveedor
            </th>
            <th>
                Contrato
            </th>
            <th>
                Monto
            </th>
            <th>
                Renta
            </th>
            <th>
                NIT
            </th>
            <th>
                Retención
            </th>
            <th>
                Acción
            </th>
        </tr>
        </thead>
    <tbody id="contenedor">
    <?php echo (!empty($html))? $html:null; ?>
    </tbody>
</table>
    <div class="form-actions">
        <button type="button" class="btn btn-success" id="registrar_compra" disabled><span class="fa fa-check white"></span> Procesar</button>
        <a id="cancelar" name="cancelar" type="button" class="btn btn-danger" href="<?php echo base_url().'home/abastecimiento/procesar_solicitudes'; ?>"> <span class="fa fa-times"> Cancelar</span></a>  
    </div>

        </div>
    </div>

    </form>
</div>
