<?php

namespace App\Src\Company\Modules\JobCard\Policies;

use App\Models\JobCard;
use App\Models\Moderator;
use App\Models\Staff;
use App\Utility\Enums\JobCardStatusTypeEnum;

class JobCardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Moderator $moderator): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Moderator $moderator, JobCard $jobCard): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Moderator $moderator): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Moderator $moderator, JobCard $jobCard): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Moderator | Staff $moderator, JobCard $jobCard): bool
    {
        return in_array($jobCard->job_status, [JobCardStatusTypeEnum::Pending->value, JobCardStatusTypeEnum::Design->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Moderator $moderator, JobCard $jobCard): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Moderator $moderator, JobCard $jobCard): bool
    {
        //
    }
}
