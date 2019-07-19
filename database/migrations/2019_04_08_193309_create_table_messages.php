<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->increments('id')->comment('id');
            $table->string('title')->comment('消息标题');
            $table->integer('type_id')->comment('消息类型id');
            $table->string('type_name')->comment('消息类型');
            $table->integer('status')->comment('消息状态')->comment('1未读 2已读');
            $table->tinyInteger('is_del')->default(1)->comment('1未删除 2已删除');
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
        Schema::dropIfExists('messages');
    }
}
