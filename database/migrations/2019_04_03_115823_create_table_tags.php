<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->string('tag_name', 30)->comment('标签名');
            $table->string('desc', 255)->nullable()->comment('标签描述');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->tinyInteger('is_del')->default(1)->comment('删除为1 不删除为2');
            $table->tinyInteger('is_show')->default(1)->comment('展示为1 不展示2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
