<?php namespace App\Http\Controllers;
use App\Todos;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class TodoController extends Controller
{
    //
    public function index()
    {
	   
	    $todos=Todos::all();
	   
	    return view('todo.index',compact('todos'));
	}
    public function add()
	   {
	      //

	      $todo=Request::all();
	      
	      Todos::create($todo);

		  return $todo;
		  //return Response::json(array('success'=>true,'last_id' =>$))
	   }
	public function remove($request){


		//$id=$request->id;
		Todos::destroy($request);
		//$response = $data->delete();
	}
	
	public function delete($request){
		$ids = explode(",",$request);
		foreach($ids as $id){
			Todos::destroy($id);
		}
		return $id;
	}
}
