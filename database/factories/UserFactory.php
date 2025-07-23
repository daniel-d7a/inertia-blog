<?php

namespace Database\Factories;

use App\Enums\UserType;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'type' => UserType::USER->value,
            'remember_token' => Str::random(10),
            "display_name" => fake()->name(),
            'bio' => fake()->paragraph(3),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            "type" => UserType::ADMIN->value,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {

            // followers
            $users = User::where('id', '!=', $user->id)->inRandomOrder()->take(rand(0, 5))->get();
            $user->followers()->attach($users);

            // interests
            $tags = Tag::inRandomOrder()->take(rand(1, 5))->get();
            $user->interests()->attach($tags);

            // links
            UserLink::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}