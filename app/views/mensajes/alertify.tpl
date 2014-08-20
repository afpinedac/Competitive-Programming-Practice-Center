  {if Session::has('valid')}  
      
              <script> 
                  alertify.log("{Session::get('valid')|upper}", "success",4000);  
              </script>
              {Session::forget('valid')}
              {else if Session::has('invalid')}
                  <script>
                      alertify.log("{Session::get('invalid')|upper}", "error",4000);                                   
                  </script>
                  {Session::forget('invalid')}
                
     {/if} 
  {if Session::has('valid2')}  
      
              <script> 
                  alertify.log("{Session::get('valid2')|upper}", "success",4000);  
              </script>
              {Session::forget('valid2')}
              {else if Session::has('invalid2')}
                  <script>
                      alertify.log("{Session::get('invalid2')|upper}", "error",4000);                                   
                  </script>
                  {Session::forget('invalid2')}
                
     {/if} 