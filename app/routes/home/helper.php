<?php

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Cookie\SetCookie;

function getLinks($app,$movie_title,$movie_image,$resolve_url,$episode_url,$ads_hash,$ads_token)
{

  $client = $app->Client;
  $response = $client->request('GET', $episode_url,[
    "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
    "cookies"=>$app->CookieJar
  ]);
  $crawler = new Crawler($response->getBody()->getContents());
  $crawler=$crawler->filter('.le-server > .les-content > a');

    $episodes = $crawler->each(function ($node) use ($app,$movie_title,$movie_image,$ads_hash,$ads_token){
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
        "ads_hash" => $ads_hash,
        "ads_token" => $ads_token,
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
    $movies= getLinks($app,$movie_title,$movie_image,$url,$episode_url,$ads_hash,$ads_token);
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
