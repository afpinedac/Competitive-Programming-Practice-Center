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
    template: "<div>\n\
                            <small class='pull-right' ng-click='watch_submission()' style='cursor: pointer; padding-right:5px;' ng-show='ready' onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)'>\n\
                                   x\n\
                             </small>\n\
                             <p><strong>Envio:</strong> [[envio]]</p>\n\
                              <p style='margin-top:-5px;'>\n\
                                  <strong>Estatus:</strong>\n\
                                      <a ng-if='!aceptado && !timeOrWrong' href='[[redirect]]' ng-click='watch_submission()' target='_blank'>\n\
                                                <span style='font-size:20px;'>[[status]]</span>\n\
                                       </a>\n\
                                       <a ng-if='aceptado' href=[[redirect]] ng-click='watch_submission()' >\n\
                                                <span style='font-size:20px;'>[[status]]</span>\n\
                                       </a>\n\
                                       <a ng-if='timeOrWrong && !aceptado' ng-click='watch_submission()' >\n\
                                                <span style='font-size:20px;'>[[status]]</span>\n\
                                       </a>\n\
                              </p>\n\
                 </div>",
    scope: {
      envio: "&envio"
    },
    link: function($scope, $attr, $element) {
      $scope.envio = $element.envio;
      tipo = $element.tipo;
      codigo = $element.codigo;
      $scope.ready = false;
      $scope.status = 'En espera...';
      $scope.redirect = '';
      $scope.aceptado = false;
      $scope.timeOrWrong = false;
      jinterval = $interval(function() {
        ajax.post(base_url + '/ejercicio/aceptar/0', {envio: $scope.envio}, function(data) {
          if (data) {
            if (data.resultado != null) {
              $scope.status = data.resultado;
              if (data.resultado == 'accepted') {
                $scope.status = 'Aceptado';
                $scope.aceptado = true;
              } else if (data.resultado == 'time limit') {
                $scope.status = 'Tiempo límite excedido';
                $scope.timeOrWrong = true;
              } else if (data.resultado == 'wrong answer') {
                $scope.status = 'Respuesta Incorrecta';
                $scope.timeOrWrong = true;
              } else if (data.resultado == 'compilation error') {
                $scope.status = 'Error de compilación';
              } else if (data.resultado == 'runtime error') {
                $scope.status = 'Error de ejecución';
              }
              $scope.ready = true;
            }
            stop_interval();
            //parar la ejecución
          }
        });
      }, 1000);

      stop_interval = function() {
        $interval.cancel(jinterval);
      };

      $scope.watch_submission = function() {
        ajax.post(base_url + '/ejercicio/aceptar', {envio: $scope.envio}, function(data) {
          $scope.loading = true;
          if (data.resultado == 'accepted') {

            ajax.post(base_url + '/ejercicio/calcular-puntos', {envio: data.id}, function(response) {
              alertify.log('Has obtenido ' + response.puntos_obtenidos + ' puntos', 'success', 3000);
            });
            if (tipo == 0) {
              $scope.redirect = base_url + '/curso/ver/' + data.curso + '/contenido';
            }
            else {
              $scope.redirect = base_url + '/curso/ver/' + data.curso + '/evaluacion/' + data.codigo;
            }
          } else if (data.resultado == 'compilation error' || data.resultado == 'runtime error') {  //compilation error or runtime error
            $("judgeonline").remove();
            $scope.redirect = base_url + '/curso/ver/' + data.curso + '/mis-envios/' + data.id;
          } else { //wrong answer or time limit
            $("judgeonline").remove();
          }
        });
      };


    }
  }
}).controller('InicioController', function($scope, ajax) {
  $scope.notificaciones = [];
  $scope.comentario = []; //guarda lo que la persona va escribiendo en el textarea
  var step_notificaciones = 10;
  $scope.notificaciones_mostradas = 0;
  $scope.boton_mas = true;
  $scope.loading = false;
  $scope.loading_init = false;
  $scope.notificaciones_arr = [];
  $scope.loading2 = false;
  var curso_actual;



  $scope.InicioController = function(curso) {
    $scope.loading_init = true;
    curso_actual = curso;
    $scope.cargar_notificaciones();
    $scope.loading_init = false;
  };



  $scope.cargar_notificaciones = function() {
    $scope.loading2 = true;
    ajax.post(base_url + '/curso/json/notificaciones', {curso: curso_actual, skip: $scope.notificaciones_mostradas}, function(data) {
      var array = $.map(data, function(value, index) {
        return [value];
      });
      $scope.notificaciones_arr = $scope.notificaciones_arr.concat(array.reverse());
      //window.console.log($scope.notificaciones_arr);
      $.extend($scope.notificaciones, data);
      //window.console.log($scope.notificaciones);
    });
    $scope.notificaciones_mostradas += step_notificaciones;
    $scope.loading2 = false;
  };

  $scope.comentar = function(notif) {
    notif = notif[0][0];
    data = {notificacion: notif, comentario: $scope.comentario[notif]};
    ajax.post(base_url + '/notificacion/comentar', data, function(data2) {
      $scope.notificaciones[notif].comentarios.push(
              {
                nombres: data2.nombres,
                apellidos: data2.apellidos,
                publicacion: data2.publicacion,
                comentadorid: data2.comentadorid,
                id: data2.id,
              }

      );
      $scope.comentario[notif] = '';
      alertify.log('Has comentado esto', "success", 4000);
    });

  },
          $scope.eliminar_comentario = function(comentario, notif) {
            comentario = comentario[0][0];
            notif = notif[0][0];
            data = {comentario: comentario};
            $("#comment-" + notif + "-" + comentario).remove();
            ajax.post(base_url + '/notificacion/eliminar-comentario', data, function(data2) {
              alertify.log("Comentario eliminado", "success", 4000);
            });
          },
          $scope.me_gusta = function(notificacion) {
            var notifi = notificacion[0][0],
                    data = {notificacion: notifi, usuario: $scope.usuario_logueado}, n;

            ajax.post(base_url + '/notificacion/me-gusta', data, function(data2) {
              n = parseInt($("#me-gusta-" + notifi).text(), 10);
              if (data2.tipo === '1') {
                $("#me-gusta-" + notifi).text(n + 1);
                $("#text-me-gusta-" + notifi).text("Ya no me gusta");
                alertify.log("Te ha gustado esta publicación", "success", 4000);
              } else {
                $("#text-me-gusta-" + notifi).text("Me gusta");
                $("#me-gusta-" + notifi).text(n - 1);
                alertify.log("Ya no te gusta esta publicación", "success", 4000);
              }
            });
          },
          $scope.eliminar_notificacion = function(notificacion) {
            ajax.post(base_url + '/notificacion/eliminar-comentario', {comentario: notificacion}, function(data) {
              if (data == "1") {
                $('#publicacion-' + notificacion + ' + hr').remove(); //quitamos el hr que está al final de cada publicación
                $('#publicacion-' + notificacion).remove();
                alertify.log("Se ha eliminado esta publicación", "success", 4000);
              } else {
                alertify.log('Se generó un problema, intentelo más tarde', "error", 4000);
                // alert();
              }
            });

          }


}).controller('EvaluacionController', function($scope, ajax) {
  evaluacion = null;

  $scope.EvaluacionController = function(eval) {
    evaluacion = eval;
  };

  //terminar
  $scope.get_tabla_de_posiciones = function() {

  };


}).controller('RankingEvaluacion', function($scope, ajax) {
  $scope.ranking = [];
  $scope.column = 'puntos_totales';
  $scope.RankingEvaluacion = function(evaluacion) {
    ajax.get(base_url + '/evaluacion/json/' + evaluacion, {}, function(data) {
      $scope.ranking = data;
    });
  }
}
).controller('MonitorearTaller', function($scope, ajax) {
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
      // window.console.log(data);
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



