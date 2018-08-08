<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Video;

use Storage;

class UploadVideo implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
    protected $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video) //$filename
    {
        $this->filename = $video->video_filename;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = storage_path() . '/uploads/' . $this->filename;

        $thumbnailName = $this->video->video_id . '_thumbnail.jpg';
        $thumbnailPath = storage_path() . '/uploads/' . $thumbnailName;

        // shell command [highly simplified, please don't run it plain on your script!]
        shell_exec("ffmpeg -i $file -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnailPath 2>&1");

        if( Storage::disk('s3videos')->put($this->filename, file_get_contents($file)) ){
            //delete file from local disk
            Storage::disk('uploads')->delete($this->filename);
            
        }

        if ( Storage::disk('s3videos')->put($thumbnailName, file_get_contents($thumbnailPath)) ) {
            //delete file from local disk
            Storage::disk('uploads')->delete($thumbnailName);
        }

        //Because telestream trail is over
        // DONT GET TO THIS POINT
        ($this->video)->processed = true;
        ($this->video)->save();

        //encoding will happen by a third party service that will pull videos of the drop bucket, then encode them and place them into the videos bucket.
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        throw $exception;
    }
}
