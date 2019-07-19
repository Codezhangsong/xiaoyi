
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStatMarketing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_activity', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id')->comment('id');
            $table->string('org_code',255)->nullable()->comment('机构端id');

            $table->bigInteger('total_UV')->default(0)->comment('累计UV数');
            $table->bigInteger('total_PV')->default(0)->comment('累计访问量');
            $table->bigInteger('total_number')->default(0)->comment('累计访问人数');
            $table->decimal('avg_bounce',2,2)->nullable()->comment('平均跳出率');
            $table->bigInteger('total_minutes')->default(0)->comment('累计访问分钟数');
            $table->date('stat_date');
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
        Schema::dropIfExists('stat_activity');
    }
}
