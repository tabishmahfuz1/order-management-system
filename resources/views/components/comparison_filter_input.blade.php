<div class="input-group advance_filter"> 
    <div class="input-group-append">
      <select class="rounded-left form-control-sm form-control"
              name="{{ $name }}_comparison"> 
        <option value="lt">&lt;</option>
        <option value="le">&lt;=</option>
        <option value="ge">&gt;=</option>
        <option value="gt">&gt;</option>
        <option value="eq">=</option>
      </select>
    </div>
    <input type="{{ $type ?? 'text' }}" 
            @isset($step) step="{{ $step }}" @endisset 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            class="form-control form-control-sm {{ $input_class ?? '' }}" />
</div>