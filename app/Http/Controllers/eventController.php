<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class eventController extends Controller
{
    function getEvent($id = null)
    {
        if ($id)
        {
            return Event::find($id);
        }
        else{
            return Event::all();
        }
    }
    function addEvent(Request $request)
    {

        $event = new Event;
        $event->name = $request->name;
        $event->eventDate = $request->date;
        $event->eventTime = $request->event_time;
        $event->eventPrice = $request->event_price;
        $event->eventAddress = $request->eventAddress;
        $event->charityAddress = $request->charityAddress;
        $event->description = $request->description;
        $event->isActive = 1;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public');
            $imageName = basename($imagePath);
            $event->image = $imageName;
        }

        $result = $event->save();

        if ($result) {
            $fromDatabase = Event::find($event->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            return response()->json(["error" => "Failed to add event"], 500);
        }

    }
    public function editEvent(Request $request, $id)
    {
        // return $request;
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->isActive = $request->isActive; //dd("hello ". $request->isActive);
        $event->name = $request->name;
        $event->eventDate = $request->date;
        $event->eventTime = $request->eventTime;
        $event->eventPrice = $request->eventPrice;
        $event->eventAddress = $request->eventAddress;
        $event->charityAddress = $request->charityAddress;
        $event->description = $request->description;

        $result = $event->save();

        if ($result) {
            $fromDatabase = Event::find($event->id);
            $response = [
                    "message" => "success",
                    "data" => $fromDatabase
                ];
            return response()->json($response);
        } else {
            return response()->json(['error' => 'Failed to edit event'], 500);
        }
    }
    function deleteEvent($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        if ($event->image) {
            Storage::delete($event->image);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
