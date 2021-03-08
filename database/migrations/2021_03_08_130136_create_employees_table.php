<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateEmployeesTable
 * @package ${NAMESPACE}
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   08/03/2021
 * @version 1.0
 */
class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T_EMPLOYEES', function (Blueprint $table) {
            $table->bigIncrements('employee_id')
                ->comment('type: big int, length: 20, comment: Employees Id');
            $table->string('first_name')
                ->length(35)
                ->comment('type: varchar, length: 35, comment: Employees first name');
            $table->string('last_name')
                ->length(50)
                ->comment('type: varchar, length: 50, comment: Employees last name');
            $table->bigInteger('company_id')
                ->unsigned()
                ->comment('type: big int, length: 20, comment: Company Id');
            $table->foreign('company_id')
                ->references('company_id')
                ->on('T_COMPANIES')
                ->onDelete('cascade');
            $table->string('email')
                ->length(254)
                ->comment('type: varchar, length: 254, comment: Employees email')
                ->nullable();
            $table->string('phone')
                ->length(15)
                ->comment('type: varchar, length: 15, comment: Employees phone number')
                ->nullable();
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('type: timestamp, length: , comment: row created date');
            $table->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))
                ->comment('type: timestamp, length: , comment: row updated date');
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T_EMPLOYEES');
    }
}
