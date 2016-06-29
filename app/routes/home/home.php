<?php

  use Symfony\Component\DomCrawler\Crawler;
  use GuzzleHttp\Cookie\SetCookie;

  $app->get('/resolve',function() use($app){


    //$body = $app->request->getBody();
    //$body = json_decode($body);
    //$url = $body->url;
    $url ="http://123movies.to/film/containment-season-1-11616/watching.html";
    $movies = crawl($app,$url);
    //var_dump($movies);
    $app->render('home/home.php',["movies"=>$movies]);
  });
  $app->get('/',function() use ($app){
    $movies = $app->Movie->all();
    $app->render('home/home.php',["movies"=>$movies]);
  })->name('home');

  $app->get('/movie/:id',function($id) use ($app){
    $movie = $app->Movie->find($id);
    $links = $app->Link->where('movie_id',$id)->get();
    $app->render('home/result.php',["movie"=>$movie,"links"=>$links]);
  })->name('view');

  $app->get('/results',function() use($app){
    $app->Client->request('GET',"http://123movies.to/movie/topimdb");
    var_dump($app->Client);
  })->name('results');

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

  $app->post("/download",function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);
    $url = $body->url;

    set_time_limit(0); // unlimited max execution time
    $episode_url = "http://streaming.fshare.to/api_process.php?link=V25JMlFiNnBRZUlXOGxxTm1OVEtMb3BBTG82b0E1N0JkMzk1MVVzQ0htOG5Wa0k1N0phWW9aNzlEbnhlcDBOVEtBcUhQbWxRWXFGbm1pQTJqaGRyYngyQWJycllSVldoVzhaSnM0OERHbHNPQlM0amo2OFlURHBUZElTWW9ZR3Q2eEo0VXQ3TjJsOUJPTnNhMytaVGY0U3JiM04xbmgyeDVyTFpod0x3bnR1NXhNcksrWHJEcFErU25WdzhOQTJJQ21iSnpOQW1XSTA3SXJkZDRyTXNQMitmZm10T3VMYU9IcmV1bG5PeGpFcmZRa3d4QTN0Q2xkc3czSkNPWmlxS2pWdC82eVh5N01TbTZpTXp3ajA2c0QwdnBWaUVjRFR2cEU5RDRoai9MdlhEaGRxYTc4ZkhwalFRNWlSekhRa2Jrd3NTZWE5VGlvMnNTZUhrdGZUTUNiWHR2UXF5Y2ZUKzVlVHhyMGR0aGNKenp4ZWsyenR2MGpIaVZYUlhodnVxd2ZhNTNoakc2ZEJmbWZEY1ZKUk03S1BjK1kwM0Z3Si92ZjE0TjFHeFc1SFBCZ3g3Z2N6T09hVC93Tm1rTTNsOHdWMjA3WTRvV0QyVlU3NER3VkF2NmlHZHZXVVNJSmxrcnc2Nmd4cmVMZVllY3Z1S3Z4VFBHU25BK0ZoNlN3M1RWZllNdzdOM2M2ZlY2eWJOQnBJZ05NRSs1amJRSWwzQlRQeWIxQXJCUGxIR3RpcVJQdkFaaGtlbkNITCtONkJ0eHhnaEVRRnV1dlAvNkdGbUs2Zm5LQT09";
    $client = $app->goute;
    $myFile = fopen('Downloads/movie2.mp4',"wb") or die("Problems");
    $response = $client->request('GET', $episode_url,['sink'=>$myFile,'stream' => true,'progress' => function ($dl_total_size, $dl_size_so_far, $ul_total_size, $ul_size_so_far) {
         echo $dl_size_so_far / $dl_total_size  * 100;
    }]);
    fclose($myFile);
  })->name("download");

  function getLinks($app,$movie_title,$movie_image,$resolve_url,$episode_url)
  {

    $client = $app->Client;
    $response = $client->request('GET', $episode_url,[
      "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
      "cookies"=>$app->CookieJar
    ]);
    $crawler = new Crawler($response->getBody()->getContents());
    $crawler=$crawler->filter('.le-server > .les-content > a');

      $episodes = $crawler->each(function ($node) use ($app,$movie_title,$movie_image){
        $episode_id = $node->attr('episode-id');
        $token = $node->attr('hash');
        $server = $node->attr('onclick');

        $content_url = "http://123movies.to/ajax/load_episode/{$episode_id}/{$token}";
        $client = $app->Client;
        $response = $client->request('GET',  $content_url,[
          "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
          "cookies"=>$app->CookieJar
        ]);
        //var_dump($response);
        $crawler = new Crawler($response->getBody()->getContents());
        $fileInfo = $crawler->filterXPath('//item/jwplayer:source')->each(function ($node) use ($app,$crawler){
          $file_title = $crawler->filterXPath('//item/title')->first()->text();
          $content = [
            "title" => str_replace(":","",$file_title),
            "type" => $node->attr('type'),
            "file_url" => $node->attr('file'),
            "quality"  => $node->attr('label')
          ];
          return $content;
        });
        $movieContent = [
          "title" => str_replace(":","",$movie_title),
          "image" => $movie_image,
          "contentUrl"=>$content_url,
          "info" => $fileInfo
        ];
        return $movieContent;
    });
    $app->Movie->saveUrl($movie_title,$movie_image,$resolve_url,$episode_url,$episodes);
    return $episodes;
  }

  function crawl($app,$url)
  {
    $movies = [];
    if(!$app->Movie->movieExists($url)){
      $client = $app->Client;
      $response = $client->request('GET', $url,[
        "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
        "cookies"=>$app->CookieJar
      ]);
      $crawler = new Crawler($response->getBody()->getContents());
      //image
      $movie_image = $crawler->filter('#mv-info > div.mvi-content > div.thumb.mvic-thumb')->first()->attr('style');
      $movie_image = str_replace('background-image: url(','',$movie_image);
      $movie_image = str_replace(');','',$movie_image);
      //title
      $movie_title = $crawler->filter('#bread > ol > li.active')->first()->text();

      //ads_tokens
      $ads = $crawler->filterXPath('//html/body/script[3]')->first()->text();
      $ads = str_split($ads,300)[0];
      $ads = explode(':',$ads);

      $ads_hash = $ads[5];
      $ads_token = $ads[6];

      $patten = "(\".*?\")";

      preg_match_all($patten,$ads_hash,$hash_matches);
      preg_match_all($patten,$ads_token,$token_matches);
      $ads_hash = trim($hash_matches[0][0],"\"");
      $ads_token = trim($token_matches[0][0],"\"");

      echo "$ads_hash <br>";
      echo "$ads_token";
      $cookie = new SetCookie([
        "Name"=> $ads_hash,
        "Value"=>$ads_token,
        "Domain"=>".123movies.to",
        "Path"=> "/",
        "Expires" => time()+3600*12
      ]);

      $app->CookieJar->SetCookie($cookie);
      //token and id
      $crawler=$crawler->filter('#media-player')->first();
      $token = $crawler->attr('player-token');
      $episode_id = $crawler->attr('movie-id');
      $episode_url = "http://123movies.to/ajax/get_episodes/{$episode_id}/{$token}";
      $movies= getLinks($app,$movie_title,$movie_image,$url,$episode_url);
    }else{
      $M = $app->Movie->where('url',$url)->get();
      foreach ($M as $movie) {
        $links = $app->Link->where('movie_id',$movie->id)->where('quality','!=',null)->get();
        $fileInfo =[];
        foreach ($links as $link) {
          $content = [
            "title" => str_replace(":","",$link->title),
            "type" => $link->type,
            "file_url" => $link->url,
            "quality"  => $link->quality
          ];
          //var_dump($content);
         array_push($fileInfo,$content);
        }
        array_push($movies,[
          "title" => str_replace(":","",$movie->title),
          "image" => $movie->image,
          "info" => $fileInfo
        ]);
      }
    }

    return $movies;
  }
 ?>
