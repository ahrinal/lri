@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            <div class="form-group  col-md-12 ">    
                                <label class="control-label" for="name">Institusi</label>
                                @if(isset($user->institution_id))  
                                    <select class="form-control" name="institution_id" data-get-items-route="{{url('')}}/admin/news/relation" data-get-items-field="news_belongsto_lri_type_relationship" data-method="add" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                        <option value="{{$user->institution_id}}" data-select2-id="12" selected>{{$user->institution->nama}}</option>
                                    </select>
                                @else
                                    @if($dataTypeContent->institution_id == "")
                                    <select class="form-control select2-ajax select2-hidden-accessible" id="institution_id" name="institution_id" data-get-items-route="{{url('')}}/admin/news/relation" data-get-items-field="news_belongsto_lri_type_relationship" data-method="add" data-select2-id="4" tabindex="-1" aria-hidden="true">
                                        <option value="" data-select2-id="6">None</option>
                                    </select>
                                    @else
                                    <select class="form-control" id="institution_id" name="institution_id" >
                                        <option value="" data-select2-id="6">None</option>
                                        @foreach($institution as $result)
                                        <option value="{{$result->id}}" data-select2-id="6" 
                                        @if($result->id == $dataTypeContent->institution_id)
                                            selected
                                        @endif
                                        >{{$result->name_id}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="name">Title</label>
                                <input required="" type="text" class="form-control" name="title" placeholder="Title" value="{{$dataTypeContent->title}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="name">Judul</label>
                                <input type="text" class="form-control" name="judul" placeholder="Judul" value="{{$dataTypeContent->judul}}">
                            </div>
                            <div class="form-group  col-md-6">
                                <label class="control-label" for="name">Content</label>
                                <textarea class="form-control richTextBox" name="description" id="richtextdescription" aria-hidden="true"> 
                                    {{$dataTypeContent->description}}   
                                </textarea>
                            </div>
                            <div class="form-group  col-md-6">
                                <label class="control-label" for="name">Isi Berita</label>
                                <textarea class="form-control richTextBox" name="deskripsi" id="richtextdeskripsi" aria-hidden="true">    
                                    {{$dataTypeContent->deskripsi}}  
                                </textarea>
                            </div>
                            <div class="form-group  col-md-12">
                                <label class="control-label" for="name">Gambar</label>
                                @if($dataTypeContent->image)
                                <div data-field-name="image">
                                    <a href="#" class="voyager-x remove-single-image" style="position:absolute;"></a>
                                    <img src="{{ url('')}}/storage/{{$dataTypeContent->image}}" data-file-name="{{$dataTypeContent->image}}" data-id="10" style="max-width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                                </div>
                                @endif
                                <input type="file" name="image" accept="image/*">
                            </div>                                                                               
                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <div style="display:none">
                        <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                        <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $(document).ready(function() {
            var additionalConfig = {
                selector: 'textarea.richTextBox[name="description"]',
                toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl"
            }

            $.extend(additionalConfig, {})

            tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
        });
        $(document).ready(function() {
            var additionalConfig = {
                selector: 'textarea.richTextBox[name="deskripsi"]',
                toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl"
            }

            $.extend(additionalConfig, {})

            tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
        });
    </script>
    
@stop
