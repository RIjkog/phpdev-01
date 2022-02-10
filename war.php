<?php

interface IUnit
{
    public function __construct(string $name, int $health, int $armor, int $damage);
    public function getName(): string;
    public function setName(string $name);
    public function getHealth(): int;
    public function setHealth(int $health);
    public function getArmor(): int;
    public function setArmor(int $armor);
    public function getDamage(): int;
    public function setDamage(int $damage);
}

class Unit implements IUnit
{
    private string $name = '';
    private int $health = 0;
    private int $armor = 0;
    private int $damage = 0;

    public function __construct(string $name, int $health, int $armor, int $damage)
    {
        $this->name = $name;
        $this->health = $health;
        $this->armor = $armor;
        $this->damage = $damage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function setHealth(int $health)
    {
        $this->health = $health;
    }

    public function getArmor(): int
    {
        return $this->armor;
    }

    public function setArmor(int $armor)
    {
        $this->armor = $armor;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function setDamage(int $damage)
    {
        $this->damage = $damage;
    }
}


class Units extends Unit
{
    private int $count = 0;

    public function setCount(int $count)
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}


interface IArmy
{
    public function __construct(string $name);
    public function getName(): string;
    public function setName(string $name);
    public function addUnits(Units $unit, int $count);
    public function getUnits();
    public function getArmyHealth(): int;
    public function getArmyArmor(): int;
    public function getArmyDamage(): int;
}

class Army implements IArmy
{
    private string $name = '';
    private array $units= [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function addUnits(Units $unit, int $count)
    {
        $this->units[] = $unit;
        $unit->setCount($count);
        return $this;
    }

    public function getUnits()
    {
        $result = '';
        foreach($this->units as $unit)
        {
            $result .= $unit->getName() . ' (' . $unit->getCount() . ') ';
        }
        return $result;
    }

    public function getArmyHealth(array $conditions = []): int
    {
        $health = 0;
        foreach($this->units as $unit)
        {
            $health += $unit->getHealth()* $unit->getCount();
        }
        return $health + $this->getArmyArmor($conditions);
    }

    public function getArmyArmor(array $conditions = []): int
    {
        $armor = 0;
        foreach($this->units as $unit)
        {
            if(in_array('ice', $conditions) && $unit->getName() == 'Конница') $unit->setArmor(0);
            $armor += $unit->getArmor() * $unit->getCount();
        }
        return $armor;
    }

    public function getArmyDamage(array $conditions = []): int
    {
        $damage = 0;
        foreach($this->units as $unit)
        {
            if(in_array('rain', $conditions) && $unit->getName() == 'Лучники') $unit->setDamage($unit->getDamage() * 0.5);
            $damage += $unit->getDamage() * $unit->getCount();
        }
        return $damage;
    }
}

interface IBattle
{
    public function __construct(Army $army1, Army $army2);
    public function startBattle(array $conditions);

}

class Battle implements IBattle
{
    private Army $army1;
    private Army $army2;

    public function __construct(Army $army1, Army $army2)
    {
        $this->army1 = $army1;
        $this->army2 = $army2;
    }

    public function startBattle(array $conditions = [])
    {
        $damage1 = $this->army1->getArmyDamage($conditions);
        $damage2 = $this->army2->getArmyDamage($conditions);

        $health1 = $this->army1->getArmyHealth($conditions);
        $health2 = $this->army2->getArmyHealth($conditions);

        $duration = 0;

        while($health1 > 0 && $health2 > 0 )
        {
            $health1 -= $damage2;
            $health2 -= $damage1;
            $duration++;
        }

        return [$duration, $health1, $health2];
    }
}


$infantry = new Units('Пехота', 100, 10, 10);
$archers = new Units('Лучники', 100, 5, 20);
$cavalry = new Units('Конница', 300, 30, 30);

$army1 = (new Army('Александр Ярославич'))
    ->addUnits(clone $infantry, 200)
    ->addUnits(clone $archers, 30)
    ->addUnits(clone $cavalry, 15);

$army2 = (new Army('Ульф Фасе'))
    ->addUnits(clone $infantry, 90)
    ->addUnits(clone $archers, 65)
    ->addUnits(clone $cavalry, 25);


$battle = new Battle($army1, $army2);
list($resultDuration, $resultHealth1, $resultHealth2) = $battle->startBattle(['ice', 'rain']);    

?>

<table border="1">
    <tr>
        <th></th>
        <th><?=$army1->getName()?></th>
        <th><?=$army2->getName()?></th>
    </tr>
    <tr>
        <th>Army units:</th>
        <td><?= $army1->getUnits()?></td>
        <td><?= $army2->getUnits()?></td>
    </tr>
    <tr>
        <th>Health after <?=$resultDuration?> hits:</th>
        <td><?=$resultHealth1?></td>
        <td><?=$resultHealth2?></td>
    </tr>
    <tr>
        <th>Result</th>
        <td><?=$resultHealth1 > $resultHealth2 ? 'WINNER' : 'LOOSER'?></td>
        <td><?=$resultHealth2 > $resultHealth1 ? 'WINNER' : 'LOOSER'?></td>
    </tr>
</table>