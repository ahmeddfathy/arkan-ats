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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->enum('status', ['active', 'inactive', 'closed']);
            $table->enum('category', [
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
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
