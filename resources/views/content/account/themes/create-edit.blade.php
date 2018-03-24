@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( $title == 'Edit' )
    {!! Breadcrumbs::render('account/themes/edit', $theme) !!}
@else
    {!! Breadcrumbs::render('account/themes/create') !!}
@endif

<h1>{{ $title }} Theme <small>{{ $theme->name or '' }}</small></h1>

<div class="page-content container-fluid">

    <form action="{{ $action }}" method="post" class="validate labels-right" id="create_edit_theme_form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $theme->id or '' }}">
        {!! Html::hiddenInput(['method' => $method]) !!}

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Theme Name</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $theme->name or '' }}" data-fv-notempty="true" autofocus>
                <div class="form-text text-muted font-13">Give your theme a name.  This is for internal use only.</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Form Title</label>
            <div class="col-sm-9 form-control-static">
                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" name="show_title" id="show_title_yes" value="1" {{ !$theme || $theme->show_title ? 'checked' : '' }}>
                    <label for="show_title_yes">Yes</label>
                </div>
                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" name="show_title" id="show_title_no" value="0" {{ $theme && !$theme->show_title ? 'checked' : '' }}>
                    <label for="show_title_no">No</label>
                </div>
                <div class="form-text text-muted font-13">Do you want to display the form title?</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Logo</label>
            <div class="col-sm-9 form-control-static">

                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-show=".show-logo-wrapper" name="show_logo" id="show_logo_yes" value="1" {{ $theme && $theme->show_logo ? 'checked' : '' }}>
                    <label for="show_logo_yes">Yes</label>
                </div>
                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-hide=".show-logo-wrapper" name="show_logo" id="show_logo_no" value="0" {{ !$theme || !$theme->show_logo ? 'checked' : '' }}>
                    <label for="show_logo_no">No</label>
                </div>
                <div class="form-text text-muted font-13">Do you want to display a logo on the form page?</div>
            </div>
        </div>

        <div class="show-logo-wrapper child-content {{ $theme && $theme->show_logo ? '' : 'display-none' }}">
            <div class="form-group row">
                <label class="col-form-label col-sm-3">Logo Image</label>
                <div class="col-sm-9 form-control-static">
                    @if ( $theme && $theme->logo_image )
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ Storage::url($theme->logo_image) }}" class="w-100">
                            </div>
                            <div class="col-sm-9">
                                <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="delete_logo" id="delete_logo" value="1">
                                    <label for="delete_logo">Delete Logo Image</label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="file" name="logo_image">
                        <div class="form-text text-muted font-13">Upload the logo image you want displayed. Max file size: 2MB.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Background Type</label>
            <div class="col-sm-9 form-control-static">

                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-show=".background-type-color-wrapper" data-hide=".background-type-image-wrapper" name="background_type" id="background_type_color" value="color" {{ !$theme || $theme->background_type == 'color' ? 'checked' : '' }}>
                    <label for="background_type_color">Color</label>
                </div>
                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-show=".background-type-image-wrapper" data-hide=".background-type-color-wrapper" name="background_type" id="background_type_image" value="image" {{ $theme && $theme->background_type == 'image' ? 'checked' : '' }}>
                    <label for="background_type_image">Image</label>
                </div>
                <div class="form-text text-muted font-13">Select the background type you want to use on the form page.</div>
            </div>
        </div>

        <div class="background-type-color-wrapper child-content {{ !$theme || $theme->background_type == 'color' ? '' : 'display-none' }}">
            <div class="form-group row">
                <label class="col-form-label col-sm-3">Background Color</label>
                <div class="col-sm-9">
                    <div class="input-group color-picker colorpicker-component">
                        <span class="input-group-addon"><i></i></span>
                        <input type="text" name="background_color" class="form-control" placeholder="#abc123" value="{{ $theme && $theme->background_color ? $theme->background_color : '#f3f4f5' }}" data-fv-notempty="true">
                    </div>
                    <div class="form-text text-muted font-13">Select (or manually type) the color you want to use for the page background.</div>
                </div>
            </div>
        </div>

        <div class="background-type-image-wrapper child-content {{ $theme && $theme->background_type == 'image' ? '' : 'display-none' }}">
            <div class="form-group row">
                <label class="col-form-label col-sm-3">Background Image</label>
                <div class="col-sm-9 form-control-static">
                    @if ( $theme && $theme->background_image )
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ Storage::url($theme->background_image) }}" class="w-100">
                            </div>
                            <div class="col-sm-9">
                                <div class="abc-checkbox abc-checkbox-primary checkbox-inline">
                                    <input type="checkbox" name="delete_background" id="delete_background" value="1">
                                    <label for="delete_background">Delete Background Image</label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="file" name="background_image">
                        <div class="form-text text-muted font-13">Upload the image to be used for the page background. Max file size: 2MB.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Primary Color</label>
            <div class="col-sm-9">
                <div class="input-group color-picker colorpicker-component">
                    <span class="input-group-addon"><i></i></span>
                    <input type="text" name="primary_color" class="form-control" placeholder="#abc123" value="{{ $theme && $theme->primary_color ? $theme->primary_color : '#2096f3' }}" data-fv-notempty="true">
                </div>
                <div class="form-text text-muted font-13">Select (or manually type) the primary color to be used on the form page.</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Secondary Color</label>
            <div class="col-sm-9">
                <div class="input-group color-picker colorpicker-component">
                    <span class="input-group-addon"><i></i></span>
                    <input type="text" name="secondary_color" class="form-control" placeholder="#abc123" value="{{ $theme && $theme->secondary_color ? $theme->secondary_color : '#212121' }}" data-fv-notempty="true">
                </div>
                <div class="form-text text-muted font-13">Select (or manually type) the secondary color to be used on the form page.</div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3">Custom CSS</label>
            <div class="col-sm-9 form-control-static">

                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-show=".custom-css-wrapper" name="enable_custom_css" id="enable_custom_css_yes" value="1" {{ $theme && $theme->enable_custom_css ? 'checked' : '' }}>
                    <label for="enable_custom_css_yes">Yes</label>
                </div>
                <div class="abc-radio abc-radio-primary radio-inline">
                    <input type="radio" class="toggle-content" data-hide=".custom-css-wrapper" name="enable_custom_css" id="enable_custom_css_no" value="0" {{ !$theme || !$theme->enable_custom_css ? 'checked' : '' }}>
                    <label for="enable_custom_css_no">No</label>
                </div>
                <div class="form-text text-muted font-13">Do you want to enable custom CSS rules? (advanced users)</div>
            </div>
        </div>


        <div class="custom-css-wrapper child-content {{ $theme && $theme->enable_custom_css ? '' : 'display-none' }}">
            <div class="form-group row">
                <label class="col-form-label col-sm-3">CSS Rules</label>
                <div class="col-sm-9">
                    <textarea name="custom_css" class="form-control" rows="3">{{ $theme->custom_css or '' }}</textarea>
                    <div class="form-text text-muted font-13">Enter your CSS rules here.  These will override the default CSS styles on the form page.</div>
                </div>
            </div>
        </div>

        <div class="form-group row mt-4">
            <div class="col-sm-9 ml-auto">
                <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
            </div>
        </div>

    </form>

</div>

@endsection