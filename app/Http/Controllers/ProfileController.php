<?php

namespace App\Http\Controllers;

use App\Models\Businessprofile;
use App\Models\Contractprofile;
use App\Models\Profile;
use Illuminate\Http\Request;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::paginate();

        return view('profile.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('profile.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'dni' => 'required|unique:profiles|max:8',
            'correo' => 'required|max:100',
            'fecha_nac' => 'required|date',
            'area' => 'required|max:100',
            'cargo' => 'required|max:100',
            'local' => 'required|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'tipo' => 'required|max:1',
        ]);


        $bs = Businessprofile::create([
            'area' => $request->area,
            'cargo' => $request->cargo,
            'local' => $request->local,
        ]);
        $ct = Contractprofile::create([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tipo' => $request->tipo,
        ]);

        $pr = Profile::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'dni' => $request->dni,
            'correo' => $request->correo,
            'fecha_nac' => $request->fecha_nac,
            'bu_pro_id' => $bs->id,
            'co_pro_id' => $ct->id,
        ]);

        return redirect()->route('profiles.index')
            ->with('success', 'Empleado creado exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::find($id);

        return view('profile.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile = Profile::find($id);

        return view('profile.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Profile $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'dni' => 'required|unique:profiles,dni,' . $profile->id . '|max:8',
            'correo' => 'required|max:100',
            'fecha_nac' => 'required|date',
            'area' => 'required|max:100',
            'cargo' => 'required|max:100',
            'local' => 'required|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'tipo' => 'required|max:1',
        ]);

        $profile->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'dni' => $request->dni,
            'correo' => $request->correo,
            'fecha_nac' => $request->fecha_nac,
        ]);
        $profile->businessprofile()->update([
            'area' => $request->area,
            'cargo' => $request->cargo,
            'local' => $request->local,
        ]);
        $profile->contractprofile()->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('profiles.index')
            ->with('success', 'Se guardaron los datos correctamente!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $profile = Profile::find($id)->delete();

        return redirect()->route('profiles.index')
            ->with('success', 'Empleado eliminado correctamente!');
    }
}
