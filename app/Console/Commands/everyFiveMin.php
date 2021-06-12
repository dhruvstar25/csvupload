<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Image;
class everyFiveMin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will read the data from csv and download the image from csv path';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        //Read Data From Table where is_processed is 0

        $dataNotProcessed = DB::table("csvuploads")->where('is_processed', 0)->get();
        //Loop over the data and get the csv file
        foreach ($dataNotProcessed as $data) {
            $id = $data->id;
            $filePath = $data->filepath;
            $email = $data->email;
            //Read Csv From the file and store in an array


            $csvData =  $this->csvToArray(public_path() . '\\file\\' . $filePath, ',');

            if ($this->downLoadImage($csvData)) {
                $this->html_email($email, 'Image has been downloaded from the csv.');
                //UPdate Table and set isProcecssed = true
                DB::update('update csvuploads set is_processed = 1 where id='.$id);
            }
        }
        return 0;
    }

    public function html_email($toEmail, $body)
    {

        $data = array('name' => "Dhruv", 'email' => $toEmail, 'body' => $body);
        Mail::raw($body, function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Image has been downloaded from the uploaded csv.');
        });
        
    }
    public function csvToArray($filename = '', $delimiter = ',')
    {

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function downLoadImage($csvData)
    {
        foreach ($csvData as $imageData) {
            $this->downLoadImageFromUrl($imageData["Image URL"]);
        }
        return true;
    }
    public function downLoadImageFromUrl($url)
    {
        $image = file_get_contents($url);
        $filenameToSave = time() . '.' . 'jpg';
        file_put_contents(public_path('image/' . $filenameToSave), $image);
        $this->resizeImageSubmit(public_path('image/' . $filenameToSave));
        //crop image function call
    }
    public function resizeImageSubmit($filePath){
        
        $image_resize = Image::make($filePath);
        $image_resize->resize(256,256);
        $image_resize->save(public_path('corp/'.time() . '.' . 'jpg'));
        return "image has been resized successfully";
    }
}
