<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->string('code')->nullable()->comment('课程code（必须唯一且和添翼平台现有不能重复）');
            $table->string('class_id')->nullable()->comment('课程平台id');
            $table->string('course_name', 50)->comment('课程名称');
            $table->string('account', 50)->nullable()->comment('账号');
            $table->string('teacher_name', 50)->comment('教师名称');
            $table->string('teacher_id', 50)->nullable()->comment('教师天翼平台id');
            $table->text('teacher_info')->nullable()->comment('教师资料');
            $table->text('desc')->nullable()->comment('课程描述');
            $table->text('class_type')->nullable()->comment('课程类型 枚举 学科教育 素质教育 国际教育');
            $table->string('class_tag')->nullable()->comment('课程标签');
            $table->string('class_difficult')->nullable()->comment('课程难度');
            $table->text('cover_img')->nullable()->comment('课程封面'); //图片地址
            $table->text('course_introduce_img')->nullable()->comment('课程主图');//图片地址
            $table->Integer('course_num')->default(6)->comment('课时数');
            $table->decimal('class_hour',10,0)->nullable()->comment('课时');
            $table->text('course_consultant')->nullable()->comment('课程顾问');
            $table->text('course_prompt')->nullable()->comment('课程描述');
            $table->text('course_obj')->nullable()->comment('课程目标 json字符串');
            $table->text('course_feature')->nullable()->comment('课程特色');
            $table->text('course_content')->nullable()->comment('课程内容');
            $table->string('class', 30)->default('暂无')->comment('学生年级');
            $table->string('textbook', 50)->default('暂无')->comment('讲义/教材');
            $table->decimal('price',8,2)->comment('课程价格');
            $table->Integer('PV')->default(0)->comment('点击量');
            $table->Integer('UV')->default(0)->comment('访问量');
            $table->decimal('total_BR',2,2)->nullable()->comment('跳出率');
            $table->tinyInteger('is_del')->default(1);
            $table->tinyInteger('is_show')->default(1);
            $table->tinyInteger('status')->default(1)->comment('1待审核 2未通过 3已下架 4已上架 5审核中');
            $table->date('reg_date')->comment('课程创建日期');
            $table->dateTime('start_date')->nullable()->comment('课程开始日期');
            $table->dateTime('end_date')->nullable()->comment('课程结束日期');
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
        Schema::dropIfExists('course');
    }
}
