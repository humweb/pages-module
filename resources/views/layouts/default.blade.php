@section('content')
    {!! $content !!}
@show

@section('title')
    {!! $title !!}
@show

@section('styles')
    @if (!empty($js))
        <style>{!! $css !!}</style>
    @endif
@show

@section('header_scripts')
    @if (!empty($js))
        <script>{!! $js !!}</script>
    @endif
@show
