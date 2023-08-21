@foreach ($objects as $object)
    <div class="card">
        <h4 class="card-title">{{ $object->title }}</h4>
    </div>
@endforeach