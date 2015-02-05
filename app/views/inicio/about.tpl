{capture assign='content'}
    
    {include file='./_components/header_login.tpl'}
    
    <div class="row-fluid">
        <div class="span12">
                
            
            <div class="row-fluid">
                <div class="span12">
                    <center><h1><a href="{url('/')}">CPP - Centro de Práctica de Programación</a></h1></center>
                </div>
            </div>
            <br/>
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="span10 offset1">
                        
                        
             <div id="myCarousel" class="carousel slide">

                            <!-- Carousel items -->
                            <div class="carousel-inner">
                                <div class="active item"><img src='{url('img/about/1.png')}'></div>
                                <div class="item"><img src='{url('img/about/2.png')}'></div>
                                <div class="item"><img src='{url('img/about/3.png')}'></div>
                                <div class="item"><img src='{url('img/about/4.png')}'></div>
                                <div class="item"><img src='{url('img/about/5.png')}'></div>
                                <div class="item"><img src='{url('img/about/6.png')}'></div>
                            </div>
                            <!-- Carousel nav -->
                            <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                            <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                        </div>  
                        
                        

                    </div>
                </div>
            </div>
                       
        </div>
    </div>
                        
           
    </div>
</div>
   
    
{/capture}   


{include file='_templates/template.tpl' layout=''}
