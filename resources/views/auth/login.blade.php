<x-layout>
   
        <div class="login_container">
            <h2>Welcome Back</h2>
            
            <form action="{{route('login')}}" method="POST">
                @csrf
                
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
                        placeholder="Enter your password"
                    >
                    <div class="flex items-center justify-between mt-2">
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-500">
                                Forgot your password?
                            </a>
                        </div>
                    </div>
                </div>

                <button class="btn" type="submit">Sign In</button>

                @if ($errors->any())        
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="text-center mt-4 text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('show.register') }}" class="text-blue-600 hover:text-blue-700">
                        Register here
                    </a>
                </div>
            </form>
        </div>
   
</x-layout>