<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStatCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_course', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->Integer('total_course')->default(0)->comment('累计课程数');
            $table->Integer('today_increase_course')->default(0)->comment('今日新增课程数');
            $table->Integer('today_audit_course')->default(0)->comment('今日审核课程数');
            $table->Integer('last_week_course')->default(0)->comment('近7日课程数');
            $table->Integer('last_mon_course')->default(0)->comment('近30日课程数');
            $table->Integer('l_w_avg_increased_course')->default(0)->comment('近7日新增课程数');
            $table->Integer('l_w_increased_course')->default(0)->comment('近7日平均新增课程数');
            $table->Integer('l_m_increased_course')->default(0)->comment('近30日平均新增课程数');
            $table->Integer('l_m_avg_audit_course')->default(0)->comment('近30日新增课程数');
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
        Schema::dropIfExists('stat_course');
    }
}
