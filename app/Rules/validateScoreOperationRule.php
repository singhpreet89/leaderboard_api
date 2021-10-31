<?php

namespace App\Rules;

use App\Models\Game;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class validateScoreOperationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->userId = $user->id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $game = Game::where('user_id', $this->userId)->first();
        
        if(!is_null($game) && (int) $game->points === 0 && $value === "subtraction") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "The subtraction operation is not permitted with 0 points.";
    }
}
