<?php

namespace App\Http\Controllers;

use App\Actions\RandomAlphaNumeric;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UniqueCodeController extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validate($request, [
                'count' => 'required|numeric|min:0|not_in:0|lte:250000',
            ]);
        } catch (ValidationException $e) {
            return response()
                ->json([
                    "message" => $e->getMessage()
                ], 400);
        }

        $result = (new RandomAlphaNumeric())->create($request->input('count'), 7);

        return response()
            ->json([
                "message" => $result
            ], 200);
    }
}
