<?php

namespace Tshafer\Traits\Traits;

trait AttachesGravatars
{
    /**
     * Get a gravatar URL for this model, based on an attached email.
     *
     * @method  getGravatarAttribute
     *
     * @return string The gravatar URL
     */
    public function getGravatarAttribute()
    {
        // Check for a defined email field, or fallback to 'email'
        $email = $this->emailField ? $this->{$this->emailField} : $this->email;

        return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s=80&d=mm&r=g';
    }
}
