<?php

class HomeController extends BaseController {

/*
|--------------------------------------------------------------------------
| Default Home Controller
|--------------------------------------------------------------------------
|
| You may wish to use controllers instead of, or in addition to, Closure
| based routes. That's great! Here is an example controller method to
| get you started. To route to this controller, just add the route:
|
|	Route::get('/', 'HomeController@showWelcome');
|
*/
	public function index(){
		return View::make('index');
	}

	public function listPage(){

		$userid =  Input::get('userid','');

		$page = DB::table('page')->where('userid',$userid)->get();

		if(empty($page)){
			$return = array(
        	'status' => 201,
        	'msg' => 'No Page'
        	);
		}
		else
		{
			$temp=array();

			foreach ($page as $key => $value) {
				$temp[$key] = array(
	        	'name' => $page[$key]->name ,
	        	'id' => $page[$key]->id,
	        	'updated_at' => $page[$key]->updated_at );
			}

			$return = array(
        	'status' => 200,
        	'msg' => 'Success',
        	'data' => $temp
        	);
		}

		echo json_encode($return);
	}

	public function publishPage($id)
	{
		//get page id and content_json and name
		$page_id = $id;
		$page = Page::find($page_id);

		var_dump($page);

 		//echo json_encode($return);

	}

	public function loadPage(){
		//get page id
		$page_id =  Input::get('page_id','');

		$page = DB::table('page')->where('id', $page_id)->first();
		
		if(empty($page)){
			$return = array(
        	'status' => 201,
        	'msg' => 'No File'
        	);
		}
		else
		{
			$return = array(
        	'status' => 200,
        	'msg' => 'Success',
        	'data' => $page
        	);
		}

		echo json_encode($return);
	}

	public function deleteImage(){

		$get_path = Input::get('link','');

		if(file_exists($get_path))
		{	
			unlink($get_path);
			$return = array(
        	'status' => 200,
        	'msg' => 'Success'
        	);
		}
		else
		{
			$return = array(
        	'status' => 201,
        	'msg' => 'No File'
        	);
		}
		
		echo json_encode($return);
	}

	public function deletePage(){
		//get page id
		$page_id= 1;
		$page = Page::find($page_id);

		if(!empty($page))
		{
			$page->delete();
			$return = array(
        	'status' => 200,
        	'msg' => 'Success'
        	);

		}
		else
		{
			$return = array(
        	'status' => 201,
        	'msg' => 'Failed to delete'
        	);
		}

 		echo json_encode($return);

	}

	public function savePage(){
		//get page id and content_json and name
		$page_id = Input::get('id',1);
		$content_json =  Input::get('content_json','');
		$page_setup =  Input::get('page_setup','');
		$page_name =  Input::get('page_name','');
		$page = Page::find($page_id);

		if(!empty($page))
		{
			$page->content_json = $content_json;
			$page->page_setup = $page_setup;
			$page->name = $page_name;
			$page->save();
			$return = array(
        	'status' => 200,
        	'msg' => 'Success'
        	);
		}
		else
		{
			$return = array(
        	'status' => 201,
        	'msg' => 'Failed to save'
        	);
		}
 		

 		echo json_encode($return);

	}

	public function newPage(){
		//get page name for insert to db

		$pagename = Input::get('pagename','');

		if(!empty($pagename)){
			$page = new Page;
			$page->userid = 2;
			$page->name = $pagename;
			$page->save();


			$cek = DB::table('page')->orderBy('id','desc')->first();
			$path = public_path().'\\pages\\'.$cek->id;

			
		
			$return = array();
			
			if(file_exists($path)){	
				 $return = array(
	        	'status' => 201,
	        	'msg' => 'Already Exists'
	        	);
			}
			else
			{
				//new folder , insert db
			
				File::makeDirectory($path, $mode = 0777, true, true);

				 $return = array(
	        	'status' => 200,
	        	'msg' => 'Success',
	        	'data'=> array(
	        			'id' => $cek->id
	        		)
	        	);
			}
		}
		else{
			 $return = array(
        	'status' => 201,
        	'msg' => 'No Page Name'
        	);
		}
		echo json_encode($return);
	}

	public function doUpload(){
		//get id page
		$id =  Input::get('id','');
		$name =  Input::get('name','');
		$destinationPath = public_path().'\\pages\\' . $id;

		$return = array();

		$input = Input::all();
		$maxsize = 1000*1000;
		$file = Input::file('photo');

		if(!empty($id)){
			if(!empty($file)){
				if ($file->isValid())
					{
					    //
			    		$size = $file->getSize();
						$mime = $file->getMimeType();
					    if($mime == 'image/jpeg' || $mime =='image/png')
					    {
							if($size< $maxsize){
						    	//$destinationPath =  public_path().'/pages/3'; #dummy
						    	$temp = explode('.', $file->getClientOriginalName());

						        $filename        = $name . "." . $temp[sizeof($temp)-1];
						        $uploadSuccess   = $file->move($destinationPath, $filename);
						       
						        $return = array(
						        	'status' => 200,
						        	'msg' => 'Success',
						        	'data' => array(
						        		'filename' => $filename
						        		)
						        	);
						    }
						    else
						    {
						    	  $return = array(
						        	'status' => 201,
						        	'msg' => 'Oversize'
						        	);
						    }
					    }
					    else
					    {
					    	  $return = array(
						        	'status' => 201,
						        	'msg' => 'Wrong Type'
						        	);
					    }
					    
					}
					else
					{
						  $return = array(
						        	'status' => 201,
						        	'msg' => 'Invalid file'
						        	);
					}

			}
			else
			{
					  $return = array(
						        	'status' => 201,
						        	'msg' => 'Empty Image'
						        	);
			}
		

		}else{  
			$return = array(
					        	'status' => 201,
					        	'msg' => 'Empty id'
					        	);
		}
		echo json_encode($return);
	}
	
	function publish($page_id){
		//get page id
		$data["page_id"] = $page_id;

		return View::make('publish', $data);
	}
}
