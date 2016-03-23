<?php namespace Bedard\Webhooks\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateHooksTable extends Migration
{

    public function up()
    {
        Schema::create('bedard_webhooks_hooks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('token', 40)->unique();
            $table->string('name')->nullable();
            $table->string('directory')->nullable();
            $table->text('script')->nullable();
            $table->string('http_method', 10)->nullable();
            $table->string('executed_by')->nullable();
            $table->datetime('executed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_webhooks_hooks');
    }

}
