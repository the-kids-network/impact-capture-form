<?php

namespace App\Domains\Funding\Controllers;

use App\Domains\Funding\Models\Funder;
use App\Domains\Funding\Models\Funding;
use App\Domains\UserManagement\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('hasAnyOfRoles:admin,manager');
    }

    public function index() {
        return view('fundings.fundings.index')
            ->with('funders', Funder::canSee()->get())
            ->with('mentors', User::mentor()->canSee()->get())
            ->with('fundings', 
                Funding::canSee()
                    ->orderBy('user_id', 'asc')
                    ->orderBy('funding_year', 'desc')->get());
    }

    public function store(Request $request) {
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

        return redirect('/fundings')->with('status','Funding added.');
    }

    public function destroy(Request $request, $id) {
        $funder = Funding::canSee()->find($id);
        if (!$funder) {
            abort(401,'Unauthorized');
        }

        $funder->delete();
        
        return redirect('/fundings')->with('status', 'Funding deleted.');
    }

    public function export(Request $request){
        return view('fundings.fundings.export')
                ->with('fundings', 
                    Funding::canSee()
                        ->orderBy('user_id', 'asc')
                        ->orderBy('funding_year', 'desc')->get());
    }

}
