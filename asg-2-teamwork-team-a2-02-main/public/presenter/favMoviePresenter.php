<?php

//tales in user object, finds their favouri

class favMoviePresenter
{

  public $favMovie;
  function __construct($favMovie)
  {
    $this->favMovie = $favMovie;
  }

  function displayCard()
  {

    // "<div class='fav-card'> " .
    //   " <div class='pic'> <img src='https://image.tmdb.org/t/p/w92/'" . $this->favMovie['poster_path'] .
    //   "alt=" . $favMovie['title'] . "</div>" .
    //   "<div class='title'> " . $favMovie['title'] . "</div>
    //     </div>";

    return "<div class='fav-card'> " . $this->createImg() . $this->createTitle() .  "</div>";
  }

  public function createImg()
  {
    return  '<div class="pic"><img data-id='. $this->favMovie['id'] . ' alt=' .  $this->createAlt()   . " src=https://image.tmdb.org/t/p/w92/" . $this->favMovie['poster_path'] .
      "></div>";
  }

  public function createAlt()
  {
    return str_replace(' ', '',$this->favMovie['title'] );
  }

  function createTitle()
  {
    return '<div class="title" data-id= ' . $this->favMovie['id'] . '>'. $this->favMovie['title'] . "</div>";
  }
}
