<?php

use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration;

class CreateTrackerSqlQueryBindingsParametersTable extends Migration {

	/**
	 * Table related to this migration.
	 *
	 * @var string
	 */

	private $table = 'tracker_sql_query_bindings_parameters';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Do not run this migration in testing env
        if (!env('TRACKER_ENABLED')) {
            return;
        }

        Schema::connection('tracker')->create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('sql_query_bindings_id')->unsigned()->nullable();
            $table->string('name')->nullable()->index();
            $table->text('value')->nullable();

            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->index();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // Do not run this migration in testing env
        if (env('APP_ENV') == 'testing') {
            return;
        }

        Schema::drop($this->table);
	}

}
