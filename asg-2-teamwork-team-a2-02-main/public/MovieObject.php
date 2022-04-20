<?php

class MovieObject
{

  public $id;
  public $tmdbId;
  public $imdbId;
  public $relDate;
  public $title;
  public $voteAvg;
  public $voteCount;
  public $runtime;
  public $popularity;
  public $revenue;
  public $posterPath;
  public $tagline;
  public $overview;
  public $prodCompanies;
  public $prodCountries;
  public $genres;
  public $keywords;
  public $cast;
  public $crew;

  public function __construct(
    $id,
    $tmdbId,
    $imdbId,
    $relDate,
    $title,
    $voteAvg,
    $voteCount,
    $runtime,
    $popularity,
    $revenue,
    $posterPath,
    $tagline,
    $overview,
    $prodCompanies,
    $prodCountries,
    $genres,
    $keywords,
    $cast,
    $crew
  ) {
    $this->id = $id;
    $this->tmdbId = $tmdbId;
    $this->imdbId = $imdbId;
    $this->relDate = $relDate;
    $this->title = $title;
    $this->voteAvg = $voteAvg;
    $this->voteCount = $voteCount;
    $this->runtime = $runtime;
    $this->popularity = $popularity;
    $this->revenue = $revenue;
    $this->posterPath = $posterPath;
    $this->tagline = $tagline;
    $this->overview = $overview;
    $this->prodCompanies = $prodCompanies;
    $this->prodCountries = $prodCountries;
    $this->genres = $genres;
    $this->keywords = $keywords;
    $this->cast = $cast;
    $this->crew = $crew;
  }

  function formattedRevenue()
  {
    return number_format($this->revenue, 2);
  }

  /**
   * This function populates the companies, countries, keywords, and genres sections of the single-movie page based on what section title is passed in.
   */
  function populateDetails($section)
  {
    $stringList = "";
    $chameleonList = $this->$section;
    $chameleonList = json_decode($chameleonList, true);

    foreach ($chameleonList as $value) {
      $stringList .= $value["name"] . ", ";
    }
    // this return capitalizes the first letter of the list and removes the trailing space and comma. 
    return ucfirst(substr_replace($stringList, "", -2) . ".");
  }

  function populateCrew()
  {
    $stringList = "";
    $crewList = $this->crew;
    $crewList = json_decode($crewList, true);
    $sortedCrew = $this->sortCrew($crewList);

    foreach ($sortedCrew as $value) {
      $stringList .= '<div class="crewDepartment">' . $value["department"] . '</div> <div class="crewJob">' . $value["job"] .
        '</div> <div class="crewName">' . $value["name"] . "</div>";
    }
    //var_dump($crewList);
    return $stringList;
  }

  function populateCast()
  {
    $stringList = "";
    $castList = $this->cast;
    $castList = json_decode($castList, true);
    $sortedCast = $this->sortCast($castList);

    foreach ($sortedCast as $value) {
      $stringList .= '<div class="castCharacter">' . $value["character"] . '</div> <div class="castName">' . $value["name"] . "</div>";
    }

    // var_dump($castList);
    return $stringList;
  }

  public function sortCrew($crewToSort)
  {

    //https://blog.martinhujer.cz/clever-way-to-sort-php-arrays-by-multiple-values/
    usort($crewToSort, function ($a,  $b): int {
      if ($a['department'] === $b['department']) {
        return $a['name'] <=> $b['name'];
      }
      return $a['department'] <=> $b['department'];
    });

    //var_dump($list);
    return $crewToSort;
  }

  public function sortCast($castToSort)
  {

    //https://blog.martinhujer.cz/clever-way-to-sort-php-arrays-by-multiple-values/
    usort($castToSort, function ($a,  $b): int {

      return $a['order'] <=> $b['order'];
    });

    // var_dump($list);
    return $castToSort;
  }
}
