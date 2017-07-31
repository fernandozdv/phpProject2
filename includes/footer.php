</div>

<footer class="col-md-12 text-center" id="footer">&copy; Copyright 2017 Fernando Zuñe</footer>
<script type="text/javascript">
//Al realizar scroll en la pantalla, desplazamiento top pixeles.
  jQuery(window).scroll(function() {
    var vscroll=jQuery(this).scrollTop();
    //Desplazamiento para el logo
    jQuery('#logotext').css({
      //0 horizontal y la mitad del desplazamiento verdadero
      "transform": "translate(0px, "+vscroll/2+"px)"
    })
    //Desplazamiento para flor morada
    jQuery('#back-flower').css({
      //quinta parte del desplazamiento en horizontal y 12va en vertical
      "transform": "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
    })
    //Desplazamiento para flor rosada
    jQuery('#fore-flower').css({
      //0 del desplazamiento en horizontal y mitad en vertical
      "transform": "translate(0px, -"+vscroll/2+"px)"
    })
  });

//Funcion utiliza ajax para redireccionar el id en pagina principal a detailsmodal.php
  function detailsmodal(id)
  {
    //Data json id
    var data ={"id" :id};
    jQuery.ajax({
      //Ruta a redireccionar
      url:"includes/detailsmodal.php",
      //Método
      method:"post",
      //Información a enviar
      data: data,
      success: function(data){
        //Une el form al cuerpo total
        jQuery('body').append(data);
        //Modal
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
