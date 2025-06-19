<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PointsController extends Controller
{

    public function __construct()
    {
        $this->points = new PointsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //array objek
        $data = [
            'title' => 'Map',
        ];

        //memanggil $data kedalam view
        return view('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate(
            [
                'name' => 'required|unique:points,name',
                'description' => 'required',
                'geom_point' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2024',
                'sektor' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'pekerja' => 'required|string|max:100', // bisa juga integer tergantung migrasi
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Geometry point is required',
                'sektor.required' => 'Sector is required',
                'alamat.required' => 'Address is required',
                'pekerja.required' => 'Number of workers is required',
            ]
        );

        // Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        // Prepare data
        $data = [
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
            'sektor' => $request->sektor,
            'alamat' => $request->alamat,
            'pekerja' => $request->pekerja,
        ];

        // Create data
        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Point failed to be added');
        }

        // Redirect
        return redirect()->route('map')->with('success', 'Point has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Point',
            'id' => $id,
        ];
        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($id, $request->all());

        // Pastikan ini bukan null
        if (!is_numeric($id)) {
            return redirect()->route('map')->with('error', 'Invalid ID');
        }

        //Validation
        $request->validate(
            [
                'name' => ['required', Rule::unique('points')->ignore($id)],
                'description' => 'required',
                'geom_point' => 'required',
                'sektor' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'pekerja' => 'required|string|max:100',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Location is required',
                'sektor.required' => 'Sector is required',
                'alamat.required' => 'Address is required',
                'pekerja.required' => 'Number of workers is required',
            ]
        );

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        $old_image = $this->points->find($id)->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            if ($old_image != null && file_exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }
        } else {
            $name_image = $old_image;
        }

        $data = [
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'sektor' => $request->sektor,
            'alamat' => $request->alamat,
            'pekerja' => $request->pekerja,
            'image' => $name_image,
        ];

        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Point failed to update');
        }

        return redirect()->route('map')->with('success', 'Point has been updated');
    }

    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Points Failed to delete');
        }
        //Delete Image
        if ($imagefile != null){
            if (file_exists('./storage/images/' . $imagefile)) {
                unlink('./storage/images/' . $imagefile);
            }
        }
        return redirect()->route('map')->with('success', 'Points has been deleted');
    }
}
