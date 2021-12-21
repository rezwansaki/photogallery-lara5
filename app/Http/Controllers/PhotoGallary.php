<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Image;
use App\Album;
use App\User;
use App\Setting;
use Auth;

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
        try {

            $this->showNotifyFromSettings(); //check if information is not correct 

            if ($this->totalImageToDisplay == '') {
                $this->totalImageToDisplay = 20;
            }

            $settings = Setting::all()->first();

            if ($settings == '') //if query is empty  
            {
                $this->setDataForSettings();  //to set default data in settings table 
                $this->totalImageToDisplay = 20; //if settins table is empty then default value for the displaying image 
                $albums = Album::all();
                $imgs = Image::orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
                return view('welcome')->with('imgs', $imgs)->with('albums', $albums);
            } else {
                $this->totalImageToDisplay = $settings->total_images_to_display; //totalImageToDisplay value get value from database when settings table is not empty 
                $albums = Album::all();
                $imgs = Image::orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
                return view('welcome')->with('imgs', $imgs)->with('albums', $albums);
            }
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }


    //selected album 
    public function selectedAlbum($id)
    {
        try {

            $albums = Album::all();

            $imageFromAlbum = Image::orderBy('id', 'DESC')->where('album_id', $id)->paginate($this->totalImageToDisplay);

            return view('albumImages')->with('imageFromAlbum', $imageFromAlbum)->with('albums', $albums);
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //single image 
    public function singleImage($id)
    {
        try {

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
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //search
    public function search(Request $request)
    {
        try {

            $albums = Album::all();
            $srch = $request->search;
            $query = Image::where('name', 'like', "%" . $srch . "%")->orwhere('display_name', 'like', $srch . "%")->orderBy('id', 'DESC')->paginate($this->totalImageToDisplay);
            if ($query->isEmpty()) {
                return redirect('/')->with('messagefail', 'Image not found!');
            } else {
                return view('searchresult')->with('srchResult', $query)->with('srchWord', $srch)->with('albums', $albums);
            }
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }
    //======================================== Images ========================================

    //edit image info
    public function edit($id)
    {
        try {

            $imgEdit = Image::find($id);

            //get album list
            $albums = Album::all();

            return view('editImage')->with('imgEdit', $imgEdit)->with('albums', $albums);
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

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
        try {

            if ($request->hasFile('file')) { //max 921600bytes or, 900KB - this value will be changed dynamically from settings

                foreach ($request->file as $file) {

                    //file store to the destination folder as a new name 
                    $fileExt = $file->getClientOriginalExtension();
                    $fileSize = $file->getClientSize();

                    //file store to the destination folder as a new name 
                    $fileName2 = $file->getClientOriginalName(); //get the file name with extension 

                    $without_extension = basename($fileName2, '.' . $fileExt); //'index.html' to 'index' 

                    //remove '+' and other special sign including space from file name and replace from '_' 
                    $remove = array("+", "^", " ", "%", "=", "*", ",", "|");
                    $fileName = str_replace($remove, "_", $without_extension); //filename without extension 
                    //end of remove '+' and other special sign including space from file name and replace from '_' 

                    //reduce file name size if file name is big 
                    $fileNameLength = strlen($fileName);
                    if ($fileNameLength >= 20) {
                        $fileName = substr($fileName, 0, 20);
                        $fileName = $fileName . '.' . $fileExt;
                    } else {
                        $fileName = $fileName . '.' . $fileExt; //if file name length is less then 20 
                    } //end of file name must be less than or equal to 80 characters
                    //end of reduce file name size if file name is big 

                    $fileToSave = date("Ymdhmsa") . '_' . $fileName; //actual file name with extension which will be stored 


                    //album id
                    $albumId = Album::select('id')->where('name', $request->album)->get();
                    foreach ($albumId as $album_Id) {
                        $selected_Album_Id = $album_Id->id;
                    }

                    //store all data in Post model 
                    $dataSave = new Image;

                    $dataSave->name = $fileToSave;
                    $dataSave->image_views = 1; //count single image views and default value is 1 for better result 

                    if ($request->display_name == '') {  //if display_name is empty then 
                        if ($fileNameLength >= 20) {
                            $dataSave->display_name = substr($fileName, 0, 20); //only first to 20 characters will be stored 
                        } else {
                            $dataSave->display_name = substr($fileName, 0, $fileNameLength); //only first to 20 characters will be stored 
                        }
                    } else { //if display_name is not empty then 
                        $displayNameLength = strlen($request->display_name);
                        if ($displayNameLength >= 20) {
                            $dataSave->display_name = substr($request->display_name, 0, 20);
                        } else {
                            $dataSave->display_name = substr($request->display_name, 0, $displayNameLength);
                        }
                    }

                    if ($request->description == '') {
                        $fileLength2 = strlen($fileName);
                        if ($fileLength2 >= 150) {
                            $dataSave->description = substr($fileName, 0, 150); //only first to 20 characters will be stored 
                        } else {
                            $dataSave->description = substr($fileName, 0, $fileLength2); //only first to 20 characters will be stored 
                        }
                    } else {
                        $displayDescLength = strlen($request->description);
                        if ($displayDescLength >= 150) {
                            $dataSave->description = substr($request->description, 0, 150);
                        } else {
                            $dataSave->description = substr($request->description, 0, $displayDescLength);
                        }
                    }

                    $dataSave->size = $file->getClientSize(); //file size in bytes 
                    $dataSave->album_id = $selected_Album_Id;
                    $dataSave->uploaded_date = date('Y-m-d h:m:s');
                    $dataSave->uploaded_by = $request->uploaded_by;

                    $dataSave->save(); //data will be saved in database 

                    //if data save successfully then file will be uploaded 
                    $file->storeAs('/images', $fileToSave);
                    $file->storeAs('/images/thumb', 'thumb_' . $fileToSave);

                    //to make a thumbnail image 
                    $img = \Images::make('upload/images/thumb/thumb_' . $fileToSave)->resize(500, 400)->save('upload/images/thumb/thumb_' . $fileToSave, 52);
                }
                return redirect('/')->with('message', 'Data has been successfully Inserted!');
            } else {
                return Redirect::back()->with('messagefail', 'Data has not been Inserted! Please, select an image which is not more than 900 KB and must be jpg or jpeg');
            }
        } catch (\Exception $e) {
            //return Redirect::back()->with('messagefail',$e->getMessage());  //for the developer to details report 
            return Redirect::back()->with('messagefail', 'Please check all of requirements. Such as Display Name will be less than 80 characters and Description will be less than 150 characters. Do not keep these blank. And the plus + sign is not accepted as a file name.');  //for the user 
        }
    } //store images


    //update images
    public function update(Request $request, $id)
    {
        try {


            //store all data in Post model 
            $dataSave = Image::find($id);

            $dataSave->name = $request->image_name;


            //display_name 
            $oldDisplayName = $request->display_name;

            if ($request->display_name == '') {  //if display_name is empty then 
                $dataSave->display_name = $oldDisplayName;
            } else { //if display_name is not empty then 
                $displayNameLength = strlen($request->display_name);
                if ($displayNameLength >= 20) {
                    $dataSave->display_name = substr($request->display_name, 0, 20);
                } else {
                    $dataSave->display_name = substr($request->display_name, 0, $displayNameLength);
                }
            }
            //end of display_name 


            //description 
            $oldDescription = $request->description;

            if ($request->description == '') {  //if display_name is empty then 
                $dataSave->description = $oldDescription;
            } else { //if display_name is not empty then 
                $descriptionLength = strlen($request->description);
                if ($descriptionLength >= 150) {
                    $dataSave->description = substr($request->description, 0, 150);
                } else {
                    $dataSave->description = substr($request->description, 0, $descriptionLength);
                }
            }
            //end of description 



            //album id
            $albumId = Album::select('id')->where('name', $request->album)->get();
            foreach ($albumId as $album_Id) {
                $selected_Album_Id = $album_Id->id;
            }

            $dataSave->album_id = $selected_Album_Id;
            $dataSave->uploaded_date = date('Y-m-d h:m:s');
            $dataSave->uploaded_by = $request->uploaded_by;

            $dataSave->update();

            return redirect('/')->with('message', 'Data has been successfully Updated!');
        } catch (\Exception $e) {
            //return Redirect::back()->with('messagefail',$e->getMessage());  //for the developer to details report 
            return Redirect::back()->with('messagefail', 'Please check all of requirements. Such as Display Name will be less than 80 characters and Description will be less than 150 characters. Do not keep these blank. And the plus + sign is not accepted as a file name.');  //for the user 
        }
    } //update images 



    //distroy or remove the image with database row 
    public function destroy($id)
    {
        try {

            $selectedRow = Image::find($id); //find the row 
            File::delete(public_path() . '/upload/images/' . $selectedRow->name);  //delete the file 
            File::delete(public_path() . '/upload/images/thumb/thumb_' . $selectedRow->name);  //delete the thumbnail image file 
            $selectedRow->delete(); //delete the row 
            return redirect('/')->with('message', 'Data has been successfully deleted!');
        } catch (\Exception $e) {
            return Redirect::back()->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }
    //======================================== /Images ========================================


    //delete all images from images folder with images table 
    public function deleteAllImages()
    {
        try {

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
        } catch (\Exception $e) {
            return Redirect::back()->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }


    //======================================== Album ========================================
    //show album 
    public function showAlbum()
    {
        try {

            $imgs = Album::paginate($this->totalImageToDisplay);
            if ($imgs->count() < 1) {
                return Redirect('/')->with('messagefail', 'No any album is available! You have to create album.');
            } else {
                return view('showAlbum')->with('imgs', $imgs);
            }
        } catch (\Exception $e) {
        } {
            return Redirect::back()->with('messagefail', $e->getMessage());
        } //try...catch
    }

    //album creating 
    public function createalbum()
    {
        try {

            return view('albumcreate');
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //store album 
    public function storeAlbum(Request $request)
    {
        try {


            //================= Store Album Code ==============================================
            if ($request->hasFile('file')) {
                $file = $request->file;
                $albumName = $request->album_name;

                //album name must be less than or equal to 20 characters 
                $albumNameLength = strlen($albumName);
                if ($albumNameLength >= 20) {
                    return Redirect::back()->with('messagefail', 'Album name must be less than or equal to 20 characters.');
                } //end of album name must be less than or equal to 20 characters

                //to remove white space from album name 
                $removeFromAlbumName = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "+", "=", "/", "?", ".", ">", ",", "<", "|", " ");
                $albumName = str_replace($removeFromAlbumName, '', $albumName);
                //end of - to remove white space from album name 

                //store all data in Post model 
                $dataSave = new Album;
                $dataSave->name = $albumName; //$request->album_name;
                $dataSave->cover_image = $albumName . '.jpg';

                //file store to the destination folder as a new name 
                $file = $request->file;
                $fileName = $file->getClientOriginalName();

                //check existing album 
                $newAlbumName = $albumName;
                $checkExistingAlbum = Album::where('name', '=', $newAlbumName)->get();
                if (($checkExistingAlbum->isEmpty()) && ($albumName != '')) //if query is null or empty then true else false 
                {
                    $file->storeAs('/albums', $albumName . '.jpg');
                    //after upload image for album then save album data in database 
                    $dataSave->save();

                    //to make a thumbnail image 
                    $img = \Images::make('upload/albums/' . $albumName . '.jpg')->resize(500, 400)->save('upload/albums/' . $albumName . '.jpg', 72);

                    //return Redirect::back()->with('message','Album has been successfully Created!');
                    return redirect('album/showAlbum')->with('message', 'Album has been successfully Created!');
                } else {
                    return Redirect::back()->with('messagefail', 'This album is alreay existed, please try to create with new name! or, album field is empty!');
                }
            } else {
                return Redirect::back()->with('messagefail', 'Album has not been Created! May be you can not choose any file or, album is already existed.');
            }
            //================= End Of Store Album Code ==========================================



        } catch (\Exception $e) {
            //return Redirect::back()->with('messagefail',$e->getMessage());  //for the developer to details report 
            return Redirect::back()->with('messagefail', 'Please check all of requirements. Such as Album Name will be less than or equal to 20 characters without any space. Do not keep these blank. You have to choose any file for album cover.');  //for the user 
        }
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
        try {



            //check the checkbox checeked or not 
            $checked = 'false';
            if (isset($_POST['cover_image_change'])) { //if checked then return true else return false 

                $checked = 'true';
            } else {
                $checked = 'false';
            } //check the checkbox checeked or not 


            $file = $request->file;
            $albumName = $request->album_name;

            //album name must be less than or equal to 20 characters 
            $albumNameLength = strlen($albumName);
            if ($albumNameLength >= 20) {
                return Redirect::back()->with('messagefail', 'Album name must be less than or equal to 20 characters.');
            } //end of album name must be less than or equal to 20 characters

            //to remove white space from album name 
            $removeFromAlbumName = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "+", "=", "/", "?", ".", ">", ",", "<", "|", " ");
            $albumName = str_replace($removeFromAlbumName, '', $albumName);
            //end of - to remove white space from album name 


            //store all data in Post model 
            $dataSave = Album::find($id);
            $dataSave->name = $albumName;
            $dataSave->cover_image = $albumName . '.jpg';

            //check existing album 
            $newAlbumName = $request->album_name;
            $checkExistingAlbum = Album::where('name', '=', $newAlbumName)->get();
            if (($checkExistingAlbum->isEmpty()) && ($albumName != '')) //if query is null or empty then true else false 
            {
                $selectedRow = Album::find($id);
                File::move(public_path() . '/upload/albums/' . $selectedRow->name . '.jpg', public_path() . '/upload/albums/' . $albumName . '.jpg');
                $dataSave->update();
                return redirect('album/showAlbum')->with('message', 'Album has been successfully Updated!');
            } else {
                return Redirect::back()->with('messagefail', 'This album is alreay existed, please try to create with new name! or, album field is empty!');
            }
        } catch (\Exception $e) {
            //return Redirect::back()->with('messagefail',$e->getMessage());  //for the developer to details report 
            return Redirect::back()->with('messagefail', 'Please check all of requirements. Such as Album Name will be less than or equal to 20 characters without any space. Do not keep these blank. You have to choose any file for album cover.');  //for the user 
        }
    }

    //distroy or remove the image with database row 
    public function destroyAlbum($id)
    {
        try {

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
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
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
        try {

            //get currect logged in user data  
            $userID = Auth::user()->id;
            $userName = Auth::user()->name;
            $userEmail = Auth::user()->email;

            $dataSave = User::find($userID);

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
                $file = $request->file;
                $fileName = $request->profile_name . '.jpg';
                $file->storeAs('/users', $fileName);
                //to make a thumbnail image 
                $img = \Images::make('upload/users/' . $fileName)->resize(500, 500)->save('upload/users/' . $fileName, 90);

                return redirect('/dashboard'); //->with('message','Data has been successfully Updated!')
            } else { //if file is not selected 
                $getOldImageName = $dataSave->name . '.jpg'; //to store old images before updated 
                //store all data in Post model 
                $dataSave->name = $request->profile_name;
                $dataSave->email = $request->profile_email;

                $makeNewImageName = $request->profile_name . '.jpg'; //to store old images before updated 

                $dataSave->update(); //update user data from the database 

                //copy old image with a new name 
                $img = \Images::make('upload/users/' . $getOldImageName)->resize(500, 500)->save('upload/users/' . $makeNewImageName, 90);

                //user name change then delete old user image 
                if ($makeNewImageName != $getOldImageName) {
                    File::delete(public_path() . '/upload/users/' . $getOldImageName);  //delete old user image 
                }

                return redirect('/dashboard'); //->with('message','Data has been successfully Updated!');
            }
        } catch (\Exception $e) {
            return Redirect::back()->with('message', $e->getMessage());
        }
    } //update profile 


    //Reset App to reset everything to use this project new one. It removes everything as like factory reset 
    /* It removes everything which are created by admin. It removes all uploaded images, all albums, data from database and also removes users with their images. It will be a fresh gallary to use new one.*/
    public function resetApp()
    {
        try {

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
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }


    /* ===================================== Settings =============================================== */
    //settings such as project name, maximum uploaded file size, email for password reset system 
    public function setDataForSettings()  //to set default data for settings for the first time 
    {
        try {
            $dataSave = new Setting;
            $dataSave->project_name = 'PhotoGallary';
            $dataSave->email_to_reset_password = 'Null@Null.com'; //'alinsworld2010@gmail.com';
            $dataSave->password_of_email = 'Null'; //'aw002017';
            $dataSave->max_uploaded_file_size = 900;
            $dataSave->total_images_to_display = 20;

            $dataSave->save();
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    public function showSettings()  //to display settings page with existing data 
    {
        try {

            $settings = Setting::all()->first();

            return view('settings')->with('settings', $settings);
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }


    public function doSettings(Request $request, $id)  //to update data of settings 
    {
        try {

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
            $settings->password_of_email = $passwordOfEmail;
            $settings->max_uploaded_file_size = $maxFileSize;
            $settings->total_images_to_display = $totalImgDisplay;

            $settings->update();

            $this->setEnvValue(); //to change the value of .env file 

            return redirect('/');
        } catch (\Exception $e) {
            //return Redirect::back()->with('messagefail',$e->getMessage());  //for the developer to details report 
            return Redirect::back()->with('messagefail', 'Error!! Please check all of requirements.');  //for the user 
        } //try...catch 
    }

    //get notification message from settings 
    public function showNotifyFromSettings()
    {
        try {

            $settings = Setting::all()->first();

            $projectName = $settings->project_name;
            $email = $settings->email_to_reset_password;
            $password = $settings->password_of_email;
            $maxUploadedFileSize = $settings->max_uploaded_file_size;
            $totalImagesToDisplay = $settings->total_images_to_display;

            //check for valid information 
            if ($projectName == '' || $email == '' || $email == 'Null@Null.com' || $password == '' || $password == 'Null' || $maxUploadedFileSize < 1 || $totalImagesToDisplay < 1) {
                return redirect('/')->with('messagefail', 'Please, go to the settings and input all valid information with varified email and password. ' . $email);
            }
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    } //end of get notification message from settings 
    /* ================================== End Of Settings ============================================ */


    /* =============================================================================================== */
    /* Changing Value of Environment .env file */
    /* =============================================================================================== */
    public function setEnvValue()  //in here set the key with value 
    {
        try {

            $settings = Setting::all()->first();

            $env_update = $this->changeEnv([
                'APP_NAME'   => $settings->project_name,  //'APP_NAME'   => 'PhotoGallary', last value don't use coma (,)
                'MAIL_USERNAME'   => $settings->email_to_reset_password,
                'MAIL_PASSWORD'   => $settings->password_of_email
            ]);
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }

    //$this->setEnvValue();  //to change the value of .env file 

    protected function changeEnv($data = array())  //tricky part, nothing to do in here  
    {
        try {

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
        } catch (\Exception $e) {
            return Redirect('/')->with('messagefail', $e->getMessage());  //for the developer to details report 
        }
    }
    /* =============================================================================================== */
    /* End of Changing Value of Environment .env file */
    /* =============================================================================================== */
}
