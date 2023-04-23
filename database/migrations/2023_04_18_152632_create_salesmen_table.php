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
            $table->uuid();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->enum('titles_before', [
                'Bc.', 'Mgr.', 'Ing.', 'JUDr.', 'MVDr.', 'MUDr.', 'PaedDr.', 'prof.', 'doc.', 'dipl.', 'MDDr.', 'Dr.',
                'Mgr. art.', 'ThLic.', 'PhDr.', 'PhMr.', 'RNDr.', 'ThDr.', 'RSDr.', 'arch.', 'PharmDr.'
            ])->nullable();
            $table->enum('titles_after', [
                'CSc.', 'DrSc.', 'PhD.', 'ArtD.', 'DiS', 'DiS.art', 'FEBO', 'MPH', 'BSBA', 'MBA', 'DBA', 'MHA',
                'FCCA', 'MSc.', 'FEBU', 'LL.M'
            ])->nullable();
            $table->string('prosight_id', 5)->unique();
            $table->string('email', 150)->unique();
            $table->string('phone', 50)->nullable();
            $table->enum('gender', ['m', 'f']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
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
