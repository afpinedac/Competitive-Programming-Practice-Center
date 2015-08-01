<?php

class TestController extends LMSController {

  public function getResizeAchievements() {

    if (!Auth::check() || (Auth::check() && !Auth::user()->id == 1))
      exit;

    foreach (scandir(public_path() . '/img/logros') as $logro) {
      try {
        Image::make(public_path() . ("/img/logros/{$logro}"))->resize(100, 100)->save(public_path() . ("/img/logros/small/{$logro}"));
      } catch (Exception $e) {
        echo "error en {$logro} <br>";
      }
    }
  }

  public function getCreateImages($from = 1) {
    if (!Auth::check() || (Auth::check() && !Auth::user()->id == 1))
      exit;
    $c = 0;
    $max = Usuario::max('id');
    while ($c < 40 && $from <= $max) {
      try {
        Image::make(public_path() . ("/avatares/userimages/{$from}.png"))->resize(20, 20)->save(public_path() . ("/avatares/userimages/thumbnail/{$from}.png"));
        Image::make(public_path() . ("/avatares/userimages/{$from}.png"))->resize(64, 64)->save(public_path() . ("/avatares/userimages/small/{$from}.png"));
      } catch (Exception $e) {
        echo "error en {$from}.png <br>";
      } finally {
        $from++;
        $c++;
      }
    }
    if ($from < $max) {
      return Redirect::to("/create-images/{$from}");
    }
  }

}
