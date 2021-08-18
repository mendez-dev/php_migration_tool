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
    <div class="container-fluid">
        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <ul>
                        <li>
                            Paso 1 - Ingrese un valor por defecto para las columnas que no tienen datos (ignore la llave primaria)
                        </li>
                        <li>
                        Paso 2 - Indique el usuario que crea y actualiza el registro (1 corresponde al super usuario)
                        </li>
                    </ul>
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

        <form action="<?= base_url("/index/pre") ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="file_name" value="<?= $file_name ?>">
            <input type="hidden" name="table" value="<?= $table ?>">

            <?php foreach ($data as $key => $value) : ?>
                <?php if ($value['old_id'] == 1) : ?>
                    <div class="alert alert-primary">
                        Se creara un campo <b>old_id</b> de tipo entero para almacenar el campo <b><?= $value["old_column"] ?></b>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php foreach ($final_data as $key => $value) : ?>

                <div class="row">
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label for="index_<?= $key ?>" class="form-label">Indice</label>
                            <input type="text" name="index[]" class="form-control" id="index_<?= $key ?>" value="<?= $key ?>" readonly>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="destiny_field_<?= $key ?>" class="form-label">Columna destino</label>
                            <input type="text" name="destiny_field[]" class="form-control" id="destiny_field_<?= $key ?>" value="<?= $value['destiny_field'] ?>" readonly>
                        </div>
                    </div>

                    <?php if ($value['old_column'] == "") : ?>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="defauld_value_<?= $key ?>" class="form-label">Valor por defecto</label>
                                <input type="text" name="defauld_value[]" class="form-control" id="defauld_value_<?= $key ?>" value="">
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="col-md-2">
                            <p style="margin-top: 15px;" >Tomara el valor de la columna <b><?= $value['old_column'] ?></b></p>
                            <input type="hidden" name="defauld_value[]" class="form-control" id="defauld_value_<?= $key ?>" value="">
                        </div>
                    <?php endif; ?>

                    <?php if ($value['old_column'] == "") : ?>
                        <div class="col-md-1">
                            <div class="form-check" style="margin-top: 35px;">
                                <input class="form-check-input" type="checkbox" name="join[]" value="<?= $key ?>" id="join_<?= $key ?>">
                                <label class="form-check-label" for="join_<?= $key ?>">
                                    Join
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select class="form-select" aria-label="Default select example" name="join_table[]" style="margin-top: 33px;">
                                <option selected value="">Seleccione la tabla</option>
                                <?php foreach ($tables as $key => $table) : ?>
                                    <option value="<?= $table ?>"><?= $table ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <div class="mb-3">
                                <label for="join_col_<?= $key ?>" class="form-label">Columna</label>
                                <input type="text" name="join_col[]" class="form-control" id="join_col_<?= $key ?>" value="">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <select class="form-select" aria-label="Default select example" name="compare[]" style="margin-top: 33px;">
                                <option selected value="">Comparar con</option>
                                <?php foreach ($headers as $key => $header) : ?>
                                    <option value="<?= $key ?>"><?= $header ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>


                        <div class="col-md-1">
                            <div class="mb-3">
                                <label for="value_col_<?= $key ?>" class="form-label">Tomar el valor</label>
                                <input type="text" name="value_col[]" class="form-control" id="value_col_<?= $key ?>" value="">
                            </div>
                        </div>

                    <?php else : ?>

                        <input class="form-check-input" type="hidden" name="join[]" id="join_<?= $key ?>">
                        <input class="form-check-input" type="hidden" name="join_table[]" id="join_table_<?= $key ?>">
                        <input class="form-check-input" type="hidden" name="join_col[]" id="join_col_<?= $key ?>">
                        <input class="form-check-input" type="hidden" name="compare[]" id="compare_<?= $key ?>">
                        <input class="form-check-input" type="hidden" name="value_col[]" id="value_col_<?= $key ?>">

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary">Siguiente</button>


        </form>

    </div>



    <script src="<?= base_url("/assets/js/bootstrap.bundle.min.js") ?>" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>