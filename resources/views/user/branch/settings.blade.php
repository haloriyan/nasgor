<form action="{{ route('branch.settings.save') }}" method="POST">
    @csrf
    <button>Submit</button>
</form>