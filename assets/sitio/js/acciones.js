$(document).ready(function(){
  $(document).on('submit','.form-ajax',function(e){
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();
		var url = $this.data('url');
		//loadingApp.activar();
		$.post(ajaxurl + '/' + url, data).then(function(res){
			//loadingApp.desactivar();
			if(res.error){
			     swal('Error', res.message, 'error');
			} else {
			  swal('Éxito', res.mensaje, 'success').then(function(){
					location.href = res.url;
				});
			}
		}).fail(function(){
			//loadingApp.desactivar();
			//swal('Error', 'Error al conectarse con el servidor', 'error');
		});
		$(".form-ajax")[0].reset();
	});
});
