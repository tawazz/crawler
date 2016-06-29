<?php

namespace Crawler\Models;
use Illuminate\Database\Eloquent\Model as Model;
use Carbon\Carbon;

/**
 * represents table movie
 */
class Movie extends Model
{

  public function saveUrl($movie_title,$movie_image,$resolve_url,$episode_url,$episodes)
  {
    $movies = Movie::where('url',$resolve_url)->count();
    if($movies < 1 )
    {
      $movie = new Movie();
      $movie->title = $movie_title;
      $movie->image = $movie_image;
      $movie->url =$resolve_url;
      $movie->episode_url = $episode_url;
      $movie->ads_hash = $episodes[0]["ads_hash"];
      $movie->ads_token = $episodes[0]["ads_token"];
      $movie->save();
      foreach ($episodes as $episode) {
        foreach ($episode["info"] as $info) {
          $link = new Link();
          $link->movie_id = $movie->id;
          $link->content_url= $episode["contentUrl"];
          $link->url = $info["file_url"];
          $link->quality = $info["quality"];
          $link->type = $info["type"];
          $link->title = $info['title'];
          if($link->quality!= null){
            $link->save();
          }
        }
      }
    }
  }

  public function movieExists($url)
  {
      $movies = Movie::where('url',$url)->count();
      if($movies > 0){
        $expires = Carbon::createFromTimestamp(time() - 3600*12,'Australia/Perth');
        $movies = Movie::where('url',$url)->get();
        if($movies[0]->updated_at->lt($expires)){
          foreach ($movies as $movie) {
              $movie->delete();
          }
          return false;
        }
        return true;
      }else{
        return false;
      }

  }

  public function episodes()
  {
    return $this->hasMany('Crawler\Models\Episode');
  }

}



 ?>
