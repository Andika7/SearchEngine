$(document).ready(function() {
  $(".btn").click(function(){
    var cari = $("#cari").val();
    var rank = $("#rank").val();

    $.ajax({
           url: BASE_URL+'Dashboard/Query/'+cari+'/'+rank,
           dataType : "json",
           success: function(data){
                 // alert(data);
                 $('#time').html(data[0]);
                 $('#content').html(data[1]);
                 $('#found').html(data[2]);
            },
            error: function(data){
                 alert("Please insert your command");
            }
        });
});

  $('.form-control').keypress(function (e) {
    var cari = $("#cari").val();
    var rank = $("#rank").val();
    if (e.which == 13) {
    $.ajax({
           url: BASE_URL+'Dashboard/Query/'+cari+'/'+rank,
           dataType : "json",
           success: function(data){
                 // alert(data);
                 $('#time').html(data[0]);
                 $('#content').html(data[1]);
                 $('#found').html(data[2]);
            },
            error: function(data){
                 alert("Please insert your command");
            }
        });
      }
  });
});
