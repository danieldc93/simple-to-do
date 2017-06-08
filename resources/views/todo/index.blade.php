<html>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title> To Do List</title>
	
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<style>
		body{
			font-size: 20px;
		}
		#button{
			margin-top:25px;
		}
		li{
			list-style: none;
		}
		.del{
			cursor: pointer;
		}
		.clear{
			clear:both;
		}
	</style>
	<body>
		
	    <div class="form-group col-md-6">
	        {!! Form::label('Todo', 'Todo:') !!}
	        {!! Form::text('title',null,['class'=>'form-control','id'=>'title']) !!}
	       
	    </div>
	  
		<div class="form-group col-md-2" id="button">
      		{!! Form::submit('Add Todo', ['class' => 'btn btn-primary form-control', 'id' => 'add-button']) !!}
    	</div>
    	<div class="clear"></div>
    	<div class="col-md-6" id="list">
	    	Type in a new todo...<br>
    		@foreach ($todos as $todo)
	    		<li>{!! Form::checkbox('list[]',$todo->id) !!} {{ $todo->title}}  <a class="del" value="{{$todo->id}}">[x]</a></li>
	    	@endforeach
			
    	</div>
    	<div class="clear"></div>
    	<?php
				if(empty($todo)){
					$todo_id = 0;
				}
				else{
					$todo_id = $todo->id +1;
				}	
			?>
			{!! Form::hidden('id',$todo_id,['id' => 'id']) !!}
    	<div class="col-md-2">
	    	<br>
			{!! Form::submit('Delete Selected', ['class' => 'btn btn-primary form-control', 'id' => 'batch-delete']) !!}
    	</div>
	</body>
	<script type="text/javascript">
		
		$(document).ready(function(){
			$('#title').val("");
		});		
		$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
		function add(){
			
			var title = $('#title').val();
			var id = parseInt($('#id').val())+1;
			//alert(title);
			$.ajax({
				type:"POST",
				url:'/todo/add',
				data:{"id":id,"title":title},
				dataType:'json',
				success:function(responddata){
$("#list").append("<li><input type='checkbox' name='list[]' value='"+responddata.id+"'> "+responddata.title+" <a class='del' value='"+responddata.id+"'>[x]</a></li>");
					 $('#id').val(id)+1;
					 $('#title').val("");
				},
				error:function(responddata){
					
				}
			});

		}
		
		
		$('#add-button').click(function(e){
			e.preventDefault;
			add();
		});
		$('#title').keypress(function(e){
			if (e.which == 13){
				add();
			}
		})
		$(document).on('click','.del',function(events){
			var id = $(this).attr("value");	
			var $li = $(this).closest('li');
			
			$.ajax({
				type : 'DELETE',
				url : '/todo/remove/'+id,
				data:{"id":id,"_method":"DELETE"},
				success:function(result){
					$li.remove();
				},
			});
		});
		$('#batch-delete').click(function(){
			var sel = $('input[type=checkbox]:checked').map(function(_, el) {
	        return $(el).val();
	    }).get();
	    
	    $.ajax({
		    type : 'POST',
		    url : '/todo/delete/'+sel,
		    data:{"sel":sel},
		    success:function(result){
			  	   $('input[type=checkbox]:checked').parent().remove();
		    },
	    });
	   
		});
	
		
	</script>
</html>