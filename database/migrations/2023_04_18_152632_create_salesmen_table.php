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
        Schema::create('salesmen', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->enum('titles_before', [
                'Bc.', 'Mgr.', 'Ing.', 'JUDr.', 'MVDr.', 'MUDr.', 'PaedDr.', 'prof.', 'doc.', 'dipl.', 'MDDr.', 'Dr.',
                'Mgr. art.', 'ThLic.', 'PhDr.', 'PhMr.', 'RNDr.', 'ThDr.', 'RSDr.', 'arch.', 'PharmDr.'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesmen');
    }
};
