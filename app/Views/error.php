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


    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    No se pudo realizar la migracion, revise la estructura de la tabla y del csv
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="<?= base_url() ?>" class="btn btn-primary btn-block">Aceptar</a>
            </div>
        </div>
        
    </div>




    <script src="<?= base_url("/assets/js/bootstrap.bundle.min.js") ?>" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>