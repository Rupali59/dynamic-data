<?php

	namespace App\Http\Controllers;

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Schema;

	class SchemaController extends Controller
	{
		private $input;

		public function __construct(Request $request)
		{
			$this->input = $request->all();
		}

		/**
		 * Show the form for creating a new resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function create(Request $request)
		{
			$input     = $this->input;
			$tableName = $input[ 'tableName' ];
			$columns   = $input[ 'columns' ];
			if ($this->exists($tableName)) {
				return \response("Table with name $tableName already exists!", Response::HTTP_FORBIDDEN);
			}
			else {
				try {
					Schema::create($tableName, function (Blueprint $table) use ($columns) {
						$table->increments('id');
						foreach ($columns as $column) {
							$method = $this->mapColumns($column[ 'type' ]);
							if ($method == "integer") {
								call_user_func_array([ $table, $method ], [ $column[ 'name' ], false, isset($column[ "unsigned" ]) ]);
							}
							else {
								call_user_func_array([ $table, $method ], [ $column[ 'name' ], implode("", $column[ 'arguments' ]) ]);
							}
						}
					});
					return \response("Table with name $tableName created!", Response::HTTP_OK);
				} catch (\Exception $exception) {
					return \response("Error in creating a new table", Response::HTTP_INTERNAL_SERVER_ERROR);

				}
			}
		}

		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function exists($schemaName)
		{
			return Schema::hasTable($schemaName);
		}

		private function mapColumns($type)
		{
			switch (strtoupper($type)) {
				case "BIGINT" :
					return "bigInteger";

				case "BOOLEAN":
					return "boolean";

				case "CHAR":
					return "char";

				case "DATE":
					return "date";

				case "DATETIME":
					return "dateTime";

				case "DECIMAL":
					return "decimal";

				case "DOUBLE":
					return "double";

				case "ENUM":
					return "enum";

				case "FLOAT":
					return "float";

				case "INTEGER":
					return "integer";

				case "LONGTEXT":
					return "longText";

				case "MEDIUMINT":
					return "mediumInteger";

				case "MEDIUMTEXT":
					return "mediumText";

				case "SMALLINT":
					return "smallInteger";

				case "TINYINT":
					return "tinyInteger";

				case "VARCHAR":
					return "string";

				case "TEXT":
					return "text";

				case "TIME":
					return "time";

				case "TIMESTAMP":
					return "timestamp";

			}
		}

		/**
		 * Store a newly created resource in storage.
		 *
		 * @param  \Illuminate\Http\Request $request
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function store($schemaName)
		{
			if ($this->exists($schemaName)) {
				$input = $this->input;
				try {
					DB::table($schemaName)->insert($input);
					return \response("Row successfully inserted", Response::HTTP_OK);

				} catch (\Exception $exception) {
					return \response("Error during insertion!", Response::HTTP_INTERNAL_SERVER_ERROR);

				}
			}
			else {
				return \response("No table with name: $schemaName exists!", Response::HTTP_FORBIDDEN);
			}

		}

		/**
		 * Display the specified resource.
		 *
		 * @param  int $id
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function show($schemaName)
		{
			$data = $users = DB::table($schemaName)->get();
			return \response(json_encode($data), Response::HTTP_OK);
		}

		public function showAll()
		{
			$tableNames = [];
			$tables     = DB::select('SHOW TABLES');
			foreach ($tables as $table) {
				foreach ($table as $key => $value) {
					$tableNames[] = $value;
				}
			}
			return \response(json_encode($tableNames), Response::HTTP_OK);
		}

		/**
		 * Update the specified resource in storage.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  int $id
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function update($schemaName)
		{
			$input = $this->input;
			DB::table($schemaName)
			  ->where('id', $input[ 'id' ])
			  ->update($input[ 'content' ]);
		}

		/**
		 * Remove the specified resource from storage.
		 *
		 * @param  int $id
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function deleteRow($schemaName)
		{
			$input = $this->input;
			DB::table($schemaName)->where('id', $input[ 'id' ])->delete();
		}

		public function dropTable($schemaName)
		{
			DB::table($schemaName)->delete();
		}
	}
