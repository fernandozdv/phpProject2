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

  function detailsmodal(id)
  {
    var data ={"id" :id};
    jQuery.ajax({
      url:"includes/detailsmodal.php",
      method:"post",
      data: data,
      success: function(data){
        jQuery('body').append(data);
        jQuery('#details-modal').modal('toggle');
      },
      error: function(){
        alert("error");
      }
    });
  }
</script>

</body>
</html>
