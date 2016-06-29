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
 ?>
