@if($layout = config('mongovity.layout'))
    @extends($layout)

    @section(config('mongovity.content_section', 'content'))
        @include('mongovity::partials.content')
    @endsection

    @push(config('mongovity.styles_stack', 'styles'))
        @include('mongovity::partials.styles')
    @endpush

    @push(config('mongovity.scripts_stack', 'scripts'))
        @include('mongovity::partials.scripts')
    @endpush
@else
    @include('mongovity::layouts.standalone')
@endif
