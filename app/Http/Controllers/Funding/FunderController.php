<?php

namespace App\Http\Controllers\Funding;

use App\Funder;
use App\Funding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        return redirect('/funder')->with('status','Funder Added');
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

        } else{
            $funder = Funder::find($id);
            $funder->delete();
            $status = 'Funder deactivated.';
        }

        return redirect('/funder')->with('status', $status);
    }

    public function restore($id) {
        Funder::withTrashed()
            ->where('id', $id)
            ->restore();

        return redirect('/funder')->with('status','Funder restored.');
    }
}
