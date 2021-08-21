<div class="form-group">
    {!! Form::customSelect('series', $listSeries, old('series', $seriesId), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('order', trans('plugins/series::series.order'), array('class' => 'control-label')); !!}
    {!! Form::text('order', old('order', $order), ['class' => 'form-control', 'placeholder' => trans('plugins/series::series.order_placeholder')]) !!}
</div>