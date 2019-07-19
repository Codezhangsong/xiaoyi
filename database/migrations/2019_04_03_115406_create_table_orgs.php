<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orgs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('org_name', 30)->comment('机构名');
            $table->string('org_code', 255)->comment('机构code');
            $table->string('openId', 255)->nullable()->comment('机构code');
            $table->string('secret', 255)->nullable()->comment('机构code');
            $table->string('pwd', 255)->comment('机构密码');
            $table->string('linkman', 30)->comment('机构联系人姓名');
            $table->bigInteger('mobile')->comment('机构联系人电话');
            $table->string('address')->comment('机构地址');
            $table->string('distinction')->comment('校区');
            $table->string('desc')->comment('机构描述');
            $table->string('pic_url')->comment('机构封面图片 url');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->tinyInteger('is_del')->default(1)->comment('未删除 1 删除 2');
            $table->tinyInteger('use_flag')->default(1)->comment('启用 1 未启用 2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orgs');
    }
}
