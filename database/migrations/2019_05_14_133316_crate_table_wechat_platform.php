<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableWechatPlatform extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_platform', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('自增id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->string('title')->comment('标题');
            $table->string('content')->comment('内容');
            $table->string('media_id')->comment('mediaId');
            $table->tinyInteger('result')->comment('1成功2失败');
            $table->text('return_message')->comment('微信平台返回消息');
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
        Schema::dropIfExists('wechat_platform');
    }
}
