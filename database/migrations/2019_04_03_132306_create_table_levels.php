<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->string('level_name', 30)->comment('等级名称');
            $table->string('desc', 255)->nullable()->comment('等级描述');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->tinyInteger('is_del')->default(1)->comment('是否删除 未删除 1 删除 2');
            $table->tinyInteger('is_show')->default(1)->comment('是否展示 1展示 2不展示');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
