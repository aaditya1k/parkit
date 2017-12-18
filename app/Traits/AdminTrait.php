<?php

namespace App\Traits;
use Validator;

trait AdminTrait
{
    private function valid($request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }

        return true;
    }

    private function redirectWithError($errors = [])
    {
        return redirect()->back()
                         ->withErrors($errors)
                         ->withInput();
    }
}
