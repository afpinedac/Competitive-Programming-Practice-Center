{capture assign='content'}


  {include file='./_components/header_login.tpl'}


  <div class="row-fluid">
    <div class="span12">               

      <div class="row-fluid">
        <div class="span12">
          <center><h1>Enseña / Aprende / Practíca Programación Competitiva</h1></center>
          <center style='margin-top: -15px'><h3 >de una manera divertida y social</h3></center>
        </div>
      </div>
      <br>

      <div class="row-fluid">
        <div class="span12">
          <div class="span10 offset1">


            <div class="span6">


              <iframe width="500" height="315" src="//www.youtube.com/embed/{$videorand}" frameborder="0" allowfullscreen></iframe>

            </div>

            <div class="span6">

              <br>
              <br>
              <br>
              <p class='texto-inicial' class='' style='text-align: justify'>
              <p class='texto-inicial' class='' style='text-align: justify'>
                <strong>  CPP Es un sitio ideal para los profesores e Instituciones Educativas que desean contar
                  con una plataforma Virtual de Aprendizaje para la enseñanza y práctica de Programación Competitiva.</strong>
              </p>
              <br>
              <br>
              <p class='texto-inicial'>¿ Deseas saber más ? </p>

              <p><center><a href='{url('/about')}' class='btn btn-info'>Entra aquí</a>  ó <a href='{url('/registrar')}' class='btn btn-success'>Regístrate</a></center></p>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Rest of content here -->
</div>
</div>


<style>
  body{
    background-image: url('img/backgrounds/{$back}.jpg');
    background-repeat: no-repeat;
    background-size: cover;

  }

</style>



{/capture}   


{include file='_templates/template.tpl' layout=''}
