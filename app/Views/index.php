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

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <ul>
                        <li>
                            Paso 1 - Exporte la tabla que decea migrar de postgres en un archivo CSV
                        </li>
                        <li>
                            Paso 2 - Importe en esta herramienta el archivo CSV que exporto
                        </li>
                        <li>
                            Paso 3 - Seleccione la tabla destino
                        </li>
                        <li>
                            Paso 4 - Haga click en "Siguiente"
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if ((session('errors')) !== null):?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach (session('errors') as $error):?>
                    <li><?= $error?></li>
                <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>

        <form action="<?= base_url("/index/load")?>" method="post" enctype="multipart/form-data">
        
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="csv_file" name="csv_file" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <select class="form-select" aria-label="Default select example" name="table" required>
                        <option selected value="">Seleccione la tabla destino</option>
                        <?php foreach ($tables as $key => $table):?>
                            <option value="<?= $table ?>"><?= $table ?></option>
                        <?php endforeach?>
                     </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Siguiente</button>
        </form>

    </div>



    <script src="<?= base_url("/assets/js/bootstrap.bundle.min.js") ?>" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>