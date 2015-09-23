<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOwnerRequest;
use Illuminate\Http\Request;
use App\Owner;
use App\DeviceLog;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $owner;

    public function __construct(Owner $owner)
    {
        $this->owner = $owner;
        $this->middleware('auth');
    }

    public function index()
    {
        //
        $deletedOwners = Owner::onlyTrashed()->get();
        return view('owners.index', compact('deletedOwners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateOwnerRequest $request, Owner $owner)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $location = $request->get('location');

        $new_owner = new Owner();
        $new_owner->firstName = $firstname;
        $new_owner->lastName = $lastname;
        $new_owner->location = $location;
        $new_owner->save();

        return redirect()->back()->with('success_msg', 'Owner :: '. $new_owner->firstName . ' ' . $new_owner->lastName .' has been successfuly created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($owner)
    {
        $ctr = 0;
        $device_logs = DeviceLog::with(['device.category', 'owner', 'device'])->where('owner_id', $owner->id)->latest('device_logs.created_at')->paginate(25);
        $device_logs->setPath('');

        return view('owners.show', compact('owner', 'device_logs', 'ctr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($slug)
    {
        $edit_owner = Owner::editOwner($slug);

        return $edit_owner;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($owner)
    {
        //
        $ownerName = $owner->fullname();
        Owner::whereSlug($owner->slug)->delete();

        return redirect(route('owner.index'))->with('success_msg', 'Owner :: ' .$ownerName.' was successfully deleted');
    }

    public function fetchOwners()
    {
        $json = array();
        $owners = Owner::all();

        foreach ($owners as $owner) {
            $json[] = [
                'name' => $owner->firstName . ' ' . $owner->lastName,
                'campaign' => $owner->location,
                'id' => $owner->id,
                'updated_at' => date('m/d/Y h:i:s A', strtotime($owner->updated_at)),
                'slug' => $owner->slug
            ];
        }

        return json_encode($json);
    }

    public function fetchDispatches($id)
    {
        $json = array();
        $device_logs = DeviceLog::where('owner_id', $id)->get();

        foreach ($device_logs as $device_log) {
            $json[] = [
                'device_id' => $device_log->device_id,
                'device_name' => $device_log->device->name,
                'created_at' => date('M d, Y h:i:s A', strtotime($device_log->created_at)),
                'action' => $device_log->action,
                'device_slug' => $device_log->device->slug,
                'user' => $device_log->user->name,
                'user_id' => $device_log->user->id
            ];
        }

        return json_encode($json);
    }

    public function fetchAvailableOwners($category_id)
    {
        $json = array();

        $owners = Owner::whereNotIn('id', function ($query) use ($category_id) {
            $query->select(['owner_id']);
            $query->from('devices');
            $query->where('category_id', $category_id);
        })->get();

        foreach ($owners as $owner) {
            $json[] = [
                'name' => $owner->firstName . " " . $owner->lastName,
                'id' => $owner->id
            ];
        }

        return json_encode($json);
    }

    public function openExcel(Request $request)
    {
        //return $category_id;
        $import_excel = Owner::importOwner($request);

        return $import_excel;
    }

    public function ownerIndex()
    {
        return view('import.owner');
    }

    public function editOwner($id, Request $request)
    {
    }
}
