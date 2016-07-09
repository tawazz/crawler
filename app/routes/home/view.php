<?php

$app->get('/',function() use ($app){
  $movies = $app->Movie->all();
  $app->render('home/home.php',["movies"=>$movies]);
})->name('home');

$app->get('/results',function() use($app){

})->name('results');

$app->get('/view/:id',function($id) use ($app){
  $movie = $app->Movie->findOrFail($id);
  if($app->Movie->movieExists($movie->url)){
    $links = $app->Link->where('movie_id',$id)->get();
    $app->render('home/result.php',["movie"=>$movie,"links"=>$links]);
  }else{
    crawl($app,$movie->url);
    $links = $app->Link->where('movie_id',$id)->get();
    $app->render('home/result.php',["movie"=>$movie,"links"=>$links]);
  }

})->name('view');

$app->get('/add',function() use ($app){
  $app->render('home/add.php');
})->name('add');

$app->get('/about',function() use ($app){
  $app->render('home/iframe.php');
})->name('about');

$app->get('/test',function() use ($app){
  $app->render('home/xmovies.php');
})->name('test');

 ?>
