<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medias = DB::table('media')
                    ->latest()
                    ->get();
                    
        return view('media.index')
            ->with(compact('medias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        try{

            DB::beginTransaction();

            $input = $request->only(['name', 'description']);
            $input['file_name'] = $this->__uploadMedia($request);
            $input['uploaded_by'] = auth()->user()->id;

            Media::create($input);

            DB::commit();

            return redirect(route('medias.index'))
                ->with('status', 'Media uploaded');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('status', __("messages.something_went_wrong"));
        }
    }


    private function __uploadMedia($request)
    {
        $path = '';
        if(
            !empty($request->file('file_name'))
        ) {
            $file_name = time().'_'.$request->file('file_name')->getClientOriginalName();
            $path = $request->file('file_name')->storeAs(
                        'media', $file_name
                    );
        }
        return $path;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $media = Media::findorfail($id);

        return view('media.edit')
            ->with(compact('media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        try{

            DB::beginTransaction();

            $input = $request->only(['name', 'description']);

            Media::where('id', $id)
                ->update($input);

            DB::commit();

            return redirect(route('medias.index'))
                ->with('status', 'Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('status', __("messages.something_went_wrong"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {

                $media = Media::findorfail($id);
                
                if (!empty($media)) Storage::delete($media->file_name);

                $media->delete();

                $output = [
                    'success' => true,
                    'msg' => 'Success.'
                ];
            } catch (\Exception $e) {
                $output = [
                    'success' => false,
                    'msg' => 'Something went wrong.'
                ];
            }

            return $output;
        }
    }

    public function downloadMedia($id)
    {
        $media = Media::findorfail($id);

        $file_name_arr = explode('_', $media->file_name);
        $file_name = !empty($file_name_arr[1]) ? $file_name_arr[1] : $media->file_name;

        return \Storage::download($media->file_name, $file_name);
    }
}
