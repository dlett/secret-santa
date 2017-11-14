<?php

namespace App\Jobs;

use App\Group;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateMemberConnections implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Group
     */
    private $group;

    /**
     * Create a new job instance.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @param Dispatcher $dispatcher
     *
     * @return void
     */
    public function handle(Dispatcher $dispatcher)
    {
        // First loop through the group members that have unconnectables
        $this->group->members()->has('unconnectables')->each(
            function (Member $member) {
                $connection = $this->group->members()
                    ->where('id', '<>', $member->getKey())
                    ->whereNotIn('id', $member->unconnectables()->pluck('id'))
                    ->doesntHave('connected')
                    ->inRandomOrder()
                    ->first();

                $member->connection()->associate($connection);
                $member->save();
            }
        );

        // Then loop through anyone remaining
        $this->group->members()->whereNull('connection_id')->each(
            function (Member $member) {
                $connection = $this->group->members()
                    ->where('id', '<>', $member->getKey())
                    ->doesntHave('connected')
                    ->inRandomOrder()
                    ->first();

                $member->connection()->associate($connection);
                $member->save();
            }
        );

        // If someone didn't get attached, we'll retry.
        if ($this->group->members()->whereNull('connection_id')->count() > 0) {
            $this->group->members()->update(['connection_id' => null]);
            $dispatcher->dispatch(new static($this->group));
        }
    }
}
