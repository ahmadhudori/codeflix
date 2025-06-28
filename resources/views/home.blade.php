<form action="{{ route('logout') }}" method="POST" id="logout-form">
	{{ csrf_field() }}
</form>
@if (session('device_id'))
	session : <p>{{ session('device_id') }}</p>
@endif
<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">logout</a>