<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourseDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->increments('id')->comment('自增id');
            $table->Integer('course_id')->comment('课程id');
            $table->string('lesson_id', 50)->nullable()->comment('平台端课程id');
            $table->string('lesson_name', 50)->comment('课节名称');
            $table->string('code', 50)->nullable()->comment('课节code，同一课程内不能重复');
            $table->Integer('lesson_num')->comment('课结数');
            $table->date('lesson_date')->comment('上课时间');
            $table->time('start_time')->comment('开始时间 08:00');
            $table->time('end_time')->comment('结束时间 18:00');
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_detail');
    }
}
