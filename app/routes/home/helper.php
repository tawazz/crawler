<?php

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Cookie\SetCookie;

function crawl($app,$url)
{
  set_time_limit(0); // unlimited max execution time
  $DATA = [];
  if(!$app->Movie->movieExists($url)){
    $client = $app->Client;
    $response = $client->request('GET', $url,[
      "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
      "cookies"=>$app->CookieJar
    ]);
    $crawler = new Crawler($response->getBody()->getContents());
    //image
    $movie_image = $crawler->filter('body > div.page-container > div:nth-child(3) > div:nth-child(2) > div > div:nth-child(1) > div.col-lg-4.col-md-4.col-sm-4.col-xs-12 > div.profile-pic > img')->first()->attr('src');
    $movie_image = "http://xmovies8.tv".$movie_image;

    //title
    $movie_title = $crawler->filter('body > div.page-container > div:nth-child(3) > div:nth-child(2) > div > div:nth-child(1) > div.col-lg-8.col-md-8.col-sm-8.col-xs-12 > h1')->first()->text();

    $episodes_links = $crawler->filter('body > div.page-container > div:nth-child(3) > div.block-s2.well > div > div.group-btn > a');
    $episodes = $episodes_links->each(function ($node){
      $episode_title = $node->text();
      $episode_url = "http:".$node->attr('href');
      $episode_id = explode('=',$episode_url);
      $episode_id = $episode_id[1];
      return[
        'title'=> $episode_title,
        'url' => $episode_url,
        'id' => $episode_id
      ];
    });

    $LINKS = [];
    foreach ($episodes as $episode) {
      $client = $app->Client;
      $response = $client->request('GET', $episode['url'],[
        "headers"=>['User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31"],
        "cookies"=>$app->CookieJar
      ]);
      $crawler = new Crawler($response->getBody()->getContents());

      $links_quality = $crawler->filter('#list-eps > div.le-server > div.les-content > a');
      $link_ids = $links_quality->each(function($node){
          $func = $node->attr('onclick');
          $quality = $node->text();
          $patten = "(\'.*?\')";
          preg_match_all($patten,$func,$matches);
          $matches=trim($matches[0][0],"\'");
        return[
          'id'=> $matches,
          'quality'=>rtrim($quality, "p") 
        ];
      });
      foreach ($link_ids as $link_id) {

        $response = $client->request('POST', "http://xmovies8.tv/ajax/movie/load_player" ,[
          "form_params"  => [
            "quality"    => $link_id['quality'],
            "id"=> $link_id['id'],
          ],
          "headers"      => [
            'User-Agent' => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36 OPR/38.0.2220.31",
            'Referer'    => $episode['url'],
          ],
          "cookies"      => $app->CookieJar,

        ]);

        $crawler = new Crawler($response->getBody()->getContents());
        $download_link = $crawler->filter('input')->first();
        if(isset($download_link)){
          $download_link =$download_link->attr('value');
          array_push($LINKS ,[
            'title' => $episode['title'],
            'url' => $download_link,
          ]);
        }
        break;
      }

    }
    $app->Movie->saveUrl($url,$movie_title,$movie_image,$LINKS);
    array_push($DATA,[
      'movie_title' => $movie_title,
      'movie_url'   => $url,
      'movie_image' => $movie_image,
      'links'       => $LINKS
    ]);
    return $DATA;
  }else{
    $movie = $app->Movie->where('url',$url)->first();
    $links = $app->Link->where('movie_id',$movie->id)->get();
    $DATA = [];
    $LINKS = [];
    foreach ($links as $link) {
      array_push($LINKS ,[
        'title' => $link->title,
        'url' => $link->url,
      ]);
    }
    array_push($DATA,[
      'movie_title' => $movie->title,
      'movie_id'   => $movie->id,
      'movie_image' => $movie->image,
      'links'       => $LINKS
    ]);
    return $DATA;
  }
}

 ?>
