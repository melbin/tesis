<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>js/jQuery_UI/css/jquery-ui.css" />
 <script src="<?php echo base_url()?>js/jQuery_UI/ui/jquery-ui.js"></script>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

	<div style='height:20px;' id="botones_control">
        <input type="checkbox" id="enviar_correo"><label for="enviar_correo" style="float: right; margin-top: -15px;"></label>
    </div>  

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
        	<?php 
            echo $output; 
            $flag_correo = $this->regional_model->get_parametro('mail_to_solicitante');

            ?>
       </div>
    </div> 

<script>
    $(document).ready(function() {

        var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";
        $("#field-per_telefono").mask('9999-9999');
        $("#enviar_correo").button({  icons: { primary: "ui-icon-mail-closed"}, label: "Enviar correo a Solicitantes" });

        estado ="<?php echo $flag_correo;?>";

        $("#enviar_correo").bind('click', function(){
            if($(this).is(':checked')){

                $("#enviar_correo").button("option", "label", "Envio de correo activado");
                $("#enviar_correo").button("option", "icons", {primary: "ui-icon-check"});
                $("label[for='"+$(this).attr('id')+"']").css({'background':'#ECF786', 'text-align':'center', 'font-weight':'bolder'});
                activar_correo(1);

            } else {
                $("#enviar_correo").button("option", "icons", { primary: "ui-icon-mail-closed"});
                $("#enviar_correo").button("option", "label", "Enviar correo a Solicitantes");
                $("label[for='"+$(this).attr('id')+"']").css({'background':'#E2E0E1', 'text-align':'center', 'font-weight':''});
                activar_correo(0);
            }
        });

        if(estado==1){

            $("#enviar_correo").prop("checked");
            $("#enviar_correo").trigger("click");
            
        }

        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });

        function activar_correo(estado)
        {
            $.ajax({
            url: urlj+'sistema/personal/activar_envio_correo',
                type: 'POST',
                dataType: 'json',
                data: {estado : estado},
                success:function(data) {
                 if(data.drop == 1)
                    {    
                        if(data.estado==1){
                            alertify.success("Envio de correos activado correctamente");
                        } else {
                            alertify.success("Se desactivo el envio de correos");
                        }
                    } 
                    else if(tipo_alerta === 'error')
                    {
                        alertify.error("No se pudo completar la petición");
                    }
                }
            });    

        }

        // Cuando sea lectura, ocultar campos que no se desea mostrar
        $("#new_password_key_field_box, #banned_display_as_box, #ban_reason_field_box, #new_email_field_box, #new_email_key_field_box").hide();
        $("#new_password_requested_field_box, #last_ip_field_box, #last_login_field_box, #banned_field_box").hide();

    });
</script>        