<?php

namespace Database\Factories;

use App\Models\OpenOverheidDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpenOverheidDocument>
 */
class OpenOverheidDocumentFactory extends Factory
{
    protected $model = OpenOverheidDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => 'oep-'.Str::random(40),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'content' => fake()->text(500),
            'publication_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'document_type' => fake()->randomElement(['advies', 'agenda', 'besluit', 'brief', 'rapport']),
            'category' => fake()->randomElement(['jaarverslagen', 'beleidsstukken', 'onderzoeken']),
            'theme' => fake()->randomElement(['afval', 'klimaat', 'onderwijs', 'zorg', 'verkeer']),
            'organisation' => fake()->randomElement([
                'ministerie van Justitie en Veiligheid',
                'ministerie van Volksgezondheid, Welzijn en Sport',
                'ministerie van Binnenlandse Zaken en Koninkrijksrelaties',
                'ministerie van Economische Zaken en Klimaat',
            ]),
            'metadata' => [
                'document' => [
                    'titelcollectie' => [
                        'officieleTitel' => fake()->sentence(4),
                    ],
                    'verantwoordelijke' => [
                        'label' => fake()->company(),
                    ],
                    'publisher' => [
                        'label' => fake()->company(),
                    ],
                ],
                'versies' => [
                    [
                        'openbaarmakingsdatum' => fake()->date('Y-m-d'),
                        'bestanden' => [
                            [
                                'mime-type' => 'application/pdf',
                                'aantalPaginas' => fake()->numberBetween(1, 100),
                                'grootte' => fake()->numberBetween(100000, 10000000),
                            ],
                        ],
                    ],
                ],
            ],
            'synced_at' => now(),
        ];
    }

    /**
     * Indicate that the document is recent (within last week).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'publication_date' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the document is old (over a year ago).
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'publication_date' => fake()->dateTimeBetween('-5 years', '-1 year'),
        ]);
    }

    /**
     * Set a specific document type.
     */
    public function documentType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => $type,
        ]);
    }

    /**
     * Set a specific theme.
     */
    public function theme(string $theme): static
    {
        return $this->state(fn (array $attributes) => [
            'theme' => $theme,
        ]);
    }

    /**
     * Set a specific organisation.
     */
    public function organisation(string $organisation): static
    {
        return $this->state(fn (array $attributes) => [
            'organisation' => $organisation,
        ]);
    }
}
