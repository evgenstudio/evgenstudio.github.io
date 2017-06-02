$(document).ready(function() {

	//Таймер обратного отсчета
	//Документация: http://keith-wood.name/countdown.html
	//<div class="countdown" date-time="2015-01-07"></div>
	function getWeekDay(date) {
  var days = ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'];

  return days[date.getDay()];
}
function menu_slider_chose(aa){
	aa.indexOf();
}




var d = new Date();
var month_num = d.getMonth()
var day = d.getDate();


month=new Array("январь", "февраль", "марта", "апрель", "май", "июнь",
"июль", "август", "сентябрь", "октябрь", "ноябрь", "декабрь");

  $("html").niceScroll({
  	cursorcolor: "#ce000c",
  	cursoropacitymin: 1,
  	cursorborder: "1px solid #ce000c",
  	background: "fdfed6",
  	mousescrollstep: 30,
  	cursorborderradius: "0px" // change opacity when cursor is inactive (scrollabar "hidden" state), range from 1 to 0

  });



 // 'пт'

$(".day").html(day);
$(".month").html(month[month_num]);
$(".weekday").html(getWeekDay(d));

$(".menu__category").click(function(){
      	$(".menu__category.active").removeClass("active");
      	$(this).addClass("active");
      	menu_slider_chose(this);
      });
	 var pp = setTimeout(function(){

	$(".header__portraits").addClass("slideInUp");
	$(".header__bg").addClass("slideInUp");
	clearTimeout(pp);

    },600)

	 if ($(window).width() >= '1001'){
		$('.upp').addClass("hiddens").viewportChecker({
		classToAdd: 'visibles animated slideInUp',
		offset: 100
		});
		$('.fade').addClass("hiddens").viewportChecker({
		classToAdd: 'visibles animated rotateIn',
		offset: 150
		});
	}
	 


		

	//Попап менеджер FancyBox
	//Документация: http://fancybox.net/howto
	//<a class="fancybox"><img src="image.jpg" /></a>
	//<a class="fancybox" data-fancybox-group="group"><img src="image.jpg" /></a>
	$(".fancybox").fancybox();

	


	//Плавный скролл до блока .div по клику на .scroll
	//Документация: https://github.com/flesler/jquery.scrollTo
	$("a.to__menu").click(function() {
		$.scrollTo($(".menu__section"), 800, {
			offset: -90
		});
	});
	$("a.to__promo").click(function() {
		$.scrollTo($(".promo__section"), 800, {
			offset: 130
		});
	});
	$("a.to__features").click(function() {
		$.scrollTo($(".interior__section"), 800, {
			offset: -90
		});
	});

	var owl2 = $('.slider');
      owl2.owlCarousel(
      	{	
      		loop:true,
      		nav:true,
    margin:0,
    lazyLoad:true,
    dotsContainer:$(".slider__dots"),
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
      	});
      owlt = $(".tslider");
      owlt.owlCarousel(
      	{	
      		loop:true,
      		nav:true,
		    margin:0,
		    autoplay:false,
		    smartSpeed:100,
		    animateIn: 'flash',
   			animateOut: 'flash',
		    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
      	});
     $('.but_l').click(function() {
    owlt.trigger('next.owl.carousel');
   /* $(".twrapper").removeClass("flash");
    $(".twrapper").addClass("flash");*/
})
     $('.but_r').click(function() {
    owlt.trigger('prev.owl.carousel');
    /* $(".twrapper").removeClass("flash");
    $(".twrapper").addClass("flash");*/
})
      $(".menu__slider1").owlCarousel(
      	{	
      		loop:true,
		    margin:0,
		    dotsContainer:$(".menu__dots"),
		    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
      	});
     

});