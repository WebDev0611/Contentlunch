@if ($errors->any())
    <div class="alert alert-danger alert-forms" id="formError">
        <p><strong>Oops! We had some errors:</strong>
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </p>
    </div>
@endif