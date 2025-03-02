jQuery(document).ready(function ($) {

  // Find <nav>-elementet med klassen "custom-breadcrumb"
  const breadcrumbNav = document.querySelector('nav.custom-breadcrumb');

  if (breadcrumbNav && breadcrumbNav.nextSibling) {
    // Tjek om det næste søskendeelement er en tekstnode
    if (breadcrumbNav.nextSibling.nodeType === 3) {
      breadcrumbNav.nextSibling.remove(); // Fjern teksten
    }
  }

  // *********************************************************
  // Slide down submenu when arrow down is clicked in span-tag
  // This way, you can have an active link beside the arrow 
  // *********************************************************
  var menuchild = $('li.menu-item-has-children');

  menuchild.click(function (e) {
    if (e.offsetX > 264) {
      e.preventDefault();
      var submenu = $('ul.sub-menu', this);
      submenu.toggleClass('open');
      console.log('helo: ' + e.offsetX);
    }
  });

  /* define menu icon */
  var nav_icon = $('#nav-icon4');

  /* Close menu when clicking on body */
  $('.site-main').click(function () {
    if (nav_icon.hasClass('open')) {
      $('.menu-overlay').toggleClass('open');
      nav_icon.toggleClass('open');
      $('#menu-hovedmenu').toggleClass('open');
      $('#menu-topmenu').toggleClass('open');
    }
  });

  /* Open-close menu when clicking menu (hamburger) button */
  nav_icon.click(function () {
    $('.menu-overlay').toggleClass('open');
    nav_icon.toggleClass('open');
    $('#menu-hovedmenu').toggleClass('open');
    $('#menu-topmenu').toggleClass('open');
  });

  /* Auto-close menu after clicking menu item */
  $('#menu-hovedmenu li a').click(function () {
    if (nav_icon.hasClass('open')) {
      $('.menu-overlay').toggleClass('open');
      nav_icon.toggleClass('open');
      $('#menu-hovedmenu').toggleClass('open');
      $('#menu-topmenu').toggleClass('open');
    }
  });

  /* Scroll-up button */
  var btn = $('#scroll-button');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 20) {
      btn.show(300);
    } else {
      btn.hide(300);
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '500');
  });


  /* Se mere knap */
  $('.se-mere-knap').click(function (e) {
    e.preventDefault();
    $('article [data-article-id=id]').animate({ height: 450 });
    $('.nyhedsuddrag [data-uddrag-id=id]').show(100);
  });

  /**
   * 
   * Vis Filtre på mobil
   * 
   */

  jQuery(document).ready(function ($) {
    const filterToggle = $('.custom-filters-toggle');
    const filterContent = $('.custom-filters');

    filterToggle.on('click', function () {
      filterContent.toggle();
    });
  });


  /**
   * 
   * Simple Woo Product Gallery
   * 
   */
  $('.gallery-images a').click(function (e) {
    e.preventDefault();
    var src = $(this).find('img').data('full');
    $('.main-image img').removeAttr('srcset').attr('src', src);
  });

  /**
   * 
   * Dynamisk prisberegning baseret på parrede egenskaber
   * 
   */

  let ProductData = {
    weight: null,
  };

  /**
   * 
   * Hvis produktet er en variant skal vægten hentes under hver variant og ikke som en egenskab / attribut
   * 
   */

  $('form.variations_form').on('found_variation', function (_event, variation) {
    if (variation && variation.weight) {
      // Gem vægten i det lokale objekt
      ProductData.weight = variation.weight;
      $('#total_weight').text(ProductData.weight);
      calculatePrice();
    }
  });
	
	/**
	 * 
	 * Skjul variation hvis der kun er en
	 * 
	 */ 

	var variations = $(".variations_form").data("product_variations");
	if (variations &&  variations.length === 1) {
		$(".variations_form .variations").hide(); // Skjul dropdown
		$(".single_variation_wrap").show(); // Vis prisen og "Tilføj til kurv"
	}

  /**
   * 
   * Beregn pris udfra vægt, kg pris og længde eller længde x bredde 
   * Dimensioner kan også være flexible
   *  
   */

  $('.custom-price-fields input[type="number"], .custom-price-fields input[type="radio"]').on("input", function () { calculatePrice(); });
  $('form.cart .variations select').on("change", function () {
    $('#input_length').attr({
      "max": parseFloat($(this).val())
    });
    calculatePrice();
  });

  /**
   * 
   * Fejl håndtering af flexible mængder
   * 
   */

  $('input[type="number"]').on('input', function () {
    var max = $(this).attr('max'); // Get the max attribute value
    var value = parseFloat($(this).val()); // Get the current value entered by the user

    if (value > max) {
      $(this).val(max); // Reset the input to the max value if it exceeds
      calculatePrice();

      var position = $(this).position(); // Get the position of $(this)

      // Position the .box under $(this)
      $('.input-error-tipbox.kun-laengde').css({
        left: position.left,        // Align left with $(this)
        display: 'inline-flex'          // Show the .box
      });
    }
  });


  function calculatePrice() {
    // Tjek om attributterne findes før beregning ved hjælp af jQuery
    var fulllength = 1;
    if ($('#pa_laengde').length) {
      fulllength = parseInt($('#pa_laengde').find(":selected").val()) / 1000; // Valgte længde af det variable produkt
    } else if ($('#attr-pa_laengde').length) {
      fulllength = parseInt($('#attr-pa_laengde').data('pa_laengde')) / 1000; // Længde af produktet
    }

    var fullheight = 1;
    if ($('#pa_hojde').length) {
      fullheight = parseInt($('#pa_hojde').find(":selected").val()) / 1000; // Valgte højde af det variable produkt
    } else if ($('#attr-pa_hojde').length) {
      fullheight = parseInt($('#attr-pa_hojde').data('pa_hojde')) / 1000; // Højde af produktet
    }

    var weight = parseFloat(ProductData.weight);
    if (weight == 0 || Number.isNaN(weight)) {
      if ($('#attr-pa_vaegt').length) {
        weight = parseFloat($('#attr-pa_vaegt').data('pa_vaegt'));
        if ($('input[type="radio"][value="input_length_height"]').length > 0) {
          $('#total_weight').text(Math.ceil(((fulllength * fullheight)) * weight)); // Beregn vægt i kg for hele arealet
        } else {
          $('#total_weight').text(Math.ceil((fulllength) * weight)); // Beregn vægt i kg for hele længden
        }
      } else if ($('#attr-pa_samlet-vaegt').length) {
        weight = parseFloat($('#attr-pa_samlet-vaegt').data('pa_samletVaegt'));
        $('#total_weight').text(Math.ceil(weight)); // Samlet vægt
      }
    } else {
      if (fullheight == 0) {
        $('#total_weight').text(Math.ceil((fulllength) * weight)); // Beregn vægt i kg for hele længden 
      } else {
        $('#total_weight').text(Math.ceil(((fulllength * fullheight)) * weight)); // Beregn vægt i kg for hele arealet
      }
    }

    // Tjek for pris pr. kg
    var pricePerKg = 0;
    if ($('.single-product-attributes').data('price')) {
      pricePerKg = parseFloat($('.single-product-attributes').data('price')); // Pris pr. kg
    }

    // Hent længde og bredde inputfelter med jQuery
    var length = $('#input_length').length ? parseFloat($('#input_length').val()) / 1000 : 0;
    var width = $('#input_width').length ? parseFloat($('#input_width').val()) / 1000 : 0;

    // Hent valgt prisindstilling (radio buttons)
    var priceOption = $('input[name="price_option"]:checked').val();

    // Beregn pris baseret på valg
    var quantity = 0;
    var totalPrice = 0;
    var totalWeight = 0;
    var totalPricePerItem = 0;
    if (priceOption === 'full_area_price') {
      quantity = $('#full_area_price_pieces').length ? parseInt($('#full_area_price_pieces').val()) : 1;
      totalWeight = fulllength * weight; // Beregn totalvægt for hele hele længden
    } else if (priceOption === 'input_length') {
      quantity = $('#input_length_pieces').length ? parseInt($('#input_length_pieces').val()) : 1;
      totalWeight = length * weight; // Beregn totalvægt for angivet længde
    } else if (priceOption === 'input_length_height') {
      quantity = $('#input_length_height_pieces').length ? parseInt($('#input_length_height_pieces').val()) : 1;
      var area = length * width;
      totalWeight = area * weight; // Beregn totalvægt for hele arealet
    }

    totalPricePerItem = totalWeight * pricePerKg; // Pris pr. stykke
    totalPrice = quantity * totalPricePerItem; // Samlet pris

    // Vis samlet pris
    $('#total_price').text(totalPrice.toFixed(2) + ' kr.');
    $('#custom_price').val(totalPricePerItem);
    $('#custom_qty').val(quantity);
  }

  calculatePrice(); // Kør første gang

});