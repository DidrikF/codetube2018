<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Channel;
use Storage;
use Image;
//use File;

class UploadImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $channel;
    public $fileId;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channel $channel, $fileId)
    {
        $this->channel = $channel;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get the image
        $path = storage_path() . '/uploads/' . $this->fileId;
        $fileName = $this->fileId . '.png';

        //resize
        
        Image::make($path)->encode('png')->fit(40, 40, function($c){
            $c->upsize(); //if less than 40 pixels
        })->save();
        
        //upload to s3
        $s3return = Storage::disk('s3images')->put('profile/' . $fileName, file_get_contents($path));
        

        if( $s3return )
        {
            //delete file
            Storage::disk('uploads')->delete($this->fileId);
            //var_dump('upload success');
            //File::delete($path);
            //unlink($path, E_WARNING);
        }
        
        //update channel image
        $this->channel->image_filename = $fileName;
        $this->channel->save(); //or use the update method

    }
}
