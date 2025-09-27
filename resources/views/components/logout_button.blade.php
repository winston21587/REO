<form action="{{ route('logout') }}" method="POST">
     @csrf 
     <button type="submit">{{ $slot }}</button>
    </form>
