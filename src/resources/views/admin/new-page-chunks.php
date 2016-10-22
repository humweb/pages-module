<br />

<div id="page-chunks-wrapper">
<ul id="page-chunks" class="no-bullet">
	@if (isset($page_chunks))
	@foreach ($page_chunks as $chunk)
	<li class="page-chunk">
		<div class="row">
			<div class="large-2 columns">
				{{ Form::text('chunk['.$chunk->id.'][slug]', $chunk->slug, ['class' => "label", 'placeholder'=>"slug"]) }}
			</div>
			<div class="large-3 columns">
				{{ Form::text('chunk['.$chunk->id.'][class]', $chunk->class, ['class'=>"label", 'placeholder'=>"class"]) }}
			</div>
			<div class="large-3 columns">
				{{ Form::select('chunk['.$chunk->id.'][type]', array(
					'html' => 'html',
					'markdown' => 'markdown',
					'wysiwyg-simple' => 'wysiwyg-simple',
					'wysiwyg-advanced' => 'wysiwyg-advanced',
				), $chunk->type, ['class'=>'chunk_type']) }}
			</div>
			<div class="right large-4 columns">
				<a href="javascript:void(0)" class="remove-chunk btn red">{{ trans('global.remove') }}</a>
				<span class="sort-handle"></span>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
			<span class="chunky">
			{{ Form::textarea('chunk['.$chunk->id.'][content]', $chunk->content, array('id' => $chunk->id, 'rows' => 20, 'class'=> $chunk->type, 'style' => 'width:100%')) }}</span>
		</div>
	</li>
	@endforeach
	@endif
</ul>
<a class="add-chunk btn orange" href="#">Add Chunk</a>
</div>


<script>
$(function(){


	var Chunks = {};
	Chunks.decorators = {};

	Chunks.decorators.foundation = {
		"row": 'row',
		"column": 'column {{prefix}}{{size}}',
		// "column-lg": 'column large-{{size}}',
		// "column-med": 'column medium-{{size}}',
		// "column-sm": 'column small-{{size}}',
		"offset": '{{prefix}}offset-{{size}}',
		"grid-prefixes": {
			"lg": 'large-',
			"med": 'medium-',
			"sm": 'small-',
		},

		addColumn: function(size, prefix, offset){

		}
	};
	function addCss(row, type, opts){
		var str = Chunks.decorators.foundation[type];
		row.css.push(tmpl.build(obj, opts));
	}

	Chunks.Builder = {
		addColumn: function(size, prefix, options){
			prefix = prefix||'lg';
			options = options||{};

			var str = [
				'<div class="',
				''
				];

		}
	};

	// // Hide the huge textarea
	// $('#page-chunks').on('mousedown', '.sort-handle', function(e){
	// 	$('.chunky').hide();
	// });

	// // Attach sortable to the page chunks ul
	// $("#page-chunks").sortable({
	// 	opacity:0.3,
	// 	handle: ".sort-handle",
	// 	placeholder: 'sort-placeholder',
	// 	forcePlaceholderSize: true,
	// 	items: 'li',
	// 	cursor:"move",
	// 	start: function () {
	// 		$('.wysiwyg-advanced, .wysiwyg-simple').each(function() {
	// 			$(this).ckeditorGet().destroy();
	// 		});
	// 	},
	// 	stop: function(ev,ui){
	// 		$('.chunky').show();
	// 		pyro.init_ckeditor();
	// 	}
	// });

	// add another page chunk

	$('#page-chunks-wrapper')
		.on('click', '.add-chunk', function(e){
			e.preventDefault();

			// The date in hexdec
			key = 'new-'+Number(new Date()).toString(16).substr(-5, 5);

			$('#page-chunks').append([
				'<li class="page-chunk">',
				'<div class="row">',
				'<div class="large-3 columns">',
				'<input class="label" type="text" name="chunk[' + key + '][slug]" value="' + key + '" placeholder="Slug" />',
				'</div>',
				'<div class="large-3 columns">',
				'<input class="label" type="text" name="chunk[' + key + '][class]" placeholder="CSS Class(es)" />',
				'</div>',
				'<div class="large-3 columns">',
				'<select name="chunk[' + key + '][type]" class="chunk-type">',
				'<option value="html">html</option>',
				'<option value="markdown">markdown</option>',
				'<option value="wysiwyg-simple">wysiwyg-simple</option>',
				'<option selected="selected" value="wysiwyg-advanced">wysiwyg-advanced</option>',
				'</select>',
				'</div>',
				'<div class="right large-3 columns">',
				'<a href="javascript:void(0)" class="remove-chunk btn red">Remove</a>',
				'<span class="sort-handle"></span>',
				'</div></div>',
				'<div class="row"><div class="right large-12 columns">',
				'<span class="chunky"><textarea id="' + key + '" class="pages wysiwyg-advanced" rows="20" style="width:100%" name="chunk[' + key + '][content]"></textarea>',
				'</span></div></div></li>'].join(""));

				CKEDITOR.replace(key, {startupFocus: true});

			// initialize the editor using the view from fragments/wysiwyg.php
			//pyro.init_ckeditor();
			//$("#page-chunks").sortable("refresh");
		})
		.on('click', '.remove-chunk', function(e) {
			e.preventDefault();

			var removemsg = $(this).attr('title');

			if (confirm(removemsg || 'Are you sure?'))
			{
				$(this).closest('li.page-chunk').slideUp('slow', function(){ $(this).remove(); });
				if ($('#page-content').find('li.page-chunk').length < 2) {
				}
			}
		})
		.on('change', '.chunk_type',function() {
			var chunk = $(this).closest('li.page-chunk'),
				textarea = $('textarea', chunk),
				type = $(this).find('option:selected').val(),
				id = textarea.attr('id');
			// Set up the new instance
			textarea.removeClass('wysiwyg-simple wysiwyg-advanced markdown html').addClass(type);

			// Destroy existing WYSIWYG instance
			if ( type != 'wysiwyg-simple' && type != 'wysiwyg-advanced')
			{
				var instance = CKEDITOR.instances[id];
				instance && instance.destroy();
			} else {
				if ( !(id in CKEDITOR.instances))
				{
					CKEDITOR.replace(textarea[0],{startupFocus: true});
				}
			}


			//pyro.init_ckeditor();
		});
	$('.chunk_type').trigger('change');
});
$(document).load(function(){

});
</script>