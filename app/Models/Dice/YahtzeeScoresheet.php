<?php

namespace App\Models\Dice;

/**
* Represents simplified yahtzee game score sheet.
* @author Lowe Wilsson
*/
class YahtzeeScoresheet
{
    private const BONUS_THRESHOLD = 63;
    private const BONUS_POINTS = 50;

    private array $categoryScores = [
        '6' => null,
        '5' => null,
        '4' => null,
        '3' => null,
        '2' => null,
        '1' => null
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
        if (!array_key_exists($categoryName, $this->categoryScores)) {
            throw new InvalidCategoryException();
        }
        $this->categoryScores[$categoryName] = $score;
    }

    /**
    * @return int Sum of all categories' scores.
    */
    public function getTotalScore(): int
    {
        $totalScore = array_reduce(
            $this->categoryScores,
            fn($val1, $val2) => intval($val1) + intval($val2)
        );
        // add bonus
        if ($totalScore >= self::BONUS_THRESHOLD) {
            $totalScore += self::BONUS_POINTS;
        }
        return $totalScore;
    }

    /**
    * @return array An array of category-score pairs
    */
    public function getCategoryScores(): array
    {
        return $this->categoryScores;
    }

    /**
    * Gets the name of the first-positioned category which has
    * no set score.
    * @return string
    */
    public function getScoreLessCategoryName(): string
    {
        foreach ($this->categoryScores as $catName => $val) {
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

    /**
    * Resets this score sheet.
    */
    public function reset(): void
    {
        for ($i = 0; $i < count($this->categoryScores); $i++) {
            $this->categoryScores[$i + 1] = null;
        }
    }
}
