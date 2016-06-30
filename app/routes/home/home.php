<?php

  use Symfony\Component\DomCrawler\Crawler;
  use GuzzleHttp\Cookie\SetCookie;

  $app->post('/resolve',function() use($app){

    $url = $_POST['url'];
    $movies = crawl($app,$url);
    $app->redirect($app->urlFor('home'));

  })->name('post.add');

  $app->get('/content',function() use($app){
    set_time_limit(0); // unlimited max execution time
    $url ="http://123movies.to/movie/topimdb";

    $client = $app->goute;
    $crawler = $client->request('GET', $url);
    $crawler = $crawler->filterXPath('//*[@id="main"]/div/div[2]/div/div[2]/div/a');
    $movies = $crawler->each(function ($node) use ($app){
        $movie_url = $node->attr('href');
        $movie_url = $movie_url."watching.html";
        return $movie_url;
    });

    foreach ($movies as $movie) {
      crawl($app,$movie);
      break;
    }
  })->name('content');

  $app->get("/download/:id",function($id) use($app){

    $link = $app->Link->find($id);
    $movie = $app->Movie->find($link->movie_id);
    $movieFolder = str_replace([':','?',"/",' '],"_",$movie->title);
    $title = str_replace([':','?',"/",' '],"_",$link->title);
    $folder = 'C:/wamp/www/crawler/Downloads/'.$movieFolder;
    if(!file_exists($folder)){
      mkdir($folder);
    }
    $cmd = 'wget -O '.$folder.'/'.$title.'.mp4 --no-cookies --header "Cookie:'. $movie->ads_hash."=". $movie->ads_token.'" --header "Cookie:__cfduid = d24101684cf98efe599dfcd70370065ec1467193730" --no-check-certificate '.$link->url .'2>&1';

    $WshShell = new COM("WScript.Shell");
    $oExec = $WshShell->Run($cmd, 0, false);
    echo "downloading...";
  })->name("download");
 ?>
