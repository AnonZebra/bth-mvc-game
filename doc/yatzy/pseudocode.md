# Pseudocode for Yahtzee game
CREATE players and dice
SET flow control variables
WHILE (numRounds) < (totalNumRounds)
    WHILE not all players have played
        GET next player
        WHILE numRolls < 3
            player rolls
            BUMP numRolls
            IF (numRolls < 3)
                player decides which dice to keep
            ELSE
                all dice are kept
            ENDIF
            IF (numDiceKept == maxNumDice)
                BREAK
            ENDIF
        ENDWHILE
        SET validInput to false
        player decides which kind of die values to save
    ENDWHILE
    BUMP numRounds
ENDWHILE
