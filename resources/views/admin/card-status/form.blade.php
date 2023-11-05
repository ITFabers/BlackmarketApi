<div class="form-check mb-4">
    <input type="checkbox" class="form-check-input" {{ !empty($facilities->show_in_homepage) ? 'checked' : ''}} name="show_in_homepage" id="news-show_in_homepage" placeholder="show_in_homepage">
    <label class="form-check-label" for="news-title">Show in homepage</label>
    @if ($errors->has('show_in_homepage'))
        <span class="text-danger">{{ $errors->first('show_in_homepage') }}</span>
    @endif
</div>
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ isset($facilities->title) ? $facilities->title : ''}}" >
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ 'Description' }}</label>
    <textarea class="form-control" name="description">{{ isset($facilities->description) ? $facilities->description : ''}}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
    <label for="image" class="control-label">{{ 'Image' }}</label>
    <input class="form-control" name="image" type="file" id="image" value="{{ isset($facilities->image) ? $facilities->image : ''}}" >
    {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('alt') ? 'has-error' : ''}}">
    <label for="alt" class="control-label">{{ 'Image alt' }}</label>
    <input class="form-control" name="image_alt" type="text" id="alt" value="{{ isset($facilities->image_alt) ? $facilities->image_alt : ''}}" >
    {!! $errors->first('alt', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('ordering') ? 'has-error' : ''}}">
    <label for="ordering" class="control-label">{{ 'Ordering' }}</label>
    <input class="form-control" name="ordering" type="text" id="ordering" value="{{ isset($facilities->ordering) ? $facilities->ordering : ''}}" >
    {!! $errors->first('ordering', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
