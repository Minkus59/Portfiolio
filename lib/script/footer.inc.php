<FOOTER>
<div class="Center">
<div id="BoutonBas">

</div>

<div class="Cadre">
<?php 
    $SelectSocial=$cnx->prepare("SELECT * FROM ".$Prefix."_Social WHERE statue='1' ORDER BY id ASC");
    $SelectSocial->bindParam(':parrin', $PageFooter->lien, PDO::PARAM_STR);
    $SelectSocial->execute();
    
    while ($LienSocial=$SelectSocial->fetch(PDO::FETCH_OBJ)) { ?>
        <a href='<?php echo $LienSocial->lien; ?>' target="_blank"><img src="<?php echo $LienSocial->logo; ?>"/></a>
<?php } ?>
</div>


<!--[if !IE]><!-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8]>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<![endif]-->
<!--[if gt IE 8]>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<![endif]-->

<script type="text/javascript" language="javascript">
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
</script>

 <script type="text/javascript" language="javascript">
$('a[href^="#"]').click(function(){
  var the_id = $(this).attr("href");

  $('html, body').animate({
    scrollTop:$(the_id).offset().top
  }, 'slow');
  return false;
});
</script>

<script type="text/javascript">
jQuery( document ).ready(function( jQuery ) {
    if (/(iphone|ipod|ipad|android|iemobile|webos|fennec|blackberry|kindle|series60|playbook|opera\smini|opera\smobi|opera\stablet|symbianos|palmsource|palmos|blazer|windows\sce|windows\sphone|wp7|bolt|doris|dorothy|gobrowser|iris|maemo|minimo|netfront|semc-browser|skyfire|teashark|teleca|uzardweb|avantgo|docomo|kddi|ddipocket|polaris|eudoraweb|opwv|plink|plucker|pie|xiino|benq|playbook|bb|cricket|dell|bb10|nintendo|up.browser|playstation|tear|mib|obigo|midp|mobile|tablet)/.test(navigator.userAgent.toLowerCase())) {
        // add event listener on resize event (for orientation change)
        jQuery(window).resize(function() {
            readDeviceOrientation();
        });
        // initial execution
        readDeviceOrientation();
    }
});
</script>

<script type="text/javascript">
$(document).ready(function() {
  $(document).scroll(function(event) {
    var topPos = $(this).scrollTop() + 500;
    var windowHeight = $(this).height();
    var docHeight = $(document).height();

    if (topPos >= $('.progress').position().top) {
      $('.progress >.progress-bar').animate({
          'max-width': '100%'
        }, 3000)
        .attr('aria-valuenow', 100)
        .find('span.title').text('100%');
    } else if (topPos < $('.progress').position().top) {
      $('.progress >.progress-bar').css({
          'max-width': '0%'
        })
        .attr('aria-valuenow', 0)
        .find('span.title').text('0%');
    }
  });
})
</script>

</FOOTER>

</CENTER>
</body>

</html>
