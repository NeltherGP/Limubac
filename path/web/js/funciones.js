$(document).ready(function  () {

});

function get_equipos(){
	  var url= $("#EqA").attr("alt");
		var url2 =$("#formulario").attr("action");

	  $.post(url).done(function(data){
	  	var datos = JSON.parse(data);
	  	$("#EqA").attr("value",datos[1].nombre);
	  	$("#EqB").attr("value",datos[0].nombre);
	  	$("#Rama").attr("value",datos[0].rama);
	  	$("#Categoria").attr("value",datos[0].categoria);
	  	$("#Lugar").attr("value",datos[0].lugar);
	  	$("#Torneo").attr("value",datos[0].torneo);
			//$("#Torneo").attr("alt",datos[0].idpartido);
		$("#formulario").attr("action",url2+"/"+datos[0].idpartido);
	  });
}

function getIdTorneo(){
  var url2= $("#Torneo").attr("alt");

  $("#formulario").attr("action","{{path('limubacadministrator_hojaAnotaciones',{'idpartido':"+url2+"})}}");

}
