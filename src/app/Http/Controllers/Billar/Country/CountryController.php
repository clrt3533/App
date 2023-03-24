<?php

namespace App\Http\Controllers\Billar\Country;

use App\Filters\Billar\Category\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Models\Billar\Country\Country;
use App\Services\Billar\Country\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    public function index()
    {
        return Country::query()
            ->filters(new CategoryFilter())
            ->select('id', 'code', 'name')
            ->paginate(request('per_page', 10));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
