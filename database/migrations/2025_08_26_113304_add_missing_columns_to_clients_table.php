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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('code_postal')->nullable()->after('postal_code');
            $table->string('pays')->nullable()->after('code_postal');
            $table->date('date_obtention_permis')->nullable()->after('date_permis');
            $table->string('numero_piece_identite')->nullable()->after('date_passport');
            $table->string('type_piece_identite')->nullable()->after('numero_piece_identite');
            $table->date('date_expiration_piece')->nullable()->after('type_piece_identite');
            $table->string('profession')->nullable()->after('date_expiration_piece');
            $table->string('employeur')->nullable()->after('profession');
            $table->decimal('revenu_mensuel', 10, 2)->nullable()->after('employeur');
            $table->text('notes')->nullable()->after('description');
            $table->boolean('is_blacklist')->default(false)->after('is_blacklisted');
            $table->string('motif_blacklist')->nullable()->after('is_blacklist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'code_postal',
                'pays',
                'date_obtention_permis',
                'numero_piece_identite',
                'type_piece_identite',
                'date_expiration_piece',
                'profession',
                'employeur',
                'revenu_mensuel',
                'notes',
                'is_blacklist',
                'motif_blacklist',
            ]);
        });
    }
};
