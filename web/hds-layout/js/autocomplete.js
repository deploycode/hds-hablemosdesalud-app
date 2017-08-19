  $(document).ready(function(){
    src = "../autoComplete";
    $("#search").autocomplete({
      source: function(request, response) {
          $.ajax({
             url: src,
             dataType: "json",
             data: {term : request.term},
             success: function(data){
                 response(data);
             }
          });
       }, min_length: 3,
       select: function( event, ui ) {
          $('#search').val(ui.item.value);
          location.href='/articulos/'+ui.item.post;
          return false;
      }
    });
  });
