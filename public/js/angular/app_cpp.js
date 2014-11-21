//controladores
angular.module('Controllers', [])
        .controller('envioController', function($scope, $http, $interval, ajax) {
          $scope.envios = [];
          $scope.notificaciones = [];
          $scope.n = 0;


          /*
           $scope.get_envios = function() {
           interval = $interval(function() {
           
           
           $http.get(base_url + '/envio/json').success(function(data) {
           $scope.envios = data;
           });
           }, 3000);
           };*/

          $scope.getClass = function(data) {
            if (data.respuesta == 'accepted')
              return 'success';
            else if (data.respuesta == 'wrong answer')
              return 'warning';
            else if (data.respuesta == 'compilation error')
              return 'info';
            else
              return '';
          };

        }).controller('InicioController', function($scope, ajax) {
            $scope.notificaciones = [];
            $scope.comentarios = []; //guarda la lista de comentarios de cada notifcacion
            $scope.comentario = []; //guarda lo que la persona va escribiendo en el textarea
            $scope.comments_visible = []; //muesta si se ha escrito al menos un comentario
            $scope.nlikes = []; //guarda el numero de likes de cada notificaicon
            min_notificaciones = 5;
            $scope.limit_notificaciones = min_notificaciones;
            step_notificaciones = 5;
            $scope.boton_mas = true;
            $scope.loading=false;
            
              ajax.post(base_url + '/curso/json/notificaciones', {curso:curso_actual}, function(data) {
                $scope.notificaciones = data;
              });
              
              $scope.menos_notificaciones= function(){
                    $scope.limit_notificaciones = Math.max($scope.limit_notificaciones-step_notificaciones, min_notificaciones);
              };
              $scope.mas_notificaciones= function(){
                $scope.loading = true;
                $scope.limit_notificaciones = Math.min($scope.limit_notificaciones+step_notificaciones, $scope.notificaciones.length)
                $scope.loading = false;
              };
              $scope.get_comentarios = function(notificacion){
                  ajax.post(base_url + '/notificacion/json/comentarios',{notificacion:notificacion}, function(data){
                     $scope.comentarios[notificacion] = data;
                     $scope.comments_visible[notificacion] = data.length>0;
                     
                  });
              };
              $scope.comentar = function(notif){
                notif= notif[0][0]
                data = {notificacion:notif, comentario:$scope.comentario[notif]};
               ajax.post(base_url+'/notificacion/comentar',data, function(data2){
                    $scope.get_comentarios(notif);
                    $scope.comentario[notif] = '';
                     alertify.log('HAS COMENTADO ESTO', "success", 4000);
               });
              },
              $scope.eliminar_comentario = function(comentario, notif){
                comentario=comentario[0][0]; notif=notif[0][0];
                data = {comentario:comentario};
               ajax.post(base_url+'/notificacion/eliminar-comentario',data, function(data2){
                    $scope.get_comentarios(notif);
                    alertify.log("COMENTARIO ELIMINADO", "success", 4000);
               });
              },
              $scope.me_gusta = function(notificacion){
                  notifi = notificacion[0][0];
                data = {notificacion:notifi,usuario: $scope.usuario_logueado};
               ajax.post(base_url+'/notificacion/me-gusta',data, function(data2){
                    $scope.numero_likes(notificacion);
                    if(data2.tipo==1)
                    alertify.log("Te ha gustado esta publicación", "success", 4000);
                     else
                       alertify.log("Ya no te gusta esta publicación", "success", 4000);
               });     
                        
              },
              $scope.eliminar_notificacion = function(notificacion){
                
              },
              $scope.ver_likes = function(notificacion){
                
              },
              $scope.numero_likes = function(notificacion){
                notificacion = notificacion[0][0];
                data = {notificacion:notificacion};
                ajax.post(base_url+'/notificacion/json/nlikes', data, function(data2){
                  $scope.nlikes[notificacion] = data2;
                })
              }
        }).controller('MonitorearTaller', function($scope, ajax){
            $scope.estudiantes=[];
            
            ajax.post(base_url+'/curso/json/monitorear_taller', {taller: taller_actual}, function(data){
              $scope.estudiantes = data;
              window.console.log(data);
            })
});


//todas las clases

angular.module('Classes', [])
        .service('ajax', function($http) {
          this.get = function($url, $data, $function) {
            this.ajax('get', $url, $data, $function);
          },
                  this.post = function($url, $data, $function) {
                    this.ajax('post', $url, $data, $function);
                  },
                  this.ajax = function($type, $url, $dataf, $function) {
                    $.ajax({
                      type: $type,
                      url: $url,
                      data: $dataf,
                      async: false,
                      success: function(data) {
                        $function(data);
                      }
                    });
                  }
        });



var cpp = angular.module('CPP', ['Controllers', 'Classes'], function($interpolateProvider) {
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

