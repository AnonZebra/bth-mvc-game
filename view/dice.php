<?php

/**
 * Dice game section of dice layout.
 */

declare(strict_types=1);

$die = new dtlw\Dice\Dice(6);

$gDie = new dtlw\Dice\GraphicalDice();

$diceHand = new dtlw\Dice\DiceHand(2);
$diceHand->roll();

?><h1>Dicey dice</h1>

<p>Normal die: <?=  $die->roll() ?></p>
<p>Graphical die: <?=  $gDie->roll() ?></p>
<img class="die-img" src="<?=  $gDie->getFaceImg() ?>">
<!-- <p>Graphical die: </p> -->
<p>Base url: <?= Mos\Functions\getBaseUrl() ?></p>
<p>rand(0, 1): <?= rand(0, 1) ?></p>

<?php foreach ($diceHand->getFaceImgs() as $faceImg) : ?>
    <img class="die-img" src="<?=  $faceImg ?>">
<?php endforeach; ?>
<p>Hand total: <?= $diceHand->getRollTotal(); ?> </p>
