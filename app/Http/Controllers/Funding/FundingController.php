<?php

namespace App\Http\Controllers\Funding;

use App\Funder;
use App\Funding;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FundingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager');
    }

    public function index() {
        return view('funding.funding.index')
            ->with('funders', Funder::canSee()->get())
            ->with('mentors', User::mentor()->canSee()->get())
            ->with('fundings', 
                Funding::canSee()
                    ->orderBy('user_id', 'asc')
                    ->orderBy('funding_year', 'desc')->get());
    }

    public function store(Request $request) {
        // dd($request);
        $request->validate([
            'mentor_id'    => 'required|exists:users,id',
            'funder_id'    => 'required|exists:funders,id',
            'funding_year' => 'required|numeric|min:1900|max:2500'
        ]);

        $mentor = User::mentor()->canSee()->whereId($request->mentor_id)->first();
        if (!$mentor) {
            abort(401,'Unauthorized');
        }

        $funding = new Funding();
        $funding->funder_id = $request->funder_id;
        $funding->user_id = $request->mentor_id;
        $funding->funding_year = $request->funding_year;
        $funding->save();

        return redirect('/funding')->with('status','Funding added.');
    }

    public function destroy(Request $request, $id) {
        $funder = Funding::canSee()->find($id);
        if (!$funder) {
            abort(401,'Unauthorized');
        }

        $funder->delete();
        
        return redirect('/funding')->with('status', 'Funding deleted.');
    }

    public function export(Request $request){
        return view('funding.funding.export')
                ->with('fundings', 
                    Funding::canSee()
                        ->orderBy('user_id', 'asc')
                        ->orderBy('funding_year', 'desc')->get());
    }

}
