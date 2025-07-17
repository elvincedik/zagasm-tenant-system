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
        // Only add column if it doesn't already exist
        if (!Schema::hasColumn('clients', 'organization_id')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')->after('id')->nullable(); // make it nullable

                // Optional: add foreign key constraint
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            });
        }
    }

    // down()
    public function down()
    {
        // Only drop column if it exists
        if (Schema::hasColumn('clients', 'organization_id')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            });
        }
    }
};