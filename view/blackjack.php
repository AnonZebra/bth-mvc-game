<?php

/**
 * Game section of blackjack layout.
 */

declare(strict_types=1);

?>

<h1>Blackjack with Dice</h1>
<h2>Antal vunna rundor</h2>
<ul>
    <li>Du: <?= $wonRounds["human"] ?></li>
    <li>Datorn: <?= $wonRounds["computer"] ?></li>
</ul>
<h2>Pågående runda</h2>
<ul>
    <li>Din poäng: <?= $scores["human"] ?>
        <?php if ($scores["human"] === 21) :
            ?> Grattis!
        <?php endif ?>
    </li>
    <li>
        Datorns poäng: <?= $scores["computer"]?>
    </li>
</ul>

<?php if (!$roundHasEnded) : ?>
    <form action="" method="POST">
        <input name="roll-dice" id="roll-dice" type="submit" value="Rulla"></input>
    </form>

    <form action="" method="POST">
        <input name="stay" id="stay" type="submit" value="Stanna"></input>
    </form>
<?php else : ?>
    <p>Runda slut!</p>
    <p>Vinnare är: <?= $winner ?></p>
    <form action="" method="POST">
        <input name="new-round" id="new-round" type="submit" value="Ny runda"></input>
    </form>
<?php endif ?>

<h2>Starta nytt spel</h2>
<form action="" method="POST">
    <label for="num-dice">Antal tärningar:</label>
    <input name="num-dice" id="num-dice" type="number"></input>
    <input name="start-new" id="start-new" type="submit" value="Starta nytt spel"></input>
</form>
