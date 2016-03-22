
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

	<div style='height:20px;' id="div_botones"></div>  

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
        	<?php echo $output; ?>
       </div>
    </div>
 
    <script type="text/javascript">
 //   $("#div_botones").append('<button type="button" style="float: right; margin-top: -15px;" onclick="window.history.back();" class="btn btn-outline btn-default">Volver</button>');
    </script>
