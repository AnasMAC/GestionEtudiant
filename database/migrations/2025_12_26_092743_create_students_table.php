<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Foreign Key linking to the Users table
            // onDelete('cascade') means if we delete the User, the Student file is deleted too.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('cne')->unique();
            $table->string('filiere'); // GINF, GIND...
            // Note: 'nom', 'prenom', 'email' are already in the 'users' table, 
            // so we don't strictly need them here unless you want to duplicate data.
            // For a clean design, we usually keep Name/Email in Users and CNE/Filiere here.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
