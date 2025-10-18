    <x-layout>

    <form  action="{{route('register')}}" method="POST">
    <h2>Register</h2>

    @csrf
    <label for="email">Name</label>
    <input 
        type="text"
        name="name"
        value="{{ old('name') }}"
        required
        
        >
         <label for="email">Email</label>
    <input 
        type="text"
        name="email"
        value="{{ old('email') }}"
        required
        
        >
    <label for="password">Password</label>
    <input 
        type="password"
        name="passwprd"
        required
        
        >
         <label for="password_confirmation">Confirm Password</label>
    <input 
        type="password"
        name="passwprd_confirmation"
        required
        
        >


</form>
    </x-layout>