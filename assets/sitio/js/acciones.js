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
				//swal('Error', res.mensaje, 'error');
			} else {
			  /*swal('Éxito', res.mensaje, 'success').then(function(){
					$('.modal').modal('hide');
					$('.tabla-ajax').bootgrid('reload');
					location.href = res.url;
				});*/
			}
		}).fail(function(){
			//loadingApp.desactivar();
			//swal('Error', 'Error al conectarse con el servidor', 'error');
		});
		$(".form-ajax")[0].reset();
	});
});
