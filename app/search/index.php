<?php
/**
 * クエリパラメータで指定された条件の結果を表示
 *
 * @author media max japan inc.
 * @since php7.3
 * @version 0.0.1
 */
require('../config.php');

$markers = [];
$stmt = '';
$location = $id = $lat = $lng = $rad = '';

try {
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ];
  $pdo = new PDO('mysql:host='.HOST.':'.PORT.';dbname='.DB_NAME.';charset=utf8mb4', USER_NAME, PASSWORD,$options);

} catch ( PDOException $e ) {
  exit( 'データベース接続失敗 ' . $e->getMessage() );
}

if( !empty($_GET['keyword']) ) $location = htmlspecialchars($_GET['keyword'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['id']) ) $id = htmlspecialchars($_GET['id'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['lat']) ) $lat = htmlspecialchars($_GET['lat'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['lng']) ) $lng = htmlspecialchars($_GET['lng'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['radius']) ) $rad = htmlspecialchars($_GET['radius'],ENT_QUOTES,'UTF-8');
$sql = 'SELECT id,name,latitude,longitude,source,source_description,note FROM place WHERE ';

if( !empty($location) ) $sql = $sql.'name LIKE :location';

if( !empty($id) ) {
  if( empty($location) ) {
    $sql = $sql.' id = :id ';
  } else {
    $sql = $sql.' AND id = :id ';
  }
}
if( !empty($lat) && !empty($lng) ) {
  $rad = empty($rad) ? 1000 : (int)$rad * 1000;
  if( !empty($location) || !empty($id) ) {
    $sql = $sql.' AND SQRT(POWER((latitude - :lat) * 111000,2) + POWER((longitude - :lng) * 91000,2)) <= :rad';
  } else {
    $sql = $sql.' SQRT(POWER((latitude - :lat) * 111000,2) + POWER((longitude - :lng) * 91000,2)) <= :rad';
  }
}

$stmt = $pdo->prepare($sql);
if( !empty($location) ) $stmt->bindValue(':location', '%'.addcslashes($location, '\_%').'%',PDO::PARAM_STR);
if( !empty($id) ) $stmt->bindValue(':id',$id, PDO::PARAM_INT);
if( !empty($lat) ) $stmt->bindValue(':lat',$lat, PDO::PARAM_INT);
if( !empty($lng) ) $stmt->bindValue(':lng',$lng, PDO::PARAM_INT);
if( !empty($rad) ) $stmt->bindValue(':rad',$rad, PDO::PARAM_INT);
$stmt->execute();

while( $row = $stmt->fetch() ) {
  $id = $row['id'];
  $name = $row['name'];
  $lat = $row['latitude'];
  $lng = $row['longitude'];
  $source = $row['source'];
  $desc = $row['source_description'];
  $note = $row['note'];
  $markers[] = ['id' => $id, 'name' => $name, 'lat' => $lat, 'lng' => $lng, 'source' => $source,'desc' => $desc, 'note' => $note ];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <title>検索結果 | 歴史地名データ検索システム</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="mb-3">
    <div class="container py-3">
      <h1 class="h3">歴史地名データ検索システム</h1>
    </div>
  </header>
  <section>
    <div class="container">
      <script>
        var map;
        var markers = [
        <?php
        foreach($markers as $m) {
          echo "{'name':'".$m['name']."','lat':".$m['lat'].",'lng':".$m['lng'].",'content':'".$m['source'].' ' .$m['desc']."'},";
        } ?>];
        console.log(markers);

        function initMap() {
          var center = new google.maps.LatLng({lat: markers[0]['lat'], lng: markers[0]['lng']}); 
          map = new google.maps.Map(document.getElementById('map'), { 
            center: center,
            zoom: 15,
            scrollwheel: true
          });
         
          markers.forEach( function(val, index, arr) {
            var marker = new google.maps.Marker({
              position: {lat: val.lat,lng: val.lng},
              map: map
            });

            google.maps.event.addListener(marker, 'click', function(e) {
              new google.maps.InfoWindow({ 
                content: '<div class="info"><h3 class="h5">' + val.name + '</h3><p>' + val.content  +  '</p></div>'
              }).open(marker.getMap(), marker);
            });
          });
        }

      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo API_KEY; ?>&callback=initMap" async defer></script>
      <div id="map" class="mb-3"></div>
      <a href="/">検索フォームへ戻る</a>
      <?php 
      if( count($markers) == 0 ): ?>
        <p class="mt-3">ご指定条件の地名は見つかりませんでした。</p>
      <?php else: ?>
        <table class="table table-bordered table-responsive mt-3">
          <thead>
            <tr>
              <th>地名ID</th><th>地名</th><th>出典</th><th>出典詳細</th><th>備考</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach( $markers as $m ): ?>
            <tr>
            <td><?php echo $m['id']; ?></td><td><?php echo $m['name']; ?></td><td><?php echo $m['source']; ?></td><td><?php echo $m['desc']; ?></td><td><?php echo $m['note']; ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </section>
  <!-- jQuery first, then Bootstrap JS. -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>
</body>
</html>
