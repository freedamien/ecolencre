
function additionalCarousel(sliderId){
	/*======  curosol For Additional ==== */
	 var tmgadditional = $(sliderId);
      tmgadditional.owlCarousel({
     	 items : 3, //10 items above 1000px browser width
     	 itemsDesktop : [1199,3], 
     	 itemsDesktopSmall : [991,3], 
     	 itemsTablet: [480,3], 
     	 itemsMobile : [320,2] 
      });
      // Custom Navigation Events
      $(".additional_next").click(function(){
        tmgadditional.trigger('owl.next');
      })
      $(".additional_prev").click(function(){
        tmgadditional.trigger('owl.prev');
      });
}

$(document).ready(function(){
	
	bindGrid();
	additionalCarousel("#main #additional-carousel");
	
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
	if(!isMobile) {
		if($(".parallax").length){ $(".parallax").sitManParallex({  invert: false });};
	}else{
		$(".parallax").sitManParallex({  invert: false });
	}
	
	$('.cart_block').on('click', function (event) {
		event.stopPropagation();
	}); 
	
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
	if(!isMobile) {
		if($(".parallax-comment").length){ $(".parallax-comment").sitManParallex({  invert: true });};
	}else{
		$(".parallax-comment").sitManParallex({  invert: true });
	}	
	
	// ---------------- start more menu setting ----------------------
		var max_elem = 6;	
		var items = $('.menu ul#top-menu > li');	
		var surplus = items.slice(max_elem, items.length);
		
		surplus.wrapAll('<li class="category more_menu" id="more_menu"><div id="top_moremenu" class="popover sub-menu js-sub-menu collapse"><ul class="top-menu more_sub_menu">');
	
		$('.menu ul#top-menu .more_menu').prepend('<a href="#" class="dropdown-item" data-depth="0"><span class="pull-xs-right hidden-md-up"><span data-target="#top_moremenu" data-toggle="collapse" class="navbar-toggler collapse-icons"><i class="material-icons add">&#xE313;</i><i class="material-icons remove">&#xE316;</i></span></span></span>More</a>');
	
		$('.menu ul#top-menu .more_menu').mouseover(function(){
			$(this).children('div').css('display', 'block');
		})
		.mouseout(function(){
			$(this).children('div').css('display', 'none');
		});
	// ---------------- end more menu setting ----------------------

});

// ------------- vertical menu ----------------

$("#_desktop_vertical_menu").click(function(){
$(this).toggleClass('active');
$("#_desktop_vertical_menu #top-vertical-menu").slideToggle("slow");
  });

// Add/Remove acttive class on menu active in responsive  
	$('#menu-icon').on('click', function() {
		$(this).toggleClass('active');
	});

// Loading image before flex slider load
	$(window).load(function() { 
		$(".loadingdiv").removeClass("spinner"); 
	});

// Flex slider load
	//$(window).load(function() {
		//if($('.flexslider').length > 0){ 
		//	$('.flexslider').flexslider({		
			//	slideshowSpeed: $('.flexslider').data('interval'),
			//	pauseOnHover: $('.flexslider').data('pause'),
			//	animation: "fade"
			//});
		//}
	//});		

// Scroll page bottom to top
	$(window).scroll(function() {
		if ($(this).scrollTop() > 500) {
			$('.top_button').fadeIn(500);
		} else {
			$('.top_button').fadeOut(500);
		}
	});							
	$('.top_button').click(function(event) {
		event.preventDefault();		
		$('html, body').animate({scrollTop: 0}, 800);
	});



/*======  Carousel Slider For Feature Product ==== */
	
	var tmgfeature = $("#feature-carousel");
	tmgfeature.owlCarousel({
		items :4, //10 items above 1000px browser width
		itemsDesktop : [1199,4], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [767,1], 
		itemsMobile : [479,1]
	});
	// Custom Navigation Events
	$(".feature_next").click(function(){
		tmgfeature.trigger('owl.next');
	})
	$(".feature_prev").click(function(){
		tmgfeature.trigger('owl.prev');
	});

/*======  Carousel Slider For Feature CMS ==== */
	
	var tmgfeaturecms = $("#feature-cms");
	tmgfeaturecms.owlCarousel({
		items :1, //10 items above 1000px browser width
		itemsDesktop : [1199,1], 
		itemsDesktopSmall : [991,1], 
		itemsTablet: [767,1], 
		itemsMobile : [479,1]
	});
	// Custom Navigation Events
	$(".featurecms_next").click(function(){
		tmgfeaturecms.trigger('owl.next');
	})
	$(".featurecms_prev").click(function(){
		tmgfeaturecms.trigger('owl.prev');
	});

/*======  Carousel Slider For service Product ==== */
	
	var tmgservice = $("#service-carousel");
	tmgservice.owlCarousel({
		items :1, //10 items above 1000px browser width
		itemsDesktop : [1199,1], 
		itemsDesktopSmall : [991,1], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1],
		autoPlay:true 
	});
	
/*======  Carousel Slider For Instagram Block ==== */
	var tmginsta = $("#instagram-carousel");
	tmginsta.owlCarousel({
		items : 6, //10 items above 1000px browser width
		itemsDesktop : [1199,4], 
		itemsDesktopSmall : [991,3], 
		itemsTablet: [479,2], 
		itemsMobile : [319,2] 
	});
	// Custom Navigation Events
	$(".insta_next").click(function(){
		tmginsta.trigger('owl.next');
	})
	$(".insta_prev").click(function(){
		tmginsta.trigger('owl.prev');
	});

/*======  Carousel Slider For New Product ==== */
	
	var tmgnewproduct = $("#newproduct-carousel");
	tmgnewproduct.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,4], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".newproduct_next").click(function(){
		tmgnewproduct.trigger('owl.next');
	})
	$(".newproduct_prev").click(function(){
		tmgnewproduct.trigger('owl.prev');
	});



/*======  Carousel Slider For Bestseller Product ==== */
	
	var tmgbestseller = $("#bestseller-carousel");
	tmgbestseller.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,4], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".bestseller_next").click(function(){
		tmgbestseller.trigger('owl.next');
	})
	$(".bestseller_prev").click(function(){
		tmgbestseller.trigger('owl.prev');
	});



/*======  Carousel Slider For Special Product ==== */
	var tmgspecial = $("#special-carousel");
	tmgspecial.owlCarousel({
		items : 2, //10 items above 1000px browser width
		itemsDesktop : [1199,2], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".special_next").click(function(){
		tmgspecial.trigger('owl.next');
	})
	$(".special_prev").click(function(){
		tmgspecial.trigger('owl.prev');
	});


/*======  Carousel Slider For Accessories Product ==== */

	var tmgaccessories = $("#accessories-carousel");
	tmgaccessories.owlCarousel({
		items : 3, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".accessories_next").click(function(){
		tmgaccessories.trigger('owl.next');
	})
	$(".accessories_prev").click(function(){
		tmgaccessories.trigger('owl.prev');
	});


/*======  Carousel Slider For Category Product ==== */

	var tmproductscategory = $("#productscategory-carousel");
	tmproductscategory.owlCarousel({
		items : 3, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".productscategory_next").click(function(){
		tmproductscategory.trigger('owl.next');
	})
	$(".productscategory_prev").click(function(){
		tmproductscategory.trigger('owl.prev');
	});


/*======  Carousel Slider For Viewed Product ==== */

	var tmgviewed = $("#viewed-carousel");
	tmgviewed.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".viewed_next").click(function(){
		tmgviewed.trigger('owl.next');
	})
	$(".viewed_prev").click(function(){
		tmgviewed.trigger('owl.prev');
	});

/*======  Carousel Slider For Crosssell Product ==== */

	var tmgcrosssell = $("#crosssell-carousel");
	tmgcrosssell.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".crosssell_next").click(function(){
		tmgcrosssell.trigger('owl.next');
	})
	$(".crosssell_prev").click(function(){
		tmgcrosssell.trigger('owl.prev');
	});

/*======  curosol For Manufacture ==== */
	 var tmgbrand = $("#brand-carousel");
      tmgbrand.owlCarousel({
     	 items : 6, //10 items above 1000px browser width
     	 itemsDesktop : [1199,4], 
     	 itemsDesktopSmall : [991,3],
     	 itemsTablet: [480,2], 
     	 itemsMobile : [320,1] 
      });
      // Custom Navigation Events
      $(".brand_next").click(function(){
        tmgbrand.trigger('owl.next');
      })
      $(".brand_prev").click(function(){
        tmgbrand.trigger('owl.prev');
      });
	  



/*======  Carousel Slider For For Tesimonial ==== */

	var tmgtestimonial = $("#testimonial-carousel");
	tmgtestimonial.owlCarousel({
		 pagination:true,
	     items : 1, //10 items above 1000px browser width
     	 itemsDesktop : [1199,1], 
     	 itemsDesktopSmall : [991,1],
     	 itemsTablet: [480,1], 
     	 itemsMobile : [320,1]
	});
	// Custom Navigation Events
      $(".tmgtestimonial_next").click(function(){
        tmgtestimonial.trigger('owl.next');
      });

      $(".tmgtestimonial_prev").click(function(){
        tmgtestimonial.trigger('owl.prev');
      });

	 
/*======  Carousel Slider For For blog ==== */

	 var blogcarousel = $("#blog-carousel");
      blogcarousel.owlCarousel({
     	 items : 3, //10 items above 1000px browser width
     	 itemsDesktop : [1199,3], 
     	 itemsDesktopSmall : [991,2],
     	 itemsTablet: [480,1], 
     	 itemsMobile : [320,1] 
      });
 	 // Custom Navigation Events
      $(".blog_next").click(function(){
        blogcarousel.trigger('owl.next');
      });

      $(".blog_prev").click(function(){
        blogcarousel.trigger('owl.prev');
      });	


function bindGrid()
{
	var view = $.totalStorage("display");

	if (view && view != 'grid')
		display(view);
	else
		$('.display').find('li#grid').addClass('selected');

	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '#list', function(e){
		e.preventDefault();
		display('list');		
	});	
}

function display(view)
{
	if (view == 'list')
	{
		$('#products ul.product_list').removeClass('grid').addClass('list row');
		$('#products .product_list > li').removeClass('col-xs-12 col-sm-6 col-md-6 col-lg-4').addClass('col-xs-12');
		
		
		$('#products .product_list > li').each(function(index, element) {
			var html = '';
			html = '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product"><div class="row">';
				html += '<div class="thumbnail-container col-xs-4 col-xs-5 col-md-4">' + $(element).find('.thumbnail-container').html() + '</div>';
				
				html += '<div class="product-description center-block col-xs-4 col-xs-7 col-md-8">';
				
				html += '<div class="product_reviews">' + $(element).find('.product_reviews').html() + '</div>';
				
				html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() + '</h3>';
					
					var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
					if (price != null) {
						html += '<div class="product-price-and-shipping">'+ price + '</div>';
					}
					
					html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
					
					var colorList = $(element).find('.highlighted-informations').html();
					if (colorList != null) {
						html += '<div class="highlighted-informations">'+ colorList +'</div>';
					}
					
					html += '<div class="product-actions">'+ $(element).find('.product-actions').html() +'</div>';
					
				html += '</div>';
			html += '</div></div>';
		$(element).html(html);
		});
		$('.display').find('li#list').addClass('selected');
		$('.display').find('li#grid').removeAttr('class');
		$.totalStorage('display', 'list');
	}
	else
	{
		$('#products ul.product_list').removeClass('list').addClass('grid row');
		$('#products .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-6 col-md-6 col-lg-4');
		$('#products .product_list > li').each(function(index, element) {
		var html = '';
		html += '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product">';
			html += '<div class="thumbnail-container">' + $(element).find('.thumbnail-container').html() +'</div>';
			
			html += '<div class="product-description">';
			
				html += '<div class="product_reviews">' + $(element).find('.product_reviews').html() + '</div>';
				
				html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() +'</h3>';
			
				var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
				if (price != null) {
					html += '<div class="product-price-and-shipping">'+ price + '</div>';
				}
				
				html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
				
				var colorList = $(element).find('.highlighted-informations').html();
				if (colorList != null) {
					html += '<div class="highlighted-informations">'+ colorList +'</div>';
				}
				
			html += '</div>';
			
		html += '</div>';
		$(element).html(html);
		});
		$('.display').find('li#grid').addClass('selected');
		$('.display').find('li#list').removeAttr('class');
		$.totalStorage('display', 'grid');
	}
}
/*$("#footer .block-social").appendTo(".block_newsletter");*/
function responsivecolumn(){
	
	if ($(document).width() <= 767){
				
	//---------------- Fixed header responsive ----------------------
		$(window).bind('scroll', function () {
		if ($(window).scrollTop() > 0) {
				$('.header-top').addClass('fixed');
		} else {
				$('.header-top').removeClass('fixed');
			}
		});
	}
	
	if ($(document).width() >= 768){
				
		// ---------------- Fixed header responsive ----------------------
		$(window).bind('scroll', function () {
			if ($(window).scrollTop() > 150) {
				$('#header .header-nav-full').addClass('fixed');
			} else {
				$('#header .header-nav-full').removeClass('fixed');
			}
		});
	}
	
	
	if ($(document).width() <= 991)
	{
		$('.container #columns_inner #left-column').appendTo('.container #columns_inner');
		
	}
	else if($(document).width() >= 992) 
	{
		$('.container #columns_inner #left-column').prependTo('.container #columns_inner');
		
	}
}
$(document).ready(function(){responsivecolumn();});
$(window).resize(function(){responsivecolumn(); });

$(document).ready(function(){customMoves();});
function customMoves(){	
	
    $("#top-menu .sub-menu li:has(ul),.vertical-menu .top-vertical-menu li:has(ul)").parent().parent().addClass("mega");
	$("#top-menu .sub-menu li:has(ul),.vertical-menu .top-vertical-menu li:has(ul)").parent().parent().parent().addClass("mega-li");
   
	$('.account-button').click(function(event){		
		$(this).toggleClass('active');		
		event.stopPropagation();		
		$(".user-info").slideToggle("slow");	
	});

	$('.search-button').click(function(event){		
		$(this).toggleClass('active');	
		$(".search-widget form .ui-autocomplete-input").focus();
	});
	
	
	$(document).click(function(event){		
		$('.search-button').removeClass('active');
	});	
	
	$(".user-info").on("click", function (event) {		
		event.stopPropagation();	
	});

}
jQuery(window).scroll(function () { hb_animated_contents(); });
jQuery(window).load(function () { hb_animated_contents(); });
function hb_animated_contents() {
	  jQuery(".hb-animate-element:in-viewport").each(function (i) {
	  var $this = jQuery(this);
	  if (!$this.hasClass('hb-in-viewport')) {
	  setTimeout(function () {
	  $this.addClass('hb-in-viewport');
	  }, 150 * i);
	  }
	});
}