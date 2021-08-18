<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="<?= base_url("/assets/css/bootstrap.min.css") ?>" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Herramienta de migracion</title>
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Herramienta de migracion de PostgreSql a MariaDB</span>
        </div>
    </nav>
    <div class="container">
        <br>
        
        <h3>Previsualizar datos antes de insertar</h3>
        <?php d($data) ?>
        
        <a href="<?= base_url("index/save") ?>" class="btn btn-primary">MIGRAR</a>

        
    <script src="<?= base_url("/assets/js/bootstrap.bundle.min.js") ?>" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>