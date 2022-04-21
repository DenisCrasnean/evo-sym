<?php

namespace App\Factory;

use App\Entity\Programme;
use App\Repository\ProgrammeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Programme>
 *
 * @method static          Programme|Proxy createOne(array $attributes = [])
 * @method static          Programme[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static          Programme|Proxy find(object|array|mixed $criteria)
 * @method static          Programme|Proxy findOrCreate(array $attributes)
 * @method static          Programme|Proxy first(string $sortedField = 'id')
 * @method static          Programme|Proxy last(string $sortedField = 'id')
 * @method static          Programme|Proxy random(array $attributes = [])
 * @method static          Programme|Proxy randomOrCreate(array $attributes = [])
 * @method static          Programme[]|Proxy[] all()
 * @method static          Programme[]|Proxy[] findBy(array $attributes)
 * @method static          Programme[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static          Programme[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static          ProgrammeRepository|RepositoryProxy repository()
 * @method Programme|Proxy create(array|callable $attributes = [])
 */
final class ProgrammeFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $startTime = new \DateTimeImmutable('now');

        return [
            'name' => self::faker()->text(),
            'description' => self::faker()->text(),
            'startTime' => self::faker()->dateTimeBetween($startTime->format('Y-m-d H:i:s'), '+1 week'),
            'endTime' => self::faker()->dateTimeBetween($startTime->format('Y-m-d H:i:s'), '+3 days'),
            'maxParticipants' => self::faker()->numberBetween(10, 30),
            'isOnline' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (Programme $programme) {
                $startTime = new \DateTimeImmutable($programme->getStartTime()->format('Y-m-d H:i:s'));
                $end = $programme->setStartTime($programme->getStartTime()->add());
                $endTime = self::faker()->dateTimeBetween($startTime->format('Y-m-d H:i:s'), $end);
                $programme->setEndTime(new \DateTime($endTime->format('Y-m-d H:i:s')));
            });
    }

    protected static function getClass(): string
    {
        return Programme::class;
    }
}
