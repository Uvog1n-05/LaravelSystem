<x-layout>
        
    <form class="login_container" action="{{route('login')}}" method="POST">
    <h2>Login Your Account</h2>

    @csrf
    
    <label for="email">Email</label>
    <input type="text"
        name="email"
        
        value="{{ old('email') }}"
        required
        
        >
        <label for="password">Password</label>
    <input 
        type="password"
        name="password"
        required
            >
    <button class="btn" type="submit">Login</button>
        @if ($errors->any())        
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif

</form>
    </x-layout>