@section('content')
    {!! Form::open(['route' => ['post.admin.pages.edit', $page->id]]) !!}
    {!! Form::hidden('parent_id', Request::old("parent_id", $page->parent_id)) !!}
    <div class="panel panel-default">
        <div class="panel-heading"><h4>{{$page->title}}</h4></div>
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#panel-general" data-toggle="tab">General</a></li>
                <li><a href="#panel-metadata" data-toggle="tab">Metadata</a></li>
                <li><a href="#panel-css" data-toggle="tab">CSS</a></li>
                <li><a href="#panel-js" data-toggle="tab">JS</a></li>
            </ul>


            <div class="tab-content">
                <div class="tab-pane active" id="panel-general">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <br/>
                                {!! Form::textarea('content', Request::old('content', $page->content), ['id' => 'htmleditor', 'class' => 'form-control ckeditor']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Title</label>
                                {!! Form::text('title', Request::old("title", $page->title), ['id' => 'title', 'class'=>'form-control']) !!}
                            </div>

                            {{ ! empty($parent_page) ? url($parent_page->uri) : url('/') }}/<span id="slug_uri"></span>
                            <hr>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                {!! Form::text('slug', Request::old("slug", $page->slug), ['id'=>'slug', 'tabindex' => '-1', 'class'=>'form-control']) !!}
                            </div>
                            <hr>
                            <div class="form-group">
                                {!! Form::label('published', 'Status') !!}
                                {!! Form::select('published', [0 => 'Draft', 1 => 'Live'], Request::old("published", $page->published), ['class' => 'select',
                                'style' => 'display:block']) !!}
                            </div>
                            <hr>
                            <div class="form-group">
                                {!! Form::label('layout', 'Layout') !!}
                                {!! Form::select('layout', $layouts, Request::old("layout", $page->layout), ['class' => 'select', 'style' => 'display:block']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="panel-metadata">
                    <div class="form-group">
                        {!! Form::label('tags', 'Tags') !!}
                        {!! Form::select('tags[]', $available_tags, Request::old("tags", $current_tags), ['id'=>'tags','placeholder'=>'Adtags..','multiple'=>'multiple', 'class' => 'input-tags']) !!}
                    </div>

                    <div class="form-group">
                        <label for="meta_title">Title</label>
                        {!! Form::text('meta_title', Request::old("meta_title", $page->meta_title), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('meta_description', 'Description') !!}
                        {!! Form::textarea('meta_description', Request::old("meta_description", $page->meta_description), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('meta_robots', 'Robots') !!}
                        {!! Form::select('meta_robots', [
                            'all'=>'No Restrictions',
                            'index'=>'Index', 'noindex'=>'Noindex',
                            'nofollow'=>'Nofollow',
                            'both'=>'Noindex'
                        ], Request::old("meta_robots", $page->meta_robots), ['id'=>'tags', 'class' => 'select', 'placeholder'=>'Robot options..']) !!}
                    </div>
                </div>

                <div class="tab-pane" id="panel-css">
                    <br/>
                    {!! Form::textarea('css', Request::old("css", $page->css), array('id' => 'css-editor', 'class' => 'form-control css_editor')) !!}
                </div>
                <div class="tab-pane" id="panel-js">
                    <br/>
                    {!! Form::textarea('js', Request::old("js", $page->js), array('id' => 'js-editor', 'class' => 'js_editor form-control')) !!}
                </div>
            </div>

        </div>
        <div class="panel-footer">
            {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
            <a href="{{ route('get.admin.pages.index') }}" class="btn btn-default">Cancel</a>
            <label class="inline-checkbox" title="Set as index page" data-toggle="tooltip">
                &nbsp {!! Form::checkbox('is_index', 1, Request::old('is_index', $page->is_index)) !!} Default page
            </label>
        </div>
    </div>
    {!! Form::close() !!}


    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ace/ace.js') }}"></script>
    <script src="{{ asset('js/ace/mode-javascript.js') }}"></script>
    <script src="{{ asset('js/ace/mode-css.js') }}"></script>
    <script src="{{ asset('js/ace/theme-tomorrow_night.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('.js_editor').ace({height: 300, width: '100%', theme: 'tomorrow_night', lang: 'javascript'});
            $('.css_editor').ace({height: 300, width: '100%', theme: 'tomorrow_night', lang: 'css'});
            $('#slug').on('change', function (e) {
                $('#slug_uri').text(this.value);
            }).trigger('change');
            $('#title').slugify();
        });
    </script>
@endsection

@section('style')
    <style>
        .tab-pane {
            padding: 25px 0;
        }

        .css_editr, .js_editor {
            position: relative;
            height: 300px;
            font-family: Monaco, Menlo, 'Ubuntu Mono', Consolas, source-code-pro, monospace;
        }
    </style>
@endsection