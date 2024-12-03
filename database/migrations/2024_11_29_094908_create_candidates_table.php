<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->string('cv');
            $table->integer('experience');
            $table->text('skills');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table -> enum('category' , [
                'IT',
                'Graphic Design',
                'Marketing',
                'Social Media',
                'Content Writing',
                'Customer Service',
                'Data Entry',
                'Digital Marketing',
                'Accounting',
                'Sales',
                'Engineering',
                'HR',
                'Software Development',
                'Technical Support',
            ]);
            $table->string('is_process')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
