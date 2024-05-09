<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_last_name', 20);
            $table->string('second_last_name', 20);
            $table->string('first_name', 20);
            $table->string('other_names', 50)->nullable();
            $table->enum('country', ['Colombia', 'United States']);
            $table->string('id_type');
            $table->string('id_number', 20)->unique();
            $table->string('email', 300)->unique();
            $table->date('entry_date');
            $table->string('area');
            $table->enum('status', ['Active']);
            $table->timestamp('registration_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
