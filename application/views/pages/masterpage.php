<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Regional de Salud</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>media/sistema/favicon.ico" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url()?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url()?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- CSS jQuery_UI -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>js/jQuery_UI/css/jquery-ui.css" />

    <!-- jQuery iButton CSS -->
    <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>stylesheet/jquery.ibutton.css" />     -->

    <!-- DataTables CSS -->
    <link type="text/css" href="<?php echo base_url()?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link type="text/css" href="<?php echo base_url()?>bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- CSS Nuevo-->
    <link href="<?php echo base_url()?>stylesheet/sistema/sistema.css" rel="stylesheet">

    <!-- CSS FancyBox-->
    <link href="<?php echo base_url()?>js/fancy_box/jquery.fancybox.css" rel="stylesheet">
    <link href="<?php echo base_url()?>js/fancy_box/helpers/jquery.fancybox-buttons.css" rel="stylesheet">
    <link href="<?php echo base_url()?>js/fancy_box/helpers/jquery.fancybox-thumbs.css" rel="stylesheet">


    <!-- CSS del Select2-->
    <link href="<?php echo base_url()?>stylesheet/select2.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>stylesheet/select2-bootstrap.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url()?>dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url()?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url()?>bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Alertas plugin de jQuery -->
    <!-- <link rel='stylesheet' href="<?php echo base_url()?>stylesheet/alertify.core.css" />  --><!-- centra -->
    <!-- <link rel='stylesheet' href="<?php echo base_url()?>stylesheet/alertify.bootstrap.css" /> --> <!-- centra -->
    <!-- <link rel='stylesheet' href="<?php echo base_url()?>stylesheet/alertify.default.css" /> --> <!--estilo borde -->

    <link rel='stylesheet' type="text/css" href="<?php echo base_url()?>stylesheet/estilo/build/css/alertify.css" />
    <link rel='stylesheet' type="text/css" href="<?php echo base_url()?>stylesheet/estilo/build/css/themes/bootstrap.css" />

    
    <!-- Custom Fonts -->
    <link href="<?php echo base_url()?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

     <!-- jQuery --> 
  <!-- <script src="<?php echo base_url()?>bower_components/jquery/dist/jquery-2.1.4.js"></script>  -->

    <!-- Probar esta libreria de JQuery, ya que es compatible con la libreria del Checkbox -->
  <!--  <script src="<?php echo base_url()?>js/jquery-1.11.1.min.js"></script> -->

   <!-- jQuery -->
    <script src="<?php echo base_url()?>bower_components/jquery/dist/jquery.min.js"></script> 

   <!-- jQuery UI -->
   <script src="<?php echo base_url()?>js/jQuery_UI/ui/jquery-ui.js"></script>

   <!-- jQuery iButton js -->
  <!-- <script src="<?php echo base_url()?>js/jquery.ibutton.min.js"></script> -->

    <!-- Resuelve problemas de incompatibilidades con jQuerys viejos -->
   <script src="<?php echo base_url()?>bower_components/jquery/dist/jquery-migrate-1.2.1.min.js"></script>  

    <!-- jquery.browser.js  Is a plugin-->
    <script src="<?php echo base_url()?>bower_components/jquery/jquery.browser.js"></script>  

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url()?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- jQuery Masked Input -->
    <script src="<?php echo base_url()?>js/jquery.maskedinput.js"></script>
    
    <!-- Plugin para Selects  -->
    <script src="<?php echo base_url()?>js/select2.min.js"></script>

    <!-- Plugin de Alertify -->
    <!--  <script src="<?php echo base_url()?>js/alertify/alertify.js"></script> -->
    <script src="<?php echo base_url()?>js/alertify.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url()?>bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Validar todo tipo de campos -->
    <script src="<?php echo base_url()?>js/validar.js"></script>

    <script src="<?php echo base_url()?>js/jquery.numeric.js"></script>

     <!-- Formatos a Numeros -->
    <script src="<?php echo base_url()?>js/jquery.number.js"></script>

    <!-- Plugin para Validar Formularios -->
    <script src="<?php echo base_url()?>js/jquery.validate.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo base_url()?>bower_components/raphael/raphael-min.js"></script>
    <script src="<?php echo base_url()?>bower_components/morrisjs/morris.min.js"></script>
    <!--<script src="/<?php echo base_url()?>js/morris-data.js"></script> -->

    <!-- Print Plugin -->
    <script src="<?php echo base_url()?>assets/grocery_crud/themes/flexigrid/js/jquery.printElement.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url()?>dist/js/sb-admin-2.js"></script>

    <!-- DataTables JavaScript -->
    <script src="<?php echo base_url()?>bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url()?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- FancyBox -->
    <script src="<?php echo base_url()?>js/fancy_box/jquery.fancybox.pack.js"></script>
    <script src="<?php echo base_url()?>js/fancy_box/helpers/jquery.fancybox-buttons.js"></script>
    <script src="<?php echo base_url()?>js/fancy_box/helpers/jquery.fancybox-media.js"></script>
    <script src="<?php echo base_url()?>js/fancy_box/helpers/jquery.fancybox-thumbs.js"></script>

    <!-- Init plugins only for page -->
      <script type="text/javascript" src="<?php echo base_url()?>js/jquery-ui-timepicker-addon.js"></script>

    <?php if(isset($texto2)) { ?>
    <!-- CSS y JavaScript del Grocery Crud -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/grocery_crud/themes/flexigrid/css/flexigrid.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/grocery_crud/css/jquery_plugins/fancybox/jquery.fancybox.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css" />
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery-1.11.1.min.js"></script> 
   <!-- <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/jquery.noty.js"></script> -->
   <!-- <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/config/jquery.noty.config.js"></script> -->
    <script src="<?php echo base_url()?>assets/grocery_crud/js/common/lazyload-min.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/js/common/list.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/themes/flexigrid/js/cookies.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/themes/flexigrid/js/flexigrid.js"></script> 
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/jquery.form.min.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/themes/flexigrid/js/jquery.printElement.min.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/jquery.fancybox-1.3.4.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/jquery.easing-1.3.pack.js"></script>
    <script src="<?php echo base_url()?>assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js"></script>
    <?php } ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>welcome">Regional de Salud</a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-left">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <li>
                    <a  href="<?php echo base_url(); ?>welcome">Home</a>
                </li>
                <?php if(count($accede_sistema)>0){ ?>
                |
                <li>
                    <a  href="<?php echo base_url(); ?>admin">Sistema</a>
                </li>
                <?php } ?>
            </ul>
            <!-- MENU del panel superior derecho -->
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <!-- <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        
                    </ul> -->
                    <!-- /.dropdown-alerts -->
                </li>
               
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo base_url(); ?>welcome/perfil_usuario"><i class="fa fa-user fa-fw"></i> Perfil de Usuario</a>
                        </li>
                        <!-- <li><a href="#"><i class="fa fa-gear fa-fw"></i> Configuraciones</a>
                        </li> -->
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url(); ?>auth/logout"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            <img style="position: absolute; top:10px; left: 268px; width: 100px;" src="<?php echo base_url(); ?>media/sistema/<?php echo $logo;?>.gif">

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                    <!-- Codigo para el Search -->
                        <li class="sidebar-search">
                            <!-- <div class="input-group custom-search-form"> 
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div> -->
                            <!-- /input-group -->
                        </li>
                         
                        <?php
                            if(isset($menus)){
                                echo $menus;
                            }
                        ?>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

<!-- PARTE DE ARRIBA PUEDE SER DE LA MASTER PAGE -->

        <div id="page-wrapper">
            <div class="row" id="contenido">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php if(isset($titulo)){echo $titulo;} else {echo "Inicio";} ?></h1>

                <?php if(isset($contenido)) {echo $contenido;} else {echo '';} ?>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row-fluid">
      <!-- LUGAR DONDE  SE CARGARAN LAS PAGINAS ENVIADAS -->
               <div class="row" id="contenido">
                <div class="col-lg-12">
                        
                            <mp:Content />
                            
                            <?php  if(!empty($texto)){ echo $texto; } else {echo '';}?>  
                </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
    <a href="#" class="scrollup">Scroll</a>  <!-- Boton para Subir en la pagina -->
    <div id="wait"><img src="<?php echo base_url(); ?>media/sistema/spinner.gif" width="64" height="64" /><br> Cargando.. </div>
    
    <div class="row-fluid" id="pie">              
        <span style="color: #c0c0c0; font-size: 10pt;">Copyright <?php echo date('Y');?> ©&nbsp;Regional de Salud. Todos los Derechos Resevados</span>
    </div>

</body>

</html>


<script type="text/javascript">
    $(document).ready(function(){
        //debugger;
        tipo_alerta='<?php echo $this->session->flashdata('tipo_alerta');?>';
        texto_alerta = '<?php echo $this->session->flashdata('texto_alerta') ?>';

        if(tipo_alerta === 'success')
        {    
            alertify.success(texto_alerta);
        } 
        else if(tipo_alerta === 'error')
        {
            alertify.error(texto_alerta);
        } else if(tipo_alerta === 'alert')
        {
            alertify.alert(texto_alerta).setHeader('');
        }



        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });

        //  cargar un Wait en cada peticion Ajax
           // $(document).ajaxStart(function(){
           //  setTimeout(function(){
           //      $("#wait").css("display", "block");
           //      //$("#wail").fadeIn();
           //  }, 2000);
     
           //  });
           //  $(document).ajaxComplete(function(){
           //      $("#wait").css("display", "none");
           //      //$("#wait").delay(900).fadeOut();
           //  });
        // Fin de la prueba

        $(".fecha").mask("99-99-9999");    
        $(".fecha").datepicker({
              beforeShow: function() {
                    setTimeout(function(){
                        $('.ui-datepicker').css('z-index', 101);
                    }, 0);
                },
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            nextText: "Siguiente",
            prevText: "Anterior",
            closeText : "Cerrar",
            inline: true,
            currentText: "Ahora"
        }).click(function() {
            $('button.ui-datepicker-current')
            .removeClass('ui-priority-secondary')
            .addClass('ui-priority-primary');
        });
        $('button.ui-datepicker-current').live('click', function() {
            $.datepicker._curInst.input.datepicker('setDate', new Date()).datepicker('hide').blur();
        });

    });
</script>





