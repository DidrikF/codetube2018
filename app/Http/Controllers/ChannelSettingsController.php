<?php

namespace App\Http\Controllers;

use App\Http\Requests; // \ChannelUpdateRequest;
use Illuminate\Http\Request;

use App\Models\Channel;
use App\Http\Requests\ChannelUpdateRequest;
use App\Jobs\UploadImage;
use Storage; 
use File;

class ChannelSettingsController extends Controller
{
    //The service container will be able to automatically inject type hinted class instances in controllers. The controllers are instantiated through the service container.
    public function edit(Channel $channel) //Sinve we give $channel the same name as {channe} from the route URI and we are typehinting a Channel object, Laravel will automatically get the right user model from the database and inject it (method injection)
    {
    	$this->authorize('edit', $channel);

        return view('channel.settings.edit', [
    			'channel' => $channel
    		]);
    }

    public function update(ChannelUpdateRequest $request, Channel $channel)
    {
    	//Athorization
        $this->authorize('update', $channel); //All controllers inherit this method, and you configure authoriation through creating Policies and register them within the AuthServiceProvider
        
        $channel->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);


        if($request->file('image'))
        {
            //move to temp location
            $request->file('image')->move(storage_path() . '/uploads', $fileId = uniqid(true));

            
            
            //dispatch job
            $this->dispatch(new UploadImage($channel, $fileId));
        }

        //echo storage_path() . '/uploads/' . $fileId;
        //$path = storage_path() . '/uploads/' . $fileId;
        //echo File::delete($path);
        //echo Storage::disk('uploads')->delete($fileId);
        return redirect()->to("/channel/{$channel->slug}/edit"); //redirect()->back() will redirect to the old slug, which does no longer exist.

    }

}

