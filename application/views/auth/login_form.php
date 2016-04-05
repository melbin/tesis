<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'username',
	'placeholder' => 'Usuario o Correo',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'placeholder' => 'Contraseña',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Regional de Salud</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>stylesheet/style.css" />
</head>

<body>
<div id="logo">
	<img style="height: 30%; position: absolute; padding-left: 46%; top: -118px;>" src="<?php echo base_url(); ?>media/sistema/<?php echo $logo;?>.gif">	
</div>

<div class="container">
	<section id="content">
		
		<?php echo form_open($this->uri->uri_string()); ?>
		<h1>Regional</h1>
<table>
	<tr>
		<!--<td><?php echo form_label($login_label, $login['id']); ?></td> -->
		<td><?php echo form_input($login); ?></td>
		<!-- <td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td> -->
		<label style="color:red;font-size:11px;"><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></label>
	</tr>	
	<tr>
	<!--	<td><?php echo form_label('Password', $password['id']); ?></td> -->
		<td><?php echo form_password($password); ?></td>
		<label style="color:red;font-size:11px;"><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></label>
		<!-- <td style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td> -->
	</tr>

	<?php if ($show_captcha) {
		if ($use_recaptcha) { ?>
	<tr>
		<td colspan="2">
			<div id="recaptcha_image"></div>
		</td>
		<td>
			<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recaptcha_only_if_image">Enter the words above</div>
			<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
		</td>
		<td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
		<td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
		<?php echo $recaptcha_html; ?>
	</tr>
	<?php } else { ?>
	<tr>
		<td colspan="3">
			<p>Enter the code exactly as it appears:</p>
			<?php echo $captcha_html; ?>
		</td>
	</tr>
	<tr>
		<td><?php echo form_label('Confirmation Code', $captcha['id']); ?></td>
		<td><?php echo form_input($captcha); ?></td>
		<td style="color: red;"><?php echo form_error($captcha['name']); ?></td>
	</tr>
	<?php }
	} ?>

	<tr>
		<td colspan="3" style="text-align:left;">
			<?php echo form_checkbox($remember); ?>
			<?php echo form_label('Recordarme', $remember['id']); ?>
			<!--  <?php echo anchor('/auth/forgot_password/', 'Olvidó su contraseña'); ?> -->
		    <!--	<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Registrarse'); ?> -->
		</td>
	</tr>
</table>
<?php echo form_submit('submit', 'Ingresar'); ?>
<?php echo form_close(); ?>
		
	</section><!-- content -->
</div><!-- container -->
</body>
</html>
