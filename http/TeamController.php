<?php

namespace Modules\Crm\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Crm\Entities\CrmCallLog;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\CarbonInterval;
use App\Contact;
use App\User;
use Modules\Crm\Entities\Team;
use Modules\Crm\Entities\TeamMember;
use App\Utils\Util;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function index()
    {
        $department  = Category::where('category_type','hrm_department')->get();
        $team = Team::leftjoin('categories as c', 'team.department_id', '=', 'c.id')
            ->leftjoin('users as u', 'team.team_lead', '=', 'u.id')
            ->leftjoin('users as usr', 'team.created_by', '=', 'usr.id')
            ->select('team.id',
            'team.name as team',
            'c.name as department',
            'u.username as team_lead',
            'usr.username as created_by')
            ->get();
        return view('crm::team.index')->with(compact('department','team'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();
                $input = [];
                $input['department_id'] = $request->input('department_id');
                $input['name']          = $request->input('name');
                $input['team_lead']     = $request->input('team_lead');
                $input['created_by']    = Auth::user()->id;
                $team = Team::create($input);
                foreach($request->input('team_member') as $val){
                    $members = new TeamMember;
                    $members->team_id = $team->id;
                    $members->member = $val;
                    $members->save();
                }
            DB::commit();
            $output = ['msg' => 'success'];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("Filed:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = ['msg' => 'error'];
        }
        return redirect()->back()->with($output);
    }

    public function edit($id){
        $department     = Category::where('category_type','hrm_department')->get();
        $team           = Team::where('id',$id)->first();
        $team_members   = TeamMember::where('team_id',$id)->get();
        $members        = User::where('essentials_department_id',$team->department_id)->get();
        return view('crm::team.edit')->with(compact('department','team','members','team_members'));
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
                $id = $request->input('id');
                $input = [];
                $input['department_id'] = $request->input('department_id');
                $input['name']          = $request->input('name');
                $input['team_lead']     = $request->input('team_lead');
                $input['created_by']    = Auth::user()->id;
                $team = Team::where('id',$id)->update($input);
                $delete = TeamMember::where('team_id',$id)->delete();
                foreach($request->input('team_member') as $val){
                    $members = new TeamMember;
                    $members->team_id = $id;
                    $members->member = $val;
                    $members->save();
                }
            DB::commit();
            $output = ['msg' => 'success'];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("Filed:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = ['msg' => 'error'];
        }
        return redirect()->back()->with($output);
    }

    public function destroy($id){
        try {
            DB::beginTransaction();
                
            Team::where('id',$id)->delete();
            TeamMember::where('team_id',$id)->delete();
                
            DB::commit();
            $output = ['msg' => 'success'];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("Filed:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = ['msg' => 'error'];
        }
        return redirect()->back()->with($output);
    }

    public function get_members($id){
        $members     = User::where('essentials_department_id',$id)->get();
        return response()->json($members);
    }

}
