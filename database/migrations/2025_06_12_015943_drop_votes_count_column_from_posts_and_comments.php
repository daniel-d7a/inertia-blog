<?php

use App\Enums\VoteType;
use App\Models\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts_and_comments', function (Blueprint $table) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('votes_count');
            });

            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('votes_count'); // or 'vote_count', 'votes_total' etc.
            });

            Schema::dropIfExists('comment_votes');
            Schema::dropIfExists('post_votes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts_and_comments', function (Blueprint $table) {
            Schema::table('posts', function (Blueprint $table) {
                $table->integer('votes_count')->default(0);
            });

            Schema::table('comments', function (Blueprint $table) {
                $table->integer('votes_count')->default(0);
            });
        });

        Schema::create('comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Comment::class);
            $table->unique(['comment_id', 'user_id']);
            $table->enum('vote_type', VoteType::toValues());
            $table->timestamps();
        });

        Schema::create('post_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Post::class);
            $table->unique(['post_id', 'user_id']);
            $table->enum('vote_type', VoteType::toValues());
            $table->timestamps();
        });
    }
};
