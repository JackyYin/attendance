@if ( $session->has('danger'))
    <div class="alert alert-danger">
        @foreach ($session->get('danger') as $key => $value)
        {!! $value[0] !!}
        <br>
        @endforeach
    </div>
@elseif ( $session->has('success'))
    <div class="alert alert-success">
        @foreach ($session->get('success') as $key => $value)
        {!! $value[0] !!}
        <br>
        @endforeach
    </div>
@elseif ( $session->has('warning'))
    <div class="alert alert-warning">
        @foreach ($session->get('warning') as $key => $value)
        {!! $value[0] !!}
        <br>
        @endforeach
    </div>
@elseif ( $session->has('info'))
    <div class="alert alert-info">
        @foreach ($session->get('info') as $key => $value)
        {!! $value[0] !!}
        <br>
        @endforeach
    </div>
@endif
