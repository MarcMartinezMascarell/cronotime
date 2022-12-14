<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Events;

class CalendarController extends Controller
{
    //
    public function index() {
        //Check if user is logged in
        if($user = auth()->user()) {
            //Check if user is admin
            if($user->hasRole('superAdmin')) {
                $events = Events::leftJoin('users', 'events.owner_id', 'users.id')->select('events.*', 'users.name as owner')->get();
                return view('calendar.calendar', ['events' => $events]);
                // $events = Events::all();
                // return view('calendar.calendar', ['events' => $events]);
            } else {
                //Get events for the company
                $events = Events::where('id_empresa', $user->company->id)->leftJoin('users', 'events.owner_id', 'users.id')->select('events.*', 'users.name as owner')->get();
                return view('calendar.calendar', ['events' => $events]);
            }
        } else {
            return redirect()->route('login');
        }
        //return view('calendar.calendar', ['events' => $events]);
    }

    public function addEvent(Request $request) {
        if($user = auth()->user()) {
            $event = new Events;
            $event->title = $request->title;
            $event->description = $request->description;
            $start_date = $request->start_date;
            if($request->start_time != null) {
                $start_date = date('Y-m-d H:i', strtotime("$request->start_date $request->start_time"));
                $event->allDay = 0;
            } else {
                $start_date = $request->start_date;
                $event->allDay = 1;
            }
            $event->start = $start_date;
            if($request->end_date != null) {
                //Make sure the end date is after the start date
                if($request->end_date < $request->start_date) {
                    return redirect()->back()->withError(__('La fecha de finalización no puede ser anterior a la fecha de inicio'));
                }
                if($request->end_time != null) {
                    $end_date = date('Y-m-d H:i', strtotime("$request->end_date $request->end_time"));
                } else {
                    $end_date = $request->end_date;
                }
                $event->end = $end_date;
            } else {
                $event->end = $start_date;
            }
            //$event->end_date = $request->end_date;
            $event->owner_id = $request->owner_id;
            $event->id_empresa = $request->empresa_id;
            $event->event_type = $request->event_type;
            $event->color = $request->color;
            if($request->event_type == 2)
                $event->allDay = 0;
            $event->save();
        }
        return redirect()->back();
    }

    public function getEvents() {
        return response()->json($events);
    }
}
