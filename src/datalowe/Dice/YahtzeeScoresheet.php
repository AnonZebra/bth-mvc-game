<?php

namespace dtlw\Dice;

use dtlw\Dice\InvalidCategoryException;
use dtlw\Dice\FullSheetException;

/**
* Represents simplified yahtzee game score sheet.
* @author Lowe Wilsson
*/
class YahtzeeScoresheet
{
    private array $categoryScores = [
        '1' => null,
        '2' => null,
        '3' => null,
        '4' => null,
        '5' => null,
        '6' => null
    ];

    public function __construct()
    {
    }

    /**
    * @param string $categoryName Name of category to set score for.
    * @param int $score Score which category should be assigned.
    */
    public function setCategoryScore(string $categoryName, int $score): void
    {
        if (!array_key_exists($categoryName, $categoryScores)) {
            throw new InvalidCategoryException();
        }
        $this->categoryScores[$categoryName] = $score;
    }

    /**
    * @return int Sum of all categories' scores.
    */
    public function getTotalScore(): int
    {
        return array_reduce(
            $this->categoryScores,
            fn($val1, $val2) => $val1 + $val2
        );
    }

    /**
    * @return array An array of category-score pairs
    */
    public function getCategoryScores(): int
    {
        return $this->categoryScores;
    }

    /**
    * Gets the name of the last-positioned category which has
    * no set score.
    * @return string
    */
    public function getScoreLessCategoryName(): string
    {
        foreach (array_reverse($this->categoryScores) as $catName => $val) {
            if (is_null($val)) {
                return $catName;
            }
        }
        throw new FullSheetException("The sheet has already been filled");
    }

    /**
    * Indicates if the the sheet has been fully filled out or not.
    * @return bool true if sheet has been filled out, otherwise false.
    */
    public function isFilledOut(): bool
    {
        foreach ($this->categoryScores as $catName => $val) {
            if (is_null($val)) {
                return false;
            }
        }
        return true;
    }
}
