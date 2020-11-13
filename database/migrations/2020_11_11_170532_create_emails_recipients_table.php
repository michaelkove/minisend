<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('recipient_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status',['posted','sent','failed'])->default('posted');
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
        Schema::dropIfExists('emails_recipients');
    }
}
