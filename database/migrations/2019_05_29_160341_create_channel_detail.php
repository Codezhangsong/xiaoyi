<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_channel_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('渠道ID');
            $table->integer('act_id')->comment('活动id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->integer('channel_id')->comment('渠道id');
            $table->string('channel_name')->comment('渠道名称');
            $table->string('url')->comment('渠道url');
            $table->integer('pv')->default(0)->comment('渠道pv数');
            $table->integer('jump_rate')->default(0)->comment('渠道pv数');
            $table->tinyInteger('is_del')->default(1)->comment('1 未删除 2 删除');
            $table->string('comment')->nullable()->comment('备注');
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
        Schema::dropIfExists('activity_channel_detail');
    }
}
