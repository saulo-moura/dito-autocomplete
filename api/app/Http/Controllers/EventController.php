<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Event;

class EventController extends Controller
{
    public function index()
    {
        return Event::simplePaginate(10);
    }

    public function show($event)
    {
        if(strlen($event) >= 2)
            return Event::where('event', 'like', "%{$event}%")->simplePaginate(10);
        $retorno = ['error' => 'O termo da pesquisa deve ter 2 ou mais caracteres'];
        return response($retorno, 400);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user' => 'required',
                'event' => 'required|max:255',
                'timestamp' => 'required|date',
            ]);
     
            if ($validator->fails())
                return response($validator->errors(), 400);
            
            $event = new Event;
            $event->user = $request->user;
            $event->event = $request->event;
            $event->timestamp = Carbon::parse($request->timestamp)->format('Y-m-d H:i:s');
            $event->save();
    
            return response($event, 201);
        } catch(Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
