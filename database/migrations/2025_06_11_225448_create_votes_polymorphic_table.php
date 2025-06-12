<?php

use App\Enums\VoteableType;
use App\Enums\VoteType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("voteable_id");
            $table->enum("voteable_type", VoteableType::toValues());
            $table->enum("vote_type", VoteType::toValues());
            $table->unique(['user_id', 'voteable_type', 'voteable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
