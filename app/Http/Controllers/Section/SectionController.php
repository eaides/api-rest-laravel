<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\ApiController;
use App\Transformers\SectionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Section;

class SectionController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['index','show']);

        $this->middleware('transform.input:'.SectionTransformer::class)
            ->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sections = Section::all();

        return $this->showAll($sections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        // if need fix any data do:     $data['xxx'] = 'zzz';

        /** @var Model $section */
        $section = Section::create($data);

        return $this->showOne($section, 201);
    }

    /**
     * Display the specified resource.
     *
     * deprecated @param  int  $id
     * @param Section $section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Section $section)
    {
        // /** @var Section $section */
        // $section = Section::findOrFail($id);

        return $this->showOne($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * deprecated @param  int  $id
     * @param Section $section
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Section $section)
    {
        // /** @var Section $section */
        // $section = Section::findOrFail($id);

        $rules = [
            'name' => ['string', 'max:255'],
        ];

        $this->validate($request, $rules);

        if ($request->has('name'))
        {
            $section->name = $request->name;
        }

        if (!$section->isDirty())
        {
            return $this->errorUpdateNoChanges();
        }

        $section->save();

        return $this->showOne($section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * deprecated @param $id
     * @param Section $section
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(Section $section )
    {
        // /** @var Section $section */
        // $section = Section::findOrFail($id);

        $section->delete();

        return $this->showOne($section);
    }
}
