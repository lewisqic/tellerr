@extends('layouts.email')

@section('title', 'Reset Your Password')

@section('heading', 'Reset Password')

@section('content')

	<p>
		Click on the link below to reset your account password:
	</p>

	<p>
		<a href="{{ $url }}">{{ $url }}</a>
	</p>

@endsection