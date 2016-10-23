@section('title')
    Pages -
    @parent
@stop

{{-- Content --}}
@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Pages</h4>
        </div>
        <div class="panel-body">
            @if ( ! empty($content))

                <div class="">
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            {!! $content !!}
                        </ol>
                    </div>
                </div>
            @else
                <h5>No pages added yet..</h5>
            @endif

        </div>

        <script src="{{ asset('js/jquery.nestable.js') }}"></script>

        <script>
            $(function(){
                var updateOutput = function(e){
                    var list = e.length ? e : $(e.target), output = list.data('output');
                    if (window.JSON) {
                        $.post('/admin/pages/sort', {
                                    _token: '{{ Session::token() }}',
                                    pages: window.JSON.stringify(list.nestable('serialize'))
                                },
                                function(data){
                                    console.log(data)
                                }, 'json'
                        );
                    }
                };

                $('#nestable').nestable({
                    maxDepth: 10
                }).on('change', updateOutput);

            });
        </script>

@show