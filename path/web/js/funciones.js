var primero=0;
var segundo=0;
var tercero=0;
var cuarto=0;
var complementario=0;

$(document).ready(function  () {
	selectTorneoPartidos();
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

function formCompletar(id){//Este metodo no es generico debe mejorarse
	$("#formulariocompletar").attr("action","/limubac/path/web/app_dev.php/completarInfoPartidoPost/"+id);

}

function selectTorneoPartidos(){
	$('#SelectTorneo').modal({backdrop: 'static', keyboard: false});
	$('#SelectTorneo').modal({show: true});
}
/*
function checkboxColor(valor){
	var aux,aux1;
	if(primero>valor){
		aux=primero;
		primero=valor;
		complementario=tercero;
		tercero=segundo;
		segundo=aux;
	}else{
		if(segundo>valor){
			aux=
		}
	}
}*/
