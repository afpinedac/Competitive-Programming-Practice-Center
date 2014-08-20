





<script type="text/javascript" src="https://www.google.com/jsapi"></script>  
  <script type="text/javascript">
      
    google.load('visualization', '1', {ldelim}packages: ['annotatedtimeline']{rdelim});
    function drawVisualization() {
        
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Date');
      data.addColumn('number', 'Envíos');
      data.addColumn('string', 'title1');
      data.addColumn('string', 'text1');
      data.addColumn('number', null);
      data.addColumn('string', 'title2');
      data.addColumn('string', 'text2');
      data.addRows([
          
            {foreach $envios as $envio}                        
                  [new Date('{$envio->dia}'), {$envio->nenvios}, null, null, 0, null, null],
            {/foreach}
          
        
        //[new Date(2008, 1 ,2), 4, null, null, 0, null, null],
        //[new Date(2008, 1 ,3), 3, null, null, 0, null, null],
        //[new Date(2008, 1 ,4), 0, null, null, 0, null , null],
        //[new Date(2008, 1 ,5), 4, 'Bought Pens', 'Bought 200k pens', 0, null, null],
        //[new Date(2008, 1 ,6), 8, null, null, 0, null, null]
      ]);
    
    
     var options = {ldelim}'title':'{$title}',
                       'width':{$width|default:500},
                       'is3D':true,
                       'height':{$height|default:400},
                       'displayAnnotations':true{rdelim};
    
      var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
          document.getElementById('timeline'));
      annotatedtimeline.draw(data, options);
    }
    
    google.setOnLoadCallback(drawVisualization);
  </script>




