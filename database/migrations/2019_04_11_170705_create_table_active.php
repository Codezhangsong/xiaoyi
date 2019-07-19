<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');
            $table->string('name')->comment('活动名称');
            $table->tinyInteger('type')->comment('活动类型 1文章类 2活动类');
            $table->string('class')->comment('活动类目名称');
            $table->tinyInteger('classId')->comment('活动类目ID');
            $table->string('channel')->comment('渠道');
            $table->text('content')->nullable()->comment('html content');
            $table->text('rule')->nullable()->comment('json rule');
            $table->tinyInteger('online')->comment('1 线上 2 线下');
            $table->tinyInteger('status')->default(1)->comment('审核状态 1未审核 2审核通过 3未通过 4 已下架');
            $table->integer('PV')->default(0)->comment('点击量');
            $table->integer('UV')->default(0)->comment('独立访客');
            $table->integer('IP')->default(0)->comment('ip数');
            $table->integer('sign_up_num')->default(0)->comment('报名人数');
            $table->integer('student_num')->default(0)->comment('学生参与人数');
            $table->integer('parent_num')->default(0)->comment('家长参与人数');
            $table->integer('bounce_rate')->default(0)->comment('跳出率');
            $table->integer('stay_minutes')->default(0)->comment('页面停留时间');
            $table->tinyInteger('is_del')->default(1)->comment('1 未删除 2 删除');
            $table->tinyInteger('is_show')->default(1)->comment('1 展示 2 不展示');
            $table->string('creator')->comment('发布人');
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
        Schema::dropIfExists('activity');
    }
}
