var webp = false;

Modernizr.on('webp', function(result) {
  if (result) {
  	webp = true;
  	$('body').addClass('webp');
  } else {
    webp = false;
    $('body').addClass('nowebp');
  }
});

$(document).ready(function() {

	if(location.hash.includes('#')){
		function ScrollDown(){
			$('html,body').animate({scrollTop:$('[data-anchor="'+location.hash.replace('#','')+'"]').offset().top - 150}, 400);	
		}
		var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
		if(isChrome){
			if ('scrollRestoration' in history) {
				history.scrollRestoration = 'manual';
			}
			window.onload = setTimeout(function(){
			        ScrollDown();
			    },0);	
		}
		else{
			ScrollDown();
		}
		
	}
	

	var windowWidth = $(window).width();

	$('.header_burger').on('click', function(){
		if($('header').hasClass('_active')){
			$('header').removeClass('_active');
		}
		else{
			$('header').addClass('_active');
		}	
	});

// ---------------------------------------------------------------------- РЕСАЙЗ --------------------------------------------------------------------
	
	if(windowWidth > 1439){
		var resizeFlag = 1440;
	}
	else if(windowWidth > 767){
		var resizeFlag = 768;
	}
	else{
		var resizeFlag = 320;
	}

	$(window).on('resize', function(){
		windowWidth = $(window).width();
		if(resizeFlag == 1440 && windowWidth < 1440){
			if(windowWidth > 767){
				resizeFlag = 768;
				resizeToPad();
			}
			else{
				resizeFlag = 320;
				resizeToMobile();
			}
		}
		if(resizeFlag == 768 && windowWidth > 1439){
			resizeFlag = 1440;
			resizeToDesc();
		}
		if(resizeFlag == 768 && windowWidth < 768){
			resizeFlag = 320;
			resizeToMobile();
		}
		if(resizeFlag == 320 && windowWidth > 767){
			if(windowWidth > 1439){
				resizeFlag = 1440;
				resizeToDesc();
			}
			else{
				resizeFlag = 768;
				resizeToPad();
			}
		}
	});

	function resizeToDesc(){
		console.log('desc');
		if(typeof itemCar !== 'undefined'){
			itemCar.destroy();
		}
		if(typeof mySwiper !== 'undefined'){
			mySwiper.destroy();
			personalDesc();
		}
		if(typeof reviewCar !== 'undefined'){
			reviewCar.destroy();
			reviewDesc();
		}
		if(typeof itemVal !== 'undefined'){
			itemVal.destroy();
		}

		if(typeof fotorama !== 'undefined'){
			objectGalleryDesc();
		}
		if(typeof tagCar !== 'undefined'){
			if(typeof tagCar == 'object'){
				tagCar.destroy();
			}
		}
	}

	function resizeToPad(){
		console.log('pad');
		if(typeof itemCar !== 'undefined'){
			if(typeof itemCar == 'object'){
				itemCar.destroy();
			}
			itemCarPad();
		}
		if(typeof mySwiper !== 'undefined'){
			if(typeof mySwiper == 'object'){
				mySwiper.destroy();
			}
			console.log('perspad');
			personalPad();
		}
		if(typeof reviewCar !== 'undefined'){
			reviewCar.destroy();
			reviewPad();
		}
		if(typeof itemVal !== 'undefined'){
			if(typeof itemVal == 'object'){
				itemVal.destroy();
			}
			itemValPad();
		}
		if(typeof fotorama !== 'undefined'){
			objectGalleryPad();
		}
		if(typeof tagCar !== 'undefined'){
			if(typeof tagCar == 'object'){
				tagCar.destroy();
			}
		}
	}

	function resizeToMobile(){
		console.log('mobile');
		if(typeof itemCar !== 'undefined'){
			if(typeof itemCar == 'object'){
				itemCar.destroy();
			}
			itemCarMobile();
		}
		if(typeof mySwiper !== 'undefined'){
			if(typeof mySwiper == 'object'){
				mySwiper.destroy();
			}
			personalMobile();
		}
		if(typeof reviewCar !== 'undefined'){
			if(typeof reviewCar == 'object'){
				reviewCar.destroy();
			}
			reviewMobile();
		}
		if(typeof itemVal !== 'undefined'){
			if(typeof itemVal == 'object'){
				itemVal.destroy();
			}
			itemValMobile();
		}
		if(typeof fotorama !== 'undefined'){
			objectGalleryMobile();
		}
		if(typeof tagCar !== 'undefined'){
			if(typeof tagCar == 'object'){
				tagCar.destroy();
			}
			tagCarMobile();
		}
	}

// ---------------------------------------------------------------------- РЕСАЙЗ --------------------------------------------------------------------

// ---------------------------------------------------------------- КАРУСЕЛЬ ИЗБРАННОГО -------------------------------------------------------------

	if($('.items_list_main').length > 0){
		var itemCar = '';
		if(windowWidth > 767 && windowWidth < 1440){
			itemCarPad();
		}
		else if(windowWidth < 768){
			itemCarMobile();
		}		

		function itemCarPad(){
			itemCar = new Swiper('.items_list_main', {
				loop: true,
				slidesPerView: 3,
				spaceBetween: 15,
				centeredSlides: true,
				pagination: {
			        el: '.items_list_pagination',
			        clickable: true,
			    },
			});
		};

		function itemCarMobile(){
			itemCar = new Swiper('.items_list_main', {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 15,
				centeredSlides: true,
				pagination: {
			        el: '.items_list_pagination',
			        clickable: true,
			    },
			});
		};
	};
// ---------------------------------------------------------------- КАРУСЕЛЬ ИЗБРАННОГО -------------------------------------------------------------

// ----------------------------------------------------------------- КАРУСЕЛЬ ПЕРСОНАЛА -------------------------------------------------------------

	if($('.personal_wrapper').length > 0){
		var mySwiper = '';

		if(windowWidth > 1439){
			personalDesc();
		}
		else if(windowWidth > 767 && windowWidth < 1440){
			personalPad();
		}
		else if(windowWidth < 768){
			personalMobile();
		}		

		function personalDesc(){
			mySwiper = new Swiper ('.personal_wrapper', {
				loop: true,
				navigation: {
				  nextEl: '.personal_arrow._next',
				  prevEl: '.personal_arrow._prev',
				},
				slidesPerView: 5,
				centeredSlides: true,
				init: false,
				simulateTouch: false,
			});

			mySwiper.on('init', function() {
				var swiperLength = mySwiper.slides.length - mySwiper.loopedSlides*2 - 1;
				$('.personal_wrapper .swiper-slide').each(function(){
					if($(this).data('swiper-slide-index') == 0){
						$(this).addClass('_personalCurrent');
					}
					else if($(this).data('swiper-slide-index') == 1 || $(this).data('swiper-slide-index') == swiperLength){
						$(this).addClass('_personalHalf');
					}
				});
			});
			
			mySwiper.init();

			mySwiper.on('slideChange', function(){
				var swiperLength = mySwiper.slides.length - mySwiper.loopedSlides*2 - 1;
				if(mySwiper.realIndex == 0){
					var indexLeft = swiperLength;
				}
				else{
					var indexLeft = mySwiper.realIndex - 1;
				}
				if(mySwiper.realIndex == swiperLength){
					var indexRight = 0;
				}
				else{
					var indexRight = mySwiper.realIndex + 1;
				}
				$('.personal_wrapper .swiper-slide').each(function(){
					if($(this).data('swiper-slide-index') == mySwiper.realIndex){
						$(this).removeClass('_personalHalf');
						$(this).addClass('_personalCurrent');
					}
					else if($(this).data('swiper-slide-index') == indexRight || $(this).data('swiper-slide-index') == indexLeft ){
						$(this).removeClass('_personalCurrent');
						$(this).addClass('_personalHalf');
					}
					else{
						$(this).removeClass('_personalCurrent');
						$(this).removeClass('_personalHalf');
					}
				});
			});

			mySwiper.on('beforeDestroy', function(){
				$('.personal_wrapper .swiper-slide').each(function(){
					$(this).removeClass('_personalHalf');
					$(this).removeClass('_personalCurrent');
				});
			});
		}

		function personalPad(){
			mySwiper = new Swiper ('.personal_wrapper', {
				loop: true,
				navigation: {
				  nextEl: '.personal_arrow._next',
				  prevEl: '.personal_arrow._prev',
				},
				slidesPerView: 3,
				centeredSlides: true,
				init: true,
				simulateTouch: false,
			});
		}

		function personalMobile(){
			mySwiper = new Swiper ('.personal_wrapper', {
				loop: true,
				navigation: {
				  nextEl: '.personal_arrow._next',
				  prevEl: '.personal_arrow._prev',
				},
				slidesPerView: 1,
				centeredSlides: true,
				init: true,
				simulateTouch: false,
				pagination: {
			        el: '.personal_pagination',
			        clickable: true,
			    },
			    autoHeight: true,
			});
		}
	};
// ----------------------------------------------------------------- КАРУСЕЛЬ ПЕРСОНАЛА -------------------------------------------------------------

// ----------------------------------------------------------------- КАРУСЕЛЬ ОТЗЫВОВ -------------------------------------------------------------

	if($('.reviews_list').length > 0){
		var reviewCar;

		if(windowWidth > 1439){
			reviewDesc();
		}
		else if(windowWidth > 767){
			reviewPad();
		}
		else{
			reviewMobile();
		}

		function reviewDesc(){
			reviewCar = new Swiper ('.reviews_list', {
				loop: true,
				navigation: {
				  nextEl: '.reviews_arrow._next',
				  prevEl: '.reviews_arrow._prev',
				},
				slidesPerView: 1,
				spaceBetween: 50,
				centeredSlides: true,
				init: true,
				simulateTouch: true,
				autoHeight: true,
			});
		}

		function reviewPad(){
			reviewCar = new Swiper ('.reviews_list', {
				loop: true,
				navigation: {
				  nextEl: '.reviews_arrow._next',
				  prevEl: '.reviews_arrow._prev',
				},
				slidesPerView: 1,
				spaceBetween: 50,
				centeredSlides: true,
				init: true,
				simulateTouch: true,
				autoHeight: true,
				pagination: {
			        el: '.review_pagination',
			        clickable: true,
			    },
			});
		}

		function reviewMobile(){
			reviewCar = new Swiper ('.reviews_list', {
				loop: true,
				navigation: {
				  nextEl: '.reviews_arrow._next',
				  prevEl: '.reviews_arrow._prev',
				},
				slidesPerView: 1,
				spaceBetween: 50,
				centeredSlides: true,
				init: true,
				simulateTouch: true,
				autoHeight: true,
				pagination: {
			        el: '.review_pagination',
			        clickable: true,
			    },
			});
		}
	};

// ----------------------------------------------------------------- КАРУСЕЛЬ ОТЗЫВОВ -------------------------------------------------------------

// ---------------------------------------------------------------- КАРУСЕЛЬ ЦЕННОСТЕЙ ------------------------------------------------------------

	if($('.valuations_items').length > 0){
		var itemVal = '';
		if(windowWidth > 767 && windowWidth < 1440){
			itemValPad();
		}
		else if(windowWidth < 768){
			itemValMobile();
		}		

		function itemValPad(){
			itemVal = new Swiper('.valuations_items', {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 40,
				centeredSlides: true,
				autoHeight: true,
				pagination: {
			        el: '.valuations_pagination',
			        clickable: true,
			    },
			});
		};

		function itemValMobile(){
			itemVal = new Swiper('.valuations_items', {
				loop: true,
				slidesPerView: 1,
				spaceBetween: 40,
				centeredSlides: true,
				autoHeight: true,
				pagination: {
			        el: '.valuations_pagination',
			        clickable: true,
			    },
			});
		};
	};

// ---------------------------------------------------------------- КАРУСЕЛЬ ЦЕННОСТЕЙ ------------------------------------------------------------

// ---------------------------------------------------------------------- ПОПАПЫ ------------------------------------------------------------------

	$('body').on('click', '.popup_close', function() {
		$(this).closest('._popup').removeClass('_active');
		if($(this).closest('._popup').find('form').length > 0){
			$(this).closest('._popup').find('form')[0].reset();
		}
		$(this).closest('._popup').find('.callback_form').removeClass('_success');
		$('.popups').removeClass('_active');
		$('.main_wrap').removeClass('_blur');
	});

	$('body').on('click', '.popup_overlay', function() {
		$('.popups').removeClass('_active');
		$('.main_wrap').removeClass('_blur');
		$('._popup').each(function(){
			$(this).removeClass('_active');
		});
	});

	$('body').on('click', '.personal_item ', function() {
		$('.main_wrap').addClass('_blur');
		$('.popups').addClass('_active');
		$('.popup_personal_img img').attr('src', $(this).find('img').attr('src'));
		$('.popup_personal_prof').text($(this).find('.personal_prof').text());
		$('.popup_personal_name').text($(this).find('.personal_name').text());
		$('.popup_personal_bottom ul').html($(this).find('.personal_content').text());
		$('.popup_personal').addClass('_active');
	});

	$('body').on('click', '.search_open ', function() {
		let search_type = $('[data-search-type]').data('search-type');
		$('.main_wrap').addClass('_blur');
		$('.popups').addClass('_active');
		$('.search_popup').addClass('_active');
		if(search_type == /kvartiry/){
			$('.search_popup .search_popup_title').text('Поиск по базе квартир');
		}
		else{
			$('.search_popup .search_popup_title').text('Поиск по базе домов');
		}
	});

	$('body').on('click', '.search_popup_button ', function() {
		let search_type = $('[data-search-type]').data('search-type');
		let search_string = $('.search_popup_input').val();
		window.location.href = location.protocol + '//' + location.host + search_type + 'search/?s=' + search_string;
	});
	
	$('body').on('click', '.header_phone_button ', function() {
		$('.main_wrap').addClass('_blur');
		$('.popups').addClass('_active');
		var $popup = $('.callback_popup');
		$popup.find('form').data('callback-email', '');
		$popup.find('form').data('callback-type', 'call');
		$popup.addClass('_active');
		$popup.find('.callback_popup_title').text('Перезвонить вам?');
	});

	$('body').on('click', '.object_buy_button', function() {
		$('.main_wrap').addClass('_blur');
		$('.popups').addClass('_active');
		var $popup = $('.callback_popup');
		$popup.find('form').data('callback-email', $(this).data('callback-email'));
		$popup.find('form').data('callback-type', 'order');
		$popup.addClass('_active');
		$popup.find('.callback_popup_title').text('Оформление заявки');
		$popup.find('[name="objectid"]').val($(this).data('object-id'));
		console.log($popup.find('form').data('callback-email'));
		console.log($popup.find('form').data('callback-type'));
	});


// ---------------------------------------------------------------------- ПОПАПЫ ------------------------------------------------------------------

// -------------------------------------------------------------------- ЦЕННОСТИ ------------------------------------------------------------------

	$('body').on('click', '.valuations_menu_item ', function() {
		var valId = $(this).data('valid');
		$('.valuations_item._active, .valuations_menu_item._active').removeClass('_active');
		$('.valuations_item[data-valid="'+valId+'"]').addClass('_active');
		$(this).addClass('_active');
	});

// -------------------------------------------------------------------- ЦЕННОСТИ ------------------------------------------------------------------

// -------------------------------------------------------------------- ГАЛЕРЕЯ -------------------------------------------------------------------
	
	if($('.object_gallery').length > 0){
		var $fotoramaDiv = $('#fotorama').fotorama();
	    var fotorama = $fotoramaDiv.data('fotorama');
	    fotorama.setOptions({
			auto: false,
			allowfullscreen: "native",
			ratio: "16/9",
			width: "100%",
			fit: "contain",
			nav: "thumbs",
			thumbmargin: 5,
			thumbheight: 92,
			thumbwidth: 138,
			thumbborderwidth: 3,
		});

	    if(windowWidth > 1439){
			objectGalleryDesc();
		}
		else if(windowWidth > 767){
			objectGalleryPad();
		}
		else{
			objectGalleryMobile();
		}

	    function objectGalleryDesc(){
	    	fotorama.setOptions({
				ratio: "16/10",
				thumbheight: 92,
				thumbwidth: 138,
				thumbborderwidth: 3,
			});
	    };
	    function objectGalleryPad(){
	    	fotorama.setOptions({
	    		ratio: "16/10",
	    		thumbheight: 92,
				thumbwidth: 138,
				thumbborderwidth: 3,
	    	});
	    };
	    function objectGalleryMobile(){
	    	fotorama.setOptions({
	    		ratio: "16/10",
	    		thumbheight: 50,
				thumbwidth: 75,
				thumbborderwidth: 2,
	    	});
	    };
	}
	

// -------------------------------------------------------------------- ГАЛЕРЕЯ -------------------------------------------------------------------

// --------------------------------------------------------------------- КАРТА --------------------------------------------------------------------

	if ($('.object_map_wrapper').length > 0 || $('.contacts_map_wrapper').length > 0) {
		ymaps.ready(init);	
	}
    var myMap,
        myPlacemark,
        object;
    function init(){
        myMap = new ymaps.Map("map", {
            center: [$('#map').data('mapdotx'), $('#map').data('mapdoty')],
            controls: ["smallMapDefaultSet"],
            zoom: 16
        }),
        MyBalloonLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="popover top">' +
            '<a class="close" href="#">&times;</a>' +
            '<div class="arrow"></div>' +
            '<div class="popover-inner">' +
            '$[[options.contentLayout observeSize minWidth=100 maxWidth=320 maxHeight=350]]' +
            '</div>' +
            '</div>', {
            build: function () {
                this.constructor.superclass.build.call(this);

                this._$element = $('.popover', this.getParentElement());

                this.applyElementOffset();

                this._$element.find('.close')
                    .on('click', $.proxy(this.onCloseClick, this));
            },
            clear: function () {
                this._$element.find('.close')
                    .off('click');

                this.constructor.superclass.clear.call(this);
            },
            onSublayoutSizeChange: function () {
                MyBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);

                if(!this._isElement(this._$element)) {
                    return;
                }

                this.applyElementOffset();

                this.events.fire('shapechange');
            },
            applyElementOffset: function () {
                this._$element.css({
                    left: -(this._$element[0].offsetWidth / 2),
                    top: -(this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight - 10)
                });
            },
            onCloseClick: function (e) {
                e.preventDefault();

                this.events.fire('userclose');
            },
            getShape: function () {
                if(!this._isElement(this._$element)) {
                    return MyBalloonLayout.superclass.getShape.call(this);
                }

                var position = this._$element.position();

                return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
                    [position.left, position.top], [
                        position.left + this._$element[0].offsetWidth,
                        position.top + this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight,
                    ]
                ]));
            },
            _isElement: function (element) {
                return element && element[0] && element.find('.arrow')[0];
            }
        }),
        MyBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="popover-content">$[properties.balloonContent]</div>'
        );

        if(!$('#map').hasClass('dom')){
	        ymaps.geocode($('#map').data('address'), {
	        	results: 1
	    	}).then(function (res) {
	    		var firstGeoObject = res.geoObjects.get(0),
	    			coords = firstGeoObject.geometry.getCoordinates(),
	    			bounds = firstGeoObject.properties.get('boundedBy');
	    		object = new ymaps.Placemark(coords,
		        	{
			        	hintContent: $('#map').data('hint'),
			        	balloonContent: $('#map').data('balloon'),
		        	}
		        	,{
			            balloonShadow: false,
			            balloonLayout: MyBalloonLayout,
			            balloonContentLayout: MyBalloonContentLayout,
			            balloonPanelMaxMapArea: 0,
			            iconLayout: "default#image",
				        iconImageHref: "/assets/img/m_logo.svg",
				        iconImageSize: [47, 24],
				        iconImageOffset: [-23, -24],
			        },
				);
				myMap.geoObjects.add(object);
				myMap.setBounds(bounds, {
	                // Проверяем наличие тайлов на данном масштабе.
	                checkZoomRange: true
	            });
			});
		}
		else{
			object = new ymaps.Placemark([$('#map').data('mapdotx'), $('#map').data('mapdoty')],
	        	{
		        	hintContent: $('#map').data('hint'),
		        	balloonContent: $('#map').data('balloon'),
	        	}
	        	,{
		            balloonShadow: false,
		            balloonLayout: MyBalloonLayout,
		            balloonContentLayout: MyBalloonContentLayout,
		            balloonPanelMaxMapArea: 0,
		            iconLayout: "default#image",
			        iconImageHref: "/assets/img/m_logo.svg",
			        iconImageSize: [47, 24],
			        iconImageOffset: [-23, -24],
		        },
			);
			myMap.geoObjects.add(object);
		}        
    };

// --------------------------------------------------------------------- КАРТА --------------------------------------------------------------------

// --------------------------------------------------------------------- ТЕГИ ---------------------------------------------------------------------

	if($('.tag_wrapper').length > 0){
		var tagCar = '';
		if(windowWidth < 768){
			tagCarMobile();
		}		

		function tagCarMobile(){
			tagCar = new Swiper('.tag_wrapper', {
				loop: false,
				slidesPerView: 'auto',
				spaceBetween: 20,
				centeredSlides: false,
				autoHeight: true,
				freeMode: true,
			});
		};
	};

	$('body').on('click', '.tag ', function() {
		$(this).toggleClass('_active');
	});

// --------------------------------------------------------------------- ТЕГИ ---------------------------------------------------------------------

// -------------------------------------------------------------------- ФИЛЬТР --------------------------------------------------------------------

	class Filter{
		constructor(filterBlock){
			let _this = this;

			this.$filterBlock = $(filterBlock);
			this.filterState = {};

			this.$filterBlock.find('.filter_select_block').each(function(){
				let filterFlag = $(this).find('._nullItem').hasClass('_active');
				let blockType = $(this).data('type');
				if(!filterFlag){
					let type = $(this).data('type');
					_this.filterState[type] = '';
					$(this).find('.filter_select_item._active').each(function(){
						if(_this.filterState[type] == ''){
							_this.filterState[type] += $(this).data('value');
						}
						else{
							_this.filterState[type] += ','+$(this).data('value');
						}
					});					
				}
			});

			this.$filterBlock.find('.filter_check').each(function(){
				let blockType = $(this).data('type');
				if($(this).hasClass('_checked')){
					_this.filterState[blockType] = 1;
				}
			});

			this.$filterBlock.find('.filter_select_current').on('click', function(event){
				event.stopPropagation();
				if($(this).parent().hasClass('_active')){
					$(this).parent().removeClass('_active');
				}
				else{
					$('.filter_select_block._active').removeClass('_active');
					$(this).parent().addClass('_active');
				}
			});

			this.$filterBlock.find('.filter_select_item').on('click', function(event){
				let blockType = $(this).closest('[data-type]').data('type');
				$(this).toggleClass('_active');
				_this.selectClick($(this).closest('[data-type]'));
			});

			this.$filterBlock.find('.filter_check').on('click', function(event){
				let blockType = $(this).data('type');
				if($(this).hasClass('_checked')){
					$(this).removeClass('_checked');
					_this.filterState[blockType] = null;
				}
				else{
					$(this).addClass('_checked');
					_this.filterState[blockType] = 1;
				}
				
			});

			$(window).click(function() {
				//$('.filter_select_block._active').removeClass('_active');
			});

			$('.filter_submit_button').on('click', function(){
				let filterHref = '?';
				let filterFlag = false;
				for (var prop in _this.filterState) {
					if(_this.filterState[prop] != null){
						if(filterFlag) filterHref += '&';
						filterHref += prop + '=' + _this.filterState[prop];
						filterFlag = true;
					}
				}
				window.location.href = location.protocol + '//' + location.host + '/catalog/' + filterHref;
			});

			console.log(_this);
		}

		selectClick($block){
			let _this = this;
			let blockType = $block.data('type');
			_this.filterState[blockType] = '';
			$block.find('.filter_select_item._active').each(function(){
				if(_this.filterState[blockType] == ''){
					_this.filterState[blockType] += $(this).data('value');
				}
				else{
					_this.filterState[blockType] += ','+$(this).data('value');
				}
			});
		}
	}

	var filter = new Filter($('.filter'));

	/*$('.filter_select_current').on('click', function(event){
		event.stopPropagation();
		if($(this).parent().hasClass('_active')){
			$(this).parent().removeClass('_active');
		}
		else{
			$('.filter_select_block._active').removeClass('_active');
			$(this).parent().addClass('_active');
		}
	});

	$('.filter_select_item').on('click', function(){
		$(this).parent().find('.filter_select_item._active').removeClass('_active');
		$(this).addClass('_active');
		$(this).closest('.filter_select_block').removeClass('_active');
		console.log($(this).closest('.filter_select_current').find('p').text());
		$(this).closest('.filter_select_block').find('.filter_select_current p').text($(this).find('p').text());
	});

	$(window).click(function() {
		$('.filter_select_block._active').removeClass('_active');
	});

	$('.filter_check').on('click', function(event){
		$(this).toggleClass('_checked');
	});*/


// -------------------------------------------------------------------- ФИЛЬТР --------------------------------------------------------------------

// ------------------------------------------------------------------- ПАГИНАЦИЯ ------------------------------------------------------------------

	$('.items_pagination_item').on('click', function(){
		var url = window.location.href;
		var pagination = $(this).data('pagid');
		var data = { url: url, pagination: pagination, webp: webp};		
		console.log(data);
		if($(this).hasClass('_dom')){
			var url = '/ajax/doma/pagination/';
		}
		else{
			var url = '/ajax/kvartiry/pagination/';
		}
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function(response) {
				$response = $.parseJSON(response);
				console.log($response);
				$('.items_list').html($response.html);
				$('.items_pagination_item._active').removeClass('_active');
				$('.items_pagination_item[data-pagid='+pagination+']').addClass('_active');
				toTop = $('.items_list').offset().top - 270;
				$('html, body').animate({
					scrollTop: toTop
				}, 500);
				history.pushState({},'',$response.url);
				location.href;
			},
			error: function(response) {console.log(response)},
		});
	});

// ------------------------------------------------------------------- ПАГИНАЦИЯ ------------------------------------------------------------------

	autosize($('.callback_textarea textarea'));

	$('body').on('click', '.personalData_label', function() {
		var checkbox = $(this).closest('form').find('.personalData');
		if (checkbox.prop('checked') === true) {
			checkbox.prop('checked',false);
			$(this).closest('form').find('[type=submit]').prop('disabled',true).addClass('_inactive');
		} else {
			checkbox.prop('checked',true);
			$(this).closest('form').find('[type=submit]').prop('disabled',false).removeClass('_inactive');
		}
	});
	
});

$(document).ready(function() {

	$('.object_gallery_wrap img').on('click', function(){
		var img_iter = $(this).data('iter');
		var big_img = $('.object_gallery_main img[data-iter="'+img_iter+'"]');

		if(!big_img.length){
			var data = {'url' : $(this).attr('src'), 'id' : $('.object_gallery').data('id')};
			$.ajax({
				url: '/ajax/watermark/',
				type: 'post',
				data: data,
				success: function(response) {
					$('.object_gallery_main img._active').removeClass('_active');
					$('.object_gallery_main').append('<img data-iter="'+img_iter+'" src="'+response+'" class="_active" />');
				},
				error: function(response) {console.log(response)},
			});
		}
		else{
			$('.object_gallery_main img._active').removeClass('_active');
			big_img.addClass('_active');
		}
	});
});