<?php

use App\Models\Lesson;
use App\Models\Slide;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * @var Faker
     */
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     *
     */
    public function run()
    {
        $faker = $this->faker;
        Lesson::get()->each(function($lesson) use($faker) {
            for($i = 0; $i <= rand(1, 3); $i++) {
                $type = $faker->randomElement(Slide::TYPES);
                /** @var Slide $slide */
                $slide = Slide::create([
                    'lesson_id' => $lesson->id,
                    'name' => $faker->company,
                    'type' => $type,
                    'content' => $this->getContentBasedOnType($type),
                ]);
                $slide
                    ->addMediaFromUrl('https://picsum.photos/seed/picsum/500/500')
                    ->toMediaCollection();
            }
        });
    }

    private function getContentBasedOnType($type)
    {
        return $type == Slide::TYPE_HTML
            ? $this->getHtmlContent()
            : $this->getJsonContent();
    }

    private function getHtmlContent()
    {
        return $this->faker->realText();
    }

    private function getJsonContent()
    {
        return json_encode([
            'question' => $this->faker->sentence,
            'answer' => [
                $this->faker->words(rand(1, 3), true),
                $this->faker->words(rand(1, 2), true),
                $this->faker->words(1, true),
            ],
            'valid_answer' => rand(0, 2)
        ]);
    }
}
