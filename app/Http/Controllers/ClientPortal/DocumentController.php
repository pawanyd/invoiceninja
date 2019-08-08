<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2019. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPortal\StoreDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        $contact = auth()->user();

        Log::error($request->all());
        
        Storage::makeDirectory('public/' . $contact->client->client_hash, 0755);

        $path = Storage::putFile('public/' . $contact->client->client_hash, $request->file('file'));
        $url = Storage::url($path);
        $size = $request->file('file')->getSize();
        $type = $request->file('file')->getClientOriginalExtension();

        $contact = auth()->user();
        $contact->avatar_size = $size;
        $contact->avatar_type = $type;
        $contact->avatar = $url;
        $contact->save();

        /*
        [2019-08-07 05:50:23] local.ERROR: array (
          '_token' => '7KoEVRjB2Fq8XBVFRUFbhQFjKm4rY9h0AGSlpdj3',
          'is_avatar' => '1',
          'q' => '/client/document',
          'file' => 
          Illuminate\Http\UploadedFile::__set_state(array(
             'test' => false,
             'originalName' => 'family.jpg',
             'mimeType' => 'image/jpeg',
             'error' => 0,
             'hashName' => NULL,
          )),
        )  
         */
        
        return response()->json($contact);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $contact = auth()->user();

        $file = basename($contact->avatar);
        $image_path = 'public/' . $contact->client->client_hash . '/' . $file;

        Log::error($image_path);
        Storage::delete($image_path);

        
        $contact->avatar = '';
        $contact->avatar_type = '';
        $contact->avatar_size = '';
        $contact->save();

        return response()->json($contact);
    }
}