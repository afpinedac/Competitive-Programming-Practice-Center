//controladores
angular.module('Controllers', [])
        .controller('envioController', function($scope, $http, $interval, Envio) {

          $scope.envios = [];
          $scope.n = 0;

          $scope.get_envios = function() {
            interval = $interval(function() {
              Envio.get_lista().success(function(data) {
                //window.console.log(data);
                $scope.envios = data;
              });
            }, 3000);
          };
          
          $scope.getClass = function(data){
             if(data.respuesta == 'accepted') return 'success';
             else if(data.respuesta == 'wrong answer') return 'warning';
             else if(data.respuesta == 'compilation error') return 'info';
             else return '';
          };

        });

angular.module('Classes', [])
        .factory('Envio', function($http) {
          return {
            get_lista: function() {
              return $http.get(envios.request_url); //<--ojo cambiar
            }
          }
        });



var colaEnvios = angular.module('colaEnvios', ['Controllers', 'Classes']);

