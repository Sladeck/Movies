<?php
namespace Controller;

use Doctrine\DBAL\Query\QueryBuilder;

class IndexController{
  public function indexAction(){
      include("search.php");
  }

  public function searchAction(){
    //se connecter à la bdd
    header('Content-Type: application/json');

    $connec = \MovieSearch\Connexion::getInstance();

    $yearStart = $_POST['year_start'];
    $yearStart = strip_tags($yearStart);
    $yearStart = htmlentities($yearStart);
    $yearStart = trim($yearStart);

    $yearEnd = $_POST['year_end'];
    $yearEnd = strip_tags($yearEnd);
    $yearEnd = htmlentities($yearEnd);
    $yearEnd = trim($yearEnd);

    $author = $_POST['author'];
    $author = strip_tags($author);
    $author = htmlentities($author);
    $author = trim($author);

    $duration = $_POST['duration'];

    if(isset($duration)){
      if($duration === 'All'){
        $dura = "";
      }
      elseif ($duration === 'lessH') {
        $dura = " AND duration < 3600";
      }
      elseif ($duration === 'betweenShort') {
        $dura = " AND duration BETWEEN 3600 AND 5400";
      }
      elseif ($duration === 'betweenLong') {
        $dura = " AND duration BETWEEN 5400 AND 9000";
      }
      elseif ($duration === 'moreH') {
        $dura = " AND duration > 9000";
      }

    }

    if (isset($yearStart)) {
            $sqlYearStart = " AND year >= '$yearStart' ";
        }
        if (empty($yearStart)) {
            $sqlYearStart = "";
        }
        if (isset($yearEnd)) {
            $sqlYearEnd = " AND year <= '$yearEnd' ";
        }
        if (empty($yearEnd)) {
            $sqlYearEnd = "";
        }

    if(isset($_POST['title'])){
      //récup du titre + sécurité
      $title = $_POST['title'];
      $title = strip_tags($title);
      $title = htmlentities($title);
      $title = trim($title);
      $title = "%".$title."%"; //Requête pas secure sans le bindParam

      $qry = $connec->prepare("SELECT * FROM film_director
                                INNER JOIN artist AS a
                                ON artist_id = a.id
                                INNER JOIN film AS f
                                ON film_director.film_id = f.id
                                WHERE f.title
                                LIKE :title".$dura.$sqlYearStart.$sqlYearEnd);
      $qry->bindParam('title', $title);
    }else{
      $qry = $connec->prepare("SELECT * FROM film_director
                                INNER JOIN artist AS a
                                ON artist_id = a.id
                                INNER JOIN film AS f
                                ON film_director.film_id = f.id
                                WHERE 1".$dura.$sqlYearStart.$sqlYearEnd);
    }

    // if(isset($author)){
    //   $qry = $connec->prepare("SELECT *
    //                             FROM artist
    //                             INNER JOIN film_director
    //                             ON film_director.artist_id = artist.id
    //                             INNER JOIN film
    //                             ON film.id = film_director.film_id
    //                             WHERE last_name = :author");
    //   $qr->bindParam('author', $author)
    // }

    //envoyer la requête à la BDD
    $qry->execute();
    //renvoyer les films qu'on a trouvés
    $films = $qry->fetchAll();
    return json_encode(["films" => $films]);
  }
}
