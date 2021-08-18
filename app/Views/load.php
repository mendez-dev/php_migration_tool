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
                            Paso 1 - Indique las columna que decea migrar (Las columnas no marcadas se ignoraran)
                        </li>
                        <li>
                            Paso 2 - Para los ID seleccione old_id para relacionar con futuras migraciones (No necesitas selecionar columna destino)
                        </li>
                        <li>
                            Paso 3 - Seleccione la columna destino para las columnas que se migraran
                        </li>
                        <li>
                            Paso 4 - Haga Click en "siguiente"
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Previsualizar CSV
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <?php d($csv) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ((session('errors')) !== null) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url("/index/proccess") ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="file_name" value="<?=$file_name?>">
        <input type="hidden" name="table" value="<?=$table?>">

            <?php foreach ($headers as $key => $header) : ?>


                <div class="row">

                    <div class="col-md-1">
                        <div class="mb-3">
                            <label for="col_<?= $key ?>" class="form-label">Indice</label>
                            <input type="text" name="col[]" class="form-control" id="col_<?= $key ?>" value="<?= $key ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="name_<?= $key ?>" class="form-label">Columna</label>
                            <input type="text" name="name[]" class="form-control" id="name_<?= $key ?>" value="<?= $header ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-2"></div>

                    <div class="col-md-2">
                        <div class="form-check" style="margin-top: 35px;">
                            <input class="form-check-input" type="checkbox" name="migrate[]" value="<?=$key?>" id="migrate_<?=$key?>">
                            <label class="form-check-label" for="migrate_<?=$key?>">
                                Migrar
                            </label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-check" style="margin-top: 35px;">
                            <input class="form-check-input" type="checkbox" name="old_id[]" value="<?=$key?>" id="old_id_<?=$key?>">
                            <label class="form-check-label" for="old_id_<?=$key?>">
                                Crear old_id
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="filed_<?=$key?>">Columna destino</label>
                    <select class="form-select" aria-label="Default select example" id="filed_<?=$key?>"  name="field[]">
                        <option selected value="">Seleccione</option>
                        <?php foreach ($fileds as $key => $item):?>
                            <option value="<?= $item ?>"><?= $item ?></option>
                        <?php endforeach?>
                     </select>
                </div>



                </div>

            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Siguiente</button>


        </form>

    </div>



    <script src="<?= base_url("/assets/js/bootstrap.bundle.min.js") ?>" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>