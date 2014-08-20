 $(document).ready(function() {
lms = {
    mensaje : {
       desvanecer : function(){
            $(".fadeOut").fadeOut(4000);
       }
    },
    confirmar : function(){
         return confirm('¿Está seguro de realizar esta acción?');
    },
    
    limpiar_formulario : function(form){
        
        
    }
    
}

lms.mensaje.desvanecer();



// tooltips

Tipped.create('.ejercicio',{ajax : true , skin : 'white', hook: {
  target:  'topright',
  tooltip: 'bottomleft'
}, offset: {x:20}});

    
});
    