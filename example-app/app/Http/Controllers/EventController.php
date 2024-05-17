<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Event;
use App\Models\Type;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $events = Event::with('users')->get()->toJson();
        return view("app.index" , compact('events'));
    }

    public function create(){
        $cities = City::all();
        $types = Type::all();
        return view("app.create-event", compact('cities', 'types'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'ticket_price' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'comment' => 'nullable|string',
            'contact' => 'nullable|string',
            'location' => 'required|string',
            'image_url' => 'required|url',
        ]);
    
        $event = new Event();
        $event->user_id = 1;
        $event->type_id = $request->type; 
        $event->city_id = $request->location; 
        $event->title = $validatedData['title'];
        $event->ticket_price = $validatedData['ticket_price'];
        $event->ticket_url = $validatedData['ticket_url'];
        $event->from = $validatedData['from'];
        $event->to = $validatedData['to'];
        $event->image_url = $validatedData['image_url'];
        $event->comment = $validatedData['comment'];
        $event->contact = $validatedData['contact'];
        $event->location = $validatedData['location'];
    
        $event->save();
        
        return redirect()->route('view-events')->with('success', 'Event created successfully.');
    }
}
