<?php

/**
 * Game section of yahtzee layout.
 */

declare(strict_types=1);

?>

<h1>Yahtzee</h1>
<h2>Current score</h2>
<ul>
    <li>Your score: <?= $scores["human"] ?></li>
</ul>

<p>Number of rolls left: <?= $numRollsLeft ?></li>

<?php if ($currentRoll > 1) : ?>
    <p>Current die values:</p>
    <ul>
    <?php foreach ($dieVals as $i => $dieVal) : ?>
        <li>Die <?= $i + 1 ?>: <?= $dieVal ?></li>
    <?php endforeach ?>
    </ul>
<?php endif ?>

<?php if (!$roundHasEnded) : ?>
    <p>You should try to get as many <?= $goalValue ?>'s as possible now.</p>
    <?php if ($numRollsLeft > 0) : ?>
    <form action="" method="POST">
        <?php if ($currentRoll > 1) : ?>
            <p>Choose which dice to leave alone (not roll):</p>
            <fieldset>
                <?php for ($i = 0; $i < 5; $i++) : ?>
                    <input type="checkbox" id="die-<?= $i ?>" name="dice[<?= $i ?>]" value="<?= $i ?>">
                    <label for="die-<?= $i ?>">Die <?= $i + 1?></label>
                    <br>
                <?php endfor ?>
                <input name="roll-dice" id="roll-dice" type="submit" value="Roll"></input>
            </fieldset>
        <?php else : ?>
            <input name="roll-dice" id="roll-dice" type="submit" value="Roll"></input>
        <?php endif ?>


    </form>
    <?php endif ?>
    <?php if ($currentRoll > 1) : ?>
        <form action="" method="POST">
            <input name="register" id="register" type="submit" value="Register roll"></input>
        </form>
    <?php endif ?>
<?php else : ?>
    <p>Round ended!</p>
    <p>Your final score is: <?= $scores["human"] ?></p>
    <form action="" method="POST">
        <input name="new-round" id="new-round" type="submit" value="New round"></input>
    </form>
<?php endif ?>
