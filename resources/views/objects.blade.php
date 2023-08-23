@extends('components.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-5">
        <div class="container container--narrow">
            <h2>Фильтр:</h2>
            <div class="container container--narrow">
                <form action="{{ route('objects.index') }}" method="GET" id="filterForm">
                    @foreach($groups as $group)
                        <fieldset>
                            <div class="font-weight-bold">
                                {{$group->title}}:
                            </div>
                            <div class="container container--narrow">
                                @foreach($group->categories as $category)
                                    <div class="form-check">
                                        <input name="{{$group->id}}" class="form-check-input" type="{{ $group->multiple ? 'checkbox' : 'radio' }}" id="{{$category->id}}" value="{{$category->alias}}" {{ in_array($category->id, $sharedData['categoryIds']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{$category->id}}">
                                            {{$category->title}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                </form>
            </div>
        </div>
      </div>
      <div class="col-md-7">
        <h2>Объекты:</h2>
        <div class="objects">
            <div id="objects-container">
                @include('objects_only')
            </div>
        </div>
      </div>
    </div>
    </div>
    <script>
        function getSelectedCategories() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');

            const values = [];
            checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                values.push(checkbox.value);
            }
            });
            return values;
        }

        const form = document.querySelector('#filterForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const selectedCategories = getSelectedCategories();
            fetch(form.action + 'raw' + '?' + new URLSearchParams({ categories: selectedCategories }))
            .then(response => response.json())
            .then(html => {
                const objectsContainer = document.getElementById('objects-container');
                objectsContainer.innerHTML = html.theHTML;
                history.pushState(null, null, form.action + '?' + new URLSearchParams({ categories: selectedCategories }));
            })
            .catch(error => console.error(error));
        });

        window.addEventListener('popstate', function(event) {
            fetch(location.href)
            .then(response => response.json())
            .then(html => {
                const objectsContainer = document.getElementById('objects-container');
                objectsContainer.innerHTML = html.theHTML;
            })
            .catch(error => console.error(error));
        });
    </script>
@endsection