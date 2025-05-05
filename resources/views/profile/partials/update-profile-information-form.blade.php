<section class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow-sm">
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        
        <!-- Profile Picture Section -->
        <div class="text-center mb-8">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden">
                <img src="{{ asset('avatar/' . $user->avatar) }}" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-left">Profile Picture</h3>
                <div class="flex items-center">
                    <label class="btn btn-outline normal-case">
                        Choose File
                        <input type="file" name="avatar" class="hidden" accept="image/jpeg,image/png">
                    </label>
                    <span class="ml-3 text-sm text-gray-500">Accepted formats: JPG, PNG.</span>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">Personal Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        {{ $user->role=="Seller" ? "Business Name" : "First Name" }}
                    </label>
                    <input type="text" name="fname" class="input input-bordered w-full" 
                        placeholder="{{ $user->role=="Seller"?"Business Name":"First Name" }}"
                        value="{{ $user->fname }}" required />
                </div>
                @if($user->role!="Seller")
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                        <input type="text" name="lname" class="input input-bordered w-full" 
                            placeholder="Last Name" value="{{ $user->lname }}" required />
                    </div>
                @endif
            </div>
        </div>

        <!-- Contact Details Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">Contact Details</h3>
            <div class="space-y-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bx bx-envelope"></i>
                    </span>
                    <input type="email" name="email" class="input input-bordered w-full pl-10" 
                        placeholder="Email" value="{{ $user->email }}" required />
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bx bx-map"></i>
                    </span>
                    <input type="text" name="address" class="input input-bordered w-full pl-10" 
                        placeholder="Address" value="{{ $user->address }}" required />
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="bx bx-phone"></i>
                    </span>
                    <input type="tel" name="phone" class="input input-bordered w-full pl-10" 
                        placeholder="Phone Number" value="{{ $user->phone }}" required 
                        maxlength="11" pattern="[0-9]{11}" />
                </div>
            </div>
        </div>

        @if ($user->role == 'Seller')
            <!-- Payment Information for Sellers -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Payment Information</h3>
                <div class="space-y-4">
                    <input type="text" name="gcash_number" class="input input-bordered w-full" 
                        placeholder="GCash Number" value="{{ $user->gcash_number }}" required />
                    <input type="text" name="bank_name" class="input input-bordered w-full" 
                        placeholder="Bank Name" value="{{ $user->bank_name }}" required />
                    <input type="text" name="bank_account_number" class="input input-bordered w-full" 
                        placeholder="Bank Account Number" value="{{ $user->bank_account_number }}" required />
                </div>
            </div>
        @endif

        <button type="submit" class="btn btn-primary w-full text-white">Save</button>
    </form>
</section>
