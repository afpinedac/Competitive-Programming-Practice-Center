

//FUNCIONES UTILES
util =  {
    ajax : function(url, data1){
        var result;
        $.ajax({
            dataType: "json",
            type: 'post',
            url: url,
            data: data1,            
            success: function(data) {
               result = data;
               return result;
            }
        });
        
        return result;
    }     
}

// NODO
   nodo = {        
         crear : function(posx,posy){
            node = this.get_copy();
            node.css({"top": posy , "left": posx});            
             $("body").append(node);
              
         },
         eliminar : function(){
     
         },
        get_copy : function(){
              clon =  $("#clon");
               clon.id = "hola";
              
               return clon;
        },
     };
         
     
 //GRAFO   
  grafo = {
    nodos : 0,
    selected : null,
    crear_nodo: function(posx,posy){
          nodo.crear(posx,posy);
          this.nodos++;
    },
    
        
    
 };






$("body").dblclick(function(e){
    grafo.crear_nodo(e.pageX,e.pageY);
    $("body").trigger('click');
});



