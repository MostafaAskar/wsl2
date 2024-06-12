@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.cms_plural')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('cms') !!}">{{trans('lang.cms_plural')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.cms_create')}}</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="error_top" style="display:none"></div>

                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">

                        <fieldset>

                            <legend>{{trans('lang.cms_create')}}</legend>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.cms_name')}}<span
                                            class="required-field"></span></label>
                                <div class="col-7">
                                    <input type="text" class="form-control" id="name">
                                    <div class="form-text text-muted">{{ trans("lang.cms_name_help") }}</div>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.cms_slug')}}<span
                                            class="required-field"></span></label>
                                <div class="col-7">
                                    <input type="text" class="form-control" id="slug">
                                    <div class="form-text text-muted slug-info"></div>
                                    <input type="hidden" id="total_slug" value="0"/>
                                </div>
                            </div>

                            <div class="form-group width-100">
                                <label class="col-3 control-label">{{trans('lang.cms_description')}}<span
                                            class="required-field"></span></label>
                                <div class="col-7">
                                    <textarea class="form-control col-7" name="description" id="description"></textarea>
                                    <div class="form-text text-muted">{{ trans("lang.cms_description_help") }}</div>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <div class="form-check">
                                    <input type="checkbox" class="publish" id="publish">
                                    <label class="col-3 control-label" for="publish">{{trans('lang.status')}}</label>
                                </div>
                            </div>

                        </fieldset>

                    </div>

                </div>
                <div class="form-group col-12 text-center btm-btn">

                    <button type="button" class="btn btn-primary save_cms_btn">
                        <i class="fa fa-save"></i> {{trans('lang.save')}}
                    </button>

                    <a href="{!! route('cms') !!}" class="btn btn-default">
                        <i class="fa fa-undo"></i>{{trans('lang.cancel')}}
                    </a>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    var database = firebase.firestore();
    var id = database.collection("tmp").doc().id;
    var ref = database.collection('cms_pages');

    $('#description').summernote({
        height: 400,
        width: 1000,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['forecolor', ['forecolor']],
            ['backcolor', ['backcolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ]
    });

    $(document).ready(function () {
        $('.cms_page').addClass('active');

        $("#name").keyup(function () {
            var name = $(this).val();
            if (name.trim()) {
                name = name.toLowerCase();
                name = name.replace(/[^a-zA-Z0-9]+/g, '-');
                $("#slug").val(name);
                $(".slug-info").text('http://yoursite.com/' + name);
                checkSlug();
            } else {
                $("#slug").val('');
                $(".slug-info").empty();
            }
        });

        $("#slug").keyup(function () {
            var slug = $(this).val();
            if (slug.trim()) {
                slug = slug.toLowerCase();
                slug = slug.replace(/[^a-zA-Z0-9]+/g, '-');
                $(this).val(slug);
                $(".slug-info").text('http://yoursite.com/' + slug);
                checkSlug();
            } else {
                $(".slug-info").empty();
            }
        });

        $(".save_cms_btn").click(function () {

            var name = $("#name").val();
            var slug = $("#slug").val();
            var total_slug = $("#total_slug").val();
            var description = $('#description').summernote('code');
            var publish = $("#publish").is(":checked");

            $(".error_top").empty();
            if (name == '') {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.cms_name_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (slug == "") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.cms_slug_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (description == "" || description == "<p><br></p>") {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.cms_description_error')}}</p>");
                window.scrollTo(0, 0);
            } else if (total_slug > 0) {
                $(".error_top").show();
                $(".error_top").html("");
                $(".error_top").append("<p>{{trans('lang.cms_slug_exist')}}</p>");
                window.scrollTo(0, 0);
            } else {
                jQuery("#overlay").show();

                database.collection('cms_pages').doc(id).set({
                    'id': id,
                    'name': name,
                    'slug': slug,
                    'description': description,
                    'publish': publish,
                }).then(function (result) {
                    jQuery("#overlay").hide();

                    window.location.href = '{{ route("cms")}}';
                });
            }
        });
    });

    async function checkSlug() {
        var slug = $("#slug").val();
        var pages = await ref.where('slug', '==', slug).get();
        $("#total_slug").val(pages.docs.length)
    }

</script>

@endsection