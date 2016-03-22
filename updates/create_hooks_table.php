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
            $table->string('name');
            $table->string('directory');
            $table->text('script');
            $table->string('executed_by');
            $table->datetime('executed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_webhooks_hooks');
    }

}
