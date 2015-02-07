$(document).ready(function() { 
	$(".añadirF").each(function (X){
				$(this).bind("click",addField_Faltas);
										 });
	$(".añadirA").each(function (X){
				$(this).bind("click",addField_Anotacion);
										 });
								});

function addField_Faltas(){
	$("#jugadorFaltas").clone(true).appendTo("#jugadorFaltoso");
}

function addField_Anotacion(){
	console.log("hola");
	$("#jugadorAnotacion").clone(true).appendTo("#jugadorAnotador");
}