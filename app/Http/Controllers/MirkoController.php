<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Services\MirkoService;

class MirkoController extends Controller
{
    /**
     * Return the list of entries.
     *
     * @return mixed
     */
    public function index()
    {
        $entries = MirkoService::index();
        
        return EntryResource::collection($entries);
    }

    /**
     * Store a newly created entry.
     *
     * @param EntryRequest $request
     * @return EntryResource
     */
    public function store(EntryRequest $request)
    {
        $entry = MirkoService::store($request);
        return new EntryResource($entry);
    }

    /**
     * Return the specified entry.
     *
     * @param Entry $entry
     * @return EntryResource
     */
    public function show(Entry $entry)
    {
        return new EntryResource($entry);
    }

    /**
     * Update the specified entry.
     *
     * @param EntryRequest $request
     * @param Entry $entry
     * @return EntryResource
     */
    public function update(EntryRequest $request, Entry $entry)
    {
        $this->authorize('update', $entry);
        
        $entry = MirkoService::update($request, $entry);

        return new EntryResource($entry);
    }

    /**
     * Remove the specified entry.
     *
     * @param  Entry $entry
     * @return void
     */
    public function destroy(Entry $entry)
    {
        $this->authorize('delete', $entry);

        MirkoService::delete($entry);
    }

    /**
     * Plus the specified entry.
     *
     * @param Entry $entry
     * @return void
     */
    public function plus(Entry $entry)
    {
        $this->authorize('plus', $entry);

        MirkoService::plus($entry);
    }

    /**
     * Unplus the specified entry.
     *
     * @param Entry $entry
     * @return void
     */
    public function unPlus(Entry $entry)
    {
        $this->authorize('unPlus', $entry);

        MirkoService::unPlus($entry);
    }
}
