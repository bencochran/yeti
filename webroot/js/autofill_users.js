var data = ["Something","another", "brerer"];
$().ready(function(){
  //$('input#user').autocomplete("/user/autocomplete.php");
  //$('input#user').autocomplete(data);
  $('#title').autocomplete(['thing','another','yay']);
  $('input#user').css('width','200px');
});