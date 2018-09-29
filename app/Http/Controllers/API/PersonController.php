<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;
use App\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $people = Person::all();
        return PersonResource::collection($people);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\PersonResource
     */
    public function store(Request $request): PersonResource
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
        ]);

        $person = Person::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
        ]);

        return new PersonResource($person);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\PersonResource
     */
    public function show(int $id): PersonResource
    {
        $person = Person::findOrFail($id);
        return new PersonResource($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Http\Resources\PersonResource
     */
    public function update(Request $request, int $id): PersonResource
    {
        $person = Person::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
        ]);

        $person->fill([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
        ]);
        $person->save();

        return new PersonResource($person);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json([]);
    }
}
