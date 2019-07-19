<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->string('name', 30)->comment('学生姓名');
            $table->string('parent_name', 30)->comment('家长姓名');
            $table->bigInteger('parent_mobile')->comment('家长电话');
            $table->tinyInteger('gender')->comment('性别 1男性 2 女性 ');
            $table->integer('age')->comment('学生年龄');
            $table->date('birthday')->comment('生日');
            $table->string('province')->nullable()->comment('省');
            $table->string('city')->nullable()->comment('市');
            $table->string('region')->nullable()->comment('区');
            $table->string('street')->nullable()->comment('街道');
            $table->string('lat')->nullable()->comment('经度');
            $table->string('lng')->nullable()->comment('纬度');
            $table->string('school',50)->comment('学校');
            $table->string('origin',50)->comment('来源');
            $table->string('intention',50)->comment('意向课程');
            $table->date('reg_date')->comment('注册日期');
            $table->tinyInteger('is_del')->default(1)->comment('是否删除 1未删除 2删除');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
