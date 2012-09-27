$(document).ready(function(){
$("#RegistroTriavip2").validate({
		rules: {
			user_name: {
				required: true,
				minlength: 2
			},
			user_lastname: {
				required: true,
				minlength: 2
			},
			email_address: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 6
			},
			agree: "required"
		},
		messages: {
			user_name: {
						required:"Tu nombre debe tener al menos 2 caracteres. Por favor int&eacute;ntalo de nuevo",
						minlength: "Tu nombre debe tener al menos 2 caracteres"
						},
			user_lastname:
						{
						required:"Tu apellido debe tener al menos 2 caracteres. Por favor inténtalo de nuevo",
						minlength: "Tu apellido debe tener al menos 2 caracteres"
						},
			email_address: "Lo sentimos, tu email no es v&aacute;lido. Por favor int&eacute;ntalo de nuevo",
			password: {
				required: "Por favor, introduce contraseña.Tu contraseña tiene que ser mínimo de 6 caracteres",
				minlength: "Tu contraseña tiene que ser mínimo de 6 caracteres"
			},
			agree: "Por favor, acepte los términos y condiciones"
		}
	});
});