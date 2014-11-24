//controladores
angular.module('Controllers', [])
        .controller('EnvioController', function($scope, $interval, ajax) {
          $scope.submission = null;
          $scope.watch_submission = function(id) {
            alert(id);
          };
        }).directive('judgeonline', function($interval, ajax) {
  return {
    restrict: 'EA',
    template: "<div ><small class='pull-right' ng-click='watch_submission(1)' style='cursor: pointer; padding-right:5px;' ng-show='ready' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'>x</small><p><strong>Envio:</strong> [[envio]]</p><p style='margin-top:-5px;'><strong>Estatus:</strong> <span style='font-size:20px;'>[[status]]</span></p> </div>",
    scope: {
      envio: "&envio"
    },
    link: function($scope, $attr, $element) {
      $scope.envio= $element.envio;
      $scope.ready = false;
      $scope.status = 'En cola';
      interval = $interval(function() {
        ajax.post(base_url + '/ejercicio/aceptar/0', {envio: $scope.envio}, function(data) {
          if (data) {
            if(data.resultado!=''){
            $scope.status = data.resultado;
            $scope.ready = true;
            }
          }
        });
      }, 800);

      $scope.watch_submission = function() {
        ajax.post(base_url + '/ejercicio/aceptar', {envio: $scope.envio}, function(data) {
          delay = 3000;
          window.console.log(data);
          if (data) {
            if (data.resultado == 'accepted') {
              alertify.success('ACEPTADO');
              alertify.success("Has obtenido " + data.puntos_obtenidos + " puntos", "", delay);
            } else if (data.resultado == 'wrong answer') {
              alertify.log('Respuesta Incorrecta', "error", delay);
            } else if (data.resultado == 'time limit') {
              alertify.log('Tiempo limite excedido', "error", delay);
            } else if (data.resultado == 'compilation error') {
              alertify.log('Error de compilación', "error", delay);
            }
          } else {
            alertify.log('Ha ocurrido un error');
          }

        });
      };

   
    }
  }
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
  $scope.loading = false;
  

  ajax.post(base_url + '/curso/json/notificaciones', {curso: curso_actual}, function(data) {
    $scope.notificaciones = data;
  });

  $scope.menos_notificaciones = function() {
    $scope.limit_notificaciones = Math.max($scope.limit_notificaciones - step_notificaciones, min_notificaciones);
  };
  $scope.mas_notificaciones = function() {
    $scope.limit_notificaciones = Math.min($scope.limit_notificaciones + step_notificaciones, $scope.notificaciones.length)
   
  };
  $scope.get_comentarios = function(notificacion) {
    ajax.post(base_url + '/notificacion/json/comentarios', {notificacion: notificacion}, function(data) {
      $scope.comentarios[notificacion] = data;
      $scope.comments_visible[notificacion] = data.length > 0;

    });
  };
  $scope.comentar = function(notif) {
    notif = notif[0][0]
    data = {notificacion: notif, comentario: $scope.comentario[notif]};
    ajax.post(base_url + '/notificacion/comentar', data, function(data2) {
      $scope.get_comentarios(notif);
      $scope.comentario[notif] = '';
      alertify.log('HAS COMENTADO ESTO', "success", 4000);
    });
  },
          $scope.eliminar_comentario = function(comentario, notif) {
            comentario = comentario[0][0];
            notif = notif[0][0];
            data = {comentario: comentario};
            ajax.post(base_url + '/notificacion/eliminar-comentario', data, function(data2) {
              $scope.get_comentarios(notif);
              alertify.log("COMENTARIO ELIMINADO", "success", 4000);
            });
          },
          $scope.me_gusta = function(notificacion) {
            notifi = notificacion[0][0];
            data = {notificacion: notifi, usuario: $scope.usuario_logueado};
            ajax.post(base_url + '/notificacion/me-gusta', data, function(data2) {
              $scope.numero_likes(notificacion);
              if (data2.tipo == 1)
                alertify.log("Te ha gustado esta publicación", "success", 4000);
              else
                alertify.log("Ya no te gusta esta publicación", "success", 4000);
            });

          },
          $scope.eliminar_notificacion = function(notificacion) {

          },
          $scope.ver_likes = function(notificacion) {

          },
          $scope.numero_likes = function(notificacion) {
            notificacion = notificacion[0][0];
            data = {notificacion: notificacion};
            ajax.post(base_url + '/notificacion/json/nlikes', data, function(data2) {
              $scope.nlikes[notificacion] = data2;
            })
          }
}).controller('MonitorearTaller', function($scope, ajax) {
  $scope.estudiantes = [];
  taller = null;

  $scope.MonitorearTaller = function(id) {
    taller = id;
    load_data();
  }

  load_data = function() {
    ajax.post(base_url + '/curso/json/monitorear_taller', {taller: taller}, function(data) {
      $scope.estudiantes = data;
      //window.console.log(data);
    })
  }

}).controller('MonitorearEstudiante', function($scope, ajax) {
  $scope.estudiantes = [];
  var curso = null;
  $scope.MonitorearEstudiante = function(cursoid) {
    curso = cursoid;
    load_data();
  }

  load_data = function() {
    ajax.post(base_url + '/curso/json/monitorear_estudiantes', {curso: curso}, function(data) {
      $scope.estudiantes = data;
      window.console.log(data);
    });
  },
          $scope.set_monitor = function(id, curso) {
            id = id[0][0];
            ajax.get(URL.set_monitor, {monitor: id, curso: curso}, function(data) {
              if (data == 1) {
                alertify.alert('El estudiante ha sido asignado como monitor');
              } else {
                alertify.alert('El estudiante ha dejado de ser monitor');
              }
            });

          }
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

