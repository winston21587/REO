<x-user_layout>

<h1>home for users</h1>
    <h2>Welcome, {{ Auth::user()->name }}!</h2>
<p>Your email: {{ Auth::user()->email }}</p>
<x-first_time_popup/>
</x-user_layout>