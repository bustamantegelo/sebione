<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateCompaniesTable
 * @package ${NAMESPACE}
 * @author  Angelo C. Bustamante <bustamantegelo@gmail.com>
 * @since   08/03/2021
 * @version 1.0
 */
class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T_COMPANIES', function (Blueprint $table) {
            $table->bigIncrements('company_id')
                ->comment('type: big int, length: 20, comment: Company Id');
            $table->string('name')
                ->length(255)
                ->comment('type: varchar, length: 255, comment: Company name');
            $table->string('email')
                ->length(255)
                ->comment('type: varchar, length: 255, comment: Company email');
            $table->string('logo')
                ->length(255)
                ->comment('type: varchar, length: 255, comment: Company logo file path');
            $table->string('website')
                ->length(2083)
                ->comment('type: varchar, length: 2083, comment: Company website');
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
        Schema::dropIfExists('companies');
    }
}
