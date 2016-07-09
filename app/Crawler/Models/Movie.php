<?php

namespace Crawler\Models;
use Illuminate\Database\Eloquent\Model as Model;
use Carbon\Carbon;

/**
 * represents table movie
 */
class Movie extends Model
{

  public function saveUrl($resolve_url,$movie_title,$movie_image,$episodes)
  {
    $movies = Movie::where('url',$resolve_url)->count();
    if($movies < 1 )
    {
      $movie = new Movie();
      $movie->title = $movie_title;
      $movie->image = $movie_image;
      $movie->url =$resolve_url;
      $movie->save();
      foreach ($episodes as $episode) {
        $link = new Link();
        $link->movie_id = $movie->id;
        $link->url = $episode["url"];
        $link->title = $episode['title'];
        $link->save();
      }
    }else{
      $movie = Movie::where('url',$resolve_url)->first();
      $links = Link::where('movie_id',$movie->id)->get();
      foreach ($links as $link) {
        $link->delete();
      }
      foreach ($episodes as $episode) {
        $link = new Link();
        $link->movie_id = $movie->id;
        $link->url = $episode["url"];
        $link->title = $episode['title'];
        $link->save();
      }
    }
  }

  public function movieExists($url)
  {
      $movies = Movie::where('url',$url)->count();
      if($movies > 0){
        $expires = Carbon::createFromTimestamp(time() - 3600*3,'Australia/Perth');
        $movie = Movie::where('url',$url)->first();
        if($movie->updated_at->lt($expires)){
          $movie->updated_at = time();
          $movie->save();
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
