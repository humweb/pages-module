@section('content')
	{!! $content !!}
@show

@section('styles')
	@if ($css != '')
		<style>{{ $css }}</style>
	@endif
@show

@section('header_scripts')
	@if ($js != '')
		<!-- Page JS -->
		<script>{{ $js }}</script>
	@endif
@show
