
<ul  class="breadcrumb">
  
    {for $i=1 to count($path)}
            {if $i == count($path)}               
                 <li class="active">{$path[$i]['nombre']}</li>
            {else}            
                 <a href="{$path[$i]['enlace']}">{$path[$i]['nombre']}</a> 
                 <span class="divider">/</span>
            {/if}
    {/for}
  
</ul>