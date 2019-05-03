<?php
/**
 * typeパラメータで指定されたAPIレスポンスを返す
 *
 * @author media max japan
 * @since php7.3
 * @version 0.0.1
 */
require('../config.php');
include_once('../vendor/phayes/geophp/geoPHP.inc');

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

$location = $id = $lat = $lng = $rad = '';

if( !empty($_GET['keyword']) ) $location = htmlspecialchars($_GET['keyword'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['id']) ) $id = htmlspecialchars($_GET['id'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['lat']) ) $lat = htmlspecialchars($_GET['lat'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['lng']) ) $lng = htmlspecialchars($_GET['lng'],ENT_QUOTES,'UTF-8');
if( !empty($_GET['radius']) ) $rad = htmlspecialchars($_GET['radius'],ENT_QUOTES,'UTF-8');

if( empty($_GET['keyword']) && empty($_GET['id']) && empty($_GET['lat']) && empty($_GET['lng'])  ) {
  if( $_GET['type'] == 'geojson' ) {
    $collection = ["type" => "FeatureCollection",
      "features" => []
    ];

    header('Content-Type: application/vnd.geo+json; charset="UTF-8"');
    header('Access-Control-Allow-Origin: ' . HTTP_ORIGIN);
    header('Access-Control-Allow-Method: ' . HTTP_ACCESS_CONTROL_REQUEST_METHOD);
    header('Access-Control-Allow-Headers: ' . HTTP_ACCESS_CONTROL_REQUEST_HEADERS);
    echo json_encode($collection);
  } elseif( $_GET['type'] == 'kml' ) {
    header('Content-Type: application/vnd.google-earch.kml+xml kml; charset="UTF-8"');
    header('Access-Control-Allow-Origin: ' . HTTP_ORIGIN);
    header('Access-Control-Allow-Method: ' . HTTP_ACCESS_CONTROL_REQUEST_METHOD);
    header('Access-Control-Allow-Headers: ' . HTTP_ACCESS_CONTROL_REQUEST_HEADERS);
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<kml xmlns="http://earth.gooogle.com//kml/2.1">';
    echo '</kml>';
  } else {
    exit;
  }

} else {
  $sql = 'SELECT name,latitude,longitude,source,source_description FROM place WHERE ';

  if( !empty($location) ) {
    $sql = $sql.'name LIKE :location';
  }

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

  if( $_GET['type'] == 'geojson' ) {
    $collection = ["type" => "FeatureCollection",
      "features" => []
    ];

    $stmt->execute();

    while( $row = $stmt->fetch() ) {
      $features[] = [
        "type" => "Feature",
        "geometry" => [
          "type" => "Point",
          "coordinates" => [ $row['longitude'], $row['latitude'] ]
        ],
        "properties" => [
          "name" => $row['name'],
          "description" => $row['source'].$row['source_description']
        ]
      ];
      $collection["features"] = $features;
    }

    header('Content-Type: application/vnd.geo+json; charset="UTF-8"');
    header('Access-Control-Allow-Origin: ' . HTTP_ORIGIN);
    header('Access-Control-Allow-Method: ' . HTTP_ACCESS_CONTROL_REQUEST_METHOD);
    header('Access-Control-Allow-Headers: ' . HTTP_ACCESS_CONTROL_REQUEST_HEADERS);
    echo json_encode($collection);
  } elseif( $_GET['type'] == 'kml' ) {
    header('Content-Type: application/vnd.google-earch.kml+xml kml; charset="UTF-8"');
    header('Access-Control-Allow-Origin: ' . HTTP_ORIGIN);
    header('Access-Control-Allow-Method: ' . HTTP_ACCESS_CONTROL_REQUEST_METHOD);
    header('Access-Control-Allow-Headers: ' . HTTP_ACCESS_CONTROL_REQUEST_HEADERS);
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<kml xmlns="http://earth.gooogle.com//kml/2.1">';
    $stmt->execute();
    while( $row = $stmt->fetch() ) {
      echo '<Placemark>';
      echo '<name>'.$row['name'].'</name>';
      echo '<description>'.$row['source'].$row['source_description'].'</description>';
      echo '<Point>';
      echo '<coordinates>'.$row['longitude'].','.$row['latitude'].'</coordinates>';
      echo '</Point>';
      echo '</Placemark>';
    }
    echo '</kml>';
  } else {
    exit;
  }
}
