<div class="form-group {{$col ?? ''}} {{$required ?? ''}}">
    <label>{{$label}}</label>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="{{$name}}" id="{{$name}}"
    @if(!empty($onchange)) onchange="{{$onchange ?? ''}}" @endif>
        <label class="custom-file-label" for="{{$name}}">Choose file</label>
    </div>
</div>