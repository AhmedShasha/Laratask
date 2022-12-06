<?php

namespace App\Http\Controllers\Laratask;

use App\Http\Controllers\Controller;
use App\Models\Laratask\File;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Response;
use SoareCostin\FileVault\Facades\FileVault;
use ZipArchive;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // for get all files
    public function index()
    {
        $files = File::all();
        return view('Laratask.index', compact('files'));
    }

    // store & save file
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:zip',
        ]);

        $file = new File();
        // file variables definition
        $fileSize = $request->file->getSize(); // size in bytes
        $fileExtention = $request->file->extension();
        $fileName = time() . '_' . $request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('zipFiles', $fileName);

        $file->name = time() . '_' . $request->file->getClientOriginalName();   // uniqe name with time 
        $file->path = '/storage/' . $filePath;

        // store file data
        $file->name = $fileName;
        $file->size = $fileSize;    // in bytes
        $file->path = $filePath;
        $file->type = $fileExtention;

        Auth::user()->files()->save($file);

        // extract file
        $this->extract($filePath);

        toast()->success('Added Successfully');
        return redirect()->route('home');
    }

    // function download attachments
    public function preview($id)
    {
        $file = File::find($id);

        $filepath = storage_path('app/uploads')."/".$file->name;
        
        return Response::download($filepath);         

    }

    public function destroy($id)
    {

        $file = File::find($id);

        $filepath = storage_path('app/zipFiles')."/".$file->name;

        if(file_exists($filepath))    unlink($filepath);  // unlink from storage
        
        $file->delete();

        toast()->success('Deleted Successfully');
        return redirect()->back();
    }

    public function extract($filePath)
    {
        $zip = new ZipArchive();

        $zipFile = $zip->open(Storage::disk('local')->path($filePath));

        if ($zipFile === TRUE) {
            $zip->extractTo(Storage::disk('local')->path($filePath . 'zip')); 
            $zip->close(); 
        }
    }

    public function myFiles($id){
        $files = File::where('user_id',$id)->get();
        return view('Laratask.myFiles', compact('files'));
    }

    public function userFiles($id)
    {
        $user = User::find($id);
        
        return response()->json([
            'user id' => $user->id,
            'user name' => $user->name,
            'files' => $user->files()->get(['id','name','size','type']),
        ]);
    }

}
