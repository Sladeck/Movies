<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MovieSearch</title>
  <script src="\js\jquery-2.2.0.min.js"></script>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<div class="row">
  <div class="col-md-12">
    <div class="well">
      <form class="form-horizontal">
        <div class="form-group">
          <label for="titleInput" class="col-sm-2 control-label">Titre</label>
          <div class="col-sm-10">
            <input name="title" type="text" class="form-control" id="titleInput" placeholder="Titre du film">
          </div>
        </div>
        <div class="form-group">
          <label for="durationInput" class="col-sm-2 control-label">Durée</label>
          <div class="col-sm-10">
            <select name="duration" class="form-control" id="titleInput">
              <option value="All">Tous</option>
              <option value="lessH">Moins d'une heure</option>
              <option value="betweenShort">Entre 1h et 1h30</option>
              <option value="betweenLong">Entre 1h30 et 2h30</option>
              <option value="moreH">Plus de 2h30</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Année</label>
          <div class="col-sm-1">
            Entre
          </div>
          <div class="col-sm-4">
            <input name="year_start" type="text" class="form-control titleInput" placeholder="début">
          </div>
          <div class="col-sm-1">
            Et
          </div>
          <div class="col-sm-4">
            <input name="year_end" type="text" class="form-control titleInput" placeholder="fin">
          </div>
        </div>
        <!-- <div class="col-sm-1">
          Auteur
        </div>
        <div class="col-sm-4">
          <input name="author" type="text" class="form-control titleInput" placeholder="Auteur">
        </div> -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" class="btn btn-default" value="Chercher">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="results">
  <table class="table table-hover">
    <tr>
      <th>
        Titre
      </th>
      <th>
        Année
      </th>
      <th>
        Synopsis
      </th>
      <th>
        Nom
      </th>
      <th>
        Prénom
      </th>
      <th>
        Durée
      </th>
    </tr>
  </table>
</div>

<script type="text/javascript">
  $(function(){


    $('form').submit(function(e){

      $.post("/Index/search",$(this).serialize(),function(data){
        if(typeof(data.error) != "undefined"){
          alert(data.error);
        }else{

          for (var i in data.films) {
              second = data.films[i].duration;
              minutes = second / 60;
              second = second % 60;
              hour = minutes / 60;
              minutes = minutes % 60;
              $('.table').append(
                  '<tr><td>' + data.films[i].title + '</td><br>'
                  + '<td>' + data.films[i].year + '</td><br>'
                  + '<td>' + data.films[i].synopsis + '</td><br>'
                  + '<td>' + data.films[i].first_name + '</td><br>'
                  + '<td>' + data.films[i].last_name + '</td><br>'
                  + '<td>' + Math.trunc(hour) + 'h' + Math.trunc(minutes) + '</td><br></tr>'
              );
          }
        }

      },'json');

        return false;
    });

  });
</script>

</body>
</html>
