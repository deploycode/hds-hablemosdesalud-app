$(document).ready(function(){
  var flag = false;
  var scroll;

  if(screen.width<=768){
    $(".menu-first").addClass('navbar-fixed-top');
    $("#main").css('margin-top','50px');
  }

  $(window).scroll(function(){
    scroll = $(window).scrollTop();
    if((scroll > 230) && (screen.width>768)){
      if(!flag){
        $(".menu-third").addClass('navbar-fixed-top');
        $(".menu-third").removeClass('sombra');
        $(".menu-third").removeClass('padding-sombra');
        flag = true;
      }
    }else{
      if(flag){
        $(".menu-third").removeClass('navbar-fixed-top');
        $(".menu-third").addClass('sombra');
        $(".menu-third").addClass('padding-sombra');
        flag = false;
      }
    }
  });
  $('#subir').click(function(){
		$('body, html').animate({
			scrollTop: '0px'
		}, 1000);
	});
	$('#scrollspy a').click(function(){
			$('#menu').removeClass('in');
	});
});
// carusel
(function(){
  $('.carousel-showmanymoveone .item').each(function(){
    var itemToClone = $(this);

    for (var i=1;i<4;i++) {
      itemToClone = itemToClone.next();

      // wrap around if at end of item collection
      if (!itemToClone.length) {
        itemToClone = $(this).siblings(':first');
      }

      // grab item, clone, add marker class, add to collection
      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo($(this));
    }
  });
}());
