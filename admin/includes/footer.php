</div>
<footer class="col-md-12 text-center" id="footer">&copy; Copyright 2017 Fernando Zuñe</footer>

<script type="text/javascript">
  function updateSizes()
  {
    var sizeString='';
    for(var i=1;i<=12;i++)
    {
      if(jQuery('#size'+i).val()!='')
      {
        var temp=jQuery('#qty'+i).val();
        sizeString+=','+jQuery('#size'+i).val()+':'+((temp)?temp:'0');
      }
    }
    sizeString=sizeString.replace(",","");
    jQuery('#sizes').val(sizeString);
  }
  function get_child_options(selected)
  {
    var parentID=jQuery("#parent").val();
    jQuery.ajax({
      url:'/tutorial/phpProject2/admin/parsers/child_categories.php',
      type: 'POST',
      data: {parentID:parentID,selected:selected},
      success: function(data){
        //Escribe dentro de la etiqueta #child que es el select
        jQuery('#child').html(data);
      },
      error: function(){alert("Problemas al obtener hijos,ajax.")},
    })
  }
  //Seleccionar select de nombre parent para obtener hijos
  //Change-> Cuando se seleccione una opción en el select saltará esta función
  jQuery('select[name="parent"]').change(function(){
    get_child_options();
  });

</script>
</body>
</html>
