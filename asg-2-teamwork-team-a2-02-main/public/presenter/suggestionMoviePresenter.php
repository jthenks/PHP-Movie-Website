<?php

//tales in user object, finds their favouri

class suggestionMoviePresenter
{

  public $suggMovie;
  function __construct($suggMovie)
  {
    $this->suggMovie = $suggMovie;
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
    return  '<div class="pic"><img data-id=' . $this->suggMovie['id'] . ' alt=' .  $this->createAlt()   . " src=https://image.tmdb.org/t/p/w92/" . $this->suggMovie['poster_path'] .
      "></div>";
  }

  public function createAlt()
  {
    return str_replace(' ', '', $this->suggMovie['title']);
  }

  function createTitle()
  {
    return '<div class="title" data-id= ' . $this->suggMovie['id'] . '>' . $this->suggMovie['title'] . "</div>";
  }
}
