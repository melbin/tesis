<div>
	<form class="" id="frm_usuario" name="frm_usuario" method="POST" action="<?php echo base_url()?>welcome/perfil_usuario"> 

		<div class="panel panel-default">
		        <div class="panel-heading">
		        	<b>Editar Usuario</b> 
		        </div>
		       
		        <div class="panel-body">
		        	<!-- All your code here -->

		        	<table width="50%" align="left" border="0">
		        		<tr>
		        			<th width="23%"></th>
		        			<th></th>
		        		</tr>

		        		<tr>
		                <td><h5>Nombre:<b style="color:red;"> *</b></h5></td>
		                    <td>
		                        <input type="text" id="nombre" name="nombre" value="<?php echo !empty($usuario_array['username'])? $usuario_array['username']:''; ?>">
		                        <div id="nombre_error" style="color:red;font-size:11px;"></div>
		                        <input type="hidden" name="user_id" value="<?php echo !empty($usuario_array['id'])? $usuario_array['id']:0; ?>">
		                    </td>
		                </tr>
		                <tr>
		                <td width="10%"><h5>Correo:<b style="color:red;"> *</b></h5></td>
		                    <td colspan="2">
		                        <input type="text" id="correo" name="correo" value="<?php echo !empty($usuario_array['email'])? $usuario_array['email']:''; ?>" placeholder="ejemplo@gmail.com">
		                        <div id="correo_error" style="color:red;font-size:11px;"></div>
		                    </td>
		                </tr>
		                <tr>
		                <td width="10%"><h5>Contraseña:<b style="color:red;"> *</b></h5></td>
		                    <td colspan="2">
		                        <input type="password" id="password" name="password">
		                        <div id="password_error" style="color:red;font-size:11px;"></div>
		                    </td>
		                </tr>
		                <tr>
		                <td width="10%"><h5>Confirmar contraseña:<b style="color:red;"> *</b></h5></td>
		                    <td colspan="2">
		                        <input type="password" id="confirm_password" name="confirm_password">
		                        <div id="confirm_password_error" style="color:red;font-size:11px;"></div>
		                    </td>
		                </tr>

		                <tr>
		        			<td><button id="add" name="add" type="submit" class="btn btn-primary"> <span class="fa fa-check">Agregar</span></button></td>
		        			<td><a id="cancelar" name="cancelar" href="<?php echo base_url();?>welcome/" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></a></td>
		        		</tr>

		        	</table>
		        </div>
		</div>
	</form>
</div>        

<script type="text/javascript">
    $(document).ready(function(){

    	// Validar el formulario con el Plugin de Jquery
    	$("#frm_usuario").validate({
            // Specify the validation rules
            ignore: "",
        rules: {
            nombre: "required",
            correo: {
            	required: true,
            	email: true    
            },
            password: {
            	required: true,
			    minlength: 5
            },
            confirm_password: {
			    required: true,
			    equalTo: "#password",
			    minlength: 5
            }
        },

        // Specify the validation error messages
        messages: {
            nombre: "requerido",
            correo: {
            	email: "Ingrese un correo válido",
            	required: "requerido"
            },
            password: {
            		required: "requerido",
            		minlength: "Minimo 6 caracteres"
            }
            ,
            confirm_password: {
            		required: "requerido",
            		minlength: "Minimo 6 caracteres",
            		equalTo: "Contraseña debe coincidir"
            }
        },

        errorPlacement: function (error, element) {
      		var nombre=$(element).attr("id");
      		$('#'+nombre+'_error').html(error);
    	},

        submitHandler: function(form) {
            form.submit();
        }

        }); // Fin validar Formulario

    });
</script>

