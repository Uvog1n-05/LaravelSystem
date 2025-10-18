<x-layout>
        
    <form action="{{route('login')}}" method="POST">
    <h2>Login</h2>

    @csrf
    <label for="email">Email</label>
    <input type="text"
        name="email"
        
        value="{{ old('email') }}"
        required
        
        >
        <label for="password">Email</label>
    <input 
        type="password"
        name="passwprd"
        required
            >


</form>
    </x-layout>