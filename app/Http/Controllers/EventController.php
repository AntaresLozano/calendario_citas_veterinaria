<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEvents()
    {
        $events = Event::all();
        echo json_encode($events);
        //    return $events;
    }

    public function createEvent(Request $request)
    {
        $request->validate([
            'title' => 'required | min:3',
            'cedula' => 'required | min:3',
            'nombre' => 'required | min:3',
            'apellido' => 'required | min:3',
            'mascota' => 'required | min:3',
            'start' => 'required | min:3',
            'fecha' => 'required | min:3',
            'hora' => 'required | min:3',
            'descripcion' => 'required | min:3',
            'color' => 'required | min:3',
        ]);

        $event = new Event;

        $event->title = $request->title;
        $event->cedula = $request->cedula;
        $event->nombre = $request->nombre;
        $event->apellido = $request->apellido;
        $event->mascota = $request->mascota;
        $event->start = $request->start;
        $event->fecha = $request->fecha;
        $event->hora = $request->hora;
        $event->descripcion = $request->descripcion;
        $event->color = $request->color;

        $event->save();
    }

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        $event->delete();
        // return redirect('/');
    }
    public function updateEvent(Request $request, $id)
    {
        $event = Event::find($id);
        $event->title = $request->title;
        $event->cedula = $request->cedula;
        $event->nombre = $request->nombre;
        $event->apellido = $request->apellido;
        $event->mascota = $request->mascota;
        $event->start = $request->start;
        $event->fecha = $request->fecha;
        $event->hora = $request->hora;
        $event->descripcion = $request->descripcion;
        $event->color = $request->color;
        $event->save();
    }
}
