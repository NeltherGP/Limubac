$(document).ready(function  () {
	
get_equipos();
});

function get_equipos(){
	  var url= $("#EqA").attr("alt");
	  $.post(url).done(function(data){
	  	var datos = JSON.parse(data);
	  	$("#EqA").attr("value",datos[1].nombre);
	  	$("#EqB").attr("value",datos[0].nombre);
	  	$("#Rama").attr("value",datos[0].rama);
	  	$("#Categoria").attr("value",datos[0].categoria);
	  	$("#Lugar").attr("value",datos[0].lugar);
	  	$("#Torneo").attr("value",datos[0].torneo);
	  });
}