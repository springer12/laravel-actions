<?php

namespace Lorisleiva\Actions\Tests\Actions;

use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Tests\Stubs\User;

class UpdateProfilePicture extends Action
{
    public function authorize(User $user)
    {
        return $user->role !== 'cannot_update_avatar';
    }

    public function rules()
    {
        return [
            'avatar' => 'not_in:invalid_avatar',
        ];
    }

    public function handle(User $user, $avatar)
    {
        $user->avatar = $avatar;

        return "UpdateProfilePicture ran as {$this->runningAs}";
    }
}