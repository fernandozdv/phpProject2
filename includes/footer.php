</div>

<footer class="col-md-12 text-center" id="footer">&copy; Copyright 2017 Fernando Zuñe</footer>
<script type="text/javascript">
//Al realizar scroll en la pantalla, desplazamiento top pixeles.
  jQuery(window).scroll(function() {
    var vscroll=jQuery(this).scrollTop();
    jQuery('#logotext').css({
      "transform": "translate(0px, "+vscroll/2+"px)"
    })
    jQuery('#back-flower').css({
      "transform": "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
    })
    jQuery('#fore-flower').css({
      "transform": "translate(0px, -"+vscroll/2+"px)"
    })
  });
</script>

</body>
</html>
