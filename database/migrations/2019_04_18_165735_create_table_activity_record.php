<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableActivityRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_record', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('自增id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->integer('activity_id')->comment('活动id');
            $table->integer('channel_id')->comment('渠道id');
            $table->string('name')->comment('参与人姓名');
            $table->tinyInteger('gender')->comment('性别 1男2女3未知');
            $table->bigInteger('mobile')->comment('联系方式');
            $table->string('comment')->nullable()->comment('备注');
            $table->tinyInteger('is_del')->default(1)->comment('1 未删除 2 删除');
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
        Schema::dropIfExists('activity_record');
    }
}
