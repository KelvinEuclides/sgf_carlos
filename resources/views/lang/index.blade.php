@extends('layouts.app')
@section('page-title')
    {{__('Language')}}
@endsection
@section('action-button')
    @can('manage income')
        <a href="#" data-size="md" data-url="{{ route('create.language') }}" data-ajax-popup="true" data-title="{{__('Create Custom Language')}}" class="btn btn-sm btn-create btn-icon-only rounded-circle ml-4">
            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
        </a>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="language-wrap">
                        <div class="row">

                            <div class="col-lg-9 col-md-9 col-sm-12 language-form-wrap">
                                <div class="language=form">
                                    <div class="tab-content no-padding" id="myTab2Content">
                                        <div class="tab-pane fade show active" id="lang1" role="tabpanel" aria-labelledby="home-tab4">
                                            <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                                @csrf
                                                <div class="row">
                                                    @foreach($arrLabel as $label => $value)
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="example3cols1Input">{{$label}} </label>
                                                                <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="col-lg-12 text-right">
                                                        <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Save Changes')}}</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 language-list-wrap">
                                <div class="language-list">
                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                        @foreach($languages as $lang)
                                            <li class="nav-item">
                                                <a href="{{route('manage.language',[$lang])}}" class="nav-link {{($currantLang == $lang)?'active':''}}">{{Str::upper($lang)}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
