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
  /*Cuando se da click en añadir al carrito*/
  function add_to_cart()
  {
    jQuery("#modal_errors").html("");
    var size= jQuery("#size").val();
    var quantity= jQuery("#quantity").val();
    var available= jQuery("#available").val();
    var error='';
    //Serializa los campos del formulario en notación URL para Ajax
    var data=jQuery("#add_product_form").serialize();
    if(size==''||quantity==''||quantity==0)
    {
      error+='<p class="text-danger text-center">Debes elegir un tamaño y la cantidad a comprar</p>';
      jQuery("#modal_errors").html(error);
      return;
    }else if(quantity>available){
      error+='<p class="text-danger text-center">La cantidad que desea comprar excede la cantidad disponible</p>';
      jQuery("#modal_errors").html(error);
      return;
    }else{
      //proceder a realizar la petición
      jQuery.ajax({
        url: '/tutorial/phpProject2/admin/parsers/add_cart.php',
        method:'post',
        data:data,
        success:function(){
          location.reload();
        },
        error:function(){alert("Error ajax,carrito");}
      })
    }
  }
</script>

</body>
</html>
