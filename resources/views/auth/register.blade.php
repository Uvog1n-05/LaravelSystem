    <x-layout>
    
        <div class="register_container">
            <h2>Create Your Account</h2>
            
            <form action="{{route('register')}}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input 
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Enter your full name"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="Enter your email"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="Choose a password"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Confirm your password"
                    >
                </div>

                <button type="submit" class="btn">Create Account</button>

                @if ($errors->any())
                    <ul >
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="text-center mt-4 text-gray-600">
                    Already have an account? 
                    <a href="{{ route('show.login') }}" class="text-blue-600 hover:text-blue-700">
                        Login here
                    </a>
                </div>
            </form>
       
    </div>
</x-layout>