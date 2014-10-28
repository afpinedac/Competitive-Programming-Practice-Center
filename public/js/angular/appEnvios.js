//controladores
angular.module('Controllers', [])
        .controller('envioController', function($scope, $http, $interval, Envio) {

          $scope.envios = [];
          $scope.n = 0;

          $scope.get_envios = function() {
            interval = $interval(function() {
              Envio.get_lista().success(function(data) {
                window.console.log(data);
                $scope.envios = data;
              });
            }, 3000);
          };

          $scope.add = function() {
            addx = $interval(function() {
              $scope.n++;
            }, 1000);
          }
        });
        
angular.module('Classes', [])
        .factory('Envio', function($http) {
          return {
            get_lista: function() {
              return $http.get('http://localhost/lms2/public/envio/all/4'); //<--ojo cambiar
            }
          }
        });



var colaEnvios = angular.module('colaEnvios', ['Controllers', 'Classes']);

