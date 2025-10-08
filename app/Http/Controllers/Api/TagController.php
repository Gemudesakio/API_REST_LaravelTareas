<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success'=> true,
            'data'   => TagResource::collection(Tag::orderBy('name')->get())
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:50','unique:tags,name'],
        ]);

        $tag = Tag::create($data);

        return response()->json(['success'=>true,'data'=> new TagResource($tag)], 201);
    }
}
