<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Slide
 * @package App\Models
 * @property-read Media image
 * @property-read string imageUrl
 * @property-read string question
 * @property-read array answers
 * @property-read int validAnswer
 * @property int lesson_id
 * @property string name
 * @property string type
 * @property string content
 * @property Lesson lesson
 * @property int position
 */
class Slide extends Model implements HasMedia
{
    public const TYPE_HTML = 'html';

    public const TYPE_JSON = 'json';

    const TYPE_VIDEO = 'video';

    public const TYPES = [self::TYPE_VIDEO, self::TYPE_HTML, self::TYPE_JSON];

    protected $fillable = ['lesson_id', 'name', 'type', 'content', 'video_url', 'position', 'correct_answer_message', 'incorrect_answer_message', 'bg_color', 'bg_image'];

    /**
     * @var array
     */
    public static $formFields = [
        'name' => [
            'label' => 'Slide Name',
            'required' => true,
        ],
        'bg_color' => [
            'label' => 'Background color',
            'placeholder' => '#cccccc',
        ],
//        'position' => [
//            'label' => 'Position',
//            'placeholder' => '100',
//        ],
        'image' => [
            'label' => 'Background Image',
            'type' => 'file',
        ],
        'type' => [
            'label' => 'Slide Type',
            'type' => 'radio',
            'values' => self::TYPES
        ],
    ];

    use HasMediaTrait, HasImage {
        HasImage::registerMediaConversions insteadof HasMediaTrait;
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @param $value
     * @return array|string
     */
    public function getContentAttribute($value)
    {
        if($this->type == self::TYPE_JSON) {
            $value = json_decode($value, true);
        }

        return  $value;
    }

    public function getQuestionAttribute(): string
    {
        return $this->type == self::TYPE_JSON && !empty($this->content)
            ? (string) $this->content['question']
            : '';
    }

    public function getAnswersAttribute(): array
    {
        $default = ['', '', '', '', ''];
        if($this->type != self::TYPE_JSON || !array_key_exists('answer', (array) $this->content)) {
            return  $default;
        }

        return array_slice(array_merge($this->content['answer'] + $default), 0, 5);
    }

    public function getValidAnswerAttribute(): int
    {
        return $this->type == self::TYPE_JSON && !empty($this->content)
            ? (int) $this->content['valid_answer']
            : 0;
    }

    public function getVideoUrl()
    {
        /** @var Media $media */
        $media = $this->getFirstMedia('video');

        return optional($media)->getUrl();
    }

    public function prevSlide()
    {
        $slide = $this->lesson->rawSlides()->where('position', '<', $this->position)
            ->orderBy('position', 'desc')->first();
        if($slide) {
            return $slide;
        }

        return $this->lesson->rawSlides()->where('id', '<', $this->id)
            ->orderBy('id', 'desc')->first();
    }

    public function nextSlide()
    {
        $slide = $this->lesson->slides()->where('position', '>', $this->position)->first();
        if($slide) {
            return $slide;
        }

        if($this->position == 100) {
            return $this->lesson->slides()->where('id', '>', $this->id)->first();
        }

        return null;
    }
}
