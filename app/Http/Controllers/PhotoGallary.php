<?php

namespace App\Http\Controllers;

use console;
use App\User;
use App\Album;
use App\Image;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
// import the Intervention Image Manager Class
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as imgIntervention;
use Intervention\Image\Size;
use SebastianBergmann\Environment\Console as EnvironmentConsole;

class PhotoGallary extends Controller
{
    //======================== Variable Declaration ==================================
    private $totalImageToDisplay; //total images to display in one page, if this is empty or 0 then all images whill be display 
    //==================== End Of Variable Declaration ===============================


    //construct 
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'selectedAlbum', 'singleImage', 'search');
    }

    //index function for welcome page 
    public function index()  //this is for '/' root route which run at the first 
    {
        if ($this->totalImageToDisplay == '') {
            $this->totalImageToDisplay = 20;
        }

        $settings = Setting::all()->first();

        if ($settings == '') //if query is empty  
        {
            $this->setDataForSettings();  //to set default data in settings table 
            $this->totalImageToDisplay = 20; //if settins table is empty then default value for the displaying image 
            // $this->showNotifyFromSettings(); //check if information is not correct 
            $albums = Album::all();
            $imgs = Image::orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
            return view('welcome')->with('imgs', $imgs)->with('albums', $albums);
        } else {
            $this->totalImageToDisplay = $settings->total_images_to_display; //totalImageToDisplay value get value from database when settings table is not empty 
            // $this->showNotifyFromSettings(); //check if information is not correct 
            $albums = Album::all();
            $imgs = Image::orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
            return view('welcome')->with('imgs', $imgs)->with('albums', $albums);
        }
    }


    //selected album 
    public function selectedAlbum($id)
    {
        $albums = Album::all();

        $imageFromAlbum = Image::orderBy('id', 'DESC')->where('album_id', $id)->paginate($this->totalImageToDisplay);

        return view('albumImages')->with('imageFromAlbum', $imageFromAlbum)->with('albums', $albums);
    }

    //single image 
    public function singleImage($id)
    {
        $imageImage = Image::where('id', $id)->get();

        /* Total views of the image when click a single image */
        foreach ($imageImage as $imgViews) {
            $getTotalViews = $imgViews->image_views;
            $getTotalViews++;
        }

        $dataUpdate = Image::find($id);
        $dataUpdate->image_views = $getTotalViews;
        $dataUpdate->update();
        /* Total views of the image when click a single image */

        return view('singleImage', compact('imageImage'));
    }

    //search
    public function search(Request $request)
    {
        $albums = Album::all();
        $srch = $request->search;
        $query = Image::where('name', 'like', "%" . $srch . "%")->orwhere('display_name', 'like', $srch . "%")->orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
        if ($query->isEmpty()) {
            return redirect('/')->with('messagefail', 'Image not found!');
        } else {
            return view('searchresult')->with('srchResult', $query)->with('srchWord', $srch)->with('albums', $albums);
        }
    }
    //======================================== Images ========================================
    //I use resource route for Images. It has default routes and function 'create, store, edit, update, destroy'. 

    //store an image with database 
    public function create()
    {
        try {

            //get album list
            $albums = Album::all();
            if ($albums != '') //if query is not empty 
            {
                return view('storeImage')->with('albums', $albums);
            } else {
                return redirect('/')->with('messagefail', 'Album is not available. At first you have to create album.');
            }
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //store images  
    public function store(Request $request)
    {
        $file = $request->file;

        $maxFileSize = Setting::all()->first()->max_uploaded_file_size;

        //validation the image file
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,jpg|max:' . $maxFileSize,
            'display_name' => 'required|max:20',
            'description' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()->all()]);
        } else {
            //album id
            $albumId = Album::select('id')->where('name', $request->album)->get();

            if ($albumId != '') {
                if ($request->hasFile('file')) {
                    //file store to the destination folder as a new name 
                    $fileExt = $file->getClientOriginalExtension();

                    //file store to the destination folder as a new name 
                    $fileName2 = $file->getClientOriginalName(); //get the file name with extension 

                    $without_extension = basename($fileName2, '.' . $fileExt); //'index.html' to 'index' 

                    //remove '+' and other special sign including space from file name and replace from '_' 
                    $remove = array("+", "^", " ", "%", "=", "*", ",", "|");
                    $fileName = str_replace($remove, "_", $without_extension); //filename without extension 
                    //end of remove '+' and other special sign including space from file name and replace from '_' 

                    $fileName = $fileName . '.' . $fileExt; //if file name length is less then 20 

                    $fileToSave = date("Ymdhmsa") . '_' . $fileName; //actual file name with extension which will be stored 

                    foreach ($albumId as $album_Id) {
                        $selected_Album_Id = $album_Id->id;
                    }

                    //store all data in Post model 
                    $dataSave = new Image;

                    $dataSave->name = $fileToSave;
                    $dataSave->image_views = 1; //count single image views and default value is 1 
                    $dataSave->display_name = $request->display_name;
                    $dataSave->description = $request->description;
                    $dataSave->size = $file->getClientSize(); //file size in bytes 
                    $dataSave->album_id = $selected_Album_Id;
                    $dataSave->uploaded_date = date('Y-m-d h:m:s');
                    $dataSave->uploaded_by = $request->uploaded_by;

                    $dataSave->save(); //data will be saved in database 

                    //if data save successfully then file will be uploaded 
                    $file->storeAs('/images', $fileToSave);
                    $file->storeAs('/images/thumb', 'thumb_' . $fileToSave);

                    //to make a thumbnail image 
                    $img = imgIntervention::make('upload/images/thumb/thumb_' . $fileToSave)->resize(500, 400)->save('upload/images/thumb/thumb_' . $fileToSave, 52);
                    return redirect('/')->with('message', 'Data has been successfully Inserted!');
                }
            } else {
                return redirect()->back()->with('messagefail', 'Insert Failed! Please, check all fields.');
            }
        } //validation 
    } //store images

    //edit image info
    public function edit($id)
    {
        $imgEdit = Image::find($id);
        $albums = Album::all();

        return view('editImage')->with('imgEdit', $imgEdit)->with('albums', $albums);
    }

    //update images
    public function update(Request $request, $id)
    {
        //validation the image file
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|max:20',
            'description' => 'required|max:150',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()->all()]);
        } else {
            //album id
            $albumId = Album::select('id')->where('name', $request->album)->get();
            foreach ($albumId as $album_Id) {
                $selected_Album_Id = $album_Id->id;
            }

            //store all data in Post model 
            $dataSave = Image::find($id);
            $dataSave->image_views = 1; //count single image views and default value is 1 

            $dataSave->display_name = $request->display_name;
            $dataSave->description = $request->description;
            $dataSave->album_id = $selected_Album_Id;
            $dataSave->uploaded_date = date('Y-m-d h:m:s');
            $dataSave->uploaded_by = $request->uploaded_by;

            $dataSave->update(); //data will be saved in database 

            return redirect('/')->with('message', 'Data has been successfully Inserted!');
        } //validation 
    } //update images 

    //distroy or remove the image with database row 
    public function destroy($id)
    {
        $selectedRow = Image::find($id); //find the row 
        File::delete(public_path() . '/upload/images/' . $selectedRow->name);  //delete the file 
        File::delete(public_path() . '/upload/images/thumb/thumb_' . $selectedRow->name);  //delete the thumbnail image file 
        $selectedRow->delete(); //delete the row 
        return redirect('/')->with('message', 'Data has been successfully deleted!');
    }
    //======================================== /Images ========================================


    //delete all images from images folder with images table 
    public function deleteAllImages()
    {
        $selectedRow = Image::all();
        if ($selectedRow->isEmpty()) {
            return redirect('/')->with('message', 'This gallary is empty!');
        } else {
            foreach ($selectedRow as $selectOne) {
                $selectOne->delete();
            }

            File::deleteDirectory(public_path() . '/upload/images/thumb');
            File::deleteDirectory(public_path() . '/upload/images');
            File::makeDirectory(public_path() . '/upload/images');
            File::makeDirectory(public_path() . '/upload/images/thumb');

            return redirect('/')->with('message', 'All data has been successfully deleted!');
        }
    }


    //======================================== Album ========================================
    //show album 
    public function showAlbum()
    {
        $imgs = Album::paginate($this->totalImageToDisplay);
        if ($imgs->count() < 1) {
            return Redirect('/')->with('messagefail', 'No any album is available! You have to create album.');
        } else {
            return view('showAlbum')->with('imgs', $imgs);
        }
    }

    //album creating to open a page 
    public function createalbum()
    {
        try {

            return view('albumcreate');
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //store album to create an Album 
    public function storeAlbum(Request $request)
    {
        $file = $request->file;
        $album_name = $request->album_name;

        $maxFileSize = Setting::all()->first()->max_uploaded_file_size;

        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,jpg|max:' . $maxFileSize,
            'album_name' => 'required|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()->all()]);
        } else {
            if ($request->hasFile('file')) {
                $file = $request->file;
                $album_name = $request->album_name;
                //to remove white space from album name 
                $removeFromAlbumName = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "+", "=", "/", "?", ".", ">", ",", "<", "|", " ");
                $album_name = str_replace($removeFromAlbumName, '', $album_name);
                //end of - to remove white space from album name 

                //check existing album 
                $newAlbumName = $album_name;
                $checkExistingAlbum = Album::where('name', $newAlbumName)->get();
                if ($checkExistingAlbum->isEmpty()) //if query is empty 
                {
                    $dataSave = new Album;
                    $dataSave->name = $album_name;
                    $dataSave->cover_image = $album_name . '.jpg';
                    $file->storeAs('/albums', $album_name . '.jpg');
                    //compress the image with resize 
                    $img = imgIntervention::make('upload/albums/' . $album_name . '.jpg')->resize(500, 400)->save('upload/albums/' . $album_name . '.jpg', 72);
                    $dataSave->save();
                    return redirect()->back()->with('message', 'Album has been successfully Created!');
                } else {
                    return redirect()->back()->with('messagefail', 'Album has not been Created! May be you can not choose any file or, album is already existed.');
                }
            }
        } //end of validation procress 'else' section 
    }

    //edit album
    public function editAlbum($id)
    {
        try {

            $imgEdit = Album::find($id);

            return view('editAlbum')->with('imgEdit', $imgEdit);
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }
    //update album
    public function updateAlbum(Request $request, $id)
    {
        $album_info = Album::find($id);
        $album_name = $request->album_name;

        $validator = Validator::make($request->all(), [
            'album_name' => 'required|min:1|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()->all()]);
        } else {
            $album_name = $request->album_name;
            //to remove white space from album name 
            $removeFromAlbumName = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "+", "=", "/", "?", ".", ">", ",", "<", "|", " ");
            $album_name = str_replace($removeFromAlbumName, '', $album_name);
            //end of - to remove white space from album name 
            //check existing album 
            $newAlbumName = $album_name;
            $checkExistingAlbum = Album::where('name', $newAlbumName)->get();
            if ($checkExistingAlbum->isEmpty()) //if query is empty 
            {
                File::move(public_path() . '/upload/albums/' . $album_info->name . '.jpg', public_path() . '/upload/albums/' . $album_name . '.jpg');
                $album_info->name = $album_name;
                $album_info->cover_image = $album_name . '.jpg';
                $album_info->update();
                return redirect()->back()->with('message', 'Album has been successfully Updated!');
            } else {
                return redirect()->back()->with('messagefail', 'This album is alreay existed, please try to create with new name!');
            } //end of validation procress 'else' section 
        }
    }

    //distroy or remove the image with database row 
    public function destroyAlbum($id)
    {
        //check the album is empty or not. then empty album will be deleted and another album will not be deleted 
        $imgs = Image::where('album_id', '=', $id)->get();

        if ($imgs->isEmpty()) {
            //delete the album 
            $selectedRow = Album::find($id); //find the row 
            File::delete(public_path() . '/upload/albums/' . $selectedRow->name . '.jpg');  //delete the file 
            $selectedRow->delete(); //delete the row 
            return Redirect('/album/showAlbum')->with('message', 'Album has been successfully deleted!');
        } else {
            return Redirect::back()->with('messagefail', 'This album is not empty! So, you can not delete this album.');
        }
    }
    //======================================== /Album ========================================


    //dashboard
    public function dashboard()
    {
        try {

            return view('dashboard');
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //Edit profile 
    public function editProfile()
    {
        try {

            return view('profile');
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //update profile
    public function updateProfile(Request $request)
    {
        //get currect logged in user data  
        $userID = Auth::user()->id;

        $dataSave = User::find($userID);

        $file = $request->file;

        //this value comes from settings table of the database 
        $maxFileSize = Setting::all()->first()->max_uploaded_file_size;

        //validation the image file
        $validator = Validator::make($request->all(), [
            //max:1024 means maximum 1024 kB or 1 MB as an example. User can upload the image file which size is maximum 1024 kB. This data will come from database. 
            'file' => 'required|image|mimes:jpeg,jpg|max:' . $maxFileSize,
        ]);
        if ($validator->fails()) { //when validation rules not match 
            return redirect()->back()->with(['errors' => $validator->errors()->all()]);
        } else { //when validation rules match 
            //upload the image 
            if ($request->hasFile('file')) { //if file is selected 
                $getOldImageName = $dataSave->name . '.jpg'; //to store old images before updated 
                $getOldImageName_2 = $dataSave->name . '.jpeg'; //to store old images before updated 
                //store all data in Post model 
                $dataSave->name = $request->profile_name;
                $dataSave->email = $request->profile_email;

                $dataSave->update();

                //remove old image 
                File::delete(public_path() . '/upload/users/' . $getOldImageName);  //delete the file 
                File::delete(public_path() . '/upload/users/' . $getOldImageName_2);  //delete the file 

                //file store to the destination folder as a new name 
                $fileName = $request->profile_name . '.jpg';
                $file->storeAs('/users', $fileName);
                //to make a thumbnail image 
                $img = imgIntervention::make('upload/users/' . $fileName)->resize(500, 500)->save('upload/users/' . $fileName, 90);

                return redirect('/dashboard'); //->with('message','Data has been successfully Updated!')
            } else { //if file is not selected 
                $getOldImageName = $dataSave->name . '.jpg'; //to store old images before updated 
                //store all data in Post model 
                $dataSave->name = $request->profile_name;
                $dataSave->email = $request->profile_email;

                $makeNewImageName = $request->profile_name . '.jpg'; //to store old images before updated 

                $dataSave->update(); //update user data from the database 

                //copy old image with a new name 
                $img = imgIntervention::make('upload/users/' . $getOldImageName)->resize(500, 500)->save('upload/users/' . $makeNewImageName, 90);

                //user name change then delete old user image 
                if ($makeNewImageName != $getOldImageName) {
                    File::delete(public_path() . '/upload/users/' . $getOldImageName);  //delete old user image 
                }

                return redirect('/dashboard'); //->with('message','Data has been successfully Updated!');
            }
        }
    } //update profile 


    //Reset App to reset everything to use this project new one. It removes everything as like factory reset 
    /* It removes everything which are created by admin. It removes all uploaded images, all albums, data from database and also removes users with their images. It will be a fresh gallary to use new one.*/
    public function resetApp()
    {
        $images = Image::all();
        $albums = Album::all();
        $users = User::all();
        $settings = Setting::all();

        foreach ($images as $image) {
            $image->delete();
        }

        foreach ($albums as $album) {
            $album->delete();
        }

        foreach ($users as $user) {
            $user->delete();
        }

        foreach ($settings as $setting) {
            $setting->delete(); //delete all information of settings table 

            $this->setDataForSettings(); //to reset the settings, it is urgent for the information of file upload 
        }

        File::deleteDirectory(public_path() . '/upload/images');
        File::makeDirectory(public_path() . '/upload/images');

        File::deleteDirectory(public_path() . '/upload/albums');
        File::makeDirectory(public_path() . '/upload/albums');

        File::deleteDirectory(public_path() . '/upload/users');
        File::makeDirectory(public_path() . '/upload/users');

        return redirect('/')->with('message', 'All data has been successfully deleted!');
    }


    /* ===================================== Settings =============================================== */
    //settings such as project name, maximum uploaded file size, email for password reset system 
    public function setDataForSettings()  //to set default data for settings for the first time 
    {
        $dataSave = new Setting;
        $dataSave->project_name = 'PhotoGallary';
        $dataSave->email_to_reset_password = 'Null@Null.com'; //'alinsworld2010@gmail.com';
        $dataSave->password_of_email = 'Null'; //'aw002017';
        $dataSave->max_uploaded_file_size = 900;
        $dataSave->total_images_to_display = 20;

        $dataSave->save();
    }

    public function showSettings()  //to display settings page with existing data 
    {
        $settings = Setting::all()->first();

        return view('settings')->with('settings', $settings);
    }


    public function doSettings(Request $request, $id)  //to update data of settings 
    {

        //project_name, emailToResetPassword, passwordOfEmail, maxFileSize
        $projectName = $request->project_name;
        $emailToResetPassword = $request->emailToResetPassword;
        $passwordOfEmail = $request->passwordOfEmail;  //bcrypt($request->passwordOfEmail);
        $maxFileSize = $request->maxFileSize;
        $totalImgDisplay = $request->totalImgDisplay;

        if ($projectName == '') {
            $projectName = 'PhotoGallary'; //set default value 
        }
        if ($emailToResetPassword == '') {
            $emailToResetPassword = 'Null@Null.com'; //set default value 
        }
        if ($passwordOfEmail == '') {
            $passwordOfEmail = 'Null'; //set default value 
        }
        if ($maxFileSize == '' || $maxFileSize < 1) {
            $maxFileSize = 900; //set default value 
        }
        if ($totalImgDisplay == '') {
            $totalImgDisplay = 20; //set default value 
        }

        $settings = Setting::find($id);
        $settings->project_name = $projectName;
        $settings->email_to_reset_password = $emailToResetPassword;
        $settings->password_of_email = bcrypt($passwordOfEmail);
        $settings->max_uploaded_file_size = $maxFileSize;
        $settings->total_images_to_display = $totalImgDisplay;

        $settings->update();

        $this->setEnvValue(); //to change the value of .env file 

        return redirect('/');
    }


    /* =============================================================================================== */
    /* Changing Value of Environment .env file */
    /* =============================================================================================== */
    public function setEnvValue()  //in here set the key with value 
    {
        $settings = Setting::all()->first();

        $env_update = $this->changeEnv([
            'APP_NAME'   => $settings->project_name,  //'APP_NAME'   => 'PhotoGallary', last value don't use coma (,)
            'MAIL_USERNAME'   => $settings->email_to_reset_password,
            // 'MAIL_PASSWORD'   => bcrypt($settings->password_of_email)
            'MAIL_PASSWORD'   => $settings->password_of_email
        ]);
    }

    //$this->setEnvValue();  //to change the value of .env file 

    protected function changeEnv($data = array())  //tricky part, nothing to do in here  
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach ((array)$data as $key => $value) {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }
    /* =============================================================================================== */
    /* End of Changing Value of Environment .env file */
    /* =============================================================================================== */


    /* =============================================================================================== */
    // Generating dummy content 
    /* =============================================================================================== */
    public function generateDummyContent()
    {
        return 'Hello';
    }
    /* =============================================================================================== */
    /* End of Generatring dummy content */
    /* =============================================================================================== */
}
