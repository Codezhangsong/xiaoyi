<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->string('name', 30)->comment('家长姓名');
            $table->char('password', 32)->comment('密码');
            $table->bigInteger('mobile')->comment('联系方式');
            $table->tinyInteger('gender')->comment('性别 1男 2女');
            $table->tinyInteger('age')->comment('年龄');
            $table->date('birthday')->comment('生日 1993-01-01');
            $table->integer('level_id')->comment('等级id');
            $table->string('level',50)->comment('等级名');
            $table->string('province',50)->comment('省');
            $table->string('city',50)->comment('市');
            $table->string('region',50)->comment('区');
            $table->string('street')->comment('街道');
            $table->string('lat')->nullable()->comment('经度');
            $table->string('lng')->nullable()->comment('纬度');
            $table->string('occupation',50)->comment('职业');
            $table->string('tag_id')->comment('标签id');
            $table->string('tag_name')->comment('标签名');
            $table->date('reg_date')->comment('注册日期');
            $table->tinyInteger('use_flag')->default(1)->comment('启用为1  不启用为2');
            $table->tinyInteger('is_del')->default(1)->comment('未删除 1 删除 2');
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

        Schema::dropIfExists('parents');
    }
}
