<?php

namespace Crawler\Models;
use Illuminate\Database\Eloquent\Model as Model;
use Carbon\Carbon;

/**
 * represents table movie
 */
class Episode extends Model
{

  public function saveUrl($resolve_url,$episode_url,$resolve_html,$episode_html,$movieContent)
  {
    $movies = Movie::where('url',$resolve_url)->count();
    if($movies < 1 )
    {
      foreach ($movieContent as $M) {
        $movie = new Movie();
        $movie->url = $resolve_url;
        $movie->episode_url = $episode_url;
        $movie->episode_html = $episode_html;
        $movie->title = $M["title"];
        $movie->save();
        foreach ($M["info"] as $info) {
          $link = new Link();
          $link->movie_id = $movie->id;
          $link->url = $info["file_url"];
          $link->quality = $info["quality"];
          $link->type = $info["type"];
          $link->content_url = $M["contentUrl"];
          $link->content_html = $M["contentHtml"];
          $link->title = $info['title'];
          $link->save();
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

  public function links()
  {
    return $this->hasMany('Crawler\Models\Link');
  }

}



 ?>
