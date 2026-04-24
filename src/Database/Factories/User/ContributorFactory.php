<?php

namespace Gingerminds\LaravelCore\Database\Factories\User;

use Gingerminds\LaravelCore\Models\User\Contributor;
use Gingerminds\LaravelCore\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contributor>
 */
class ContributorFactory extends Factory
{
    protected $model = Contributor::class;

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
        $user = User::inRandomOrder()->firstOrFail();

        return [
            'civility'  => fake()->randomElement(['mr', 'mrs']),
            'lastname'  => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'trigram'   => fake()->unique()->lexify(str_repeat('?', fake()->numberBetween(2, 3))),
            'user_id'   => $user->id,
        ];
    }
}
