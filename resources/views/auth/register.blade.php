    <x-layout>
      <div class="register_container">
    <form  action="{{route('register')}}" method="POST">
    <h2>Register New Account</h2>

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
        name="password"
        required
        
        >
         <label for="password_confirmation">Confirm Password</label>
    <input 
        type="password"
        name="password_confirmation"
        required
        
        >
   <button type="submit" class="btn mt-4">Register</button>
</div>
    <!-- validation errors -->
    @if ($errors->any())
      <ul class="px-4 py-2 bg-red-100">
        @foreach ($errors->all() as $error)
          <li class="my-2 text-red-500">{{ $error }}</li>
        @endforeach
      </ul>
    @endif
    </x-layout>