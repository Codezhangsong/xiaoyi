<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStatStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_students', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->integer('total_students')->default(0)->comment('累计学生数');
            $table->integer('last_week_increased_students')->default(0)->comment('近七天累计学生数');
            $table->decimal('sex_proportion',2,2)->nullable()->comment('男女比例');
            $table->string('age_distribution')->nullable()->comment('家长最高分布年龄段');
            $table->string('location_distribution')->nullable()->comment('最高分布地区');
            $table->date('stat_date');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stat_students');
    }
}
