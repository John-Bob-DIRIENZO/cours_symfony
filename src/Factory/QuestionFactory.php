<?php

namespace App\Factory;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\UploadHelper;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Question>
 *
 * @method static Question|Proxy createOne(array $attributes = [])
 * @method static Question[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Question|Proxy find(object|array|mixed $criteria)
 * @method static Question|Proxy findOrCreate(array $attributes)
 * @method static Question|Proxy first(string $sortedField = 'id')
 * @method static Question|Proxy last(string $sortedField = 'id')
 * @method static Question|Proxy random(array $attributes = [])
 * @method static Question|Proxy randomOrCreate(array $attributes = [])
 * @method static Question[]|Proxy[] all()
 * @method static Question[]|Proxy[] findBy(array $attributes)
 * @method static Question[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Question[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestionRepository|RepositoryProxy repository()
 * @method Question|Proxy create(array|callable $attributes = [])
 */
final class QuestionFactory extends ModelFactory
{
    private static $randomImages = [
        'random1.jpg',
        'random2.jpg',
        'random3.jpg',
        'random4.jpg',
    ];
    private UploadHelper $helper;

    public function __construct(UploadHelper $helper)
    {
        parent::__construct();
        $this->helper = $helper;
    }

    public function notPublished(): self
    {
        // Me permet de changer la valeur d'un attribut
        // par rapport à la valeur par défaut
        return $this->addState(['askedAt' => null]);
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->realText(40),
            'question' => self::faker()->paragraphs(rand(1, 4), true),
            'votes' => rand(-20, 50),
            'askedAt' => self::faker()->dateTimeBetween('-100 days', '-1 second'),
            'user' => UserFactory::random(),
            'tag' => TagFactory::randomRange(1, 6),

            'imageFilename' => $this->helper->fixtureUpload(
                new File(__DIR__.'/Randoms/'.self::faker()->randomElement(self::$randomImages))
            )
        ];
    }

    protected function initialize(): self
    {
        return $this
//            ->afterInstantiate(function(Question $question) {
//            // L'utilitaire de slug de Symfony
//            $slugger = new AsciiSlugger();
//            $question->setSlug($slugger->slug($question->getName()));
//        })
            ;
    }

    protected static function getClass(): string
    {
        return Question::class;
    }
}
