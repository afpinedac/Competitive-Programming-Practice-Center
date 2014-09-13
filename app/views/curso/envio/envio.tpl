{capture assign='content'}


    
    
   
    <div class="row-fluid">
        <div class="span12">
            <h2>Envíos</h2>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span12">
                
            
            
            <table class="table table-striped">
                <thead>
                <th>ID</th>
                <th>Problema</th>
                <th>Usuario</th>
                <th>Veredicto</th>
                <th>Lenguaje</th>
                <th>Tiempo</th>
                <th>Fecha</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>    
            </table>    
            
        </div>
    </div>
    
    
    
    
    
    
    
    <script>
        
 
        
        last_update = Date.now();
        envio = function(){
         
          
              $.ajax({
                    dataType: "json",
                        type: 'post',
                        url: "{url('envio/all')}",
                        async : false,
                
                data: {                    
                   time : last_update                    
                },
                success: function(data) {                                        
                        
                        $.each(data,function(idx,value){
                                 //   console.log(value);
                            });
                            
                            last_update = Date.now()/1000;
                }
              });
              
              
              
              setTimeout(envio,3000);              
              
        }
        last_update = Date.now()/1000;
  envio();
        
        
    </script>
    
   
    
    
  
    
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='envios'}
