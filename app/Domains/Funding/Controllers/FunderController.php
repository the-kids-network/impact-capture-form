<?php

namespace App\Domains\Funding\Controllers;

use App\Domains\Funding\Models\Funder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FunderController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {
        $funders = Funder::canSee();

        if (filter_var($request->deactivated, FILTER_VALIDATE_BOOLEAN)) {
            $funders->withTrashed();
        }

        return view('funding.funder.index')
            ->with('funders', $funders->get());
    }

    public function store(Request $request) {
        $request->validate([
            'code' => 'required|string',
        ]);

        $funder = new Funder();
        $funder->code = $request->code;
        $funder->description = $request->description;
        $funder->save();

        return redirect('/funders')->with('status','Funder Added');
    }

    public function destroy(Request $request, $id) {
        if ($request->really_delete){
            $funder = Funder::withTrashed()->where('id', $id)->withCount('fundings')->first();
            if ($funder->fundings_count > 0 ) {
                $status = 'Funder cannot be deleted (since it is assigned to a mentor).';
            } else {
                Funder::withTrashed()->where('id',$id)->forceDelete();
                $status = 'Funder deleted.';
            }

        } else {
            $funder = Funder::find($id);
            $funder->delete();
            $status = 'Funder deactivated.';
        }

        return redirect('/funders')->with('status', $status);
    }

    public function restore($id) {
        Funder::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/funders')->with('status','Funder restored.');
    }
}
