var larg = (document.body.clientWidth);

if (larg >= 1200 ) {
   $("footer").addClass("fixed");
}
$("#BoutonBas").addClass("BoutonFix");

$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll == 0) {
       if (larg >= 480) {
          $("footer").addClass("fixed");
          $("footer").removeClass("relativ");
          $("#BoutonBas").addClass("BoutonFix");
          $("#BoutonBas").removeClass("relative");
       }
    $("nav").addClass("relativ");
    $("nav").removeClass("fixedTop");
    }
    else {
    if (larg >= 480 ) {
       $("footer").removeClass("fixed");
       $("footer").addClass("relativ");
       $("#BoutonBas").removeClass("BoutonFix");
       $("#BoutonBas").addClass("relative");
    }
    $("nav").removeClass("relativ");
    $("nav").addClass("fixedTop");
    }
});