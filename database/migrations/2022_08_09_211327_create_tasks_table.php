<?php

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // $table->unsignedBigInteger('todo_list_id');
            $table->foreignId('todo_list_id')
                ->references('id')
                ->on('todo_lists')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('description')->nullable();
            $table->foreignId('label_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('status')->default(Task::NOT_STARTED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
