<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectBidsTable extends Migration
{
    public function up()
    {
        Schema::create('project_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // References the projects table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // References the users table
            $table->decimal('bid_amount', 10, 2);
            $table->string('status')->default('pending'); // Example statuses: pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_bids');
    }
}
    