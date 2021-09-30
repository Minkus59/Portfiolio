      <FOOTER>
        <div class="container">
            <div id="BoutonBas">
            </div>

          <div class="row justify-content-end">                      
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
              <a href='<?php echo $SelectSocial[0]->lien; ?>' target="_blank"><img src="<?php echo $SelectSocial[0]->logo; ?>" alt="lien reseau linkedin"/></a>
            </div>
          </div>
        </div>
      </FOOTER>


    <?php
    if (!isset($_SESSION['cookie']) || $_SESSION['cookie']==1) {
      // visiteur accepte les cookies
      ?>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-R359T1R5TJ"></script>
      <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-R359T1R5TJ');
      </script>
      <?php
    }
    ?>

    <!--[if !IE]><!-->
    <script type="text/javascript" charset="UTF-8" src="https://code.jquery.com/jquery-3.6.0.min.js" defer="defer"></script>
    <!--<![endif]-->
    <!--[if lte IE 8]>
    <script type="text/javascript" charset="UTF-8" src="https://code.jquery.com/jquery-3.6.0.min.js" defer="defer"></script>
    <![endif]-->
    <!--[if gt IE 8]>
    <script type="text/javascript" charset="UTF-8" src="https://code.jquery.com/jquery-3.6.0.min.js" defer="defer"></script>
    <![endif]-->

    <script type="text/javascript" charset="UTF-8" src="<?php HOME ?>/frontend/public/js/menu.js" defer="defer"></script>
    <script type="text/javascript" charset="UTF-8" src="<?php HOME ?>/frontend/public/js/pageTop.js" defer="defer"></script>
    <script type="text/javascript" charset="UTF-8" src="<?php HOME ?>/frontend/public/js/skills.js" defer="defer"></script>
  </body>
</html>
