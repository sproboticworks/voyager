@extends('voyager::master')

@section('css')
	<style type="text/css">
		#error_page {
			background-color: #ffffff !important;
			font-weight: bold;
			height: 450px;
			/*margin-top: 0px;*/
			text-align: center;
			padding: 120px;
			/*text-transform: uppercase;*/
		}
		#error_title
		{
			font-size: 40px;
			color:red;
		}
		#error_message{
			font-size: 24px;
		}
	</style>
@endsection

@section('content')
	<div id = "error_page" >
		<H3 id = "error_title" >Oops,</H3> <BR>
		<H4 id = "error_message" >You don't have permission for the operation
            <b>
                @if($operation)
                    {{ $operation->name }}
                @else
                    {{ $route }}
                @endif
            </b>
        </h4>
	</div>

@endsection

