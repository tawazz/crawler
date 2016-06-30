<?php
  require 'vendor/autoload.php';
  require 'app/config/db.php';
  use Slim\Slim;
  use Goutte\Client;
  use GuzzleHttp\Client as HTTP;
  use Crawler\Models\Movie;
  use Crawler\Models\Link;
  use GuzzleHttp\Cookie\FileCookieJar;

  $app = new Slim([
    'view'=> new \Slim\Views\Twig()
  ]);

  //views
  $view = $app->view();
  $view->setTemplatesDirectory('app/views');
  $view->parserExtensions = [new \Slim\Views\TwigExtension()];

  //dependancies
  $app->container->singleton('CookieJar',function(){
    return  new FileCookieJar('c:/wamp/www/crawler/cookies.txt',true);
  });
  $app->container->singleton('Client',function() use ($app){
    return  new HTTP([
      'allow_redirects' => true,
      'cookies' => $app->CookieJar,
      //'proxy' => "localhost:8888"
    ]);
  });
  $app->container->singleton('goute',function() use ($app){
    return  new Client([
      'allow_redirects' => true,
      'cookies' => $app->CookieJar,
      'HTTP_USER_AGENT' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"
    ]);
  });
  $app->container->singleton('db',function(){
    return new Capsule;
  });
  $app->container->singleton('Movie',function(){
    return  new Movie();
  });
  $app->container->singleton('Link',function(){
    return  new Link();
  });

  //routes
  require'app/routes/routes.php';

  $app->run();
 ?>
