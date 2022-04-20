<?php
//not working yet
require "../MovieObject.php";
class singleMoviePresenter
{
  private $movie;
  public function __construct($movie)
  {
    $this->movie = $movie;
  }
  public function displayFavourite($loginStatus)
  {
    if ($loginStatus) {
      //return markup that looks like this:
      // <p> Favourite : <span class="fa fa-heart " id="258193"></span></p>
      return 'Favourite : <span class="fa fa-heart" id="258193"></span>';

      //echo "HEYYYYYY";
    }
  }
}
