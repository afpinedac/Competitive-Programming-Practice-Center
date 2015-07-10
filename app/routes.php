<?php

//App Controller
Route::get('/', ['uses' => 'AppController@getIndex']);
Route::get('/about', ['uses' => 'AppController@getAbout']);
Route::get('/registrar', ['uses' => 'AppController@getRegistrar']);
Route::post('/registrar', ['uses' => 'AppController@postRegistrar']);
Route::post('/loguear',['uses' => 'AppController@postLoguear']);
Route::get('/create-images/{from?}', ['uses' => 'AppController@getCreateImages']);
Route::get('/au/{id}', ['uses' => 'AppController@getAutenticar']);
Route::get('/ver-logro/{logro}', ['uses' => 'AppController@getVerLogro']);
Route::get('/update-avatars/{from}/{to}', ['uses' => 'AppController@getUpdateAvatars']);


Route::group(['before' => 'admin'], function() {
  Route::controller('admin', 'AdminController');
});


Route::group(array('before' => 'auth'), function() {

  Route::controller('usuario', 'UsuarioController');
  Route::controller('curso', 'CursoController');
  Route::controller('taller', 'TallerController');
  Route::controller('evaluacion', 'EvaluacionController');
  Route::controller('recurso', 'RecursoController');
  Route::controller('modulo', 'ModuloController');
  Route::controller('contenido', 'ContenidoController');
  Route::controller('ejercicio', 'EjercicioController');
  Route::controller('notificacion', 'NotificacionController');
  Route::controller('mensaje', 'MensajeController');
  Route::controller('chat', 'ChatController');
  Route::controller('foro', 'ForoController');
  Route::controller('item', 'ItemController');
  Route::controller('api', 'ApiController');
  Route::controller('lms', 'LMSController');
});




















