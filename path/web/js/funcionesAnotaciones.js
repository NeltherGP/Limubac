$(document).ready(function  () {
get_idTorneo();
});

function getIdTorneo(){
  var url= $("#formulario").attr("alt");
  $.post(url).done(function(data){
  $("#formulario").attr("action","{{path('limubacadministrator_hojaAnotaciones',{'idpartido':"+data+"})}}");
  });
}
