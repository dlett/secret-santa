<?php

namespace App\Http\Controllers;

use App\Group;
use App\Jobs\CreateMemberConnections;
use App\Member;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GroupController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * GroupController constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param Dispatcher      $dispatcher
     */
    public function __construct(ResponseFactory $responseFactory, Dispatcher $dispatcher)
    {
        $this->responseFactory = $responseFactory;
        $this->dispatcher = $dispatcher;
    }

    public function store(Request $request)
    {
        $group = new Group($request->all());
        $group->save();

        $memberData = collect($request->input('members'));
        $members = $this->createGroupMembers($memberData, $group);

        $memberData->each(
            function (array $values) use ($members) {
                /** @var Member $member */
                $member = $members->where('name', $values['name'])->first();
                $unconnectables = collect($values['unconnectables'] ?? [])->map(
                    function ($unconnectable) use ($members) {
                        return $members->where('name', $unconnectable)->first()->id;
                    }
                );
                $member->unconnectables()->sync($unconnectables);
            }
        );

        $this->dispatcher->dispatch(new CreateMemberConnections($group));

        return $this->responseFactory->json(
            ['code' => 'success', 'message' => 'Successfully created group.']
        );
    }

    /**
     * @param Collection $memberData
     * @param Group      $group
     *
     * @return Collection
     */
    private function createGroupMembers(Collection $memberData, Group $group): Collection
    {
        return $memberData->map(
            function (array $values) use ($group) {
                return tap((new Member($values))->group()->associate($group))->save();
            }
        );
    }

    public function show(Group $group, Request $request)
    {
        return view('enter-name', compact('group'));
    }
}
