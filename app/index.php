<!DOCTYPE html>
<html lang="ja">
<head>
  <title>歴史地名データ検索システム</title>
  <!-- Required meta tags always come first -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- Bootstrap CSS -->
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
      <form action="../search/" method="GET">
        <h2 class="h5">地名検索</h2>
        <div class="form-group mb-4">
          <input type="text" name="keyword" class="form-control" placeholder="地名を入力してください" />
        </div>

        <h2 class="h5">ID検索</h2>
        <div class="form-group mb-4">
          <input type="number" name="id" class="form-control" placeholder="IDを入力して下さい"/>
        </div>

        <h2 class="h5">緯度・経度検索</h2>
        <div class="form-row mb-3">
          <div class="col">
          <input type="text" name="lat" class="form-control" placeholder="緯度を入力して下さい"/>
          </div>
          <div class="col">
          <input type="text" name="lng" class="form-control" placeholder="経度を入力して下さい"/>
          </div>
          <div class="col">
            <input type="number" name="radius" class="form-control" placeholder="(オプション)km単位で半径を指定する"/>
          </div>
        </div>
        
        <input type="submit" value="検索する" class="btn btn-primary">
      </form>
    </div>
  </section>
  <!-- jQuery first, then Bootstrap JS. -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>
</body>
</html>
