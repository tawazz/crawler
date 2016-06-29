<?php

$app->get('/',function() use ($app){
  $movies = $app->Movie->all();
  $app->render('home/home.php',["movies"=>$movies]);
})->name('home');

$app->get('/results',function() use($app){

})->name('results');

$app->get('/view/:id',function($id) use ($app){
  $movie = $app->Movie->find($id);
  if($app->Movie->movieExists($movie->url)){
    $links = $app->Link->where('movie_id',$id)->get();
    $app->render('home/result.php',["movie"=>$movie,"links"=>$links]);
  }else{
    echo "Refreshing...";
    crawl($app,$movie->url);
    $movie = $app->Movie->where("url",$movie->url)->first();
    $links = $app->Link->where('movie_id',$id)->get();
    $app->render('home/result.php',["movie"=>$movie,"links"=>$links]);
  }

})->name('view');

$app->get('/add',function() use ($app){
  $app->render('home/add.php');
})->name('add');

$app->get('/about',function() use ($app){
  $app->render('home/home.php');
})->name('about');

 ?>
