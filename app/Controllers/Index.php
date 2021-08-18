<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Index extends BaseController
{
	// Almacena una instancia de coneccion a la base de datos
	protected $db;
	protected $forge;

	public function __construct()
	{
		// Creamos nuestra instancia de conexxion a la base de datos
		$this->db = \Config\Database::connect();
		$this->forge = \Config\Database::forge();
	}

	public function index()
	{
		// Obtenemos el listado de tablas de la base de datos
		$tables = $this->db->listTables();

		$data = [
			"tables" => $tables
		];


		return view("index", $data);
	}

	public function load()
	{
		// Validamos la informacion
		$validation = service('validation');
		$validation->setRules([
			'csv_file' => [
				'label' => 'El archivo',
				'rules' => 'uploaded[csv_file]|ext_in[csv_file,csv]'
			],
			'table' => [
				'label' => 'tabla',
				'rules' => 'required'
			],
		]);

		if (!$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with(
				'errors',
				$validation->getErrors()
			);
		}

		// Obtenemos los datos del CSV
		$temp_csv = $this->request->getFile('csv_file');

		// Generamos un nombre para guardarlo
		$file_name = $temp_csv->getRandomName();
		// Guardamos el archivo en public/csvfile/
		$temp_csv->move('../public/csvfile', $file_name);
		// Leemos el archivo guardado
		$file = fopen("../public/csvfile/" . $file_name, "r");

		// Guardamos la data en un array
		$csv = array();
		while (($row = fgetcsv($file, 10000, ",")) != FALSE) {
			$csv[] = $row;
		}

		$fields = $this->db->getFieldNames(
			trim($this->request->getVar('table'))
		);

		$data = [
			"csv" => $csv,
			"file_name" => $file_name,
			"table" => trim($this->request->getVar('table')),
			"fileds" => $fields,
			"headers" => fgetcsv(fopen("../public/csvfile/" . $file_name, "r"))
		];

		return view("load", $data);
	}

	public function proccess()
	{
		// TODO: VALIDATE

		// Capturamos los campos
		$file_name	     = trim($this->request->getVar('file_name'));
		$table 		     = trim($this->request->getVar('table'));
		$index 		     = $this->request->getVar('col');
		$old_fields_name = $this->request->getVar('name');
		$migrate 	     = $this->request->getVar('migrate');
		$old_id 	     = $this->request->getVar('old_id');
		$fields 		 = $this->request->getVar('field');

		// Obtenemos las columnas de la tabla
		$all_fields = $this->db->getFieldNames($table);

		// Ordenamos los datos del formulario en un solo array ------------
		$data = array();
		if ($migrate) {
			foreach ($migrate as $key => $value) {
				$data[] = ["csv_index" => $value];
			}
		}
		foreach ($data as $key => $value) {
			if (in_array($value["csv_index"], $old_id)) {
				$data[$key]["old_id"] = 1;
			} else {
				$data[$key]["old_id"] = 0;
			}
		}
		foreach ($data as $key => $value) {
			$data[$key]['field'] = $fields[$value["csv_index"]];
			$data[$key]['old_column'] = $old_fields_name[$value["csv_index"]];
		}
		// ----------------------------------------------------------------

		// Guardaos el arreglo con la data en sesion
		session()->set(['data' => $data]);

		$final_data = array();

		foreach ($all_fields as $key => $item) {
			$in_array = false;
			$val = [];

			foreach ($data as $key => $value) {
				if ($item == $value['field']) {
					$in_array = true;
					$val = $value;
				}
			}

			if ($in_array) {
				$final_data[] = [
					"destiny_field" => $item,
					"old_id" => $val["old_id"],
					"origin_filed_index" => $val["csv_index"],
					"old_column" => $val["old_column"]
				];
			} else {
				$final_data[] = [
					"destiny_field" => $item,
					"old_id" => "0",
					"origin_filed_index" => -1,
					"old_column" => ""
				];
			}
		}

		session()->set(["final_data" => $final_data]);
		$tables = $this->db->listTables();



		$data = [
			"data" => $data,
			"final_data" => $final_data,
			"file_name" => $file_name,
			"table" => $table,
			"tables" => $tables,
			"headers" => fgetcsv(fopen("../public/csvfile/" . $file_name, "r"))
		];

		return view("proccess", $data);
	}

	public function pre()
	{

		// dd($this->request);
		// dd(session("final_data"));

		// Variables para obtener los datos del csv
		$final_data = session("final_data");
		$origin_filed_index = [];
		$destiny_filed_name = [];

		// Variables para asignar los valores por defecto
		$defauld_value_form = $this->request->getVar("defauld_value");
		$destiny_field_form = $this->request->getVar("destiny_field");

		// Variables para guardar los id anteriores
		$old_id_index = 0;

		// Variables para relaciones entre tablas
		$join_tables = $this->request->getVar("join_table");
		$join_cols = $this->request->getVar("join_col");
		$compare_fields = $this->request->getVar("compare");
		$value_fields = $this->request->getVar("value_col");


		foreach ($final_data as $value) {
			if ($value['origin_filed_index'] != -1) {
				$origin_filed_index[] = $value['origin_filed_index'];
				$destiny_filed_name[$value['origin_filed_index']] = $value['destiny_field'];
			}
		}

		// Creamos el old_id
		$is_old_id = $this->crear_old_id(
			session("data"),
			$this->request->getVar('table')
		);

		if ($is_old_id) {
			foreach (session("data") as $item) {
				if ($item['old_id'] == 1) {
					$old_id_index = $item['csv_index'];
				}
			}
		}

		$csv = $this->obtener_informacion_csv(
			trim($this->request->getVar('file_name'))
		);

		// Eliminamos el encabezado
		unset($csv[0]);

		// dd($final_data);
		$data = [];
		foreach ($csv as $key => $item) {

			$temp = [];

			// Asignamos los valores a migrar en sus campos correspondientes
			foreach ($item as $key => $csv_item) {
				if (in_array($key, $origin_filed_index)) {
					if (is_numeric($csv_item)) {
						$temp[$destiny_filed_name[$key]] =  $csv_item;
					} else {
						$temp[$destiny_filed_name[$key]] = 
						mb_strtoupper($csv_item) == "\N" ? "" 
						: mb_strtoupper($csv_item) ;
					}
				}
			}

			// Asignamos los campos por defecto
			foreach ($defauld_value_form as $key => $default) {
				if ($default != "") {
					if (is_numeric($default)) {
						$temp[$destiny_field_form[$key]] = $default;
					} else {
						$temp[$destiny_field_form[$key]] = mb_strtoupper($default);
					}
				}
			}

			// Asignamos los valores para las llaves foraneas
			foreach ($join_tables as $key => $table) {
				if ($table != "") {
					$join_table = $join_tables[$key];
					$join_col = $join_cols[$key];
					$value = $value_fields[$key];
					$compare = $item[$compare_fields[$key]];

					$query = $this->db->query("SELECT $value FROM $join_table WHERE $join_col = '$compare'");
					if (!empty($query->getResult())) {
						$x = (array) $query->getResult()[0];
						$temp[$destiny_field_form[$key]] = $x[$value];
					}else{
						$temp[$destiny_field_form[$key]] = 1;
					}
				}
			}

			// asignamos el campo old_id
			if ($is_old_id) {
				$temp['old_id'] = $item[$old_id_index];
			}



			$data[] = $temp;
		}

		// Eliminamos el encabezado


		session()->set(['batch_data' => $data]);
		session()->set(['table_name' => trim($this->request->getVar('table'))]);

		$data = [
			"data" => $data
		];

		return view("pre", $data);
	}

	public function save()
	{
		$builder = $this->db->table(session('table_name'));
		$data = session("batch_data");

		$this->db->transStart();
		$builder->insertBatch($data);
		$this->db->transComplete();

		if ($this->db->transStatus() === FALSE) {
			return view("error");
		}

		return view("success");
	}

	/**
	 * Crea el campo old_id en caso de ser requerido
	 */
	private function crear_old_id($data, $table_name)
	{

		$crear_old_id = false;

		foreach ($data as $value) {
			if ($value['old_id'] == 1) {
				$crear_old_id = true;
			}
		}

		if (!$this->db->fieldExists('old_id', $table_name) && $crear_old_id) {
			$fields = [
				'old_id' => [
					'type' => 'INT',
					'null' => true,
				],

			];
			$this->forge->addColumn($table_name, $fields);
		}

		return $crear_old_id;
	}

	/**
	 * Obtener la informacion del CSV
	 */
	private function obtener_informacion_csv($file_name)
	{
		// Leemos el archivo guardado
		$file = fopen("../public/csvfile/" . $file_name, "r");

		// Guardamos la data en un array
		$csv = array();
		while (($row = fgetcsv($file, 10000, ",")) != FALSE) {
			$csv[] = $row;
		}

		return $csv;
	}
}
