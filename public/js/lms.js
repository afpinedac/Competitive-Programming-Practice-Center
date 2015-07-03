$(document).ready(function () {
  lms = {
    mensaje: {
      desvanecer: function () {
        $(".fadeOut").fadeOut(4000);
      }
    },
    confirmar: function () {
      return confirm('¿Está seguro de realizar esta acción?');
    },
    validarEnvio: function(){
      return true;
      
      var algoritmo = $("#algoritmo").val();
      
      //if(algoritmo.co)
      
        
      
      //var invalidStrings = ['import java.web'];
      //todo
      
      
      
      
      
    }
    
    
  }

  lms.mensaje.desvanecer();

// tooltips

  Tipped.create('.ejercicio', {ajax: true, skin: 'white', hook: {
      target: 'topright',
      tooltip: 'bottomleft'
    }, offset: {x: 20}});


});
    